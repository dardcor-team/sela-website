<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            ALTER TABLE "public"."profiles" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."groups" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."group_members" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."tasks" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."subtasks" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."subtask_progress" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."task_links" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."task_files" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."courses" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."classes" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."lecturer_classes" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."profile_abilities" ENABLE ROW LEVEL SECURITY;
            ALTER TABLE "public"."notifications" ENABLE ROW LEVEL SECURITY;

            CREATE OR REPLACE FUNCTION public.is_group_member(p_group_id uuid, p_user_id uuid)
            RETURNS boolean
            LANGUAGE sql
            STABLE
            SECURITY DEFINER
            SET search_path = public
            AS $$
                SELECT EXISTS (
                    SELECT 1 FROM public.group_members
                    WHERE group_id = p_group_id
                      AND user_id = p_user_id
                );
            $$;

            CREATE OR REPLACE FUNCTION public.find_group_by_invite_code(p_code text)
            RETURNS TABLE (
                id uuid,
                name text,
                course_name text,
                class_name text,
                group_number integer,
                member_limit integer,
                invitation_code text,
                lecture_code text,
                created_by uuid,
                created_at timestamp with time zone
            )
            LANGUAGE sql
            STABLE
            SECURITY DEFINER
            SET search_path = public
            AS $$
                SELECT id, name, course_name, class_name, group_number, member_limit,
                       invitation_code, lecture_code, created_by, created_at
                FROM public.groups
                WHERE invitation_code = p_code
                LIMIT 1;
            $$;

            DO $$
            BEGIN
                DROP POLICY IF EXISTS "Public profiles are viewable by everyone" ON profiles;
                CREATE POLICY "Public profiles are viewable by everyone"
                    ON profiles FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Anyone can view courses" ON courses;
                CREATE POLICY "Anyone can view courses"
                    ON courses FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Anyone can view classes" ON classes;
                CREATE POLICY "Anyone can view classes"
                    ON classes FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Anyone can view lecturer classes" ON lecturer_classes;
                CREATE POLICY "Anyone can view lecturer classes"
                    ON lecturer_classes FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Lecturers can manage their classes" ON lecturer_classes;
                CREATE POLICY "Lecturers can manage their classes"
                    ON lecturer_classes FOR ALL USING (auth.uid() = lecturer_id);

                DROP POLICY IF EXISTS "Users can update own profile" ON profiles;
                CREATE POLICY "Users can update own profile"
                    ON profiles FOR UPDATE USING (auth.uid() = id);

                DROP POLICY IF EXISTS "Users can insert own profile" ON profiles;
                CREATE POLICY "Users can insert own profile"
                    ON profiles FOR INSERT WITH CHECK (auth.uid() = id);

                DROP POLICY IF EXISTS "Anyone can view groups" ON groups;
                DROP POLICY IF EXISTS "Users can view groups they are in" ON groups;
                CREATE POLICY "Users can view groups they are in"
                    ON groups FOR SELECT USING (
                        auth.uid() = created_by
                        OR public.is_group_member(id, auth.uid())
                    );

                DROP POLICY IF EXISTS "Users can create groups" ON groups;
                CREATE POLICY "Users can create groups"
                    ON groups FOR INSERT WITH CHECK (auth.uid() = created_by);

                DROP POLICY IF EXISTS "Users can update own group" ON groups;
                CREATE POLICY "Users can update own group"
                    ON groups FOR UPDATE USING (auth.uid() = created_by);

                DROP POLICY IF EXISTS "Creators or leaders can delete groups" ON groups;
                CREATE POLICY "Creators or leaders can delete groups"
                    ON groups FOR DELETE USING (
                        auth.uid() = created_by
                        OR EXISTS (
                            SELECT 1 FROM public.group_members
                            WHERE group_id = groups.id
                              AND user_id = auth.uid()
                              AND role = \'leader\'
                        )
                    );

                DROP POLICY IF EXISTS "Anyone can view member list" ON group_members;
                DROP POLICY IF EXISTS "Members can view group members" ON group_members;
                CREATE POLICY "Members can view group members"
                    ON group_members FOR SELECT USING (
                        auth.uid() = user_id
                        OR public.is_group_member(group_id, auth.uid())
                    );

                DROP POLICY IF EXISTS "Users can join groups" ON group_members;
                CREATE POLICY "Users can join groups"
                    ON group_members FOR INSERT WITH CHECK (auth.uid() = user_id);

                DROP POLICY IF EXISTS "Users can leave groups" ON group_members;
                CREATE POLICY "Users can leave groups"
                    ON group_members FOR DELETE USING (auth.uid() = user_id);

                DROP POLICY IF EXISTS "Users can view their tasks" ON tasks;
                CREATE POLICY "Users can view their tasks"
                    ON tasks FOR SELECT USING (
                        auth.uid() = created_by
                        OR (
                            is_group = true
                            AND group_id IS NOT NULL
                            AND public.is_group_member(group_id, auth.uid())
                        )
                    );

                DROP POLICY IF EXISTS "Users can create tasks" ON tasks;
                CREATE POLICY "Users can create tasks"
                    ON tasks FOR INSERT WITH CHECK (auth.uid() = created_by);

                DROP POLICY IF EXISTS "Users can update their tasks" ON tasks;
                CREATE POLICY "Users can update their tasks"
                    ON tasks FOR UPDATE USING (
                        auth.uid() = created_by
                        OR (
                            is_group = true
                            AND group_id IS NOT NULL
                            AND public.is_group_member(group_id, auth.uid())
                        )
                    );

                DROP POLICY IF EXISTS "Users can delete their tasks" ON tasks;
                CREATE POLICY "Users can delete their tasks"
                    ON tasks FOR DELETE USING (
                        auth.uid() = created_by
                        OR (
                            is_group = true
                            AND group_id IS NOT NULL
                            AND public.is_group_member(group_id, auth.uid())
                        )
                    );

                DROP POLICY IF EXISTS "Anyone can view subtasks" ON subtasks;
                CREATE POLICY "Anyone can view subtasks"
                    ON subtasks FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Creators or Leaders can manage subtasks" ON subtasks;

                DROP POLICY IF EXISTS "Leaders can insert subtasks" ON subtasks;
                CREATE POLICY "Leaders can insert subtasks"
                    ON subtasks FOR INSERT WITH CHECK (
                        EXISTS (
                            SELECT 1 FROM tasks
                            WHERE tasks.id = subtasks.task_id
                              AND (
                                  (tasks.is_group = false AND tasks.created_by = auth.uid())
                                  OR
                                  (tasks.is_group = true AND EXISTS (
                                      SELECT 1 FROM group_members
                                      WHERE group_id = tasks.group_id
                                        AND user_id = auth.uid()
                                        AND role = \'leader\'
                                  ))
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Leaders can update delete subtasks" ON subtasks;
                CREATE POLICY "Leaders can update delete subtasks"
                    ON subtasks FOR ALL USING (
                        EXISTS (
                            SELECT 1 FROM tasks
                            WHERE tasks.id = subtasks.task_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR EXISTS (
                                      SELECT 1 FROM group_members
                                      WHERE group_id = tasks.group_id
                                        AND user_id = auth.uid()
                                        AND role = \'leader\'
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Users can view subtask progress" ON subtask_progress;
                CREATE POLICY "Users can view subtask progress"
                    ON subtask_progress FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Users can update own progress or leaders can manage" ON subtask_progress;

                DROP POLICY IF EXISTS "Leaders insert subtask progress" ON subtask_progress;
                CREATE POLICY "Leaders insert subtask progress"
                    ON subtask_progress FOR INSERT WITH CHECK (
                        auth.uid() = user_id
                        OR EXISTS (
                            SELECT 1 FROM subtasks
                            JOIN tasks ON tasks.id = subtasks.task_id
                            WHERE subtasks.id = subtask_progress.subtask_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR EXISTS (
                                      SELECT 1 FROM group_members
                                      WHERE group_id = tasks.group_id
                                        AND user_id = auth.uid()
                                        AND role = \'leader\'
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Users update own progress leaders update all" ON subtask_progress;
                CREATE POLICY "Users update own progress leaders update all"
                    ON subtask_progress FOR UPDATE USING (
                        auth.uid() = user_id
                        OR EXISTS (
                            SELECT 1 FROM subtasks
                            JOIN tasks ON tasks.id = subtasks.task_id
                            WHERE subtasks.id = subtask_progress.subtask_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR EXISTS (
                                      SELECT 1 FROM group_members
                                      WHERE group_id = tasks.group_id
                                        AND user_id = auth.uid()
                                        AND role = \'leader\'
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Anyone can view task links" ON task_links;
                CREATE POLICY "Anyone can view task links"
                    ON task_links FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Creators can manage task links" ON task_links;
                DROP POLICY IF EXISTS "Members can manage task links" ON task_links;
                CREATE POLICY "Members can manage task links"
                    ON task_links FOR ALL USING (
                        EXISTS (
                            SELECT 1 FROM tasks
                            WHERE tasks.id = task_links.task_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR (
                                      tasks.is_group = true 
                                      AND tasks.group_id IS NOT NULL
                                      AND public.is_group_member(tasks.group_id, auth.uid())
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Anyone can view task files" ON task_files;
                CREATE POLICY "Anyone can view task files"
                    ON task_files FOR SELECT USING (
                        EXISTS (
                            SELECT 1 FROM tasks
                            WHERE tasks.id = task_files.task_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR (
                                      tasks.is_group = true
                                      AND tasks.group_id IS NOT NULL
                                      AND public.is_group_member(tasks.group_id, auth.uid())
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Creators can insert task files" ON task_files;
                DROP POLICY IF EXISTS "Members can insert task files" ON task_files;
                CREATE POLICY "Members can insert task files"
                    ON task_files FOR INSERT WITH CHECK (
                        auth.uid() = uploaded_by
                        AND EXISTS (
                            SELECT 1 FROM tasks
                            WHERE tasks.id = task_files.task_id
                              AND (
                                  tasks.created_by = auth.uid()
                                  OR (
                                      tasks.is_group = true
                                      AND tasks.group_id IS NOT NULL
                                      AND public.is_group_member(tasks.group_id, auth.uid())
                                  )
                              )
                        )
                    );

                DROP POLICY IF EXISTS "Anyone can view profile abilities" ON profile_abilities;
                CREATE POLICY "Anyone can view profile abilities"
                    ON profile_abilities FOR SELECT USING (true);

                DROP POLICY IF EXISTS "Users can manage own abilities" ON profile_abilities;
                CREATE POLICY "Users can manage own abilities"
                    ON profile_abilities FOR ALL USING (auth.uid() = user_id);

                DROP POLICY IF EXISTS "Users can view own notifications" ON notifications;
                CREATE POLICY "Users can view own notifications"
                    ON notifications FOR SELECT USING (auth.uid() = user_id);

                DROP POLICY IF EXISTS "Users can update own notifications" ON notifications;
                CREATE POLICY "Users can update own notifications"
                    ON notifications FOR UPDATE USING (auth.uid() = user_id);

                DROP POLICY IF EXISTS "Users can delete own notifications" ON notifications;
                CREATE POLICY "Users can delete own notifications"
                    ON notifications FOR DELETE USING (auth.uid() = user_id);

                DROP POLICY IF EXISTS "System can insert notifications" ON notifications;
                CREATE POLICY "System can insert notifications"
                    ON notifications FOR INSERT WITH CHECK (true);

            END $$;

            CREATE OR REPLACE FUNCTION public.handle_new_user()
            RETURNS trigger AS $$
            BEGIN
              IF TG_TABLE_SCHEMA = \'auth\' THEN
                INSERT INTO public.profiles (id, username, full_name, class_name)
                VALUES (
                  new.id, 
                  LOWER(COALESCE(new.raw_user_meta_data->>\'username\', SPLIT_PART(new.email, \'@\', 1))),
                  COALESCE(new.raw_user_meta_data->>\'full_name\', new.raw_user_meta_data->>\'username\', SPLIT_PART(new.email, \'@\', 1)),
                  COALESCE(new.raw_user_meta_data->>\'class_name\', \'\')
                )
                ON CONFLICT (id) DO UPDATE SET
                  username = EXCLUDED.username,
                  full_name = EXCLUDED.full_name,
                  class_name = EXCLUDED.class_name,
                  updated_at = now();
              END IF;
              RETURN new;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;

            DROP TRIGGER IF EXISTS on_auth_user_created ON auth.users;
            CREATE TRIGGER on_auth_user_created
              AFTER INSERT ON auth.users
              FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

            CREATE OR REPLACE FUNCTION public.notify_task_created()
            RETURNS trigger AS $$
            DECLARE
                v_member RECORD;
                v_title  text;
                v_msg    text;
            BEGIN
                v_title := CASE WHEN new.is_group THEN \'Group Task\' ELSE \'Individual Task\' END;
                v_msg   := \'Task "\' || new.title || \'" berhasil dibuat\';

                INSERT INTO public.notifications (user_id, title, message, type, related_id)
                VALUES (new.created_by, v_title, v_msg, \'task\', new.id);

                IF new.is_group = true AND new.group_id IS NOT NULL THEN
                    FOR v_member IN
                        SELECT user_id FROM public.group_members
                        WHERE group_id = new.group_id
                          AND user_id <> new.created_by
                    LOOP
                        INSERT INTO public.notifications (user_id, title, message, type, related_id)
                        VALUES (
                            v_member.user_id,
                            v_title,
                            \'Task baru "\' || new.title || \'" ditambahkan ke grup kamu\',
                            \'task\',
                            new.id
                        );
                    END LOOP;
                END IF;

                RETURN new;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;

            DROP TRIGGER IF EXISTS tr_notify_task_created ON tasks;
            CREATE TRIGGER tr_notify_task_created
                AFTER INSERT ON tasks
                FOR EACH ROW EXECUTE FUNCTION public.notify_task_created();

            CREATE OR REPLACE FUNCTION public.notify_group_joined()
            RETURNS trigger AS $$
            DECLARE
                v_group_name text;
                v_member     RECORD;
            BEGIN
                SELECT name INTO v_group_name FROM public.groups WHERE id = new.group_id;

                INSERT INTO public.notifications (user_id, title, message, type, related_id)
                VALUES (
                    new.user_id,
                    \'Bergabung ke Grup\',
                    \'Kamu telah bergabung ke grup "\' || COALESCE(v_group_name, \'grup\') || \'"\',
                    \'group\',
                    new.group_id
                );

                FOR v_member IN
                    SELECT user_id FROM public.group_members
                    WHERE group_id = new.group_id
                      AND user_id <> new.user_id
                LOOP
                    INSERT INTO public.notifications (user_id, title, message, type, related_id)
                    VALUES (
                        v_member.user_id,
                        \'Anggota Baru\',
                        \'Ada anggota baru yang bergabung ke grup "\' || COALESCE(v_group_name, \'grup\') || \'"\',
                        \'group\',
                        new.group_id
                    );
                END LOOP;

                RETURN new;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;

            DROP TRIGGER IF EXISTS tr_notify_group_joined ON group_members;
            CREATE TRIGGER tr_notify_group_joined
                AFTER INSERT ON group_members
                FOR EACH ROW EXECUTE FUNCTION public.notify_group_joined();

            CREATE OR REPLACE FUNCTION public.notify_task_status_changed()
            RETURNS trigger AS $$
            DECLARE
                v_member RECORD;
                v_msg    text;
            BEGIN
                IF OLD.status = NEW.status THEN
                    RETURN NEW;
                END IF;

                v_msg := \'Task "\' || NEW.title || \'" diperbarui menjadi \' || NEW.status;

                INSERT INTO public.notifications (user_id, title, message, type, related_id)
                VALUES (NEW.created_by, \'Update Task\', v_msg, \'task\', NEW.id);

                IF NEW.is_group = true AND NEW.group_id IS NOT NULL THEN
                    FOR v_member IN
                        SELECT user_id FROM public.group_members
                        WHERE group_id = NEW.group_id
                          AND user_id <> NEW.created_by
                LOOP
                    INSERT INTO public.notifications (user_id, title, message, type, related_id)
                    VALUES (v_member.user_id, \'Update Task\', v_msg, \'task\', NEW.id);
                    END LOOP;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;

            DROP TRIGGER IF EXISTS tr_notify_task_status_changed ON tasks;
            CREATE TRIGGER tr_notify_task_status_changed
                AFTER UPDATE OF status ON tasks
                FOR EACH ROW EXECUTE FUNCTION public.notify_task_status_changed();

            CREATE OR REPLACE FUNCTION public.notify_subtask_assigned()
            RETURNS trigger AS $$
            DECLARE
                v_subtask_title text;
                v_task_title text;
                v_task_id uuid;
            BEGIN
                SELECT s.title, t.title, t.id
                INTO v_subtask_title, v_task_title, v_task_id
                FROM public.subtasks s
                JOIN public.tasks t ON t.id = s.task_id
                WHERE s.id = new.subtask_id;

                IF auth.uid() IS NOT NULL AND new.user_id <> auth.uid() THEN
                    INSERT INTO public.notifications (user_id, title, message, type, related_id)
                    VALUES (
                        new.user_id,
                        \'Tugas Baru Diberikan\',
                        \'Kamu ditugaskan pada subtask "\' || COALESCE(v_subtask_title, \'\') || \'" di task "\' || COALESCE(v_task_title, \'\') || \'"\',
                        \'task\',
                        v_task_id
                    );
                END IF;

                RETURN new;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;

            DROP TRIGGER IF EXISTS tr_notify_subtask_assigned ON subtask_progress;
            CREATE TRIGGER tr_notify_subtask_assigned
                AFTER INSERT ON subtask_progress
                FOR EACH ROW EXECUTE FUNCTION public.notify_subtask_assigned();

            ALTER TABLE public.profiles REPLICA IDENTITY FULL;
            ALTER TABLE public.groups REPLICA IDENTITY FULL;
            ALTER TABLE public.tasks REPLICA IDENTITY FULL;
            ALTER TABLE public.subtasks REPLICA IDENTITY FULL;
            ALTER TABLE public.subtask_progress REPLICA IDENTITY FULL;
            ALTER TABLE public.group_members REPLICA IDENTITY FULL;
            ALTER TABLE public.notifications REPLICA IDENTITY FULL;

            DO $$
            BEGIN
              IF NOT EXISTS (SELECT 1 FROM pg_publication WHERE pubname = \'supabase_realtime\') THEN
                CREATE PUBLICATION supabase_realtime;
              END IF;
            END $$;

            ALTER PUBLICATION supabase_realtime SET TABLE 
                public.profiles,
                public.groups,
                public.group_members,
                public.tasks,
                public.subtasks,
                public.subtask_progress,
                public.notifications,
                public.profile_abilities,
                public.task_links,
                public.task_files;
        ');
    }

    public function down(): void
    {
    }
};

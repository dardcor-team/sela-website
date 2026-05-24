<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared("
            CREATE OR REPLACE FUNCTION public.notify_task_created()
            RETURNS trigger AS \$$
            DECLARE
                v_member RECORD;
                v_title  text;
                v_msg    text;
            BEGIN
                -- Diubah ke Bahasa Indonesia
                v_title := CASE WHEN new.is_group THEN 'Tugas Grup' ELSE 'Tugas Individu' END;
                v_msg   := 'Task \"' || new.title || '\" berhasil dibuat';

                INSERT INTO public.notifications (user_id, title, message, type, related_id)
                VALUES (new.created_by, v_title, v_msg, 'task', new.id);

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
                            'Tugas baru \"' || new.title || '\" ditambahkan ke grup kamu',
                            'task',
                            new.id
                        );
                    END LOOP;
                END IF;

                RETURN new;
            END;
            \$$ LANGUAGE plpgsql SECURITY DEFINER SET search_path = public;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

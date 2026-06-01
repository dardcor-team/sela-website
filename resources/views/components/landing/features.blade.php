<section id="features" class="py-[120px] max-[768px]:py-[60px] bg-black text-white border-t-[8px] border-black rounded-t-[60px] max-[480px]:rounded-t-[36px] -mt-[60px] max-[480px]:-mt-[36px] relative z-20 bg-[linear-gradient(to_right,rgba(8,131,149,0.1)_1px,transparent_1px),linear-gradient(to_bottom,rgba(8,131,149,0.1)_1px,transparent_1px)] bg-[size:50px_50px]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="text-center mb-[80px] max-[768px]:mb-[48px] reveal">
            <div class="inline-block text-[0.78rem] font-bold uppercase tracking-[2px] text-cyan mb-[16px]">Fitur Unggulan</div>
            <h2 class="text-[3.2rem] max-[768px]:text-[2.4rem] max-[480px]:text-[1.7rem] mb-[20px] text-white">Didesain untuk<br>Kolaborasi Nyata.</h2>
            <p class="text-[1.1rem] max-[480px]:text-[0.95rem] max-w-[680px] mx-auto leading-[1.7] text-[#ccc]">Semua fitur di bawah ini saling terhubung. Coba simulasi interaktifnya dari langkah pertama hingga selesai!</p>
        </div>

        <div class="flex flex-col gap-[100px] max-[768px]:gap-[60px]">

            <!-- FEATURE 1: AI GENERATOR -->
            <div class="grid grid-cols-2 max-[992px]:grid-cols-1 gap-[60px] max-[768px]:gap-[32px] items-center reveal">
                <div>
                    <div class="inline-flex items-center gap-[8px] bg-[#09637e]/20 border-[1.5px] border-cyan text-cyan text-[0.75rem] font-bold uppercase tracking-[1.5px] py-[6px] px-[14px] rounded-full mb-[20px]"><span class="w-[6px] h-[6px] bg-cyan rounded-full animate-pulse"></span>Langkah 01</div>
                    <h3 class="text-[2.2rem] max-[768px]:text-[1.6rem] max-[480px]:text-[1.35rem] text-white mb-[18px] leading-[1.15]">Generate Sub-Tugas (AI)</h3>
                    <p class="text-[1.05rem] max-[480px]:text-[0.92rem] text-[#aaa] leading-[1.75] mb-[28px]">Input tugas besar dari dosen, dan biarkan AI memecahnya menjadi langkah-langkah kecil yang siap dieksekusi.</p>
                    <div class="flex items-start gap-[12px]"><div class="w-[20px] h-[20px] rounded-full bg-cyan shrink-0 mt-[2px] flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:bg-white after:rounded-full"></div><span class="text-[0.95rem] text-[#ccc] leading-[1.6]">Cobalah ketik tugas di kotak simulasi sebelah kanan.</span></div>
                </div>
                
                <div class="relative w-full flex justify-center items-center">
                    <div class="absolute w-[250px] h-[250px] bg-cyan rounded-full blur-[70px] opacity-20"></div>
                    <div class="w-full max-w-[380px] bg-white rounded-[24px] border-[3px] border-black shadow-[8px_8px_0px_#13889B] relative z-[2] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[12px_12px_0px_#13889B]">
                        
                        <h4 class="text-black font-black text-base mb-4 flex items-center gap-2.5">
                            <span class="bg-[#13889B] p-1.5 rounded-lg text-white shadow-[2px_2px_0px_#000]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </span>
                            Demo AI: Pemecah Tugas
                        </h4>
                        
                        <div class="flex flex-col gap-2 mb-4">
                            <label class="text-xs font-bold text-gray-700">Judul Tugas Kelompok</label>
                            <div class="flex gap-2">
                                <input type="text" id="demo1-input" class="w-full bg-gray-50 border-2 border-gray-300 focus:border-[#13889B] focus:ring-0 rounded-xl px-3 py-2.5 text-sm text-black font-medium transition" placeholder="Misal: Buat Website E-Commerce" value="Buat Project Aplikasi Flutter">
                                <button id="demo1-btn" class="bg-[#13889B] border-2 border-black text-white px-4 py-2.5 rounded-xl text-sm font-black whitespace-nowrap hover:bg-black transition shadow-[3px_3px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none active:scale-95">Generate</button>
                            </div>
                        </div>
                        
                        <div id="demo1-loading" class="hidden py-6 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-[3px] border-gray-200 border-b-[#13889B] mb-2"></div>
                            <p class="text-xs font-bold text-[#13889B] animate-pulse">AI sedang memikirkan langkah terbaik...</p>
                        </div>

                        <div id="demo1-result" class="space-y-2 opacity-50 pointer-events-none transition-opacity duration-500">
                            <div class="p-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center text-center">
                                <svg style="width: 40px; height: 40px;" class="text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p class="text-xs font-bold text-gray-400">Tugas yang di-generate<br>akan muncul di sini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FEATURE 2: DISTRIBUTION -->
            <div class="grid grid-cols-2 max-[992px]:grid-cols-1 gap-[60px] max-[768px]:gap-[32px] items-center [direction:rtl] [&>*]:[direction:ltr] max-[992px]:[direction:ltr] reveal">
                <div>
                    <div class="inline-flex items-center gap-[8px] bg-[#09637e]/20 border-[1.5px] border-cyan text-cyan text-[0.75rem] font-bold uppercase tracking-[1.5px] py-[6px] px-[14px] rounded-full mb-[20px]"><span class="w-[6px] h-[6px] bg-cyan rounded-full animate-pulse"></span>Langkah 02</div>
                    <h3 class="text-[2.2rem] max-[768px]:text-[1.6rem] max-[480px]:text-[1.35rem] text-white mb-[18px] leading-[1.15]">Bagi Tugas Berkeadilan</h3>
                    <p class="text-[1.05rem] max-[480px]:text-[0.92rem] text-[#aaa] leading-[1.75] mb-[28px]">Tugas yang sudah dipecah tadi kini dibagikan secara adil berdasarkan spesialisasi masing-masing anggota. Ceklis tugas untuk melihat dampaknya!</p>
                </div>
                <div class="relative w-full flex justify-center items-center">
                    <div class="absolute w-[250px] h-[250px] bg-green-500 rounded-full blur-[70px] opacity-10"></div>
                    <div class="w-full max-w-[380px] bg-white rounded-[24px] border-[3px] border-black shadow-[8px_8px_0px_#16a34a] relative z-[2] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[12px_12px_0px_#16a34a]">
                        
                        <h4 class="text-black font-black text-base mb-4 flex items-center gap-2.5">
                            <span class="bg-[#16a34a] p-1.5 rounded-lg text-white shadow-[2px_2px_0px_#000]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </span>
                            Demo Eksekusi Anggota
                        </h4>
                        
                        <div id="demo2-empty" class="p-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center text-center">
                            <svg style="width: 40px; height: 40px;" class="text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="text-xs font-bold text-gray-400">Menunggu tugas di-generate<br>dari Langkah 01...</p>
                        </div>

                        <div id="demo2-content" class="hidden">
                            <button id="demo2-distribute-btn" class="w-full bg-white border-[2px] border-[#16a34a] text-[#16a34a] py-3 rounded-xl text-sm font-black hover:bg-[#16a34a] hover:text-white transition-all shadow-[3px_3px_0px_#16a34a] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none mb-4">
                                ⚡ Bagikan Tugas dengan AI
                            </button>
                            
                            <div id="demo2-members" class="space-y-3 max-h-[260px] overflow-y-auto pr-2 custom-scroll">
                                <!-- Checkboxes will be injected here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FEATURE 3: ANALYTICS -->
            <div class="grid grid-cols-2 max-[992px]:grid-cols-1 gap-[60px] max-[768px]:gap-[32px] items-center reveal">
                <div>
                    <div class="inline-flex items-center gap-[8px] bg-[#09637e]/20 border-[1.5px] border-cyan text-cyan text-[0.75rem] font-bold uppercase tracking-[1.5px] py-[6px] px-[14px] rounded-full mb-[20px]"><span class="w-[6px] h-[6px] bg-cyan rounded-full animate-pulse"></span>Langkah 03</div>
                    <h3 class="text-[2.2rem] max-[768px]:text-[1.6rem] max-[480px]:text-[1.35rem] text-white mb-[18px] leading-[1.15]">Pantauan Dosen Otomatis</h3>
                    <p class="text-[1.05rem] max-[480px]:text-[0.92rem] text-[#aaa] leading-[1.75] mb-[28px]">Setiap kotak yang Anda ceklis di Langkah 02 akan secara *real-time* mengubah grafik kontribusi di *dashboard* dosen ini.</p>
                </div>
                <div class="relative w-full flex justify-center items-center">
                    <div class="absolute w-[250px] h-[250px] bg-purple-500 rounded-full blur-[70px] opacity-20"></div>
                    <div class="w-full max-w-[380px] bg-white rounded-[24px] border-[3px] border-black shadow-[8px_8px_0px_#7c3aed] relative z-[2] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[12px_12px_0px_#7c3aed]">
                        
                        <h4 class="text-black font-black text-base mb-4 flex items-center gap-2.5">
                            <span class="bg-[#7c3aed] p-1.5 rounded-lg text-white shadow-[2px_2px_0px_#000]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </span>
                            Demo Analytics Dosen
                        </h4>
                        
                        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200 mb-4 relative overflow-hidden">
                            <div class="flex justify-between items-end mb-2 relative z-10">
                                <div class="text-xs font-black text-gray-500 uppercase tracking-widest">Total Progres</div>
                                <span class="text-4xl font-black text-[#7c3aed] font-mono transition-all duration-300" id="demo3-total-text">0%</span>
                            </div>
                            <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden shadow-inner relative z-10">
                                <div class="h-full bg-gradient-to-r from-[#7c3aed] to-[#a855f7] transition-all duration-700 ease-out w-[0%] relative" id="demo3-total-bar">
                                    <div class="absolute inset-0 bg-white/20 w-full animate-[slide-right_2s_linear_infinite]"></div>
                                </div>
                            </div>
                        </div>

                        <div class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Kontribusi Individu</div>
                        <div class="space-y-2.5" id="demo3-members-list">
                            <!-- Andi -->
                            <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 border border-blue-200">A</div>
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-800 font-bold">Andi</span> <span class="text-blue-600 font-black font-mono" id="demo3-text-a">0%</span></div>
                                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 transition-all duration-500 w-[0%]" id="demo3-bar-a"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Budi -->
                            <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center font-black text-xs shrink-0 border border-green-200">B</div>
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-800 font-bold">Budi</span> <span class="text-green-600 font-black font-mono" id="demo3-text-b">0%</span></div>
                                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 transition-all duration-500 w-[0%]" id="demo3-bar-b"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Citra -->
                            <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs shrink-0 border border-orange-200">C</div>
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs mb-1.5"><span class="text-gray-800 font-bold">Citra</span> <span class="text-orange-600 font-black font-mono" id="demo3-text-c">0%</span></div>
                                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-500 transition-all duration-500 w-[0%]" id="demo3-bar-c"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Logic JavaScript untuk menghubungkan ketiga fitur -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // STATE
        let tasks = [];
        const members = [
            { id: 'a', name: 'Andi', role: 'UI/UX', colorBadge: 'bg-blue-100 text-blue-600 border-blue-200', colorBar: 'bg-blue-500', colorCheck: 'accent-blue-500' },
            { id: 'b', name: 'Budi', role: 'Mobile Dev', colorBadge: 'bg-green-100 text-green-600 border-green-200', colorBar: 'bg-green-500', colorCheck: 'accent-green-500' },
            { id: 'c', name: 'Citra', role: 'Penulis', colorBadge: 'bg-orange-100 text-orange-600 border-orange-200', colorBar: 'bg-orange-500', colorCheck: 'accent-orange-500' },
        ];

        // L1 Elements
        const d1Btn = document.getElementById('demo1-btn');
        const d1Input = document.getElementById('demo1-input');
        const d1Loading = document.getElementById('demo1-loading');
        const d1Result = document.getElementById('demo1-result');

        // L2 Elements
        const d2Empty = document.getElementById('demo2-empty');
        const d2Content = document.getElementById('demo2-content');
        const d2DistBtn = document.getElementById('demo2-distribute-btn');
        const d2Members = document.getElementById('demo2-members');

        // L1: Generate AI
        d1Btn.addEventListener('click', () => {
            if(!d1Input.value) return;
            
            d1Btn.disabled = true;
            d1Btn.classList.add('opacity-50', 'cursor-not-allowed', 'shadow-none', 'translate-y-0.5', 'translate-x-0.5');
            d1Result.classList.add('hidden');
            d1Loading.classList.remove('hidden');

            // Reset L2 & L3 State
            tasks = [];
            d2Content.classList.add('hidden');
            d2Empty.classList.remove('hidden');
            d2DistBtn.classList.remove('hidden');
            d2Members.innerHTML = '';
            window.updateProgress();

            setTimeout(() => {
                // Mock Generated Tasks
                tasks = [
                    { id: 1, title: 'Buat Wireframe UI', assignee: 'a', done: false },
                    { id: 2, title: 'Buat Prototype app', assignee: 'a', done: false },
                    { id: 3, title: 'Setup project Flutter', assignee: 'b', done: false },
                    { id: 4, title: 'Slicing UI ke kode', assignee: 'b', done: false },
                    { id: 5, title: 'Buat Bab Pendahuluan', assignee: 'c', done: false },
                    { id: 6, title: 'Menyusun Laporan', assignee: 'c', done: false },
                ];

                d1Loading.classList.add('hidden');
                
                // Render list in L1
                d1Result.innerHTML = tasks.map((t, index) => `
                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border-2 border-gray-100 shadow-sm opacity-0 animate-[fade-in_0.3s_ease-out_forwards]" style="animation-delay: ${index * 150}ms">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#13889B] shadow-[0_0_6px_#13889B]"></div>
                        <span class="text-sm font-bold text-gray-800">${t.title}</span>
                    </div>
                `).join('');
                
                d1Result.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
                
                // Unlock L2
                d2Empty.classList.add('hidden');
                d2Content.classList.remove('hidden');
                
                d1Btn.disabled = false;
                d1Btn.classList.remove('opacity-50', 'cursor-not-allowed', 'shadow-none', 'translate-y-0.5', 'translate-x-0.5');
                d1Btn.innerText = 'Generate Ulang';
            }, 1500);
        });

        // L2: Distribute Tasks
        d2DistBtn.addEventListener('click', () => {
            d2DistBtn.classList.add('hidden'); // Hide button after distribute
            
            let html = '';
            members.forEach((m, mIndex) => {
                const memberTasks = tasks.filter(t => t.assignee === m.id);
                html += `
                    <div class="bg-gray-50 p-3.5 rounded-2xl border-2 border-gray-200 mb-3 opacity-0 animate-[fade-in_0.4s_ease-out_forwards]" style="animation-delay: ${mIndex * 200}ms">
                        <div class="flex items-center gap-3 mb-3 pb-2.5 border-b-2 border-gray-200">
                            <div class="w-8 h-8 rounded-lg ${m.colorBadge} flex items-center justify-center text-base font-black border-2">${m.name[0]}</div>
                            <div>
                                <div class="text-sm font-black text-gray-800 leading-tight">${m.name}</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">${m.role}</div>
                            </div>
                        </div>
                        <div class="space-y-2.5">
                            ${memberTasks.map((t, tIndex) => `
                                <label class="flex items-start gap-2.5 cursor-pointer group hover:bg-white p-1.5 -mx-1.5 rounded-xl transition-all border border-transparent hover:border-gray-200 hover:shadow-sm">
                                    <div class="relative flex items-center pt-0.5">
                                        <input type="checkbox" class="w-4 h-4 rounded border-2 border-gray-300 bg-white ${m.colorCheck} cursor-pointer task-cb transition-all" data-task-id="${t.id}" onchange="window.updateProgress()">
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 group-hover:text-black transition-colors leading-snug">${t.title}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `;
            });
            
            d2Members.innerHTML = html;
        });

        // L3 & Checkbox Logic (Global function so inline onchange can call it)
        window.updateProgress = function() {
            // Update Data Model based on checkboxes
            const checkboxes = document.querySelectorAll('.task-cb');
            checkboxes.forEach(cb => {
                const taskId = parseInt(cb.getAttribute('data-task-id'));
                const task = tasks.find(t => t.id === taskId);
                if(task) {
                    task.done = cb.checked;
                    // Styling checked text
                    const textSpan = cb.parentElement.nextElementSibling;
                    if(cb.checked) {
                        textSpan.classList.add('line-through', 'text-gray-400');
                        textSpan.classList.remove('text-gray-600', 'group-hover:text-black');
                    } else {
                        textSpan.classList.remove('line-through', 'text-gray-400');
                        textSpan.classList.add('text-gray-600', 'group-hover:text-black');
                    }
                }
            });

            // Calculate and Update Chart in L3
            let totalDone = 0;
            
            members.forEach(m => {
                const memberTasks = tasks.filter(t => t.assignee === m.id);
                const doneTasks = memberTasks.filter(t => t.done).length;
                const pct = memberTasks.length > 0 ? Math.round((doneTasks / memberTasks.length) * 100) : 0;
                
                totalDone += doneTasks;

                const bar = document.getElementById(`demo3-bar-${m.id}`);
                const txt = document.getElementById(`demo3-text-${m.id}`);
                if(bar) bar.style.width = `${pct}%`;
                if(txt) txt.innerText = `${pct}%`;
            });

            const totalPct = tasks.length > 0 ? Math.round((totalDone / tasks.length) * 100) : 0;
            const f3TotalBar = document.getElementById('demo3-total-bar');
            const f3TotalText = document.getElementById('demo3-total-text');
            
            if(f3TotalBar) f3TotalBar.style.width = `${totalPct}%`;
            if(f3TotalText) f3TotalText.innerText = `${totalPct}%`;
        }
    });
</script>

<style>
    @keyframes slide-right {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 6px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

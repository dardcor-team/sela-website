<section class="hero pt-[160px] pb-[120px] max-[768px]:pt-[120px] max-[768px]:pb-[80px] relative 
                bg-[linear-gradient(to_right,rgba(0,0,0,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.05)_1px,transparent_1px)] 
                bg-[size:50px_50px] overflow-hidden animate-[grid-move_20s_linear_infinite]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="grid grid-cols-2 gap-[60px] max-[768px]:gap-[40px] items-center max-[992px]:grid-cols-1 max-[992px]:text-center">
            
            <div class="hero-content">
                
                <h1 class="reveal d1 text-[3.2rem] max-[768px]:text-[2.4rem] max-[480px]:text-[1.7rem] text-black mb-8 leading-[1.05] font-mono font-normal uppercase">
                    Kerja Kelompok <em class="text-cyan not-italic">Adil</em>,<br>Tanpa Drama Free-Rider.
                </h1>
                
                <p class="reveal d2 text-[1.2rem] max-[480px]:text-[1rem] text-muted mb-[48px] max-[768px]:mb-[32px] font-medium leading-[1.8] max-w-[520px] max-[992px]:max-w-full max-[992px]:mx-auto">
                    SELA membagi tugas dengan cerdas menggunakan teknologi AI, memantau kontribusi setiap anggota, dan memastikan kolaborasi mahasiswa berjalan transparan.
                </p>
                
                <div class="hero-btns reveal d2 flex gap-4 max-[480px]:gap-3 flex-wrap mb-9 max-[768px]:mb-6 max-[992px]:justify-center max-[480px]:flex-col max-[480px]:w-full max-[480px]:max-w-[300px] max-[480px]:mx-auto">
                    <a href="https://play.google.com/store/apps/details?id=com.pdbl.sela" class="btn inline-flex items-center justify-center gap-2 py-[14px] px-[28px] max-[480px]:py-[12px] max-[480px]:px-[20px] font-mono text-[0.85rem] max-[480px]:text-[0.78rem] cursor-pointer rounded-[14px] border-[3px] border-cyan transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] whitespace-nowrap bg-cyan text-white shadow-[6px_6px_0px_#000] hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-[10px_10px_0px_#000]">
                        Mulai Gunakan SELA Gratis
                    </a>
                    <a href="#how" class="btn inline-flex items-center justify-center gap-2 py-[14px] px-[28px] max-[480px]:py-[12px] max-[480px]:px-[20px] font-mono text-[0.85rem] max-[480px]:text-[0.78rem] cursor-pointer rounded-[14px] border-[3px] border-black transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] whitespace-nowrap bg-white text-black shadow-neo hover:-translate-x-[3px] hover:-translate-y-[3px] hover:shadow-neo-hover">
                        Lihat Cara Kerja
                    </a>
                </div>
                
                <div class="hero-stats reveal d3 flex gap-8 max-[480px]:gap-5 max-[992px]:justify-center">
                    <div class="hero-stat">
                        <strong class="block font-mono text-[1.6rem] max-[480px]:text-[1.3rem] text-black">10K+</strong>
                        <span class="text-[0.85rem] max-[480px]:text-[0.75rem] text-muted font-semibold">Mahasiswa Aktif</span>
                    </div>
                    <div class="hero-stat">
                        <strong class="block font-mono text-[1.6rem] max-[480px]:text-[1.3rem] text-black">500+</strong>
                        <span class="text-[0.85rem] max-[480px]:text-[0.75rem] text-muted font-semibold">Universitas</span>
                    </div>
                    <div class="hero-stat">
                        <strong class="block font-mono text-[1.6rem] max-[480px]:text-[1.3rem] text-black">4.9★</strong>
                        <span class="text-[0.85rem] max-[480px]:text-[0.75rem] text-muted font-semibold">Rating Pengguna</span>
                    </div>
                </div>
            </div>

            <div class="reveal d2 relative h-[clamp(280px,42vw,580px)] flex justify-center items-center [perspective:1200px]">
                <div class="phone-blob absolute w-[clamp(180px,30vw,360px)] h-[clamp(180px,30vw,360px)] bg-cyan rounded-full blur-[clamp(50px,7vw,80px)] opacity-35 animate-[blob-pulse_5s_alternate_infinite]"></div>
                
                <div class="phone-mockup w-[clamp(160px,28vw,260px)] aspect-[13/27] bg-slate-50 rounded-[clamp(28px,4vw,42px)] border-[clamp(7px,1.1vw,10px)] border-[#2a2a2a] shadow-[24px_24px_64px_rgba(9,99,126,0.45)] relative overflow-hidden z-10 [transform-style:preserve-3d] animate-[phone-float_7s_ease-in-out_infinite]" id="heroPhone">
                    <div class="absolute top-0 inset-x-0 h-[clamp(14px,1.5vw,20px)] bg-[#2a2a2a] rounded-b-2xl w-[45%] mx-auto z-20"></div>
                    
                    <!-- Gambar Screenshot Aplikasi Flutter -->
                    <div class="w-full h-full bg-white relative">
                        <img 
                            src="{{ asset('images/dashboard-mahasiswa.jpg') }}" 
                            class="absolute top-0 left-0 w-full h-full object-cover" 
                            alt="Dashboard Sela App"
                        >
                    </div>

                    <!-- iOS Home Indicator -->
                    <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-[35%] h-[4px] bg-black/40 rounded-full z-40"></div>
                </div>

                <div class="fb1 absolute font-mono text-[0.8rem] py-[10px] px-[18px] border-[3px] border-black rounded-[14px] bg-white shadow-neo-cyan z-[15] top-[8%] -right-[12%] max-[992px]:hidden animate-[fbob_6s_ease-in-out_infinite]" id="float1">AI Task Split</div>
                <div class="fb2 absolute font-mono text-[0.8rem] py-[10px] px-[18px] border-[3px] border-black rounded-[14px] bg-white shadow-neo-cyan z-[15] bottom-[16%] -left-[16%] max-[992px]:hidden animate-[fbob_8s_ease-in-out_infinite_reverse]" id="float2">No Free-Rider!</div>
            </div>
        </div>
    </div>
    
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fade-in-right {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes progress-grow {
            from { width: 0; }
            to { width: 85%; }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(0.98); }
        }
        @keyframes bounce-soft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</section>

<div class="navbar-wrap fixed top-4 left-1/2 -translate-x-1/2 z-[1000] 
            w-[calc(100%_-_80px)] max-w-[1200px] bg-white/[0.88] backdrop-blur-[20px] 
            border-[3px] border-black rounded-[100px] shadow-neo 
            transition-all duration-300 ease-in-out 
            max-[768px]:w-[calc(100%_-_32px)] max-[768px]:top-[10px] max-[768px]:rounded-[24px]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-4">
        <nav class="flex items-center justify-between py-[16px] px-[32px] max-[768px]:py-[10px] max-[768px]:px-[12px]">
            <a href="#" class="logo flex items-center gap-2 font-mono text-[1.8rem] max-[480px]:text-[1.4rem] tracking-[-2px] 
                               bg-[linear-gradient(135deg,var(--color-cyan)_0%,var(--color-cyan-bright)_50%,var(--color-cyan)_100%)] 
                               bg-clip-text text-transparent bg-[length:200%_auto] 
                               animate-[shimmer_3s_linear_infinite]">
                <img src="{{ asset('images/sela.png') }}" alt="Logo SELA" class="w-8 h-8 -mt-1"> SELA
            </a>
            <div class="nav-links flex gap-8 font-bold text-[0.88rem] uppercase max-[992px]:hidden">
                <a href="#features" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Fitur</a>
                <a href="#how" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Cara kerja</a>
                <a href="#faq" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Pertanyaan</a>
                <a href="#reviews" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Testimoni</a>
                <a href="#team" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Bantuan</a>
            </div>
            <div class="nav-actions flex gap-3 items-center">
                <a href="https://play.google.com/store/apps/details?id=com.pdbl.sela" class="btn inline-flex items-center justify-center gap-2 
                                   py-[14px] px-[28px] max-[768px]:py-[10px] max-[768px]:px-[18px] max-[768px]:text-[0.75rem] font-mono text-[0.85rem] cursor-pointer 
                                   rounded-[14px] border-[3px] max-[768px]:border-[2px] border-black whitespace-nowrap 
                                   transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] 
                                   bg-black text-white shadow-[8px_8px_0px_var(--color-cyan)] max-[768px]:shadow-[4px_4px_0px_var(--color-cyan)]
                                   hover:translate-x-[-4px] hover:translate-y-[-4px] 
                                   hover:shadow-[12px_12px_0px_var(--color-cyan)]
                                   max-[480px]:hidden">Download App</a>
                <!-- Hamburger Button -->
                <button id="hamburger-btn" class="hamburger hidden max-[992px]:flex flex-col justify-center items-center w-[44px] h-[44px] cursor-pointer bg-transparent border-none gap-[6px] z-[1010]" aria-label="Menu">
                    <span class="hamburger-line block w-[24px] h-[3px] bg-black rounded-full transition-all duration-300 origin-center"></span>
                    <span class="hamburger-line block w-[24px] h-[3px] bg-black rounded-full transition-all duration-300 origin-center"></span>
                    <span class="hamburger-line block w-[24px] h-[3px] bg-black rounded-full transition-all duration-300 origin-center"></span>
                </button>
            </div>
        </nav>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="mobile-menu fixed inset-0 z-[999] transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="mobile-menu-panel absolute top-[80px] max-[768px]:top-[70px] left-1/2
                w-[calc(100%_-_32px)] max-w-[400px] bg-white border-[3px] border-black rounded-[24px] 
                shadow-[8px_8px_0px_var(--color-cyan)] p-6
                transition-all duration-300">
        <div class="flex flex-col gap-4">
            <a href="#features" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Fitur</a>
            <a href="#how" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Cara kerja</a>
            <a href="#faq" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Pertanyaan</a>
            <a href="#reviews" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Testimoni</a>
            <a href="#team" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Bantuan</a>
            <a href="https://play.google.com/store/apps/details?id=com.pdbl.sela" class="btn inline-flex items-center justify-center gap-2 
                               py-[14px] px-[28px] font-mono text-[0.85rem] cursor-pointer 
                               rounded-[14px] border-[3px] border-black whitespace-nowrap 
                               transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] 
                               bg-black text-white shadow-[6px_6px_0px_var(--color-cyan)] 
                               hover:translate-x-[-3px] hover:translate-y-[-3px] 
                               hover:shadow-[10px_10px_0px_var(--color-cyan)] mt-2 sm:hidden">Download App</a>
        </div>
    </div>
</div>

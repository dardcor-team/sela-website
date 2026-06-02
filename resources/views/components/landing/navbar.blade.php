<div class="navbar-wrap fixed top-[10px] md:top-4 inset-x-0 mx-auto z-[1000] 
            w-[calc(100%_-_32px)] md:w-[calc(100%_-_80px)] max-w-[1200px] bg-white/[0.88] backdrop-blur-[20px] 
            border-[3px] border-black rounded-[24px] md:rounded-[100px] shadow-neo 
            transition-all duration-300 ease-in-out">
    <div class="max-w-[1280px] mx-auto px-2 md:px-10">
        <nav class="flex items-center justify-between py-[10px] md:py-[16px] px-[12px] md:px-[32px]">
            <a href="#" class="logo flex items-center gap-2 font-mono text-[1.4rem] sm:text-[1.8rem] tracking-[-2px] 
                                bg-[linear-gradient(135deg,#088395_0%,#0ea5b9_50%,#088395_100%)] 
                                bg-clip-text text-transparent bg-[length:200%_auto] 
                                animate-[shimmer_3s_linear_infinite]">
                <img src="{{ asset('images/sela.png') }}" alt="Logo SELA" class="w-8 h-8 -mt-1"> SELA
            </a>
            <div class="nav-links hidden lg:flex gap-8 font-bold text-[0.88rem] uppercase">
                <a href="#features" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Fitur</a>
                <a href="#how" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Cara kerja</a>
                <a href="#faq" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Pertanyaan</a>
                <a href="#reviews" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Testimoni</a>
                <a href="#team" class="nav-link transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:text-cyan">Bantuan</a>
            </div>
            <div class="nav-actions flex gap-3 items-center">
                <a href="https://play.google.com/store/apps/details?id=com.pdbl.sela" class="btn hidden sm:inline-flex items-center justify-center gap-2 
                                   py-[10px] md:py-[14px] px-[18px] md:px-[28px] text-[0.75rem] md:text-[0.85rem] font-mono cursor-pointer 
                                   rounded-[14px] border-[2px] md:border-[3px] border-black whitespace-nowrap 
                                   transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] 
                                   bg-black text-white shadow-[4px_4px_0px_var(--color-cyan)] md:shadow-[8px_8px_0px_var(--color-cyan)]
                                   hover:translate-x-[-4px] hover:translate-y-[-4px] 
                                   hover:shadow-[12px_12px_0px_var(--color-cyan)]">Download App</a>
                <!-- Hamburger Button -->
                <button id="hamburger-btn" class="hamburger flex lg:hidden flex-col justify-center items-center w-[44px] h-[44px] cursor-pointer bg-transparent border-none gap-[6px] z-[1010]" aria-label="Menu">
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
    <div class="mobile-menu-panel absolute top-[70px] md:top-[80px] inset-x-0 mx-auto
                w-[calc(100%_-_32px)] max-w-[400px] bg-white border-[3px] border-black rounded-[24px] 
                shadow-[8px_8px_0px_var(--color-cyan)] p-6
                transition-all duration-300">
        <div class="flex flex-col gap-4">
            <a href="#features" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Fitur</a>
            <a href="#how" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Cara kerja</a>
            <a href="#faq" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Pertanyaan</a>
            <a href="#reviews" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Testimoni</a>
            <a href="#team" class="mobile-nav-link text-[1rem] font-bold uppercase py-3 px-4 rounded-[14px] border-[2px] border-transparent hover:border-black hover:bg-gray-50 transition-all">Bantuan</a>
            <a href="https://play.google.com/store/apps/details?id=com.pdbl.sela" class="btn flex sm:hidden items-center justify-center gap-2 
                               py-[14px] px-[28px] font-mono text-[0.85rem] cursor-pointer 
                               rounded-[14px] border-[3px] border-black whitespace-nowrap 
                               transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] 
                               bg-black text-white shadow-[6px_6px_0px_var(--color-cyan)] 
                               hover:translate-x-[-3px] hover:translate-y-[-3px] 
                               hover:shadow-[10px_10px_0px_var(--color-cyan)] mt-2">Download App</a>
        </div>
    </div>
</div>

<footer class="bg-black text-white pt-[80px] max-[768px]:pt-[50px]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr] gap-[32px] md:gap-[40px] lg:gap-[60px] pb-[60px] max-[768px]:pb-[40px] border-b-[2px] border-[#222]">
            <!-- Column 1: Logo & Email -->
            <div>
                <a href="#" class="logo flex items-center gap-2 text-[2.4rem] max-[480px]:text-[2rem] mb-4">
                    <img src="{{ asset('images/sela.png') }}" alt="Logo SELA" class="w-10 h-10 inline-block align-middle"> <span class="align-middle">SELA</span>
                </a>
                <p class="text-[0.92rem] max-[480px]:text-[0.85rem] text-[#888] leading-[1.7] max-w-[280px] mb-6">Platform kolaborasi akademik berbasis AI yang memastikan setiap mahasiswa berkontribusi secara adil dan dosen dapat memantau kemajuan kelompok secara transparan.</p>
                <div class="mt-4">
                    <h4 class="font-sans text-[0.8rem] font-bold uppercase tracking-[2px] text-[#555] mb-[10px]">Kontak Kami</h4>
                    <a href="mailto:administratorsela@gmail.com" class="text-[0.92rem] text-[#aaa] hover:text-cyan transition-colors duration-200">administratorsela@gmail.com</a>
                </div>
            </div>
            <!-- Column 2: Navigasi -->
            <div>
                <h4 class="font-sans text-[0.8rem] font-bold uppercase tracking-[2px] text-[#555] mb-[20px]">Navigasi</h4>
                <ul class="list-none flex flex-col gap-[12px]">
                    <li><a href="#features" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Detail Fitur</a></li>
                    <li><a href="#how" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Cara Kerja</a></li>
                    <li><a href="#reviews" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Testimoni</a></li>
                    <li><a href="#team" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Tentang Kami</a></li>
                </ul>
            </div>
            <!-- Column 3: Dukungan -->
            <div>
                <h4 class="font-sans text-[0.8rem] font-bold uppercase tracking-[2px] text-[#555] mb-[20px]">Dukungan</h4>
                <ul class="list-none flex flex-col gap-[12px]">
                    <li><a href="#faq" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">FAQ</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Pusat Bantuan</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Kontak Kami</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Status Sistem</a></li>
                </ul>
            </div>
            <!-- Column 4: Legalitas -->
            <div>
                <h4 class="font-sans text-[0.8rem] font-bold uppercase tracking-[2px] text-[#555] mb-[20px]">Legalitas</h4>
                <ul class="list-none flex flex-col gap-[12px]">
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Terms of Service</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Privacy Policy</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Cookie Policy</a></li>
                    <li><a href="/maintenance" class="text-[0.92rem] text-[#888] transition-colors duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] font-medium hover:text-white">Lisensi</a></li>
                </ul>
            </div>
        </div>
        <div class="py-[28px] text-center md:text-left">
            <p class="text-[0.85rem] text-[#555]">&copy; {{ date('Y') }} Tim SELA. All rights reserved.</p>
        </div>
    </div>
</footer>

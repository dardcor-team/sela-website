@props(['reviews' => [
    ['name' => 'Irvan Aditya Kurniawan', 'text' => 'Fitur auto create subtask dan juga group menu, mungkin bisa diberikan default data di input group ketika add task sehingga tidak langsung kosong dan juga jika create subtask otomatis menggunakan ai mungkin bisa diberikan input users untuk menjelaskan flow subtask secara singkat sehingga subtask yang dibuat masih berkaitan.', 'role' => 'Mahasiswa'],
    ['name' => 'Irwin Ahmad Wiryawan', 'text' => 'Responsif, fitur utama on-point, estetika dan pemilihan tema sangat cocok, sistem invitation bekerja dengan baik, automatic subtask berjalan dengan baik dan sangat membantu', 'role' => 'Mahasiswa'],
    ['name' => 'Resty Setya Indrayani', 'text' => 'Fungsi aplikasi sangat bermanfaat untuk mahasiswa, dimana kita dapat mencatat tugas-tugas yang ada, tida hanya itu.. kita juga bisa langsung simpan file di task yang kita tambahkan, sangat memudahkan sehingga kita tidak perlu nyari-nyari lagi menyelam di penyimpanan disk laptop', 'role' => 'Mahasiswa'],
    ['name' => 'Nadhiva Azwar Nur Efendy', 'text' => 'Task join grupnya yang keren, jadi ga harus menambahkan task per anak tinggal undang', 'role' => 'Mahasiswa'],
    ['name' => 'Nur Legia Erifadina', 'text' => 'Hal yang disukai dapat mengelo tugas individu/grup dengan bisa melihat notifikasi ketika tugas mendekati deadline', 'role' => 'Mahasiswa'],
    ['name' => 'Diaz Raharjo Muliasmara', 'text' => 'UI nya cukup nyaman dimata, disini enaknya kita bisa menambahkan ability dan bisa mengganti foto profile sesuai dengan yang kita mau. dan untuk UXnya lumayan baik juga', 'role' => 'Mahasiswa'],
    ['name' => 'Muhammad Syauqy Arrayyan', 'text' => 'Fungsionalitas aplikasi berkerja dengan baik, tampilannya informatif dan sangat membantu', 'role' => 'Mahasiswa'],
    ['name' => 'Nadira Ridha Aulia', 'text' => 'Tampilan aplikasi sederhana dan fungsionalitasnya informatif, mudah dioperasikan', 'role' => 'Mahasiswa'],
    ['name' => 'Nur Aini Agusthina', 'text' => 'Invitation code, membantu mempermudah join class', 'role' => 'Mahasiswa'],
    ['name' => 'Refa Brillian', 'text' => 'AI nya keren, tapi perlu training lagi kali yaaa. kalo ainya pinter harusnyaa akan sangat kepakee. bisa lihat on progress tugas juga keren', 'role' => 'Mahasiswa'],
]])

<section id="reviews" class="py-[100px] max-[768px]:py-[60px] bg-white relative bg-[linear-gradient(to_right,rgba(0,0,0,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.05)_1px,transparent_1px)] bg-[size:50px_50px]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="text-center mb-16 max-[768px]:mb-10 reveal">
            <h2 class="font-mono uppercase text-4xl max-[768px]:text-[2.4rem] max-[480px]:text-[1.7rem] text-black mb-4">Apa Kata Mereka?</h2>
            <p class="text-muted text-lg max-[480px]:text-base">Pengalaman nyata dari pengguna SELA.</p>
        </div>
        
        <div class="carousel-container relative overflow-hidden w-full">
            <div id="review-carousel" class="flex gap-6 animate-marquee py-4">
                @foreach($reviews as $review)
                    <div class="shrink-0 w-[300px] max-[480px]:w-[260px] sm:w-[350px] bg-white border-[3px] max-[480px]:border-[2px] border-black p-8 max-[480px]:p-5 rounded-[24px] max-[480px]:rounded-[18px] shadow-neo max-[480px]:shadow-[4px_4px_0px_#000] flex flex-col justify-between hover:-translate-y-2 transition-transform duration-300">
                        <div>
                            <div class="text-[#fbbf24] mb-4 max-[480px]:mb-3 text-xl max-[480px]:text-lg">★★★★★</div>
                            <p class="text-black italic mb-6 max-[480px]:mb-4 leading-relaxed text-[0.95rem] max-[480px]:text-[0.85rem]">{{ $review['text'] }}</p>
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-200 pt-4 max-[480px]:pt-3">
                            <div class="w-10 h-10 max-[480px]:w-8 max-[480px]:h-8 rounded-full bg-cyan-700 text-white flex items-center justify-center font-bold text-sm max-[480px]:text-xs">
                                {{ strtoupper(substr($review['name'], 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-sm max-[480px]:text-xs">{{ $review['name'] }}</div>
                                <div class="text-xs text-muted">{{ $review['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Duplicated items for infinite marquee --}}
                @foreach($reviews as $review)
                    <div class="shrink-0 w-[300px] max-[480px]:w-[260px] sm:w-[350px] bg-white border-[3px] max-[480px]:border-[2px] border-black p-8 max-[480px]:p-5 rounded-[24px] max-[480px]:rounded-[18px] shadow-neo max-[480px]:shadow-[4px_4px_0px_#000] flex flex-col justify-between hover:-translate-y-2 transition-transform duration-300">
                        <div>
                            <div class="text-[#fbbf24] mb-4 text-xl">★★★★★</div>
                            <p class="text-black italic mb-6 leading-relaxed">{{ $review['text'] }}</p>
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-200 pt-4">
                            <div class="w-10 h-10 rounded-full bg-cyan-700 text-white flex items-center justify-center font-bold">
                                {{ strtoupper(substr($review['name'], 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-sm">{{ $review['name'] }}</div>
                                <div class="text-xs text-muted">{{ $review['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 40s linear infinite;
        display: flex;
        width: max-content;
    }
    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>
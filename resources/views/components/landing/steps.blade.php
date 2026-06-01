@props(['steps' => [
    [
        'number' => '01',
        'title' => 'Registrasi & Pembuatan Grup',
        'description' => 'Masuk menggunakan akun SSO institusi akademikmu dan buat ruang kolaborasi untuk kelompokmu dalam sekejap.',
    ],
    [
        'number' => '02',
        'title' => 'Otomatisasi Tugas',
        'description' => 'Sistem AI menyusun rencana kerja secara cerdas dan membagi porsi tugas ke masing-masing anggota berdasarkan skill.',
    ],
    [
        'number' => '03',
        'title' => 'Eksekusi & Pantauan',
        'description' => 'Setiap anggota fokus mengerjakan tugasnya masing-masing sementara sistem mencatat dan melaporkan progres secara real-time.',
    ],
]])

<section id="how" class="py-[130px] max-[768px]:py-[70px] bg-white relative bg-[linear-gradient(to_right,rgba(0,0,0,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.05)_1px,transparent_1px)] bg-[size:50px_50px]">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="text-center mb-[80px] max-[768px]:mb-[48px] reveal">
            <div class="inline-block text-[0.78rem] font-bold uppercase tracking-[2px] text-cyan mb-[16px]">Alur Penggunaan</div>
            <h2 class="text-[3.2rem] max-[768px]:text-[2.4rem] max-[480px]:text-[1.7rem] text-black mb-[20px]">Mulai dalam<br>3 Langkah Mudah.</h2>
            <p class="text-[1.1rem] max-[480px]:text-[0.95rem] text-muted max-w-[680px] mx-auto leading-[1.7]">Tanpa konfigurasi rumit. Langsung kolaborasi secara adil dalam hitungan menit.</p>
        </div>
        <div class="grid grid-cols-3 max-[992px]:grid-cols-1 gap-[36px] max-[768px]:gap-[24px] max-[992px]:max-w-[480px] max-[992px]:mx-auto">
            @foreach($steps as $index => $step)
                <div class="border-[4px] max-[480px]:border-[3px] border-black rounded-[36px] max-[480px]:rounded-[24px] px-[28px] max-[480px]:px-[20px] py-[48px] max-[480px]:py-[32px] text-center bg-white shadow-neo-hover transition-transform duration-300 ease flex flex-col items-center hover:-translate-y-[14px] {{ $index === 0 ? 'hover:shadow-[12px_24px_0px_var(--color-cyan)]' : ($index === 1 ? 'hover:shadow-[12px_24px_0px_#000]' : 'hover:shadow-[12px_24px_0px_var(--color-cyan-light)]') }} reveal d{{ $index + 1 }}">
                    <div class="font-mono text-[5rem] max-[768px]:text-[3.5rem] max-[480px]:text-[3rem] text-cyan [text-shadow:4px_4px_0px_#000] max-[480px]:[text-shadow:3px_3px_0px_#000] mb-[18px] max-[480px]:mb-[12px]">{{ $step['number'] }}</div>
                    <div class="text-[2.5rem] max-[480px]:text-[2rem] mb-[20px] max-[480px]:mb-[14px]"></div>
                    <h3 class="text-[1.3rem] max-[480px]:text-[1.1rem] text-black mb-[14px]">{{ $step['title'] }}</h3>
                    <p class="text-[0.97rem] max-[480px]:text-[0.88rem] text-muted leading-[1.65]">{{ $step['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

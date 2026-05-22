@props([
    'row1' => [
        ['initial' => 'D', 'name' => 'Dino Ariel Ihsan Saputra', 'role' => 'Product Owner', 'sub' => 'Jira & Notion', 'gradient' => 'linear-gradient(135deg, var(--color-cyan), var(--color-cyan-bright))', 'links' => ['LN' => '#', 'GH' => '#']],
        ['initial' => 'H', 'name' => 'Havid Rosihandanu', 'role' => 'Backend Developer', 'sub' => 'PostgreSQL & Node.js', 'gradient' => 'linear-gradient(135deg, #1d4ed8, #3b82f6)', 'links' => ['LN' => 'https://www.linkedin.com/in/havid-rosihandanu-538653325/', 'GH' => 'https://github.com/Havidrosihandanu']],
        ['initial' => 'S', 'name' => 'Syahrul Ardi Prasetiyo', 'role' => 'Mobile Developer', 'sub' => 'Flutter & Dart', 'gradient' => 'linear-gradient(135deg, #7c3aed, #a78bfa)', 'links' => ['LN' => 'https://www.linkedin.com/in/syahrul-ardi-prasetiyo-5564a4396/', 'GH' => 'https://github.com/Dardcor']],
        ['initial' => 'F', 'name' => 'Fahroldhi Sukirno', 'role' => 'DevOps', 'sub' => 'Docker & CI/CD', 'gradient' => 'linear-gradient(135deg, #059669, #34d399)', 'links' => ['LN' => 'https://www.linkedin.com/in/fahroldhi/', 'GH' => 'https://github.com/acalypha9']],
    ],
    'row2' => [
        ['initial' => 'M', 'name' => 'Musthofa Agung Distyawan', 'role' => 'UI/UX Designer', 'sub' => 'Figma & Prototyping', 'gradient' => 'linear-gradient(135deg,#0f766e,#14b8a6)', 'links' => ['LN' => 'https://www.linkedin.com/in/musthofaagungdistyawan/', 'GH' => 'https://github.com/Msthfaa']],
        ['initial' => 'R', 'name' => 'Rafif Ahmad Yudhistira', 'role' => 'Frontend Developer', 'sub' => 'Laravel & Tailwind CSS', 'gradient' => 'linear-gradient(135deg,#b45309,#f59e0b)', 'links' => ['LN' => 'https://www.linkedin.com/in/rafif-ahmad-yudhistira-04836331b/', 'GH' => 'https://github.com/Lionz-IT']],
        ['initial' => 'Y', 'name' => 'Yosiyanti Cendekia Sari', 'role' => 'QA Engineer', 'sub' => 'Testing & Automation', 'gradient' => 'linear-gradient(135deg,#be185d,#f472b6)', 'links' => ['LN' => 'https://www.linkedin.com/in/yosiyanti-cendekia-sari-0649a8402/', 'GH' => 'https://github.com/Yosi-15']],
    ],
])

<section id="team" class="py-[130px] max-[768px]:py-[70px] bg-white">
    <div class="max-w-[1280px] mx-auto px-10 max-[768px]:px-5">
        <div class="text-center mb-[80px] max-[768px]:mb-[48px] reveal">
            <div class="inline-block text-[0.78rem] font-bold uppercase tracking-[2px] text-cyan mb-4">Tim Pengembang</div>
            <h2 class="font-mono uppercase leading-[1.1] text-[3.2rem] max-[768px]:text-[2.4rem] max-[480px]:text-[1.7rem] mb-[20px] text-black">Dibangun oleh<br>Tim yang Berdedikasi.</h2>
            <p class="text-[1.1rem] max-[480px]:text-[0.95rem] max-w-[680px] mx-auto leading-[1.7] text-muted">Kami adalah tim mahasiswa yang merasakan langsung masalah free-rider dan bertekad membangun solusinya.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[28px] max-[480px]:gap-[20px]">
            @foreach($row1 as $index => $member)
                <div class="border-[4px] max-[480px]:border-[3px] border-black rounded-[28px] max-[480px]:rounded-[20px] py-[36px] max-[480px]:py-[24px] px-[24px] max-[480px]:px-[18px] text-center bg-white shadow-neo transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:-translate-x-1 hover:-translate-y-1 {{ $index % 2 === 0 ? 'hover:shadow-[10px_10px_0px_var(--color-cyan)]' : 'hover:shadow-neo-hover' }} reveal d{{ $index + 1 }}">
                    <div class="w-[90px] h-[90px] max-[480px]:w-[70px] max-[480px]:h-[70px] rounded-full flex items-center justify-center text-[2rem] max-[480px]:text-[1.5rem] font-mono text-white mx-auto mb-[20px] max-[480px]:mb-[14px] border-[4px] max-[480px]:border-[3px] border-black shadow-[4px_4px_0px_#000] max-[480px]:shadow-[3px_3px_0px_#000]" style="background: {{ $member['gradient'] }};">{{ $member['initial'] }}</div>
                    <div class="font-mono text-[1rem] max-[480px]:text-[0.85rem] text-black mb-[6px] uppercase">{{ $member['name'] }}</div>
                    <div class="text-[0.85rem] font-bold text-cyan mb-[4px] uppercase tracking-[0.5px]">{{ $member['role'] }}</div>
                    <div class="text-[0.8rem] text-muted mb-[20px]">{{ $member['sub'] }}</div>
                    <div class="flex justify-center gap-[12px]">
                        @foreach($member['links'] as $label => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-[38px] h-[38px] rounded-[10px] border-[2.5px] border-black flex items-center justify-center text-[1rem] transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] bg-white shadow-[3px_3px_0_#000] hover:bg-black hover:text-white hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[5px_5px_0_var(--color-cyan)]" title="{{ $label }}">
                                @if($label === 'LN')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                @elseif($label === 'GH')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                @else
                                    {{ $label }}
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[28px] max-[480px]:gap-[20px] mt-[28px] max-[480px]:mt-[20px] lg:max-w-[calc(75%+14px)] mx-auto">
            @foreach($row2 as $index => $member)
                <div class="border-[4px] max-[480px]:border-[3px] border-black rounded-[28px] max-[480px]:rounded-[20px] py-[36px] max-[480px]:py-[24px] px-[24px] max-[480px]:px-[18px] text-center bg-white shadow-neo transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] hover:-translate-x-1 hover:-translate-y-1 {{ $index % 2 === 0 ? 'hover:shadow-[10px_10px_0px_var(--color-cyan)]' : 'hover:shadow-neo-hover' }} reveal d{{ $index + 1 }}">
                    <div class="w-[90px] h-[90px] max-[480px]:w-[70px] max-[480px]:h-[70px] rounded-full flex items-center justify-center text-[2rem] max-[480px]:text-[1.5rem] font-mono text-white mx-auto mb-[20px] max-[480px]:mb-[14px] border-[4px] max-[480px]:border-[3px] border-black shadow-[4px_4px_0px_#000] max-[480px]:shadow-[3px_3px_0px_#000]" style="background:{{ $member['gradient'] }};">{{ $member['initial'] }}</div>
                    <div class="font-mono text-[1rem] max-[480px]:text-[0.85rem] text-black mb-[6px] uppercase">{{ $member['name'] }}</div>
                    <div class="text-[0.85rem] font-bold text-cyan mb-[4px] uppercase tracking-[0.5px]">{{ $member['role'] }}</div>
                    <div class="text-[0.8rem] text-muted mb-[20px]">{{ $member['sub'] }}</div>
                    <div class="flex justify-center gap-[12px]">
                        @foreach($member['links'] as $label => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="w-[38px] h-[38px] rounded-[10px] border-[2.5px] border-black flex items-center justify-center text-[1rem] transition-all duration-200 ease-[cubic-bezier(0.19,1,0.22,1)] bg-white shadow-[3px_3px_0_#000] hover:bg-black hover:text-white hover:-translate-x-[2px] hover:-translate-y-[2px] hover:shadow-[5px_5px_0_var(--color-cyan)]" title="{{ $label }}">
                                @if($label === 'LN')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                @elseif($label === 'GH')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                @else
                                    {{ $label }}
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

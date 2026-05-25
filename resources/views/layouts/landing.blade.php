<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SELA – Kerja Kelompok Adil, Tanpa Drama.')</title>
    <meta name="description" content="@yield('description', 'SELA membagi tugas dengan cerdas menggunakan AI, memantau kontribusi setiap anggota, dan memastikan kolaborasi mahasiswa berjalan transparan.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/sela.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/sela.png') }}">
    
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        mono: ['Rubik Mono One', 'monospace'],
                    },
                    colors: {
                        cyan: {
                            DEFAULT: '#0089A5',
                            light: '#00A3C4',
                            bright: '#006CA5',
                        },
                        muted: '#666666',
                    },
                    boxShadow: {
                        'neo': '6px 6px 0px #000000',
                        'neo-hover': '12px 12px 0px #000000',
                        'neo-cyan': '6px 6px 0px #0089A5',
                    }
                }
            }
        }
    </script>
    
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>

    <!-- Custom CSS Styles -->
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: #000;
            background: #fff;
            line-height: 1.5;
            overflow-x: hidden;
            padding-top: 100px;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 85px;
            }
        }

        h1, h2, h3 {
            font-family: 'Rubik Mono One', monospace;
            font-weight: 400;
            text-transform: uppercase;
            line-height: 1.1;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .navbar-wrap.scrolled {
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 8px 8px 0px #000;
        }

        .nav-links a.active {
            color: #0089A5;
        }

        @media (max-width: 768px) {
            .navbar-wrap.scrolled {
                box-shadow: 4px 4px 0px #000;
            }
        }

        .hamburger.active .hamburger-line:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        .faq-item.open .faq-icon {
            transform: rotate(45deg);
        }

        .faq-item.open .faq-a {
            max-height: 300px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(60px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .d1 { transition-delay: 0.1s; }
        .d2 { transition-delay: 0.2s; }
        .d3 { transition-delay: 0.3s; }
        .d4 { transition-delay: 0.4s; }

        @keyframes shimmer {
            to { background-position: 200% center; }
        }

        @keyframes grid-move {
            100% { background-position: 50px 50px; }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        @keyframes blob-pulse {
            to { transform: scale(1.25); opacity: 0.55; }
        }

        @keyframes phone-float {
            0%, 100% { transform: translateY(0) rotateX(5deg) rotateY(-12deg); }
            50% { transform: translateY(-28px) rotateX(9deg) rotateY(-4deg); box-shadow: 24px 56px 80px rgba(9, 99, 126, 0.6); }
        }

        @keyframes fbob {
            0%, 100% { transform: translateY(0) rotate(-4deg); }
            50% { transform: translateY(-18px) rotate(4deg); }
        }

        @keyframes progress-anim {
            from { width: 45%; }
            to { width: 72%; }
        }

        .mobile-menu {
            opacity: 0;
            pointer-events: none;
        }

        .mobile-menu .mobile-menu-panel {
            transform: translateX(-50%) translateY(-20px);
        }

        .mobile-menu.menu-open {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-menu.menu-open .mobile-menu-panel {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body class="m-0 p-0">
    @yield('content')

    <!-- Landing Page Custom Scripts -->
    <script>
        // 1. FAQ toggle logic
        window.toggleFaq = function (btn) {
            const item = btn.closest('.faq-item');
            const isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-item.open').forEach((i) => i.classList.remove('open'));

            if (!isOpen) {
                item.classList.add('open');
            }
        };

        // 2. Reveal animations logic
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12 }
        );
        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

        // 3. Parallax effect logic
        const phone = document.getElementById('heroPhone');
        const float1 = document.getElementById('float1');
        const float2 = document.getElementById('float2');

        document.addEventListener('mousemove', (e) => {
            if (window.innerWidth <= 992) return;

            const x = (window.innerWidth / 2 - e.pageX) / 35;
            const y = (window.innerHeight / 2 - e.pageY) / 35;

            if (phone) {
                phone.style.transform = `translateY(${-y}px) rotateX(${8 + y / 2}deg) rotateY(${-12 + x / 2}deg)`;
            }
            if (float1) {
                float1.style.transform = `translate(${x * 1.6}px, ${y * 1.6}px) rotate(-4deg)`;
            }
            if (float2) {
                float2.style.transform = `translate(${-x * 2}px, ${-y * 2}px) rotate(4deg)`;
            }
        });

        // 4. Navbar active state & hamburger logic
        const navWrap = document.querySelector('.navbar-wrap');

        window.addEventListener('scroll', () => {
            if (navWrap) {
                navWrap.classList.toggle('scrolled', window.scrollY > 20);
            }
        });

        const navLinks = document.querySelectorAll('.nav-links a.nav-link');
        const sections = document.querySelectorAll('section[id], div[id]'); 

        window.addEventListener('scroll', () => {
            if (navWrap) {
                navWrap.classList.toggle('scrolled', window.scrollY > 20);
            }

            let current = '';
            const scrollPosition = window.scrollY + 100;

            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach((link) => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (hamburgerBtn && mobileMenu) {
            hamburgerBtn.addEventListener('click', () => {
                const isOpen = mobileMenu.classList.contains('menu-open');
                if (isOpen) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            mobileMenu.addEventListener('click', (e) => {
                if (e.target === mobileMenu || e.target === mobileMenu.querySelector('.absolute')) {
                    closeMobileMenu();
                }
            });

            mobileMenu.querySelectorAll('.mobile-nav-link').forEach((link) => {
                link.addEventListener('click', () => {
                    closeMobileMenu();
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileMenu.classList.contains('menu-open')) {
                    closeMobileMenu();
                }
            });
        }

        function openMobileMenu() {
            mobileMenu.classList.add('menu-open');
            hamburgerBtn.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('menu-open');
            hamburgerBtn.classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>

const navWrap = document.querySelector('.navbar-wrap');

window.addEventListener('scroll', () => {
    if (navWrap) {
        navWrap.classList.toggle('scrolled', window.scrollY > 20);
    }
});

// Scroll active state for navbar
const navLinks = document.querySelectorAll('.nav-links a.nav-link');
const sections = document.querySelectorAll('section[id], div[id]'); 

window.addEventListener('scroll', () => {
    // 1. Handle background navbar
    if (navWrap) {
        navWrap.classList.toggle('scrolled', window.scrollY > 20);
    }

    // 2. Handle Highlight Aktif
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

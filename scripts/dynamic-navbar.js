document.addEventListener('DOMContentLoaded', function () {
    const mainNavbar = document.getElementById('main-navbar');
    const navCollapse = document.getElementById('navbarNav');

    // --- GŁÓWNA FUNKCJA DO INTELIGENTNEGO PRZEWIJANIA ---
    function smartScroll(targetId, smooth = true) {
        let targetElement;
        if (targetId === 'galeria') {
            const desktopGrid = document.getElementById('desktop-gallery-grid');
            const mobileCarousel = document.getElementById('mobileGalleryCarousel');
            if (desktopGrid && window.getComputedStyle(desktopGrid).display !== 'none') {
                targetElement = desktopGrid;
            } else if (mobileCarousel && window.getComputedStyle(mobileCarousel).display !== 'none') {
                targetElement = mobileCarousel;
            } else {
                targetElement = document.getElementById('galeria');
            }
        } else {
            targetElement = document.getElementById(targetId);
        }

        if (!targetElement) return;

        const navbarHeight = mainNavbar ? mainNavbar.offsetHeight : 70;
        let offsetPosition;
        if (targetElement.id === 'desktop-gallery-grid') {
            const elementRect = targetElement.getBoundingClientRect();
            offsetPosition = elementRect.top + window.pageYOffset - (window.innerHeight / 2) + (elementRect.height / 2);
        } else {
            offsetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20;
        }

        window.scrollTo({
            top: offsetPosition,
            behavior: smooth ? 'smooth' : 'auto'
        });
    }

    // --- LOGIKA DLA KLIKNIĘĆ W NAWIGACJI (NOWA, POPRAWIONA) ---
    if (navCollapse) {
        // Pobieramy WSZYSTKIE linki, tak jak na początku
        const navLinks = navCollapse.querySelectorAll('.nav-link, .btn-custom-green');
        const bsCollapse = new bootstrap.Collapse(navCollapse, {
            toggle: false
        });

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                // Sprawdzamy, czy menu jest otwarte - jeśli tak, ZAWSZE będziemy je zamykać
                if (navCollapse.classList.contains('show')) {
                    event.preventDefault(); // Zawsze zatrzymujemy, żeby mieć kontrolę
                    
                    const href = this.getAttribute('href');
                    
                    // Zamykamy menu
                    bsCollapse.hide();

                    // Po zamknięciu menu, decydujemy co dalej
                    navCollapse.addEventListener('hidden.bs.collapse', function onHide() {
                        // Jeśli to kotwica na tej samej stronie
                        if (href.startsWith('#')) {
                            const targetId = href.substring(1);
                            smartScroll(targetId);
                        } else {
                            // Jeśli to link do innej strony (np. cennik.html lub index.html#galeria)
                            window.location.href = href;
                        }
                    }, { once: true });

                } else {
                    // Jeśli menu jest zamknięte, a link jest kotwicą na tej stronie, to też go obsłuż
                    if (this.getAttribute('href').startsWith('#')) {
                        event.preventDefault();
                        const targetId = this.getAttribute('href').substring(1);
                        smartScroll(targetId);
                    }
                    // Jeśli menu jest zamknięte, a link prowadzi do innej strony, pozwól przeglądarce działać
                }
            });
        });
    }

    // --- LOGIKA PO ZAŁADOWANIU STRONY (dla przekierowań z kotwicą) ---
    function handleAnchorOnLoad() {
        if (window.location.hash) {
            const targetId = window.location.hash.substring(1);
            setTimeout(() => {
                smartScroll(targetId, false);
            }, 100);
        }
    }
    
    handleAnchorOnLoad();
});
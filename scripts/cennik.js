// js/scripts.js - JEDEN, KOMPLETNY PLIK

document.addEventListener('DOMContentLoaded', function() {

    // =========================================================================
    // CZĘŚĆ 1: USTAWIENIA I GŁÓWNE FUNKCJE POMOCNICZE
    // =========================================================================

    const navbar = document.querySelector('.navbar');
    const offset = navbar ? navbar.offsetHeight + 20 : 100; // Offset = wysokość navbara + 20px marginesu

    /**
     * Generyczna funkcja do płynnego przewijania do WIDOCZNEGO elementu.
     * @param {string} targetId - ID elementu docelowego, np. "#eye-story-section".
     */
    function smoothScrollTo(targetId) {
        if (!targetId || targetId === '#') return;
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;
            window.scrollTo({
                top: offsetPosition,
                behavior: "smooth"
            });
        }
    }

    /**
     * Specjalna funkcja dla FAQ, która najpierw otwiera kontener, a potem przewija.
     */
    function handleFaqScroll() {
        const faqSection = document.getElementById('faq-cennik');
        // UWAGA: Upewnij się, że ten selektor jest poprawny dla kontenera cennika!
        const pricingContainerToOpen = document.querySelector('#pricingDetailsSolo'); 

        if (faqSection && pricingContainerToOpen) {
            const collapseInstance = bootstrap.Collapse.getOrCreateInstance(pricingContainerToOpen);
            
            // Jeśli kontener jest już otwarty, po prostu przewiń
            if (pricingContainerToOpen.classList.contains('show')) {
                smoothScrollTo('#faq-cennik');
            } else {
                // Jeśli jest zamknięty, dodaj jednorazowy listener, który przewinie po otwarciu
                pricingContainerToOpen.addEventListener('shown.bs.collapse', () => {
                    smoothScrollTo('#faq-cennik');
                }, { once: true });
                
                // I zleć otwarcie kontenera
                collapseInstance.show();
            }
        }
    }

    // =========================================================================
    // CZĘŚĆ 2: OBSŁUGA ŁADOWANIA STRONY Z KOTWICĄ (np. z innej podstrony)
    // =========================================================================
    
    // Sprawdzamy, czy w adresie URL jest kotwica (np. "cennik.html#faq-cennik")
    if (window.location.hash) {
        // Dajemy przeglądarce chwilę na renderowanie strony. To kluczowe.
        setTimeout(() => {
            const hash = window.location.hash;

            // Używamy switcha dla przejrzystości i łatwej rozbudowy w przyszłości
            switch (hash) {
                case '#faq-cennik':
                    // To jest nasz specjalny przypadek
                    handleFaqScroll();
                    break;
                
                // Tutaj możesz dodać inne specjalne przypadki w przyszłości
                
                default:
                    // To jest standardowy przypadek (np. #eye-story-section)
                    smoothScrollTo(hash);
                    break;
            }
        }, 100); // 100ms to zazwyczaj bezpieczna wartość
    }

    // =========================================================================
    // CZĘŚĆ 3: OBSŁUGA KLIKNIĘĆ W LINKI NA BIEŻĄCEJ STRONIE
    // =========================================================================

    // Nasłuchujemy na wszystkie linki-kotwice
    document.querySelectorAll('a[href*="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const linkHref = this.getAttribute('href');
            
            // Rozbijamy link na część przed i po hashu
            const [path, hash] = linkHref.split('#');

            // Jeśli link prowadzi do bieżącej strony lub jest tylko kotwicą (np. "#faq-cennik")
            if (!path || path === window.location.pathname.split('/').pop() || path === window.location.pathname) {
                // Jeśli link ma kotwicę
                if (hash) {
                    e.preventDefault(); // Zatrzymaj domyślne zachowanie tylko jeśli działamy

                    switch ('#' + hash) {
                        case '#faq-cennik':
                            handleFaqScroll();
                            break;
                        default:
                            smoothScrollTo('#' + hash);
                            break;
                    }
                }
            }
            // Jeśli link prowadzi do innej strony (np. z index.html do cennik.html#faq-cennik),
            // pozwalamy przeglądarce działać normalnie. Kod z CZĘŚCI 2 zajmie się resztą po przeładowaniu.
        });
    });

    // =========================================================================
    // SEKCJA 4: Logika specyficzna dla strony (np. karuzela cennika)
    // Jeśli ten kod ma być tylko na stronie cennika, warto go dodatkowo zabezpieczyć.
    // =========================================================================
    const pricingCarousel = document.querySelector('#pricingCarousel');
    if (pricingCarousel) {
        const tabIds = ['#solo-tab', '#duo-tab', '#trio-tab', '#quadro-tab'];
        pricingCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeIndex = event.to;
            const targetTab = document.querySelector(tabIds[activeIndex]);
            if (targetTab) {
                new bootstrap.Tab(targetTab).show();
            }
        });
    }

}); // Koniec DOMContentLoaded
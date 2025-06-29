document.addEventListener('DOMContentLoaded', function() {

    // =========================================================================
    // CZĘŚĆ 1: USTAWIENIA I GŁÓWNE FUNKCJE POMOCNICZE
    // =========================================================================

    const navbar = document.querySelector('.navbar');
    const offset = navbar ? navbar.offsetHeight + 20 : 100; // Offset = wysokość navbara + 20px marginesu

    /**
     * Generyczna funkcja do płynnego przewijania do elementu.
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
     * NOWA, UNIWERSALNA FUNKCJA do obsługi kliknięć w przyciski rozwijające.
     * Otwiera element, przełącza zakładkę (jeśli zdefiniowano) i przewija po zakończeniu animacji.
     * @param {HTMLElement} clickedButton - Przycisk, który został kliknięty.
     */
    function handleCollapseAndScroll(clickedButton) {
        const collapseTargetSelector = clickedButton.getAttribute('data-bs-target');
        const tabTargetSelector = clickedButton.getAttribute('data-tab-target');

        if (!collapseTargetSelector) return;
        const collapseElement = document.querySelector(collapseTargetSelector);
        if (!collapseElement) return;

        const collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapseElement);

        // Funkcja, która wykona się PO otwarciu elementu
        const actionAfterOpening = () => {
            // 1. Przewiń do rozwiniętej sekcji
            smoothScrollTo(collapseTargetSelector);

            // 2. Jeśli przycisk ma zdefiniowany cel zakładki, przełącz ją
            if (tabTargetSelector) {
                const tabToActivate = document.querySelector(tabTargetSelector);
                if (tabToActivate) {
                    const tabInstance = new bootstrap.Tab(tabToActivate);
                    tabInstance.show();
                }
            }
        };

        // Jeśli element jest już otwarty, po prostu wykonaj akcje (przewiń i przełącz zakładkę)
        if (collapseElement.classList.contains('show')) {
            actionAfterOpening();
        } else {
            // Jeśli jest zamknięty, dodaj jednorazowy listener, który wykona akcje PO otwarciu
            collapseElement.addEventListener('shown.bs.collapse', actionAfterOpening, { once: true });
            
            // I zleć otwarcie kontenera
            collapseInstance.show();
        }
    }

    // =========================================================================
    // CZĘŚĆ 2: OBSŁUGA ŁADOWANIA STRONY Z KOTWICĄ (np. z innej podstrony)
    // =========================================================================
    
    if (window.location.hash) {
        setTimeout(() => {
            const hash = window.location.hash;

            // Specjalny przypadek: jeśli link prowadzi do FAQ wewnątrz cennika,
            // musimy najpierw otworzyć cennik.
            if (hash === '#faq-cennik') {
                const pricingContainer = document.querySelector('#pricingDetailsSolo');
                if (pricingContainer) {
                    const collapseInstance = bootstrap.Collapse.getOrCreateInstance(pricingContainer);
                    pricingContainer.addEventListener('shown.bs.collapse', () => {
                        smoothScrollTo('#faq-cennik');
                    }, { once: true });
                    collapseInstance.show();
                }
            } else {
                // Standardowe przewijanie do kotwicy
                smoothScrollTo(hash);
            }
        }, 100);
    }

    // =========================================================================
    // CZĘŚĆ 3: OBSŁUGA KLIKNIĘĆ W LINKI NA BIEŻĄCEJ STRONIE (UPROSZCZONA)
    // =========================================================================

    document.querySelectorAll('a[href*="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const linkHref = this.getAttribute('href');
            const [path, hash] = linkHref.split('#');

            // Sprawdzamy, czy link prowadzi do bieżącej strony lub jest tylko kotwicą
            const isCurrentPageLink = !path || path === window.location.pathname.split('/').pop() || path === window.location.pathname;

            if (isCurrentPageLink && hash) {
                e.preventDefault(); // Zatrzymaj domyślne zachowanie tylko jeśli działamy na bieżącej stronie

                // Sprawdzamy, czy kliknięty link jest jednocześnie triggerem Bootstrap Collapse
                if (this.matches('[data-bs-toggle="collapse"]')) {
                    // Jeśli tak, użyj naszej nowej, dedykowanej funkcji
                    handleCollapseAndScroll(this);
                } else {
                    // Jeśli to zwykły link-kotwica, po prostu przewiń
                    smoothScrollTo('#' + hash);
                }
            }
            // Jeśli link prowadzi do innej strony z kotwicą (np. z index.html do cennik.html#faq-cennik),
            // pozwalamy przeglądarce działać normalnie. Kod z CZĘŚCI 2 zajmie się resztą.
        });
    });

    // =========================================================================
    // SEKCJA 4: Logika specyficzna dla strony (np. karuzela cennika)
    // =========================================================================
    const pricingCarousel = document.querySelector('#pricingCarousel');
    if (pricingCarousel) {
        const tabIds = ['#solo-tab', '#duo-tab', '#trio-tab', '#quadro-tab'];
        pricingCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeIndex = event.to;
            const targetTab = document.querySelector(tabIds[activeIndex]);
            
            // Upewniamy się, że cennik jest rozwinięty przed próbą pokazania taba
            const pricingDetails = document.querySelector('#pricingDetailsSolo');
            if (pricingDetails && !pricingDetails.classList.contains('show')) {
                const collapseInstance = bootstrap.Collapse.getOrCreateInstance(pricingDetails);
                collapseInstance.show();
            }

            if (targetTab) {
                const tabInstance = new bootstrap.Tab(targetTab);
                tabInstance.show();
            }
        });
    }

}); // Koniec DOMContentLoaded
// js/scripts.js - JEDEN PLIK DO WSZYSTKIEGO

document.addEventListener('DOMContentLoaded', function () {

    // --- Funkcje pomocnicze, używane w wielu miejscach ---
    const navbar = document.querySelector('.navbar'); 
    const navbarHeight = navbar ? navbar.offsetHeight : 70;

    function scrollToWithOffset(element) {
        if (!element) return;
        const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
        const offsetPosition = elementPosition - navbarHeight - 20;
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }

    // =========================================================================
    // SEKCJA 1: Logika dla rozwijanego CENNIKA (bez zmian)
    // =========================================================================
    const pricingButtons = document.querySelectorAll('a[data-tab-target]');
    const detailsSection = document.querySelector('#pricingDetailsSolo');

    if (pricingButtons.length > 0 && detailsSection) {
        pricingButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const tabTargetId = this.getAttribute('data-tab-target');
                const tabToActivate = document.querySelector(tabTargetId);
                if (tabToActivate) {
                    new bootstrap.Tab(tabToActivate).show();
                }
                if (detailsSection.classList.contains('show')) {
                    scrollToWithOffset(detailsSection);
                } else {
                    detailsSection.addEventListener('shown.bs.collapse', () => scrollToWithOffset(detailsSection), { once: true });
                }
            });
        });
    }

    // =========================================================================
    // SEKCJA 2: Logika dla głównego przycisku "Zobacz cennik" (bez zmian)
    // =========================================================================
    const seePricingButton = document.querySelector('a[href="#pricingContainer"]');
    const pricingContainer = document.querySelector('#pricingContainer');

    if (seePricingButton && pricingContainer) {
        seePricingButton.addEventListener('click', function(event) {
            event.preventDefault();
            pricingContainer.addEventListener('shown.bs.collapse', () => pricingContainer.scrollIntoView({ behavior: 'smooth', block: 'start' }), { once: true });
            bootstrap.Collapse.getOrCreateInstance(pricingContainer).toggle();
        });
    }

    // =========================================================================
    // SEKCJA 3: Logika dla FORMULARZY KONTAKTOWYCH (bez zmian)
    // =========================================================================
    function handleFormSubmit(formId, fields) { /* ... kod bez zmian ... */ }
    handleFormSubmit("contactForm", { name: "name", email: "email", message: "message" });
    handleFormSubmit("contactFormInspired", { name: "contactName", email: "contactEmail", message: "contactMessage" });

    // =========================================================================
    // SEKCJA 4: NOWA, ULEPSZONA LOGIKA DLA FAQ
    // =========================================================================

    /**
     * Funkcja, która otwiera cennik i przewija do sekcji FAQ.
     * Będziemy jej używać w dwóch różnych miejscach.
     */
    function handleFaqScroll() {
        const faqSection = document.getElementById('faq-cennik');
        const pricingContainerToOpen = document.querySelector('#pricingDetailsSolo');

        if (faqSection && pricingContainerToOpen) {
            const collapse = bootstrap.Collapse.getOrCreateInstance(pricingContainerToOpen);
            
            // Jeśli kontener jest już otwarty, po prostu przewiń
            if (pricingContainerToOpen.classList.contains('show')) {
                scrollToWithOffset(faqSection);
            } else {
                // Jeśli jest zamknięty, najpierw go otwórz, a potem przewiń
                pricingContainerToOpen.addEventListener('shown.bs.collapse', () => scrollToWithOffset(faqSection), { once: true });
                collapse.show();
            }
        }
    }

    // SCENARIUSZ 1: Użytkownik wchodzi na stronę z innej podstrony
    // (Sprawdzamy hash w URL po załadowaniu strony)
    if (window.location.hash === '#faq-cennik') {
        setTimeout(handleFaqScroll, 300); // czas możesz dostroić
    }

    // SCENARIUSZ 2: Użytkownik jest już na stronie cennik.html i klika "FAQ"
    // (Dodajemy aktywny listener na przycisk)
    const faqButton = document.querySelector('a[href="cennik.html#faq-cennik"]');
    if (faqButton) {
        faqButton.addEventListener('click', function(event) {
            // Sprawdzamy, czy na pewno jesteśmy na stronie cennika
            if (window.location.pathname.endsWith('cennik.html')) {
                // Zapobiegamy domyślnemu "skokowi" przeglądarki
                event.preventDefault();
                // Ręcznie uruchamiamy naszą funkcję
                handleFaqScroll();
            }
            // Jeśli nie jesteśmy na cennik.html (np. na index.html),
            // link zadziała normalnie i przeniesie nas na cennik.html#faq-cennik,
            // a wtedy zadziała SCENARIUSZ 1.
        });
    }

    const pricingCarousel = document.querySelector('#pricingCarousel');
    const tabIds = ['#solo-tab', '#duo-tab', '#trio-tab', '#quadro-tab']; // wg kolejności slajdów

    if (pricingCarousel) {
        pricingCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeIndex = event.to; // indeks aktywnego slajdu (0–3)
            const targetTabId = tabIds[activeIndex];
            const targetTab = document.querySelector(targetTabId);

            if (targetTab) {
                new bootstrap.Tab(targetTab).show();
            }
        });
    }


}); // Koniec DOMContentLoaded
// js/scripts.js - JEDEN PLIK DO WSZYSTKIEGO

// POPRAWKA: Ten listener jest teraz na zewnątrz i jest tylko jeden.
// Uruchomi się po załadowaniu całej strony i wszystkich zasobów.
window.addEventListener('load', () => {
    // Sprawdzamy, czy weszliśmy na stronę z linku z kotwicą do FAQ
    if (window.location.hash === '#faq-cennik') {
        // Dajemy małe opóźnienie, aby animacje miały szansę się zakończyć, jeśli jakieś są
        setTimeout(() => {
            // Sprawdzamy, czy funkcja handleFaqScroll już istnieje (bo jest definiowana w DOMContentLoaded)
            if (typeof handleFaqScroll === 'function') {
                handleFaqScroll();
            }
        }, 100); 
    }
});

// Ta funkcja musi być zadeklarowana w globalnym zakresie, aby listener 'load' mógł ją znaleźć
let handleFaqScroll;

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
    // SEKCJA 4: Definicja funkcji FAQ (przeniesiona wyżej dla czytelności)
    // =========================================================================
    handleFaqScroll = function() { // POPRAWKA: przypisujemy do globalnej zmiennej
        const faqSection = document.getElementById('faq-cennik');
        const pricingContainerToOpen = document.querySelector('#pricingDetailsSolo');

        if (faqSection && pricingContainerToOpen) {
            const collapse = bootstrap.Collapse.getOrCreateInstance(pricingContainerToOpen);
            
            if (pricingContainerToOpen.classList.contains('show')) {
                scrollToWithOffset(faqSection);
            } else {
                pricingContainerToOpen.addEventListener('shown.bs.collapse', () => scrollToWithOffset(faqSection), { once: true });
                collapse.show();
            }
        }
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
    // SEKCJA 4: Logika dla FAQ (kontynuacja)
    // =========================================================================

    // SCENARIUSZ 2: Użytkownik jest już na stronie cennik.html i klika "FAQ"
    const faqButton = document.querySelector('a[href="cennik.html#faq-cennik"]');
    if (faqButton) {
        faqButton.addEventListener('click', function(event) {
            if (window.location.pathname.endsWith('cennik.html')) {
                event.preventDefault();
                handleFaqScroll();
            }
        });
    }

    // =========================================================================
    // Logika dla karuzeli w cenniku
    // =========================================================================
    const pricingCarousel = document.querySelector('#pricingCarousel');
    const tabIds = ['#solo-tab', '#duo-tab', '#trio-tab', '#quadro-tab'];

    if (pricingCarousel) {
        pricingCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeIndex = event.to;
            const targetTabId = tabIds[activeIndex];
            const targetTab = document.querySelector(targetTabId);

            if (targetTab) {
                // UWAGA: Usunąłem stąd linię, która przewijała do FAQ. 
                // Jeśli to było celowe, możesz ją przywrócić.
                // setTimeout(() => handleFaqScroll(), 400); 
                new bootstrap.Tab(targetTab).show();
            }
        });
    }

}); // Koniec DOMContentLoaded
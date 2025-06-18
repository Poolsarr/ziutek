// js/scripts.js - JEDEN PLIK DO WSZYSTKIEGO

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // SEKCJA 1: Logika dla rozwijanego CENNIKA
    // (Przełączanie tabów i inteligentne przewijanie z uwzględnieniem navbar)
    // =========================================================================
    const pricingButtons = document.querySelectorAll('a[data-tab-target]');
    const detailsSection = document.querySelector('#pricingDetailsSolo');
    
    // --- POCZĄTEK ZMIAN ---

    // ZMIANA 1: Znajdź navbar i pobierz jego wysokość.
    // WAŻNE: Upewnij się, że selektor '.navbar' jest prawidłowy dla Twojego paska nawigacji.
    // Może to być np. 'header', '#main-nav' itp.
    const navbar = document.querySelector('.navbar'); 
    const navbarHeight = navbar ? navbar.offsetHeight : 70; // Użyj faktycznej wysokości lub wartości domyślnej

    /**
     * Funkcja pomocnicza do przewijania z offsetem dla paska nawigacji.
     * @param {HTMLElement} element - Element, do którego chcemy przewinąć.
     */
    function scrollToWithOffset(element) {
        // Oblicz pozycję elementu względem góry dokumentu
        const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
        // Odejmij wysokość paska nawigacji i mały margines dla estetyki
        const offsetPosition = elementPosition - navbarHeight - 20; // 20px dodatkowego marginesu

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }

    // --- KONIEC ZMIAN ---


    if (pricingButtons.length > 0 && detailsSection) {
        pricingButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                // Zatrzymaj domyślną akcję linku, aby przejąć kontrolę
                event.preventDefault();

                // Przełącz na odpowiednią zakładkę (tab)
                const tabTargetId = this.getAttribute('data-tab-target');
                const tabToActivate = document.querySelector(tabTargetId);
                if (tabToActivate) {
                    const tab = new bootstrap.Tab(tabToActivate);
                    tab.show();
                }

                // Sprawdź, czy sekcja ze szczegółami jest już otwarta
                const isAlreadyOpen = detailsSection.classList.contains('show');

                if (isAlreadyOpen) {
                    // Jeśli tak, po prostu do niej przewiń z uwzględnieniem offsetu
                    // ZMIANA 2: Użyj nowej funkcji zamiast scrollIntoView
                    scrollToWithOffset(detailsSection);
                } else {
                    // Jeśli nie, poczekaj aż się otworzy i dopiero wtedy przewiń
                    detailsSection.addEventListener('shown.bs.collapse', function () {
                        // ZMIANA 3: Użyj nowej funkcji również tutaj
                        scrollToWithOffset(detailsSection);
                    }, { once: true });
                }
            });
        });
    }

    // =========================================================================
    // SEKCJA 2: Logika dla głównego przycisku "Zobacz cennik"
    // (Ta sekcja nie wymagała zmian, ale zostawiam dla kompletności)
    // =========================================================================
    const seePricingButton = document.querySelector('a[href="#pricingContainer"]');
    const pricingContainer = document.querySelector('#pricingContainer');
    const processSection = document.querySelector('#proces-tworzenia'); // Upewnij się, że masz to ID w HTML

    if (seePricingButton && pricingContainer && processSection) {
        seePricingButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Nasłuchuj na zakończenie animacji otwierania
            pricingContainer.addEventListener('shown.bs.collapse', function() {
                pricingContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, { once: true });

            // Nasłuchuj na zakończenie animacji zamykania
            pricingContainer.addEventListener('hidden.bs.collapse', function() {
                processSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, { once: true });

            // Ręcznie aktywuj rozwinięcie/zwinięcie
            const collapseInstance = bootstrap.Collapse.getOrCreateInstance(pricingContainer);
            collapseInstance.toggle();
        });
    }

    // =========================================================================
    // SEKCJA 3: Logika dla FORMULARZY KONTAKTOWYCH
    // (Ta sekcja nie wymagała zmian)
    // =========================================================================
    function handleFormSubmit(formId, fields) {
        const form = document.getElementById(formId);
        if (form) { // Dodano sprawdzenie, czy formularz istnieje
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const name = document.getElementById(fields.name).value;
                const email = document.getElementById(fields.email).value;
                const message = document.getElementById(fields.message).value;

                const recipient = "eyestory@email.pl"; // Zmień na swój adres e-mail
                const subject = encodeURIComponent("Wiadomość z formularza kontaktowego");
                const body = encodeURIComponent(`Imię i nazwisko: ${name}\nEmail: ${email}\n\n${message}`);

                window.location.href = `mailto:${recipient}?subject=${subject}&body=${body}`;
            });
        }
    }

    // Obsługa pierwszego formularza (jeśli istnieje)
    handleFormSubmit("contactForm", {
        name: "name",
        email: "email",
        message: "message"
    });

    // Obsługa drugiego formularza w cenniku
    handleFormSubmit("contactFormInspired", {
        name: "contactName",
        email: "contactEmail",
        message: "contactMessage"
    });

}); // <-- TEN WIERSZ ZAMYKA GŁÓWNY I JEDYNY DOMContentLoaded
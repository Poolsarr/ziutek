// js/scripts.js - JEDEN PLIK DO WSZYSTKIEGO

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // SEKCJA 1: Logika dla rozwijanego CENNIKA
    // (Przełączanie tabów i inteligentne przewijanie)
    // =========================================================================
    const pricingButtons = document.querySelectorAll('a[data-tab-target]');
    const detailsSection = document.querySelector('#pricingDetailsSolo');

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
                    // Jeśli tak, po prostu do niej przewiń
                    detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    // Jeśli nie, poczekaj aż się otworzy i dopiero wtedy przewiń
                    detailsSection.addEventListener('shown.bs.collapse', function () {
                        detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, { once: true });
                }
            });
        });
    }

    // =========================================================================
    // SEKCJA 2: Logika dla głównego przycisku "Zobacz cennik"
    // (Przewijanie do cennika przy otwieraniu i do sekcji "Proces" przy zamykaniu)
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
    // (Otwieranie klienta poczty)
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
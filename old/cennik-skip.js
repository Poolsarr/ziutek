document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // BLOK 1: Kod do obsługi przełączania tabów w cenniku (ten już masz)
    // =========================================================================
    const pricingButtons = document.querySelectorAll('a[data-tab-target]');
    const detailsSection = document.querySelector('#pricingDetailsSolo');

    if (detailsSection) {
        pricingButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const tabTargetId = this.getAttribute('data-tab-target');
                const tabToActivate = document.querySelector(tabTargetId);
                if (tabToActivate) {
                    const tab = new bootstrap.Tab(tabToActivate);
                    tab.show();
                }
                const isAlreadyOpen = detailsSection.classList.contains('show');
                if (isAlreadyOpen) {
                    detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    detailsSection.addEventListener('shown.bs.collapse', function () {
                        detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, { once: true });
                }
            });
        });
    }

    // =========================================================================
    // BLOK 2: NOWY KOD do obsługi przycisku "Zobacz cennik"
    // =========================================================================
    const seePricingButton = document.querySelector('a[href="#pricingContainer"]');
    const pricingContainer = document.querySelector('#pricingContainer');
    const processSection = document.querySelector('#proces-tworzenia'); // Odwołanie do sekcji z nowym ID

    if (seePricingButton && pricingContainer && processSection) {
        seePricingButton.addEventListener('click', function(event) {
            // 1. Zawsze zatrzymujemy domyślną akcję linku
            event.preventDefault();

            // 2. Sprawdzamy, czy cennik jest WŁAŚNIE OTWIERANY
            // Nasłuchujemy na event 'show.bs.collapse', który odpala się na początku animacji otwierania
            pricingContainer.addEventListener('shown.bs.collapse', function() {
                pricingContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, { once: true });

            // 3. Sprawdzamy, czy cennik jest WŁAŚNIE ZAMYKANY
            // Nasłuchujemy na event 'hidden.bs.collapse', który odpala się po zakończeniu animacji zamykania
            pricingContainer.addEventListener('hidden.bs.collapse', function() {
                processSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, { once: true });

            // 4. Ręcznie aktywujemy przełączenie (rozwinięcie/zwinięcie)
            const collapseInstance = bootstrap.Collapse.getOrCreateInstance(pricingContainer);
            collapseInstance.toggle();
        });
    }

});
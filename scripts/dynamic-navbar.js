document.addEventListener('DOMContentLoaded', function () {
    const navCollapse = document.getElementById('navbarNav');

    if (navCollapse) {
        const navLinks = navCollapse.querySelectorAll('.nav-link, .btn-custom-green');
        const bsCollapse = new bootstrap.Collapse(navCollapse, {
            toggle: false
        });

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                // Sprawdzamy, czy menu jest aktualnie rozwinięte i czy kliknięto w coś, co ma href
                if (navCollapse.classList.contains('show') && this.hasAttribute('href')) {
                    
                    const href = this.getAttribute('href');
                    
                    // KROK 1: Zatrzymaj domyślną akcję ORAZ zatrzymaj "bąbelkowanie" zdarzenia w górę DOM.
                    // To zapobiega konfliktom z wewnętrznymi listenerami Bootstrapa.
                    event.preventDefault();
                    event.stopPropagation();

                    // KROK 2: Ustawiamy JEDNORAZOWY listener, który odpali się po zakończeniu animacji zamykania.
                    navCollapse.addEventListener('hidden.bs.collapse', function onHide() {
                        // KROK 3: Po zakończeniu animacji, ręcznie wykonaj akcję.
                        window.location.href = href;
                    }, { once: true });

                    // KROK 4: Rozpocznij animację zamykania menu.
                    bsCollapse.hide();
                }
            });
        });
    }
});
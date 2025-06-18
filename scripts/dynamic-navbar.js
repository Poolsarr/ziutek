document.addEventListener('DOMContentLoaded', function () {
    // ... Twoje istniejące sekcje 1, 2, 3 ...

    // =========================================================================
    // SEKCJA 4: Ulepszenie mobilnej nawigacji
    // (Automatyczne zamykanie menu po kliknięciu linku)
    // =========================================================================
    const navCollapse = document.getElementById('navbarNav');
    
    // Upewniamy się, że menu istnieje na stronie
    if (navCollapse) {
        // Pobieramy WSZYSTKIE klikalne elementy wewnątrz menu
        const navLinks = navCollapse.querySelectorAll('.nav-link, .btn-custom-green');
        
        // Tworzymy instancję Collapse z Bootstrapa, aby móc używać jej metod (np. .hide())
        const bsCollapse = new bootstrap.Collapse(navCollapse, {
            toggle: false // Zapobiega dziwnemu zachowaniu przy pierwszym załadowaniu
        });

        // Do każdego linku w menu dodajemy listener
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                // Sprawdzamy, czy menu jest AKTUALNIE otwarte (ma klasę .show)
                if (navCollapse.classList.contains('show')) {
                    // Jeśli tak, używamy wbudowanej metody Bootstrapa, aby je płynnie zamknąć.
                    // To uruchomi tę samą, poprawną animację, ale w drugą stronę.
                    bsCollapse.hide();
                }
            });
        });
    }

}); // <-- Koniec głównej funkcji
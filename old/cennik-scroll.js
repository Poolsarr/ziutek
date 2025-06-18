document.addEventListener('DOMContentLoaded', function () {
    // Znajdź wszystkie przyciski "Dowiedz się więcej"
    const pricingButtons = document.querySelectorAll('a[data-tab-target]');
    
    // Znajdź główny kontener sekcji ze szczegółami
    const detailsSection = document.querySelector('#pricingDetailsSolo');

    // Upewnij się, że sekcja istnieje, aby uniknąć błędów
    if (!detailsSection) {
        return;
    }

    pricingButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // KROK 1: Zatrzymaj domyślną, błędną akcję przewijania
            event.preventDefault();

            // KROK 2: Przełącz taba (ta część kodu pozostaje bez zmian)
            const tabTargetId = this.getAttribute('data-tab-target');
            const tabToActivate = document.querySelector(tabTargetId);
            if (tabToActivate) {
                const tab = new bootstrap.Tab(tabToActivate);
                tab.show();
            }

            // KROK 3: Sprawdź, czy sekcja jest już widoczna
            const isAlreadyOpen = detailsSection.classList.contains('show');

            if (isAlreadyOpen) {
                // Jeśli jest już otwarta, po prostu przewiń do niej
                detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // Jeśli jest zamknięta, poczekaj na specjalny event od Bootstrapa,
                // który odpali się, gdy animacja rozwijania się ZAKOŃCZY.
                detailsSection.addEventListener('shown.bs.collapse', function () {
                    // Dopiero teraz, gdy sekcja jest w pełni widoczna, przewiń do niej.
                    detailsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, { once: true }); // {once: true} sprawia, że ten listener zadziała tylko raz
            }

            // KROK 4: Ręcznie aktywuj/zwiń sekcję (Bootstrap zrobi to za nas dzięki data-bs-toggle)
            // Nie musimy nic więcej robić, atrybut data-bs-toggle="collapse" w HTML wciąż działa.
        });
    });
});
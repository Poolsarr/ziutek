// Użyj tego jednego, kompletnego skryptu w swoim pliku (np. main.js)
document.addEventListener('DOMContentLoaded', function() {

    // --- KROK 1: Dynamiczne obliczanie offsetu ---
    // Znajdź swój pasek nawigacji. Upewnij się, że selektor '.navbar' jest prawidłowy.
    const navbar = document.querySelector('.navbar');
    
    // Oblicz wysokość offsetu: wysokość paska nawigacji + dodatkowy margines.
    // Jeśli navbar nie zostanie znaleziony, użyj domyślnej wartości (np. 100px).
    const offset = navbar ? navbar.offsetHeight + 2 : 100; // 20px to dodatkowy margines

    /**
     * Główna funkcja do płynnego przewijania z uwzględnieniem offsetu.
     * @param {string} targetId - ID elementu docelowego, np. "#eye-story-section".
     */
    function smoothScrollTo(targetId) {
        // Zabezpieczenie przed pustymi linkami href="#"
        if (!targetId || targetId === '#') {
            return;
        }

        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            // Oblicz pozycję, do której strona ma się przewinąć
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            // Wykonaj płynne przewinięcie
            window.scrollTo({
                top: offsetPosition,
                behavior: "smooth"
            });
        }
    }

    // --- KROK 2: Obsługa kliknięć w linki na TEJ SAMEJ stronie ---
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Działaj tylko dla prawdziwych linków-kotwic, ignoruj href="#"
            if (targetId !== '#') {
                e.preventDefault(); // Zatrzymaj domyślne zachowanie przeglądarki
                smoothScrollTo(targetId); // Użyj naszej funkcji do przewijania
            }
        });
    });

    // --- KROK 3: Obsługa ładowania strony Z INNEJ STRONY bezpośrednio do kotwicy ---
    // Sprawdza, czy w adresie URL jest kotwica (np. index.html#eye-story-section)
    if (window.location.hash) {
        // Używamy setTimeout, aby dać przeglądarce chwilę na renderowanie strony.
        // To zapewnia, że przewinięcie odbędzie się po załadowaniu wszystkich elementów.
        setTimeout(() => {
            smoothScrollTo(window.location.hash);
        }, 100);
    }
});
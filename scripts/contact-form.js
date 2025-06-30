document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('contactForm');
    const formStatus = document.getElementById('form-status');

    // Zmieniamy na funkcję asynchroniczną dla lepszej obsługi
    async function handleSubmit(event) {
        event.preventDefault(); // Zawsze zatrzymujemy domyślną akcję formularza

        // 1. Sprawdzamy, czy użytkownik zaznaczył reCAPTCHA v2
        const recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            formStatus.innerHTML = "Proszę zaznaczyć, że nie jesteś robotem.";
            formStatus.style.color = "red";
            return; // Przerywamy wysyłanie, jeśli CAPTCHA nie jest zaznaczona
        }

        const data = new FormData(event.target);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Blokujemy przycisk i informujemy o wysyłaniu
        submitButton.innerHTML = 'Wysyłanie...';
        submitButton.disabled = true;
        formStatus.innerHTML = ""; // Czyścimy poprzedni status

        try {
            // 2. Wysyłamy dane na Twój endpoint Formspree
            const response = await fetch(form.action, {
                method: form.method,
                body: data,
                headers: {
                    'Accept': 'application/json' // Formspree zaleca ten nagłówek dla odpowiedzi w formacie JSON
                }
            });

            // 3. Obsługujemy odpowiedź z Formspree
            if (response.ok) {
                // Sukces!
                formStatus.innerHTML = "Wiadomość została wysłana! Dziękuję.";
                formStatus.style.color = "green";
                form.reset(); // Czyścimy pola formularza
                // 4. Resetujemy również widget reCAPTCHA
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            } else {
                // Błąd po stronie serwera (np. błąd walidacji)
                const responseData = await response.json();
                if (Object.hasOwn(responseData, 'errors')) {
                    formStatus.innerHTML = responseData.errors.map(error => error.message).join(", ");
                } else {
                    formStatus.innerHTML = "Coś poszło nie tak. Spróbuj ponownie.";
                }
                formStatus.style.color = "red";
            }
        } catch (error) {
            // Błąd sieciowy
            console.error("Błąd wysyłania:", error);
            formStatus.innerHTML = "Wystąpił błąd sieci. Spróbuj ponownie później.";
            formStatus.style.color = "red";
        } finally {
            // Przywracamy przycisk do stanu pierwotnego
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        }
    }

    form.addEventListener("submit", handleSubmit);
});
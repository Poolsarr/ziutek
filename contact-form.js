document.addEventListener("DOMContentLoaded", function() {
    function handleFormSubmit(formId, fields) {
        const form = document.getElementById(formId);
        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const name = document.getElementById(fields.name).value;
            const email = document.getElementById(fields.email).value;
            const message = document.getElementById(fields.message).value;

            const recipient = "eyestory@email.pl";
            const subject = encodeURIComponent("Wiadomość z formularza kontaktowego");
            const body = encodeURIComponent(`Imię i nazwisko: ${name}\nEmail: ${email}\n\n${message}`);

            window.location.href = `mailto:${recipient}?subject=${subject}&body=${body}`;
        });
    }

    // Obsługa pierwszego formularza
    handleFormSubmit("contactForm", {
        name: "name",
        email: "email",
        message: "message"
    });

    // Obsługa drugiego formularza
    handleFormSubmit("contactFormInspired", {
        name: "contactName",
        email: "contactEmail",
        message: "contactMessage"
    });
});

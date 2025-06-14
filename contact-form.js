document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("contactForm").addEventListener("submit", function(e){
        e.preventDefault();

        const name = document.getElementById("name").value;
        const message = document.getElementById("message").value;

        const recipient = "eyestory@email.pl";
        const subject = encodeURIComponent("Wiadomość z formularza kontaktowego");
        const body = encodeURIComponent(`${message}\n\ ${name}`);

        window.location.href = `mailto:${recipient}?subject=${subject}&body=${body}`;
    });
});

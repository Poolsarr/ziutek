document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('#eyeStoryCarouselBootstrap');
    if (!carousel) return;

    // Inicjalizacja karuzeli, jeśli nie została jeszcze zainicjowana
    let instance = bootstrap.Carousel.getInstance(carousel);
    if (!instance) {
        instance = new bootstrap.Carousel(carousel, {
            interval: 1000, // bez autoplay, możesz dać np. 5000 dla 5s
            ride: false,
            wrap: false // możesz ustawić true, jeśli ma zapętlać
        });
    }

    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    carousel.addEventListener('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
            instance.next();
        } else if (touchEndX > touchStartX + swipeThreshold) {
            instance.prev();
        }
    }
});
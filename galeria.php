<?php
    ini_set('display_errors', 1); // Zostaw do debugowania, usuń na produkcji
    error_reporting(E_ALL);

    // --- KONFIGURACJA ---
    $galleryDir = 'assets/galeria/';
    $fallbackFormats = ['jpg', 'jpeg', 'png', 'gif'];

    $files = scandir($galleryDir);
    $galleryItems = [];

    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($file === '.' || $file === '..' || is_dir($galleryDir . $file) || $ext === 'webp') {
            continue; // Pomijamy wszystko, co nie jest "oryginalnym" formatem
        }

        if (in_array($ext, $fallbackFormats)) {
            $basename = pathinfo($file, PATHINFO_FILENAME);
            $originalPath = $galleryDir . $file;
            $webpPath = $galleryDir . $basename . '.webp';

            // Jeśli plik webp nie istnieje, użyjemy oryginału w obu miejscach
            if (!file_exists($webpPath)) {
                $webpPath = $originalPath;
            }
            
            $galleryItems[$basename] = [
                'webp' => $webpPath,
                'fallback' => $originalPath,
            ];
        }
    }
    
    // Odwracamy klucze tablicy, aby najnowsze były pierwsze
    $galleryItems = array_reverse($galleryItems, true);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Profesjonalna makrofotografia tęczówki w Trójmieście. Unikalne zdjęcia oka i wyjątkowe wydruki. Sesje w Gdańsku, Gdyni i Sopocie.">
    <title>Galeria - Eyestory</title>

    <!-- Favicon -->     
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
    <link rel="manifest" href="/assets/icons/site.webmanifest">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://www.eyestory.pl/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="EyeStory">
    <meta property="og:description" content="Profesjonalna makrofotografia tęczówki w Trójmieście. Unikalne zdjęcia oka i wyjątkowe wydruki. Sesje w Gdańsku, Gdyni i Sopocie.">
    <meta property="og:image" content="https://opengraph.b-cdn.net/production/images/31e4bec6-299c-4649-b1d3-85b2231fb0fb.png?token=Bsw9IHIN9F53mAqw03CasqNkbvaL7I92Mowysojz2M8&height=978&width=978&expires=33287461452">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="eyestory.pl">
    <meta property="twitter:url" content="https://www.eyestory.pl/">
    <meta name="twitter:title" content="EyeStory">
    <meta name="twitter:description" content="Profesjonalna makrofotografia tęczówki w Trójmieście. Unikalne zdjęcia oka i wyjątkowe wydruki. Sesje w Gdańsku, Gdyni i Sopocie.">
    <meta name="twitter:image" content="https://opengraph.b-cdn.net/production/images/31e4bec6-299c-4649-b1d3-85b2231fb0fb.png?token=Bsw9IHIN9F53mAqw03CasqNkbvaL7I92Mowysojz2M8&height=978&width=978&expires=33287461452">


    <!--Site weryfikacja dla googla-->
    <meta name="google-site-verification" content="6jJ3ql7iKqt2s5dMiKPtwQEEzVNSX-ogO-sfiY41Qs4" />
    
    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="scripts/dynamic-navbar.js" defer></script>
    <script src="scripts/eye-story-scroll.js" defer></script>
</head>
<body>
    <!-- Nawigacja -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top">
        <div class="container-fluid px-lg-5">          
            <a class="navbar-brand" href="index.html">
                <picture>
                    <!-- Jest png a nie jpg fallback bo musi byc support dla transparency -->
                    <source type="image/webp" srcset="assets/logo-eyestory-1.webp">
                    <img src="assets/logo-eyestory-1.png" alt="Eyestory Logo" height="35">
                </picture>
            </a> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.html#eye-story-section">O nas</a></li>
                    <li class="nav-item"><a class="nav-link" href="cennik.php">Cennik</a></li>
                    <li class="nav-item"><a class="nav-link active" href="galeria.html">Galeria</a></li>
                    <li class="nav-item"><a class="nav-link" href="cennik.php#faq-cennik">FAQ</a></li>
                    <li class="nav-item"><a class="btn btn-custom-green" href="index.html#kontakt">Napisz do nas już teraz!</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="gallery-page-new mt-5 pt-4">
        <div class="container gallery-container">
            <div class="tz-gallery">
                <div class="row g-2 g-sm-4">        
                    <?php
                        $gridClasses = ['col-6 col-sm-6 col-md-4', 'col-6 col-sm-6 col-md-4', 'col-6 col-sm-6 col-md-4', 'col-6 col-sm-6 col-md-8', 'col-6 col-sm-6 col-md-4', 'col-6 col-sm-6 col-md-4'];
                        $gridCount = count($gridClasses);
                        $slideIndex = 0;

                        foreach ($galleryItems as $basename => $paths):
                            $currentGridClass = $gridClasses[$slideIndex % $gridCount];
                    ?>
                        <div class="<?php echo $currentGridClass; ?>">
                            <a class="lightbox" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="<?php echo $slideIndex; ?>">
                                <picture>
                                    <source type="image/webp" srcset="<?php echo htmlspecialchars($paths['webp']); ?>">
                                    <img src="<?php echo htmlspecialchars($paths['fallback']); ?>" alt="Zdjęcie z galerii: <?php echo htmlspecialchars($basename); ?>" loading="lazy">
                                </picture>
                            </a>
                        </div>
                    <?php
                            $slideIndex++;
                        endforeach;
                    ?>           
                </div>
            </div>
        </div>
    </main>

    <!-- Modal z karuzelą -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button> 
                    <div id="galleryCarousel" class="carousel slide">
                        <div class="carousel-inner">
                            <?php
                                $slideIndex = 0;
                                foreach ($galleryItems as $basename => $paths):
                                    $activeClass = ($slideIndex == 0) ? 'active' : '';
                            ?>
                                <div class="carousel-item <?php echo $activeClass; ?>">
                                    <picture>
                                        <source type="image/webp" srcset="<?php echo htmlspecialchars($paths['webp']); ?>">
                                        <img src="<?php echo htmlspecialchars($paths['fallback']); ?>" class="d-block w-100" alt="Zdjęcie z galerii: <?php echo htmlspecialchars($basename); ?>">
                                    </picture>
                                </div>
                            <?php
                                    $slideIndex++;
                                endforeach;
                            ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Poprzedni</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Następny</span>
                        </button>
                    </div>

                    <div id="carouselCounter" class="carousel-counter text-center py-2 fw-bold"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- Stopka                                                                    -->
    <!-- ========================================================================= -->

    <footer class="site-footer">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                
                <p class="col-md-4 mb-2 mb-md-0 text-center text-md-start">
                    © 2025 Ameiz Studio, All rights reserved.
                </p>

                <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                    <li class="ms-3">
                        <a class="text-body-secondary" href="#">
                            <svg class="bi" width="24" height="24"><use xlink:href="#instagram"/></svg>
                        </a>
                    </li>
                    <li class="ms-3">
                        <a class="text-body-secondary" href="#">
                            <svg class="bi" width="24" height="24"><use xlink:href="#facebook"/></svg>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </footer>
    
    <!-- Ikony SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="bootstrap" fill="currentColor" viewBox="0 0 118 94">
        <title>Bootstrap</title>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.509zM89.331 83.284c-3.859 0-7.24-1.299-9.94-3.56-2.69-2.26-4.51-5.4-4.51-9.284s1.82-7.024 4.51-9.284c2.7-2.26 6.08-3.56 9.94-3.56s7.24 1.299 9.94 3.56c2.69 2.26 4.51 5.4 4.51 9.284s-1.82 7.024-4.51 9.284c-2.7 2.26-6.08 3.56-9.94 3.56zM28.657 83.284c-3.859 0-7.24-1.299-9.94-3.56-2.69-2.26-4.51-5.4-4.51-9.284s1.82-7.024 4.51-9.284c2.7-2.26 6.08-3.56 9.94-3.56s7.24 1.299 9.94 3.56c2.69 2.26 4.51 5.4 4.51 9.284s-1.82 7.024-4.51 9.284c-2.7 2.26-6.08 3.56-9.94 3.56z"></path>
    </symbol>
    <symbol id="facebook" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0 0 3.603 0 8.049c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
    </symbol>
    <symbol id="instagram" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.703.01 5.556 0 5.829 0 8s.01 2.444.048 3.297c.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.556 15.99 5.829 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372.527-.205.973-.478 1.417-.923.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.444 16 10.171 16 8s-.01-2.444-.048-3.297c-.04-.852-.174-1.433-.372-1.942a3.916 3.916 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.444.01 10.171 0 8 0zm0 1.442c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.282.24.705.275 1.486.039.843.047 1.096.047 3.232s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.232s.008-2.389.046-3.232c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.843-.038 1.096-.047 3.232-.047z"/>
        <path d="M8 4.884c-1.74 0-3.116 1.376-3.116 3.116s1.376 3.116 3.116 3.116 3.116-1.376 3.116-3.116S9.74 4.884 8 4.884zm0 5.098c-1.09 0-1.982-.892-1.982-1.982s.892-1.982 1.982-1.982 1.982.892 1.982 1.982-.892 1.982-1.982 1.982zm4.11-6.19c-.38 0-.69.31-.69.69s.31.69.69.69.69-.31.69-.69-.31-.69-.69-.69z"/>
    </symbol>
    </svg>
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('galleryModal');
        const carouselEl = document.getElementById('galleryCarousel');
        const counterEl = document.getElementById('carouselCounter');
        if (modalEl && carouselEl && counterEl) {
                
            const carousel = new bootstrap.Carousel(carouselEl, {
                interval: false,
                wrap: true
            });

            const prevButton = carouselEl.querySelector('.carousel-control-prev');
            const nextButton = carouselEl.querySelector('.carousel-control-next');

            function updateCounter() {
                const items = carouselEl.querySelectorAll('.carousel-item');
                const activeItem = carouselEl.querySelector('.carousel-item.active');
                const activeIndex = Array.from(items).indexOf(activeItem);

                counterEl.textContent = `${activeIndex + 1} / ${items.length}`;
            }

            modalEl.addEventListener('show.bs.modal', function(event) {
                const triggerEl = event.relatedTarget;
                if (triggerEl) {
                    const slideTo = parseInt(triggerEl.getAttribute('data-bs-slide-to'), 10);
                    if (!isNaN(slideTo)) {
                        carousel.to(slideTo, {
                            transition: false
                        }); 
                    }
                }
            });

            modalEl.addEventListener('shown.bs.modal', updateCounter);

            carouselEl.addEventListener('slid.bs.carousel', updateCounter);

            if (prevButton && nextButton) {
                prevButton.addEventListener('click', function() {
                    console.log('Kliknięto PREV programowo');
                    carousel.prev();
                });

                nextButton.addEventListener('click', function() {
                    console.log('Kliknięto NEXT programowo');
                    carousel.next();
                });
            }
        }
    });
    </script>
</body>
</html>

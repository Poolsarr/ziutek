<?php
    // Ustawienie raportowania błędów - bardzo pomocne przy szukaniu problemów.
    // Na gotowej stronie można je wyłączyć lub logować do pliku.
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // --- BEZPIECZNE WCZYTYWANIE DANYCH ---

    // Ścieżka do pliku z danymi.
    // Upewnij się, że jest poprawna względem lokalizacji pliku cennik.php.
    // Jeśli cennik.php jest w głównym folderze, a CMS w podfolderze, to ta ścieżka jest OK.
    $dataFile = 'cms/data.json';
    
    // Inicjalizujemy zmienne jako puste tablice na wszelki wypadek.
    $products = [];
    $pricing_details = [];
    $faq = [];

    // Sprawdzamy, czy plik z danymi w ogóle istnieje.
    if (file_exists($dataFile)) {
        // Wczytujemy zawartość pliku.
        $jsonData = file_get_contents($dataFile);
        // Dekodujemy JSON do tablicy PHP.
        $data = json_decode($jsonData, true);

        // Sprawdzamy, czy dekodowanie się powiodło i czy dane nie są puste.
        if (is_array($data)) {
            // Bezpieczne przypisanie wartości.
            // Operator '??' (null coalescing) oznacza: "użyj wartości po lewej, a jeśli jej nie ma (jest null), użyj tej po prawej".
            $products = $data['products'] ?? [];
            $pricing_details = $data['pricing_details'] ?? [];
            $faq = $data['faq'] ?? [];
        } else {
             // Jeśli plik JSON jest uszkodzony, wyświetlamy błąd.
             echo "Błąd: Plik data.json jest uszkodzony lub ma nieprawidłowy format.";
        }
    } else {
        // Jeśli plik nie istnieje, wyświetlamy czytelny komunikat o błędzie.
        // `die()` zatrzymuje wykonywanie skryptu.
        die("BŁĄD KRYTYCZNY: Nie można znaleźć pliku z danymi pod ścieżką: " . htmlspecialchars($dataFile) . ". Sprawdź, czy ścieżka jest poprawna i czy plik istnieje na serwerze.");
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Profesjonalna makrofotografia tęczówki w Trójmieście. Unikalne zdjęcia oka i wyjątkowe wydruki. Sesje w Gdańsku, Gdyni i Sopocie.">
    <title>Cennik - Eyestory</title>

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
    
    <!-- Style zewnętrzne -->
    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="scripts/cennik.js" defer></script>
    <script src="scripts/dynamic-navbar.js" defer></script>
    <script src="scripts/eye-story-scroll.js" defer></script>

</head>
<body class="d-flex flex-column min-vh-100 body-light-theme">
    <!-- ========================================================================= -->
    <!-- Navbar (dostosowana do podstrony cennika)                                 -->
    <!-- ========================================================================= -->
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#eye-story-section">O nas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cennik.php">Cennik</a>
                    </li>
                    <li class="nav-item">                                            
                        <a class="nav-link" href="galeria.php">Galeria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cennik.php#faq-cennik">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-custom-green" href="index.html#kontakt">Napisz do nas już teraz!</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    

    <!-- Pusta przestrzeń pod stałą nawigacją, aby treść nie była zasłonięta -->
    <div style="margin-top: 30px;"></div>

    <main class="flex-grow-1" style="margin-top: 10px;">
        <!-- ========================================================================= -->
        <!-- CENNIK                                                                    -->
        <!-- ========================================================================= -->
        <section id="cennik">
            <div id="pricingContainer">
                <section class="pricing-section">     
                    <div class="container">
                        <h1 class="fw-bold mb-5 text-dark text-center">Tęczówki w komplecie.</h1>

                        <!-- =================================== -->
                        <!-- WERSJA NA DESKTOP (>768px)          -->
                        <!-- =================================== -->
                        <div class="row row-cols-1 row-cols-md-4 g-4 d-none d-md-flex">

                            <?php foreach ($products as $key => $product): ?>
                            <div class="col">
                                <div class="card border-0 text-center h-100 d-flex flex-column">
                                    <picture>
                                        <source type="image/webp" srcset="assets/cennik/cennik-<?php echo $key; ?>.webp">
                                        <img src="assets/cennik/cennik-<?php echo $key; ?>.jpg" class="card-img-top my-rounded mb-5 mx-auto d-block" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                    </picture>
                                    <div class="card-body px-2 flex-grow-1">
                                        <h5 class="fw-bold"><?php echo htmlspecialchars($product['title']); ?></h5>
                                        <p class="text-muted mb-1"><?php echo $product['description']; // Używamy bez htmlspecialchars, aby <br> działało ?></p>
                                        <p class="fw-bold mb-2 mt-2"><?php echo htmlspecialchars($product['price_from']); ?></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pb-4">
                                        <a href="#pricingDetailsSolo" class="btn btn-primary btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#pricingDetailsSolo" data-tab-target="#<?php echo $key; ?>-tab">Dowiedz się więcej</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div> <!-- /.row (desktop) -->

                        <!-- ===================================== -->
                        <!-- WERSJA NA URZĄDZENIA MOBILNE (<768px) -->
                        <!-- ===================================== -->
                        <div id="pricingCarousel" class="carousel slide d-md-none">
                            <div class="carousel-indicators indicators-pozycja">
                                <?php $i = 0; foreach ($products as $key => $product): ?>
                                <button type="button" data-bs-target="#pricingCarousel" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0) ? 'active' : ''; ?>" aria-current="<?php echo ($i == 0) ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
                                <?php $i++; endforeach; ?>
                            </div>
                            <div class="carousel-inner pb-4">
                                <?php $i = 0; foreach ($products as $key => $product): ?>
                                <div class="carousel-item <?php echo ($i == 0) ? 'active' : ''; ?>">
                                    <div class="card border-0 bg-transparent text-center w-75 mx-auto">
                                        <picture>
                                            <source type="image/webp" srcset="assets/cennik/cennik-<?php echo $key; ?>.webp">
                                            <img src="assets/cennik/cennik-<?php echo $key; ?>.jpg" class="card-img-top my-rounded mb-5 mx-auto d-block" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                        </picture>
                                        <div class="card-body px-2">
                                            <h5 class="fw-bold"><?php echo htmlspecialchars($product['title']); ?></h5>
                                            <p class="text-muted mb-1"><?php echo $product['description']; ?></p>
                                            <p class="fw-bold mb-2 mt-2"><?php echo htmlspecialchars($product['price_from']); ?></p>
                                            <a href="#pricingDetailsSolo" class="btn btn-primary btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#pricingDetailsSolo" data-tab-target="#<?php echo $key; ?>-tab">Dowiedz się więcej</a>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#pricingCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#pricingCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                            </button>
                        </div> <!-- /#pricingCarousel (mobile) -->

                        <p class="mt-5 text-muted small text-center">* Wersja cyfrowa <strong class="text-decoration-underline">za darmo</strong></p>
                    </div> <!-- /.container -->        
                </section> <!-- /.pricing-section -->

                <!-- =================================== -->
                <!-- CZĘŚĆ 2: SZCZEGÓŁY PRODUKTÓW (TABY) -->
                <!-- =================================== -->
                <section id="pricingDetailsSolo" class="collapse pricing-details-section bg-white text-dark">
                    <div class="py-5">
                        <div class="container">
                            
                            <!-- Nawigacja po tabach -->
                            <ul class="nav nav-tabs product-type-tabs mb-5" id="productTypeTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="solo-tab" data-bs-toggle="tab" data-bs-target="#solo-tab-pane" type="button" role="tab" aria-controls="solo-tab-pane" aria-selected="true">SOLO</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="duo-tab" data-bs-toggle="tab" data-bs-target="#duo-tab-pane" type="button" role="tab" aria-controls="duo-tab-pane" aria-selected="false">DUO</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="trio-tab" data-bs-toggle="tab" data-bs-target="#trio-tab-pane" type="button" role="tab" aria-controls="trio-tab-pane" aria-selected="false">TRIO</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="quadro-tab" data-bs-toggle="tab" data-bs-target="#quadro-tab-pane" type="button" role="tab" aria-controls="quadro-tab-pane" aria-selected="false">QUADRO</button>
                                </li>
                            </ul>

                            <!-- Zawartość tabów -->
                            <div class="tab-content" id="productTypeTabContent">

                                <!-- Tab: SOLO -->
                                <div class="tab-pane fade show active" id="solo-tab-pane" role="tabpanel" aria-labelledby="solo-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mb-4 mb-lg-0">
                                            <div id="soloCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/mockup-1-1.webp">
                                                            <img src="assets/cennik/mockup-1-1.jpg" alt="Wizualizacja produktu Solo 1" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/solo-mockup-1.webp">
                                                            <img src="assets/cennik/solo-mockup-1.jpg" alt="Wizualizacja produktu Solo 2" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#soloCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Poprzedni</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#soloCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Następny</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="pricing-options-wrapper">
                                                <h4 class="fw-bold mb-3">Rozmiar. <span class="text-muted">Który jest najważniejszy dla Ciebie?</span></h4>
                                                <?php foreach ($pricing_details['solo'] as $option): ?>
                                                <div class="price-option d-flex justify-content-between align-items-center p-3 mb-3">
                                                    <div>
                                                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($option['size']); ?></h6>
                                                        <p class="mb-0 text-muted small"><?php echo $option['dimensions']; ?></p>
                                                    </div>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($option['price']); ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                                <a href="index.html#kontakt" class="btn contact-btn-blue w-75 py-2 fs-5 mt-3">Skontaktuj się już teraz!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /#solo-tab-pane -->

                                <!-- Tab: DUO -->
                                <div class="tab-pane fade" id="duo-tab-pane" role="tabpanel" aria-labelledby="duo-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mb-4 mb-lg-0">
                                            <div id="duoCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/mockup-2-1.webp">
                                                            <img src="assets/cennik/mockup-2-1.jpg" alt="Wizualizacja produktu Duo 1" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/duo-mockup-2.webp">
                                                            <img src="assets/cennik/duo-mockup-2.jpg" alt="Wizualizacja produktu Duo 2" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#duoCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Poprzedni</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#duoCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Następny</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="pricing-options-wrapper">
                                                <h4 class="fw-bold mb-3">Rozmiar. <span class="text-muted">Który jest najważniejszy dla Ciebie?</span></h4>
                                                <?php foreach ($pricing_details['duo'] as $option): ?>
                                                <div class="price-option d-flex justify-content-between align-items-center p-3 mb-3">
                                                    <div>
                                                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($option['size']); ?></h6>
                                                        <p class="mb-0 text-muted small"><?php echo $option['dimensions']; ?></p>
                                                    </div>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($option['price']); ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                                <a href="index.html#kontakt" class="btn contact-btn-blue w-75 py-2 fs-5 mt-3">Skontaktuj się już teraz!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /#duo-tab-pane -->

                                <!-- Tab: TRIO -->
                                <div class="tab-pane fade" id="trio-tab-pane" role="tabpanel" aria-labelledby="trio-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mb-4 mb-lg-0">
                                            <div id="trioCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/mockup-3-1.webp">
                                                            <img src="assets/cennik/mockup-3-1.jpg" alt="Wizualizacja produktu Trio 1" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/trio-1.webp">
                                                            <img src="assets/cennik/trio-1.jpg" alt="Wizualizacja produktu Trio 2" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#trioCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Poprzedni</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#trioCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Następny</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="pricing-options-wrapper">
                                                <h4 class="fw-bold mb-3">Rozmiar. <span class="text-muted">Który jest najważniejszy dla Ciebie?</span></h4>
                                                <?php foreach ($pricing_details['trio'] as $option): ?>
                                                <div class="price-option d-flex justify-content-between align-items-center p-3 mb-3">
                                                    <div>
                                                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($option['size']); ?></h6>
                                                        <p class="mb-0 text-muted small"><?php echo $option['dimensions']; ?></p>
                                                    </div>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($option['price']); ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                                <a href="index.html#kontakt" class="btn contact-btn-blue w-75 py-2 fs-5 mt-3">Skontaktuj się już teraz!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /#trio-tab-pane -->

                                <!-- Tab: QUADRO -->
                                <div class="tab-pane fade" id="quadro-tab-pane" role="tabpanel" aria-labelledby="quadro-tab" tabindex="0">
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mb-4 mb-lg-0">
                                            <div id="quadroCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/mockup-4-1.webp">
                                                            <img src="assets/cennik/mockup-4-1.jpg" alt="Wizualizacja produktu Quadro 1" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <picture>
                                                            <source type="image/webp" srcset="assets/cennik/quadro-1.webp">
                                                            <img src="assets/cennik/quadro-1.jpg" alt="Wizualizacja produktu Quadro 2" class="product-image d-block w-100">
                                                        </picture>
                                                    </div>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#quadroCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Poprzedni</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#quadroCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Następny</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="pricing-options-wrapper">
                                                <h4 class="fw-bold mb-3">Rozmiar. <span class="text-muted">Który jest najważniejszy dla Ciebie?</span></h4>
                                                <?php foreach ($pricing_details['quadro'] as $option): ?>
                                                <div class="price-option d-flex justify-content-between align-items-center p-3 mb-3">
                                                    <div>
                                                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($option['size']); ?></h6>
                                                        <p class="mb-0 text-muted small"><?php echo $option['dimensions']; ?></p>
                                                    </div>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($option['price']); ?></span>
                                                </div>
                                                <?php endforeach; ?>
                                                <a href="index.html#kontakt" class="btn contact-btn-blue w-75 py-2 fs-5 mt-3">Skontaktuj się już teraz!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /#quadro-tab-pane -->

                            </div> <!-- /#productTypeTabContent -->
                        </div> <!-- /.container -->
                    </div> <!-- /.py-5 -->

                          
                    <!-- =================================== -->
                    <!-- CZĘŚĆ 3: SEKCJA FAQ Z KATEGORIAMI   -->
                    <!-- =================================== -->
                    <section id="faq-cennik" class="faq-section pt-4 pb-5 bg-white">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <h2 class="text-center fw-bold mb-5 text-dark">Częste pytania</h2>

                                    <?php if (!empty($faq)): // Sprawdzamy, czy w ogóle jest jakaś treść w FAQ ?>

                                        <!-- NAWIGACJA ZAKŁADEK (KATEGORIE) - generowana dynamicznie -->
                                        <ul class="nav nav-pills justify-content-center mb-4" id="faq-tabs" role="tablist">
                                            <?php 
                                            $i = 0;
                                            // Tworzymy mapę kluczy na czytelne nazwy
                                            $categoryNames = [
                                                'oferta' => 'Oferta i Ceny',
                                                'sesja' => 'Sesja Zdjęciowa',
                                                'proces' => 'Proces Zamówienia',
                                                'reklamacje' => 'Reklamacje i Zwroty'
                                            ];

                                            foreach ($faq as $catKey => $questions): 
                                                // Pomijamy puste kategorie
                                                if (empty($questions)) continue;
                                                
                                                $isActive = ($i == 0); // Pierwsza zakładka jest aktywna
                                            ?>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?php if ($isActive) echo 'active'; ?>" id="<?php echo $catKey; ?>-tab" data-bs-toggle="pill" data-bs-target="#<?php echo $catKey; ?>-content" type="button" role="tab" aria-controls="<?php echo $catKey; ?>-content" aria-selected="<?php echo $isActive ? 'true' : 'false'; ?>">
                                                    <?php echo $categoryNames[$catKey] ?? ucfirst($catKey); // Użyj nazwy z mapy lub z dużej litery ?>
                                                </button>
                                            </li>
                                            <?php 
                                                $i++;
                                            endforeach; 
                                            ?>
                                        </ul>

                                        <!-- TREŚĆ ZAKŁADEK - generowana dynamicznie -->
                                        <div class="tab-content" id="faq-tabs-content">
                                            <?php 
                                            $i = 0;
                                            foreach ($faq as $catKey => $questions): 
                                                if (empty($questions)) continue;

                                                $isActive = ($i == 0);
                                            ?>
                                            <!-- ZAKŁADKA: <?php echo strtoupper($catKey); ?> -->
                                            <div class="tab-pane fade <?php if ($isActive) echo 'show active'; ?>" id="<?php echo $catKey; ?>-content" role="tabpanel" aria-labelledby="<?php echo $catKey; ?>-tab">
                                                <div class="accordion" id="accordion-<?php echo $catKey; ?>">
                                                    
                                                    <?php foreach ($questions as $index => $qa): ?>
                                                    <!-- Pytanie <?php echo $index + 1; ?> -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $catKey; ?>-<?php echo $index; ?>">
                                                                <?php echo htmlspecialchars($qa['question']); ?>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-<?php echo $catKey; ?>-<?php echo $index; ?>" class="accordion-collapse collapse" data-bs-parent="#accordion-<?php echo $catKey; ?>">
                                                            <div class="accordion-body text-muted">
                                                                <?php echo nl2br(htmlspecialchars($qa['answer'])); // nl2br() zamienia znaki nowej linii na <br> ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                            <?php 
                                                $i++;
                                            endforeach; 
                                            ?>
                                        </div> <!-- /.tab-content -->

                                    <?php else: // Jeśli $faq jest puste ?>
                                        <p class="text-center text-muted">Brak pytań do wyświetlenia.</p>
                                    <?php endif; ?>

                                </div> <!-- /.col-lg-9 -->
                            </div> <!-- /.row -->
                        </div> <!-- /.container -->
                    </section> <!-- /#faq-cennik -->

                        

                    <!-- ========================================================================= -->
                    <!-- Kontakt                                                                   -->
                    <!-- ========================================================================= -->
                
                    <section id="kontakt" class="contact-form-section">
                        <div class="container">
                            <h3 class="section-title mb-4 text-dark">Masz dodatkowe pytania?<br>Napisz do mnie!</h2>
                            
                            <form id="contactForm" action="https://formspree.io/f/xwpbyogo" method="POST">

                                <div class="mb-3">
                                    <label for="name" class="form-label text-dark">Imię i Nazwisko <span>*</span></label>
                                    <input type="text" name="Imię i Nazwisko" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label text-dark">E-mail <span>*</span></label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="jan.kowalski@poczta.pl" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label text-dark">Wiadomość <span>*</span></label>
                                    <textarea name="Wiadomość" class="form-control" id="message" rows="5" placeholder="Zacznij pisać..." required></textarea>
                                </div>

                                <div class="mb-3 d-flex justify-content-center">
                                    <div class="g-recaptcha" data-sitekey="6LcS13IrAAAAAH7fNZM6z0-QI2Ca5jFw-g-vEXDk"></div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-secondary">PRZEŚLIJ</button>
                                </div>

                                <p id="form-status" class="text-center mt-3"></p>

                            </form>
                        </div>
                    </section>


                    <script src="https://www.google.com/recaptcha/api.js" defer></script>
                    <script src="scripts/contact-form.js" defer></script>

                </section> <!-- /#pricingDetailsSolo -->

            </div> <!-- /#pricingContainer -->
        </section> <!-- /#cennik -->
    </main>

            
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

</body>
</html>
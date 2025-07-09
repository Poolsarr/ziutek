<?php
// --- SETUP ---
session_start();
$password = 'siemaeniu339'; // Pamiętaj, aby to zmienić na produkcyjnym serwerze!
$galleryDir = '../assets/galeria/';
$dataFile = 'data.json';

// Bezpieczne wczytywanie danych z JSON
$data = [];
if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
}
// --- KONIEC SETUP ---

// Obsługa logowania i wylogowania
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $password) { $_SESSION['loggedin'] = true; header("Location: index.php"); exit; } else { $login_error = 'Nieprawidłowe hasło!'; }
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Zarządzania Treścią</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; } .container { max-width: 960px; }
        .thumbnail { width: 80px; height: 80px; object-fit: cover; }
        .tab-content { border: 1px solid #dee2e6; border-top: none; padding: 1.5rem; background-color: white; border-radius: 0 0 .375rem .375rem; }
        .sub-tab-content { padding-top: 1rem; }
    </style>
</head>
<body>
<div class="container pb-5">

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <!-- === WIDOK PO ZALOGOWANIU === -->
        <div class="d-flex justify-content-between align-items-center pt-4 mb-3">
            <h2 class="mb-0">Panel Zarządzania</h2>
            <a href="?logout=true" class="btn btn-outline-danger">Wyloguj</a>
        </div>

        <?php if(isset($_GET['status'])): // Komunikaty o statusie ?>
            <?php if($_GET['status'] == 'saved'): ?><div class="alert alert-success">Zmiany zostały zapisane!</div><?php endif; ?>
            <?php if($_GET['status'] == 'save_error'): ?><div class="alert alert-danger">Błąd podczas zapisywania zmian.</div><?php endif; ?>
            <?php if($_GET['status'] == 'success'): ?><div class="alert alert-success">Zdjęcia zostały pomyślnie przesłane!</div><?php endif; ?>
            <?php if($_GET['status'] == 'deleted'): ?><div class="alert alert-success">Plik został usunięty.</div><?php endif; ?>
        <?php endif; ?>

        <!-- GŁÓWNA NAWIGACJA W ZAKŁADKACH -->
        <ul class="nav nav-tabs" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="cennik-tab" data-bs-toggle="tab" data-bs-target="#cennik-pane" type="button" role="tab">Cennik</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="galeria-tab" data-bs-toggle="tab" data-bs-target="#galeria-pane" type="button" role="tab">Galeria</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-pane" type="button" role="tab">FAQ</button></li>
        </ul>

        <!-- GŁÓWNA ZAWARTOŚĆ ZAKŁADEK -->
        <div class="tab-content" id="mainTabContent">
            
            <!-- ### ZAKŁADKA 1: CENNIK ### -->
            <div class="tab-pane fade show active" id="cennik-pane" role="tabpanel">
                <form action="save_data.php" method="post">
                    <!-- Nawigacja pod-zakładek cennika -->
                    <ul class="nav nav-pills mb-3" id="pricingSubTab" role="tablist">
                        <?php $i = 0; foreach ($data['products'] as $key => $product): ?>
                        <li class="nav-item" role="presentation"><button class="nav-link <?php if($i==0) echo 'active'; ?>" data-bs-toggle="pill" data-bs-target="#pricing-<?php echo $key; ?>-pane" type="button"><?php echo htmlspecialchars($product['title']); ?></button></li>
                        <?php $i++; endforeach; ?>
                    </ul>
                    <!-- Zawartość pod-zakładek cennika -->
                    <div class="tab-content" id="pricingSubTabContent">
                        <?php $i = 0; foreach ($data['products'] as $key => $product): ?>
                        <div class="tab-pane fade <?php if($i==0) echo 'show active'; ?>" id="pricing-<?php echo $key; ?>-pane" role="tabpanel">
                            <h5>Pakiet: <?php echo htmlspecialchars($product['title']); ?></h5>
                            <input type="hidden" name="products[<?php echo $key; ?>][title]" value="<?php echo htmlspecialchars($product['title']); ?>">
                            <div class="mb-3">
                                <label class="form-label small">Opis (daj tag < br > dla newline'a)</label>
                                <textarea name="products[<?php echo $key; ?>][description]" class="form-control" rows="2"><?php echo htmlspecialchars(str_replace('<br>', "\n", $product['description'])); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small">Cena "Od"</label>
                                <input type="text" name="products[<?php echo $key; ?>][price_from]" class="form-control" value="<?php echo htmlspecialchars($product['price_from']); ?>">
                            </div>
                            
                            <h6>Szczegółowe ceny (rozmiary) dla <?php echo htmlspecialchars($product['title']); ?></h6>
                            <div id="pricing-list-<?php echo $key; ?>">
                                <?php if (isset($data['pricing_details'][$key])): foreach ($data['pricing_details'][$key] as $index => $detail): ?>
                                <div class="row g-2 mb-2 align-items-center pricing-item">
                                    <div class="col"><input type="text" name="pricing_details[<?php echo $key; ?>][<?php echo $index; ?>][size]" class="form-control form-control-sm" placeholder="Rozmiar (np. S (K))" value="<?php echo htmlspecialchars($detail['size']); ?>"></div>
                                    <div class="col"><input type="text" name="pricing_details[<?php echo $key; ?>][<?php echo $index; ?>][dimensions]" class="form-control form-control-sm" placeholder="Wymiary (np. 15x15 cm)" value="<?php echo htmlspecialchars(str_replace('<br>', ' ', $detail['dimensions'])); ?>"></div>
                                    <div class="col"><input type="text" name="pricing_details[<?php echo $key; ?>][<?php echo $index; ?>][price]" class="form-control form-control-sm" placeholder="Cena (np. 129 zł)" value="<?php echo htmlspecialchars($detail['price']); ?>"></div>
                                    <div class="col-auto"><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.pricing-item').remove()">X</button></div>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addPricingItem('<?php echo $key; ?>')">Dodaj rozmiar</button>
                        </div>
                        <?php $i++; endforeach; ?>
                    </div>
                    <div class="mt-4 text-center"><button type="submit" class="btn btn-primary">Zapisz zmiany w cenniku i FAQ</button></div>
                </form>
            </div>

            <!-- ### ZAKŁADKA 2: GALERIA ### -->
            <div class="tab-pane fade" id="galeria-pane" role="tabpanel">
                <h5>Wgraj nowe zdjęcia</h5>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="photos" name="photos[]" multiple required accept="image/*">
                        <button type="submit" class="btn btn-success">Prześlij na serwer</button>
                    </div>
                </form>
                <hr>
                <h5>Zarządzaj istniejącymi zdjęciami</h5>
                <ul class="list-group">
                    <?php
                    $imageFiles = [];
                    if (is_dir($galleryDir)) {
                        $files = array_diff(scandir($galleryDir), ['.', '..']);
                        foreach ($files as $file) {
                            if (is_file($galleryDir . $file) && !str_ends_with(strtolower($file), '.webp')) {
                                $imageFiles[] = $file;
                            }
                        }
                        $imageFiles = array_reverse($imageFiles);
                    }
                    if (empty($imageFiles)) { echo '<li class="list-group-item">Galeria jest pusta.</li>'; } 
                    else {
                        foreach ($imageFiles as $file) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo '<div><img src="' . htmlspecialchars($galleryDir . $file) . '" class="img-thumbnail thumbnail me-3" alt="Miniatura"><span>' . htmlspecialchars($file) . '</span></div>';
                            echo '<form action="delete.php" method="post" onsubmit="return confirm(\'Czy na pewno chcesz usunąć ten plik i jego wersję .webp?\');"><input type="hidden" name="filename" value="' . htmlspecialchars($file) . '"><button type="submit" class="btn btn-danger btn-sm">Usuń</button></form>';
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- ### ZAKŁADKA 3: FAQ ### -->
            <div class="tab-pane fade" id="faq-pane" role="tabpanel">
                 <form action="save_data.php" method="post">
                    <!-- Nawigacja pod-zakładek FAQ -->
                    <ul class="nav nav-pills mb-3" id="faqSubTab" role="tablist">
                        <?php $i = 0; foreach ($data['faq'] as $catKey => $questions): ?>
                        <li class="nav-item" role="presentation"><button class="nav-link <?php if($i==0) echo 'active'; ?>" data-bs-toggle="pill" data-bs-target="#faq-<?php echo $catKey; ?>-pane" type="button"><?php echo htmlspecialchars(ucfirst($catKey)); ?></button></li>
                        <?php $i++; endforeach; ?>
                    </ul>
                    <!-- Zawartość pod-zakładek FAQ -->
                    <div class="tab-content" id="faqSubTabContent">
                        <?php $i = 0; foreach ($data['faq'] as $catKey => $questions): ?>
                        <div class="tab-pane fade <?php if($i==0) echo 'show active'; ?>" id="faq-<?php echo $catKey; ?>-pane" role="tabpanel">
                            <div id="faq-list-<?php echo $catKey; ?>">
                            <?php foreach ($questions as $index => $qa): ?>
                                <div class="p-2 border rounded mb-2 faq-item">
                                    <input type="text" name="faq[<?php echo $catKey; ?>][<?php echo $index; ?>][question]" class="form-control form-control-sm mb-1" placeholder="Pytanie" value="<?php echo htmlspecialchars($qa['question']); ?>">
                                    <textarea name="faq[<?php echo $catKey; ?>][<?php echo $index; ?>][answer]" class="form-control form-control-sm" placeholder="Odpowiedź" rows="3"><?php echo htmlspecialchars($qa['answer']); ?></textarea>
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.closest('.faq-item').remove()">Usuń pytanie</button>
                                </div>
                            <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addFaqItem('<?php echo $catKey; ?>')">Dodaj pytanie</button>
                        </div>
                        <?php $i++; endforeach; ?>
                    </div>
                    <div class="mt-4 text-center"><button type="submit" class="btn btn-primary">Zapisz zmiany w cenniku i FAQ</button></div>
                 </form>
            </div>

        </div> <!-- /#mainTabContent -->

    <?php else: ?>
        <!-- === WIDOK LOGOWANIA === -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card"><div class="card-header"><h4 class="mb-0">Logowanie</h4></div><div class="card-body"><form action="index.php" method="post"><div class="mb-3"><label for="password" class="form-label">Hasło</label><input type="password" class="form-control" id="password" name="password" required></div><?php if ($login_error): ?><div class="alert alert-danger"><?php echo $login_error; ?></div><?php endif; ?><button type="submit" class="btn btn-primary w-100">Zaloguj</button></form></div></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Skrypty do dynamicznego dodawania i usuwania pól
function addPricingItem(productKey) {
    const list = document.getElementById('pricing-list-' + productKey);
    const index = Date.now(); // Używamy timestamp dla unikalnego indeksu
    const item = document.createElement('div');
    item.className = 'row g-2 mb-2 align-items-center pricing-item';
    item.innerHTML = `
        <div class="col"><input type="text" name="pricing_details[${productKey}][${index}][size]" class="form-control form-control-sm" placeholder="Rozmiar"></div>
        <div class="col"><input type="text" name="pricing_details[${productKey}][${index}][dimensions]" class="form-control form-control-sm" placeholder="Wymiary"></div>
        <div class="col"><input type="text" name="pricing_details[${productKey}][${index}][price]" class="form-control form-control-sm" placeholder="Cena"></div>
        <div class="col-auto"><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.pricing-item').remove()">X</button></div>
    `;
    list.appendChild(item);
}

function addFaqItem(catKey) {
    const list = document.getElementById('faq-list-' + catKey);
    const index = Date.now(); // Używamy timestamp dla unikalnego indeksu
    const item = document.createElement('div');
    item.className = 'p-2 border rounded mb-2 faq-item';
    item.innerHTML = `
        <input type="text" name="faq[${catKey}][${index}][question]" class="form-control form-control-sm mb-1" placeholder="Pytanie">
        <textarea name="faq[${catKey}][${index}][answer]" class="form-control form-control-sm" placeholder="Odpowiedź" rows="3"></textarea>
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.closest('.faq-item').remove()">Usuń pytanie</button>
    `;
    list.appendChild(item);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
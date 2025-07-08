<?php
session_start();
$password = 'siemaeniu339'; // Pamiętaj, aby używać swojego hasła!
$galleryDir = '../assets/galeria/';

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $login_error = 'Nieprawidłowe hasło!';
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 800px; }
        .card { margin-top: 30px; }
        .thumbnail { width: 80px; height: 80px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="container">

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <!-- === WIDOK PO ZALOGOWANIU === -->

            <!-- Formularz do przesyłania plików -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Wgraj zdjęcia</h4>
                    <a href="?logout=true" class="btn btn-sm btn-outline-danger">Wyloguj</a>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['status'])): ?>
                        <?php if($_GET['status'] == 'success'): ?>
                            <div class="alert alert-success">Zdjęcia zostały pomyślnie przesłane!</div>
                        <?php elseif($_GET['status'] == 'error'): ?>
                            <div class="alert alert-danger">Wystąpił błąd podczas przesyłania.</div>
                        <?php elseif($_GET['status'] == 'deleted'): ?>
                            <div class="alert alert-success">Plik(i) został usunięty.</div>
                        <?php elseif($_GET['status'] == 'delete_error'): ?>
                            <div class="alert alert-danger">Nie udało się usunąć pliku.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="photos" class="form-label">Wybierz zdjęcia (możesz zaznaczyć kilka naraz)</label>
                            <input class="form-control" type="file" id="photos" name="photos[]" multiple required accept="image/jpeg,image/png,image/gif">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Prześlij na serwer</button>
                    </form>
                </div>
            </div>

            <!-- SEKCJA ZARZĄDZANIA GALERIĄ (ZMIENIONA) -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Zarządzaj galerią</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        if (is_dir($galleryDir)) {
                            // Skanujemy katalog
                            $files = array_diff(scandir($galleryDir, SCANDIR_SORT_DESCENDING), ['.', '..']);
                            $originalFiles = [];

                            // Filtrujemy, aby zostawić tylko oryginalne pliki (nie .webp)
                            foreach ($files as $file) {
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                if ($ext !== 'webp' && is_file($galleryDir . $file)) {
                                    $originalFiles[] = $file;
                                }
                            }
                            
                            if (empty($originalFiles)) {
                                echo '<li class="list-group-item">Galeria jest pusta.</li>';
                            } else {
                                foreach ($originalFiles as $file) {
                                    // Ścieżka do miniatury. Używamy oryginału, bo to tylko podgląd.
                                    $thumbnailPath = htmlspecialchars($galleryDir . $file);
                                    
                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                    // Miniatura i nazwa pliku
                                    echo '<div>';
                                    echo '<img src="' . $thumbnailPath . '?' . time() . '" class="img-thumbnail thumbnail me-3" alt="Miniatura">';
                                    echo '<span>' . htmlspecialchars($file) . '</span>';
                                    echo '</div>';
                                    
                                    // Formularz z przyciskiem do usuwania
                                    echo '<form action="delete.php" method="post" onsubmit="return confirm(\'Czy na pewno chcesz usunąć to zdjęcie (JPG/PNG i WEBP)?\');">';
                                    // Wysyłamy do delete.php nazwę oryginalnego pliku. On już będzie wiedział, co zrobić.
                                    echo '<input type="hidden" name="filename" value="' . htmlspecialchars($file) . '">';
                                    echo '<button type="submit" class="btn btn-danger btn-sm">Usuń</button>';
                                    echo '</form>';
                                    echo '</li>';
                                }
                            }
                        } else {
                            echo '<li class="list-group-item text-danger">Błąd: Folder galerii nie istnieje!</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

        <?php else: ?>
            <!-- === WIDOK LOGOWANIA (bez zmian) === -->
            <div class="card">
                <div class="card-header"><h4 class="mb-0">Logowanie do panelu</h4></div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <?php if ($login_error): ?><div class="alert alert-danger"><?php echo $login_error; ?></div><?php endif; ?>
                        <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
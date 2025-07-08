<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die('BŁĄD: Brak autoryzacji!');
}

// --- KONFIGURACJA ---
$uploadDir = '../assets/galeria/';
$maxFileSize = 10 * 1024 * 1024; // Zwiększone do 10MB
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
$webpQuality = 80; // Jakość konwersji do WebP (0-100)
// --- KONIEC KONFIGURACJI ---

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die('BŁĄD: Nie można utworzyć folderu do zapisu plików!');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
    
    $files = $_FILES['photos'];
    $filesCount = count($files['name']);
    $hadErrors = false;

    for ($i = 0; $i < $filesCount; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) continue;

        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $files['tmp_name'][$i];
            
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $tmpName);
            finfo_close($fileInfo);

            if ($files['size'][$i] > $maxFileSize || !in_array($mimeType, $allowedMimeTypes)) {
                $hadErrors = true;
                continue;
            }

            $originalName = basename($files['name'][$i]);
            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
            $baseName = preg_replace("/[^a-zA-Z0-9_-]/", "", $baseName); // Oczyszczenie nazwy
            $originalExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $destinationOriginal = $uploadDir . $baseName . '.' . $originalExtension;
            $destinationWebp = $uploadDir . $baseName . '.webp';

            // Przenosimy oryginalny plik
            if (move_uploaded_file($tmpName, $destinationOriginal)) {
                // Konwersja do WebP
                try {
                    $image = null;
                    if ($mimeType === 'image/jpeg') {
                        $image = imagecreatefromjpeg($destinationOriginal);
                    } elseif ($mimeType === 'image/png') {
                        $image = imagecreatefrompng($destinationOriginal);
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    } elseif ($mimeType === 'image/gif') {
                        $image = imagecreatefromgif($destinationOriginal);
                    }

                    if ($image !== null) {
                        imagewebp($image, $destinationWebp, $webpQuality);
                        imagedestroy($image);
                    }
                } catch (Exception $e) {
                    // Jeśli konwersja się nie uda, kontynuujemy bez pliku webp
                    // Można dodać logowanie błędu
                }
            } else {
                $hadErrors = true;
            }

        } else {
            $hadErrors = true;
        }
    }

    if ($hadErrors) {
        header('Location: index.php?status=error');
    } else {
        header('Location: index.php?status=success');
    }
    exit;
} else {
    header('Location: index.php');
    exit;
}
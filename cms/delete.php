<?php
session_start();

// KROK 1: Sprawdzenie, czy użytkownik jest zalogowany. Krytyczne dla bezpieczeństwa!
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die('BŁĄD: Brak autoryzacji!');
}

// KROK 2: Sprawdzenie, czy żądanie jest typu POST i czy nazwa pliku została przesłana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {

    // Ścieżka do galerii - musi być taka sama jak w index.php i upload.php
    $galleryDir = '../assets/galeria/';
    
    // KROK 3: Zabezpieczenie nazwy pliku przed atakami Path Traversal (np. ../../plik.txt)
    // basename() usuwa wszelkie informacje o ścieżce, zostawiając samą nazwę pliku.
    $filename = basename($_POST['filename']);

    // Pełna ścieżka do pliku, który ma zostać usunięty
    $filePath = $galleryDir . $filename;

    // KROK 4: Sprawdzenie, czy plik istnieje i jego usunięcie
    if (file_exists($filePath) && is_file($filePath)) {
        // unlink() to funkcja PHP do usuwania plików
        if (unlink($filePath)) {
            // Jeśli się udało, przekieruj z powrotem z komunikatem o sukcesie
            header('Location: index.php?status=deleted');
            exit;
        }
    }
}

// Jeśli coś poszło nie tak (np. brak nazwy pliku, plik nie istnieje),
// przekieruj z komunikatem błędu.
header('Location: index.php?status=delete_error');
exit;
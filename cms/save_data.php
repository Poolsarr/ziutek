<?php
session_start();

// Bezpieczeństwo: tylko zalogowani mogą zapisywać
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die('BŁĄD: Brak autoryzacji!');
}

$dataFile = 'data.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Odczytujemy aktualne dane
    $data = json_decode(file_get_contents($dataFile), true);

    // Aktualizacja produktów
    if (isset($_POST['products'])) {
        foreach ($_POST['products'] as $key => $productData) {
            if (isset($data['products'][$key])) {
                $data['products'][$key]['title'] = $productData['title'];
                $data['products'][$key]['description'] = $productData['description'];
                $data['products'][$key]['price_from'] = $productData['price_from'];
            }
        }
    }

    // Aktualizacja szczegółów cenowych
    if (isset($_POST['pricing_details'])) {
        foreach ($_POST['pricing_details'] as $productKey => $details) {
            $newDetails = [];
            foreach ($details as $detail) {
                // Pomijamy puste wpisy, które mogły zostać dodane
                if (!empty($detail['size']) && !empty($detail['price'])) {
                    $newDetails[] = $detail;
                }
            }
            $data['pricing_details'][$productKey] = $newDetails;
        }
    }
    
    // Aktualizacja FAQ
    if (isset($_POST['faq'])) {
        foreach ($_POST['faq'] as $categoryKey => $questions) {
            $newQuestions = [];
            foreach ($questions as $qa) {
                if (!empty($qa['question']) && !empty($qa['answer'])) {
                    $newQuestions[] = $qa;
                }
            }
            $data['faq'][$categoryKey] = $newQuestions;
        }
    }

    // Zapisujemy z powrotem do pliku JSON
    // JSON_PRETTY_PRINT - dla czytelności pliku
    // JSON_UNESCAPED_UNICODE - dla polskich znaków
    if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        header('Location: index.php?status=saved');
    } else {
        header('Location: index.php?status=save_error');
    }
    exit;
}

// Przekieruj, jeśli ktoś wejdzie tu bezpośrednio
header('Location: index.php');
exit;
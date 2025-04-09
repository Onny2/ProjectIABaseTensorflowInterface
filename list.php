<?php
// list.php
header('Content-Type: application/json');

$jsonDir = 'saved/';
$uploadDir = 'uploads/';

if (!is_dir($jsonDir)) {
    echo json_encode([]);
    exit;
}

$files = array_diff(scandir($jsonDir), ['..', '.']);
$savedData = [];

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
        $content = file_get_contents($jsonDir . $file);
        $data = json_decode($content, true);
        if ($data) {
            // Converti il percorso dell'immagine in URL relativo
            $data['image'] = $data['image'];
            $savedData[] = $data;
        }
    }
}

echo json_encode($savedData);
?>
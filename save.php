<?php
// save.php
header('Content-Type: application/json');

// Cartella per salvare le immagini
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Cartella per salvare i JSON
$jsonDir = 'saved/';
if (!is_dir($jsonDir)) {
    mkdir($jsonDir, 0755, true);
}

// Ricevi i dati JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $className = $data['className'];
    $probability = $data['probability'];
    $timestamp = $data['timestamp'];
    $imageData = $data['image'];

    // Decodifica l'immagine
    if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
        $imageData = substr($imageData, strpos($imageData, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo json_encode(['success' => false, 'message' => 'Tipo di immagine non supportato']);
            exit;
        }

        $imageData = base64_decode($imageData);
        if ($imageData === false) {
            echo json_encode(['success' => false, 'message' => 'Base64 decode fallito']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Formato immagine non valido']);
        exit;
    }

    // Salva l'immagine
    $imageName = uniqid() . '.' . $type;
    $imagePath = $uploadDir . $imageName;
    if (!file_put_contents($imagePath, $imageData)) {
        echo json_encode(['success' => false, 'message' => 'Salvataggio immagine fallito']);
        exit;
    }

    // Prepara i dati da salvare nel JSON
    $savedData = [
        'className' => $className,
        'probability' => $probability,
        'timestamp' => $timestamp,
        'image' => $imagePath
    ];

    // Salva i dati nel file JSON
    $jsonName = uniqid() . '.json';
    $jsonPath = $jsonDir . $jsonName;
    if (!file_put_contents($jsonPath, json_encode($savedData, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => false, 'message' => 'Salvataggio JSON fallito']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Dati salvati correttamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Nessun dato ricevuto']);
}
?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Connessione a un server tramite una chiave API
define('API_KEY', ''); // Sostituisci con la tua chiave API

// Funzione per chiamare un'API generica
function call_api($endpoint, $data = [])
{
    $url = "https://esempio.sito.api/api/chat/completions" . $endpoint; // Cambia con l'URL dell'API se necessario

    // Aggiungi la chiave API negli headers
    $headers = [
        "Authorization: Bearer " . API_KEY,
        "Content-Type: application/json"
    ];

    // Crea la connessione cURL
    $ch = curl_init($url);

    // Imposta le opzioni di cURL
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers
    ]);

    // Esegui la richiesta
    $response = curl_exec($ch);

    // Controlla se ci sono errori con cURL
    if (curl_errno($ch)) {
        return ["error" => "Errore API: " . curl_error($ch)];
    }

    // Ottieni il codice di stato della risposta
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Chiudi la connessione
    curl_close($ch);

    // Se la risposta non è OK, ritorna l'errore
    if ($http_code !== 200) {
        return ["error" => "Errore API: Risposta HTTP " . $http_code . " - " . $response];
    }

    // Decodifica la risposta JSON
    $response_data = json_decode($response, true);

    // Se si verifica un errore nel JSON, ritorna l'errore
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "Errore JSON: " . json_last_error_msg()];
    }

    return $response_data;
}

// Gestione del POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codice_input = trim($_POST['codice'] ?? '');
    if (empty($codice_input)) {
        $result = "Il campo codice è obbligatorio.";
    } else {
        
        // Definisci i dati da inviare all'API secondo la struttura fornita
        if(isset($_POST['search'])){
            $data = [
            "stream" => false,
            "model" => "llama3-8b-8192", // Cambia con il modello desiderato
            "messages" => [
                [
                    "role" => "system",
                    "content" => "RISPONDI SENZA MAI AGGIUNGERE RIGHE VUOTE TRA I CONCETTI.
                    Rispondi senza righe vuote, sempre in lingua italiana, evita gli asterischi e NON lasciare righe vuote.
                    Fornisci informazioni dettagliate sui vari tipi di pesci senza lasciare righe vuote. Rispondi in modo conciso e diretto senza lasciare righe vuote, evitando prefissi inutili come 'ecco le informazioni sul' e senza lasciare righe vuote. 
                    Inizia sempre la risposta con il nome del pesce, e a capo, le informazioni.
                    Rispondi SEMPRE senza lasciare righe vuote.
                    Fornisci le risposte nel formato: 'Argomento: Spiegazione' SENZA LASCIARE RIGHE VUOTE
                    Fornisci le seguenti informazioni: nome scientifico, famiglia, gruppo taxonomico, habitat, dimensioni, dieta, stato di conservazione, importanza economica SENZA LASCIARE RIGHE VUOTE TI PREGOOO
                    NON. LASCIARE. RIGHE. VUOTE.
                    NON METTERE DEI <br>.
                    "
                ],
                [
                    "role" => "user",
                    "content" => "Dammi tutte le informazioni riguardo questo pesce senza utilizzare dei <br> e dei /n".$codice_input
                ]
            ],
            "params" => new stdClass(), // Puoi aggiungere parametri specifici se necessario
            "features" => [
                "web_search" => true
            ],
            "id" => uniqid() // Genera un ID univoco per la richiesta
        ];}
        else{
            $data = [
            "stream" => false,
            "model" => "llama3-8b-8192", // Cambia con il modello desiderato
            "messages" => [
                [
                    "role" => "system",
                    "content" => "RISPONDI SENZA MAI AGGIUNGERE RIGHE VUOTE TRA I CONCETTI.
                    Rispondi senza righe vuote, sempre in lingua italiana, evita gli asterischi e NON lasciare righe vuote.
                    Fornisci informazioni dettagliate sui vari tipi di pesci senza lasciare righe vuote. Rispondi in modo conciso e diretto senza lasciare righe vuote, evitando prefissi inutili come 'ecco le informazioni sul' e senza lasciare righe vuote. 
                    Inizia sempre la risposta con il nome del pesce, e a capo, le informazioni.
                    Rispondi SEMPRE senza lasciare righe vuote.
                    Fornisci le risposte nel formato: 'Argomento: Spiegazione' SENZA LASCIARE RIGHE VUOTE
                    Fornisci le seguenti informazioni: nome scientifico, famiglia, gruppo taxonomico, habitat, dimensioni, dieta, stato di conservazione, importanza economica SENZA LASCIARE RIGHE VUOTE TI PREGOOO
                    NON. LASCIARE. RIGHE. VUOTE.
                    NON METTERE DEI <br>.
                    "
                ],
                [
                    "role" => "user",
                    "content" => "Dammi tutte le informazioni riguardo questo pesce senza utilizzare dei <br> e dei /n".$codice_input
                ]
            ],
            "params" => new stdClass(), // Puoi aggiungere parametri specifici se necessario
            "features" => [
                "web_search" => false
            ],
            "id" => uniqid() // Genera un ID univoco per la richiesta
        ];}

        // Chiamata all'API
        $api_response = call_api('', $data); // L'endpoint è già incluso nell'URL di base

        // Gestisci la risposta dell'API
        if (isset($api_response['error'])) {
            $result = "<div class='alert alert-danger'>" . htmlspecialchars($api_response['error']) . "</div>";
        } else {
            // Estrai il campo 'content' dalla risposta
            if (isset($api_response['choices'][0]['message']['content'])) {
                $content = $api_response['choices'][0]['message']['content'];
                $content_formattato = str_replace("\n\n", "\n", $content);  // Rimuove le righe vuote
                $content_formattato = str_replace("<br><br>", "<br>", $content_formattato);  // Rimuove le righe vuote 2
                $result = "<h2>Risultato API:</h2><div class='alert alert-success'>" . nl2br(htmlspecialchars($content_formattato)) . "</div>";
            } else {
                $result = "<div class='alert alert-warning'>Campo 'content' non trovato nella risposta API.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generazione Risultato API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Funzione per mostrare il messaggio di caricamento
        function showLoadingMessage() {
            document.getElementById('loadingMessage').style.display = 'block';
        }
    </script>
</head>
<body class="bg-light">
    <div class="container">
        <h1 class="mt-5">Generazione Risultato API</h1>

        <?php if (isset($result)): ?>
            <div class="mt-4"><?php echo $result; ?></div>
        <?php endif; ?>

        <form method="post" class="bg-white p-4 rounded shadow-sm mt-4" onsubmit="showLoadingMessage()">
            <div class="mb-3">
                <label for="codice" class="form-label">Codice da Inviare</label>
                <textarea name="codice" id="codice" class="form-control" rows="6" required><?php echo htmlspecialchars($codice_input ?? ''); ?></textarea>
            </div>
            <button name="search" type="submit" class="btn btn-primary" value="true">Invia(webSearch)</button>
            <button type="submit" class="btn btn-primary" value="false">Invia(no webSearch)</button>
        </form>
        <!-- Div per il messaggio di caricamento -->
        <div id="loadingMessage" class="alert alert-info mt-4" style="display: none;">
            Caricamento in corso, attendere per favore...
        </div>
    </div>
</body>
</html>
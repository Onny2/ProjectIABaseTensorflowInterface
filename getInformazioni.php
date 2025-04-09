<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Definizione della chiave API e dell’URL di base
define('API_KEY', ''); // Sostituisci con la tua chiave API

// Funzione per chiamare un'API generica
function call_api($endpoint, $data = [])
{
$url = "" . $endpoint; // Modifica l'URL se necessario
$headers = [
"Authorization: Bearer " . API_KEY,
"Content-Type: application/json"
];
$ch = curl_init($url);
curl_setopt_array($ch, [
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => json_encode($data),
CURLOPT_HTTPHEADER => $headers
]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
return ["error" => "Errore API: " . curl_error($ch)];
}
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($http_code !== 200) {
return ["error" => "Errore API: Risposta HTTP " . $http_code . " - " . $response];
}
$response_data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
return ["error" => "Errore JSON: " . json_last_error_msg()];
}
return $response_data;
}

// Gestione della richiesta POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$codice_input = trim($_POST['codice'] ?? '');
if (empty($codice_input)) {
$result = [ "error" => "Il campo codice è obbligatorio." ];
} else {
// Imposta i dati per la chiamata API a seconda che sia inviata la variabile 'search' (webSearch) oppure no
if (isset($_POST['search'])) {
$data = [
"stream" => false,
"model" => "llama3-8b-8192",
"messages" => [
[
"role" => "system",
"content" => "
Fornisci informazioni senza rivelare la tua identità di assistente rispondi solo a quanto richiesto
sempre in lingua italiana, evita gli asterischi
Fornisci informazioni dettagliate sui vari tipi di pesci. Rispondi in modo conciso e diretto evitando prefissi inutili come 'ecco le informazioni sul'
Inizia sempre la risposta con il nome del pesce, e a capo, le informazioni.
Fornisci le risposte nel formato: 'Argomento: Spiegazione'<br>
Fornisci le seguenti informazioni: nome scientifico, famiglia, gruppo taxonomico, habitat, dimensioni, dieta, stato di conservazione, importanza economica
Limita la ricerca web al minor spazio possibile non cercare mai troppe informazioni
"
],
[
"role" => "user",
"content" => "Dammi tutte le informazioni riguardo questo pesce " . $codice_input
]
],
"params" => new stdClass(),
"features" => [
"web_search" => true
],
"id" => uniqid()
];
} else {
$data = [
"stream" => false,
"model" => "llama3-8b-8192",
"messages" => [
[
"role" => "system",
"content" => "
Fornisci informazioni senza rivelare la tua identità di assistente rispondi solo a quanto richiesto

sempre in lingua italiana, evita gli asterischi
Fornisci informazioni dettagliate sui vari tipi di pesci. Rispondi in modo conciso e diretto evitando prefissi inutili come 'ecco le informazioni sul'
Inizia sempre la risposta con il nome del pesce, e a capo, le informazioni.
Fornisci le risposte nel formato: 'Argomento: Spiegazione'
Fornisci le seguenti informazioni: nome scientifico, famiglia, gruppo taxonomico, habitat, dimensioni, dieta, stato di conservazione, importanza economica
"
],
[
"role" => "user",
"content" => "Dammi tutte le informazioni riguardo questo pesce " . $codice_input
]
],
"params" => new stdClass(),
"features" => [
"web_search" => false
],
"id" => uniqid()
];
}
// Chiamata all'API
$api_response = call_api('', $data);
if (isset($api_response['error'])) {
$result = [ "error" => $api_response['error'] ];
} else {
if (isset($api_response['choices'][0]['message']['content'])) {
$content = $api_response['choices'][0]['message']['content'];
// Converte le interruzioni di riga in tag <br>
$content = nl2br($content, false);
// Elimina eventuali doppioni di <br> se necessario
$content_formattato = str_replace("<br>
<br>", "<br>", $content);
$content_formattato = str_replace("*", "", $content_formattato);
$result = [ "success" => true, "content" => $content_formattato ];
} else {
$result = [ "error" => "Campo 'content' non trovato nella risposta API." ];
}
}
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($result);
exit;
} else {
header('Content-Type: application/json; charset=utf-8');
echo json_encode([ "error" => "Metodo non supportato. Utilizzare POST." ]);
exit;
}
?>
<!DOCTYPE html>

<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Premium</title>
<!-- Font Awesome per le icone -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<!-- Chart.js per i grafici -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
* { box-sizing: border-box; }
body {
font-family: 'Roboto', sans-serif;
margin: 0;
padding: 0;
background: #f0f4f8;
color: #333;
display: flex;
flex-direction: column;
min-height: 100vh;
}
header {
background-color: #0073e6;
color: white;
padding: 15px;
text-align: center;
font-size: 1.5em;
font-weight: bold;
box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
main {
flex: 1;
padding: 20px;
}
.page { display: none; }
.active-page { display: block; }
h1 {
text-align: center;
color: #0073e6;
margin-bottom: 20px;
}
.buttons-container {
display: flex;
flex-wrap: wrap;
justify-content: center;
gap: 15px;
margin-bottom: 20px;
}
button {
padding: 15px 25px;
font-size: 1em;
background: #0073e6;
color: white;
border: none;
border-radius: 8px;
cursor: pointer;
transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
display: flex;
align-items: center;
gap: 8px;
}
button:hover {
background: #005bb5;
transform: translateY(-2px);
box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
#webcam-container {
position: relative;
margin: 0 auto;
border: 2px solid #0073e6;
border-radius: 10px;
overflow: hidden;
width: 650px;
height: 650px;
transition: all 0.5s ease;
background: #fff;
}
#webcam-container img, #webcam-container canvas {
width: 100%;
height: 100%;
object-fit: cover;
}
#top-prediction {
position: absolute;
top: 10px;
left: 50%;
transform: translateX(-50%);
background-color: rgba(0, 115, 230, 0.9);
color: white;
padding: 10px 20px;
border-radius: 5px;
font-size: 1.2em;
font-weight: bold;
text-align: center;
pointer-events: none;
z-index: 10;
display: none;
width: calc(100% - 40px);
box-sizing: border-box;
}
#label-container {
margin-top: 20px;
display: flex;
flex-wrap: wrap;
justify-content: center;
gap: 15px;
width: 100%;
max-width: 800px;
padding: 0 10px;
margin-left: auto;
margin-right: auto;
}
.label {
padding: 15px;
background-color: #28a745;
color: white;
border-radius: 8px;
font-size: 1.1em;
font-weight: 500;
text-align: center;
min-width: 150px;
flex: 1;
max-width: 200px;
box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
/* Bottom Navigation */
nav {
background-color: #ffffff;
border-top: 1px solid #ddd;
display: flex;
justify-content: space-around;
padding: 10px 0;
position: sticky;
bottom: 0;
z-index: 100;
}
nav button {
background: none;
border: none;
color: #555;
font-size: 1.2em;
display: flex;
flex-direction: column;
align-items: center;
cursor: pointer;
transition: color 0.3s;
}
nav button.active { color: #0073e6; }
nav button:hover { color: white; }
/* Info Page */
.info {
max-width: 800px;
margin: 0 auto;
line-height: 1.6;
}
.info h2 {
color: #0073e6;
margin-bottom: 10px;
}
.info p { margin-bottom: 15px; }
/* User Page */
.user-info {
max-width: 400px;
margin: 0 auto;
text-align: center;
}
.user-info img {
width: 120px;
height: 120px;
border-radius: 50%;
object-fit: cover;
margin-bottom: 15px;
}
.user-info button { background-color: #dc3545; }
/* Saved Page */
.saved-container {
max-width: 1000px;
margin: 0 auto;
}
.saved-item {
display: flex;
align-items: center;
background: #fff;
padding: 15px;
margin-bottom: 15px;
border-radius: 8px;
box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.saved-item img {
width: 150px;
height: 150px;
object-fit: cover;
border-radius: 8px;
margin-right: 20px;
}
.saved-details { flex: 1; }
.saved-details h3 {
margin: 0 0 10px 0;
color: #0073e6;
}
.saved-details p { margin: 5px 0; }
.saved-details button {
background-color: #6c757d;
margin-top: 10px;
}
/* Responsive */
@media (max-width: 700px) {
#webcam-container { width: 100%; height: auto; }
button { flex: 1 1 100px; justify-content: center; }
.saved-item { flex-direction: column; align-items: flex-start; }
.saved-item img { margin-bottom: 10px; margin-right: 0; }
}
</style>
</head>
<body>
<header>
Dashboard di Fishpert Premium
</header>
<main>
<!-- Home Page -->
<div id="home" class="page active-page">
<div class="buttons-container">
<button type="button" onclick="init()" aria-label="Attiva Webcam">
<i class="fas fa-camera"></i> Attiva Webcam
</button>
<input type="file" accept="image/*" capture="camera" onchange="loadPhoto(event)" style="display: none;" id="file-input">
<button id="photo-button" type="button" onclick="document.getElementById('file-input').click()" aria-label="Scatta Foto">
<i class="fas fa-camera-retro"></i> Scatta Foto
</button>
<button id="webcam" type="button" onclick="switchCamera()" disabled aria-label="Cambia Telecamera">
<i class="fas fa-exchange-alt"></i> Cambia Telecamera
</button>
<button id="save-button" type="button" onclick="saveData()" aria-label="Salva" style="background-color: #ffc107;">
<i class="fas fa-save"></i> Salva
</button>
</div>
<div id="webcam-container">
<div id="top-prediction"></div>
</div>
<div id="label-container"></div>
</div>
<!-- Info Page -->
<div id="info" class="page">
<h1>Informazioni</h1>
<div class="info">
<h2>Che cos'è il Riconoscimento IA di Pesci?</h2>
<p>La nostra applicazione utilizza algoritmi di intelligenza artificiale per riconoscere diverse specie di pesci attraverso immagini scattate con la tua webcam o caricate dal tuo dispositivo.</p>
<h2>Come funziona?</h2>
<p>Quando scatti una foto o attivi la webcam, il sistema analizza l'immagine e fornisce una previsione sulla specie di pesce presente nell'immagine. Puoi visualizzare la previsione principale e le probabilità associate.</p>
<h2>Perché usarla?</h2>
<p>È uno strumento utile per pescatori, biologi marini o semplicemente per appassionati di pesca che desiderano identificare facilmente le specie di pesci incontrati.</p>
</div>
</div>
<!-- User Page -->
<div id="user" class="page">
<h1>Utente</h1>
<div class="user-info">
<img src="richfish.png" alt="Avatar Utente">
<h2>Pesce ricco</h2>
<p>Email: pescericco@gmail.com</p>
<button type="button" onclick="logout()" aria-label="Logout">
<i class="fas fa-sign-out-alt"></i> Logout
</button>
</div>
</div>
<!-- Saved Page -->
<div id="saved" class="page">
<h1>Salvati</h1>
<div class="saved-container" id="saved-container">
<!-- Contenuto caricato dinamicamente -->
</div>
</div>
</main>
<!-- Bottom Navigation -->
<nav>
<button id="nav-home" class="active" onclick="navigate('home')">
<i class="fas fa-home"></i> Home
</button>
<button id="nav-info" onclick="navigate('info')">
<i class="fas fa-info-circle"></i> Info
</button>
<button id="nav-saved" onclick="navigate('saved')">
<i class="fas fa-folder-open"></i> Salvati
</button>
<button id="nav-user" onclick="navigate('user')">
<i class="fas fa-user"></i> Utente
</button>
</nav>
<!-- TensorFlow e Librerie Teachable Machine -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
<script>
// Navigazione tra le pagine
function navigate(page) {
const pages = document.querySelectorAll('.page');
pages.forEach(p => p.classList.remove('active-page'));
document.getElementById(page).classList.add('active-page');
const navButtons = document.querySelectorAll('nav button');
navButtons.forEach(btn => btn.classList.remove('active'));
document.getElementById('nav-' + page).classList.add('active');
if (page === 'saved') { loadSavedData(); }
}
function logout() {
alert('Logout effettuato.');
window.location.href = 'demo.php';
}
// URL della directory del modello
const URL = "./";
let model; // Modello caricato globalmente
let webcamInstance; // Istanza della webcam
let usingFrontCamera = true;
let currentPrediction = null;
let currentImage = null;

// Carica il modello al caricamento della pagina
async function loadModel() {
try {
const modelURL = URL + "model.json";
const metadataURL = URL + "metadata.json";
model = await tmImage.load(modelURL, metadataURL);
console.log("Modello caricato con successo.");
} catch (error) {
console.error("Errore nel caricamento del modello:", error);
alert("Errore nel caricamento del modello. Assicurati che i file model.json e metadata.json siano presenti.");
}
}
document.addEventListener('DOMContentLoaded', () => { loadModel(); });

// Inizializza la webcam
async function init() {
if (!model) {
alert("Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
return;
}
try {
const webcamContainer = document.getElementById("webcam-container");
webcamContainer.classList.add('active');
if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
throw new Error("La tua macchina non supporta l'accesso alla webcam.");
}
if (webcamInstance) {
await webcamInstance.stop();
webcamInstance = null;
}
webcamContainer.innerHTML = `<div id="top-prediction"></div>`;
webcamInstance = new tmImage.Webcam(650, 650, true); // true = flip per front camera
await webcamInstance.setup({ facingMode: usingFrontCamera ? 'user' : 'environment' });
await webcamInstance.play();
webcamContainer.appendChild(webcamInstance.canvas);
document.getElementById("top-prediction").style.display = "block";
document.getElementById("webcam").disabled = false;
currentImage = webcamInstance.canvas.toDataURL('image/png');
predictLoop();
} catch (error) {
console.error("Errore durante l'inizializzazione della webcam:", error);
alert("Errore di inizializzazione della webcam. Assicurati di aver concesso i permessi e che la webcam sia disponibile.");
}
}

// Loop per aggiornare la predizione dalla webcam
async function predictLoop() {
if (webcamInstance && model) {
try {
await webcamInstance.update();
if (webcamInstance.canvas) {
await predict(webcamInstance.canvas);
currentImage = webcamInstance.canvas.toDataURL('image/png');
}
} catch (error) {
console.error("Errore nel loop di acquisizione:", error);
}
requestAnimationFrame(predictLoop);
}
}

// Predice a partire dal canvas della webcam
async function predict(source) {
try {
const prediction = await model.predict(source);
const sortedPredictions = prediction.sort((a, b) => b.probability - a.probability);
const topPrediction = sortedPredictions.length > 0 ? sortedPredictions[0] : null;
const topPredictionDiv = document.getElementById("top-prediction");
if (topPrediction) {
topPredictionDiv.innerText = `${topPrediction.className}: ${(topPrediction.probability * 100).toFixed(0)}%`;
currentPrediction = topPrediction;
updateLabels(sortedPredictions);
} else {
topPredictionDiv.innerText = "Nessuna predizione disponibile.";
}
} catch (error) {
console.error("Errore durante la predizione:", error);
}
}

// Predice a partire da una foto caricata
async function predictPhoto(source) {
try {
const prediction = await model.predict(source);
const sortedPredictions = prediction.sort((a, b) => b.probability - a.probability);
const topPrediction = sortedPredictions.length > 0 ? sortedPredictions[0] : null;
const topPredictionDiv = document.getElementById("top-prediction");
if (topPrediction) {
topPredictionDiv.innerText = `${topPrediction.className}: ${(topPrediction.probability * 100).toFixed(0)}%`;
currentPrediction = topPrediction;
updateLabels(sortedPredictions);
} else {
topPredictionDiv.innerText = "Nessuna predizione disponibile.";
}
} catch (error) {
console.error("Errore durante la predizione:", error);
}
}

// Aggiorna le etichette sotto la webcam e aggiorna il grafico
function updateLabels(sortedPredictions) {
const labelContainer = document.getElementById("label-container");
labelContainer.innerHTML = "";
const predictionsToShow = sortedPredictions.slice(0, 5);
predictionsToShow.forEach(pred => {
const probabilityPercentage = (pred.probability * 100).toFixed(2) + "%";
const classPrediction = `${pred.className}: ${probabilityPercentage}`;
const div = document.createElement("div");
div.innerHTML = classPrediction;
div.classList.add("label");
labelContainer.appendChild(div);
});
updateChart(predictionsToShow);
}

// Carica e predice una foto selezionata
async function loadPhoto(event) {
if (!model) {
alert("Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
return;
}
const file = event.target.files[0];
if (!file) return;
const imgUrl = window.URL.createObjectURL(file);
const img = new Image();
img.src = imgUrl;
img.onload = async () => {
try {
if (webcamInstance) {
await webcamInstance.stop();
webcamInstance = null;
}
const webcamContainer = document.getElementById("webcam-container");
webcamContainer.classList.add('active');
webcamContainer.innerHTML = `<div id="top-prediction"></div>`;
const canvas = document.createElement('canvas');
canvas.width = 650;
canvas.height = 650;
const ctx = canvas.getContext('2d');
ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
webcamContainer.appendChild(canvas);
document.getElementById("top-prediction").style.display = "block";
await predictPhoto(canvas);
currentImage = canvas.toDataURL('image/png');
document.getElementById('file-input').value = "";
document.getElementById("webcam").disabled = true;
} catch (error) {
console.error("Errore durante il caricamento della foto:", error);
}
};
}

// Cambia la fotocamera
async function switchCamera() {
usingFrontCamera = !usingFrontCamera;
await init();
}

// Grafico delle predizioni
let chart;
function updateChart(predictions) {
const ctx = document.getElementById('predictionChart')?.getContext('2d');
if (!ctx) {
const canvas = document.createElement('canvas');
canvas.id = 'predictionChart';
canvas.width = 400;
canvas.height = 300;
document.getElementById("label-container").appendChild(canvas);
chart = new Chart(canvas, {
type: 'bar',
data: {
labels: predictions.map(p => p.className),
datasets: [{
label: 'Probabilità (%)',
data: predictions.map(p => (p.probability * 100).toFixed(2)),
backgroundColor: 'rgba(0, 115, 230, 0.6)',
borderColor: 'rgba(0, 115, 230, 1)',
borderWidth: 1
}]
},
options: {
responsive: true,
scales: {
y: { beginAtZero: true, max: 100 }
}
}
});
} else {
chart.data.labels = predictions.map(p => p.className);
chart.data.datasets[0].data = predictions.map(p => (p.probability * 100).toFixed(2));
chart.update();
}
}

// Salva i dati relativi all'ultima predizione
async function saveData() {
if (!currentPrediction) {
alert("Nessuna predizione da salvare.");
return;
}
const data = {
className: currentPrediction.className,
probability: currentPrediction.probability,
timestamp: new Date().toISOString(),
image: currentImage
};
try {
const response = await fetch('save.php', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(data)
});
const result = await response.json();
if (result.success) {
alert("Dati salvati con successo!");
} else {
alert("Errore nel salvataggio dei dati.");
}
} catch (error) {
console.error("Errore nella richiesta di salvataggio:", error);
alert("Errore nella richiesta di salvataggio.");
}
}

// Carica i dati salvati e li visualizza nella pagina "Salvati"
async function loadSavedData() {
const container = document.getElementById("saved-container");
container.innerHTML = "Caricamento...";
try {
const response = await fetch('list.php');
const data = await response.json();
container.innerHTML = "";
if (data.length === 0) {
container.innerHTML = "<p>Nessun dato salvato.</p>";
return;
}
// Per ogni record salvato, creiamo un elemento con una form interna per la "Modalità Ricercatore"
data.forEach(item => {
const div = document.createElement("div");
div.classList.add("saved-item");
div.innerHTML = `
<img src="${item.image}" alt="${item.className}">
<div class="saved-details">
<h3>${item.className}</h3>
<p>Probabilità: ${(item.probability * 100).toFixed(2)}%</p>
<p>Data: ${new Date(item.timestamp).toLocaleString()}</p>
<form class="apiForm bg-white p-4 rounded shadow-sm mt-4">
<div class="mb-3">
<textarea name="codice" class="codice" rows="6" style="display:none" required>${item.className}</textarea>
</div>
<button name="search" type="submit" class="btn btn-primary" value="true">Modalità Ricercatore</button>
</form>
<div class="result"></div>
</div>
`;
container.appendChild(div);
});
} catch (error) {
console.error("Errore nel caricamento dei dati salvati:", error);
container.innerHTML = "<p>Errore nel caricamento dei dati.</p>";
}
}

// Event delegation per gestire la Modalità Ricercatore in tutte le form generate
document.getElementById("saved-container").addEventListener("submit", function(e) {
if (e.target && e.target.matches("form.apiForm")) {
e.preventDefault();
const form = e.target;
const resultDiv = form.parentElement.querySelector(".result");
resultDiv.innerText = "Caricamento in corso, attendere per favore...(fino a 30 secondi, purtroppo le API di web search richiedono tempo)";
const codice = form.querySelector("textarea.codice").value;
const formData = new FormData();
formData.append('codice', codice);

formData.append('search', 'true');
fetch('getInformazioni.php', {
method: 'POST',
body: formData
})
.then(response => {
if (!response.ok) { throw new Error("Errore nella chiamata: " + response.status); }
return response.json();
})
.then(data => {
if (data.error) {
resultDiv.innerHTML = "<span style='color: red;'>" + data.error + "</span>";
} else if (data.success) {
resultDiv.innerHTML = "<div style='background-color:white;color:#0073e6;padding:5px;border-radius:5px;margin-top:10px;font-weight:bold;border:1px solid lightgray'>" + data.content + "</div>";
}
})
.catch(error => {
resultDiv.innerHTML = "<span style='color: red;'>Errore: " + error.message + "</span>";
});
}
});
</script>

</body>
</html>

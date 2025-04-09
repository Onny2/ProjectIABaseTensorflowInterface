<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Fishpert Premium</title>
  
  <!-- Font Awesome per le icone -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  
  <!-- Chart.js per i grafici -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <!-- CSS Migliorato -->
  <style>
    :root {
      --primary-color: #0073e6;
      --secondary-color: #ffc107;
      --background-color: #f0f4f8;
      --text-color: #333;
      --header-bg: #0073e6;
      --header-text: #fff;
      --button-bg: #0073e6;
      --button-hover-bg: #005bb5;
      --border-radius: 8px;
      --transition-speed: 0.3s;
      --dark-bg: #1e1e1e;
      --dark-text: #f0f0f0;
      --dark-primary: #1a73e8;
      --nav-bg: #ffffff;
      --nav-text: #555;
      --nav-active: #0073e6;
      --saved-bg: #ffffff;
      --saved-text: #333;
      --error-bg: #333;
      --error-text: #f0f0f0;
    }
    
    [data-theme="dark"] {
      --background-color: #121212;
      --text-color: #f0f0f0;
      --header-bg: #1a73e8;
      --header-text: #f0f0f0;
      --button-bg: #1a73e8;
      --button-hover-bg: #135abc;
      --secondary-color: #bb86fc;
      --nav-bg: #2c2c2c;
      --nav-text: #f0f0f0;
      --nav-active: #bb86fc;
      --saved-bg: #2c2c2c;
      --saved-text: #f0f0f0;
      --error-bg: #444;
      --error-text: #f0f0f0;
    }
    
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: var(--background-color);
      color: var(--text-color);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      transition: background 0.3s, color 0.3s;
    }
    
    header {
      background-color: var(--header-bg);
      color: var(--header-text);
      padding: 15px;
      text-align: center;
      font-size: 1.5em;
      font-weight: bold;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      position: relative;
    }
    
    /* Toggle Tema */
    .theme-toggle {
      position: absolute;
      top: 15px;
      right: 80px;
      cursor: pointer;
      font-size: 1.2em;
      color: var(--header-text);
    }
    
    .toggle-switch {
      position: absolute;
      top: 15px;
      right: 20px;
      display: flex;
      align-items: center;
      cursor: pointer;
    }
    
    .toggle-switch input {
      display: none;
    }
    
    .slider {
      width: 50px;
      height: 24px;
      background-color: #ccc;
      border-radius: 34px;
      position: relative;
      transition: background-color 0.4s;
    }
    
    .slider::before {
      content: "";
      position: absolute;
      width: 20px;
      height: 20px;
      left: 2px;
      bottom: 2px;
      background-color: white;
      border-radius: 50%;
      transition: transform 0.4s;
    }
    
    input:checked + .slider {
      background-color: var(--primary-color);
    }
    
    input:checked + .slider::before {
      transform: translateX(26px);
    }
    
    main {
      flex: 1;
      padding: 20px;
    }
    
    .page {
      display: none;
      animation: fadeIn 0.5s ease-in-out;
    }
    
    .active-page {
      display: block;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    h1 {
      text-align: center;
      color: var(--primary-color);
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
      background: var(--button-bg);
      color: white;
      border: none;
      border-radius: var(--border-radius);
      cursor: pointer;
      transition: background var(--transition-speed), transform var(--transition-speed), box-shadow var(--transition-speed);
      display: flex;
      align-items: center;
      gap: 8px;
      position: relative;
    }
    
    button:disabled {
      background: #aaa;
      cursor: not-allowed;
    }
    
    button:hover:not(:disabled) {
      background: var(--button-hover-bg);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Spinner */
    .spinner {
      border: 4px solid rgba(0, 0, 0, 0.1);
      border-left-color: var(--primary-color);
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
      position: absolute;
      top: 50%;
      left: 50%;
      margin: -10px 0 0 -10px;
      display: none;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* Webcam Container */
    #webcam-container {
      position: relative;
      margin: 0 auto;
      border: 2px solid var(--primary-color);
      border-radius: var(--border-radius);
      overflow: hidden;
      width: 650px;
      height: 650px;
      transition: all 0.5s ease;
      background: var(--background-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-color);
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
      background-color: var(--primary-color);
      color: white;
      border-radius: var(--border-radius);
      font-size: 1.1em;
      font-weight: 500;
      text-align: center;
      min-width: 150px;
      flex: 1;
      max-width: 200px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .label .probability {
      font-size: 0.9em;
      margin-top: 5px;
    }
    
    /* Bottom Navigation */
    nav {
      background-color: var(--nav-bg);
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      padding: 10px 0;
      position: sticky;
      bottom: 0;
      z-index: 100;
      box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
      transition: background-color var(--transition-speed), color var(--transition-speed);
    }
    
    nav button {
      background: none;
      border: none;
      color: var(--nav-text);
      font-size: 1.2em;
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      transition: color var(--transition-speed);
    }
    
    nav button.active {
      color: var(--nav-active);
    }
    
    nav button:hover:not(.active) {
      color: var(--primary-color);
    }
    
    /* Info Page */
    .info {
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }
    
    .info h2 {
      color: var(--primary-color);
      margin-bottom: 10px;
    }
    
    .info p {
      margin-bottom: 15px;
    }
    
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
      border: 2px solid var(--primary-color);
    }
    
    .user-info button {
      background-color: #dc3545;
      transition: background var(--transition-speed);
    }
    
    .user-info button:hover {
      background-color: #c82333;
    }
    
    /* Saved Page */
    .saved-container {
      max-width: 1000px;
      margin: 0 auto;
    }
    
    .saved-item {
      display: flex;
      align-items: center;
      background: var(--saved-bg);
      color: var(--saved-text);
      padding: 15px;
      margin-bottom: 15px;
      border-radius: var(--border-radius);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: transform var(--transition-speed), box-shadow var(--transition-speed), background-color var(--transition-speed), color var(--transition-speed);
    }
    
    .saved-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .saved-item img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: var(--border-radius);
      margin-right: 20px;
    }
    
    .saved-details {
      flex: 1;
    }
    
    .saved-details h3 {
      margin: 0 0 10px 0;
      color: var(--primary-color);
    }
    
    .saved-details p {
      margin: 5px 0;
    }
    
    .saved-details button {
      background-color: #6c757d;
      margin-top: 10px;
      transition: background var(--transition-speed);
    }
    
    .saved-details button:hover {
      background-color: #5a6268;
    }
    
    /* Modal Styles */
    .modal {
      display: none; 
      position: fixed; 
      z-index: 200; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%; 
      overflow: auto; 
      background-color: rgba(0,0,0,0.5); 
      justify-content: center;
      align-items: center;
    }
    
    .modal-content {
      background-color: var(--background-color);
      color: var(--text-color);
      margin: 15% auto; 
      padding: 20px;
      border: 1px solid #888;
      width: 80%; 
      max-width: 500px;
      border-radius: var(--border-radius);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      position: relative;
    }
    
    .close-modal {
      color: #aaa;
      float: right;
      font-size: 1.5em;
      font-weight: bold;
      position: absolute;
      top: 10px;
      right: 20px;
      cursor: pointer;
    }
    
    .close-modal:hover,
    .close-modal:focus {
      color: #000;
      text-decoration: none;
    }
    
    /* Dark Mode Toggle Button Icon */
    .toggle-switch .slider::before {
      background-color: white;
    }
    
    /* Error Message Styles */
    .error-message {
      background-color: var(--error-bg);
      color: var(--error-text);
      padding: 10px;
      border-radius: var(--border-radius);
      margin-top: 10px;
      text-align: center;
    }
    
    /* Responsive */
    @media (max-width: 700px) {
      #webcam-container { width: 100%; height: auto; }
      button { flex: 1 1 100px; justify-content: center; }
      .saved-item { flex-direction: column; align-items: flex-start; }
      .saved-item img { margin-bottom: 10px; margin-right: 0; }
    }
    
    /* Loader Overlay */
    .loader-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255,255,255,0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 300;
      display: none;
    }
    
    .loader-overlay.active {
      display: flex;
    }
    
    /* Adjust Loader Overlay for Dark Mode */
    [data-theme="dark"] .loader-overlay {
      background: rgba(0, 0, 0, 0.7);
    }
    
    /* Adjust Spinner Color in Modal */
    .modal-content .spinner {
      border-left-color: var(--primary-color);
    }
  </style>
</head>
<body>
  <header>
    <span style="position:relative;left:-25px;">Fishpert Premium</span>
    <div class="theme-toggle" aria-label="Toggle Dark Mode" tabindex="0">
      <i class="fas fa-moon"></i>
    </div>
    <div class="toggle-switch" aria-label="Toggle Dark Mode" tabindex="0">
      <input type="checkbox" id="theme-switch">
      <span class="slider"></span>
    </div>
  </header>
  
  <main>
    <!-- Home Page -->
    <div id="home" class="page active-page">
      <div class="buttons-container">
        <button type="button" onclick="init()" aria-label="Attiva Webcam">
          <i class="fas fa-camera"></i> Attiva Webcam
          <div class="spinner"></div>
        </button>
        <input type="file" accept="image/*" capture="camera" onchange="loadPhoto(event)" style="display: none;" id="file-input">
        <button id="photo-button" type="button" onclick="document.getElementById('file-input').click()" aria-label="Scatta Foto">
          <i class="fas fa-camera-retro"></i> Scatta Foto
          <div class="spinner"></div>
        </button>
        <button id="webcam" type="button" onclick="switchCamera()" disabled aria-label="Cambia Telecamera">
          <i class="fas fa-exchange-alt"></i> Cambia Telecamera
          <div class="spinner"></div>
        </button>
        <button id="save-button" type="button" onclick="saveData()" aria-label="Salva" style="background-color: var(--secondary-color);">
          <i class="fas fa-save"></i> Salva
          <div class="spinner"></div>
        </button>
      </div>
      <div id="webcam-container">
        <div id="top-prediction"></div>
        <div id="error-message" class="error-message" style="display: none;">Permesso alla webcam negato o webcam non disponibile.</div>
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
        <h2>Pesce Ricco</h2>
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
    <button id="nav-home" class="active" onclick="navigate('home')" aria-label="Home">
      <i class="fas fa-home"></i> Home
    </button>
    <button id="nav-info" onclick="navigate('info')" aria-label="Informazioni">
      <i class="fas fa-info-circle"></i> Info
    </button>
    <button id="nav-saved" onclick="navigate('saved')" aria-label="Salvati">
      <i class="fas fa-folder-open"></i> Salvati
    </button>
    <button id="nav-user" onclick="navigate('user')" aria-label="Utente">
      <i class="fas fa-user"></i> Utente
    </button>
  </nav>
  
  <!-- Loader Overlay -->
  <div class="loader-overlay" id="loader">
    <div class="spinner"></div>
  </div>
  
  <!-- Modale per Notifiche -->
  <div id="modal" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <span class="close-modal" onclick="closeModal()" aria-label="Chiudi">&times;</span>
      <h2 id="modal-title">Notifica</h2>
      <p id="modal-message"></p>
    </div>
  </div>
  
  <!-- TensorFlow e Librerie Teachable Machine -->
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
  
  <!-- JavaScript Migliorato -->
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
      showModal('Logout', 'Logout effettuato.');
      setTimeout(() => { window.location.href = 'demo.php'; }, 1500);
    }

    // Modal Functions
    function showModal(title, message) {
      const modal = document.getElementById('modal');
      document.getElementById('modal-title').innerText = title;
      document.getElementById('modal-message').innerText = message;
      modal.style.display = 'flex';
      modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
      const modal = document.getElementById('modal');
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
    }

    // Theme Toggle
    const themeSwitch = document.getElementById('theme-switch');
    const toggleSwitch = document.querySelector('.toggle-switch');
    const themeToggleIcon = document.querySelector('.theme-toggle i');

    themeSwitch.addEventListener('change', () => {
      const theme = themeSwitch.checked ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', theme);
      themeToggleIcon.classList.toggle('fa-sun', themeSwitch.checked);
      themeToggleIcon.classList.toggle('fa-moon', !themeSwitch.checked);
      localStorage.setItem('theme', theme);
    });

    document.querySelector('.theme-toggle').addEventListener('click', () => {
      themeSwitch.checked = !themeSwitch.checked;
      themeSwitch.dispatchEvent(new Event('change'));
    });

    // URL della directory del modello
    const URL = "./";
    let model; // Modello caricato globalmente
    let webcamInstance; // Istanza della webcam
    let usingFrontCamera = true;
    let currentPrediction = null;
    let currentImage = null;

    const loader = document.getElementById('loader');

    // Carica il modello al caricamento della pagina
    async function loadModel() {
      try {
        loader.classList.add('active');
        const modelURL = URL + "model.json";
        const metadataURL = URL + "metadata.json";
        model = await tmImage.load(modelURL, metadataURL);
        console.log("Modello caricato con successo.");
        loader.classList.remove('active');
      } catch (error) {
        console.error("Errore nel caricamento del modello:", error);
        showModal("Errore", "Errore nel caricamento del modello. Assicurati che i file model.json e metadata.json siano presenti.");
        loader.classList.remove('active');
      }
    }

    document.addEventListener('DOMContentLoaded', () => { 
      loadModel(); 
      // Initial Theme Check
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', savedTheme);
      themeSwitch.checked = savedTheme === 'dark';
      themeToggleIcon.classList.toggle('fa-sun', savedTheme === 'dark');
      themeToggleIcon.classList.toggle('fa-moon', savedTheme !== 'dark');
    });

    // Inizializza la webcam
    async function init() {
      if (!model) {
        showModal("Attenzione", "Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
        return;
      }
      try {
        loader.classList.add('active');
        const webcamContainer = document.getElementById("webcam-container");
        webcamContainer.classList.add('active');
        document.getElementById("error-message").style.display = "none";

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          throw new Error("La tua macchina non supporta l'accesso alla webcam.");
        }

        if (webcamInstance) {
          await webcamInstance.stop();
          webcamInstance = null;
        }

        webcamContainer.innerHTML = `<div id="top-prediction"></div><div id="error-message" class="error-message" style="display: none;"></div>`;
        webcamInstance = new tmImage.Webcam(650, 650, true); // true = flip per front camera
        await webcamInstance.setup({ facingMode: usingFrontCamera ? 'user' : 'environment' });
        await webcamInstance.play();
        webcamContainer.appendChild(webcamInstance.canvas);
        document.getElementById("top-prediction").style.display = "block";
        document.getElementById("webcam").disabled = false;
        currentImage = webcamInstance.canvas.toDataURL('image/png');
        loader.classList.remove('active');
        predictLoop();
      } catch (error) {
        console.error("Errore durante l'inizializzazione della webcam:", error);
        loader.classList.remove('active');
        const errorMessageDiv = document.getElementById("error-message");
        errorMessageDiv.innerText = "Permesso alla webcam negato o webcam non disponibile.";
        errorMessageDiv.style.display = "block";
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
        const classPrediction = `${pred.className}`;
        const div = document.createElement("div");
        div.classList.add("label");
        div.innerHTML = `
          <span>${classPrediction}</span>
          <span class="probability">${probabilityPercentage}</span>
        `;
        labelContainer.appendChild(div);
      });
      updateChart(predictionsToShow);
    }

    // Carica e predice una foto selezionata
    async function loadPhoto(event) {
      if (!model) {
        showModal("Attenzione", "Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
        return;
      }
      const file = event.target.files[0];
      if (!file) return;
      const imgUrl = window.URL.createObjectURL(file);
      const img = new Image();
      img.src = imgUrl;
      loader.classList.add('active');
      img.onload = async () => {
        try {
          if (webcamInstance) {
            await webcamInstance.stop();
            webcamInstance = null;
          }
          const webcamContainer = document.getElementById("webcam-container");
          webcamContainer.classList.add('active');
          webcamContainer.innerHTML = `<div id="top-prediction"></div><div id="error-message" class="error-message" style="display: none;"></div>`;
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
          loader.classList.remove('active');
        } catch (error) {
          console.error("Errore durante il caricamento della foto:", error);
          loader.classList.remove('active');
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
      const existingCanvas = document.getElementById('predictionChart');
      const ctx = existingCanvas ? existingCanvas.getContext('2d') : null;
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
        showModal("Attenzione", "Nessuna predizione da salvare.");
        return;
      }
      const data = {
        className: currentPrediction.className,
        probability: currentPrediction.probability,
        timestamp: new Date().toISOString(),
        image: currentImage
      };
      try {
        loader.classList.add('active');
        const response = await fetch('save.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        const result = await response.json();
        loader.classList.remove('active');
        if (result.success) {
          showModal("Successo", "Dati salvati con successo!");
        } else {
          showModal("Errore", "Errore nel salvataggio dei dati.");
        }
      } catch (error) {
        console.error("Errore nella richiesta di salvataggio:", error);
        loader.classList.remove('active');
        showModal("Errore", "Errore nella richiesta di salvataggio.");
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
              <form class="apiForm">
                <input type="hidden" name="codice" value="${item.className}">
                <button name="search" type="submit" class="btn btn-primary">Modalità Ricercatore</button>
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
        const codice = form.querySelector("input[name='codice']").value;
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
            resultDiv.innerHTML = `<span style='color: red;'>${data.error}</span>`;
          } else if (data.success) {
            resultDiv.innerHTML = `<div style='background-color:var(--background-color);color:var(--primary-color);padding:10px;border-radius:5px;margin-top:10px;font-weight:bold;border:1px solid lightgray'>${data.content}</div>`;
          }
        })
        .catch(error => {
          resultDiv.innerHTML = `<span style='color: red;'>Errore: ${error.message}</span>`;
        });
      }
    });

    // Loader Spinner Controls
    function showLoader() {
      loader.classList.add('active');
    }

    function hideLoader() {
      loader.classList.remove('active');
    }
  </script>
</body>
</html>
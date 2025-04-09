<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishpert</title>
    <style>
        _,_ ::before, *::after {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(circle, rgba(255, 231, 246, 0.92) 0%, rgba(148, 187, 233, 1) 100%);
            color: #333;
            margin: 0;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }
        h1 {
            font-size: 2em;
            color: #0073e6;
            margin: 10px 0;
            text-align: center;
            padding: 0 5px;
        }
        
        h2 {
            font-size: 1em;
            color: #0073e6;
            margin: 10px 0;
            text-align: center;
            padding: 0 5px;
        }
        .buttons-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
            width: 100%;
            max-width: 800px;
        }
        .buttons-container button {
            padding: 10px 20px;
            font-size: 1em;
            background: radial-gradient(circle, rgba(63, 206, 251, 1) 0%, rgba(70, 119, 252, 1) 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            flex: 0 1 150px;
            max-width: 150px;
            min-width: 100px;
            text-align: center;
        }
        .buttons-container button:hover {
            background: radial-gradient(circle, rgba(70, 119,252,1) 0%, rgba(63,206,251,1) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        #webcam-container {
            position: relative;
            margin-top: 10px;
            border: 2px solid #0073e6;
            border-radius: 10px;
            overflow: hidden;
            width: 0;
            height: 0;
            transition: width 0.5s ease, height 0.5s ease;
        }
        #webcam-container.active {
            width: 650px;
            height: 650px;
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
            background-color: rgba(0, 115, 230, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            pointer-events: none;
            z-index: 10;
            display: none;
            width: calc(100% - 32px);
            transition: font-size 0.2s ease;
        }
#label-container {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap; /* Permette l'andare a capo */
    justify-content: center;
    gap: 10px;
    width: 100%;
    max-width: 800px;
    padding: 0 5px;
}

        .label {
            padding: 5px 15px;
            background-color: #0073e6;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            min-width: 120px;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: font-size 0.2s ease;
            flex: 1;
        }
        .nav-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #0073e6;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }
        .nav-menu button {
            background: none;
            border: none;
            color: white;
            font-size: 1em;
            cursor: pointer;
            flex: 1 0 auto;
            max-width: 100px;
            min-width: 80px;
            text-align: center;
            box-sizing: border-box;
            padding: 5px;
        }
        .nav-menu button.active {
            color: #47cc1f;
        }
        #info-page, #login-page {
            display: none;
            padding: 10px;
            text-align: center;
            width: 100%;
            max-width: 800px;
            box-sizing: border-box;
            overflow-y: auto;
        }
        /* Stili per il modale */
        .modal {
            display: none; /* Inizialmente nascosto */
            position: fixed;
            z-index: 1000; /* Sopra altri contenuti */
            left: 0;
            top: 0;
            width: 100%; /* Larghezza 100% dello schermo */
            height: 100%; /* Altezza 100% dello schermo */
            overflow: auto; /* Abilita lo scroll se necessario */
            background-color: rgba(0, 0, 0, 0.5); /* Sfondo traslucido */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* Margini automatici per centrare il modale */
            padding: 20px;
            border: 1px solid #888;
            width: 300px; /* Larghezza del modale */
            border-radius: 10px; /* Angoli arrotondati */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Stili responsivi */
        @media (max-width: 800px) {
            h1 {
                font-size: 1.8em;
            }
            .buttons-container button {
                flex: 0 1 140px;
                max-width: 140px;
                min-width: 90px;
                font-size: 0.95em;
                padding: 8px 16px;
            }
            #webcam-container.active {
                width: 90vw;
                height: 90vw;
                max-width: 600px;
                max-height: 600px;
            }
            #top-prediction {
                font-size: 14px;
                padding: 6px 12px;
                width: calc(100% - 24px);
            }
            #label-container {
                gap: 8px;
            }
            .label {
                min-width: 120px;
                max-width: 200px;
                font-size: 13px;
                padding: 5px 15px;
                flex: 1;
            }
            .nav-menu button {
                font-size: 0.95em;
                max-width: 90px;
                min-width: 70px;
                padding: 4px;
            }
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 1.5em;
            }
            .buttons-container button {
                flex: 0 1 120px;
                max-width: 120px;
                min-width: 80px;
                font-size: 0.9em;
                padding: 6px 12px;
            }
            #webcam-container.active {
                width: 100vw;
                height: 100vw;
                max-width: 550px;
                max-height: 550px;
            }
            #top-prediction {
                font-size: 13px;
                padding: 5px 10px;
                width: calc(100% - 20px);
            }
            #label-container {
                gap: 6px;
            }
            .label {
                min-width: 120px;
                max-width: 200px;
                font-size: 12px;
                padding: 5px 15px;
                flex: 1;
            }
            .nav-menu button {
                font-size: 0.85em;
                max-width: 80px;
                min-width: 60px;
                padding: 3px;
            }
        }
        @media (max-width: 400px) {
            h1 {
                font-size: 1.2em;
            }
            .buttons-container button {
                flex: 0 1 100px;
                max-width: 100px;
                min-width: 70px;
                font-size: 0.8em;
                padding: 5px 10px;
            }
            #webcam-container.active {
                width: 100vw;
                height: 100vw;
                max-width: 500px;
                max-height: 500px;
            }
            #top-prediction {
                font-size: 12px;
                padding: 4px 8px;
                width: calc(100% - 16px);
            }
            #label-container {
                gap: 4px;
            }
            .label {
                min-width: 150px;
                max-width: 200px;
                font-size: 11px;
                padding: 5px 15px;
                flex: 1;
            }
            .nav-menu button {
                font-size: 0.75em;
                max-width: 70px;
                min-width: 50px;
                padding: 2px;
            }
        }
    </style>
</head>
<body>
    <div id="main-page">
        <h1>Fishpert</h1>
        <h2>Riconoscimento IA di pesci</h2>
        <div class="buttons-container">
            <button type="button" onclick="init()" aria-label="Attiva Webcam">Attiva Webcam</button>
            <input type="file" accept="image/*" capture="camera" onchange="loadPhoto(event)" style="display: none;" id="file-input">
            <button id="photo-button" type="button" onclick="document.getElementById('file-input').click()" aria-label="Scatta Foto">Scatta Foto</button>
            <button id="webcam" type="button" onclick="switchCamera()" disabled aria-label="Cambia Telecamera">Cambia Telecamera</button>
        </div>
        <div id="webcam-container">
            <div id="top-prediction"></div>
        </div>
        <div id="label-container"></div>
    </div>
    <div id="info-page">
        <h1>Informazioni</h1>
        <p>Questa è una semplice applicazione per il riconoscimento di pesci utilizzando l'intelligenza artificiale.</p>
        <p>Puoi scattare una foto e l'applicazione ti dirà che tipo di pesce è.</p>
    </div>

    <!-- Modale di Login -->
<div id="loginModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div class="modal-content" style="background-color: white; padding: 20px; border-radius: 8px; width: 300px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <span class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 10px; font-size: 24px; cursor: pointer; color: #333;">&times;</span>
        <h2 style="text-align: center; margin-bottom: 20px;">Login</h2>
        <input type="email" id="email" placeholder="Email" required style="width: 90%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;" />
        <input type="password" id="password" placeholder="Password" required style="width: 90%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;" />
        <div class="buttons-container" style="text-align: center;">
            <button type="button" id="login-button" style="padding: 10px 20px; border: none; background-color: #4CAF50; color: white; font-size: 16px; cursor: pointer; border-radius: 4px; transition: background-color 0.3s;">
                Login
            </button>
        </div>
    <div class="info-box" style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); max-width: 400px; margin: 20px auto;">
        <strong style="font-size: 18px; color: #333;">Utenti autorizzati (sono i piani differenti):</strong>
        <p style="margin: 10px 0; font-size: 16px; color: #555;"><b>pesce@gmail.com</b> - Utente base</p>
        <p style="margin: 10px 0; font-size: 16px; color: #555;"><b>pescericco@gmail.com</b> - Ricercatore</p>
        <p style="font-style: italic; font-size: 14px; color: #777;">(La password può essere qualsiasi)</p>
    </div>

    </div>
</div>


    <div class="nav-menu">
        <button id="nav-main" class="active" onclick="showPage('main-page')">Foto</button>
        <button id="nav-info" onclick="showPage('info-page')">Info</button>
        <button id="nav-login" onclick="openModal()">Login</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <script>
        const URL = "./";
        let model;
        let webcamInstance;
        let usingFrontCamera = true;
        let lastStablePrediction = null;
        let stableCount = 0;
        const REQUIRED_STABLE_COUNT = 5;

        function showPage(pageId) {
            document.getElementById('main-page').style.display = 'none';
            document.getElementById('info-page').style.display = 'none';
            document.getElementById('loginModal').style.display = 'none';
            document.getElementById(pageId).style.display = 'block';
            document.getElementById('nav-main').classList.remove('active');
            document.getElementById('nav-info').classList.remove('active');
            document.getElementById('nav-login').classList.remove('active');
            document.getElementById(`nav-${pageId.split('-')[0]}`).classList.add('active');
        }

        async function loadModel() {
            try {
                const modelURL = URL + "model.json";
                const metadataURL = URL + "metadata.json";
                model = await tmImage.load(modelURL, metadataURL);
            } catch (error) {
                alert("Errore nel caricamento del modello. Assicurati che i file model.json e metadata.json siano presenti.");
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadModel();
        });

        function openModal() {
            document.getElementById("loginModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("loginModal").style.display = "none";
        }

        // Aggiungere l'event listener per il bottone di login
        document.getElementById("login-button").addEventListener("click", function() {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const loginData = {
                email: email,
                password: password
            };
            console.log(JSON.stringify(loginData)); // Qui puoi mandare il JSON al tuo server

            // Redirect basato sull'email
            if (email === "pesce@gmail.com") {
                closeModal(); // Chiude il modale
                window.location.href = 'grafica2.php';
            } else if(email === "pescericco@gmail.com"){
                closeModal(); // Chiude il modale
                window.location.href = 'grafica3.php';
            } else{
                alert("L'utente non esiste.");
            }
        });

        async function init() {
            if (!model) {
                alert("Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
                return;
            }
            try {
                const webcamContainer = document.getElementById("webcam-container");
                const topPredictionDiv = document.getElementById("top-prediction");
                webcamContainer.classList.add('active');
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error("La tua macchina non supporta l'accesso alla webcam.");
                }
                if (webcamInstance) {
                    await webcamInstance.stop();
                    webcamInstance = null;
                }
                webcamContainer.innerHTML = "";
                const newTopPredictionDiv = document.createElement("div");
                newTopPredictionDiv.id = "top-prediction";
                webcamContainer.appendChild(newTopPredictionDiv);
                webcamInstance = new tmImage.Webcam(650, 650, usingFrontCamera);
                await webcamInstance.setup({ facingMode: usingFrontCamera ? 'user' : 'environment' });
                await webcamInstance.play();
                webcamContainer.appendChild(webcamInstance.canvas);
                newTopPredictionDiv.style.display = "block";
                document.getElementById("webcam").disabled = false;
                window.requestAnimationFrame(loop);
                lastStablePrediction = null;
                stableCount = 0;
            } catch (error) {
                alert("Errore di inizializzazione della webcam. Assicurati di aver concesso i permessi e che la webcam sia disponibile.");
            }
        }

        async function loop() {
            try {
                if (webcamInstance) {
                    webcamInstance.update();
                    if (webcamInstance.canvas) {
                        await predict(webcamInstance.canvas);
                    }
                }
                window.requestAnimationFrame(loop);
            } catch (error) {}
        }

        async function predict(source) {
            try {
                const prediction = await model.predict(source);
                const sortedPredictions = prediction.sort((a, b) => b.probability - a.probability);
                const currentTopPrediction = sortedPredictions[0] ? sortedPredictions[0].className : null;
                if (currentTopPrediction === lastStablePrediction) {
                    stableCount++;
                } else {
                    stableCount = 1;
                    lastStablePrediction = currentTopPrediction;
                }
                const topPredictionDiv = document.getElementById("top-prediction");
                if (topPredictionDiv) {
                    if (stableCount >= REQUIRED_STABLE_COUNT && currentTopPrediction) {
                        const topProbability = (sortedPredictions[0].probability * 100).toFixed(0) + "%";
                        topPredictionDiv.innerText = `${sortedPredictions[0].className}: ${topProbability}`;
                        topPredictionDiv.style.display = "block";
                        updateLabels(sortedPredictions);
                    } else {
                        topPredictionDiv.innerText = "Stabilizza la webcam";
                    }
                }
            } catch (error) {}
        }

        function updateLabels(sortedPredictions) {
            const labelContainer = document.getElementById("label-container");
            labelContainer.innerHTML = "";
            for (let i = 1; i < Math.min(4, sortedPredictions.length); i++) {
                const probabilityPercentage = (sortedPredictions[i].probability * 100).toFixed(0) + "%";
                const classPrediction = `${sortedPredictions[i].className}: ${probabilityPercentage}`;
                const div = document.createElement("div");
                div.innerHTML = classPrediction;
                div.classList.add("label");
                labelContainer.appendChild(div);
                
                adjustLabelFontSize(div);
            }
        }

        function adjustLabelFontSize(label) {
            const maxFontSize = parseFloat(getComputedStyle(label).fontSize);
            const minFontSize = 10;
            let currentFontSize = maxFontSize;
            while (label.scrollWidth > label.clientWidth && currentFontSize > minFontSize) {
                currentFontSize -= 1;
                label.style.fontSize = `${currentFontSize}px`;
            }
        }

        async function loadPhoto(event) {
            if (!model) {
                alert("Il modello non è ancora stato caricato. Attendi qualche istante e riprova.");
                return;
            }
            const file = event.target.files[0];
            if (!file) {
                return;
            }
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
                    webcamContainer.innerHTML = "";
                    const topPredictionDiv = document.createElement("div");
                    topPredictionDiv.id = "top-prediction";
                    webcamContainer.appendChild(topPredictionDiv);
                    const canvas = document.createElement('canvas');
                    canvas.width = 650;
                    canvas.height = 650;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    webcamContainer.appendChild(canvas);
                    topPredictionDiv.style.display = "block";
                    if (canvas) {
                        await predictPhoto(canvas);
                    }
                    document.getElementById('file-input').value = "";
                    document.getElementById("webcam").disabled = true;
                } catch (error) {}
            };
        }

        async function predictPhoto(source) {
            try {
                const prediction = await model.predict(source);
                const sortedPredictions = prediction.sort((a, b) => b.probability - a.probability);
                const topPredictionDiv = document.getElementById("top-prediction");
                if (sortedPredictions.length > 0) {
                    const topProbability = (sortedPredictions[0].probability * 100).toFixed(0) + "%";
                    topPredictionDiv.innerText = `${sortedPredictions[0].className}: ${topProbability}`;
                    updateLabels(sortedPredictions);
                } else {
                    topPredictionDiv.innerText = "Nessuna predizione disponibile";
                }
            } catch (error) {}
        }

        async function switchCamera() {
            usingFrontCamera = !usingFrontCamera;
            init();
        }

        // Mostrare la pagina principale all'inizio
        showPage('main-page');
    </script>
</body>
</html>
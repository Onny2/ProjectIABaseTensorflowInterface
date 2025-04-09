<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishpert - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #89CFF0, #FFD1DC);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }
        .login-container h2 {
            color: #0073e6;
            margin-bottom: 15px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background: #0073e6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-container button:hover {
            background: #005bb5;
        }
        .login-container #noLog{
            width: 100% ;
            margin-top:30px !important;
            padding: 10px;
            background-color: gray;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration:none;
        }
        .login-container #noLog:hover {
            background: red;
        }
        .info-box {
            background: #f0f8ff;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 14px;
        }
       .login-container input{
            width:90%
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Accedi a Fishpert</h2>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password"  required>
        <button type="button" id="login-button">Login</button><br><br>
        
        <a href="demo.php" id="noLog">Continua senza login</a>
        <div class="info-box">
            <strong>Utenti autorizzati(sono i piani differenti):</strong>
            <p><b>pesce@gmail.com</b> - Utente base</p>
            <p><b>pescericco@gmail.com</b> - Ricercatore</p>
            <p><i>(La password può essere qualsiasi)</i></p>
        </div>
    </div>
    <script>
        document.getElementById("login-button").addEventListener("click", function() {
            const email = document.getElementById("email").value;
            if (email === "pesce@gmail.com") {
                window.location.href = 'grafica2.php';
            } else if (email === "pescericco@gmail.com") {
                window.location.href = 'grafica3.php';
            } else {
                alert("L'utente non esiste.");
            }
        });
    </script>
</body>
</html>
<?php
require_once __DIR__ . '/../../controllers/RecuperarController.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="public/css/login.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Khojki:wght@400..700&display=swap');

        *{
            font-family: "Noto Serif Khojki", serif;
        }

        body, html {
            
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Roboto', sans-serif;
            background: #28a745; /* Fondo verde */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            background-color: white;
            padding: 40px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .logo-container {
            position: relative;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .login-box h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .login-box p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .textbox {
            position: relative;
            margin-bottom: 20px;
        }

        .textbox input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        .textbox input:focus {
            border-color: #28a745; /* Verde */
            outline: none;
        }

        .btn {
            width: 100%;
            background: #28a745; /* Verde */
            color: white;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #218838; /* Verde más oscuro */
        }

        .footer {
            margin-top: 20px;
        }

        .footer a {
            color: #28a745;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3sease;
            font-weight: 500;
            text-decoration: underline;
        }

        #enviar:hover{
            background:#077e21;
            color: #fff;
            transition: 0.4s ease;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 1000;
            max-width: 90%;
            width: 300px;
            text-align: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .popup h3 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

        .popup p {
            margin: 10px 0;
            color: #666;
        }

        .popup ul {
            list-style-type: none;
            padding: 0;
        }

        .popup ul li {
            margin: 5px 0;
            color: #333;
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #aaa;
        }

        .popup-close:hover {
            color: #000;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .login-box h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 12px;
            }

            .textbox input {
                font-size: 14px;
                padding: 12px;
            }

            .logo-container {
                width: 80px;
            }

            .popup {
                width: 90%;
                padding: 10px;
            }

            .popup h3 {
                font-size: 18px;
            }
        }
    </style>

</head>
<body>

    <main>
        <div class="container">
            <div class="logo-container">
                <img src="../assets/Logo-Sena.jpg" alt="Logo" class="logo">
            </div>
            <div class="login-box">
                <h2>Recuperar Contraseña</h2>
                <p>Ingrese su correo electrónico para recibir un enlace de recuperación de contraseña.</p>
                <form id="form-recuperar">
                    <div class="textbox">
                        <input type="email" id="correo" name="correo" placeholder="Correo" required>
                    </div>
                    <button type="submit" class="btn" id="enviar">Enviar</button>
                </form>

                <div class="footer">
                    <a href="/gestiondeambientes/login" class="d-block mt-3 text-success return">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </main>

    <div class="popup" id="creditPopup">
        <button class="popup-close" onclick="hidePopup()">X</button>
        <h3>Créditos</h3>
        <p>Desarrollado por:</p>
        <ul>
            <li>Juan Manuel Infante Quiroga</li>
            <li>Julian David Garcia Piñeros</li>
            <li>Luis Enrique Arias</li>
        </ul>
    </div>

    <!-- Recuperar Contraseña -->
    <script>
        document.getElementById("form-recuperar").addEventListener("submit", function(event) {
            event.preventDefault();
            let correo = document.getElementById("correo").value;

            fetch("controllers/RecuperarController.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "correo=" + encodeURIComponent(correo)
            })
            .then(response => response.json())  // 🚀 Cambio aquí
            .then(data => {
                console.log("Respuesta del servidor:", data);
                if (data.success) {
                    Swal.fire("Éxito", data.success, "success");
                } else {
                    Swal.fire("Error", data.error, "error");
                }
            })
            .catch(error => {
                console.error("Error en fetch:", error);
                Swal.fire("Error", "Hubo un problema en el servidor", "error");
            });
        });
    </script>

    <script>
        function showPopup() {
            document.getElementById('creditPopup').style.display = 'block';
        }

        function hidePopup() {
            document.getElementById('creditPopup').style.display = 'none';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

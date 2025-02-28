
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="public/css/login.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
    
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #28a745;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container img {
            width: 80px;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px;
        }

        .btn-primary {
            background-color: #28a745;
            border-radius: 30px;
            padding: 12px;
            border: none;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 300px;
            text-align: center;
        }

        .popup button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #aaa;
        }

        .popup button:hover {
            color: #000;
        }
    </style>


</head>
<body>
    <div class="login-container">
        <img src="assets/Logo-Sena.jpg" alt="logo">
        <h4>Bienvenido al Sistema de Gestión y Control de Ambientes de Formación CDM</h4>
        <br>
        <p>Ingrese su correo y clave para acceder</p>
        
        <form action="/gestiondeambientes/login/login" method="POST">
            <input type="text" class="form-control mb-3" id="login" name="login" placeholder="Correo" required>
            <input type="password" id="password" name="password" class="form-control mb-3" placeholder="Clave" required>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        
        <a href="/gestiondeambientes/recuperar/mostrarFormulario" class="d-block mt-3 text-success">Recuperar contraseña</a>


        <!-- <button class="btn btn-link mt-2" onclick="showPopup()">Créditos</button> -->
    </div>

    <div class="popup" id="creditPopup">
        <button onclick="hidePopup()">X</button>
        <h3>Créditos</h3>
        <p>Desarrollado por el equipo de ADSO</p>
    </div>
    
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

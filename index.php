<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retención de Aprendices</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f4;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #28a745;
            color: white;
        }
        .header h1 {
            font-size: 24px;
        }
        .btn-login {
            padding: 10px 20px;
            background-color: white;
            color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #e9ecef;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        .container h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }
        .container p {
            font-size: 18px;
            max-width: 600px;
            margin-bottom: 20px;
        }
        .btn-start {
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
        }
        .btn-start:hover {
            background-color: #218838;
        }
    </style>

</head>
<body>
    <header class="header">
        <h1>Gestion de Ambientes</h1>
        <a href="views/index.php" class="btn-login">Iniciar Sesión</a>
    </header>
    <main class="container">
        <h2>Bienvenido a la Plataforma de Inventariado de ambientes</h2>
        <p>Facilitamos el seguimiento e inventariado de los ambientes de formación y simplificamos el proceso.</p>
        <a href="views/index.php" class="btn-start">Comenzar</a>
    </main>
</body>
</html>

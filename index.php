<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/router.php';

$router = new Router();
$router->run();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

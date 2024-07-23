<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["dni"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 600px;
            margin: auto;
            padding-top: 50px;
        }
        h2 {
            text-align: center;
        }
        .menu {
            margin: 20px 0;
            text-align: center;
        }
        .menu a {
            margin: 0 15px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo $_SESSION["nombres"]; ?> (Ciclo: <?php echo $_SESSION["ciclo"]; ?>)</h2>
        <div class="menu">
            <a href="admin_carreras.php">Habilitar Cursos</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>

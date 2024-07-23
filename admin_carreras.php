<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('brgqyb8kpejh4z0kecdv-mysql.services.clever-cloud.com', 'u9gyqf9sxdl6pyzg', 'y8vtDipRcC1ut79xjVqC', 'brgqyb8kpejh4z0kecdv');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener todas las carreras menos Ingeniería de Sistemas
$sql = "SELECT CODI_CARR, DESC_CARR FROM carrera WHERE DESC_CARR != 'Ingeniería de Sistemas'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Carrera</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 300px;
            margin: auto;
            padding-top: 50px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Seleccionar Carrera</h2>
        <form action="admin_curso.php" method="post">
            <label for="carrera">Carrera</label>
            <select id="carrera" name="carrera">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["CODI_CARR"] . "'>" . $row["DESC_CARR"] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit">Seleccionar</button>
        </form>
    </div>
</body>
</html>
?>

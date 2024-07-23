<?php
session_start();

$conn = new mysqli('brgqyb8kpejh4z0kecdv-mysql.services.clever-cloud.com', 'u9gyqf9sxdl6pyzg', 'y8vtDipRcC1ut79xjVqC', 'brgqyb8kpejh4z0kecdv');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $clave = $_POST['clave'];
    $ciclo = $_POST['ciclo'];

    if (empty($dni) || empty($clave) || empty($ciclo)) {
        die("Todos los campos son obligatorios");
    }

    $sql_usuario = "SELECT nombre FROM USUARIOS WHERE DNI = ? AND CLAVE = ? AND ACTIVO = '1'";
    $stmt_usuario = $conn->prepare($sql_usuario);
    if (!$stmt_usuario) {
        die("Preparación fallida: " . $conn->error);
    }

    $stmt_usuario->bind_param('ss', $dni, $clave);
    $stmt_usuario->execute();
    $stmt_usuario->store_result();

    if ($stmt_usuario->num_rows > 0) {
        $stmt_usuario->bind_result($nombre_usuario);
        $stmt_usuario->fetch();

        $_SESSION["dni"] = $dni;
        $_SESSION["nombres"] = $nombre_usuario;
        $_SESSION["ciclo"] = $ciclo;
        header("Location: menu.php");
        exit();
    }

    $stmt_usuario->close();
}
$conn->close();
?>

<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si se seleccionó una carrera
if (!isset($_SESSION["dni"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['carrera'])) {
    $_SESSION["carrera"] = $_POST['carrera'];
}

if (!isset($_SESSION["carrera"])) {
    header("Location: admin_carreras.php");
    exit();
}

$carrera = $_SESSION["carrera"];

// Conexión a la base de datos
$conn = new mysqli('brgqyb8kpejh4z0kecdv-mysql.services.clever-cloud.com', 'u9gyqf9sxdl6pyzg', 'y8vtDipRcC1ut79xjVqC', 'brgqyb8kpejh4z0kecdv');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Habilitar curso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['curso_id']) && isset($_POST['accion'])) {
    $curso_id = $_POST['curso_id'];
    $accion = $_POST['accion'];

    if ($accion == 'habilitar') {
        $sql = "UPDATE curricula SET acti_curr = 'S' WHERE CODI_CURS = ? AND codi_carr = ?";
    } else if ($accion == 'deshabilitar') {
        $sql = "UPDATE curricula SET acti_curr = 'N' WHERE CODI_CURS = ? AND codi_carr = ?";
    } else {
        die("Acción no válida");
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param('ss', $curso_id, $carrera);
    if (!$stmt->execute()) {
        die("Error en la ejecución de la consulta: " . $stmt->error);
    }

    $stmt->close();
}

// Obtener todos los cursos de la carrera seleccionada y del primer a tercer ciclo
$sql = "SELECT c.CICL_CURS, c.CODI_CURS, cu.DESC_CURS, c.acti_curr
        FROM curricula c
        JOIN curso cu ON c.CODI_CURS = cu.CODI_CURS
        WHERE c.codi_carr = ? AND c.CICL_CURS BETWEEN 1 AND 3";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $carrera);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: auto;
            padding-top: 50px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .button {
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
        }

        .habilitar {
            background-color: #4CAF50;
            color: white;
        }

        .deshabilitar {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Administración de Cursos</h2>
        <table>
            <tr>
                <th>Codigo</th>
                <th>Ciclo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["CODI_CURS"] . "</td>";
                    echo "<td>" . $row["CICL_CURS"] . "</td>";
                    echo "<td>" . $row["DESC_CURS"] . "</td>";
                    echo "<td>" . ($row["acti_curr"] == 'S' ? 'Habilitado' : 'Deshabilitado') . "</td>";
                    echo "<td>";
                    echo "<form method='post' style='display:inline-block;'>
                            <input type='hidden' name='curso_id' value='" . $row["CODI_CURS"] . "'>
                            <input type='hidden' name='accion' value='" . ($row["acti_curr"] == 'S' ? 'deshabilitar' : 'habilitar') . "'>
                            <button type='submit' class='button " . ($row["acti_curr"] == 'S' ? 'deshabilitar' : 'habilitar') . "'>" . ($row["acti_curr"] == 'S' ? 'Deshabilitar' : 'Habilitar') . "</button>
                          </form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay cursos disponibles para la carrera seleccionada.</td></tr>";
            }
            ?>
        </table>
        <a href="admin_carreras.php">Volver a Seleccionar Carrera</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
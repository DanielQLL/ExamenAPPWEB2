<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      width: 300px;
      margin: auto;
      padding-top: 50px;
    }

    input[type="text"],
    input[type="password"],
    select {
      width: 100%;
      padding: 10px;
      margin: 5px 0 10px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      margin: 10px 0;
      border: none;
      cursor: pointer;
      width: 100%;
    }
  </style>
  <script>
    function validarFormulario() {
      var dni = document.getElementById('dni').value;
      var clave = document.getElementById('clave').value;
      var ciclo = document.getElementById('ciclo').value;

      if (dni == "" || clave == "" || ciclo == "") {
        alert("Todos los campos son obligatorios");
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="container">
    <h2>Inicio de Sesión</h2>
    <form action="login.php" method="post" onsubmit="return validarFormulario();">
      <label for="dni">DNI/CODIGO ESTUDIANTE</label>
      <input type="text" id="dni" name="dni">

      <label for="clave">Clave</label>
      <input type="password" id="clave" name="clave">

      <label for="ciclo">Ciclo</label>
      <select id="ciclo" name="ciclo">
        <?php
        $conn = new mysqli('brgqyb8kpejh4z0kecdv-mysql.services.clever-cloud.com', 'u9gyqf9sxdl6pyzg', 'y8vtDipRcC1ut79xjVqC', 'brgqyb8kpejh4z0kecdv');

        if ($conn->connect_error) {
          die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "SELECT codi_cicl FROM CONTROL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["codi_cicl"] . "'>" . $row["codi_cicl"] . "</option>";
          }
        }

        $conn->close();
        ?>
      </select>

      <button type="submit">Iniciar Sesión</button>
    </form>
  </div>
</body>

</html>
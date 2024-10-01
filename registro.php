<?php
// Incluir la conexión a la base de datos
include 'db_conexion.php';

// Verificar que se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar contraseña
    $matricula = $_POST['matricula'];

    // Verificar que no exista un usuario con el mismo email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El email ya está registrado.";
    } else {
        // Insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, email, password, matricula) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $email, $password, $matricula);

        if ($stmt->execute()) {
            header("Location: success.php");
        } else {
            echo "Error al registrar el usuario.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h2>Registro de Usuarios</h2>
    <form action="registrar.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br><br>

	 <label for="telefono">Teléfono:</label>
        <select name="prefijo" id="prefijo" required>
            <option value="+34" selected>+34 (España)</option>
            <option value="+1">+1 (EE.UU.)</option>
            <option value="+44">+44 (Reino Unido)</option>
            <option value="+49">+49 (Alemania)</option>
            <option value="+33">+33 (Francia)</option>
        </select>
        <input type="tel" name="telefono" required pattern="[0-9]{9}" title="Introduce un número de 9 dígitos"><br><br>


        <label for="edad">Edad:</label>
        <select name="edad" id="edad" required>
            <?php
            for ($edad = 16; $edad <= 100; $edad++) {
                echo "<option value='$edad'>$edad</option>";
            }
            ?>
        </select><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" required><br><br>

	<div class="login-link">
	<p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
	</div>
	
	<input type="submit" value="Registrar">
        
    </form>


</body>
</html>


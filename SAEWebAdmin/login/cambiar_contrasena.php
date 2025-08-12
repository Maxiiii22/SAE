<?php
require_once '../config/db_connection.php'; 
include '../config/config.php'; 
include '../shared/navbar.php'; 

$message = ''; // Variable para almacenar mensajes

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar el token
    $stmt = $conn->prepare("SELECT MAIL_USUARIO FROM usuarios WHERE TOKEN_RECUPERACION = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    $num_rows = $stmt->num_rows; // Guardar el número de filas antes de cerrar la declaración
    $stmt->close(); // Cerrar la declaración de verificación

    if ($num_rows > 0) { // Verificar si hay resultados antes de procesar el formulario
        // Si el token es válido, procesar el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nueva_contrasena = $_POST['nueva_contrasena'];
            $repetir_contrasena = $_POST['repetir_contrasena'];

            // Verificar si las contraseñas coinciden
            if ($nueva_contrasena === $repetir_contrasena) {
                // Hashear la nueva contraseña
                $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la base de datos
                $stmt_update = $conn->prepare("UPDATE usuarios SET CONTRASEÑA_USUARIO = ?, TOKEN_RECUPERACION = NULL WHERE TOKEN_RECUPERACION = ?");
                $stmt_update->bind_param("ss", $nueva_contrasena_hash, $token);
                if ($stmt_update->execute()) {
                    $message = 'Contraseña cambiada con éxito. <a href="http://localhost/saewebadmin/login/login.php">Ir a Login</a>';
                } else {
                    $message = 'Error al cambiar la contraseña: ' . htmlspecialchars($stmt_update->error);
                }
                $stmt_update->close(); // Cerrar la declaración de actualización
            } else {
                $message = 'Las contraseñas no coinciden.';
            }
        }
    } else {
        $message = 'Token inválido o expirado.';
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/login/css/estilo_login.css"> 
</head>

<body>
    <div class="container text-center">
        <h2>Cambiar contraseña</h2>
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['token']) && $num_rows > 0): ?>
            <form method="POST" class="w-25 mx-auto mt-3">
                <div class="mb-3">
                    <label for="nueva_contrasena" class="form-label">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="repetir_contrasena" class="form-label">Repetir Nueva Contraseña:</label>
                    <input type="password" id="repetir_contrasena" name="repetir_contrasena" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

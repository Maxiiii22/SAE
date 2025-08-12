<?php
require '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../config/db_connection.php'; 
include '../config/config.php'; 
include '../shared/navbar.php'; 

// Variable para almacenar mensajes
$message = '';

// Verificar si se ha enviado el formulario
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Comprobar si el correo está registrado
    $stmt = $conn->prepare("SELECT CODIGO_USUARIO FROM usuarios WHERE MAIL_USUARIO = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generar un token aleatorio
        $token = bin2hex(random_bytes(16));

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET TOKEN_RECUPERACION = ? WHERE MAIL_USUARIO = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Enviar el correo con el token
        $mail = new PHPMailer(true); // Habilitar excepciones
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Para Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'recu.pass.sae@gmail.com'; // correo de Gmail
            $mail->Password = 'mdddtsghszxaanxe'; // Tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usa STARTTLS
            $mail->Port = 587; // Puerto SMTP para STARTTLS

            $mail->setFrom('recu.pass.sae@gmail.com', 'SAE recuperación'); // Cambia según tu preferencia
            $mail->addAddress($email); // Usar el correo registrado en la tabla

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8'; // Establecer la codificación a UTF-8
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = 'Haz clic en el siguiente enlace para recuperar tu contraseña: <a href="http://localhost/saewebadmin/login/cambiar_contrasena.php?token=' . $token . '">Recuperar contraseña</a>';

            $mail->send();
            $message = 'Se ha enviado un correo para recuperar la contraseña. Por favor, revise su Correo.';
        } catch (Exception $e) {
            $message = 'No se pudo enviar el correo: ' . $mail->ErrorInfo;
        }
    } else {
        $message = 'El correo no está registrado.';
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/login/css/estilo_login.css"> 
</head>
<body>
    <div class="container text-center">
        <h2>Recuperar contraseña</h2>
        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="w-25 mx-auto mt-3">
            <div class="mb-3">
                <label for="email">Ingresa tu correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
        </form>
    </div>
</body>
</html>


<?php
require_once '../config/db_connection.php';
include '../config/config.php';
include '../shared/navbar.php';

// Mostrar mensaje de éxito si el usuario viene del registro
$successMessage = '';
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $successMessage = "Registro exitoso. Ahora puedes iniciar sesión.";
}

// Mostrar mensaje de error si existe
$errorMessage = '';
if (isset($_GET['error'])) {
    $errorMessage = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/login/css/estilo_login.css">
</head>
<body>
    <div class="container text-center">
        <h2>Iniciar sesión</h2>

        <!-- Mostrar mensaje de éxito -->
        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success w-50 mx-auto mt-3"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Área para mensajes de error -->
        <div id="loginMessage" class="alert d-none w-50 mx-auto mt-3"></div>

        <form id="loginForm" class="w-25 mx-auto mt-3">
            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <a href="recuperar_contrasena.php" class="text-decoration-none">Olvidé mi contraseña</a>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>

    <!-- JavaScript al final del body para asegurar que cargue después del HTML -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loginForm").addEventListener("submit", function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch("api/login_api.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const loginMessage = document.getElementById("loginMessage");
                    if (data.status === "success") {
                        window.location.href = data.redirect;
                    } else {
                        loginMessage.classList.remove("d-none", "alert-success");
                        loginMessage.classList.add("alert-danger");
                        loginMessage.textContent = data.message;
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
</body>
</html>

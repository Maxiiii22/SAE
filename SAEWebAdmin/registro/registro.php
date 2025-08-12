<?php 
require_once '../config/db_connection.php'; 
include '../config/config.php'; 
include '../shared/navbar.php'; 
?> 

<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registro</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/registro/css/estilo_registro.css"> 
</head> 
<body> 
    <div class="container text-center">
        <form id="registroForm" class="formulario-registro w-50 mx-auto"> 
            <h3 class="mb-4">Registro de Usuario</h3>

            <div id="mensaje" class="alert d-none"></div>

            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" id="dni" name="dni" class="form-control" required pattern="\d{7,8}" title="El DNI debe tener entre 7 y 8 dígitos">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <input type="text" id="email" name="email" class="form-control" required pattern="[a-zA-Z0-9._%+-]+" title="Elige solo el nombre de usuario">
                    <span class="input-group-text">@itbeltran.com</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Repetir Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Registrarme</button>
                <button type="reset" class="btn btn-secondary">Borrar</button>
            </div>
        </form> 
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("registroForm").addEventListener("submit", function(event) {
            event.preventDefault(); 
            const formData = new FormData(this);

            fetch("api/registro_api.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const mensaje = document.getElementById("mensaje");
                if (data.status === "success") {
                    // Mostrar SweetAlert y redirigir después
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro Exitoso',
                        text: 'Tu cuenta se ha creado correctamente.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Redirigir a la página prevista
                        window.location.href = "<?php echo BASE_URL; ?>/login/login.php";
                    });
                } else {
                    // Mostrar mensaje de error en la página
                    mensaje.classList.remove("d-none", "alert-success", "alert-danger");
                    mensaje.classList.add("alert-danger");
                    mensaje.textContent = data.message;
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body> 
</html>

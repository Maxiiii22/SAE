<?php
session_start();
$isLoggedIn = isset($_SESSION['usuario']);
$rolUsuario = $_SESSION['rol_usuario'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>/assets/logo1.png" alt="Logo">
        </a>
        <img src="<?php echo BASE_URL; ?>/assets/SAE.png" alt="Tittle1" class="sae">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (!$isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/login/login.php">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/registro/registro.php">Registro</a>
                    </li>
                <?php endif; ?>

                <?php if ($rolUsuario == 1): ?>
                    <li class="nav-item"><p class="nav-link"><strong>Jefe de área</strong></p></li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/jefecarrera/gestion_carreras.php">Gestión de la carrera</a>
                    </li>
                <?php endif; ?>
                <?php if ($rolUsuario == 2): ?>
                    <li class="nav-item"><p class="nav-link"> <strong>Profesor</strong></p></li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/profesor/gestion_clases.php">Mis clases</a>
                    </li>
                <?php endif; ?>
                <?php if ($rolUsuario == 3): ?>
                    <li class="nav-item"><p class="nav-link"><strong>Administrador</strong></p></li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/admin.php">Administración</a>
                    </li>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="btnLogout">Cerrar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutButton = document.getElementById('btnLogout');

        if (logoutButton) {
            logoutButton.addEventListener('click', function (event) {
                event.preventDefault(); // Evita la acción por defecto del enlace

                Swal.fire({
                    title: '¿Estás seguro de que deseas cerrar sesión?',
                    text: "Tendrás que iniciar sesión nuevamente para continuar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cerrar sesión',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige al usuario a logout.php para destruir la sesión
                        window.location.href = '<?php echo BASE_URL; ?>/login/logout.php';
                    }
                });
            });
        }
    });
</script>

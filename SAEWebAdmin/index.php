<?php
//session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAE</title>
    <link rel="icon" href="assets/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="shared/css/dashboard.css">
</head>

<body>
    <?php
    include 'config/config.php'; // Define BASE_URL y otras configuraciones
    include 'shared/navbar.php'; // Incluye la barra de navegación
    ?>

    <!-- Contenido Principal -->
    <div class="container text-center">
        <h1>Sistema de Administración Educativa</h1>
        <p>Bienvenido <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : ' '; ?></p>
    </div>
  

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
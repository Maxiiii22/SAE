<?php
require_once '../config/db_connection.php';
include '../config/config.php';
include '../shared/navbar.php';

// Verificar si el usuario tiene DNI en la sesión
if (!isset($_SESSION['dni_persona'])) {
    echo '<p>Error: No se encontró el DNI en la sesión.</p>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/css/estilo_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="mybody">
    <div class="container-fluid">
        <div class="row">
            <!-- Barra lateral -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-secondary sidebar text-white vh-100 p-3">
                <h3 class="text-center">Menú</h3>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <button class="btn btn-outline-light w-100 text-start" onclick="cargarVista('carreras/admin_carreras.php')">
                            <i class="fas fa-graduation-cap me-2"></i> Carreras
                        </button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="btn btn-outline-light w-100 text-start" onclick="cargarVista('materias/admin_materias.php')">
                            <i class="fas fa-book me-2"></i> Materias
                        </button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="btn btn-outline-light w-100 text-start" onclick="cargarVista('personas/admin_personas.php')">
                            <i class="fas fa-users me-2"></i> Personas
                        </button>
                    </li>
                </ul>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="contenido">
                <h3 class="text-white mt-4">Bienvenido al Panel de Administración</h3>
                <p class="text-white">Seleccione una opción del menú para comenzar.</p>
            </main>
        </div>
    </div>

    <script>
        function cargarVista(pagina) {
            let contenido = document.getElementById('contenido');
            let urlCompleta = '<?php echo BASE_URL; ?>/admin/vistas/' + pagina;

            fetch(urlCompleta)
                .then(response => response.text())
                .then(html => {
                    contenido.innerHTML = html;

                    // 🔴 Eliminar scripts anteriores (agregados dinámicamente)
                    document.querySelectorAll('script[data-dinamico="true"]').forEach(script => script.remove());

                    // 🔄 Cargar script según vista
                    let scriptSrc = null;
                    let initFunction = null;

                    if (pagina === 'personas/admin_personas.php') {
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/personas/personas.js';
                        initFunction = () => {
                            if (typeof inicializarPersonas === "function") inicializarPersonas();
                            if (typeof inicializarEventosFormulario === "function") inicializarEventosFormulario();
                        };
                    } else if (pagina === 'materias/admin_materias.php') {
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/materias/materias.js';
                        initFunction = () => {
                            if (typeof inicializarMaterias === "function") inicializarMaterias();
                            if (typeof inicializarEventosFormulario === "function") inicializarEventosFormulario();
                        };
                    } else if (pagina === 'carreras/admin_carreras.php') {
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/carreras/carreras.js';
                        initFunction = () => {
                            if (typeof inicializarCarreras === "function") inicializarCarreras();
                            if (typeof inicializarEventosFormulario === "function") inicializarEventosFormulario();
                        };
                    }
                    else if (pagina.startsWith('personas/detalle_persona.php')) {
                        const dni = new URLSearchParams(pagina.split('?')[1]).get('dni');
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/personas/detalle_persona.js';
                        initFunction = () => {
                            if (typeof inicializarDetallePersona  === "function") inicializarDetallePersona(dni);
                        };
                    }
                    else if (pagina.startsWith('materias/detalle_materia.php')) {
                        const codMateria = new URLSearchParams(pagina.split('?')[1]).get('codMateria');
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/materias/detalle_materia.js';
                        initFunction = () => {
                            if (typeof inicializarDetalleMateria  === "function") inicializarDetalleMateria(codMateria);
                        };
                    }
                    else if (pagina.startsWith('carreras/detalle_carrera.php')) {
                        const codCarrera = new URLSearchParams(pagina.split('?')[1]).get('codCarrera');
                        scriptSrc = '<?php echo BASE_URL; ?>/admin/js/carreras/detalle_carrera.js';
                        initFunction = () => {
                            if (typeof inicializarDetalleCarrera  === "function") inicializarDetalleCarrera(codCarrera);
                        };
                    }

                    if (scriptSrc) {
                        const newScript = document.createElement("script");
                        newScript.src = scriptSrc;
                        newScript.setAttribute("data-dinamico", "true"); // 🔍 Marca el script para futura eliminación
                        
                        // Ejecutar función de inicialización después de cargar script
                        newScript.onload = () => {
                            if (initFunction) initFunction();
                        };
                        document.body.appendChild(newScript);
                    }

                    // Para `detalle_persona.php`, pasar DNI
                })
                .catch(error => console.error('Error al cargar la vista:', error));
        }
    </script>

</body>

</html>
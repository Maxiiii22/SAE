<?php
require_once '../../../config/db_connection.php';
include '../../../config/config.php';
?>

<div class="container mt-4">
    <h3 class="text-white">Administración de Materias</h3>
    <div class="d-flex justify-content-between align-items-center">
        <label for="selectCarreras" class="text-white">Seleccione una opcion:
            <select name="" id="selectCarreras" class="form-select mb-3"></select>
        </label>
        <button class="btn btn-success" id="btnMostrarFormulario">
            <i class="fas fa-plus"></i> Agregar Materia
        </button>
    </div>  
</div>  

<!-- Formulario para agregar persona (inicialmente oculto) -->
<div id="formAgregarMateriaContainer" class="mt-3 p-3 bg-dark text-white rounded" style="display: none;">
    <h5>Agregar una nueva materia</h5>
    <form id="formAgregarMateria">
        <div class="row d-flex justify-content-center"">
            <div class="col-md-3">
                <label for="selectCarrerasForm" class="form-label">Carreras: </label>
                <select name="" id="selectCarrerasForm" class="form-select" required></select>
            </div>
            <div class="col-md-3">
                <label for="inputCodMateriaForm" class="form-label">ID Materia: </label>
                <input type="text" name="" id="inputCodMateriaForm" class="form-control" required placeholder="Ingrese el ID de la nueva materia">
            </div>
            <div class="col-md-3">
                <label for="inputMateriaForm" class="form-label">Materia: </label>
                <input type="text" name="" id="inputMateriaForm" class="form-control" required placeholder="Ingrese el nombre de la nueva materia">
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            <button type="button" class="btn btn-secondary mx-2" id="btnCancelar">Cancelar</button>
            <button type="button" class="btn btn-primary mx-2" id="btnGuardarMateria">Añadir Materia</button>
        </div>
    </form>
</div>


<div class="table-responsive mt-3">
    <table class="table table-dark table-striped">
        <thead>
            <tr class="text-center">
                <th>Id materia</th>
                <th>Nombre materia</th>
                <th>Carrera</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tablaMaterias">
            <!-- Los datos se llenarán dinámicamente -->
        </tbody>
    </table>
</div>

<!-- Contenedor de paginación -->
<nav>
    <ul class="pagination justify-content-center" id="paginacion">
        <!-- Botones de paginación generados dinámicamente -->
    </ul>
</nav>

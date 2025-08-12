<?php
require_once '../../../config/db_connection.php';
include '../../../config/config.php';
?>

<div class="container mt-4">
    <h3 class="text-white">Administración de Carreras</h3>
    <div class="d-flex justify-content-end">
        <button class="btn btn-success" id="btnMostrarFormulario">
            <i class="fas fa-plus"></i> Agregar Carrera
        </button>
    </div>  
</div>  

<!-- Formulario para agregar persona (inicialmente oculto) -->
<div id="formAgregarCarreraContainer" class="mt-3 p-3 bg-dark text-white rounded" style="display: none;">
    <h5>Agregar una nueva carrera</h5>
    <form id="formAgregarCarrera">
        <div class="row d-flex justify-content-center"">
            <div class="col-md-3">
                <label for="inputNombreForm" class="form-label">Nombre: </label>
                <input type="text" name="" id="inputNombreForm" class="form-control" required placeholder="Ingrese el nombre de la nueva carrera">
            </div>
            <div class="col-md-3">
                <label for="inputNombreAbreviadoForm" class="form-label">Abreviacion: </label>
                <input type="text" name="" id="inputNombreAbreviadoForm" class="form-control" required placeholder="Ingrese la abreviacion de la carrera">
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            <button type="button" class="btn btn-secondary mx-2" id="btnCancelar">Cancelar</button>
            <button type="button" class="btn btn-primary mx-2" id="btnGuardarCarrera">Añadir Carrera</button>
        </div>
    </form>
</div>


<div class="table-responsive mt-3">
    <table class="table table-dark table-striped">
        <thead>
            <tr class="text-center">
                <th>Id carrera</th>
                <th>Nombre</th>
                <th>Abreviacion</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tablaCarreras">
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

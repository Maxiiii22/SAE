<?php
require_once '../../../config/db_connection.php';
include '../../../config/config.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-white">Administración de Personas</h3>
        <button class="btn btn-success" id="btnMostrarFormulario">
            <i class="fas fa-user-plus"></i> Agregar Persona
        </button>
    </div>

    <!-- Formulario para agregar persona (inicialmente oculto) -->
    <div id="formAgregarPersonaContainer" class="mt-3 p-3 bg-dark text-white rounded" style="display: none;">
        <h5>Agregar Nueva Persona</h5>
        <form id="formAgregarPersona">
            <div class="row">
                <div class="col-md-3">
                    <label for="dniNuevo" class="form-label">DNI:</label>
                    <input type="number" class="form-control" id="dniNuevo" required>
                </div>
                <div class="col-md-3">
                    <label for="apellidoNuevo" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" id="apellidoNuevo" required>
                </div>
                <div class="col-md-3">
                    <label for="nombreNuevo" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombreNuevo" required>
                </div>
                <div class="col-md-3">
                    <label for="codigoRolNuevo" class="form-label">Rol:</label>
                    <select class="form-select" id="codigoRolNuevo" required>
                        <option value="" hidden>Seleccione un rol</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end w100%">
                <button type="button" class="btn btn-secondary mx-1" id="btnCancelar">Cancelar</button>
                <button type="button" class="btn btn-primary mx-1" id="btnGuardarPersona">Guardar Persona</button>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-dark table-striped">
            <thead>
                <tr class="text-center">
                    <th>N°</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tablaPersonas">
                <!-- Los datos se llenarán dinámicamente -->
            </tbody>
        </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center" id="paginacion">
            <!-- Botones de paginación generados dinámicamente -->
        </ul>
    </nav>
</div>
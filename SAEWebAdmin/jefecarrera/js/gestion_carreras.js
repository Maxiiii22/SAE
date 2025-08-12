document.addEventListener('DOMContentLoaded', function () {
    const BASE_URL = `http://localhost/saewebadmin`;
    const apiUrl = `${BASE_URL}/jefecarrera/api/jefe_api.php`;

    // Cargar datos iniciales
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const carreras = data.carreras;
                const materias = data.materias;

                const selectCarrera = document.getElementById('selectCarrera');
                carreras.forEach(carrera => {
                    const option = document.createElement('option');
                    option.value = carrera.ID_CARRERA;
                    option.textContent = carrera.DESCRIPCION;
                    selectCarrera.appendChild(option);
                });

                const defaultCarrera = carreras[0];
                document.getElementById('titulo-carrera').textContent = defaultCarrera.TITULO_ABREVIADO;
                document.getElementById('descripcion-carrera').textContent = defaultCarrera.DESCRIPCION;
                let materiasDefaultCarrera = materias.filter(m => m.ID_CARRERA == String(defaultCarrera.ID_CARRERA));
                renderTable(materiasDefaultCarrera);

                const materiasSelect = document.getElementById('filtro-materia');
                const profesoresSelect = document.getElementById('filtro-profe');
                const comisionesSelect = document.getElementById('filtro-comision');
                const turnosSelect = document.getElementById('filtro-turno');
                const aniosSelect = document.getElementById('filtro-anio');

                // Inicializamos los filtros con las opciones de la carrera seleccionada
                const filtroMateriaActual = materiasSelect.getAttribute('data-selected') || '';
                const filtroProfesorActual = profesoresSelect.getAttribute('data-selected') || '';
                const filtroComisionActual = comisionesSelect.getAttribute('data-selected') || '';
                const filtroTurnoActual = turnosSelect.value;
                const filtroAnioActual = aniosSelect.value;

                // Llenar opciones de los filtros con selección previa
                fillFilterOptions(materiasSelect, new Set(materiasDefaultCarrera.map(m => m.NOMBRE_MATERIA)), filtroMateriaActual);
                const profesoresMap = new Map();
                materiasDefaultCarrera.forEach(m => {
                    profesoresMap.set(m.DNI_PERSONA, `${m.NOMBRE_PERSONA} ${m.APELLIDO_PERSONA} (${m.DNI_PERSONA})`);
                });
                fillFilterOptionsFromMap(profesoresSelect, profesoresMap, filtroProfesorActual);
                fillFilterOptions(comisionesSelect, new Set(materiasDefaultCarrera.map(m => m.COMISION)), filtroComisionActual);
                fillFilterOptions(turnosSelect, new Set(materiasDefaultCarrera.map(m => m.TURNO)), filtroTurnoActual);
                fillFilterOptions(aniosSelect, new Set(materiasDefaultCarrera.map(m => m.ANIO)), filtroAnioActual);

                
                // Escuchar el cambio de carrera
                selectCarrera.addEventListener('change', function () {
                    const carreraId = selectCarrera.value;
                    const selectedCarrera = carreras.find(c => c.ID_CARRERA == String(carreraId));

                    if (selectedCarrera) {
                        document.getElementById('titulo-carrera').textContent = selectedCarrera.TITULO_ABREVIADO;
                        document.getElementById('descripcion-carrera').textContent = selectedCarrera.DESCRIPCION;
                        const materiasFiltradasPorCarrera = materias.filter(m => m.ID_CARRERA == String(carreraId));
                
                        renderTable(materiasFiltradasPorCarrera);
                
                        // Filtrar los profesores según las materias de la carrera seleccionada
                        const profesoresMap = new Map();
                        materiasFiltradasPorCarrera.forEach(m => {
                            profesoresMap.set(m.DNI_PERSONA, `${m.NOMBRE_PERSONA} ${m.APELLIDO_PERSONA} (${m.DNI_PERSONA})`);
                        });
                
                        // Actualizar los filtros de profesor y otros
                        fillFilterOptions(materiasSelect, new Set(materiasFiltradasPorCarrera.map(m => m.NOMBRE_MATERIA)), filtroMateriaActual);
                        fillFilterOptionsFromMap(profesoresSelect, profesoresMap, filtroProfesorActual);
                        fillFilterOptions(comisionesSelect, new Set(materiasFiltradasPorCarrera.map(m => m.COMISION)), filtroComisionActual);
                        fillFilterOptions(turnosSelect, new Set(materiasFiltradasPorCarrera.map(m => m.TURNO)), filtroTurnoActual);
                        fillFilterOptions(aniosSelect, new Set(materiasFiltradasPorCarrera.map(m => m.ANIO)), filtroAnioActual);
                
                    }
                });

                // Escuchar cambios en los filtros
                const filtrosForm = document.getElementById('filtros-form');
                filtrosForm.addEventListener('change', function () {
                    const filtroMateria = materiasSelect.value;
                    const filtroProfesor = profesoresSelect.value;
                    const filtroComision = comisionesSelect.value;
                    const filtroTurno = turnosSelect.value;
                    const filtroAnio = aniosSelect.value;
                
                    const carreraId = selectCarrera.value; // Obtener el ID de la carrera seleccionada
                    const materiasFiltradasPorCarrera = materias.filter(materia => materia.ID_CARRERA == String(carreraId));
                
                    // Filtrar las materias en base a los filtros aplicados
                    const materiasFiltradas = materiasFiltradasPorCarrera.filter(materia => {
                        return (
                            (filtroMateria === '' || materia.NOMBRE_MATERIA === filtroMateria) &&
                            (filtroProfesor === '' || String(materia.DNI_PERSONA) === filtroProfesor) &&
                            (filtroComision === '' || materia.COMISION === filtroComision) &&
                            (filtroTurno === '' || materia.TURNO === filtroTurno) &&
                            (filtroAnio === '' || materia.ANIO === filtroAnio)
                        );
                    });
                
                    // Mostrar materias filtradas en la tabla
                    renderTable(materiasFiltradas);
                
                    // Filtrar y actualizar las opciones de los filtros (incluido profesor)
                    updateFilteredOptions(filtroMateria, filtroProfesor, filtroComision, filtroTurno, filtroAnio);
                });

                // Función para renderizar la tabla de materias
                function renderTable(data) {
                    const tbody = document.getElementById('tabla-materias');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No se encontraron resultados para los filtros seleccionados.</td></tr>`;
                        return;
                    }

                    data.forEach(materia => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${materia.CODIGO_MATERIA}</td>
                            <td>${materia.NOMBRE_PERSONA} ${materia.APELLIDO_PERSONA} (<strong>${materia.DNI_PERSONA}</strong>)</td>
                            <td>${materia.NOMBRE_MATERIA}</td>
                            <td>${materia.COMISION}</td>
                            <td>${materia.DIA || 'N/A'}</td>
                            <td>${materia.HORARIO || 'N/A'}</td>
                            <td>${materia.TURNO}</td>
                            <td>${materia.ANIO}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-ver-clases" 
                                        data-cod-materia="${materia.CODIGO_MATERIA}" 
                                        data-comision="${materia.CODIGO_COMISION}"  
                                        data-horario="${materia.CODIGO_HORARIO}" 
                                        data-nombre-materia="${materia.NOMBRE_MATERIA}"> 
                                    Ver Clases
                                </button>
                            </td>`;
                        tbody.appendChild(row);
                    });

                    // Añadir eventos a los botones "Ver Clases"
                    document.querySelectorAll('.btn-ver-clases').forEach(button => {
                        button.addEventListener('click', function () {
                            const codMateria = this.dataset.codMateria;
                            const comision = this.dataset.comision;
                            const horario = this.dataset.horario;
                            const nombreMateria = this.dataset.nombreMateria;

                            // Redirigir con los datos correctamente formateados
                            window.location.href = `${BASE_URL}/jefecarrera/vistas/vista_clases.php?codigo_materia=${codMateria}&comision=${comision}&horario=${horario}&filtro-materia=${nombreMateria}`;
                        });
                    });
                }

                // Función para llenar las opciones de los filtros
                function fillFilterOptions(selectElement, values, currentValue) {
                    const currentSelectedValue = selectElement.value;
                    if(selectElement.name === "filtro-turno" || selectElement.name === "filtro-anio"){
                        selectElement.innerHTML = '<option value="">Todos</option>';
                    }
                    else{
                        selectElement.innerHTML = '<option value="">Todas</option>';
                    }
                    values.forEach(value => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = value;
                        if (value === currentValue || value === currentSelectedValue) {
                            console.log("entro")

                            option.selected = true;
                        }
                        selectElement.appendChild(option);
                    });
                }

                // Para selects que necesitan mostrar valor distinto al texto (como DNI + nombre)
                function fillFilterOptionsFromMap(selectElement, valueMap, currentValue) {
                    const currentSelectedValue = selectElement.value;  // Obtiene el valor actualmente seleccionado del select
                    selectElement.innerHTML = '<option value="">Todos</option>';  // Añade la opción "Todas"

                    valueMap.forEach((label, value) => {
                        const option = document.createElement('option');
                        option.value = value;  // El valor de la opción será el DNI o el valor del profesor
                        option.textContent = label;  // El texto visible será el nombre completo del profesor

                        // Compara el valor de la opción con el valor seleccionado actualmente
                        if (String(value) === String(currentValue) || String(value) === String(currentSelectedValue)) {
                            console.log("Seleccionando opción:", value);  // Depuración
                            option.selected = true;  // Marca la opción como seleccionada si coincide el valor
                        }

                        selectElement.appendChild(option);  // Añadir la opción al select
                    });
                }

                // Función para actualizar opciones de filtros en base a selección actual
                function updateFilteredOptions(filtroMateria, filtroProfesor, filtroComision, filtroTurno, filtroAnio) {
                    const carreraId = selectCarrera.value; // Obtener el ID de la carrera seleccionada
                    const materiasFiltradasPorCarrera = materias.filter(materia => materia.ID_CARRERA == String(carreraId));
                
                    // Filtrar las materias en base a los filtros aplicados
                    const filteredMaterias = materiasFiltradasPorCarrera.filter(materia => {
                        return (
                            (filtroMateria === '' || materia.NOMBRE_MATERIA === filtroMateria) &&
                            (filtroProfesor === '' || String(materia.DNI_PERSONA) === filtroProfesor) &&
                            (filtroComision === '' || materia.COMISION === filtroComision) &&
                            (filtroTurno === '' || materia.TURNO === filtroTurno) &&
                            (filtroAnio === '' || materia.ANIO === filtroAnio)
                        );
                    });
                
                    // Llenar las opciones de los filtros con las materias filtradas
                    fillFilterOptions(materiasSelect, new Set(filteredMaterias.map(m => m.NOMBRE_MATERIA)), filtroMateria);
                
                    // Filtrar y actualizar el filtro de profesores basados en las materias filtradas
                    const profesoresMap = new Map();
                    filteredMaterias.forEach(m => {
                        profesoresMap.set(m.DNI_PERSONA, `${m.NOMBRE_PERSONA} ${m.APELLIDO_PERSONA} (${m.DNI_PERSONA})`);
                    });
                    fillFilterOptionsFromMap(profesoresSelect, profesoresMap, filtroProfesor);
                
                    // Actualizar las demás opciones de los filtros
                    fillFilterOptions(comisionesSelect, new Set(filteredMaterias.map(m => m.COMISION)), filtroComision);
                    fillFilterOptions(turnosSelect, new Set(filteredMaterias.map(m => m.TURNO)), filtroTurno);
                    fillFilterOptions(aniosSelect, new Set(filteredMaterias.map(m => m.ANIO)), filtroAnio);
                }
            } 
            else {
                alert(data.message || 'Error al cargar los datos.');
            }
        })
        .catch(error => console.error('Error:', error));
});

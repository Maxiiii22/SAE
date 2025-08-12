document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = `http://localhost/saewebadmin/jefecarrera/api/vista_profesores_api.php`;

    // Hacer el request a la API para obtener los datos
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const profesores = data.profesores;
                const cardsContainer = document.getElementById('cards-container');
                const filtroProfesor = document.getElementById('filtro-profesor');
                const filtroMateria = document.getElementById('filtro-materia');
                const filtroTurno = document.getElementById('filtro-turno');
                const filtroAnio = document.getElementById('filtro-anio');
                const filtroCarrera = document.getElementById('filtro-carrera');

                let profesoresFiltrados = profesores;

                // Función para llenar los filtros
                function llenarFiltros() {
                    const profesorsSet = new Set();
                    const materiasSet = new Set();
                    const turnosSet = new Set();
                    const aniosSet = new Set();
                    const carrerasSet = new Set();

                    profesores.forEach(profesor => {
                        profesorsSet.add(`${profesor.NOMBRE_PERSONA} ${profesor.APELLIDO_PERSONA}`);
                        if (profesor.MATERIAS_COMPLETAS) {
                            const materias = profesor.MATERIAS_COMPLETAS.split('<br>').map(materia => materia.split(' – ')[0]);
                            materias.forEach(materia => materiasSet.add(materia));

                            const turnos = profesor.MATERIAS_COMPLETAS.split('<br>').map(materia => materia.split(' - Turno ')[1]);
                            turnos.forEach(turno => turnosSet.add(turno));

                            const anios = profesor.MATERIAS_COMPLETAS.split('<br>').map(materia => {
                                const anio = materia.split(' – ')[1].split('°')[0].trim();
                                return anio;
                            });
                            anios.forEach(anio => aniosSet.add(anio));
                        }

                        const carreras = profesor.CARRERAS.split('<br>');
                        carreras.forEach(carrera => carrerasSet.add(carrera));
                    });

                    fillSelect(filtroProfesor, profesorsSet);
                    fillSelect(filtroMateria, materiasSet);
                    fillSelect(filtroTurno, turnosSet);
                    fillSelect(filtroAnio, aniosSet);
                    fillSelect(filtroCarrera, carrerasSet);
                }

                function fillSelect(selectElement, valuesSet) {
                    selectElement.innerHTML = '<option value="">Todas</option>';
                    valuesSet.forEach(value => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = value;
                        selectElement.appendChild(option);
                    });
                }

                function crearCards() {
                    cardsContainer.innerHTML = '';
                    profesoresFiltrados.forEach(profesor => {
                        const card = document.createElement('div');
                        card.classList.add('col-md-3', 'mb-3', 'card-profesor');
                        const materiasCompletas = profesor.MATERIAS_COMPLETAS;
                        const carrerasCompletas = profesor.CARRERAS;
                
                        card.innerHTML = `
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-person-badge"></i> ${profesor.NOMBRE_PERSONA} ${profesor.APELLIDO_PERSONA}</h5>
                                    <p><b>Correo electrónico:</b> ${profesor.MAIL_USUARIO}</p>
                                    <p><b>Cargo:</b> Profesor</p>
                                    <h6><b>Carreras a Cargo:</b></h6>
                                    <div class="card-subtitle-container">
                                        ${carrerasCompletas
                                            .split('<br>')
                                            .map(carrera => `<div class="card-subtitle-item">${carrera}</div>`)
                                            .join('')}
                                    </div>
                                    <h6><b>Materias a Cargo:</b></h6>
                                    <div class="card-subtitle-container">
                                        ${materiasCompletas
                                            .split('<br>')
                                            .map(materia => `<div class="card-subtitle-item">${materia}</div>`)
                                            .join('')}
                                    </div>
                                    <button class="btn btn-danger btn-sm mt-2 eliminar-profesor">
                                        <i class="bi bi-trash"></i> Eliminar Profesor
                                    </button>
                                </div>
                            </div>
                        `;
                        cardsContainer.appendChild(card);
                    });
                
                    agregarEventosEliminar();
                }

                function agregarEventosEliminar() {
                    const eliminarBotones = document.querySelectorAll('.eliminar-profesor');
                    eliminarBotones.forEach(boton => {
                        boton.addEventListener('click', function (event) {
                            event.preventDefault();

                            Swal.fire({
                                title: '¿Estás seguro?',
                                text: "El profesor será eliminado de la vista, pero no se eliminará de la base de datos.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, eliminar',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const card = this.closest('.card-profesor');
                                    card.remove();
                                    Swal.fire(
                                        'Eliminado',
                                        'El profesor ha sido eliminado de la vista.',
                                        'success'
                                    );
                                }
                            });
                        });
                    });
                }

                document.getElementById('filtros-form').addEventListener('change', function () {
                    profesoresFiltrados = profesores.filter(profesor => {
                        return (
                            (filtroProfesor.value === '' || `${profesor.NOMBRE_PERSONA} ${profesor.APELLIDO_PERSONA}`.toLowerCase().includes(filtroProfesor.value.toLowerCase())) &&
                            (filtroMateria.value === '' || (profesor.MATERIAS_COMPLETAS && profesor.MATERIAS_COMPLETAS.toLowerCase().includes(filtroMateria.value.toLowerCase()))) &&
                            (filtroTurno.value === '' || (profesor.MATERIAS_COMPLETAS && profesor.MATERIAS_COMPLETAS.toLowerCase().includes(filtroTurno.value.toLowerCase()))) &&
                            (filtroAnio.value === '' || (profesor.MATERIAS_COMPLETAS && profesor.MATERIAS_COMPLETAS.split('<br>').some(materia => materia.includes(filtroAnio.value)))) &&
                            (filtroCarrera.value === '' || profesor.CARRERAS.toLowerCase().includes(filtroCarrera.value.toLowerCase()))
                        );
                    });

                    crearCards();
                });

                llenarFiltros();
                crearCards();
            }
            else {
                console.error(data.message || 'No se encontraron profesores.');
            }
        })
        .catch(error => console.error('Error al cargar los datos:', error));
});

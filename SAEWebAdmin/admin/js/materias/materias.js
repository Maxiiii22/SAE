(() => {
    const BASE_URL = "http://localhost/SAE_V2/saewebadmin";

    let datosMaterias = [];
    let paginaMateriasActual = 1;
    const registrosPorPaginaMaterias = 10;

    // 🔹 Inicializar eventos del formulario
    function inicializarEventosFormulario() {
        setTimeout(() => {
            let btnMostrarFormulario = document.getElementById("btnMostrarFormulario");
            let formContainer = document.getElementById("formAgregarMateriaContainer");
            let btnCancelar = document.getElementById("btnCancelar");
            let btnGuardar = document.getElementById("btnGuardarMateria");
            let formAgregarMateria = document.getElementById("formAgregarMateria");
            let selectCarrera = document.getElementById("selectCarrerasForm");

            if (!btnMostrarFormulario || !formContainer || !btnCancelar || !btnGuardar || !formAgregarMateria || !selectCarrera) {
                console.warn("⚠️ No se encontraron elementos del formulario de agregar persona.");
                return;
            }

            btnMostrarFormulario.addEventListener("click", function () {
                console.log("📌 Botón Agregar Materia clickeado.");
                formContainer.style.display = "block";
                cargarCarreras("selectCarrerasForm")
            });

            btnCancelar.addEventListener("click", function () {
                console.log("📌 Botón Cancelar clickeado.");
                formContainer.style.display = "none";
                formAgregarMateria.reset();
            });

            btnGuardar.addEventListener("click", function () {
                agregarMateria()
            });

            console.log("✅ Eventos de formulario inicializados correctamente.");
        }, 300);
    }
    // 🔹 Función para cargar las materias según la carrera seleccionada
    function cargarMateriasPorCarrera(idCarrera) {
        fetch(`${BASE_URL}/admin/api/materias/materias_api.php?id_carrera=${idCarrera}`)
            .then(response => response.json())
            .then(data => {
                // Procesar y mostrar las materias
                    datosMaterias = data.materias;
                    paginaMateriasActual = 1;
                    mostrarPagina(paginaMateriasActual);
                    configurarPaginacion();
            })
            .catch(error => console.error("❌ Error al cargar las materias:", error));
    }

    // 🔹 Cargar las carreras y configurar el evento 'change'
    function cargarCarreras(idSelect) {
        fetch(`${BASE_URL}/admin/api/materias/materias_api.php`)
            .then(response => response.json())
            .then(data => {
                let selectCarrera = document.getElementById(idSelect);
                if(selectCarrera.id == "selectCarreras"){
                    selectCarrera.innerHTML = '<option value="">Todas las Carreras</option>';
                }
                else{
                    selectCarrera.innerHTML = '<option value="">Seleccione la carrera de la nueva materia</option>';
                }

                data.carreras.forEach(carrera => {
                    let option = document.createElement("option");
                    option.value = carrera.ID_CARRERA;
                    option.textContent = `(${carrera.TITULO_ABREVIADO}) ${carrera.DESCRIPCION}`;
                    selectCarrera.appendChild(option);
                });

                // Configurar el evento para cuando se seleccione una carrera
                selectCarrera.addEventListener("change", function () {
                    const idCarreraSeleccionada = selectCarrera.value;
                    if (idCarreraSeleccionada) {
                        cargarMateriasPorCarrera(idCarreraSeleccionada);
                    }
                    else{
                        datosMaterias = data.materias;
                        paginaMateriasActual = 1;
                        mostrarPagina(paginaMateriasActual);
                        configurarPaginacion();
                    }
                }); 
            })
            .catch(error => console.error("❌ Error al cargar las carreras:", error));
    }

    // 🔹 Cargar las materias desde la API
    function inicializarMaterias() {
        fetch(`${BASE_URL}/admin/api/materias/materias_api.php`)
            .then(response => response.json())
            .then(data => {
                cargarCarreras("selectCarreras");  // Cargamos las carreras en el Select con el ID="selectCarreras"
                datosMaterias = data.materias;
                paginaMateriasActual = 1;
                mostrarPagina(paginaMateriasActual);
                configurarPaginacion();
            })
            .catch(error => console.error("❌ Error al cargar los datos:", error));
    }

    // 🔹 Mostrar la tabla de materias con paginación
    function mostrarPagina(pagina) {
        let tabla = document.getElementById("tablaMaterias");
        if (!tabla) return;

        tabla.innerHTML = "";
        let inicio = (pagina - 1) * registrosPorPaginaMaterias;
        let fin = inicio + registrosPorPaginaMaterias;
        let datosPagina = datosMaterias.slice(inicio, fin);

        if (datosPagina.length > 0) {
            datosPagina.forEach((materia) => {
                let fila = `<tr class="text-center">
                    <td>${materia.id_materia}</td>
                    <td>${materia.nombre_materia}</td>
                    <td>${materia.descripcion}</td>
                    <td>
                        <button class="btn btn-info btn-sm btn-ver-materia" data-codMateria="${materia.id_materia}">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </td>
                </tr>`;
                tabla.innerHTML += fila;
            });

            setTimeout(() => {
                agregarEventosBotonesVer();
            }, 300);
        } 
        else {
            tabla.innerHTML = `<tr><td colspan="4" class="text-center text-white">No hay datos disponibles de esta materia</td></tr>`;
        }

        configurarPaginacion();
    }

    // 🔹 Configurar paginación
    function configurarPaginacion() {
        let paginacion = document.getElementById("paginacion");
        if (!paginacion) return;

        paginacion.innerHTML = "";
        let totalPaginas = Math.ceil(datosMaterias.length / registrosPorPaginaMaterias);

        for (let i = 1; i <= totalPaginas; i++) {
            let li = document.createElement("li");
            li.className = `page-item ${i === paginaMateriasActual ? 'active' : ''}`;

            let a = document.createElement("a");
            a.className = "page-link";
            a.innerText = i;
            a.href = "#";
            a.onclick = function (event) {
                event.preventDefault();
                paginaMateriasActual = i;
                mostrarPagina(paginaMateriasActual);
            };

            li.appendChild(a);
            paginacion.appendChild(li);
        }
    }

    function agregarMateria() {
        let nuevaMateria = {
            idCarrera : document.getElementById("selectCarrerasForm").value.trim(),
            idMateria : document.getElementById("inputCodMateriaForm").value.trim(),
            nombreMateria : document.getElementById("inputMateriaForm").value.trim()
        };

        if (!nuevaMateria.nombreMateria || !nuevaMateria.idMateria || !nuevaMateria.idCarrera) {
            Swal.fire({
                title: "No se pudo agregar la materia",
                text: "Todos los campos son obligatorios.",
                icon: "warning"
            });
            return;
        }


        fetch(`${BASE_URL}/admin/api/materias/materias_api.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(nuevaMateria)
        })
        .then(response => response.json())
        .then(res => {
            if (res.exito) {
                Swal.fire({
                    title: "Materia añadida",
                    text: res.mensaje,
                    icon: "success"
                });
                document.getElementById("formAgregarMateria").reset();
                document.getElementById("formAgregarMateriaContainer").style.display = "none";
                inicializarMaterias();
            } else {
                Swal.fire({
                    title: "Algo salio mal",
                    text: res.mensaje,
                    icon: "warning"
                });
            }
        })
        .catch(error => console.error("❌ Error al agregar la materia:", error));
    }


    // 🔹 Botones "Ver"
    function agregarEventosBotonesVer() {
        document.querySelectorAll(".btn-ver-materia").forEach(boton => {
            boton.removeEventListener("click", handleVerMateria);
            boton.addEventListener("click", handleVerMateria);
        });
    }

    function handleVerMateria(event) {
        let codMateria = event.currentTarget.getAttribute("data-codMateria");
        console.log("👀 Cargando detalle de materia con ID:", codMateria);
        cargarVista(`materias/detalle_materia.php?codMateria=${codMateria}`, true);
    }

    // 🪄 Exponer funciones necesarias al global
    window.inicializarMaterias = inicializarMaterias;
    window.inicializarEventosFormulario = inicializarEventosFormulario;

})();

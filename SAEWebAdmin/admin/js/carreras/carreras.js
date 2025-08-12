(() => {
    const BASE_URL = "http://localhost/SAE_V2/saewebadmin";

    let datosCarreras = [];
    let paginaCarrerasActual = 1;
    const registrosPorPaginaCarreras = 10;

    // 🔹 Inicializar eventos del formulario
    function inicializarEventosFormulario() {
        setTimeout(() => {
            let btnMostrarFormulario = document.getElementById("btnMostrarFormulario");
            let formContainer = document.getElementById("formAgregarCarreraContainer");
            let btnCancelar = document.getElementById("btnCancelar");
            let btnGuardar = document.getElementById("btnGuardarCarrera");
            let formAgregarCarrera = document.getElementById("formAgregarCarrera");

            if (!btnMostrarFormulario || !formContainer || !btnCancelar || !btnGuardar || !formAgregarCarrera) {
                console.warn("⚠️ No se encontraron elementos del formulario de agregar cerrera.");
                return;
            }

            btnMostrarFormulario.addEventListener("click", function () {
                formContainer.style.display = "block";
            });

            btnCancelar.addEventListener("click", function () {
                formContainer.style.display = "none";
                formAgregarCarrera.reset();
            });

            btnGuardar.addEventListener("click", function () {
                agregarCarrera()
            });

            console.log("✅ Eventos de formulario inicializados correctamente.");
        }, 300);
    }

    // 🔹 Cargar las CARRERRAS desde la API
    function inicializarCarreras() {
        fetch(`${BASE_URL}/admin/api/carreras/carreras_api.php`)
            .then(response => response.json())
            .then(data => {
                datosCarreras = data.carreras;
                paginaCarrerasActual = 1;
                mostrarPagina(paginaCarrerasActual);
                configurarPaginacion();
            })
            .catch(error => console.error("❌ Error al cargar los datos:", error));
    }

    // 🔹 Mostrar la tabla de materias con paginación
    function mostrarPagina(pagina) {
        let tabla = document.getElementById("tablaCarreras");
        if (!tabla) return;

        tabla.innerHTML = "";
        let inicio = (pagina - 1) * registrosPorPaginaCarreras;
        let fin = inicio + registrosPorPaginaCarreras;
        let datosPagina = datosCarreras.slice(inicio, fin);

        if (datosPagina.length > 0) {
            datosPagina.forEach((carrera) => {
                let fila = `<tr class="text-center">
                    <td>${carrera.ID_CARRERA}</td>
                    <td>${carrera.DESCRIPCION}</td>
                    <td>${carrera.TITULO_ABREVIADO}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm btn-ver-carrera" data-codCarrera="${carrera.ID_CARRERA}">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                    </td>
                </tr>`;
                tabla.innerHTML += fila;
            });

            setTimeout(() => {
                agregarEventosBotonesVer();
            }, 300);
        } else {
            tabla.innerHTML = '<tr><td colspan="4" class="text-center text-white">No hay datos disponibles</td></tr>';
        }

        configurarPaginacion();
    }

    // 🔹 Configurar paginación
    function configurarPaginacion() {
        let paginacion = document.getElementById("paginacion");
        if (!paginacion) return;

        paginacion.innerHTML = "";
        let totalPaginas = Math.ceil(datosCarreras.length / registrosPorPaginaCarreras);

        for (let i = 1; i <= totalPaginas; i++) {
            let li = document.createElement("li");
            li.className = `page-item ${i === paginaCarrerasActual ? 'active' : ''}`;

            let a = document.createElement("a");
            a.className = "page-link";
            a.innerText = i;
            a.href = "#";
            a.onclick = function (event) {
                event.preventDefault();
                paginaCarrerasActual = i;
                mostrarPagina(paginaCarrerasActual);
            };

            li.appendChild(a);
            paginacion.appendChild(li);
        }
    }

    function agregarCarrera() {
        let nuevaCarrera = {
            nombreCarrera : document.getElementById("inputNombreForm").value.trim(),
            abreviacion : document.getElementById("inputNombreAbreviadoForm").value.trim()
        };

        if (!nuevaCarrera.nombreCarrera || !nuevaCarrera.abreviacion) {
            Swal.fire({
                title: "No se pudo agregar la carrera",
                text: "Todos los campos son obligatorios.",
                icon: "warning"
            });
            return;
        }

        fetch(`${BASE_URL}/admin/api/carreras/carreras_api.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(nuevaCarrera)
        })
        .then(response => response.json())
        .then(res => {
            if (res.exito) {
                Swal.fire({
                    title: "Carrera añadida",
                    text: res.mensaje,
                    icon: "success"
                });
                document.getElementById("formAgregarCarrera").reset();
                document.getElementById("formAgregarCarreraContainer").style.display = "none";
                inicializarCarreras();
            } else {
                Swal.fire({
                    title: "Algo salio mal",
                    text: res.mensaje,
                    icon: "warning"
                });
            }
        })
        .catch(error => console.error("❌ Error al agregar la carrera:", error));
    }


    // 🔹 Botones "Ver"
    function agregarEventosBotonesVer() {
        document.querySelectorAll(".btn-ver-carrera").forEach(boton => {
            boton.removeEventListener("click", handleVerCarrera);
            boton.addEventListener("click", handleVerCarrera);
        });
    }

    function handleVerCarrera(event) {
        let codCarrera = event.currentTarget.getAttribute("data-codCarrera");
        cargarVista(`carreras/detalle_carrera.php?codCarrera=${codCarrera}`, true);
    }

    // 🪄 Exponer funciones necesarias al global
    window.inicializarCarreras = inicializarCarreras;
    window.inicializarEventosFormulario = inicializarEventosFormulario;

})();

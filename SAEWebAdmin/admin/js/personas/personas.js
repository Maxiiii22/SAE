(() => {
    const BASE_URL = "http://localhost/saewebadmin";

    let datosPersonas = [];
    let paginaPersonasActual = 1;
    const registrosPorPaginaPersonas = 10;

    // 🔹 Inicializar eventos para mostrar y ocultar el formulario de Agregar Persona
    function inicializarEventosFormulario() {
        setTimeout(() => {
            let btnMostrarFormulario = document.getElementById("btnMostrarFormulario");
            let formContainer = document.getElementById("formAgregarPersonaContainer");
            let btnCancelar = document.getElementById("btnCancelar");
            let btnGuardar = document.getElementById("btnGuardarPersona");
            let formAgregarPersona = document.getElementById("formAgregarPersona");
            let selectRol = document.getElementById("codigoRolNuevo");

            if (!btnMostrarFormulario || !formContainer || !btnCancelar || !btnGuardar || !formAgregarPersona || !selectRol) {
                console.warn("⚠️ No se encontraron elementos del formulario de agregar persona.");
                return;
            }

            btnMostrarFormulario.addEventListener("click", function () {
                console.log("📌 Botón Agregar Persona clickeado.");
                formContainer.style.display = "block";
                cargarRoles();
            });

            btnCancelar.addEventListener("click", function () {
                console.log("📌 Botón Cancelar clickeado.");
                formContainer.style.display = "none";
                formAgregarPersona.reset();
            });

            btnGuardar.addEventListener("click", function () {
                agregarPersona();
            });

            console.log("✅ Eventos de formulario inicializados correctamente.");
        }, 300);
    }

    function cargarRoles() {
        fetch(`${BASE_URL}/admin/api/personas/personas_api.php`)
            .then(response => response.json())
            .then(data => {
                let selectRol = document.getElementById("codigoRolNuevo");
                selectRol.innerHTML = '<option value="">Seleccione un rol</option>';

                data.roles.forEach(rol => {
                    let option = document.createElement("option");
                    option.value = rol.CODIGO_ROL;
                    option.textContent = rol.DESCRIPCION;
                    selectRol.appendChild(option);
                });

                console.log("✅ Roles cargados correctamente.");
            })
            .catch(error => console.error("❌ Error al cargar los roles:", error));
    }

    function agregarPersona() {
        let nuevaPersona = {
            dni: document.getElementById("dniNuevo").value.trim(),
            apellido: document.getElementById("apellidoNuevo").value.trim(),
            nombre: document.getElementById("nombreNuevo").value.trim(),
            codigo_rol: document.getElementById("codigoRolNuevo").value
        };

        if (!nuevaPersona.dni || !nuevaPersona.apellido || !nuevaPersona.nombre || !nuevaPersona.codigo_rol) {
            Swal.fire({
                title: "No se pudo agregar a la persona",
                text: "Todos los campos son obligatorios.",
                icon: "warning"
            });
            return;
        }

        fetch(`${BASE_URL}/admin/api/personas/personas_api.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(nuevaPersona)
        })
        .then(response => response.json())
        .then(res => {
            if (res.exito) {
                Swal.fire({
                    title: res.mensaje,
                    icon: "success"
                });
                document.getElementById("formAgregarPersona").reset();
                document.getElementById("formAgregarPersonaContainer").style.display = "none";
                inicializarPersonas();
            } else {
                Swal.fire({
                    title: res.mensaje,
                    icon: "warning"
                });
            }
        })
        .catch(error => console.error("❌ Error al agregar persona:", error));
    }

    function inicializarPersonas() {
        fetch(`${BASE_URL}/admin/api/personas/personas_api.php`)
            .then(response => response.json())
            .then(data => {
                datosPersonas = data.personas;
                paginaPersonasActual = 1;
                mostrarPagina(paginaPersonasActual);
                configurarPaginacion();
            })
            .catch(error => console.error("❌ Error al cargar los datos:", error));
    }

    function mostrarPagina(pagina) {
        let tabla = document.getElementById("tablaPersonas");
        if (!tabla) return;

        tabla.innerHTML = "";
        let inicio = (pagina - 1) * registrosPorPaginaPersonas;
        let fin = inicio + registrosPorPaginaPersonas;
        let datosPagina = datosPersonas.slice(inicio, fin);

        if (datosPagina.length > 0) {
            datosPagina.forEach((persona, index) => {
                let numeroOrden = inicio + index + 1;
                let fila = `<tr class="text-center">
                    <td>${numeroOrden}</td>
                    <td>${persona.apellido}</td>
                    <td>${persona.nombre}</td>
                    <td>${persona.dni}</td>
                    <td>${persona.descripcion_rol}</td>
                    <td>
                        <input type="checkbox" ${persona.registrado == 1 ? 'checked' : ''} style="pointer-events: none;" class="form-check-input">
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm btn-ver-persona" data-dni="${persona.dni}">
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
            tabla.innerHTML = '<tr><td colspan="7" class="text-center text-white">No hay datos disponibles</td></tr>';
        }

        configurarPaginacion();
    }

    function configurarPaginacion() {
        let paginacion = document.getElementById("paginacion");
        if (!paginacion) return;

        paginacion.innerHTML = "";
        let totalPaginas = Math.ceil(datosPersonas.length / registrosPorPaginaPersonas);

        for (let i = 1; i <= totalPaginas; i++) {
            let li = document.createElement("li");
            li.className = `page-item ${i === paginaPersonasActual ? 'active' : ''}`;

            let a = document.createElement("a");
            a.className = "page-link";
            a.innerText = i;
            a.href = "#";
            a.onclick = function (event) {
                event.preventDefault();
                paginaPersonasActual = i;
                mostrarPagina(paginaPersonasActual);
            };

            li.appendChild(a);
            paginacion.appendChild(li);
        }
    }

    function agregarEventosBotonesVer() {
        document.querySelectorAll(".btn-ver-persona").forEach(boton => {
            boton.removeEventListener("click", handleVerPersona);
            boton.addEventListener("click", handleVerPersona);
        });
    }

    function handleVerPersona(event) {
        let dni = event.currentTarget.getAttribute("data-dni");
        console.log("👀 Cargando detalle de persona con DNI:", dni);
        cargarVista(`personas/detalle_persona.php?dni=${dni}`);
    }

    // 🪄 Exponer funciones necesarias
    window.inicializarPersonas = inicializarPersonas;
    window.inicializarEventosFormulario = inicializarEventosFormulario;

})();

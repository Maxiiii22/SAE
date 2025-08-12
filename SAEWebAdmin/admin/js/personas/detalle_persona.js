(() => {
    const BASE_URL = "http://localhost/SAE_V2/saewebadmin";

    function inicializarDetallePersona(dni) {
        fetch(BASE_URL + "/admin/api/personas/detalle_personas_api.php?dni=" + dni)
            .then(response => response.json())
            .then(data => {
                document.getElementById("dni").value = data.DNI_PERSONA || "";
                document.getElementById("apellido").value = data.APELLIDO_PERSONA || "";
                document.getElementById("nombre").value = data.NOMBRE_PERSONA || "";

                let rolSelect = document.getElementById("codigo_rol");
                rolSelect.innerHTML = "";
                data.roles.forEach(rol => {
                    let option = new Option(rol.DESCRIPCION, rol.CODIGO_ROL);
                    if (rol.CODIGO_ROL == data.CODIGO_ROL) option.selected = true;
                    rolSelect.add(option);
                });

                let usuarioContainer = document.getElementById("usuarioContainer");
                let mensajeUsuarioNoRegistrado = document.getElementById("mensajeUsuarioNoRegistrado");
                let mailInput = document.getElementById("mail_usuario");
                let passInput = document.getElementById("contrasena_usuario");

                if (data.MAIL_USUARIO && data.CONTRASEÑA_USUARIO) {
                    mailInput.value = data.MAIL_USUARIO;
                    mailInput.disabled = false;
                    passInput.value = data.CONTRASEÑA_USUARIO;
                    passInput.disabled = false;
                    usuarioContainer.style.display = "block";
                    mensajeUsuarioNoRegistrado.style.display = "none";
                } else {
                    usuarioContainer.style.display = "block";
                    mensajeUsuarioNoRegistrado.style.display = "block";
                    mailInput.closest('.row').style.display = "none";
                    mailInput.disabled = true;
                    passInput.disabled = true;

                }

                let tabla = document.getElementById("tablaMaterias");
                tabla.innerHTML = "";
                if (data.CODIGO_ROL == 1) {
                    let headTabla = document.getElementById("headTablaMaterias");
                    const btnAsignarCarrera = document.getElementById("btnAsignarCarrera");
                    btnAsignarCarrera.style.display = "block"
                    headTabla.innerHTML = `
                    <tr class="text-center">
                        <th colspan="2">Carreras asignadas a este usuario</th>
                    </tr>
                    `;
                    
                    const carrerasDisponibles = data.carreras.filter(carrera => {  // Filtramos las carreras para que no se muestren las que ya están asignadas
                        return !data.carrerasAsignadas.some(c => c.ID_CARRERA === carrera.ID_CARRERA);
                    });
                    tabla.innerHTML += `
                    <tr>
                        <td>
                            <select id="selectAsignarCarreraNewRow" class="form-select select-carrera-td select-asignarCarrera" required disabled>
                                <option value="" hidden>Seleccionar Carrera</option>
                                ${carrerasDisponibles.map(c => `<option value="${c.ID_CARRERA}">${c.DESCRIPCION}</option>`).join("")}
                            </select>
                        </td>
                        <td>
                        </td>                        
                    </tr>`;
                    if (data.carrerasAsignadas && data.carrerasAsignadas.length > 0) {   // Si tiene carrera asignadas, validamos que no sean null antes de seleccionarlas
                        data.carrerasAsignadas.forEach(carrera => {
                            tabla.innerHTML += `
                            <tr>
                                <td class="w-100">
                                    <select class="form-control select-carrera-td active">
                                        <option value="${carrera.ID_CARRERA}" hidden>${carrera.DESCRIPCION}</option>
                                    </select>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="form-control btn btn-danger w-auto btnEliminarCarreraPersona" 
                                        data-materiapersonadni="${data.DNI_PERSONA}"
                                        data-materiapersonacarrera="${carrera.ID_CARRERA}"
                                        data-materiapersonarol="${data.CODIGO_ROL}"
                                        <i class="fas fa-times"></i> Desasignar Carrera
                                    </button>
                                </td>    
                            </tr>`;
                        });
                        
                    }
                    const btnsDesasignarCarrera = document.querySelectorAll(".btnEliminarCarreraPersona");
                    btnsDesasignarCarrera.forEach(btn =>{
                        btn.addEventListener("click", function(){
                            desasignarCarrera(btn)
                        })
                    });
                }
                else if (data.CODIGO_ROL == 2){
                    const btnAsignarMateria = document.getElementById("btnAñadirMateria");
                    btnAsignarMateria.style.display = "block"
                    
                    tabla.innerHTML += `
                        <tr>
                            <td>
                                <select id="selectCarreraNewRow" class="form-select select-carrera select-newValue" required disabled>
                                    <option value="" hidden>Seleccionar Carrera</option>
                                    ${data.carreras.map(c => `<option value="${c.ID_CARRERA}">${c.DESCRIPCION}</option>`).join("")}
                                </select>
                            </td>
                            <td>
                                <select class="form-select select-materia select-newValue" required disabled>
                                    <option value="" hidden>Seleccionar Materia</option>
                                    ${data.materiasList.map(m => `<option value="${m.CODIGO_MATERIA}">${m.NOMBRE_MATERIA}</option>`).join("")}
                                </select>
                            </td>
                            <td>
                                <select class="form-select select-comision select-newValue" required disabled>
                                    <option value="" hidden>Seleccionar Comisión</option>
                                    ${data.comisiones.map(co => `<option value="${co.CODIGO_COMISION}">${co.DESCRIPCION}</option>`).join("")}
                                </select>
                            </td>
                            <td>
                                <select class="form-select select-horario select-newValue" required disabled>
                                    <option value="" hidden>Seleccionar Horario</option>
                                    ${data.horarios.map(h => `<option value="${h.CODIGO_HORARIO}">${h.HORARIO}</option>`).join("")}
                                </select>
                            </td>
                            <td></td>
                        </tr>`;

                    if (data.materias.length >= 1) {   // Si tiene materias asignadas, validamos que no sean null antes de seleccionarlas
                        data.materias.forEach(materia => {
                            tabla.innerHTML += `
                            <tr>
                                <td>
                                    <select class="form-control selectMaterias select-carrera active">
                                        ${data.carreras.map(c => 
                                            `<option value="${c.ID_CARRERA}" ${materia.ID_CARRERA ? (c.ID_CARRERA == materia.ID_CARRERA ? "selected" : "") : ""}>${c.DESCRIPCION}</option>`).join("")}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control selectMaterias select-materia active">
                                        ${data.materiasList.map(m => 
                                            `<option value="${m.CODIGO_MATERIA}" ${materia.CODIGO_MATERIA ? (m.CODIGO_MATERIA == materia.CODIGO_MATERIA ? "selected" : "") : ""}>${m.NOMBRE_MATERIA}</option>`).join("")}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control selectMaterias select-comision active">
                                        ${data.comisiones.map(co => 
                                            `<option value="${co.CODIGO_COMISION}" ${materia.CODIGO_COMISION ? (co.CODIGO_COMISION == materia.CODIGO_COMISION ? "selected" : "") : ""}>${co.DESCRIPCION}</option>`).join("")}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control selectMaterias select-horario active">
                                        ${data.horarios.map(h => 
                                            `<option value="${h.CODIGO_HORARIO}" ${materia.CODIGO_HORARIO ? (h.CODIGO_HORARIO == materia.CODIGO_HORARIO ? "selected" : "") : ""}>${h.HORARIO}</option>`).join("")}
                                    </select>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="form-control btn btn-danger btnEliminarMateriaPersona w-auto" 
                                    data-materiapersonadni="${data.DNI_PERSONA}"
                                    data-materiapersonacarrera="${materia.ID_CARRERA}"
                                    data-materiapersonamateria="${materia.CODIGO_MATERIA}"
                                    data-materiapersonacomision="${materia.CODIGO_COMISION}"
                                    data-materiapersonahorario="${materia.CODIGO_HORARIO}"
                                    data-materiapersonarol="${data.CODIGO_ROL}">
                                    <i class="fas fa-times"></i> 
                                    Desasignar Materia
                                    </button>
                                </td>
                            </tr>`;
                        });
                    }
                    // Aquí configuramos el evento change para cada select de carrera
                    let selectCarrera = document.querySelectorAll('.select-carrera');
                    selectCarrera.forEach(select => {
                        select.addEventListener('change', function() {
                            const carreraId = this.value;
                            let datos = false;
                            actualizarMateriasPorCarrera(this, carreraId, datos, data);
                        });
                    });

                    const selectCarreraNewRow = document.getElementById("selectCarreraNewRow");
                    const selectsNewRow = document.querySelectorAll('.select-newValue');
                    selectCarreraNewRow.addEventListener('change', function() {
                        const carreraId = this.value;
                        let datos = false;
                        selectsNewRow.forEach(select =>{
                            select.disabled = false;
                        })
                        actualizarMateriasPorCarrera(this, carreraId, datos, data);
                    });

                    // Actualizar materias de los selects de cada fila al cargar la página
                    actualizarMateriasAlCargar(data);

                    const btnsDesasignarMateria = document.querySelectorAll(".btnEliminarMateriaPersona");
                    btnsDesasignarMateria.forEach(btn =>{
                        btn.addEventListener("click", function(){
                            console.log(btn)
                            desasignarMateria(btn);
                        })
                    });
                }
                else if (data.CODIGO_ROL == 3){
                    const tabla = document.querySelector(".content-table");
                    tabla.style.display = "none";
                }

            })
            .catch(error => console.error("Error al cargar los datos:", error));
    }

    // Esta función actualiza las opciones de materia en el select según la carrera seleccionada
    function actualizarMateriasPorCarrera(selectCarrera, carreraId,datos, data) {
        const selectMateria = selectCarrera.closest('tr').querySelector('.select-materia');

        // Guardar el valor seleccionado previamente (si existe)
        const materiaSeleccionada = selectMateria.value;

        // Limpiar las opciones actuales del select de materia
        selectMateria.innerHTML = ''; // Eliminamos todas las opciones actuales

        if(datos){
            // Agregar la opción por defecto solo si no hay materia seleccionada
            const optionDefault = document.createElement('option');
            optionDefault.value = datos.key;
            optionDefault.hidden = true;
            optionDefault.textContent = datos.value;
            selectMateria.appendChild(optionDefault);
        }
        else{
            const optionDefault = document.createElement('option');
            optionDefault.value = "";
            optionDefault.hidden = true;
            optionDefault.textContent = "Seleccionar Materia";
            selectMateria.appendChild(optionDefault);
        }

        // Filtrar las materias que corresponden a la carrera seleccionada
        const materiasFiltradas = data.materiasList.filter(materia => materia.ID_CARRERA == carreraId);
        // Agregar las nuevas opciones de materia
        materiasFiltradas.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.CODIGO_MATERIA;
            option.textContent = materia.NOMBRE_MATERIA;
            selectMateria.appendChild(option);
        });

        // Si la materia seleccionada previamente es válida, mantenerla seleccionada
        if (materiaSeleccionada && materiasFiltradas.some(materia => materia.CODIGO_MATERIA == materiaSeleccionada)) {
            selectMateria.value = materiaSeleccionada;
        }
    }

    // Esta función se asegura de que, al cargar la página, se actualicen las materias en cada fila según la carrera seleccionada
    function actualizarMateriasAlCargar(data) {
        const filas = document.querySelectorAll("#tablaMaterias tr");

        filas.forEach((fila, index) => {
            const selectCarrera = fila.querySelector('.select-carrera');
            const selectMateria = fila.querySelector('.select-materia');
            
            // Si hay una carrera seleccionada, actualizamos las materias
            if (selectCarrera.value) {
                const selectedOption = selectMateria.options[selectMateria.selectedIndex];
                let datos = {
                    key: selectMateria.value,
                    value: selectedOption.textContent // Aquí usamos el texto de la opción seleccionada
                };

                actualizarMateriasPorCarrera(selectCarrera, selectCarrera.value, datos, data);
            }


        });
    }
    const btnAccionMateria = document.getElementById("btnAñadirMateria");
    
    function toggleMateria(isClick = false) {
        let newValues = document.querySelectorAll(".select-newValue");
        let selectsMaterias = document.querySelectorAll(".selectMaterias");
        let btnsDesasignarMateria = document.querySelectorAll(".btnEliminarMateriaPersona");


        if (isClick) {
            // Si la función fue llamada por un click
            if (btnAccionMateria.id === "btnAñadirMateria") {
                btnAccionMateria.id = "btnCancelarAñadirMateria";
                btnAccionMateria.textContent = "Cancelar";
                selectsMaterias.forEach(select => {
                    select.disabled = true;
                });
    
                newValues.forEach((select,index )=> {
                    select.classList.add("active");
                    select.disabled = index !== 0; // Deshabilita todos excepto el primero
                });
    
                btnsDesasignarMateria.forEach(btn=> {
                    btn.disabled = true; 
                });
            } 
            else if(btnAccionMateria.id === "btnCancelarAñadirMateria") {
                btnAccionMateria.id = "btnAñadirMateria";
                btnAccionMateria.innerHTML = '<i class="fas fa-plus"></i> Asignarle Nueva Materia';
                selectsMaterias.forEach(select => {
                    select.classList.add("active");
                    select.disabled = false;
                });
    
                newValues.forEach(select => {
                    select.selectedIndex = 0
                    select.classList.remove("active");
                    select.disabled = true;
                });
    
                btnsDesasignarMateria.forEach(btn=> {
                    btn.disabled = false; 
                });
            }        
        } 
        else{
            if(btnAccionMateria.id === "btnCancelarAñadirMateria") {
                btnAccionMateria.id = "btnAñadirMateria";
                btnAccionMateria.innerHTML = '<i class="fas fa-plus"></i> Asignarle Nueva Materia';
                selectsMaterias.forEach(select => {
                    select.classList.add("active");
                    select.disabled = false;
                });
    
                newValues.forEach(select => {
                    select.selectedIndex = 0
                    select.classList.remove("active");
                    select.disabled = true;
                });
    
                btnsDesasignarMateria.forEach(btn=> {
                    btn.disabled = false; 
                });
            }   
        }


    }
    btnAccionMateria.addEventListener("click", () => toggleMateria(true));


    const btnAccionCarrera = document.getElementById("btnAsignarCarrera");

    function toggleCarrera(isClick = false) {
        let newCarrera = document.querySelector(".select-asignarCarrera");
        let rowsCarreras = document.querySelectorAll(".select-carrera-td");
        let btnsDesasignarCarrera = document.querySelectorAll(".btnEliminarCarreraPersona");


        if (isClick) {
            if (btnAccionCarrera.id === "btnAsignarCarrera") {
                btnAccionCarrera.id = "btnCancelarDesasignarCarrera";
                btnAccionCarrera.textContent = "Cancelar";

                rowsCarreras.forEach(select=> {
                    select.disabled = true;
                });
                
                newCarrera.classList.add("active");
                newCarrera.disabled = false; 

                btnsDesasignarCarrera.forEach(btn=> {
                    btn.disabled = true; 
                });
            } 
            else if(btnAccionCarrera.id === "btnCancelarDesasignarCarrera") {
                btnAccionCarrera.id = "btnAsignarCarrera";
                btnAccionCarrera.innerHTML = '<i class="fas fa-plus"></i> Asignarle Nueva Carrera';

                rowsCarreras.forEach(select=> {
                    select.classList.add("active");
                    select.disabled = false;
                });            

                newCarrera.classList.remove("active");
                newCarrera.disabled = true; 

                btnsDesasignarCarrera.forEach(btn=> {
                    btn.disabled = false; 
                });
            }
        }
        else{
            if(btnAccionCarrera.id === "btnCancelarDesasignarCarrera") {
                btnAccionCarrera.id = "btnAsignarCarrera";
                btnAccionCarrera.innerHTML = '<i class="fas fa-plus"></i> Asignarle Nueva Carrera';

                rowsCarreras.forEach(select=> {
                    select.classList.add("active");
                    select.disabled = false;
                });            

                newCarrera.classList.remove("active");
                newCarrera.disabled = true; 

                btnsDesasignarCarrera.forEach(btn=> {
                    btn.disabled = false; 
                });
            }
        }
    }

    btnAccionCarrera.addEventListener("click", () => toggleCarrera(true));    

    document.getElementById("formDetallePersona").addEventListener("submit", function(event) {
        event.preventDefault();
        const rol = document.getElementById("codigo_rol").value

        if (rol === "1"){
            let datos = {
                dni: document.getElementById("dni").value,
                apellido: document.getElementById("apellido").value,
                nombre: document.getElementById("nombre").value,
                codigo_rol: document.getElementById("codigo_rol").value,
                mail_usuario: document.getElementById("mail_usuario") ? document.getElementById("mail_usuario").value : "",
                contrasena_usuario: document.getElementById("contrasena_usuario") ? document.getElementById("contrasena_usuario").value : "",
                carreras: []
            };
            for (let key in datos) {
                // Validar solo los campos que no sean mail_usuario ni contrasena_usuario
                if ((key !== "mail_usuario" && key !== "contrasena_usuario" && key !== "carreras") && (datos[key] === "" || (Array.isArray(datos[key]) && datos[key].length === 0))) {
                    Swal.fire({
                        title: "Campos incompletos",
                        text: `Por favor, complete todos los campos. El campo ${key} está vacío.`,
                        icon: "warning"
                    });
                    return; 
                }
            }
    
            let carrerasValidas = true;
            datos.carreras = [];
            const filas = document.querySelectorAll("#tablaMaterias tr");
        
            for (let tr of filas) {
                const selects = tr.querySelectorAll("select");
                console.log(selects)
            
                // ⛔ Saltar la fila si alguno de los <select> NO tiene la clase "active"
                const todosActivos = Array.from(selects).every(select => select.classList.contains("active"));
                if (!todosActivos) continue;
                const carrera = {
                    id_carrera: tr.querySelector(".select-carrera-td").value
                };
                // ✅ Validar campos llenos
                if (carrera.id_carrera !== "") {
                    datos.carreras.push(carrera);
                } else {
                    Swal.fire({
                        title: "No se pudo asignar la carrera",
                        text: "Complete todos los campos en las carreras activas.",
                        icon: "warning"
                    });
                    carrerasValidas = false;
                    break;
                }
            }
            
            if (!carrerasValidas) return; // ⛔ Salimos antes de hacer el fetch

            // ✅ Si llegamos acá, todas las carreras están completas:
            fetch(BASE_URL + "/admin/api/personas/guardar_persona_api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(res => {
                Swal.fire({
                    title: "Persona actualizada",
                    text: res.mensaje,
                    icon: "success"
                });
                inicializarDetallePersona(datos.dni)
                toggleCarrera(false);
            })
            .catch(error => console.error("Error al guardar los cambios:", error));
        }
        else if (rol === "2"){
            let datos = {
                dni: document.getElementById("dni").value,
                apellido: document.getElementById("apellido").value,
                nombre: document.getElementById("nombre").value,
                codigo_rol: document.getElementById("codigo_rol").value,
                mail_usuario: document.getElementById("mail_usuario") ? document.getElementById("mail_usuario").value : "",
                contrasena_usuario: document.getElementById("contrasena_usuario") ? document.getElementById("contrasena_usuario").value : "",
                materias: []
            };
    
            for (let key in datos) {
                // Validar solo los campos que no sean mail_usuario ni contrasena_usuario
                if ((key !== "mail_usuario" && key !== "contrasena_usuario" && key !== "materias") && (datos[key] === "" || (Array.isArray(datos[key]) && datos[key].length === 0))) {
                    Swal.fire({
                        title: "Campos incompletos",
                        text: `Por favor, complete todos los campos. El campo ${key} está vacío.`,
                        icon: "warning"
                    });
                    return; 
                }
    
            }
    
            let materiasValidas = true;
            datos.materias = [];
            
            const filas = document.querySelectorAll("#tablaMaterias tr");
            
            for (let tr of filas) {
                const selects = tr.querySelectorAll("select");
            
                // ⛔ Saltar la fila si alguno de los <select> NO tiene la clase "active"
                const todosActivos = Array.from(selects).every(select => select.classList.contains("active"));
                if (!todosActivos) continue;
            
                const materia = {
                    id_carrera: tr.querySelector(".select-carrera").value,
                    codigo_materia: tr.querySelector(".select-materia").value,
                    codigo_comision: tr.querySelector(".select-comision").value,
                    codigo_horario: tr.querySelector(".select-horario").value
                };
            
                // ✅ Validar campos llenos
                if (
                    materia.id_carrera !== "" &&
                    materia.codigo_materia !== "" &&
                    materia.codigo_comision !== "" &&
                    materia.codigo_horario !== ""
                ) {
                    datos.materias.push(materia);
                } else {
                    Swal.fire({
                        title: "No se pudo agregar materia",
                        text: "Complete todos los campos en las materias activas.",
                        icon: "warning"
                    });
                    materiasValidas = false;
                    break;
                }
            }
            
            if (!materiasValidas) return; // ⛔ Salimos antes de hacer el fetch

            // ✅ Si llegamos acá, todas las materias están completas:
            fetch(BASE_URL + "/admin/api/personas/guardar_persona_api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(res => {
                Swal.fire({
                    title: "Persona actualizada",
                    text: res.mensaje,
                    icon: "success"
                });
                toggleMateria();
                inicializarDetallePersona(datos.dni)
            })
            .catch(error => console.error("Error al guardar los cambios:", error));
        }
    });

    function desasignarMateria(botonDesasignarMateria) {
        const dni = botonDesasignarMateria.dataset.materiapersonadni;
        const carrera = botonDesasignarMateria.dataset.materiapersonacarrera;
        const materia = botonDesasignarMateria.dataset.materiapersonamateria;
        const comision = botonDesasignarMateria.dataset.materiapersonacomision;
        const horario = botonDesasignarMateria.dataset.materiapersonahorario;
        const rol = botonDesasignarMateria.dataset.materiapersonarol;
    
        // Crea un objeto con todos los datos
        const datosMateria = {
            dni,
            carrera,
            materia,
            comision,
            horario,
            rol
        };
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción desasignará la materia de esta persona.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, desasignar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(BASE_URL + "/admin/api/personas/eliminar_materiaPersona_api.php", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datosMateria)  
                })
                .then(response => response.json())
                .then(res => {
                    Swal.fire({
                        title: "Materia desasignada",
                        text: res.mensaje,
                        icon: "success"
                    });
                    inicializarDetallePersona(datosMateria.dni);  
                })
                .catch(error => console.error("❌ Error al eliminar:", error));
            } 
        });
    }

    function desasignarCarrera(botonDesasignarCarrera) {
        const dni = botonDesasignarCarrera.dataset.materiapersonadni;
        const carrera = botonDesasignarCarrera.dataset.materiapersonacarrera;
        const rol = botonDesasignarCarrera.dataset.materiapersonarol;

        // Crea un objeto con todos los datos
        const datosCarrera = {
            dni,
            carrera,
            rol
        };
    
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción desasignará la carrera de esta persona.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, desasignar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(BASE_URL + "/admin/api/personas/eliminar_materiaPersona_api.php", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datosCarrera)  
                })
                .then(response => response.json())
                .then(res => {
                    Swal.fire({
                        title: "Carrera desasignada",
                        text: res.mensaje,
                        icon: "success"
                    });
                    inicializarDetallePersona(datosCarrera.dni);  
                })
                .catch(error => console.error("❌ Error al eliminar:", error));
            } 
        });
    }


    document.getElementById("btnEliminar").addEventListener("click", function() {
        let dni = document.getElementById("dni").value;
        let rol = document.getElementById("codigo_rol").value;

        // Verificar si la persona tiene materias asignadas antes de intentar eliminar
        fetch(BASE_URL + "/admin/api/personas/eliminar_persona_api.php?dni=" + dni + "&rol=" + rol)
            .then(response => response.json())
            .then(data => {
                if (!data.exito) {
                    Swal.fire({
                        title: "No se puede eliminar",
                        text: data.mensaje,
                        icon: "warning"
                    });
                } else {
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "Esta acción eliminará a la persona y su usuario asociado.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(BASE_URL + "/admin/api/personas/eliminar_persona_api.php", {
                                    method: "DELETE",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        dni,
                                        rol
                                    })
                                })
                                .then(response => response.json())
                                .then(res => {
                                    Swal.fire({
                                        title: res.exito ? "Eliminado" : "Error",
                                        text: res.mensaje,
                                        icon: res.exito ? "success" : "error"
                                    }).then(() => {
                                        if (res.exito) {
                                            cargarVista('personas/admin_personas.php', true);
                                        }
                                    });
                                })
                                .catch(error => console.error("❌ Error al eliminar:", error));
                        }
                    });
                }
            })
            .catch(error => console.error("❌ Error al verificar:", error));
    });


    
    window.inicializarDetallePersona = inicializarDetallePersona;


})();

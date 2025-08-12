(() => {
    const BASE_URL = "http://localhost/SAE_V2/saewebadmin";

    function inicializarDetalleMateria(codMateria) {
        fetch(BASE_URL + "/admin/api/materias/detalle_materia_api.php?codMateria=" + codMateria + "&fetch=1")
            .then(response => response.json())
            .then(data => {
                const selectCarrera = document.getElementById("selectCarrera");
                selectCarrera.innerHTML = "";
                data.carreras.forEach(carrera => {
                    let option = document.createElement("option");
                    option.value = carrera.ID_CARRERA;
                    option.textContent = `(${carrera.TITULO_ABREVIADO}) ${carrera.DESCRIPCION}`;
                    selectCarrera.appendChild(option);
                });                
                let option = selectCarrera.querySelector(`option[value="${data.materia.ID_CARRERA}"]`);  // Selecciona el <option> que tenga un value que coincida con `data.ID_CARRERA`
                if (option) {
                    option.selected = true;
                }
                document.getElementById("codMateria").value = data.materia.CODIGO_MATERIA || "";
                document.getElementById("nombreMateria").value = data.materia.NOMBRE_MATERIA || "";
            })
            .catch(error => console.error("Error al cargar los datos:", error));
    }



    document.getElementById("formDetalleMateria").addEventListener("submit", function(event) {
        event.preventDefault();

        let datos = {
            idCarrera: document.getElementById("selectCarrera").value,
            codMateria: document.getElementById("codMateria").value,
            nombreMateria: document.getElementById("nombreMateria").value
        };

        for (let key in datos) {
            if (datos[key] === "" || (Array.isArray(datos[key]) && datos[key].length === 0)) {
                Swal.fire({
                    title: "Campos incompletos",
                    text: `Por favor, complete todos los campos. El campo ${key} está vacío.`,
                    icon: "warning"
                });
                return; 
            }
        }

        fetch(BASE_URL + "/admin/api/materias/detalle_materia_api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(res => {
            Swal.fire({
                title: res.exito ? "Materia actualizada" : "Error",
                text: res.mensaje,
                icon: res.exito ? "success" : "error"
            });
            if (res.exito) {
                inicializarDetalleMateria(datos.codMateria);
            }
        })
        .catch(error => console.error("Error al guardar los cambios:", error));
    });

    window.inicializarDetalleMateria = inicializarDetalleMateria;


})();

(() => {
    const BASE_URL = "http://localhost/saewebadmin";

    function inicializarDetalleMateria(codMateria) {
        fetch(BASE_URL + "/admin/api/materias/detalle_materia_api.php?codMateria=" + codMateria + "&fetch=1")
            .then(response => response.json())
            .then(data => {
                document.getElementById("codMateria").value = data.CODIGO_MATERIA || "";
                document.getElementById("nombreMateria").value = data.NOMBRE_MATERIA || "";
            })
            .catch(error => console.error("Error al cargar los datos:", error));
    }



    document.getElementById("formDetalleMateria").addEventListener("submit", function(event) {
        event.preventDefault();

        let datos = {
            codMateria: document.getElementById("codMateria").value,
            nombreMateria: document.getElementById("nombreMateria").value,
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

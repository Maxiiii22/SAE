(() => {
    const BASE_URL = "http://localhost/SAE_V2/saewebadmin";

    function inicializarDetalleCarrera(codCarrera) {
        fetch(BASE_URL + "/admin/api/carreras/detalle_carrera_api.php?codCarrera=" + codCarrera + "&fetch=1")
            .then(response => response.json())
            .then(data => {
                document.getElementById("codCarrera").value = data.ID_CARRERA || "";
                document.getElementById("nombreCarrera").value = data.DESCRIPCION || "";
                document.getElementById("abreviacionCarrera").value = data.TITULO_ABREVIADO || "";
            })
            .catch(error => console.error("Error al cargar los datos:", error));
    }



    document.getElementById("formDetalleCarrera").addEventListener("submit", function(event) {
        event.preventDefault();

        let datos = {
            codCarrera: document.getElementById("codCarrera").value,
            nombreCarrera: document.getElementById("nombreCarrera").value,
            abreviacion: document.getElementById("abreviacionCarrera").value
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

        fetch(BASE_URL + "/admin/api/carreras/detalle_carrera_api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(res => {
            Swal.fire({
                title: res.exito ? "Carrera actualizada" : "Error",
                text: res.mensaje,
                icon: res.exito ? "success" : "error"
            });
            if (res.exito) {
                inicializarDetalleCarrera(datos.codCarrera);
            }
        })
        .catch(error => console.error("Error al guardar los cambios:", error));
    });

    window.inicializarDetalleCarrera = inicializarDetalleCarrera;


})();

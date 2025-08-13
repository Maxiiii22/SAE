# SAE - Sistema de Administración Educativa

**SAE** es una plataforma de gestión académica que permita a profesores y responsables de área llevar un registro de clases, materias, novedades y profesores de las diferentes carreras del instituto. 
El sistema les permite a profesores documentar, gestionar y dar seguimiento a las clases del instituto. Estará disponible tanto para profesores como responsables de cada área, los cuales podrán crear perfiles y acceder a la plataforma mediante un sistema de registro y login. Al ingresar, los usuarios tendrán diferentes funciones disponibles dependiendo de su rol en el instituto.

## Pantalla Principal
Esta es la pantalla inicial del sistema. Desde aquí, el usuario puede navegar a las diferentes secciones del Sistema 
de Administración Educativa (SAE). En la parte superior se encuentra la barra de navegación que contiene enlaces 
para iniciar sesión, registrarse o acceder a funcionalidades específicas según el rol del usuario.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img1.jpg)

---
## Pantalla de Registro de Usuario
Esta pantalla permite a los usuarios autorizados registrarse en el sistema proporcionando información básica como 
DNI, correo electrónico y contraseña.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img2.jpg)

---
## Pantalla de Inicio de Sesión
Haciendo clic en “Iniciar sesión”, se muestra la pantalla para ingresar las credenciales correspondientes para 
acceder al sistema.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img3.jpg)

---
## Pantalla de Gestión de Clases
Desde el menú principal, iniciando sesión con perfil de usuario **Profesor** se accede automáticamente a la sección 
Mis Materias, donde se muestra un listado de todas las materias asignadas al profesor. 
Desde aquí, selecciona el botón Ver clases en una materia específica para ver su detalle.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img4.jpg)

## Lista de Clases
Muestra una tabla con todas las clases cargadas de la materia seleccionada. Cada fila incluye información relevante 
como fecha, horario, aula, temas tratados y novedades.
- Botón "Nueva Clase": Haciendo clic en el botón "Nueva Clase", se abre un formulario emergente para agregar 
los detalles de una nueva clase.
- Botón "Modificar Clase": Para editar una clase existente, haz clic en el ícono de lápiz en la fila correspondiente. 
Esto abrirá un modal con los datos de la clase, que podrás actualizar y guardar.
- Botón "Eliminar Clase": Haciendo clic en el ícono de papelera, podrás eliminar una clase tras confirmar en el 
mensaje emergente.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img5.jpg)

---

## Pantalla de Gestión de Carreras
Desde el menú principal, iniciando sesión con perfil de usuario **Jefe de Área** se accede automáticamente a la 
sección Gestión de carreras. Esta pantalla permite consultar información de los profesores y ver y filtrar materias 
asociadas a una carrera.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img6.jpg)

## Pantalla de Detalle de Clases
Muestra el detalle de las clases asociadas a una materia específica seleccionada previamente.
Si no hay datos, se mostrará un mensaje indicando que no hay clases para mostrar.
- Barra de Búsqueda: Permite filtrar las clases en tiempo real ingresando palabras clave relacionadas con los 
temas, novedades o cualquier otro campo.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img7.jpg)

## Pantalla de Consulta de Profesores
Accediendo desde el botón “Profesores de la carrera”, se puede consultar la información de los profesores 
asociados a la carrera.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img8.jpg)

---

## Pantalla de Panel de Administración
Desde el menú principal, iniciando sesión con perfil de **Superadministrador** se accede automáticamente a la sección 
Panel de administración, donde se muestran tres botones que nos permitirán gestionar las carreras, materias y
personas del sistema.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img13.jpg)

## Pantalla de Gestión de Materias
Muestra una tabla con todas las materias en el sistema. Cada fila incluye información relevante como ID, nombre y 
la carrera asociada.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img9.jpg)

## Pantalla de Detalle de Materia
Haciendo clic en el botón " Ver", Esto nos llevará a la pantalla de Detalle de Materia donde 
podremos modificar los detalles de la materia seleccionada.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img10.jpg)

## Pantalla de Gestión de Personas
Muestra una tabla con todas las personas en el sistema. Cada fila incluye información relevante como N°, apellido, 
nombre, DNI, rol y si está registrado o no.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img11.jpg)

## Pantalla de Detalle de Persona (Profesor)
- Botón "Asignarle Nueva Materia": Al hacer clic en el botón "Asignarle Nueva Materia", se activará el 
desplegable "Seleccionar Carrera", lo que nos permitirá elegir una carrera. A partir de ahí, se activarán los 
desplegables "Seleccionar Materia", "Seleccionar Comisión" y "Seleccionar Horario" para asignar la nueva 
materia.
- Botón "Desasignar Materia": Haciendo clic en el botón " Desasignar Materia ", podrás desasignar una 
materia tras confirmar en el mensaje emergente.
- Botón "Eliminar Persona": Haciendo clic en el botón " Eliminar Persona ", podrás eliminar a la persona si es 
que no tiene materias asignadas.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img12.jpg)

## Pantalla de Detalle de Persona (Jefe de Área)
- Botón "Asignarle Nueva Carrera": Haciendo clic en el botón " Asignar Nueva Carrera ", Se activara el 
desplegable “Seleccionar Carrera” que nos permitirá poder asignarle una nueva carrera a la persona.
- Botón "Desasignar Carrera": Haciendo clic en el botón " Desasignar Carrera ", podrás desasignar una 
carrera tras confirmar en el mensaje emergente.
- Botón "Eliminar Persona": Haciendo clic en el botón " Eliminar Persona ", podrás eliminar a la persona si es 
que no tiene carreras asignadas.

![Vista previa](Documentacion/SRS%20-%20Archivos/imagesPreview/img14.jpg)

---

## 🧠 Tecnologías Utilizadas

| Tecnología       | Descripción                                      |
|------------------|--------------------------------------------------|
| Django           | Framework backend principal                      |
| mySql            | Base de datos por defecto para desarrollo        |
| HTML/CSS         | Maquetación responsive y diseño visual adaptable. |
| JavaScript       | Funcionalidades personalizadas del lado del cliente. |

---

> **Superadmin de testeo:**
> User: `adminsup@itbeltran.com`
> Password: `111`

> **Jefe de área de testeo:**
> User: `admin@itbeltran.com`
> Password: `111`

> **Profesor de testeo:**
> User: `ramirezj@itbeltran.com`
> Password: `111`

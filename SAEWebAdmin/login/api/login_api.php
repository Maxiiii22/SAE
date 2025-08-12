<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8'); // Forzar el tipo de contenido a JSON
require_once '../../config/db_connection.php';
require_once '../../config/config.php';

function obtenerDatosUsuario($email) {
    global $conn;

    $sql = "SELECT p.DNI_PERSONA, p.NOMBRE_PERSONA, p.APELLIDO_PERSONA, p.CODIGO_ROL
            FROM usuarios u
            JOIN personas p ON u.DNI_PERSONA = p.DNI_PERSONA
            WHERE u.MAIL_USUARIO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $datos = $result->fetch_assoc();

    $stmt->close();
    return $datos;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Por favor ingrese ambos campos."]);
        exit();
    }

    // Verificar si el usuario existe
    $sql = "SELECT MAIL_USUARIO, CONTRASEÑA_USUARIO FROM usuarios WHERE MAIL_USUARIO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        echo json_encode(["status" => "error", "message" => "El usuario no está registrado."]);
        exit();
    }

    $stmt->bind_result($db_email, $hashed_password);
    $stmt->fetch();

    // Validar la contraseña
    if (!password_verify($password, $hashed_password)) {
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta."]);
        exit();
    }

    // Obtener datos del usuario
    $datosUsuario = obtenerDatosUsuario($email);
    if (empty($datosUsuario)) {
        echo json_encode(["status" => "error", "message" => "Error al obtener los datos del usuario."]);
        exit();
    }

    // Iniciar sesión y almacenar los datos del usuario
    session_start();
    $_SESSION['dni_persona'] = $datosUsuario['DNI_PERSONA'];
    $_SESSION['usuario'] = $datosUsuario['NOMBRE_PERSONA'] . ' ' . $datosUsuario['APELLIDO_PERSONA'];
    $_SESSION['rol_usuario'] = $datosUsuario['CODIGO_ROL'];

    // Redirigir basado en el rol del usuario
    switch ($_SESSION['rol_usuario']) {
        case 1:
            $redirectURL = BASE_URL . "/jefecarrera/gestion_carreras.php";
            break;
        case 2:
            $redirectURL = BASE_URL . "/profesor/gestion_clases.php";
            break;
        case 3:
            $redirectURL = BASE_URL . "/admin/admin.php";
            break;
        default:
            $redirectURL = BASE_URL . "login/login.php"; // Opcional: página por defecto si el rol no está definido
            break;
    }

    echo json_encode(["status" => "success", "redirect" => $redirectURL]);
}
?>

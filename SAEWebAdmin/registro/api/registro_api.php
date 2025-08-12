<?php
require_once '../../config/db_connection.php';

function registrarUsuario($dni, $email, $password) {
    global $conn;

    // Verificar si el DNI está en la tabla personas
    $sql_persona = "SELECT DNI_PERSONA FROM personas WHERE DNI_PERSONA = ?";
    $stmt = $conn->prepare($sql_persona);
    $stmt->bind_param("i", $dni);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $stmt->close();
        echo json_encode(["status" => "error", "message" => "Usted no se encuentra autorizado a registrarse, por favor comuníquese con la Secretaría."]);
        exit();
    }

    $stmt->close();

    // Agregar el dominio al email
    $email .= "@itbeltran.com";

    // Verificar si ya existe el DNI o el email en usuarios
    $sql_usuario = "SELECT CODIGO_USUARIO FROM usuarios WHERE DNI_PERSONA = ? OR MAIL_USUARIO = ?";
    $stmt = $conn->prepare($sql_usuario);
    $stmt->bind_param("is", $dni, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        echo json_encode(["status" => "error", "message" => "El usuario ya está registrado."]);
        exit();
    }

    $stmt->close();

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el usuario en la tabla usuarios
    $sql_insert = "INSERT INTO usuarios (DNI_PERSONA, MAIL_USUARIO, CONTRASEÑA_USUARIO) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iss", $dni, $email, $hashed_password);
    $result = $stmt->execute();

    $stmt->close();

    // Enviar JSON con la URL de redirección si el registro es exitoso
    if ($result) {
        echo json_encode(["status" => "success", "redirect" => "../login/login.php?status=success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar el usuario."]);
    }
    
}

// Lógica para recibir el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
        exit();
    }

    registrarUsuario($dni, $email, $password);
}
?>

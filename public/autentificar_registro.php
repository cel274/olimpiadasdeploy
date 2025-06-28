<?php
session_start();
require 'agvi_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['usuario']);
    $password = $_POST['clave'];
    $default_rol = 1;

    if(strlen($username) < 3 || strlen($password) < 4){
        $_SESSION['register_error'] = "Usuario o clave demasiado cortos.";
        header("Location: registro.php");
        exit();
    }

    $check = $conn->prepare("SELECT id FROM usuarios WHERE nombre = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $_SESSION['register_error'] = "Ese nombre ya existe.";
        header("Location: registro.php");
        exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, clave, rol) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $hashed, $default_rol);

    if($stmt->execute()){
        $_SESSION['register_success'] = "Registro exitoso, ya podés iniciar sesión.";
        header("Location: registro.php");
        exit();
    } else {
        $_SESSION['register_error'] = "Error en la base de datos.";
        header("Location: registro.php");
        exit();
    }
}
?>

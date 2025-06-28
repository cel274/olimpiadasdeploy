<?php
session_start();
require 'agvi_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['usuario']);
    $password = trim($_POST['clave']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['clave'])) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['idUsuario'] = $usuario['id']; // <-- corregido acá
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta.";
            header("Location: loginUI.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Usuario no encontrado.";
        header("Location: loginUI.php");
        exit();
    }
}
?>

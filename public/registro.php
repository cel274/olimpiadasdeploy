<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agencia de Viajes</title>
  <link rel="stylesheet" href="styles/login.css">
  <link rel="icon" href="imgs/logo.png" type="image/png">
</head>
<body>
  <header class="header">
    <nav class="nav" style="margin-right: 45px;">
      <a href="loginUI.php">Login</a>
      <a href="index.php">Inicio</a>
    </nav>
  </header>
  <div class="login-container">
    <img src="imgs/logo.png" alt="Logo" class="logo">
    <h1 class="title">Agencia de Viajes</h1>
    <?php
    if (isset($_SESSION['register_error'])) {
        echo "<p style='color:red'>" . $_SESSION['register_error'] . "</p>";
        unset($_SESSION['register_error']);
    }
    if (isset($_SESSION['register_success'])) {
        echo "<p style='color:green'>" . $_SESSION['register_success'] . "</p>";
        unset($_SESSION['register_success']);
    }
    ?>
    <form class="login-form" action="autentificar_registro.php" method="POST">
      <label for="usuario">Usuario</label>
      <input type="text" id="usuario" name="usuario" required>
      <label for="clave">Contraseña</label>
      <input type="password" id="clave" name="clave" required>
      <button type="submit">Registrarse</button>
    </form>
  </div>
</body>
</html>

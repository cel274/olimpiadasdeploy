<?php
include("agvi_db.php");
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="imgs/logo.png" type="image/png">
    <title>Agencia de Viajes</title> 
</head>
<script>
    const cart = [];

    function addToCart(nombre, pais, precio, id) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const existente = carrito.find(p => p.nombre === nombre && p.pais === pais);
        if (existente) {
            existente.cantidad++;
        } else {
            carrito.push({ nombre, pais, cantidad: 1, precio, id });
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));

        const toast = document.createElement('div');
        toast.innerText = `${nombre} agregado al carrito`;
        toast.style.background = '#323232';
        toast.style.color = '#fff';
        toast.style.padding = '12px 20px';
        toast.style.marginTop = '10px';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
        toast.style.fontFamily = 'sans-serif';
        toast.style.transition = 'opacity 0.5s ease';
        toast.style.opacity = '1';
        document.getElementById('toast-container').appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="./imgs/logo.png" alt="">
                </span>
                <div class="text logo-text">
                    <span class="name">Bienvenido</span>
                    <span class="profession">
                        <?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado'; ?>
                    </span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="#" id="scroll-top"><i class='bx bx-home-alt-2 icon'></i><span class="text nav-text">Inicio</span></a>
                    </li>
                    <li class="nav-link">
                        <a href="#" id="scroll-bottom"><i class='bx bx-send-alt-2 icon'></i><span class="text nav-text">Destinos</span></a>
                    </li>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 2): ?>
                    <li class="nav-link">
                        <a href="./administrar_viajes.php"><i class='bx bx-chart-stacked-columns icon'></i><span class="text nav-text">Panel de Control</span></a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-link">
                        <a href="./carrito.php"><i class='bx bx-cart icon'></i><span class="text nav-text">Carrito</span></a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li>
                    <a href="logout.php">
                        <i class='bx bx-user-hexagon icon'></i>
                        <span class="text nav-text"><?php echo isset($_SESSION['nombre']) ? 'Cerrar sesión' : 'Iniciar sesión'; ?></span>
                    </a>
                </li>
            </div>
        </div>
    </nav>

    <section class="home">
        <section class="banner">
            <h1 class="texto" style="text-shadow: #00000071 1px 0 10px;">Encuentra tu proxima aventura</h1>
        </section>

        <section class="acerca_de">
            <h3>Tu pagina de confianza a la hora de viajar por el mundo</h3>
        </section>

        <h2 class="c">Viajes populares</h2>

        <section class="cards">
            <?php
            $sql = "SELECT * FROM viajes";
            $resultado = $conn->query($sql);
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $img_nombre = strtolower(str_replace(' ', '', $row['destino']));
                    $emoji = $row['pais'] == 'AR' ? '&#x1F1E6;&#x1F1F7;' : (
                            $row['pais'] == 'BO' ? '&#x1F1E7;&#x1F1F4;' : (
                            $row['pais'] == 'BR' ? '&#x1F1E7;&#x1F1F7;' : (
                            $row['pais'] == 'CL' ? '&#x1F1E8;&#x1F1F1;' : (
                            $row['pais'] == 'CO' ? '&#x1F1E8;&#x1F1F4;' : (
                            $row['pais'] == 'CR' ? '&#x1F1E8;&#x1F1F7;' : (
                            $row['pais'] == 'CU' ? '&#x1F1E8;&#x1F1FA;' : (
                            $row['pais'] == 'DO' ? '&#x1F1E9;&#x1F1F4;' : (
                            $row['pais'] == 'EC' ? '&#x1F1EA;&#x1F1E8;' : (
                            $row['pais'] == 'SV' ? '&#x1F1F8;&#x1F1FB;' : (
                            $row['pais'] == 'GT' ? '&#x1F1EC;&#x1F1F9;' : (
                            $row['pais'] == 'HN' ? '&#x1F1ED;&#x1F1F3;' : (
                            $row['pais'] == 'MX' ? '&#x1F1F2;&#x1F1FD;' : (
                            $row['pais'] == 'NI' ? '&#x1F1F3;&#x1F1EE;' : (
                            $row['pais'] == 'PA' ? '&#x1F1F5;&#x1F1E6;' : (
                            $row['pais'] == 'PY' ? '&#x1F1F5;&#x1F1FE;' : (
                            $row['pais'] == 'PE' ? '&#x1F1F5;&#x1F1EA;' : (
                            $row['pais'] == 'PR' ? '&#x1F1F7;&#x1F1F8;' : (
                            $row['pais'] == 'UY' ? '&#x1F1FA;&#x1F1FE;' : (
                            $row['pais'] == 'VE' ? '&#x1F1FB;&#x1F1EA;' : ''
                            )))))))))))))))))));
                    echo '<div class="card">
                            <img src="../imgs/' . $img_nombre . '.jpg" alt="Destino" class="card-img">
                            <h3>' . $row['destino'] . '</h3>
                            <p>' . $emoji . '</p>
                            <p>AR$' . number_format($row['precio'], 0, '', '.') . '</p>
                            <button onclick="addToCart(\'' . $row['destino'] . '\', \'' . $emoji . '\',' . $row['precio'] . ',' . $row['id'] . ')">Agregar al carrito</button>
                          </div>';
                }
            } else {
                echo "<p>No hay viajes disponibles.</p>";
            }
            $conn->close();
            ?>
        </section>

        <style>
            div.card > button:hover {
                cursor: pointer;
            }
        </style>

        <div id="toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;"></div>
        <center><footer><p>Secundaria John F. Kennedy N°5 de Lanus</p></footer></center>
    </section>
    <script src="../scripts/script.js"></script>
</body>
</html>
<script>
  document.getElementById("scroll-bottom").addEventListener("click", function(e) {
    e.preventDefault();
    window.scrollTo({
      top: document.body.scrollHeight,
      behavior: "smooth"
    });
  });
</script>
<script>
  document.getElementById("scroll-top").addEventListener("click", function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });
</script>

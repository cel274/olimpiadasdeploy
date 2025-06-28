<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="imgs/logo.png" type="image/png">
  <title>Agencia de Viajes - Carrito</title>
  <style>
    body {
      background-color: #f1f5fc;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 40px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 15px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #261e7e;
      color: white;
    }

    button {
      background-color: #261e7e;
      color: white;
      border: none;
      padding: 10px 15px;
      margin: 5px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1e1863;
    }

    h3 {
      text-align: center;
      margin-top: 20px;
    }

    .acciones {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    @media screen and (max-width: 600px) {
      th, td {
        font-size: 13px;
        padding: 10px;
      }

      button {
        font-size: 13px;
        padding: 8px 12px;
      }
    }
  </style>
</head>
<body>

  <h1>Carrito de Compras</h1>

  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>País</th>
        <th>Subtotal</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="carrito"></tbody>
  </table>

  <h3>Total: $<span id="total">0</span></h3>

  <div class="acciones">
    <button onclick="vaciarCarrito()">Vaciar carrito</button>
    <button onclick="window.location.href='index.php'">Seguir comprando</button>
    <button onclick="prepararFinalizacion()">Finalizar compra</button>
  </div>

  <script>
    function prepararFinalizacion() {
      const carrito = JSON.parse(localStorage.getItem('carrito') || '[]');
      if (carrito.length === 0) {
        alert("El carrito está vacío.");
        return;
      }

      const sesion = <?php echo isset($_SESSION['nombre']) ? 'true' : 'false'; ?>;

      if (!sesion) {
        alert("Tenés que iniciar sesión para poder finalizar la compra.");
        window.location.href = 'loginUI.php';
        return;
      }

      window.location.href = 'finalizar.php';
    }

    function mostrarCarrito() {
      const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      const tbody = document.getElementById('carrito');
      tbody.innerHTML = '';

      let total = 0;
      carrito.forEach((p, index) => {
        const subtotal = p.cantidad * p.precio;
        total += subtotal;
        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td>${p.nombre}</td>
          <td>
            ${p.cantidad}
            <button onclick="cambiarCantidad(${index}, 1)">+</button>
            <button onclick="cambiarCantidad(${index}, -1)">-</button>
          </td>
          <td>${p.pais}</td>
          <td>${subtotal}</td>
          <td><button onclick="eliminarItem(${index})">Eliminar</button></td>
        `;
        tbody.appendChild(fila);
      });
      document.getElementById('total').innerText = total;
    }

    function cambiarCantidad(index, delta) {
      const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito[index].cantidad += delta;
      if (carrito[index].cantidad <= 0) {
        carrito.splice(index, 1);
      }
      localStorage.setItem('carrito', JSON.stringify(carrito));
      mostrarCarrito();
    }

    function eliminarItem(index) {
      const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito.splice(index, 1);
      localStorage.setItem('carrito', JSON.stringify(carrito));
      mostrarCarrito();
    }

    function vaciarCarrito() {
      localStorage.removeItem('carrito');
      mostrarCarrito();
    }

    mostrarCarrito();
  </script>

</body>
</html>

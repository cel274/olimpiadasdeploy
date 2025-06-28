<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require 'vendor/autoload.php';
session_start();
$mysqli = new mysqli("localhost", "root", "", "agvi");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $carrito = $_POST['carrito'] ?? '[]';

    $carritoArray = json_decode($carrito, true);
    $mensaje = "Gracias por tu compra, $nombre $apellido.\n\nResumen del pedido:\n";
    $total = 0;

    foreach ($carritoArray as $item) {
        $precioUnitario = $item['precio'];
        $subtotal = $item['cantidad'] * $precioUnitario;
        $mensaje .= "- {$item['nombre']} ({$item['pais']}): {$item['cantidad']} x \$$precioUnitario = \$$subtotal\n";
        $total += $subtotal;
    }

    $mensaje .= "\nTotal: \$$total";
    if (isset($_SESSION['idUsuario'])) {
        $idUsuario = $_SESSION['idUsuario'];
        foreach ($carritoArray as $item) {
            $idViaje = $item['id'];
            $cantidad = $item['cantidad'];
            $stmt = $mysqli->prepare("INSERT INTO reservas (idUsuario, idViaje, cantidad) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("iii", $idUsuario, $idViaje, $cantidad);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    $qr = new QrCode($mensaje);
    $writer = new PngWriter();
    $result = $writer->write($qr);
    $qrTempPath = sys_get_temp_dir() . '/qr_' . uniqid() . '.png';
    file_put_contents($qrTempPath, $result->getString());

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'agencia7mo4ta@gmail.com';
        $mail->Password = 'ccga cnuu welf ikwp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('agencia7mo4ta@gmail.com', 'Agencia de Viajes 7° 4°');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de compra - Agencia de Viajes 7° 4°';

        $cid = 'qrimagen';
        $mail->AddEmbeddedImage($qrTempPath, $cid, 'qr.png');

        $html = "<h2>Gracias por tu compra, $nombre $apellido</h2>";
        $html .= "<p>Resumen del pedido:</p><ul>";
        foreach ($carritoArray as $item) {
            $precioUnitario = $item['precio'];
            $subtotal = $item['cantidad'] * $precioUnitario;
            $html .= "<li>{$item['nombre']} ({$item['pais']}): {$item['cantidad']} x \$$precioUnitario = \$$subtotal</li>";
        }
        $html .= "</ul>";
        $html .= "<p><strong>Total: \$$total</strong></p>";
        $html .= "<p><img src=\"cid:$cid\" alt=\"Código QR\"></p>";

        $mail->Body = $html;
        $mail->send();

        echo '
        <style>
            body { background-color: #f1f5fc; }
            .confirmacion {
                max-width: 700px; margin: 50px auto; background-color: white;
                padding: 30px; border-radius: 8px; font-family: Arial, sans-serif;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .confirmacion h2 { text-align: center; color: #000; margin-bottom: 10px; }
            .confirmacion p { text-align: center; color: #333; }
            .contenido {
                display: flex; flex-direction: row; gap: 20px; margin-top: 20px;
            }
            .texto { flex: 2; }
            .texto pre {
                background-color: #f5f5f5; padding: 15px; border-radius: 6px;
                overflow-x: auto; font-size: 14px; color: #000;
            }
            .qr {
                flex: 1; display: flex; justify-content: center; align-items: center;
            }
            .qr img {
                max-width: 100%; height: auto; border: 1px solid #ccc; border-radius: 6px;
            }
            .boton-descarga {
                display: flex; justify-content: center; margin-top: 20px;
            }
            .boton-descarga button {
                padding: 10px 20px; background-color: #261e7e; color: white;
                border: none; border-radius: 6px; font-size: 16px; cursor: pointer;
            }
            .boton-descarga button:hover { background-color: rgb(30, 24, 99); }
        </style>
        <link rel="icon" href="imgs/logo.png" type="image/png">
        <title>Compra Finalizada</title>
        <div id="descargar">
            <div class="confirmacion">
                <h2>Compra finalizada</h2>
                <p>Se envió un correo de confirmación a <strong>' . htmlspecialchars($email) . '</strong>.</p>
                <div class="contenido">
                    <div class="texto">
                        <pre>' . htmlspecialchars($mensaje) . '</pre>
                    </div>
                    <div class="qr">
                        <img src="data:image/png;base64,' . base64_encode(file_get_contents($qrTempPath)) . '" alt="Código QR">
                    </div>
                </div>
            </div>
        </div>
        <div class="boton-descarga">
            <button onclick="descargarPDF()">Descargar en formato PDF</button>
        </div>
        <div class="boton-descarga">
            <a href="index.php">
                <button>Volver al inicio</button>
            </a>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function descargarPDF() {
                const element = document.getElementById("descargar");
                const opt = {
                    margin: 0.5,
                    filename: "resumen_compra.pdf",
                    image: { type: "jpeg", quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: "in", format: "letter", orientation: "portrait" }
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>
        ';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    } finally {
        if (file_exists($qrTempPath)) unlink($qrTempPath);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="imgs/logo.png" type="image/png">
    <title>Finalizar compra</title>
    <style>
        body { background-color: #f5f5f5; font-family: Arial, sans-serif; }
        form {
            background-color: white; max-width: 400px; margin: 50px auto;
            padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; align-items: center;
        }
        label { font-weight: bold; width: 100%; }
        input[type="text"], input[type="email"] {
            width: 100%; padding: 10px; margin-top: 6px; margin-bottom: 20px;
            border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            background-color: #261e7e; color: white; border: none; padding: 12px;
            border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px;
        }
        button:hover {
            background-color: rgb(30, 24, 99); transition: .5s;
        }
        button:not(hover){ transition: .5s; }
        h1 { text-align: center; }
    </style>
    <script>
    function cargarCarrito() {
        const carrito = localStorage.getItem('carrito');
        document.getElementById('carritoInput').value = carrito || '[]';
    }
    window.onload = cargarCarrito;
    </script>
</head>
<body>
    <h1>Ingrese sus datos para finalizar la compra</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required>
        <input type="hidden" name="carrito" id="carritoInput">
        <button type="submit">Confirmar compra</button>
        <a href="index.php" style="width: 100%; text-align: center; margin-top: 10px;">
            <button type="button" style="background-color: #261e7e; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px;">
                Volver al inicio
            </button>
        </a>
    </form>
</body>
</html>

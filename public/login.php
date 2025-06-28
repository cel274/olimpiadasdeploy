<?php
session_start(); // <--- IMPORTANTE
require 'agvi_db.php';

if (!isset($_SESSION['nombre'])) {
    header("Location: loginUI.php");
    exit();
}
else{
  header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
session_start();
require_once("header.php");
require_once("functions.php");
?>
<div class="container">
<?php require_once("sidebar.php"); ?>
<div class="mainContent">
    <h1>Parques</h1>
    <div>
    <?php
    echo "1<br>"; // Depuración
    if (!isset($_SESSION['parquesAllNames']) || $_SESSION['parquesAllNames'] == []) {
        echo "2<br>"; // Depuración
        getAllParques();
        echo "3<br>"; // Depuración
    } else {
        echo "Parques ya cargados en la sesión.<br>"; // Depuración
    }
    if (isset($_SESSION['parquesAllNames'])) {
        echo "<pre>";
        print_r($_SESSION['parquesAllNames']); // Depuración
        echo "</pre>";
    } else {
        echo "No hay parques disponibles en la sesión.<br>"; // Depuración
    }
    ?>
    </div>
</div>
</div>
</body>
<script src="./functions.js"></script>
</html>

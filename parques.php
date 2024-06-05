<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>
    <link rel="stylesheet" href="styles.css">
  	<link rel="icon" type="image/png" href="icono.png">
</head>
<body>
<?php
session_start();
require_once("header.php");
require_once("functions.php");
?>
<div class="mainBody">

<div class="mainContent">
    <h1>Parques</h1>
    <div class="contenedorEnlaces">
    <?php
    if (!isset($_SESSION['parquesAllNames']) || $_SESSION['parquesAllNames'] == []) {
        getAllParques();
    }
    if (isset($_SESSION['parquesAllNames'])) {
      	foreach($_SESSION['parquesAllNames'] as $parque){
        	echo "<a href='/$parque' class='enlaceParque'>$parque</a><br>";
        }
    }
    ?>
    </div>
</div>
</div>
</body>
<script src="./functions.js"></script>
</html>

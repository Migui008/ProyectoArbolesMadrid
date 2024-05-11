<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Document</title>
</head>

<body>
  <?php
  session_start();
  require_once("header.php");
  require_once("sidebar.php");
  require_once("functions.php");
  ?>
  <div class="mainsite">

  </div>
  <div class="sections">
    <div class="selectarboles">
      <h2 class="selectTitle">Arboles</h2>
      <?php
      if (!isset($_SESSION['arbolesAllNames'])) {
        getAllArboles();
      }
      $arbolesAllNames = $_SESSION['arbolesAllNames'];
      //Imprimir enlaces
      foreach ($arbolesAllNames as $arbol) {
        echo "<a href='/$arbol.php'>$arbol</a>";
      }
      ?>
    </div>
    <div class="selectparques">
      <h2 class="selectTitle">Parques</h2>
      <?php
      if (!isset($_SESSION['parquesAllNames'])) {
        getAllParques();
      }
      $parquesAllNames = $_SESSION['parquesAllNames'];
      //Imprimir enlaces
      foreach ($parquesAllNames as $parques) {
        echo "<a href='/$parque.php'>$parque</a>";
      }
      ?>
    </div>
  </div>
</body>
<script src="./script.js"></script>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once("functions.php");
    $idParque = "";
    $nombreParque = "";
    if (!isset($_SESSION[$nombreParque])) {
        getInfoParque($idParque);
    }
    $infoParque = $_SESSION[$nombreParque];
    $bus = explode(" ",$infoParque["transporte_bus"]);
    $metro = explode(" ",$infoParque["transporte_metro"]);
    $renfe = explode(" ",$infoParque["transporte_renfe"]);
    ?>
    <title><?= $nombreParque ?></title>
</head>
<body>
<?php
    require_once("header.php");
    require_once("sidebar.php");
    ?>
    <div class="content">

    </div>
    <div class="transportePublico">
        <?php
        if(!empty($bus)){
            echo "<h4>Bus</h4>";
            foreach ($bus as $value) {
                echo "<p>$value</p>";
            }
        }
        if(!empty($metro)){
            echo "<h4>Metro</h4>";
            foreach ($metro as $value) {
                echo "<p>$value</p>";
            }
        }
        if(!empty($renfe)){
            echo "<h4>Bus</h4>";
            foreach ($renfe as $value) {
                echo "<p>$value</p>";
            }
        }
        ?>        
    </div>
    <div class="sideContent">
        <?php
        $imagenParque = "data:image/png;base64,".$infoParque["imagen"];

        echo '<img class="imagenParque" id="imagenParque" src="' . $imagenParque . '" alt="Imagen">';
        ?>
        <div id="map"></div>
        <?php
        echo "<h4>Arboles que encuentras</h4>";
        foreach ($infoParque['arboles_relacionados'] as $arbol) {
            echo "<a href='/$arbol'>$arbol</a>";
        }
        ?>
        <script>
        var latitud = <?= json_encode($infoParque["latitud"]) ?>;
        var longitud = <?= json_encode($infoParque["longitud"]) ?>;
        var nombreParque = <?= json_encode($nombreParque) ?>;
        </script>
    </div>
    <script src="functions.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=CLAVEGOOGLEAPI&callback=initMap" async defer></script>
</body>

</html>

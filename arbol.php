<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once("functions.php");
    $idArbol = "";
    $nombreArbol = "";
    if (!isset($_SESSION[$nombreArbol])) {
        getInfoArbol($idArbol);
    }
    $infoArbol = $_SESSION[$nombreArbol];
    ?>
    <title><?= $nombreArbol ?></title>
</head>

<body>
    <?php
    require_once("header.php");
    require_once("sidebar.php");
    ?>
    <div class="content">

    </div>
    <div class="sideContent">
        <?php
        $imagenArbol = "data:image/png;base64,".$infoArbol["imagen"];

        echo '<img class="imagenArbol" id="imagenArbol" src="' . $imagenArbol . '" alt="Imagen">';
        
        echo "<h4>Nombre científico</h4><p>".$infoArbol["nombre_cientifico"]."</p>";
        echo "<h4>Familia</h4><p>".$infoArbol["familia"]."</p>";
        echo "<h4>Clase</h4><p>".$infoArbol["clase"]."</p>";
        echo "<h4>Parques en los que se encuentra</h4>";
        foreach ($infoArbol['parques_relacionados'] as $parque) {
            echo "<a href='/$parque'>$parque</a>";
        }
        ?>
    </div>
</body>

</html>
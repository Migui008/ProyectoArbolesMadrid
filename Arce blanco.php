<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
  	<link rel="icon" type="image/png" href="icono.png">
    <?php
  	session_start();
    require_once ("functions.php");
    $idArbol = "52";
    $nombreArbol = "Arce blanco";
    if (!isset($_SESSION[$nombreArbol])) {
        getInfoArbol($idArbol);
    }
    $infoArbol = $_SESSION[$nombreArbol];
    ?>
    <title><?= $nombreArbol ?></title>
</head>

<body>
    <?php
    require_once ("header.php");
    ?>
  <div class="mainBody">
    <div class="mainArticleInfo">
        <div class="content">
            <div class="languageSwitcher">
                <form class="langForm" id="langForm" action="" method="post">
                    <input type="hidden" name="lang" value="es">
                    <button type="submit"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEU0lEQVR4nO1ZfUxVZRh/Kr0HuHzcXeBaMbOZ4+u8xhaS9UfDsD/KUCFta7lJc2xtpiXK+gMWFhYrkwxYFHWbzHvl8lXg+BQQ7jVIRYtBpAnnDTAx/KOtWsh//do5I61LznM/zrnXxm97trNz3ufZ7/c+z7O953mJFrGIRfgMEN31YxKt5Yz2cUa1nNGoxGhaEum6YoymuUjfcUYOeY28VvahQONSCsVJIpVIIk1wRvDEZB+J0TtTCXS/7sR/iKcYzuiwxGjOU+L/IeS6HEuOqQt5idEGpSR8JL7ARLomMcrWjDiep3skkSr8TpwtEFIGorv9Sv7yYxTKRfpSc/Lshn0xsYJC/EJe3g05oI7kofQGoxY56z4L4CKV602e32zwD3wlvzFQ5PnNTHjX2BMpZOIiXQkCAVcvJ5PZYwFwCaVwCQgSK/WMfI9xGZzCbBAQh2Iylx7jMvUCnMKBgJN2LRBR7EH5GMYDTtjlbgYJUHEAhHNJ2u2C/ekMxXDTcnzfHKc86yaib8kaNbuff6sAv3ZHYKrVhP6jMThbFY0zH0XgtC0Gk60m/NEdoUMZGfaqECDU3SrAaF0kmh+PwecZ0Tj2lAW2jFhYM8xoSTfjYmOkHlmoUVFCwqi7429NIRj5MEx57ioIhyM/ArWFYagpCIMjLxy9b4Qr36ZsoZjrCNFSwIiaEvrZ3XGgUoB1v4DfG0MxmGNCy3ORaF1vRr99D1x1uzHSuQezfXE4sl/AYJWGApyGaTUlNOfueO1EEgZbd2PGakT1WhNKdiyFbaUFvQ1tOFVQie6jDvxykuGbjtcw3Z6oZQbmvBIwfDAJbbnrcWZHFKxPWFCQvwrVK2Jxwl6PgeJqfF3fjm9LUtC5cx2G3tNQgFOYVdEDhqvujid3rUJHVSHOF4bjkzVReDMnETkZmdj+9Fa02x1oszkwUpWMLmsRenY9pGEGDFe8auJzryfi+EvZOFf+AOpfDIcjKxK56zLxcnom2jeY0Z5pwoWyBLTmbsHZ/KQAN7FTcLg7XjySitPdlRiqXK4IqM2KRN12I5qfjEbzThM6N5kw9nECzjs/xchnj2jZxHY1JbTP3XGs0YJeWxoma8yofSFCEWDbFouWLQ+ic3MUOrLNmKmNRZ89DZcaLNoJOGXI8+ooUVG6CUVvb8NQXTz6341H0yuPwlaRB3tFEbpeTcfQwYeVb/Ka8vc3aifgq6WptxWgiHAJYxrWsZdmkFSRD9rjtEt4687+oRkwWlQLmC+jQwEn7rphh8hT8FSK4iL9dMf+1MvgjJ4NtIBxRlnkC+TJccB2XyTPSyeIRosNfhvyyoNWPUVIjBr8Ntz9VyZEKtNBwGG/j9f/CWk1PaPFBYck0sz4atpMeuBCIkXLk+P56yFfic9KIpXKMUlvTCbSfZzRAS4S94K8xEUqnkimeykYwEVK44z2ckY1kkjD8mT772vW+Wf53TGeTHny2kDzXcQi/i/4CxS7S/c22kG3AAAAAElFTkSuQmCC"
                            alt="Español"></button>
                </form>
                <form class="langForm" id="langForm" action="" method="post">
                    <input type="hidden" name="lang" value="en">
                    <button type="submit"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFOElEQVR4nO2Z+08cVRTHx9dvVmN/0P4PmqiBaXfAzkDFiNLiDHUbVNTEH7BpGm3RRpO2QLSxVBsTK0l/0DStWkiaasDOAKXALrAib1wo4WF5OsOyy8I+2F3Y3Zk95g5LXOgszLLDQg3f5P7Azr3nns/lPs65F8N2tKMdaSKKufUcAJwEgOsCgbfxOnycJ3CvXHT4OPoNfUN1UF1sO+jAocpnSJotJGn2LsVwsCyBwBXLslBdkub6SJo9TenZPQl3PCW7YhfJsJ9RDOtCziyXWAAiipeiuZIM/Z0nE+J8GsNmkQw3s8qJeABALjRrRbY3zfGk/M7HSJr7hmLYkKID8QIwqLAhiua+Q31p6rxef+MRkubKo3esFQC3tD4YrkIzCOQ8RbPX1+tUSwBKhmBvor7jBvD7pW9vG3lDhr7an0iAl49UL9Y0TBbG5TwAHFnu1B8QR86UdHYkAuBMSYc5EJBG5cqLi29u1PndAGCFVbLafO1H8uunNgPg8Af11pEJlwkAQiBJIdfVKyYhdd/QSFJS7Fus/aNjRUGLxQIKkiRw3vh91JCWUyVqAZDOVEm/3Py7SZLAhb4H7t0bs7z2SvdyO16XfD4m5yd1ut08gXtQGOA494URgsGgEohvQRw4WdjaFw/AibOtg15vYFAeGJ/Pay84YRAIPBDZbupgJgpD1J/YPIGfXWHgADng7+rsV4JA/Q4MO+uy8mpnYwHIyrvt7OmzNwKAKA9GfV2rQBKjq9rMuS6XNqHpBADqFjQAPBTk+T+nmTdMkcZ4Apes7+e1iQ6HS3laSTYAkOfvOgChH8uGm0VJmkF/iDabZTpX37iqr5A17+0WcdY+F9HFmFqAFwDAg1p4KitahNR9/ArjqXut7mtXWyG6etYA6AGAv+Raoii5Si818ATuXlGPTBn3GRrMUWw/rwbgEwBAi7cLYtcCABjWAAgAQHO4XqxCg3ZMDUBZRKN2AJhU2UE3AIyoWQMAMAEAHSrtTob9QCpbF4DXJXdEc2CrC6/D21UAyNnT9gQgktdfyGjv374AuPdBB/CoAEge28ZTaFTNGmjfakeF6KVtXYDw1QeEDzMjACjGQWspmgMbVBAAUMjhVrWNAkBBeN8VNtrjGgDjG7UJS4frh2oAXlQeh2DAeeH8HbSQIp2aSieH/N1dgypPYvl7tJPYU1nxh2Lo8tO1dtWhRBhiKRsKa9Hc0yek7+9ftSN4HV+dawZRlKNJ2+xCR/iEXXcK5R03TthnFzqVIKT5effM0Xwjr0sWI9tO65kqFGiqBSiWjfl88/aPj9fdZ+xQVkeQ/4dHdURRmrr0Q79xI/lAyffm9mBQVJyq/r7ewamMtLsR7T9X5XwYYI+Hu9UivKQTVv8758vLWpYnVafZ3pD5Vs18PAnNq7k1XoNJQJuF/z4KFLFeLm3iCdxmIYinsVgkEPjXEdMlNP1Orkmcm5OTFpc70Jv/qWlYy5z46CnTkNsT7FWcVk7nRSxWzaSk7BJ0uCDsl+Pz7nAu7PiVG2tEOexmJPVpOWzoSvlQiyhJkYkMSpSewjaiuYsXsiX/4iLKooZGnMbs92rtibhWOfhurSOcbkroageLRzb7QklB8X+Je6IutiiGg1NfthVjmtyLMlxZogFIhvsZKyp6GNPsZprhKhIGQHO/UZThUUxLIYMUwxVTNCdt8vV6iWYjryT0CEHRrE1rAJLmLGRO1etYIkTpDY9r9cREMqwHjfrezKonsC165DuNHuxiBqBZMxoEZAN70J5Z0w9XP7vV/u5oR/8X/Qsqblqc0YX1FgAAAABJRU5ErkJggg=="
                            alt="English"></button>
                </form>
            </div>
            <?php
            echo "<h1>$nombreArbol</h1>";
            if (!isset($_POST['lang']) || $_POST['lang'] == 'es') {
                foreach ($_SESSION[$nombreArbol]['contenido'] as $contenido) {
                    echo "<h2 class='contentTitle'>" . $contenido['titulo'] . "</h2>";
                    echo "<p class='contentText'>" . $contenido['texto'] . "</p>";
                    echo "<br>";
                }
            } elseif ($_POST['lang'] == 'en') {
                foreach ($_SESSION[$nombreArbol]['contenido'] as $contenido) {
                    echo "<h2 class='contentTitle'>" . $contenido['titulo_en'] . "</h2>";
                    echo "<p class='contentText'>" . $contenido['texto_en'] . "</p>";
                    echo "<br>";
                }
            }
            ?>
        </div>
    </div>
    <div class="sideContent">
        <?php
        $imagenArbol = "data:image/png;base64," . $infoArbol["imagen"];

        echo '<img class="imagenArbol" id="imagenArbol" src="' . $imagenArbol . '" alt="Imagen">';

        if (!isset($_POST['lang']) || $_POST['lang'] == 'es') {
            echo "<h4>Parques en los que se encuentra</h4>";
        } elseif ($_POST['lang'] == 'en') {
            echo "<h4>Parks were it's found</h4>";
        }

        foreach ($infoArbol['parques_relacionados'] as $parque) {
            echo "<a class='enlaceRelaciones' href='/$parque'>$parque</a><br>";
        }

        if (!isset($_POST['lang']) || $_POST['lang'] == 'es') {
            echo "<h4><b>Nombre científico: </b></h4>";
            echo "<p>" . $infoArbol["nombre_cientifico"] . "</p>";

            echo "<h4><b>Familia: </b></h4>";
            echo "<p>" . $infoArbol["familia"] . "</p>";

            echo "<h4><b>Clase: </b></h4>";
            echo "<p>" . $infoArbol["clase"] . "</p>";
        } elseif ($_POST['lang'] == 'en') {
            echo "<h4><b>Scientific name: </b></h4>";
            echo "<p>" . $infoArbol["nombre_cientifico"] . "</p>";

            echo "<h4><b>Family: </b></h4>";
            echo "<p>" . $infoArbol["familia"] . "</p>";

            echo "<h4><b>Class: </b></h4>";
            echo "<p>" . $infoArbol["clase"] . "</p>";
        }

        ?>
    </div>
  </div>
</body>

</html>
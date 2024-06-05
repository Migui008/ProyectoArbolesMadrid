<?php
error_log("Received language change request.");
session_start();
$_SESSION["pasa1"] = "si1";
// Verificar si se recibió el parámetro de idioma
if (isset($_POST["lang"])) {
    // Establecer el idioma en la sesión
    $_SESSION["lang"] = $_POST["lang"];
  	$_SESSION["pasa2"] = "si2";
    // Imprimir el idioma cambiado en el registro del servidor
    error_log("Language changed to: " . $_POST["lang"]);
    // Devolver una respuesta al cliente
    exit("Language changed to: " . $_POST['lang']);
} else {
    // Si no se recibió el parámetro de idioma, imprimir un mensaje de error en el registro del servidor
    error_log("No language parameter received");
    // Devolver una respuesta al cliente
    exit("No language parameter received");
}

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="icono.png">
  <title>Arboles Madrid</title>
</head>

<body>
  <?php
  session_start();
  require_once("header.php");
  require_once("sidebar.php");
  require_once("functions.php");
  ?>
  <div class="indexBody">
  <div class="mainsite">
    <h1 class="selectTitle">Arboles Madrid</h1>
	<img class="banner" src="bannerPagina.png">
    <div class="indexContent">
      <h4 class="indexContentText">Esta página proporciona información sobre los parques históricos y singulares de la comunidad de Madrid, así como sobre los árboles que se encuentran en ellos. Tanto la información como las imágenes de uso libre son extraídas de las siguientes páginas web:</h4>
      <div class="indexContentImages">
        <h4>Imágenes</h4><br>
        <a class="enlacesReferencias" href="https://identify.plantnet.org/">PlantNet</a>
		<br>
		<a class="enlacesReferencias" href="https://www.inaturalist.org/observations?project_id=149718">iNaturalist</a>
		<br>		
		<a class="enlacesReferencias"  href="https://icons8.com/icon/t3NE3BsOAQwq/circular-de-gran-bretaña">Icono Gran Bretaña</a> icon by <a href="https://icons8.com">Icons8</a>
		<br>
		<a class="enlacesReferencias"  href="https://icons8.com/icon/ly7tzANRt33n/españa2-circular">Icono España</a> icon by <a href="https://icons8.com">Icons8</a>
		<br>
        <a class="enlacesReferencias" href="24ai.tech/es/tools/">24AI</a>
		<br>
		<a class="enlacesReferencias" href="https://www.madrid.es/portales/munimadrid/es/Inicio/Medio-ambiente/Parques-y-jardines/?vgnextfmt=default&vgnextchannel=2ba279ed268fe410VgnVCM1000000b205a0aRCRD">Ayuntamiento de Mardid</a>
		<br>
      </div>
      <div class="indexContentBibliografia">
        <h4>Bibliografía</h4><br>
        <p class="parrafosReferencias">'Informe Anual Arbolado parques históricos, singulares y forestales 2023', publicado por el Ayuntamiento de Madrid.</p>

		<p class="parrafosReferencias">Ayuntamiento de Madrid, Departamento de Transporte Público.</p>

		<p class="parrafosReferencias">Ayuntamiento de Madrid, Departamento de Planificación Urbana y Medio Ambiente.</p>

		<p class="parrafosReferencias">Ayuntamiento de Madrid, en colaboración con la Compañía de Ferrocarriles Españoles (RENFE).</p>

		<p class="parrafosReferencias">GBIF.org (2024), Página de Inicio de GBIF. Disponible en: https://www.gbif.org [5 de junio de 2024].</p>
      </div>
    </div>
  </div>
  </div>
  <script src="functions.js"></script>
</body>

</html>
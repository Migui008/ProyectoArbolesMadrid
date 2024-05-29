<?php
require_once("functions.php");
?>

<div class="sidebar">
  <div class="mostvisited">
    <?php
    if(!isset($_SESSION['mostVisited'])){
        mostVisited();
    }
    $mostVisited = $_SESSION['mostVisited'];

    foreach ($mostVisited as $page) {
        echo "<a class='sidebarPage' href='/$page'>$page</a><br>";
    }
    ?>
  </div>
</div>

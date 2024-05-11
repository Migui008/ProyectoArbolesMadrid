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
        echo "<a href='/$page'>$page</a>";
    }
    ?>
  </div>
  <div class="coment">
  
  </div>
</div>
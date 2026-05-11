<?php session_start(); 
include "config.php";
$qr = "SELECT s FROM imagens ORDER BY titulo";
$results = $conn->query($qr);

echo "<div class='galeria-grid'>";

while($row = $results->fetch_array()) {
    echo "<div class='foto-card'>";
        echo "<img src='galeria/".$row["foto"]."' alt='".$row["titulo"]."'>";
        echo "<h4>".$row["titulo"]."</h4>";
    echo "</div>";
}

echo "</div>";

$results->free();


?>

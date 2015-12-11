<?php 
require 'Clothes.php';
$indexName = "WomenClothes";
$objClothes = new Clothes($indexName);

$question = $_GET["question"];

if(strlen($question) > 0) {
  $infoClothes = $objClothes->getInformationClothes($question, 5);
  foreach ($infoClothes as $clothes) {
    echo $clothes["name"];#."  $". $clothes["price"]."  ".$clothes["designer"];
    echo "<br>";
  }
}
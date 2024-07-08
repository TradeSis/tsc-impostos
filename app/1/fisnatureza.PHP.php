<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
$natureza = array();

$sql = "SELECT * FROM fisnatureza";
if (isset($jsonEntrada["idNatureza"])) {
  $sql = $sql . " where fisnatureza.idNatureza = " . $jsonEntrada["idNatureza"];
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($natureza, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idNatureza"]) && $rows==1) {
  $natureza = $natureza[0];
}
$jsonSaida = $natureza;

?>
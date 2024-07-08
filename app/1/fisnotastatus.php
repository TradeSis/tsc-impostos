<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql(null);
$statusnota = array();

$sql = "SELECT fisnotastatus.idStatusNota, fisnotastatus.nomeStatusNota FROM fisnotastatus";
$where = " where ";
if (isset($jsonEntrada["idStatusNota"])) {
  $sql = $sql . $where . " fisnotastatus.idStatusNota = " . $jsonEntrada["idStatusNota"];
  $where = " and ";
}
if (isset($jsonEntrada["nomeStatusNota"])) {
  $sql = $sql . $where . " fisnotastatus.nomeStatusNota like " . "'%" . $jsonEntrada["nomeStatusNota"] . "%'";
  $where = " and ";
}

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($statusnota, $row);
    $rows = $rows + 1;
  }
if (isset($jsonEntrada["idStatusNota"]) && $rows==1) {
  $statusnota = $statusnota[0];
}
$jsonSaida = $statusnota;

?>
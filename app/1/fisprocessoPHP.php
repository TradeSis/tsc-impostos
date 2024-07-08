<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
$processos = array();

$sql = "SELECT * FROM fisprocesso";
if (isset($jsonEntrada["idProcesso"])) {
  $sql = $sql . " where fisprocesso.idProcesso = " . $jsonEntrada["idProcesso"];
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($processos, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idProcesso"]) && $rows==1) {
  $processos = $processos[0];
}
$jsonSaida = $processos;

?>
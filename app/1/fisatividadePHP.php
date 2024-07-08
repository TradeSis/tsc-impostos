<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
$atividades = array();

$sql = "SELECT * FROM fisatividade";
if (isset($jsonEntrada["idAtividade"])) {
  $sql = $sql . " where fisatividade.idAtividade = " . $jsonEntrada["idAtividade"];
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($atividades, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idAtividade"]) && $rows==1) {
  $atividades = $atividades[0];
}
$jsonSaida = $atividades;

?>
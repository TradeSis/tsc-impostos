<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
$cest = array();

$sql = "SELECT fiscest.*, GROUP_CONCAT(fisncmcest.codigoNcm) AS codigoNcm FROM fiscest
        LEFT JOIN fisncmcest ON fiscest.codigoCest = fisncmcest.codigoCest
        LEFT JOIN fisncm ON fisncmcest.codigoNcm = fisncm.codigoNcm";
$where = " WHERE ";
if (isset($jsonEntrada["FiltroTipoCest"]) && $jsonEntrada["FiltroTipoCest"] == 'codigoCest') {
    $sql .= $where . " fiscest.codigoCest = " . $jsonEntrada["dadosCest"];
    $where = " AND ";
}
if (isset($jsonEntrada["FiltroTipoCest"]) && $jsonEntrada["FiltroTipoCest"] == 'codigoNcm') {
    $sql .= $where . " fisncm.codigoNcm = " . $jsonEntrada["dadosCest"];
    $where = " AND ";
}
if (isset($jsonEntrada["FiltroTipoCest"]) && $jsonEntrada["FiltroTipoCest"] == 'nomeCest') {
    $sql .= $where . " fiscest.nomeCest LIKE '%" . $jsonEntrada["dadosCest"] . "%'";
    $where = " AND ";
}

$sql = $sql . " GROUP BY fiscest.codigoCest";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($cest, $row);
  $rows = $rows + 1;
}
/*if (isset($jsonEntrada["codigoCest"]) && $rows==1) {
  $cest = $cest[0];
} */
$jsonSaida = $cest;

?>
<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
$operacao = array();

$sql = "SELECT fisoperacao.*, fisatividade.nomeAtividade, fisnatureza.nomeNatureza, fisprocesso.nomeProcesso FROM fisoperacao
        LEFT JOIN fisatividade ON fisoperacao.idAtividade = fisatividade.idAtividade
        LEFT JOIN fisnatureza ON fisoperacao.idNatureza = fisnatureza.idNatureza
        LEFT JOIN fisprocesso ON fisoperacao.idProcesso = fisprocesso.idProcesso";
if (isset($jsonEntrada["idOperacao"])) {
  $sql = $sql . " where fisoperacao.idOperacao = " . $jsonEntrada["idOperacao"];
} else {
  $where = " where ";
  
  if (isset($jsonEntrada["idAtividade"])) {
    $sql = $sql . $where . " fisoperacao.idAtividade = " . $jsonEntrada["idAtividade"];
    $where = " and ";
  }
  
  if (isset($jsonEntrada["idNatureza"])) {
    $sql = $sql . $where . " fisoperacao.idNatureza = " . $jsonEntrada["idNatureza"];
    $where = " and ";
  }
  
  if (isset($jsonEntrada["idProcesso"])) {
    $sql = $sql . $where . " fisoperacao.idProcesso = " . $jsonEntrada["idProcesso"];
    $where = " and ";
  }
  
  if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'nomeOperacao') {
    $sql = $sql . $where . " fisoperacao.nomeOperacao LIKE '%" . $jsonEntrada["dadosOp"] . "%'";
    $where = " and ";
  }

  if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'idEntSai') {
    $sql = $sql . $where . " fisoperacao.idEntSai = " . $jsonEntrada["idEntSai"];
    $where = " and ";
  }

  if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'xfop') {
    $sql = $sql . $where . " fisoperacao.xfop = " . $jsonEntrada["xfop"];
    $where = " and ";
  }
}
//echo "-SQL->".$sql."\n";

$sql = $sql . " order by idOperacao";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($operacao, $row);
  $rows = $rows + 1;
}
if (isset($jsonEntrada["idOperacao"]) && $rows == 1) {
  $operacao = $operacao[0];
}
$jsonSaida = $operacao;
<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL") {
  $conexao = conectaMysql($idEmpresa);
  $conexaogeral = conectaMysql(null);
}

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset ($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotaproduto";
  if (isset ($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "fisnota_" . date("dmY") . ".log", "a");
    }
  }

}
if (isset ($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$notas = array();

if ($BANCO == "MYSQL") {

  $sql = "SELECT fisnotaproduto.*, produtos.* FROM fisnotaproduto
LEFT JOIN produtos ON fisnotaproduto.idProduto = produtos.idProduto ";
  $where = " where ";
  if (isset ($jsonEntrada["idNota"])) {
    $sql = $sql . $where . " fisnotaproduto.idNota = " . $jsonEntrada["idNota"];
    $where = " and ";
  }
  $rows = 0;
  $buscar = mysqli_query($conexao, $sql);
  while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    $idGeralProduto = $row['idGeralProduto'];

    $sql2 = "SELECT geralprodutos.* FROM geralprodutos WHERE geralprodutos.idGeralProduto = $idGeralProduto";
    $buscar2 = mysqli_query($conexaogeral, $sql2);

    while ($row2 = mysqli_fetch_array($buscar2, MYSQLI_ASSOC)) {
      $mergedRow = array_merge($row, $row2);
      array_push($notas, $mergedRow);
      $rows = $rows + 1;
    }
  }
}

if ($BANCO == "PROGRESS") {

  $progr = new chamaprogress();

  // PASSANDO idEmpresa PARA PROGRESS
  if (isset($jsonEntrada['idEmpresa'])) {
    $progr->setempresa($jsonEntrada['idEmpresa']);
  }
  
  $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduto", json_encode($jsonEntrada));
  fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
  $notas = json_decode($retorno, true);
  if (isset ($notas["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $notas = $notas["conteudoSaida"][0];
  } else {
      $notas = $notas["fisnotaproduto"];
  }

}

$jsonSaida = $notas;


//LOG
if (isset ($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

fclose($arquivo);


?>
<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL")
  $conexao = conectaMysql(null);

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnatureza";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "impostos_" . date("dmY") . ".log", "a");
    }
  }

}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$natureza = array();

if ($BANCO == "MYSQL") {

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

  if (isset($jsonEntrada["idNatureza"]) && $rows == 1) {
    $natureza = $natureza[0];
  }
}

if ($BANCO == "PROGRESS") {

  $progr = new chamaprogress();
  $retorno = $progr->executarprogress("impostos/app/1/fisnatureza", json_encode($jsonEntrada));
  fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
  $natureza = json_decode($retorno, true);
  if (isset($natureza["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $natureza = $natureza["conteudoSaida"][0];
  } else {

    if (!isset($natureza["fisnatureza"][1]) && ($jsonEntrada['idNatureza'] != null)) {  // Verifica se tem mais de 1 registro
      $natureza = $natureza["fisnatureza"][0]; // Retorno sem array
    } else {
      $natureza = $natureza["fisnatureza"];
    }

  }

}


$jsonSaida = $natureza;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

fclose($arquivo);


?>

<?php
// Inicio


?>
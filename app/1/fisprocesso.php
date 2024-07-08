<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL")
  $conexao = conectaMysql(null);

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisprocesso";
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

$processos = array();

if ($BANCO == "MYSQL") {

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

  if (isset($jsonEntrada["idProcesso"]) && $rows == 1) {
    $processos = $processos[0];
  }
}

if ($BANCO == "PROGRESS") {

  $progr = new chamaprogress();
  $retorno = $progr->executarprogress("impostos/app/1/fisprocesso", json_encode($jsonEntrada));
  fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
  $processos = json_decode($retorno, true);
  if (isset($processos["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $processos = $processos["conteudoSaida"][0];
  } else {

    if (!isset($processos["fisprocesso"][1]) && ($jsonEntrada['idProcesso'] != null)) {  // Verifica se tem mais de 1 registro
      $processos = $processos["fisprocesso"][0]; // Retorno sem array
    } else {
      $processos = $processos["fisprocesso"];
    }

  }

}


$jsonSaida = $processos;


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
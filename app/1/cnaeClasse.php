<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "cnaeClasse";
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



$cnaeClasse = array();

$progr = new chamaprogress();

if (isset($jsonEntrada['cnaeID'])) {
  $cnaeID = $jsonEntrada['cnaeID'];
  
  if (strlen($cnaeID) > 5) {
      $jsonEntrada['cnaeID'] = substr($cnaeID, 0, 5);
  }
} 

$retorno = $progr->executarprogress("impostos/app/1/cnaeClasse",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$cnaeClasse = json_decode($retorno,true);
if (isset($cnaeClasse["cnaeClasse"][0])) { // Conteudo Saida - Caso de erro
  $cnaeClasse = $cnaeClasse["cnaeClasse"][0];
} else {

  $cnaeClasse = array(
    "status" => 400,
    "retorno" => "Erro na saida"
  );
}
$jsonSaida = $cnaeClasse;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n");
  }
}
//LOG

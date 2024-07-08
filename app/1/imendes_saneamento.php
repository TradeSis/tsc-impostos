<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendes";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendes_Saneamento_" . date("dmY") . ".log", "a");
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



$operacao = array();

$progr = new chamaprogress();

// PASSANDO idEmpresa PARA PROGRESS
if (isset($jsonEntrada['idEmpresa'])) {
  $progr->setempresa($jsonEntrada['idEmpresa']);
}

$retorno = $progr->executarprogress("impostos/app/1/imendes_saneamento",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$operacao = json_decode($retorno,true);
if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
  $operacao = $operacao["conteudoSaida"][0];
} else {

  $operacao = array(
    "status" => 400,
    "retorno" => "Erro na saida"
  );
}
$jsonSaida = $operacao;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n");
  }
}
//LOG

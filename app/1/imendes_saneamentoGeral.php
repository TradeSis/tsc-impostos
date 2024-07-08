<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendesGeral";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendes_SaneamentoGeral_" . date("dmY") . ".log", "a");
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



$dados = array();

$progr = new chamaprogress();
$retorno = $progr->executarprogress("impostos/app/1/imendes_saneamentoGeral",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$dados = json_decode($retorno,true);
if (isset($dados["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
  $dados = $dados["conteudoSaida"][0];
}else{
    $operacao = array(
        "status" => 400,
        "retorno" => "Erro na saida"
      );
}

$jsonSaida = $dados;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n");
  }
}
//LOG

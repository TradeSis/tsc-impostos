<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnota_processargeral";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_processargeral_" . date("dmY") . ".log", "a");
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

$processar = array();

$progr = new chamaprogress();
 // PASSANDO idEmpresa PARA PROGRESS
 if (isset($jsonEntrada['idEmpresa'])) {
    $progr->setempresa($jsonEntrada['idEmpresa']);
}
$retorno = $progr->executarprogress("impostos/app/1/fisnota_processargeral", json_encode($jsonEntrada));
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$processar = json_decode($retorno, true);
if (isset($processar["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $processar = $processar["conteudoSaida"][0];
  } else {
  
    $processar = array(
      "status" => 200,
      "retorno" => "Notas Processadas"
    );
  }

$jsonSaida = $processar;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

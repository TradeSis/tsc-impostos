<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotaproduto-imp";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_" . date("dmY") . ".log", "a");
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

$impostos = array();

$progr = new chamaprogress();

// PASSANDO idEmpresa PARA PROGRESS
if (isset($jsonEntrada['idEmpresa'])) {
    $progr->setempresa($jsonEntrada['idEmpresa']);
}

$retorno = $progr->executarprogress("impostos/app/1/fisnotaproduto-imp", json_encode($jsonEntrada));
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$impostos = json_decode($retorno, true);
if (isset($impostos["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $impostos = $impostos["conteudoSaida"][0];
} else {
    $impostos = $impostos["fisnotaprodutogeral"];
}


$jsonSaida = $impostos;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

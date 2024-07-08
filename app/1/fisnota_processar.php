<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset ($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_processar";
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


// Pega XML puro
if (isset ($jsonEntrada['idNota'])) {

    $progr = new chamaprogress();

    // PASSANDO idEmpresa PARA PROGRESS
    if (isset($jsonEntrada['idEmpresa'])) {
        $progr->setempresa($jsonEntrada['idEmpresa']);
    }
    
    $retorno = $progr->executarprogress("impostos/app/1/fisnota_processar", json_encode($jsonEntrada));
    fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
    $operacao = json_decode($retorno, true);
    if (isset ($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
        $operacao = $operacao["conteudoSaida"][0];
        $jsonSaida = array(
            "status" => 200,
            "retorno" => "NFE Processada"
        );
    } else {
        /* $jsonSaida = array(
            "status" => 200,
            "retorno" => "NFE Processada"
        ); */
    }

} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
}

//LOG
if (isset ($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
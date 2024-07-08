<?php
// gabriel 30042024 criado
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "cnae";
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

$cnae = array();


$progr = new chamaprogress();
$retorno = $progr->executarprogress("impostos/app/1/cnaeSecao", json_encode($jsonEntrada));
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$cnae = json_decode($retorno, true);
if (isset($cnae["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $cnae = $cnae["conteudoSaida"][0];
} else {

    if (!isset($cnae["cnaeSecao"][1]) && ($jsonEntrada['dadosEntrada'][0]['idcnSecao'] != null)) {  // Verifica se tem mais de 1 registro
        $cnae = $cnae["cnaeSecao"][0]; // Retorno sem array
    } else {
        $cnae = $cnae["cnaeSecao"];
    }

}


$jsonSaida = $cnae;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

?>
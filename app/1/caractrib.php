<?php
// gabriel 30042024 criado
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "caracTrib";
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

$caracTrib = array();


$progr = new chamaprogress();
$retorno = $progr->executarprogress("impostos/app/1/caracTrib", json_encode($jsonEntrada));
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$caracTrib = json_decode($retorno, true);
if (isset($caracTrib["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $caracTrib = $caracTrib["conteudoSaida"][0];
} else {

    if (!isset($caracTrib["caracTrib"][1]) && ($jsonEntrada['dadosEntrada'][0]['caracTrib'] != null)) {  // Verifica se tem mais de 1 registro
        $caracTrib = $caracTrib["caracTrib"][0]; // Retorno sem array
    } else {
        $caracTrib = $caracTrib["caracTrib"];
    }

}


$jsonSaida = $caracTrib;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

?>
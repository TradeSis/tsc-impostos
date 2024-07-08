<?php
// Lucas 11012024 criacao

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "apifiscalhistorico";
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


$operacao = array();

$progr = new chamaprogress();

// PASSANDO idEmpresa PARA PROGRESS
if (isset($jsonEntrada['idEmpresa'])) {
    $progr->setempresa($jsonEntrada['idEmpresa']);
}

$retorno = $progr->executarprogress("impostos/app/1/fishistorico",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$operacao = json_decode($retorno,true);
if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $operacao = $operacao["conteudoSaida"][0];
} else {
  
   if (!isset($operacao["apifiscalhistorico"][1]) && ($jsonEntrada['idHistorico'] != null)) {  // Verifica se tem mais de 1 registro
    $operacao = $operacao["apifiscalhistorico"][0]; // Retorno sem array
  } else {
    $operacao = $operacao["apifiscalhistorico"]; 
  }

}

$jsonSaida = $operacao;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);


?>
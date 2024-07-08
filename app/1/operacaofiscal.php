<?php
// Lucas 11012024 criacao

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "operacao fiscal";
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
$retorno = $progr->executarprogress("impostos/app/1/operacaofiscal",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$operacao = json_decode($retorno,true);
if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $operacao = $operacao["conteudoSaida"][0];
} else {
  
   if (!isset($operacao["fiscaloperacao"][1]) && ($jsonEntrada['idoperacaofiscal'] != null && $jsonEntrada['idGrupo'] != null)) {  // Verifica se tem mais de 1 registro
    $operacao = $operacao["fiscaloperacao"][0]; // Retorno sem array
  }else {
    $operacao = $operacao["fiscaloperacao"];  
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
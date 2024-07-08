<?php
// Lucas 11012024 criacao

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "regra fiscal";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "regra_fiscal_" . date("dmY") . ".log", "a");
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
$retorno = $progr->executarprogress("impostos/app/1/regrafiscal",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$operacao = json_decode($retorno,true);
if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $operacao = $operacao["conteudoSaida"][0];
} else {
  
   if (!isset($operacao["fiscalregra"][1]) && ($jsonEntrada['idRegra'] != null) && ($jsonEntrada['codRegra'] != null)) {  // Verifica se tem mais de 1 registro
    $operacao = $operacao["fiscalregra"][0]; // Retorno sem array
  } elseif($jsonEntrada['idRegra'] != null){
    $operacao = $operacao["fiscalregra"][0];
  } else {
    $operacao = $operacao["fiscalregra"];  
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
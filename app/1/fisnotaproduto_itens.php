<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset ($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotaproduto_itens";
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
    fwrite($arquivo, $identificacao . "-ENTRADA->" . $jsonEntrada["itemnota"] . "\n");
  }
}
//LOG

$notas = array();


  $progr = new chamaprogress();

  // PASSANDO idEmpresa PARA PROGRESS
  if (isset($jsonEntrada['idEmpresa'])) {
    $progr->setempresa($jsonEntrada['idEmpresa']);
  }
  
  $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduto_itens", $jsonEntrada["itemnota"]);
  fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
  $notas = json_decode($retorno, true);
  if (isset ($notas["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $notas = $notas["conteudoSaida"][0];
  } else {
      $notas = $notas["fisnotaprodutos"];
  }



$jsonSaida = $notas;


//LOG
if (isset ($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

fclose($arquivo);


?>
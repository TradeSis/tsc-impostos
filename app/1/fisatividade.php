<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL")
  $conexao = conectaMysql(null);

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisatividade";
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

$atividades = array();

if ($BANCO == "MYSQL") {

  $sql = "SELECT * FROM fisatividade";
  if (isset($jsonEntrada["idAtividade"])) {
    $sql = $sql . " where fisatividade.idAtividade = " . $jsonEntrada["idAtividade"];
  }
  $rows = 0;
  $buscar = mysqli_query($conexao, $sql);
  while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($atividades, $row);
    $rows = $rows + 1;
  }

  if (isset($jsonEntrada["idAtividade"]) && $rows == 1) {
    $atividades = $atividades[0];
  }
}

if ($BANCO == "PROGRESS") {

  $progr = new chamaprogress();
  $retorno = $progr->executarprogress("impostos/app/1/fisatividade", json_encode($jsonEntrada));
  fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
  $atividades = json_decode($retorno, true);
  if (isset($atividades["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $atividades = $atividades["conteudoSaida"][0];
  } else {

    if (!isset($atividades["fisatividade"][1]) && ($jsonEntrada['idAtividade'] != null)) {  // Verifica se tem mais de 1 registro
      $atividades = $atividades["fisatividade"][0]; // Retorno sem array
    } else {
      $atividades = $atividades["fisatividade"];
    }

  }

}


$jsonSaida = $atividades;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

fclose($arquivo);


?>

<?php
// Inicio


?>
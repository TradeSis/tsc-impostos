<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL") {
  $conexao = conectaMysql($idEmpresa);
  $conexaogeral = conectaMysql(null);
}

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotatotal";
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

$notas = array();

if ($BANCO == "MYSQL") {
  $sql = "SELECT * FROM fisnotatotal ";
  $where = " where ";
  if (isset($jsonEntrada["idNota"])) {
    $sql = $sql . $where . " fisnotatotal.idNota = " . $jsonEntrada["idNota"];
    $where = " and ";
  }
  $rows = 0;
  $buscar = mysqli_query($conexao, $sql);
  while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($notas, $row);
    $rows = $rows + 1;
  }
  
  if (isset($jsonEntrada["idNota"]) && $rows==1) {
    $notas = $notas[0];
  }
}

if ($BANCO == "PROGRESS") {

    $progr = new chamaprogress();

    // PASSANDO idEmpresa PARA PROGRESS
    if (isset($jsonEntrada['idEmpresa'])) {
        $progr->setempresa($jsonEntrada['idEmpresa']);
    }
    
    $retorno = $progr->executarprogress("impostos/app/1/fisnotatotal", json_encode($jsonEntrada));
    fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
    $notas = json_decode($retorno, true);
    if (isset($notas["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
        $notas = $notas["conteudoSaida"][0];
    } else {

        if (!isset($notas["fisnotatotal"][1]) && ($jsonEntrada['idNota'] != null)) {  // Verifica se tem mais de 1 registro
            $notas = $notas["fisnotatotal"][0]; // Retorno sem array
        
        } 
        else {
            $notas = $notas["fisnotatotal"];
        }
        

    }

}

$jsonSaida = $notas;


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
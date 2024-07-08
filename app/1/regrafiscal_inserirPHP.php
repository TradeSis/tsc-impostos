<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "regrafiscal_inserir";
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

$conexao = conectaMysql(null);

$codRegra = isset($jsonEntrada['codRegra']) && $jsonEntrada['codRegra'] !== "" ? "'" . $jsonEntrada['codRegra'] . "'" : "NULL";
$codExcecao  = isset($jsonEntrada['codExcecao']) && $jsonEntrada['codExcecao'] !== "" ? "'" . $jsonEntrada['codExcecao'] . "'" : "NULL";
$dtVigIni = isset($jsonEntrada['dtVigIni']) && $jsonEntrada['dtVigIni'] !== "" ? "'" . $jsonEntrada['dtVigIni'] . "'" : "NULL";
$dtVigFin = isset($jsonEntrada['dtVigFin']) && $jsonEntrada['dtVigFin'] !== "" ? "'" . $jsonEntrada['dtVigFin'] . "'" : "NULL";
$cFOPCaracTrib = isset($jsonEntrada['cFOPCaracTrib']) && $jsonEntrada['cFOPCaracTrib'] !== "" ? "'" . $jsonEntrada['cFOPCaracTrib'] . "'" : "NULL";
$cST = isset($jsonEntrada['cST']) && $jsonEntrada['cST'] !== "" ? "'" . $jsonEntrada['cST'] . "'" : "NULL";
$cSOSN = isset($jsonEntrada['cSOSN']) && $jsonEntrada['cSOSN'] !== "" ? "'" . $jsonEntrada['cSOSN'] . "'" : "NULL";
$aliqIcmsInterna = isset($jsonEntrada['aliqIcmsInterna']) && $jsonEntrada['aliqIcmsInterna'] !== "" ? "'" . $jsonEntrada['aliqIcmsInterna'] . "'" : "NULL";
$aliqIcmsInterestadual = isset($jsonEntrada['aliqIcmsInterestadual']) && $jsonEntrada['aliqIcmsInterestadual'] !== "" ? "'" . $jsonEntrada['aliqIcmsInterestadual'] . "'" : "NULL";
$reducaoBcIcms = isset($jsonEntrada['reducaoBcIcms']) && $jsonEntrada['reducaoBcIcms'] !== "" ? "'" . $jsonEntrada['reducaoBcIcms'] . "'" : "NULL";
$reducaoBcIcmsSt = isset($jsonEntrada['reducaoBcIcmsSt']) && $jsonEntrada['reducaoBcIcmsSt'] !== "" ? "'" . $jsonEntrada['reducaoBcIcmsSt'] . "'" : "NULL";
$redBcICMsInterestadual = isset($jsonEntrada['redBcICMsInterestadual']) && $jsonEntrada['redBcICMsInterestadual'] !== "" ? "'" . $jsonEntrada['redBcICMsInterestadual'] . "'" : "NULL";
$aliqIcmsSt = isset($jsonEntrada['aliqIcmsSt']) && $jsonEntrada['aliqIcmsSt'] !== "" ? "'" . $jsonEntrada['aliqIcmsSt'] . "'" : "NULL";
$iVA = isset($jsonEntrada['iVA']) && $jsonEntrada['iVA'] !== "" ? "'" . $jsonEntrada['iVA'] . "'" : "NULL";
$iVAAjust = isset($jsonEntrada['iVAAjust']) && $jsonEntrada['iVAAjust'] !== "" ? "'" . $jsonEntrada['iVAAjust'] . "'" : "NULL";
$fCP = isset($jsonEntrada['fCP']) && $jsonEntrada['fCP'] !== "" ? "'" . $jsonEntrada['fCP'] . "'" : "NULL";
$codBenef = isset($jsonEntrada['codBenef']) && $jsonEntrada['codBenef'] !== "" ? "'" . $jsonEntrada['codBenef'] . "'" : "NULL";
$pDifer = isset($jsonEntrada['pDifer']) && $jsonEntrada['pDifer'] !== "" ? "'" . $jsonEntrada['pDifer'] . "'" : "NULL";
$pIsencao = isset($jsonEntrada['pIsencao']) && $jsonEntrada['pIsencao'] !== "" ? "'" . $jsonEntrada['pIsencao'] . "'" : "NULL";
$antecipado = isset($jsonEntrada['antecipado']) && $jsonEntrada['antecipado'] !== "" ? "'" . $jsonEntrada['antecipado'] . "'" : "'N'";
$desonerado = isset($jsonEntrada['desonerado']) && $jsonEntrada['desonerado'] !== "" ? "'" . $jsonEntrada['desonerado'] . "'" : "'N'";
$pICMSDeson = isset($jsonEntrada['pICMSDeson']) && $jsonEntrada['pICMSDeson'] !== "" ? "'" . $jsonEntrada['pICMSDeson'] . "'" : "NULL";
$isento = isset($jsonEntrada['isento']) && $jsonEntrada['isento'] !== "" ? "'" . $jsonEntrada['isento'] . "'" : "'N'";
$tpCalcDifal = isset($jsonEntrada['tpCalcDifal']) && $jsonEntrada['tpCalcDifal'] !== "" ? "'" . $jsonEntrada['tpCalcDifal'] . "'" : "NULL";
$ampLegal = isset($jsonEntrada['ampLegal']) && $jsonEntrada['ampLegal'] !== "" ? "'" . $jsonEntrada['ampLegal'] . "'" : "NULL";
$Protocolo = isset($jsonEntrada['Protocolo']) && $jsonEntrada['Protocolo'] !== "" ? "'" . $jsonEntrada['Protocolo'] . "'" : "NULL";
$Convenio = isset($jsonEntrada['Convenio']) && $jsonEntrada['Convenio'] !== "" ? "'" . $jsonEntrada['Convenio'] . "'" : "NULL";
$regraGeral = isset($jsonEntrada['regraGeral']) && $jsonEntrada['regraGeral'] !== "" ? "'" . $jsonEntrada['regraGeral'] . "'" : "NULL";


$sql = " INSERT INTO fiscalregra (codRegra, codExcecao, dtVigIni, dtVigFin, cFOPCaracTrib, cST, cSOSN, aliqIcmsInterna, aliqIcmsInterestadual, reducaoBcIcms, reducaoBcIcmsSt,
    redBcICMsInterestadual, aliqIcmsSt, iVA, iVAAjust, fCP, codBenef, pDifer, pIsencao, antecipado, desonerado, pICMSDeson, isento, tpCalcDifal, ampLegal, Protocolo, Convenio, regraGeral) 
    VALUES ($codRegra, $codExcecao, $dtVigIni, $dtVigFin, $cFOPCaracTrib, $cST, $cSOSN, $aliqIcmsInterna, $aliqIcmsInterestadual, $reducaoBcIcms, $reducaoBcIcmsSt,
    $redBcICMsInterestadual, $aliqIcmsSt, $iVA, $iVAAjust, $fCP, $codBenef, $pDifer, $pIsencao, $antecipado, $desonerado, $pICMSDeson, $isento, $tpCalcDifal, $ampLegal, $Protocolo, $Convenio, $regraGeral) ";


//echo $sql;

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 3) {
        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
    }
}
//LOG

//TRY-CATCH
try {

    $atualizar = mysqli_query($conexao, $sql);
    $idRegra = mysqli_insert_id($conexao);
    if (!$atualizar)
        throw new Exception(mysqli_error($conexao));

    $jsonSaida = array(
        "status" => 200,
        "retorno" => "ok",
        "idRegra" => $idRegra
    );
} catch (Exception $e) {
    $jsonSaida = array(
        "status" => 500,
        "retorno" => $e->getMessage()
    );
    if ($LOG_NIVEL >= 1) {
        fwrite($arquivo, $identificacao . "-ERRO->" . $e->getMessage() . "\n");
    }
} finally {
    // ACAO EM CASO DE ERRO (CATCH), que mesmo assim precise
}
//TRY-CATCH


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

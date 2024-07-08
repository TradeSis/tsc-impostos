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


$conexao = conectaMysql(null);

$regra = array();

$sql = "SELECT * ,'' AS dtVigIniFormatada ,'' AS dtVigFinFormatada FROM fiscalregra  ";

if (isset($jsonEntrada["idRegra"])) {
    $sql = $sql . " where fiscalregra.idRegra = " . "'" . $jsonEntrada["idRegra"] . "'";
}
if (isset($jsonEntrada["codRegra"])) {
    $sql = $sql . " where fiscalregra.codRegra = " . "'" . $jsonEntrada["codRegra"] . "'";
}
$where = " where ";
if (isset($jsonEntrada["codigo"])) {
    $sql = $sql . $where . " fiscalregra.codRegra IS NOT NULL ";
    $where = " and ";
}


//echo "-SQL->" . $sql . "\n";
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 3) {
        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
    }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($regra, $row);

    if (isset($regra[$rows]["dtVigIni"])) {
        $dtVigIniFormatada = date('d/m/Y', strtotime($regra[$rows]["dtVigIni"]));
        $regra[$rows]["dtVigIniFormatada"] = $dtVigIniFormatada;
    }
    if (isset($regra[$rows]["dtVigFin"])) {
        $dtVigFinFormatada = date('d/m/Y', strtotime($regra[$rows]["dtVigFin"]));
        $regra[$rows]["dtVigFinFormatada"] = $dtVigFinFormatada;
    }

    $rows = $rows + 1;
}


if (isset($jsonEntrada["idRegra"]) && $rows == 1) {
    $regra = $regra[0];
}
if (isset($jsonEntrada["codRegra"]) && $rows == 1) {
    $regra = $regra[0];
}
$jsonSaida = $regra;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
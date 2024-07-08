<?php
// Lucas 11012024 criacao

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "operacao fiscal";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "operacao_fiscal_" . date("dmY") . ".log", "a");
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

$operacao = array();

$sql = "SELECT * FROM fiscaloperacao  ";

if (isset($jsonEntrada["idoperacaofiscal"])) {
    $sql = $sql . " where fiscaloperacao.idoperacaofiscal = " . "'" . $jsonEntrada["idoperacaofiscal"] . "'" ;
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
    array_push($operacao, $row);
    $rows = $rows + 1;
}


if (isset($jsonEntrada["idoperacaofiscal"]) && $rows == 1) {
    $operacao = $operacao[0];
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
<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "opercaofiscal_inserir";
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

if (isset($jsonEntrada['idGrupo'])) {
    $idGrupo = isset($jsonEntrada['idGrupo']) && $jsonEntrada['idGrupo'] !== "" ? "'" . $jsonEntrada['idGrupo'] . "'" : "NULL";
    $codigoEstado = isset($jsonEntrada['codigoEstado']) && $jsonEntrada['codigoEstado'] !== "" ? "'" . $jsonEntrada['codigoEstado'] . "'" : "NULL";
    $cFOP = isset($jsonEntrada['cFOP']) && $jsonEntrada['cFOP'] !== "" ? "'" . $jsonEntrada['cFOP'] . "'" : "NULL";
    $codigoCaracTrib = isset($jsonEntrada['codigoCaracTrib']) && $jsonEntrada['codigoCaracTrib'] !== "" ? "'" . $jsonEntrada['codigoCaracTrib'] . "'" : "NULL";
    $finalidade = isset($jsonEntrada['finalidade']) && $jsonEntrada['finalidade'] !== "" ? "'" . $jsonEntrada['finalidade'] . "'" : "NULL";
    $idRegra = isset($jsonEntrada['idRegra']) && $jsonEntrada['idRegra'] !== "" ? "'" . $jsonEntrada['idRegra'] . "'" : "NULL";


    $sql = " INSERT INTO fiscaloperacao (idGrupo, codigoEstado, cFOP, codigoCaracTrib, finalidade, idRegra) 
    VALUES ($idGrupo, $codigoEstado, $cFOP, $codigoCaracTrib, $finalidade, $idRegra) ";


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
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));

        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
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

} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

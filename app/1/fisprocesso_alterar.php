<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
if (isset($jsonEntrada['idProcesso'])) {
    $idProcesso = $jsonEntrada['idProcesso'];
    $nomeProcesso = $jsonEntrada['nomeProcesso'];
    $sql = "UPDATE fisprocesso SET nomeProcesso='$nomeProcesso' WHERE idProcesso = $idProcesso";
    if ($atualizar = mysqli_query($conexao, $sql)) {
        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
        );
    } else {
        $jsonSaida = array(
            "status" => 500,
            "retorno" => "erro no mysql"
        );
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );

}

?>
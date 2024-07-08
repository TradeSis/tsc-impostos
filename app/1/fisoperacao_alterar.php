<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
if (isset($jsonEntrada['idOperacao'])) {
    $idOperacao = $jsonEntrada['idOperacao'];
    $nomeOperacao = $jsonEntrada['nomeOperacao'];
    $idAtividade = $jsonEntrada['idAtividade'];
    $idProcesso = $jsonEntrada['idProcesso'];
    $idNatureza = $jsonEntrada['idNatureza'];
    $idGrupoOper = $jsonEntrada['idGrupoOper'];
    $idEntSai = $jsonEntrada['idEntSai'];
    $xfop = $jsonEntrada['xfop'];

    $sql = "UPDATE fisoperacao SET nomeOperacao='$nomeOperacao', idAtividade='$idAtividade', idProcesso='$idProcesso', idNatureza='$idNatureza', idGrupoOper='$idGrupoOper', idEntSai='$idEntSai', xfop='$xfop' WHERE idOperacao = $idOperacao";
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
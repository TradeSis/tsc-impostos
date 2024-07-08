<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$conexao = conectaMysql();
if (isset($jsonEntrada['nomeOperacao'])) {
    $nomeOperacao = $jsonEntrada['nomeOperacao'];
    $idAtividade = $jsonEntrada['idAtividade'];
    $idProcesso = $jsonEntrada['idProcesso'];
    $idNatureza = $jsonEntrada['idNatureza'];
    $idGrupoOper = $jsonEntrada['idGrupoOper'];
    $idEntSai = $jsonEntrada['idEntSai'];
    $xfop = $jsonEntrada['xfop'];

    $sql = "INSERT INTO fisoperacao (`nomeOperacao`, `idAtividade`, `idProcesso`, `idGrupoOper`, `idNatureza`, `idEntSai`, `xfop`) VALUES ('$nomeOperacao','$idAtividade','$idProcesso','$idGrupoOper','$idNatureza','$idEntSai','$xfop')";
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
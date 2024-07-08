<?php
$conexao = conectaMysql();
$ncm = array();
$ncmIds = array();

function Desce($conexao, $superior, &$ncm, &$ncmIds)
{
    $sql = "SELECT fisncm.*, GROUP_CONCAT(fiscest.codigoCest) AS codigoCest FROM `fisncm`
            LEFT JOIN fisncmcest ON fisncm.codigoNcm = fisncmcest.codigoNcm
            LEFT JOIN fiscest ON fisncmcest.codigoCest = fiscest.codigoCest 
            WHERE fisncm.superior LIKE '" . $superior . "%' GROUP BY fisncm.codigoNcm";
    $buscar = mysqli_query($conexao, $sql);

    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
        $linhaNcm = [
            "codigoNcm" => $row["codigoNcm"],
            "Descricao" => $row["Descricao"],
            "superior" => $row["superior"],
            "nivel" => $row["nivel"],
            "ultimonivel" => $row["ultimonivel"],
            "ncm" => $row["ncm"],
            "codigoCest" => $row["codigoCest"],
            "pesquisado" => false
        ];

        if (!in_array($row["codigoNcm"], $ncmIds)) {
            array_push($ncm, $linhaNcm);
            array_push($ncmIds, $row["codigoNcm"]);
        }

        Desce($conexao, $row["codigoNcm"], $ncm, $ncmIds);
    }
}

function Sobe($conexao, $superior, &$ncm, &$ncmIds)
{
    $sql = "SELECT fisncm.*, GROUP_CONCAT(fiscest.codigoCest) AS codigoCest FROM `fisncm`
            LEFT JOIN fisncmcest ON fisncm.codigoNcm = fisncmcest.codigoNcm
            LEFT JOIN fiscest ON fisncmcest.codigoCest = fiscest.codigoCest 
            WHERE fisncm.codigoNcm = $superior GROUP BY fisncm.codigoNcm";
    $buscar = mysqli_query($conexao, $sql);

    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
        $linhaNcm = [
            "codigoNcm" => $row["codigoNcm"],
            "Descricao" => $row["Descricao"],
            "superior" => $row["superior"],
            "nivel" => $row["nivel"],
            "ultimonivel" => $row["ultimonivel"],
            "ncm" => $row["ncm"],
            "codigoCest" => $row["codigoCest"],
            "pesquisado" => false
        ];

        if (!in_array($row["codigoNcm"], $ncmIds)) {
            array_push($ncm, $linhaNcm);
            array_push($ncmIds, $row["codigoNcm"]);
        }

        if ($row["nivel"] != 1 || $row["ultimonivel"] != 0) {
            Sobe($conexao, $row["superior"], $ncm, $ncmIds);
        }
    }
}

//**********SQL QUERY **********/
$sql = "SELECT fisncm.*, GROUP_CONCAT(fiscest.codigoCest) AS codigoCest FROM `fisncm`
        LEFT JOIN fisncmcest ON fisncm.codigoNcm = fisncmcest.codigoNcm
        LEFT JOIN fiscest ON fisncmcest.codigoCest = fiscest.codigoCest";
$where = " WHERE ";
if (isset($jsonEntrada["FiltroTipoNcm"]) && $jsonEntrada["FiltroTipoNcm"] == 'codigoNcm') {
    $sql .= $where . " fisncm.codigoNcm = " . $jsonEntrada["dadosNcm"];
    $where = " AND ";
}
if (isset($jsonEntrada["FiltroTipoNcm"]) && $jsonEntrada["FiltroTipoNcm"] == 'Descricao') {
    $sql .= $where . " fisncm.Descricao LIKE '%" . $jsonEntrada["dadosNcm"] . "%'";
    $where = " AND ";
}

$sql = $sql . " GROUP BY fisncm.codigoNcm";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);

while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    $linhaNcm = [
        "codigoNcm" => $row["codigoNcm"],
        "Descricao" => $row["Descricao"],
        "superior" => $row["superior"],
        "nivel" => $row["nivel"],
        "ultimonivel" => $row["ultimonivel"],
        "ncm" => $row["ncm"],
        "codigoCest" => $row["codigoCest"],
        "pesquisado" => true
    ];

    if ($row["nivel"] != 1 || $row["ultimonivel"] != 0) {
        Sobe($conexao, $row["superior"], $ncm, $ncmIds);
    }

    if ($row["nivel"] < 6) {
        Desce($conexao, $row["codigoNcm"], $ncm, $ncmIds);
    }


    if (!in_array($row["codigoNcm"], $ncmIds)) {
        array_push($ncm, $linhaNcm);
        array_push($ncmIds, $row["codigoNcm"]);
    }
    $rows++;
}

$jsonSaida = $ncm;
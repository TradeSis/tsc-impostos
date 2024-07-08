<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL") {
    $conexao = conectaMysql($idEmpresa);
    $conexaogeral = conectaMysql(null);
}

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset ($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotaproduicms";
    if (isset ($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnotaproduicms_" . date("dmY") . ".log", "a");
        }
    }

}
if (isset ($LOG_NIVEL)) {
    if ($LOG_NIVEL == 1) {
        fwrite($arquivo, $identificacao . "\n");
    }
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
    }
}
//LOG

$impostos = array();

if ($BANCO == "MYSQL") {
    $sql = "SELECT fisnotaproduicms.*, produtos.nomeProduto FROM fisnotaproduicms 
    LEFT JOIN fisnotaproduto ON fisnotaproduto.idNota = fisnotaproduicms.idNota and fisnotaproduto.nItem = fisnotaproduicms.nItem
    LEFT JOIN produtos ON fisnotaproduto.idProduto = produtos.idProduto ";
    $where = " where ";
    if (isset ($jsonEntrada["idNota"])) {
        $sql = $sql . $where . " fisnotaproduicms.idNota = " . $jsonEntrada["idNota"];
        $where = " and ";
    }

    if (isset ($jsonEntrada["nItem"])) {
        $sql = $sql . $where . " fisnotaproduicms.nItem = " . $jsonEntrada["nItem"];
        $where = " and ";
    }

    if (isset ($jsonEntrada["imposto"])) {
        $sql = $sql . $where . " fisnotaproduicms.imposto = " . "'" . $jsonEntrada["imposto"] . "'";
        $where = " and ";
    }

    //echo "-SQL->".$sql."\n"; 
//LOG
    if (isset ($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
        }
    }
    //LOG

    $rows = 0;
    $buscar = mysqli_query($conexao, $sql);
    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
        if (isset ($jsonEntrada["nItem"]) && isset ($jsonEntrada["idNota"])) {
            if ($row['idNota'] === null || $row['nItem'] === null) {
                continue;
            }

            $impostos[] = $row;

            $calculadoRow = $row;
            if ($calculadoRow['imposto'] !== null) {
                $calculadoRow['imposto'] = "calculado_" . $calculadoRow['imposto'];
            }
            foreach ($calculadoRow as $key => $value) {
                if (is_numeric($value) && $key !== 'idNota' && $key !== 'nItem' && $value !== null) {
                    $calculadoRow[$key] *= 2;
                }
            }
            $impostos[] = $calculadoRow;

            $rows += 2;
        } else {
            array_push($impostos, $row);
            $rows = $rows + 1;
        }
    }
}

if ($BANCO == "PROGRESS") {

    $progr = new chamaprogress();

    // PASSANDO idEmpresa PARA PROGRESS
    if (isset($jsonEntrada['idEmpresa'])) {
        $progr->setempresa($jsonEntrada['idEmpresa']);
    }
    
    $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduicms", json_encode($jsonEntrada));
    fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
    $impostos = json_decode($retorno, true);
    if (isset ($impostos["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
        $impostos = $impostos["conteudoSaida"][0];
    } else {

       
            $impostos = $impostos["fisnotaproduicms"];
       


    }

}

$jsonSaida = $impostos;


//LOG
if (isset ($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

?>

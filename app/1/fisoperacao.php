<?php
//$BANCO = "MYSQL";
$BANCO = "PROGRESS";

if ($BANCO == "MYSQL")
    $conexao = conectaMysql(null);

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisoperacao";
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

$operacao = array();

if ($BANCO == "MYSQL") {

    $sql = "SELECT fisoperacao.*, fisatividade.nomeAtividade, fisnatureza.nomeNatureza, fisprocesso.nomeProcesso FROM fisoperacao
    LEFT JOIN fisatividade ON fisoperacao.idAtividade = fisatividade.idAtividade
    LEFT JOIN fisnatureza ON fisoperacao.idNatureza = fisnatureza.idNatureza
    LEFT JOIN fisprocesso ON fisoperacao.idProcesso = fisprocesso.idProcesso";
    if (isset($jsonEntrada["idOperacao"])) {
        $sql = $sql . " where fisoperacao.idOperacao = " . $jsonEntrada["idOperacao"];
    } else {
        $where = " where ";

        if (isset($jsonEntrada["idAtividade"])) {
            $sql = $sql . $where . " fisoperacao.idAtividade = " . $jsonEntrada["idAtividade"];
            $where = " and ";
        }

        if (isset($jsonEntrada["idNatureza"])) {
            $sql = $sql . $where . " fisoperacao.idNatureza = " . $jsonEntrada["idNatureza"];
            $where = " and ";
        }

        if (isset($jsonEntrada["idProcesso"])) {
            $sql = $sql . $where . " fisoperacao.idProcesso = " . $jsonEntrada["idProcesso"];
            $where = " and ";
        }

        if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'nomeOperacao') {
            $sql = $sql . $where . " fisoperacao.nomeOperacao LIKE '%" . $jsonEntrada["dadosOp"] . "%'";
            $where = " and ";
        }

        if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'idEntSai') {
            $sql = $sql . $where . " fisoperacao.idEntSai = " . $jsonEntrada["idEntSai"];
            $where = " and ";
        }

        if (isset($jsonEntrada["FiltroTipoOp"]) && $jsonEntrada["FiltroTipoOp"] == 'xfop') {
            $sql = $sql . $where . " fisoperacao.xfop = " . $jsonEntrada["xfop"];
            $where = " and ";
        }
    }
    //echo "-SQL->".$sql."\n";

    $sql = $sql . " order by idOperacao";
    $rows = 0;
    $buscar = mysqli_query($conexao, $sql);
    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
        array_push($operacao, $row);
        $rows = $rows + 1;
    }
    if (isset($jsonEntrada["idOperacao"]) && $rows == 1) {
        $operacao = $operacao[0];
    }
}

if ($BANCO == "PROGRESS") {

    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("impostos/app/1/fisoperacao",json_encode($jsonEntrada));
    fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
    $operacao = json_decode($retorno,true);
    if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
        $operacao = $operacao["conteudoSaida"][0];
    } else {
        
        if (!isset($operacao["fisoperacao"][1]) && ($jsonEntrada['idOperacao'] != null)) {  // Verifica se tem mais de 1 registro
        $operacao = $operacao["fisoperacao"][0]; // Retorno sem array
        } 
        else {
        $operacao = $operacao["fisoperacao"];  
        }

    }

}


$jsonSaida = $operacao;


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
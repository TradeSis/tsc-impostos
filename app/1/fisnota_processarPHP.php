<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_processar";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_" . date("dmY") . ".log", "a");
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

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
$conexaogeral = conectaMysql(null);

function buscaPessoa($idPessoa){
    
    $pessoaEntrada = array(
        "idPessoa" => $idPessoa
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("cadastros/app/1/pessoas",json_encode($pessoaEntrada));
    $pessoa = json_decode($retorno,true);
    if (isset($pessoa["conteudoSaida"][0])) { 
        $pessoa = $pessoa["conteudoSaida"][0];
    } else {
        $pessoa = $pessoa["pessoas"][0];  
    }
    return $pessoa;
}

function buscaProduto($idPessoaFornecedor=null,$refProduto=null){
    
    $produtoEntrada = array(
        "idPessoaFornecedor" => $idPessoaFornecedor,
        "refProduto" => $refProduto
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("cadastros/app/1/produtos",json_encode($produtoEntrada));
    $produto = json_decode($retorno,true);
    if (isset($produto["conteudoSaida"][0])) { 
        $produto = $produto["conteudoSaida"][0];
    } else {
        $produto = $produto["produtos"][0];  
    }
    return $produto;
}
function buscaGeralProduto($buscaProduto){
    
    $geralprodutoEntrada = array(
        "buscaProduto" => $buscaProduto
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("sistema/app/1/geralprodutos",json_encode($geralprodutoEntrada));
    $geralproduto = json_decode($retorno,true);
    if (isset($geralproduto["conteudoSaida"][0])) { 
        $geralproduto = $geralproduto["conteudoSaida"][0];
    } else {
        $geralproduto = $geralproduto["geralprodutos"];  
    }
    return $geralproduto;
}
function buscaNFE($idStatusNota,$idNota){
    
    $chaveNfeEntrada = array(
        "idStatusNota" => $idStatusNota,
        "idNota" => $idNota
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("impostos/app/1/fisnota",json_encode($chaveNfeEntrada));
    $fisnota = json_decode($retorno,true);
    if (isset($fisnota["conteudoSaida"][0])) { 
        $fisnota = $fisnota["conteudoSaida"][0];
    } else {
        $fisnota = $fisnota["fisnota"];
    }
    return $fisnota;
}

// Pega XML puro
if (isset($jsonEntrada['idEmpresa'])) {

    if (isset($jsonEntrada["idNota"])) {
    $fisnotas = buscaNFE(0,$jsonEntrada["idNota"]);
    } else {
        $fisnotas = buscaNFE(0,null);
    }
    if (!isset($fisnotas[0]['idNota'])) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "Todas NFEs estão processadas"
        );
    } else {

        foreach($fisnotas as $fisnota) {
        

            $idPessoaEmitente = $fisnota['idPessoaEmitente'];
            $idNota = (string) $fisnota['idNota'];

            $xmlFilePath = $fisnota['XML'];
            $xmlContent = file_get_contents($xmlFilePath);
            $xml = simplexml_load_string($xmlContent);
            $infNFe = $xml->NFe->infNFe;

            if ($infNFe == null) {
                $infNFe = $xml->nfeProc->NFe->infNFe;
            }

            if (isset($infNFe)) {

                //********************************************NOTA FISCAL


                $vBC = isset($infNFe->total->ICMSTot->vBC) && $infNFe->total->ICMSTot->vBC !== "" ? (string) $infNFe->total->ICMSTot->vBC : null;
                $vProd = isset($infNFe->total->ICMSTot->vProd) && $infNFe->total->ICMSTot->vProd !== "" ? (string) $infNFe->total->ICMSTot->vProd : null;
                $vPIS = isset($infNFe->total->ICMSTot->vPIS) && $infNFe->total->ICMSTot->vPIS !== "" ? (string) $infNFe->total->ICMSTot->vPIS : null;
                $vCOFINS = isset($infNFe->total->ICMSTot->vCOFINS) && $infNFe->total->ICMSTot->vCOFINS !== "" ? (string) $infNFe->total->ICMSTot->vCOFINS : null;


                $alterarEntrada = array(
                    "idNota" => $idNota
                ); 
                $progr = new chamaprogress();
                $retorno = $progr->executarprogress("impostos/app/1/fisnota_alterar",json_encode($alterarEntrada));
                fwrite($arquivo,$identificacao."-TOTAL_RETORNO->".$retorno."\n");

                if (file_exists($xmlFilePath)) {
                    unlink($xmlFilePath);
                }



                $nomeTotal = $infNFe->total->ICMSTot->getName();
                $vICMS = isset($infNFe->total->ICMSTot->vICMS) && $infNFe->total->ICMSTot->vICMS !== "" ? (string) $infNFe->total->ICMSTot->vICMS : null;
                $vICMSDeson = isset($infNFe->total->ICMSTot->vICMSDeson) && $infNFe->total->ICMSTot->vICMSDeson !== "" ? (string) $infNFe->total->ICMSTot->vICMSDeson : null;
                $vFCPUFDest = isset($infNFe->total->ICMSTot->vFCPUFDest) && $infNFe->total->ICMSTot->vFCPUFDest !== "" ? (string) $infNFe->total->ICMSTot->vFCPUFDest : null;
                $vICMSUFRemet = isset($infNFe->total->ICMSTot->vICMSUFRemet) && $infNFe->total->ICMSTot->vICMSUFRemet !== "" ? (string) $infNFe->total->ICMSTot->vICMSUFRemet : null;
                $vFCP = isset($infNFe->total->ICMSTot->vFCP) && $infNFe->total->ICMSTot->vFCP !== "" ? (string) $infNFe->total->ICMSTot->vFCP : null;
                $vBCST = isset($infNFe->total->ICMSTot->vBCST) && $infNFe->total->ICMSTot->vBCST !== "" ? (string) $infNFe->total->ICMSTot->vBCST : null;
                $vST = isset($infNFe->total->ICMSTot->vST) && $infNFe->total->ICMSTot->vST !== "" ? (string) $infNFe->total->ICMSTot->vST : null;
                $vFCPST = isset($infNFe->total->ICMSTot->vFCPST) && $infNFe->total->ICMSTot->vFCPST !== "" ? (string) $infNFe->total->ICMSTot->vFCPST : null;
                $vFCPSTRet = isset($infNFe->total->ICMSTot->vFCPSTRet) && $infNFe->total->ICMSTot->vFCPSTRet !== "" ? (string) $infNFe->total->ICMSTot->vFCPSTRet : null;
                $vFrete = isset($infNFe->total->ICMSTot->vFrete) && $infNFe->total->ICMSTot->vFrete !== "" ? (string) $infNFe->total->ICMSTot->vFrete : null;
                $vSeg = isset($infNFe->total->ICMSTot->vSeg) && $infNFe->total->ICMSTot->vSeg !== "" ? (string) $infNFe->total->ICMSTot->vSeg : null;
                $vDesc = isset($infNFe->total->ICMSTot->vDesc) && $infNFe->total->ICMSTot->vDesc !== "" ? (string) $infNFe->total->ICMSTot->vDesc : null;
                $vII = isset($infNFe->total->ICMSTot->vII) && $infNFe->total->ICMSTot->vII !== "" ? (string) $infNFe->total->ICMSTot->vII : null;
                $vIPI = isset($infNFe->total->ICMSTot->vIPI) && $infNFe->total->ICMSTot->vIPI !== "" ? (string) $infNFe->total->ICMSTot->vIPI : null;
                $vIPIDevol = isset($infNFe->total->ICMSTot->vIPIDevol) && $infNFe->total->ICMSTot->vIPIDevol !== "" ? (string) $infNFe->total->ICMSTot->vIPIDevol : null;
                $vOutro = isset($infNFe->total->ICMSTot->vOutro) && $infNFe->total->ICMSTot->vOutro !== "" ? (string) $infNFe->total->ICMSTot->vOutro : null;
                $vNF = isset($infNFe->total->ICMSTot->vNF) && $infNFe->total->ICMSTot->vNF !== "" ? (string) $infNFe->total->ICMSTot->vNF : null;
                $vTotTribTOTAL = isset($infNFe->total->ICMSTot->vTotTrib) && $infNFe->total->ICMSTot->vTotTrib !== "" ? (string) $infNFe->total->ICMSTot->vTotTrib : null;

                $fisnotatotalEntrada = array(
                    "idNota" => $idNota,
                    "nomeTotal" => $nomeTotal,
                    "vBC" => $vBC,
                    "vICMS" => $vICMS,
                    "vICMSDeson" => $vICMSDeson,
                    "vFCPUFDest" => $vFCPUFDest,
                    "vICMSUFRemet" => $vICMSUFRemet,
                    "vFCP" => $vFCP,
                    "vBCST" => $vBCST,
                    "vST" => $vST,
                    "vFCPST" => $vFCPST,
                    "vFCPSTRet" => $vFCPSTRet,
                    "vProd" => $vProd,
                    "vFrete" => $vFrete,
                    "vSeg" => $vSeg,
                    "vDesc" => $vDesc,
                    "vII" => $vII,
                    "vIPI" => $vIPI,
                    "vIPIDevol" => $vIPIDevol,
                    "vPIS" => $vPIS,
                    "vOutro" => $vOutro,
                    "vCOFINS" => $vCOFINS,
                    "vNF" => $vNF,
                    "vTotTrib" => $vTotTribTOTAL
                ); 
                $progr = new chamaprogress();
                $retorno = $progr->executarprogress("impostos/app/1/fisnotatotal_inserir",json_encode($fisnotatotalEntrada));
                fwrite($arquivo,$identificacao."-TOTAL_RETORNO->".$retorno."\n");

                //********************************************FISNOTAPRODUTOS

                include 'fisnotaproduto_inserir.php';


                $jsonSaida = array(
                    "status" => 200,
                    "retorno" => "NFE Processada"
                );
            }
        }
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
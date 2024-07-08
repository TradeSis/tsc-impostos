<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_importar";
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


// Pega XML puro
if (isset($jsonEntrada['xml'])) {

    $xmlArquivos = $jsonEntrada['xml'];
    //fwrite($arquivo, $identificacao . "-XML ARQUIVOS->" . json_encode($xmlArquivos) . "\n");

    foreach ($xmlArquivos as $xmlContent) {
        $xml = simplexml_load_string($xmlContent);

        $infNFe = $xml->NFe->infNFe;
        if ($infNFe == null) {
            $infNFe = $xml->nfeProc->NFe->infNFe;
        }
        $infNFe['Id'];
        //fwrite($arquivo, $identificacao . "-ID->" . json_encode($infNFe['Id']) . "\n");
        $newFilename = 'carregado_' . $infNFe['Id'] . '.json';
        $targetPath =  PROGRESS_TMP . $newFilename;

        $jsonContent = json_encode($xml, JSON_PRETTY_PRINT);
        file_put_contents($targetPath, $jsonContent);

        $apiJsonEntrada[] = array(
            "nomeXml" => $targetPath,
            "idEmpresa" => $jsonEntrada['idEmpresa']
        ); 
    }
    fwrite($arquivo, $identificacao . "-API JSON ENTRADA->" . json_encode($apiJsonEntrada) . "\n");
        $progr = new chamaprogress();
        // PASSANDO idEmpresa PARA PROGRESS
        if (isset($jsonEntrada['idEmpresa'])) {
            $progr->setempresa($jsonEntrada['idEmpresa']);
        }
        
        $retorno = $progr->executarprogress("impostos/app/1/fisnota_importar",json_encode($apiJsonEntrada));
        fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
        $operacao = json_decode($retorno,true);
        if (isset($operacao["conteudoSaida"])) { // Conteudo Saida - Caso de erro
            $jsonSaida = $operacao["conteudoSaida"];
        
        } else {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "NFE cadastrada"
            );
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
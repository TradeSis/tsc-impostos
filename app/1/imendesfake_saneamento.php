<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendesfake";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendesfake_Saneamento_" . date("dmY") . ".log", "a");
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

// CHAMADA IMENDES

$JSONFAKE = "{
  \"Cabecalho\": {
      \"sugestao\": \"\",
      \"amb\": 1,
      \"cnpj\": \"03521642000124\",
      \"dthr\": \"2024-01-03T11:58:03.6497049+00:00\",
      \"transacao\": \"148699938429190218428532\",
      \"mensagem\": \"OK\",
      \"prodEnv\": 1,
      \"prodRet\": 1,
      \"prodNaoRet\": 0,
      \"comportamentosParceiro\": \"102;106;108\",
      \"comportamentosCliente\": \"\",
      \"versao\": \"2.26.13.0\",
      \"duracao\": \"00:00:00.0753184\"
  },
  \"Grupos\": [
      {
          \"codigo\": \"2013\",
          \"descricao\": \"TESTEx5\",
          \"nCM\": \"39174090\",
          \"cEST\": \"10.006.00\",
          \"dtVigIni\": \"01/01/1900\",
          \"dtVigFin\": \"\",
          \"lista\": \"\",
          \"tipo\": \"\",
          \"codAnp\": \"\",
          \"passivelPMC\": \"N\",
          \"impostoImportacao\": 16.00,
          \"pisCofins\": {
              \"cstEnt\": \"50\",
              \"cstSai\": \"01\",
              \"aliqPis\": 1.65,
              \"aliqCofins\": 7.6,
              \"nri\": \"\",
              \"ampLegal\": \"''\",
              \"redPis\": 0,
              \"redCofins\": 0
          },
          \"iPI\": {
              \"cstEnt\": \"03\",
              \"cstSai\": \"53\",
              \"aliqipi\": 0,
              \"codenq\": \"999\",
              \"ex\": \"\"
          },
          \"Regras\": [
              {
                  \"uFs\": [
                      {
                          \"uF\": \"RS\",
                          \"CFOP\": {
                              \"cFOP\": \"2101\",
                              \"CaracTrib\": [
                                  {
                                      \"codigo\": \"3\",
                                      \"finalidade\": \"0\",
                                      \"codRegra\": \"6350\",
                                      \"codExcecao\": 0,
                                      \"dtVigIni\": \"01/01/2022\",
                                      \"dtVigFin\": \"\",
                                      \"cFOP\": \"2403\",
                                      \"cST\": \"10\",
                                      \"cSOSN\": \"\",
                                      \"aliqIcmsInterna\": 17.00,
                                      \"aliqIcmsInterestadual\": 12.00,
                                      \"reducaoBcIcms\": 0,
                                      \"reducaoBcIcmsSt\": 0,
                                      \"redBcICMsInterestadual\": 0,
                                      \"aliqIcmsSt\": 17.00,
                                      \"iVA\": 83.00,
                                      \"iVAAjust\": 94.02,
                                      \"fCP\": 0,
                                      \"codBenef\": \"\",
                                      \"pDifer\": 0.0,
                                      \"pIsencao\": 0,
                                      \"antecipado\": \"N\",
                                      \"desonerado\": \"N\",
                                      \"pICMSDeson\": 0,
                                      \"isento\": \"N\",
                                      \"tpCalcDifal\": 0,
                                      \"ampLegal\": \"\",
                                      \"Protocolo\": {},
                                      \"Convenio\": {},
                                      \"regraGeral\": \"N\"
                                  }
                              ]
                          },
                          \"mensagem\": \"OK\"
                      },
                      {
                          \"uF\": \"PR\",
                          \"CFOP\": {
                              \"cFOP\": \"1101\",
                              \"CaracTrib\": [
                                  {
                                      \"codigo\": \"3\",
                                      \"finalidade\": \"0\",
                                      \"codRegra\": \"6350\",
                                      \"codExcecao\": 0,
                                      \"dtVigIni\": \"01/01/2022\",
                                      \"dtVigFin\": \"\",
                                      \"cFOP\": \"1403\",
                                      \"cST\": \"60\",
                                      \"cSOSN\": \"\",
                                      \"aliqIcmsInterna\": 17.00,
                                      \"aliqIcmsInterestadual\": 0.00,
                                      \"reducaoBcIcms\": 0,
                                      \"reducaoBcIcmsSt\": 0,
                                      \"redBcICMsInterestadual\": 0,
                                      \"aliqIcmsSt\": 17.00,
                                      \"iVA\": 83.00,
                                      \"iVAAjust\": 0,
                                      \"fCP\": 0,
                                      \"codBenef\": \"RS052434\",
                                      \"pDifer\": 0,
                                      \"pIsencao\": 0,
                                      \"antecipado\": \"N\",
                                      \"desonerado\": \"N\",
                                      \"pICMSDeson\": 0,
                                      \"isento\": \"N\",
                                      \"tpCalcDifal\": 0,
                                      \"ampLegal\": \"'BASE LEGAL DA SUBSTITUICAO TRIBUTARIA - RICMS/RS, APENDICE II, SECAO III, ITEM XXVI, NUMERO 6'\",
                                      \"Protocolo\": {},
                                      \"Convenio\": {},
                                      \"regraGeral\": \"N\"
                                  }
                              ]
                          },
                          \"mensagem\": \"OK\"
                      }
                  ]
              }
          ],
          \"prodEan\": [
              \"7899830001153\",
              \"07891960708166\"
          ],
          \"Mensagem\": \"OK\"
      }
  ],
  \"SemRetorno\": [],
  \"BaixaSimilaridade\": []
}";

function atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo)
{
  //Atualiza Produto
  $sql_consulta = "SELECT * FROM produtos WHERE eanProduto = $eanProduto ";
  $buscar_consulta = mysqli_query($conexao, $sql_consulta);
  $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

  if($row_consulta !== null){
    $idProduto = $row_consulta["idProduto"];
    $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest, codigoGrupo=$codigoGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idProduto = $idProduto";

    $atualizar = mysqli_query($conexao, $update_produtos);
  }else{
    $atualizar = " Produto não encontrado ";
  }

  return $atualizar;
}

function adicionaHistorico($conexao, $retornoImendes)
{
  $sugestao = isset($retornoImendes['Cabecalho']['sugestao']) && $retornoImendes['Cabecalho']['sugestao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['sugestao'] . "'" : "null";
  $amb = isset($retornoImendes['Cabecalho']['amb']) && $retornoImendes['Cabecalho']['amb'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['amb'] . "'" : "null";
  $cnpj = isset($retornoImendes['Cabecalho']['cnpj']) && $retornoImendes['Cabecalho']['cnpj'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['cnpj'] . "'" : "null";
  $dthr = isset($retornoImendes['Cabecalho']['dthr']) && $retornoImendes['Cabecalho']['dthr'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['dthr'] . "'" : "null";
  $transacao = isset($retornoImendes['Cabecalho']['transacao']) && $retornoImendes['Cabecalho']['transacao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['transacao'] . "'" : "null";
  $mensagem = isset($retornoImendes['Cabecalho']['mensagem']) && $retornoImendes['Cabecalho']['mensagem'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['mensagem'] . "'" : "null";
  $prodEnv = isset($retornoImendes['Cabecalho']['prodEnv']) && $retornoImendes['Cabecalho']['prodEnv'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodEnv'] . "'" : "null";
  $prodRet = isset($retornoImendes['Cabecalho']['prodRet']) && $retornoImendes['Cabecalho']['prodRet'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodRet'] . "'" : "null";
  $prodNaoRet = isset($retornoImendes['Cabecalho']['prodNaoRet']) && $retornoImendes['Cabecalho']['prodNaoRet'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodNaoRet'] . "'" : "null";
  $comportamentosParceiro = isset($retornoImendes['Cabecalho']['comportamentosParceiro']) && $retornoImendes['Cabecalho']['comportamentosParceiro'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['comportamentosParceiro'] . "'" : "null";
  $comportamentosCliente = isset($retornoImendes['Cabecalho']['comportamentosCliente']) && $retornoImendes['Cabecalho']['comportamentosCliente'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['comportamentosCliente'] . "'" : "null";
  $versao = isset($retornoImendes['Cabecalho']['versao']) && $retornoImendes['Cabecalho']['versao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['versao'] . "'" : "null";
  $duracao = isset($retornoImendes['Cabecalho']['duracao']) && $retornoImendes['Cabecalho']['duracao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['duracao'] . "'" : "null";

  $inseirHistorico = " INSERT INTO apifiscalhistorico (dtHistorico, sugestao, amb, cnpj, dthr, transacao, mensagem, prodEnv, prodRet, prodNaoRet, comportamentosParceiro, 
  comportamentosCliente, versao, duracao) 
  VALUES (CURRENT_TIMESTAMP(), $sugestao, $amb , $cnpj, $dthr, $transacao, $mensagem, $prodEnv, $prodRet, $prodNaoRet, $comportamentosParceiro, 
  $comportamentosCliente, $versao, $duracao) ";

  $adicionaHistorico = mysqli_query($conexao, $inseirHistorico);

  return $adicionaHistorico;
}

function adicionaRegraFiscal($conexao, $regras, $codigoGrupo){
  foreach ($regras as $regra) {

    foreach ($regra as $ufs) {

      foreach ($ufs as $dadosCFOP) {

        $codigoEstado = isset($dadosCFOP['uF']) && $dadosCFOP['uF'] !== "null"    ? "'" . $dadosCFOP['uF'] . "'" : "null";
        $cFOP = isset($dadosCFOP['CFOP']['cFOP']) && $dadosCFOP['CFOP']['cFOP'] !== "null"    ? "'" . $dadosCFOP['CFOP']['cFOP'] . "'" : "null";

        foreach ($dadosCFOP['CFOP']['CaracTrib'] as $CaracTrib) {

          $codigoCaracTrib = isset($CaracTrib['codigo']) && $CaracTrib['codigo'] !== "null"    ? "'" . $CaracTrib['codigo'] . "'" : "null";
          $finalidade = isset($CaracTrib['finalidade']) && $CaracTrib['finalidade'] !== "null"    ? "'" . $CaracTrib['finalidade'] . "'" : "null";
          $codRegra = isset($CaracTrib['codRegra']) && $CaracTrib['codRegra'] !== "null"    ? "'" . $CaracTrib['codRegra'] . "'" : "null";
          $codExcecao = isset($CaracTrib['codExcecao']) && $CaracTrib['codExcecao'] !== "null"    ? "'" . $CaracTrib['codExcecao'] . "'" : "null";
          $dtVigIni = isset($CaracTrib['dtVigIni']) && $CaracTrib['dtVigIni'] !== ""    ? date('Ymd', strtotime($CaracTrib['dtVigIni'])) : "null";
          $dtVigFin = isset($CaracTrib['dtVigFin']) && $CaracTrib['dtVigFin'] !== ""    ? date('Ymd', strtotime($CaracTrib['dtVigFin'])) : "null";
          $cFOPCaracTrib = isset($CaracTrib['cFOP']) && $CaracTrib['cFOP'] !== "null"    ? "'" . $CaracTrib['cFOP'] . "'" : "null";
          $cST = isset($CaracTrib['cST']) && $CaracTrib['cST'] !== "null"    ? "'" . $CaracTrib['cST'] . "'" : "null";
          $cSOSN = isset($CaracTrib['cSOSN']) && $CaracTrib['cSOSN'] !== "null"    ? "'" . $CaracTrib['cSOSN'] . "'" : "null";
          $aliqIcmsInterna = isset($CaracTrib['aliqIcmsInterna']) && $CaracTrib['aliqIcmsInterna'] !== "null"    ? "'" . $CaracTrib['aliqIcmsInterna'] . "'" : "null";
          $aliqIcmsInterestadual = isset($CaracTrib['aliqIcmsInterestadual']) && $CaracTrib['aliqIcmsInterestadual'] !== "null"    ? "'" . $CaracTrib['aliqIcmsInterestadual'] . "'" : "null";
          $reducaoBcIcms = isset($CaracTrib['reducaoBcIcms']) && $CaracTrib['reducaoBcIcms'] !== "null"    ? "'" . $CaracTrib['reducaoBcIcms'] . "'" : "null";
          $reducaoBcIcmsSt = isset($CaracTrib['reducaoBcIcmsSt']) && $CaracTrib['reducaoBcIcmsSt'] !== "null"    ? "'" . $CaracTrib['reducaoBcIcmsSt'] . "'" : "null";
          $redBcICMsInterestadual = isset($CaracTrib['redBcICMsInterestadual']) && $CaracTrib['redBcICMsInterestadual'] !== "null"    ? "'" . $CaracTrib['redBcICMsInterestadual'] . "'" : "null";
          $aliqIcmsSt = isset($CaracTrib['aliqIcmsSt']) && $CaracTrib['aliqIcmsSt'] !== "null"    ? "'" . $CaracTrib['aliqIcmsSt'] . "'" : "null";
          $iVA = isset($CaracTrib['iVA']) && $CaracTrib['iVA'] !== "null"    ? "'" . $CaracTrib['iVA'] . "'" : "null";
          $iVAAjust = isset($CaracTrib['iVAAjust']) && $CaracTrib['iVAAjust'] !== "null"    ? "'" . $CaracTrib['iVAAjust'] . "'" : "null";
          $fCP = isset($CaracTrib['fCP']) && $CaracTrib['fCP'] !== "null"    ? "'" . $CaracTrib['fCP'] . "'" : "null";
          $codBenef = isset($CaracTrib['codBenef']) && $CaracTrib['codBenef'] !== "null"    ? "'" . $CaracTrib['codBenef'] . "'" : "null";
          $pDifer = isset($CaracTrib['pDifer']) && $CaracTrib['pDifer'] !== "null"    ? "'" . $CaracTrib['pDifer'] . "'" : "null";
          $pIsencao = isset($CaracTrib['pIsencao']) && $CaracTrib['pIsencao'] !== "null"    ? "'" . $CaracTrib['pIsencao'] . "'" : "null";
          $antecipado = isset($CaracTrib['antecipado']) && $CaracTrib['antecipado'] !== "null"    ? "'" . $CaracTrib['antecipado'] . "'" : "'N'";
          $desonerado = isset($CaracTrib['desonerado']) && $CaracTrib['desonerado'] !== "null"    ? "'" . $CaracTrib['desonerado'] . "'" : "'N'";
          $pICMSDeson = isset($CaracTrib['pICMSDeson']) && $CaracTrib['pICMSDeson'] !== "null"    ? "'" . $CaracTrib['pICMSDeson'] . "'" : "null";
          $isento = isset($CaracTrib['isento']) && $CaracTrib['isento'] !== "null"    ? "'" . $CaracTrib['isento'] . "'" : "'N'";
          $tpCalcDifal = isset($CaracTrib['tpCalcDifal']) && $CaracTrib['tpCalcDifal'] !== "null"    ? "'" . $CaracTrib['tpCalcDifal'] . "'" : "null";
          $ampLegal = str_replace("'", "", $CaracTrib['ampLegal']);
          $ampLegal_formatada = isset($ampLegal) && $ampLegal !== "null"    ? "'" .  $ampLegal . "'" : "null";
          //$Protocolo = isset($CaracTrib['Protocolo']) && $CaracTrib['Protocolo'] !== "null"    ? "'" . $CaracTrib['Protocolo'] . "'" : "null";
          //$Convenio = isset($CaracTrib['Convenio']) && $CaracTrib['Convenio'] !== "null"    ? "'" . $CaracTrib['Convenio'] . "'" : "null";
          $regraGeral = isset($CaracTrib['regraGeral']) && $CaracTrib['regraGeral'] !== "null"    ? "'" . $CaracTrib['regraGeral'] . "'" : "null";

          //Verifica se tem regra
          $sql_consulta = "SELECT * FROM regrafiscal WHERE codigoGrupo = $codigoGrupo AND codigoEstado = $codigoEstado AND cFOP = $cFOP AND codigoCaracTrib = $codigoCaracTrib" ;
          $buscar_consulta = mysqli_query($conexao, $sql_consulta);
          $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
  
          if($row_consulta == null){
            $sql = " INSERT INTO regrafiscal (codigoGrupo, codigoEstado, cFOP, codigoCaracTrib, finalidade, codRegra, codExcecao, dtVigIni,
            dtVigFin, cFOPCaracTrib, cST, cSOSN, aliqIcmsInterna, aliqIcmsInterestadual, reducaoBcIcms, reducaoBcIcmsSt, redBcICMsInterestadual,
            aliqIcmsSt, iVA, iVAAjust, fCP, codBenef, pDifer, pIsencao, antecipado, desonerado, pICMSDeson, isento, tpCalcDifal, ampLegal,
            Protocolo, Convenio, regraGeral) 
            VALUES ($codigoGrupo, $codigoEstado, $cFOP, $codigoCaracTrib, $finalidade, $codRegra, $codExcecao, $dtVigIni,
            $dtVigFin , $cFOPCaracTrib, $cST, $cSOSN, $aliqIcmsInterna, $aliqIcmsInterestadual, $reducaoBcIcms, $reducaoBcIcmsSt, $redBcICMsInterestadual,
            $aliqIcmsSt, $iVA, $iVAAjust, $fCP, $codBenef, $pDifer, $pIsencao, $antecipado, $desonerado, $pICMSDeson, $isento, $tpCalcDifal, $ampLegal_formatada,
            null, null, $regraGeral) ";

            $adicionaregraFiscal = mysqli_query($conexao, $sql);
          }else{
            $adicionaregraFiscal = " Regra existente ";
          }
        }
      }
    }
  }

  return $adicionaregraFiscal;
}

$retornoImendes = json_decode($JSONFAKE, true);

$historico = adicionaHistorico($conexao, $retornoImendes);


foreach ($retornoImendes['Grupos'] as $grupo) {
  if (is_array($grupo) && isset($grupo['codigo'])) {

    $codigoGrupo = $grupo['codigo'];
    $eanProdutos = $grupo['prodEan'];

    //Verifica se já tem codigoGrupo
    $sql_consulta = "SELECT * FROM grupoproduto WHERE codigoGrupo = $codigoGrupo ";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $codigoGrupo = isset($row_consulta["codigoGrupo"]) && $row_consulta["codigoGrupo"] !== "null"    ? "'" . $row_consulta["codigoGrupo"] . "'" : "null";
    $codigoNcm = isset($row_consulta["codigoNcm"]) && $row_consulta["codigoNcm"] !== "null"    ? "'" . $row_consulta["codigoNcm"] . "'" : "null";
    $codigoCest = isset($row_consulta["codigoCest"]) && $row_consulta["codigoCest"] !== "null"    ? "'" . $row_consulta["codigoCest"] . "'" : "null";


    if ($codigoGrupo != "null") {
      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo);
      }
      $regrafiscal = adicionaRegraFiscal($conexao, $grupo['Regras'], $grupo['codigo']);
      $jsonSaida = array(
        "status" => 200,
        "retorno" => "codigo do Grupo existente",
        "codigoGrupo" => $codigoGrupo
      );
    } else {

      $regrafiscal = adicionaRegraFiscal($conexao, $grupo['Regras'], $grupo['codigo']);
      $codigoGrupo = "'" . $grupo['codigo'] . "'";
      $codigoCest = "'" . $grupo['cEST'] .  "'";
      $codigoNcm = "'" . $grupo['nCM'] . "'";

      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo);
      }

      $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
        'codigoGrupo' => $grupo['codigo'],
        'nomeGrupo' => $grupo['descricao'],
        'codigoNcm' => $grupo['nCM'],
        'codigoCest' => $grupo['cEST'],
        'impostoImportacao' => $grupo['impostoImportacao'],
        'piscofinscstEnt' => $grupo['pisCofins']['cstEnt'],
        'piscofinscstSai' => $grupo['pisCofins']['cstSai'],
        'aliqPis' => $grupo['pisCofins']['aliqPis'],
        'aliqCofins' => $grupo['pisCofins']['aliqCofins'],
        'nri' => $grupo['pisCofins']['nri'],
        'ampLegal' => $grupo['pisCofins']['ampLegal'],
        'redPIS' => $grupo['pisCofins']['redPis'],
        'redCofins' => $grupo['pisCofins']['redCofins'],
        'ipicstEnt' => $grupo['iPI']['cstEnt'],
        'ipicstSai' => $grupo['iPI']['cstSai'],
        'aliqipi' => $grupo['iPI']['aliqipi'],
        'codenq' => $grupo['iPI']['codenq'],
        'ipiex' => $grupo['iPI']['ex']
      );

      $inserirGrupo = chamaAPI(null, '/cadastros/grupoproduto', json_encode($apiEntrada), 'PUT');

      //TRY-CATCH
      try {
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
    }
  } else {
    $jsonSaida = array(
      "status" => 400,
      "retorno" => "Faltaram parametros"
    );
  }
}


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

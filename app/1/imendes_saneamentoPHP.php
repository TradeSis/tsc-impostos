<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendes";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendes_Saneamento_" . date("dmY") . ".log", "a");
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

//APIFISCA
$sql_apifiscal = "SELECT apifiscal.login,apifiscal.senha,apifiscal.tpAmb,apifiscal.cfopEntrada,apifiscal.finalidade FROM apifiscal WHERE idEmpresa = $idEmpresa ";
$buscar_apifiscal = mysqli_query($conexao, $sql_apifiscal);
$row_apifiscal = mysqli_fetch_array($buscar_apifiscal, MYSQLI_ASSOC);
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-APIFISCAL->" . json_encode($row_apifiscal) . "\n");
  }
}

$login = $row_apifiscal['login'];
if (!$row_apifiscal['login']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.login não informado"
  );
  return;
}
$senha = $row_apifiscal['senha'];
if (!$row_apifiscal['senha']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.senha não informado"
  );
  return;
}
$amb = $row_apifiscal['tpAmb'];
if (!$row_apifiscal['tpAmb']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.tpAmb não informado"
  );
  return;
}
$cfopEntrada = $row_apifiscal['cfopEntrada'];
if (!$row_apifiscal['cfopEntrada']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.cfopEntrada não informado"
  );
  return;
}
$finalidade = isset($row_apifiscal['finalidade']) && $row_apifiscal['finalidade'] !== "null" ? (int)$row_apifiscal['finalidade'] : "null";
if ($row_apifiscal['finalidade'] == null) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.finalidade não informado"
  );
  return;
}

//EMPRESA
$sql_empresa = "SELECT empresa.idPessoa FROM empresa WHERE idEmpresa = $idEmpresa ";
$buscar_empresa = mysqli_query($conexaogeral, $sql_empresa);
$row_empresa = mysqli_fetch_array($buscar_empresa, MYSQLI_ASSOC);
$idPessoaEmpresa = $row_empresa['idPessoa'];

//PESSOAS
$sql_empresa = "SELECT * FROM pessoas WHERE idPessoa = $idPessoaEmpresa ";
$buscar_empresa = mysqli_query($conexao, $sql_empresa);
$row_empresa = mysqli_fetch_array($buscar_empresa, MYSQLI_ASSOC);
$cpfCnpjEmpresa = $row_empresa['cpfCnpj'];

//GERALPESSOAS 
$sql_geralPessoa = "SELECT geralpessoas.codigoEstado, geralpessoas.cpfCnpj, geralpessoas.cnae, geralpessoas.regimeEspecial, geralpessoas.regimeTrib, geralpessoas.crt, geralpessoas.origem,
geralpessoas.caracTrib FROM geralpessoas WHERE cpfCnpj = $cpfCnpjEmpresa ";
$buscar_geralPessoa = mysqli_query($conexaogeral, $sql_geralPessoa);
$row_geralPessoa = mysqli_fetch_array($buscar_geralPessoa, MYSQLI_ASSOC);

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-geralPESSOA->" . json_encode($row_geralPessoa) . "\n");
  }
}

$cpfCnpj = $row_geralPessoa['cpfCnpj'];
$cnae = $row_geralPessoa['cnae'];
if (!$row_geralPessoa['cnae']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.cnae não informado"
  );
  return;
}
$regimeEspecial = $row_geralPessoa['regimeEspecial'];
if (!$row_geralPessoa['regimeEspecial']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.regimeEspecial não informado"
  );
  return;
}
$regimeTrib = $row_geralPessoa['regimeTrib'];
if (!$row_geralPessoa['regimeTrib']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.regimeTrib não informado"
  );
  return;
}
$codigoEstado = $row_geralPessoa['codigoEstado'];
$crt = (int)$row_geralPessoa['crt'];
if (!$row_geralPessoa['crt']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.crt não informado"
  );
  return;
}
$origem = $row_geralPessoa['origem'];
if (!isset($origem)) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.origem  não informado"
  );
  return;
}
if ($regimeTrib == 'SN') {
  $simplesN = 'S';
} else {
  $simplesN = 'N';
}

$caracTrib = isset($row_geralPessoa['caracTrib']) && $row_geralPessoa['caracTrib'] !== "null" ? (int)$row_geralPessoa['caracTrib'] : "null";
if ($row_geralPessoa['caracTrib'] == null) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "geralPessoa.caracTrib não informado"
  );
  return;
}


if (isset($jsonEntrada["idProduto"])) {
  //PRODUTOS
  $produtos = array();
  $sql_produtos = "SELECT * FROM produtos WHERE idProduto = " . $jsonEntrada["idProduto"] . " ";
  $buscar_produtos = mysqli_query($conexao, $sql_produtos);

  while ($row = mysqli_fetch_array($buscar_produtos, MYSQLI_ASSOC)) {
    $idGeralProduto = $row['idGeralProduto'];
  
    $sql2 = "SELECT geralprodutos.* FROM geralprodutos WHERE geralprodutos.idGeralProduto = $idGeralProduto";
    $rows = 0;
    $buscar2 = mysqli_query($conexaogeral, $sql2);

    while ($row2 = mysqli_fetch_array($buscar2, MYSQLI_ASSOC)) {
      $mergedRow = array_merge($row, $row2);
      array_push($produtos, $mergedRow);
      $rows = $rows + 1;
    }
    
  }
  if (isset($jsonEntrada["idProduto"]) && $rows == 1) {
    $produtos = $produtos[0];
  }
  $retornoProdutos = $produtos;

  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
      fwrite($arquivo, $identificacao . "-produtos->" . json_encode($retornoProdutos) . "\n");
    }
  }
  $nomeProduto = $retornoProdutos['nomeProduto'];
  $codigoNcm = $retornoProdutos['codigoNcm'];
  $eanProduto = $retornoProdutos['eanProduto'];

}


$emit = array(
  'amb' => $amb,
  'cnpj' => $cpfCnpj,
  'crt' => $crt, //- Para o CRT 3, informe o campo 'regimeTrib' igual a 'LR' ou 'LP'."
  'regimeTrib' => $regimeTrib,
  'uf' => $codigoEstado,
  'cnae' => $cnae,
  'regimeEspecial' => $regimeEspecial,
  'substICMS' => "N", // - Verificar com Daniel
  'interdependente' => "N", // - Verificar com Daniel
);

$ufPerfil = array(
  $codigoEstado
);

$caracTrib = array(
  $caracTrib
);
$perfil = array(
  'uf' => $ufPerfil,
  'cfop' => $cfopEntrada, //"1101"
  'caracTrib' => $caracTrib,
  'finalidade' => $finalidade,
  'simplesN' => $simplesN,
  'origem' => $origem,
  'substICMS' => "N",
  'prodZFM' => "N"
);

$produto = array(
  'codigo' => $eanProduto,
  'codInterno' => "N",
  'descricao' => $nomeProduto,
  'ncm' => $codigoNcm
);

$produtos = array(
  $produto
);

$imendesEntrada = array(
  'emit' => $emit,
  'perfil' => $perfil,
  'produtos' => $produtos
);

$apiHeaders = array(
  "Content-Type: application/json",
  "login: $login",
  "senha: $senha"
);

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-imendesEntrada->" . json_encode($imendesEntrada) . "\n");
  }
}

// CHAMADA IMENDES
$JSON = chamaAPI(
  "http://consultatributos.com.br:8080",
  "/api/v3/public/SaneamentoGrades",
  json_encode($imendesEntrada),
  "POST",
  $apiHeaders
);
//echo "IMENDES\n".json_encode($JSON)."\n";


$produtoNaoRetornado = $JSON['Cabecalho']['prodNaoRet'];
if ($produtoNaoRetornado == 1) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "Nenhum produto retornado.",
    "mensagem" => true
  );
}

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-imendesSaida->" . json_encode($JSON) . "\n");
  }
}



function atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo)
{
  //Atualiza Geral Produtos
  $sql_geralProdutos = "SELECT geralprodutos.idGeralProduto FROM geralprodutos WHERE eanProduto = $eanProduto ";
  $buscar_geralProdutos = mysqli_query($conexaogeral, $sql_geralProdutos);
  $row_geralProdutos = mysqli_fetch_array($buscar_geralProdutos, MYSQLI_ASSOC);

  if ($row_geralProdutos !== null) {
    $idGeralProduto = $row_geralProdutos["idGeralProduto"];

    $update_geralProdutos = "UPDATE geralprodutos SET idGrupo=$idGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idGeralProduto = $idGeralProduto";

    $atualizar = mysqli_query($conexaogeral, $update_geralProdutos);

//Atualiza Produtos
    $produtos = array();
    $sql_produtos = "SELECT produtos.idProduto FROM produtos WHERE idGeralProduto = $idGeralProduto";

    $rows = 0;
    $buscar = mysqli_query($conexao, $sql_produtos);
    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
      array_push($produtos, $row);
      $rows = $rows + 1;
    }

    foreach ($produtos as $produto) {
      $idProduto = $produto["idProduto"];
      $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest WHERE idProduto = $idProduto";

      $atualizar2 = mysqli_query($conexao, $update_produtos);
      if (isset($atualizar2)) {
        $atualizar2 = "ok";
      }
    }
  } else {
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

function adicionaRegraFiscal($conexaogeral, $regras, $idGrupo)
{

  $returnRegraFiscal = "";

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

          //Verifica se existe regrafiscal
          $sql_regra = "SELECT fiscalregra.idRegra, fiscalregra.codRegra, fiscalregra.codExcecao FROM fiscalregra WHERE codRegra = $codRegra AND codExcecao = $codExcecao ";
          $buscar_regra = mysqli_query($conexaogeral, $sql_regra);
          $row_regra = mysqli_fetch_array($buscar_regra, MYSQLI_ASSOC);

          if ($row_regra == null) {
            $apiEntrada = array(
              'codRegra' => $CaracTrib['codRegra'],
              'codExcecao' => $CaracTrib['codExcecao'],
              'dtVigIni' =>  $dtVigIni,
              'dtVigFin' =>  $dtVigFin,
              'cFOPCaracTrib' =>  $CaracTrib['cFOP'],
              'cST' => $CaracTrib['cST'],
              'cSOSN' =>  $CaracTrib['cSOSN'],
              'aliqIcmsInterna' => $CaracTrib['aliqIcmsInterna'],
              'aliqIcmsInterestadual' => $CaracTrib['aliqIcmsInterestadual'],
              'reducaoBcIcms' => $CaracTrib['reducaoBcIcms'],
              'reducaoBcIcmsSt' => $CaracTrib['reducaoBcIcmsSt'],
              'redBcICMsInterestadual' => $CaracTrib['redBcICMsInterestadual'],
              'aliqIcmsSt' => $CaracTrib['aliqIcmsSt'],
              'iVA' => $CaracTrib['iVA'],
              'iVAAjust' => $CaracTrib['iVAAjust'],
              'fCP' => $CaracTrib['fCP'],
              'codBenef' => $CaracTrib['codBenef'],
              'pDifer' => $CaracTrib['pDifer'],
              'pIsencao' => $CaracTrib['pIsencao'],
              'antecipado' => $CaracTrib['antecipado'],
              'desonerado' => $CaracTrib['desonerado'],
              'pICMSDeson' => $CaracTrib['pICMSDeson'],
              'isento' => $CaracTrib['isento'],
              'tpCalcDifal' => $CaracTrib['tpCalcDifal'],
              'ampLegal' => $ampLegal = str_replace("'", "", $CaracTrib['ampLegal']),
              'ampLegal_formatada' => $ampLegal,
              //'Protocolo' => $CaracTrib['Protocolo'],
              //'Convenio' => $CaracTrib['Convenio'],
              'regraGeral' => $CaracTrib['regraGeral'],
            );

            $inserirRegra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'PUT');
            $idRegra = $inserirRegra['idRegra'];
          } else {
            $idRegra = $row_regra['idRegra'];
          }

          //Verifica se existe operacaofiscal
          $sql_operacao = "SELECT * FROM fiscaloperacao WHERE idGrupo = $idGrupo AND codigoEstado = $codigoEstado AND cFOP = $cFOP AND codigoCaracTrib = $codigoCaracTrib AND finalidade = $finalidade";
          $buscar_operacao = mysqli_query($conexaogeral, $sql_operacao);
          $row_operacao = mysqli_fetch_array($buscar_operacao, MYSQLI_ASSOC);

          if ($row_operacao == null) {
            $sql = " INSERT INTO fiscaloperacao (idGrupo, codigoEstado, cFOP, codigoCaracTrib, finalidade, idRegra) 
              VALUES ($idGrupo, $codigoEstado, $cFOP, $codigoCaracTrib, $finalidade, $idRegra) ";

            $inserirfiscaloperacao = mysqli_query($conexaogeral, $sql);
            if ($inserirfiscaloperacao == null) {
              $returnRegraFiscal = "erro inserir operacao";
            }
          }
        }
      }
    }
  }

  return $returnRegraFiscal;
}

$retornoImendes = $JSON;

$historico = adicionaHistorico($conexao, $retornoImendes);


foreach ($retornoImendes['Grupos'] as $grupo) {
  if (is_array($grupo) && isset($grupo['codigo'])) {

    $codigoGrupo = $grupo['codigo'];
    $eanProdutos = $grupo['prodEan'];

    //Verifica se já tem codigoGrupo
    $sql_consulta = "SELECT fiscalgrupo.idGrupo, fiscalgrupo.codigoGrupo, fiscalgrupo.codigoNcm, fiscalgrupo.codigoCest FROM fiscalgrupo WHERE codigoGrupo = $codigoGrupo ";
    $buscar_consulta = mysqli_query($conexaogeral, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

    if ($row_consulta != null) {
      if ($row_consulta["codigoGrupo"] != "null") {
        $idGrupo = $row_consulta["idGrupo"];
        $codigoGrupo = isset($row_consulta["codigoGrupo"]) && $row_consulta["codigoGrupo"] !== "null"    ? "'" . $row_consulta["codigoGrupo"] . "'" : "null";
        $codigoNcm = isset($row_consulta["codigoNcm"]) && $row_consulta["codigoNcm"] !== "null"    ? "'" . $row_consulta["codigoNcm"] . "'" : "null";
        $codigoCest = isset($row_consulta["codigoCest"]) && $row_consulta["codigoCest"] !== "null"    ? "'" . $row_consulta["codigoCest"] . "'" : "null";


        foreach ($eanProdutos as $eanProduto) {
          $atualizaProduto = atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo);
        }
        $regrafiscal = adicionaRegraFiscal($conexaogeral, $grupo['Regras'], $idGrupo);
        $jsonSaida = array(
          "status" => 200,
          "retorno" => "codigo do Grupo existente",
          "codigoGrupo" => $codigoGrupo
        );
      }
    } else {
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

      if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 2) {
          fwrite($arquivo, $identificacao . "-GRUPOINSERIR->" . json_encode($apiEntrada) . "\n");
        }
      }

      $inserirGrupo = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'PUT');
      $idGrupo = $inserirGrupo['idGrupo'];

      $codigoCest = "'" . $grupo['cEST'] .  "'";
      $codigoNcm = "'" . $grupo['nCM'] . "'";

      $regrafiscal = adicionaRegraFiscal($conexaogeral, $grupo['Regras'], $idGrupo);

      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo);
      }

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
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n");
  }
}
//LOG

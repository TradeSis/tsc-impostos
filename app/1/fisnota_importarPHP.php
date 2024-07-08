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

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
$conexaogeral = conectaMysql(null);

function buscaPessoa($cpfCnpj=null,$idPessoa=null){
    
    $pessoaEntrada = array(
        "cpfCnpj" => $cpfCnpj,
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
function buscaGeralPessoa($cpfCnpj){
    
    $geralpessoaEntrada = array(
        "cpfCnpj" => $cpfCnpj
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("sistema/app/1/geralpessoas",json_encode($geralpessoaEntrada));
    $geralpessoa = json_decode($retorno,true);
    if (isset($geralpessoa["conteudoSaida"][0])) { 
        $resposta = false;
    } else {
        $resposta = true; 
    }
    return $resposta;
}
function buscaChaveNFE($chaveNFe){
    
    $chaveNfeEntrada = array(
        "chaveNFe" => $chaveNFe
    ); 
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("impostos/app/1/fisnota",json_encode($chaveNfeEntrada));
    $NFE = json_decode($retorno,true);
    if (isset($NFE["conteudoSaida"][0])) { 
        $resposta = false;
    } else {
        $resposta = true; 
    }
    return $resposta;
}

function verificaEmpresa($conexaogeral, $conexao, $idEmpresa, $emitCpfCnpj, $destCpfCnpj)
{
    //Verifica se NFE é relacionada a empresa Padrão
    $sql_empresa = "SELECT empresa.idPessoa FROM empresa WHERE idEmpresa = $idEmpresa";
    $buscar_empresa = mysqli_query($conexaogeral, $sql_empresa);
    $row_empresa = mysqli_fetch_array($buscar_empresa, MYSQLI_ASSOC);

    $row_pessoa = buscaPessoa(null, $row_empresa["idPessoa"]);

    if ($emitCpfCnpj == $row_pessoa["cpfCnpj"] || $destCpfCnpj == $row_pessoa["cpfCnpj"]) {
        $resposta = true;
    } else {
        $resposta = true;
    }
    return $resposta;
}

function validaNota($tpNF, $finNFe)
{
    $resposta = true;

    if ($tpNF == 1 && $finNFe == 1) { 
        $resposta = true;
    }

    return $resposta;
}

// Pega XML puro
if (isset($jsonEntrada['xml'])) {

    $xmlArquivos = $jsonEntrada['xml'];
 

    foreach ($xmlArquivos as $xmlContent) {
        $xml = simplexml_load_string($xmlContent);
        $infNFe = $xml->NFe->infNFe;

        if ($infNFe == null) {
            $infNFe = $xml->nfeProc->NFe->infNFe;
        }

        if (isset($infNFe)) {



            //********************************************PESSOAS
            if (verificaEmpresa($conexaogeral, $conexao, $idEmpresa, (string) $infNFe->emit->CNPJ, (string) $infNFe->dest->CNPJ)) {
                if (validaNota((string) $infNFe->ide->tpNF,(string) $infNFe->ide->finNFe)) {
                    foreach ($infNFe->children() as $id => $dados) {
                        $campos = $dados->getName();
                        if ($campos == "emit" || $campos == "dest") {

                            if (isset($dados->CNPJ)) {
                                $cpfCnpj = isset($dados->CNPJ) && $dados->CNPJ !== "" ? (string) $dados->CNPJ : "null";
                                $tipoPessoa = "J";
                            } else {
                                $cpfCnpj = isset($dados->CPF) && $dados->CPF !== "" ? (string) $dados->CPF : "null";
                                $tipoPessoa = "F";
                            }

                            //Verifica se já tem Pessoa
                            $row_pessoa = buscaPessoa($cpfCnpj,null);
                            if (isset($row_pessoa['idPessoa'])) {
                                if ($campos == "emit") {
                                    $idPessoaEmitente = $row_pessoa["idPessoa"];
                                } elseif ($campos == "dest") {
                                    $idPessoaDestinatario = $row_pessoa["idPessoa"];
                                }
                            } else {
                                if (!buscaGeralPessoa($cpfCnpj)) {
                                    $dadosEnder = ($campos == "emit") ? $dados->enderEmit : $dados->enderDest;

                                    $geralPessoasEntrada = array(
                                        'cpfCnpj' => $cpfCnpj,
                                        'tipoPessoa' => $tipoPessoa,
                                        'nomePessoa' => (string) $dados->xNome,
                                        'nomeFantasia' => (string) $dados->xFant,
                                        'IE' => (string) $dados->IE,
                                        'municipio' => (string) $dadosEnder->xMun,
                                        'codigoCidade' => (string) $dadosEnder->cMun,
                                        'codigoEstado' => (string) $dadosEnder->UF,
                                        'pais' => (string) $dadosEnder->xPais,
                                        'bairro' => (string) $dadosEnder->xBairro,
                                        'endereco' => (string) $dadosEnder->xLgr,
                                        'endNumero' => (string) $dadosEnder->nro,
                                        'CEP' => (string) $dadosEnder->CEP,
                                        'telefone' => (string) $dadosEnder->fone,
                                        'CRT' => (string) $dados->CRT
                                    );

                                    $geralPessoasRetorno = chamaAPI(null, '/sistema/geralpessoas', json_encode($geralPessoasEntrada), 'PUT');
                                }

                                $pessoasEntrada = array(
                                    'cpfCnpj' => $cpfCnpj
                                );

                                $pessoasRetorno = chamaAPI(null, '/cadastros/pessoas', json_encode($pessoasEntrada), 'PUT');

                                if ($campos == "emit") {
                                    $idPessoaEmitente = $pessoasRetorno["idPessoa"];
                                } elseif ($campos == "dest") {
                                    $idPessoaDestinatario = $pessoasRetorno["idPessoa"];
                                }

                            }
                        }
                    }
                    //********************************************NOTA FISCAL
                    $chaveNFe = isset($infNFe['Id']) && $infNFe['Id'] !== "" && $infNFe['Id'] !== "" ? str_replace("NFe", "", $infNFe['Id']) : "null";
                    
                    if (buscaChaveNFE($chaveNFe)) {
                        $jsonSaida = array(
                            "status" => 400,
                            "retorno" => "NFe ja cadastrada"
                        );
                    } else {
                        $NF = isset($infNFe->ide->nNF) && $infNFe->ide->nNF !== "" ? (string) $infNFe->ide->nNF : "null";
                        $serie = isset($infNFe->ide->serie) && $infNFe->ide->serie !== "" ? (string) $infNFe->ide->serie : "null";
                        $dtEmissao = isset($infNFe->ide->dhEmi) && $infNFe->ide->dhEmi !== "" ? date('Y-m-d', strtotime($infNFe->ide->dhEmi)) : "null";
                        $naturezaOp = isset($infNFe->ide->natOp) && $infNFe->ide->natOp !== "" ? (string) $infNFe->ide->natOp : "null";
                        $modelo = isset($infNFe->ide->mod) && $infNFe->ide->mod !== "" ? (string) $infNFe->ide->mod : "null";
                        $idStatusNota = '0'; //Aberto

                        $vNF = isset($infNFe->total->ICMSTot->vNF) && $infNFe->total->ICMSTot->vNF !== "" ? (string) $infNFe->total->ICMSTot->vNF : "null";
                        $vProd = isset($infNFe->total->ICMSTot->vProd) && $infNFe->total->ICMSTot->vProd !== "" ? (string) $infNFe->total->ICMSTot->vProd : "null";
                        $vFrete = isset($infNFe->total->ICMSTot->vFrete) && $infNFe->total->ICMSTot->vFrete !== "" ? (string) $infNFe->total->ICMSTot->vFrete : "null";
                        $vSeg = isset($infNFe->total->ICMSTot->vSeg) && $infNFe->total->ICMSTot->vSeg !== "" ? (string) $infNFe->total->ICMSTot->vSeg : "null";
                        $vDesc = isset($infNFe->total->ICMSTot->vDesc) && $infNFe->total->ICMSTot->vDesc !== "" ? (string) $infNFe->total->ICMSTot->vDesc : "null";
                        $vOutro = isset($infNFe->total->ICMSTot->vOutro) && $infNFe->total->ICMSTot->vOutro !== "" ? (string) $infNFe->total->ICMSTot->vOutro : "null";
                        $XMLpath = isset($xml->protNFe->infProt->chNFe) && $xml->protNFe->infProt->chNFe !== "" ? $xml->protNFe->infProt->chNFe : "null";
                        
                        $notaEntrada = array(
                            "chaveNFe" => $chaveNFe,
                            "naturezaOp" => $naturezaOp,
                            "modelo" => $modelo,
                            "XML" => "/xampp/htdocs/xml/carregado_" . $XMLpath . ".xml",
                            "serie" => $serie,
                            "NF" => $NF,
                            "dtEmissao" => $dtEmissao,
                            "idPessoaEmitente" => $idPessoaEmitente,
                            "idPessoaDestinatario" => $idPessoaDestinatario,
                            "idStatusNota" => $idStatusNota,
                            "vNF" => $vNF,
                            "vProd" => $vProd,
                            "vFrete" => $vFrete,
                            "vSeg" => $vSeg,
                            "vDesc" => $vDesc,
                            "vOutro" => $vOutro
                        ); 

                        try {
                            $progr = new chamaprogress();
                            $retorno = $progr->executarprogress("impostos/app/1/fisnota_inserir",json_encode($notaEntrada));
                            fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
                            $conteudoSaida = json_decode($retorno,true);
                            if (isset($conteudoSaida["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
                                $jsonSaida = $conteudoSaida["conteudoSaida"][0];
                            } 
                        } 
                        catch (Exception $e) {
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
                        "retorno" => "NFE fora do padrão"
                    );
                }
            } else {
                $jsonSaida = array(
                    "status" => 400,
                    "retorno" => "Somente NFE da empresa Padrão é permitida"
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
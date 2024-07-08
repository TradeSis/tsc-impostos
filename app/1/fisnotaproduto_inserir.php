<?php
foreach ($infNFe->det as $item) {

    

    $nItem = isset($item['nItem']) && $item['nItem'] !== "" ? (string) $item['nItem'] : null ;
    $quantidade = isset($item->prod->qCom) && $item->prod->qCom !== "" ? (string) $item->prod->qCom : null ;
    $unidCom = isset($item->prod->uCom) && $item->prod->uCom !== "" ? (string) $item->prod->uCom : null ;
    $valorUnidade = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" ? (string) $item->prod->vUnCom : null ;
    $valorTotal = isset($item->prod->vProd) && $item->prod->vProd !== "" ? (string) $item->prod->vProd : null ;
    $cfop = isset($item->prod->CFOP) && $item->prod->CFOP !== "" ? (string) $item->prod->CFOP : null ;
    $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" ? (string) $item->prod->NCM : null ;
    $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" ? (string) $item->prod->CEST : null ;
    $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" ? (string) $item->prod->cEAN : null ;
    $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" ? (string) $item->prod->cProd : null ;

    
    if ($eanProduto == "'SEM GTIN'" || $eanProduto == "''") {
        $eanProduto = null ;
    }

    $dadosProduto = buscaProduto($idPessoaEmitente,$refProduto);
    if (isset($dadosProduto['idProduto'])) {

        $idProduto = $dadosProduto["idProduto"];

    } else {
        $nomeProduto = (string) $item->prod->xProd;

        if ($eanProduto == "NULL") {
            $geralproduto = buscaGeralProduto($eanProduto);
        } else {
            $geralproduto = buscaGeralProduto($nomeProduto);
        }
        if (!isset($geralproduto[0]['idNota'])) {
                
                $geralProdutosEntrada = array(
                    'eanProduto' => str_replace("'", "", $eanProduto),
                    'nomeProduto' => (string) $item->prod->xProd
                );
                        
                $geralProdutosRetorno = chamaAPI(null, '/sistema/geralprodutos', json_encode($geralProdutosEntrada), 'PUT');
                $idGeralProduto = $geralProdutosRetorno['idGeralProduto'];

                //**GeralFornecimento
                $dadosPessoa = buscaPessoa(null,$idPessoaEmitente);

                $geralFornecimentoEntrada = array(
                    'Cnpj' => $dadosPessoa["cpfCnpj"],
                    'refProduto' => str_replace("'", "", $refProduto),
                    'idGeralProduto' => $idGeralProduto,
                    'valorCompra' => (string) $item->prod->vUnCom
                );
                        
                $geralFornecimentoRetorno = chamaAPI(null, '/sistema/geralfornecimento', json_encode($geralFornecimentoEntrada), 'PUT');
            }
        $produEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idGeralProduto' => $idGeralProduto,
            'idPessoaFornecedor' => $idPessoaEmitente,
            'refProduto' => str_replace("'", "", $refProduto),
            'nomeProduto' => (string) $item->prod->xProd,
            'valorCompra' => (string) $item->prod->vUnCom,
            'codigoNcm' => (string) $item->prod->NCM,
            'codigoCest' => (string) $item->prod->CEST
        );
        $produRetorno = chamaAPI(null, '/cadastros/produtos', json_encode($produEntrada), 'PUT');
        $idProduto = $produRetorno['idProduto'];

    }
        $fisnotaprodutoEntrada = array(
            "idNota" => $idNota,
            "nItem" => $nItem,
            "idProduto" => (string) $idProduto,
            "quantidade" => $quantidade,
            "unidCom" => $unidCom,
            "valorUnidade" => $valorUnidade,
            "valorTotal" => $valorTotal,
            "cfop" => $cfop,
            "codigoNcm" => $codigoNcm,
            "codigoCest" => $codigoCest
        ); 
        $progr = new chamaprogress();
        $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduto_inserir",json_encode($fisnotaprodutoEntrada));
        fwrite($arquivo,$identificacao."-NOTAPRODUTO_RETORNO->".$retorno."\n");


    //********************************************FISNOTAPRODUTOSIMPOSTO
    if (isset($item->imposto)) {
        $vTotTribIMPOSTO = isset($item->imposto->vTotTrib) && $item->imposto->vTotTrib !== "" ? (string) $item->imposto->vTotTrib : null ;
        foreach ($item->imposto->children() as $filho) {
            $imposto = $filho->getName();
            if ($filho->getName() === "IPI") {
                foreach ($filho->children() as $ipi) {
                    if ($ipi->getName() !== "cEnq") {
                        $nomeImposto = $ipi->getName();
                        $campos = $ipi;
                    }
                }
            } else {
                $nomeImposto = $filho->children()->count() > 0 ? $filho->children()->getName() : null;
                $campos = $filho->$nomeImposto;
            }

            if ($imposto == "ICMS" ) {
                $orig = isset($campos->orig) ? (string) $campos->orig : null ;
                $CSOSN = isset($campos->CSOSN) ? (string) $campos->CSOSN : null ;
                $modBCST = isset($campos->modBCST) ? (string) $campos->modBCST : null ;
                $pMVAST = isset($campos->pMVAST) ? (string) $campos->pMVAST : null ;
                $vBCST = isset($campos->vBCST) ? (string) $campos->vBCST : null ;
                $pICMSST = isset($campos->pICMSST) ? (string) $campos->pICMSST : null ;
                $vICMSST = isset($campos->vICMSST) ? (string) $campos->vICMSST : null ;
                $CST = isset($campos->CST) ? (string) $campos->CST : null ;
                $modBC = isset($campos->modBC) ? (string) $campos->modBC : null ;
                $vBC = isset($campos->vBC) ? (string) $campos->vBC : null ;
                $pICMS = isset($campos->pICMS) ? (string) $campos->pICMS : null ;
                $vICMS = isset($campos->vICMS) ? (string) $campos->vICMS : null ;

                $fisnotaproduicmsEntrada = array(
                    "idNota" => $idNota,
                    "nItem" => $nItem,
                    "imposto" => $imposto,
                    "nomeImposto" => $nomeImposto,
                    "vTotTrib" => $vTotTribIMPOSTO,
                    "orig" => $orig,
                    "CSOSN" => $CSOSN,
                    "modBCST" => $modBCST,
                    "pMVAST" => $pMVAST,
                    "vBCST" => $vBCST,
                    "pICMSST" => $pICMSST,
                    "vICMSST" => $vICMSST,
                    "CST" => $CST,
                    "modBC" => $modBC,
                    "vBC" => $vBC,
                    "pICMS" => $pICMS,
                    "vICMS" => $vICMS
                ); 
                $progr = new chamaprogress();
                $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduicms_inserir",json_encode($fisnotaproduicmsEntrada));
                fwrite($arquivo,$identificacao."-ICMS_RETORNO->".$retorno."\n");

            } else {

                $cEnq = isset($filho->cEnq) ? (string) $filho->cEnq : null ;
                $CST = isset($campos->CST) ? (string) $campos->CST : null ;
                $vBC = isset($campos->vBC) ? (string) $campos->vBC : null ;

                $percentual = isset($campos->{"p".$filho->getName()}) ? (string) $campos->{"p".$filho->getName()} : null ;
                $valor = isset($campos->{"v".$filho->getName()}) ? (string) $campos->{"v".$filho->getName()} : null ;


                $fisnotaproduimpostoEntrada = array(
                    "idNota" => $idNota,
                    "nItem" => $nItem,
                    "imposto" => $imposto,
                    "nomeImposto" => $nomeImposto,
                    "cEnq" => $cEnq,
                    "CST" => $CST,
                    "vBC" => $vBC,
                    "percentual" => $percentual,
                    "valor" => $valor
                ); 
                $progr = new chamaprogress();
                $retorno = $progr->executarprogress("impostos/app/1/fisnotaproduimposto_inserir",json_encode($fisnotaproduimpostoEntrada));
                fwrite($arquivo,$identificacao."-IMPOSTO_RETORNO->".$retorno."\n");

            }

        }
    }
}

?>
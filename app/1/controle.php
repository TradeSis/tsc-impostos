<?php

//echo "metodo=".$metodo."\n";
//echo "funcao=".$funcao."\n";
//echo "parametro=".$parametro."\n";

if ($metodo == "GET") {

  switch ($funcao) {

      case "fisatividade":
      include 'fisatividade.php';
      break;

    case "fisprocesso":
      include 'fisprocesso.php';
      break;

    case "fisnatureza":
      include 'fisnatureza.php';
      break;

    case "fisoperacao":
      include 'fisoperacao.php';
      break;

    case "ncm":
      include 'ncm.php';
      break;

    case "cest":
      include 'cest.php';
      break;

    case "fisnota":
      include 'fisnota.php';
      break;

    case "fisnotaproduto":
      include 'fisnotaproduto.php';
      break;

    case "fisnotaproduto_itens":
      include 'fisnotaproduto_itens.php';
      break;

    case "fisnotaproduimposto":
      include 'fisnotaproduimposto.php';
      break;

    case "fisnotaproduicms":
      include 'fisnotaproduicms.php';
      break;

    case "fisnotatotal":
      include 'fisnotatotal.php';
      break;

    case "regrafiscal":
      include 'regrafiscal.php';
      break;

    case "operacaofiscal":
      include 'operacaofiscal.php';
      break;

    case "fisnotastatus":
      include 'fisnotastatus.php';
      break;

    case "fishistorico":
      include 'fishistorico.php';
      break;

    case "produto_operacao";
      include 'produto_operacao.php';
    break;

    case "fisnotaproduto-imp";
      include 'fisnotaproduto-imp.php';
    break;

    case "impostos-calculo";
      include 'impostos-calculo.php';
    break;
    
    case "caractrib":
      include 'caractrib.php';
      break;
    
    case "cnaeClasse":
      include 'cnaeClasse.php';
      break;  

    case "cnaeSecao":
      include 'cnaeSecao.php';
      break;  

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "PUT") {
  switch ($funcao) {

    case "fisatividade":
      include 'fisatividade_inserir.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_inserir.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_inserir.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_inserir.php';
      break;

    case "fisnota":
      include 'fisnota_importar.php';
      break;

    case "nfepessoa":
      include 'nfepessoa_inserir.php';
      break;

    case "nfefisnotaproduto":
      include 'nfefisnotaproduto_inserir.php';
      break;

    case "nfeprodutos":
      include 'nfeprodutos_inserir.php';
      break;

    case "fisnotaproduimposto":
      include 'fisnotaproduimposto_inserir.php';
      break;

    case "regrafiscal":
      include 'regrafiscal_inserir.php';
      break;

    case "operacaofiscal":
      include 'operacaofiscal_inserir.php';
      break;

    case "fisnotastatus":
      include 'fisnotastatus_inserir.php';
      break;

    case "fishistorico":
      include 'fishistorico_inserir.php';
      break;

    case "caractrib":
      include 'caractrib_inserir.php';
      break;

    case "cnaeSecao":
      include 'cnaeSecao_inserir.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "POST") {
  if ($funcao == "imendes" && $parametro == "saneamento") {
    $funcao = "imendes/saneamento";
    $parametro = null;
  }
  switch ($funcao) {

    case "fisatividade":
      include 'fisatividade_alterar.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_alterar.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_alterar.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_alterar.php';
      break;

   case "imendes/saneamento":
        include 'imendes_saneamento.php';
        break;

    case "fisnota":
          include 'fisnota_processar.php';
          break;
    
    case "fisnotastatus":
          include 'fisnotastatus_alterar.php';
          break;
    
    case "regrafiscal":
      include 'regrafiscal_alterar.php';
      break;

    case "fisnota_processargeral":
      include 'fisnota_processargeral.php';
      break;

    case "imendes_saneamentoGeral":
      include 'imendes_saneamentoGeral.php';
      break;

    case "caractrib":
      include 'caractrib_alterar.php';
      break;
      
    case "cnaeSecao":
      include 'cnaeSecao_alterar.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "DELETE") {
  switch ($funcao) {
    
    case "fisatividade":
      include 'fisatividade_excluir.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_excluir.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_excluir.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_excluir.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

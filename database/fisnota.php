<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";

function buscarCarga($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa,
		'statusNota' => "carga"
	);
	$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');
	return $notas;
}

function buscarNota($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa
	);
	$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');
	return $notas;
}

function buscarNotaProduto($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idNota' => $idNota,
		'nitem' => null
	);
	$notas = chamaAPI(null, '/impostos/fisnotaproduto', json_encode($apiEntrada), 'GET');
	return $notas;
}

function buscarNotaImpostos($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa
	);
	$notas = chamaAPI(null, '/impostos/fisnotatotal', json_encode($apiEntrada), 'GET');
	return $notas;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
		$xmlArquivos = array();
	
		foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
			$xmlArquivo = file_get_contents($tmpName);
    		$xmlArquivos[] = $xmlArquivo;
		}
	
		// Envia XML puro
		$apiEntrada = array(
			'xml' => $xmlArquivos,
			'idEmpresa' => $_SESSION['idEmpresa'],
		);
	
		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');

		/* $arquivo = fopen("C:TRADESIS/tmp/LOG.txt", "a");
		fwrite($arquivo, json_encode($nfe) . "\n");
		fclose($arquivo); */
		echo json_encode($nfe);
		return $nfe;
	}

	if ($operacao == "processarXML") {
		$idNota = isset($_POST["idNota"]) ? $_POST["idNota"] : null;

        if ($idNota == "") {
			$idNota = null;
		}

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $idNota
		);
	
		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'POST');
	
		echo json_encode($nfe);
		return $nfe;
	}

	if ($operacao == "processarGeral") {

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa']
		);
	
		$nfe = chamaAPI(null, '/impostos/fisnota_processargeral', json_encode($apiEntrada), 'POST');
	
		echo json_encode($nfe);
		return $nfe;
	}

	if ($operacao == "buscarNota") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
		);
		
		$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');

		echo json_encode($notas);
		return $notas;
	}

	if ($operacao == "buscarNotaProduto") {
		$nitem = isset($_POST["nitem"]) && $_POST["nitem"] !== ""  ? $_POST["nitem"]  : null;
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
			'nitem' => $nitem
		);
		
		$produ = chamaAPI(null, '/impostos/fisnotaproduto', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "buscaItemNotas") {
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'itemnota' => $_POST['itemnota']
		);
		
		$produ = chamaAPI(null, '/impostos/fisnotaproduto_itens', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "buscarNotaProdutoGrupo") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idGrupo' => null,
			'codigoGrupo' => null,
			'buscaGrupoProduto' => null,
			'idGeralProduto' => $_POST['idGeralProduto']

		);
		
		$produ = chamaAPI(null, '/admin/grupoproduto', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "buscarNotaProdutoOperacao") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idGeralProduto' => $_POST['idGeralProduto'],
			'cpfCnpj' => $_POST['cpfCnpj']
		);
		
		$produ = chamaAPI(null, '/impostos/produto_operacao', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "buscarProdutoImposto") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
			'nItem' => $_POST['nItem']
		);
		
		$produ = chamaAPI(null, '/impostos/fisnotaproduto-imp', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "filtrar") {
		$anoImposto = isset($_POST["anoImposto"]) && $_POST["anoImposto"] !== "null"  ? $_POST["anoImposto"]  : null;
		$mesImposto = isset($_POST["mesImposto"]) && $_POST["mesImposto"] !== "null"  ? $_POST["mesImposto"]  : null;
	
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'statusNota' => "notas",
			'anoImposto' => $anoImposto,
			'mesImposto' => $mesImposto,
			
		);
		
		$produ = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}


}
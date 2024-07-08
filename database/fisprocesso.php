<?php
// gabriel 060623 15:06

include_once __DIR__ . "/../conexao.php";

function buscaProcesso($idProcesso=null)
{
	
	$processos = array();
	$apiEntrada = array(
		'idProcesso' => $idProcesso,
	);
	$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'GET');
	return $processos;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeProcesso' => $_POST['nomeProcesso']
		);
		$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idProcesso' => $_POST['idProcesso'],
			'nomeProcesso' => $_POST['nomeProcesso']
		);
		$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'POST');
	}
	
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idProcesso' => $_POST['idProcesso']
		);
		$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {
		$apiEntrada = array(
			'idProcesso' => $_POST['idProcesso']
		);
		$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'GET');

		echo json_encode($processos);
		return $processos;
	}

	if ($operacao == "filtrar") {

		$buscaProcesso = $_POST["buscaProcesso"];

		if ($buscaProcesso == "") {
			$buscaProcesso = null;
		}

		$apiEntrada = array(
			'idProcesso' => $buscaProcesso
		);

		$processos = chamaAPI(null, '/impostos/fisprocesso', json_encode($apiEntrada), 'GET');

		echo json_encode($processos);
		return $processos;
	}


	header('Location: ../configuracao?stab=fisprocesso');
	
}

?>


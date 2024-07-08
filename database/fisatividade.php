<?php
// gabriel 060623 15:06

include_once __DIR__ . "/../conexao.php";

function buscaAtividade($idAtividade=null)
{
	
	$atividade = array();
	$apiEntrada = array(
		'idAtividade' => $idAtividade,
	);
	$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'GET');
	return $atividade;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeAtividade' => $_POST['nomeAtividade']
		);
		$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idAtividade' => $_POST['idAtividade'],
			'nomeAtividade' => $_POST['nomeAtividade']
		);
		$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'POST');
	}
	
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idAtividade' => $_POST['idAtividade']
		);
		$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {
		$apiEntrada = array(
			'idAtividade' => $_POST['idAtividade']
		);
		$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'GET');

		echo json_encode($atividade);
		return $atividade;
	}

	if ($operacao == "filtrar") {

		$buscaAtividade = $_POST["buscaAtividade"];

		if ($buscaAtividade == "") {
			$buscaAtividade = null;
		}

		$apiEntrada = array(
			'idAtividade' => $buscaAtividade
		);

		$atividade = chamaAPI(null, '/impostos/fisatividade', json_encode($apiEntrada), 'GET');

		echo json_encode($atividade);
		return $atividade;
	}


	
	header('Location: ../configuracao?stab=fisatividade');
	
}

?>


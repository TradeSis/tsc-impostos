<?php
// gabriel 060623 15:06

include_once __DIR__ . "/../conexao.php";

function buscaNatureza($idNatureza=null)
{
	
	$natureza = array();
	$apiEntrada = array(
		'idNatureza' => $idNatureza,
	);
	$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'GET');
	return $natureza;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeNatureza' => $_POST['nomeNatureza']
		);
		$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idNatureza' => $_POST['idNatureza'],
			'nomeNatureza' => $_POST['nomeNatureza']
		);
		$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'POST');
	}
	
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idNatureza' => $_POST['idNatureza']
		);
		$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {
		$apiEntrada = array(
			'idNatureza' => $_POST['idNatureza']
		);
		$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'GET');

		echo json_encode($natureza);
		return $natureza;
	}

	if ($operacao == "filtrar") {

		$buscaNatureza = $_POST["buscaNatureza"];

		if ($buscaNatureza == "") {
			$buscaNatureza = null;
		}

		$apiEntrada = array(
			'idNatureza' => $buscaNatureza
		);

		$natureza = chamaAPI(null, '/impostos/fisnatureza', json_encode($apiEntrada), 'GET');

		echo json_encode($natureza);
		return $natureza;
	}


	header('Location: ../configuracao?stab=fisnatureza');
	
}

?>


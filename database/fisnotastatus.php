<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql
// helio 26012023 16:16

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";

function buscaStatusNota($idStatusNota=null)
{
	
	$statusnota = array();

	$apiEntrada = array(
		'idStatusNota' => $idStatusNota
		
	);
	$statusnota = chamaAPI(null, '/impostos/fisnotastatus', json_encode($apiEntrada), 'GET');
	return $statusnota;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeStatusNota' => $_POST['nomeStatusNota']
		);
		$statusnota = chamaAPI(null, '/impostos/fisnotastatus', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idStatusNota' => $_POST['idStatusNota'],
			'nomeStatusNota' => $_POST['nomeStatusNota']
		);
		$statusnota = chamaAPI(null, '/impostos/fisnotastatus', json_encode($apiEntrada), 'POST');

	}

    if ($operacao == "buscarStatusNota") {
        $idStatusNota = isset($_POST["idStatusNota"]) ? $_POST["idStatusNota"] : null;
		$nomeStatusNota = isset($_POST["nomeStatusNota"]) ? $_POST["nomeStatusNota"] : null;

        if ($idStatusNota == "") {
			$idStatusNota = null;
		}

        if ($nomeStatusNota == "") {
			$nomeStatusNota = null;
		}

        $apiEntrada = array(
            'idStatusNota' => $idStatusNota,
            'nomeStatusNota' => $nomeStatusNota
		);
		
		$statusnota = chamaAPI(null, '/impostos/fisnotastatus', json_encode($apiEntrada), 'GET');

		echo json_encode($statusnota);
		return $statusnota;
	}


    header('Location: ../configuracao?stab=fisnotastatus');	
	
	
}

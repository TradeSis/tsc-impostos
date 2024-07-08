<?php
//Lucas 26122023 criado
include_once __DIR__ . "/../conexao.php";

function buscaCaracTrib($caracTrib = null)
{

	$response = array();

	$apiEntrada = 
		array("dadosEntrada" => array(
			array('caracTrib' => $caracTrib)
		));
	$response = chamaAPI(null, '/impostos/caractrib', json_encode($apiEntrada), 'GET');
	return $response;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('caracTrib' => $_POST["caracTrib"],
				  'descricaoCaracTrib' => $_POST["descricaoCaracTrib"])
		));
	

		$response = chamaAPI(null, '/impostos/caractrib', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao == "alterar") {

		$apiEntrada =
		array("dadosEntrada" => array(
			array('caracTrib' => $_POST["caracTrib"],
				  'descricaoCaracTrib' => $_POST["descricaoCaracTrib"])
		));

		
		$response = chamaAPI(null, '/impostos/caractrib', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "buscar") {

		$caracTrib = isset($_POST["caracTrib"])  && $_POST["caracTrib"] !== "" && $_POST["caracTrib"] !== "null" ? $_POST["caracTrib"]  : null;

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('caracTrib' => $caracTrib)
		));

		$response = chamaAPI(null, '/impostos/caractrib', json_encode($apiEntrada), 'GET');

		echo json_encode($response);
		return $response;
	}


}

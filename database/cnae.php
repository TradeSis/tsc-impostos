<?php
//Lucas 26122023 criado
include_once __DIR__ . "/../conexao.php";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
	$identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "cnae";
	$arquivo = fopen(defineCaminhoLog() . "impostos_" . date("dmY") . ".log", "a");
}
//LOG


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	
	if ($operacao == "buscaClasse") {
		$apiEntrada = array(
			'cnaeID' => $_POST['cnaeID']
		);
		$cnaeClasse = chamaAPI(null, '/impostos/cnaeClasse', json_encode($apiEntrada), 'GET');
		
		echo json_encode($cnaeClasse);
		return $cnaeClasse;
	}

	if ($operacao == "alterarSecao") {

		$apiEntrada =
		array("dadosEntrada" => array(
			array('idcnSecao' => $_POST["idcnSecao"],
				  'caracTrib' => $_POST["caracTrib"])
		));

		
		$response = chamaAPI(null, '/impostos/cnaeSecao', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "buscarSecao") {

		$idcnSecao = isset($_POST["idcnSecao"])  && $_POST["idcnSecao"] !== "" && $_POST["idcnSecao"] !== "null" ? $_POST["idcnSecao"]  : null;

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('idcnSecao' => $idcnSecao)
		));

		$response = chamaAPI(null, '/impostos/cnaeSecao', json_encode($apiEntrada), 'GET');

		echo json_encode($response);
		return $response;
	}

	if ($operacao == "atualizaSecao") {

		$secoes = chamaAPI('https://servicodados.ibge.gov.br', '/api/v2/cnae/secoes', null, 'GET');

		foreach ($secoes as $secao) {
			$apiEntrada = 
			array("dadosEntrada" => array(
				array('idcnSecao' => $secao['id'],
					  'descricao' => $secao['descricao'])
			));
			$response = chamaAPI(null, '/impostos/cnaeSecao', json_encode($apiEntrada), 'PUT');
		} 

		echo json_encode($secoes);
		return $secoes;
	}


}
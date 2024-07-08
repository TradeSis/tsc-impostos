<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";


function buscaCest($nomeCest = null, $codigoCest = null, $codigoNcm = null)
{
	$cest = array();

	$apiEntrada = array(
		'nomeCest' => $nomeCest,
		'codigoCest' => $codigoCest,
		'codigoNcm' => $codigoNcm

	);

	$cest = chamaAPI(null, '/impostos/cest', json_encode($apiEntrada), 'GET');

	return $cest;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	if ($operacao == "filtrar") {

		$FiltroTipoCest = $_POST["FiltroTipoCest"];
		$dadosCest = $_POST["dadosCest"];

		if ($FiltroTipoCest == "") {
			$FiltroTipoCest = null;
		}

		if ($dadosCest == "") {
			$dadosCest = null;
		}

		$apiEntrada = array(

			'FiltroTipoCest' => $FiltroTipoCest,
			'dadosCest' => $dadosCest
		);

		$_SESSION['filtro_cest'] = $apiEntrada;
		//echo json_encode(($apiEntrada));
		/* return; */
		$cest = chamaAPI(null, '/impostos/cest', json_encode($apiEntrada), 'GET');

		echo json_encode($cest);
		return $cest;

	}



}

?>
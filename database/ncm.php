<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";

function buscaNCM($Descricao = null, $codigoNcm = null)
{

	$ncm = array();

	$apiEntrada = array(
		'Descricao' => $Descricao,
		'codigoNcm' => $codigoNcm

	);

	/* echo json_encode($apiEntrada);
	return; */
	$ncm = chamaAPI(null, '/impostos/ncm', json_encode($apiEntrada), 'GET');

	return $ncm;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	if ($operacao == "filtrar") {

		$FiltroTipoNcm = $_POST["FiltroTipoNcm"];
		$dadosNcm = $_POST["dadosNcm"];

		$apiEntrada = array(

			'FiltroTipoNcm' => $FiltroTipoNcm,
			'dadosNcm' => $dadosNcm
		);

		$_SESSION['filtro_ncm'] = $apiEntrada;
		//echo json_encode(($apiEntrada));
		/* return; */
		$ncm = chamaAPI(null, '/impostos/ncm', json_encode($apiEntrada), 'GET');

		echo json_encode($ncm);
		return $ncm;

	}



}

?>
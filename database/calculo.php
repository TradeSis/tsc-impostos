<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "filtrar") {
		$anoImposto = isset($_POST["anoImposto"]) && $_POST["anoImposto"] !== "null"  ? $_POST["anoImposto"]  : null;
		$mesImposto = isset($_POST["mesImposto"]) && $_POST["mesImposto"] !== "null"  ? $_POST["mesImposto"]  : null;
		$FiltroImposto = isset($_POST["FiltroImposto"]) && $_POST["FiltroImposto"] !== "null"  ? $_POST["FiltroImposto"]  : null;
		$porcst = isset($_POST["porcst"]) && $_POST["porcst"] == "false"  ? FALSE  : TRUE;

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'anoImposto' => $anoImposto,
			'mesImposto' => $mesImposto,
			'imposto' => $FiltroImposto,
			'porcst' => $porcst
		);
		
		$calculo = chamaAPI(null, '/impostos/impostos-calculo', json_encode($apiEntrada), 'GET');

		echo json_encode($calculo);
		return $calculo;
	}


}
<?php
// lucas 08032024 - id876 passagem para progress
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {

		$apiEntrada = array(
			'idGrupo' => $_POST['idGrupo'],
			'codigoEstado' => $_POST['codigoEstado'],
			'cFOP' => $_POST['cFOP'],
			'codigoCaracTrib' => $_POST['codigoCaracTrib'],
			'finalidade' => $_POST['finalidade'],
			'idRegra' => $_POST['idRegra'],
		);

		$operacao = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'PUT');
		return $operacao;

	}

	// lucas 08032024 - id876 removido operacao buscar (sem uso)

	if ($operacao == "filtrar") {
		// lucas 08032024 - id876 alterado teste de entrada
		$idoperacaofiscal = isset($_POST["idoperacaofiscal"])  && $_POST["idoperacaofiscal"] !== "" && $_POST["idoperacaofiscal"] !== "null" ? $_POST["buscaGrupoProduto"]  : null;
	
		$apiEntrada = array(
			'idoperacaofiscal' => $idoperacaofiscal
		);
		
		$operacao = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($operacao);
		return $operacao;

	}



}

?>
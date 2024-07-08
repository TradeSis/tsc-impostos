<?php
// lucas 20032024 - criado

include_once __DIR__ . "/../conexao.php";

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'dtHistorico' => $_POST['dtHistorico'],
			'sugestao' => $_POST['sugestao'],
			'amb' => $_POST['amb'],
			'cnpj' => $_POST['cnpj'],
			'dthr' => $_POST['dthr'],
			'transacao' => $_POST['transacao'],
			'mensagem' => $_POST['mensagem'],
			'prodEnv' => $_POST['prodEnv'],
			'prodRet' => $_POST['prodRet'],
			'prodNaoRet' => $_POST['prodNaoRet'],
			'comportamentosParceiro' => $_POST['comportamentosParceiro'],
			'comportamentosCliente' => $_POST['comportamentosCliente'],
			'versao' => $_POST['versao'],
			'duracao' => $_POST['duracao'],
		);
		$historico = chamaAPI(null, '/impostos/fishistorico', json_encode($apiEntrada), 'PUT');
		return $historico;

	}

	

	if ($operacao == "buscar") {
		$idHistorico = isset($_POST["idHistorico"])  && $_POST["idHistorico"] !== "" && $_POST["idHistorico"] !== "null" ? $_POST["idHistorico"]  : null;
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idHistorico' => $idHistorico,
		);
		$historico = chamaAPI(null, '/impostos/fishistorico', json_encode($apiEntrada), 'GET');

		echo json_encode($historico);
		return $historico;
	}

}

?>


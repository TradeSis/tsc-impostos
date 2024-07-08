<?php
// gabriel 060623 15:06

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";

function buscaOperacao($idOperacao = null)
{

	$operacao = array();
	$apiEntrada = array(
		'idOperacao' => $idOperacao,
	);
	$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'GET');
	return $operacao;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
		$apiEntrada = array(
			'nomeOperacao' => $_POST['nomeOperacao'],
			'idAtividade' => $_POST['idAtividade'],
			'idProcesso' => $_POST['idProcesso'],
			'idNatureza' => $_POST['idNatureza'],
			'idGrupoOper' => $_POST['idGrupoOper'],
			'idEntSai' => $_POST['idEntSai'],
			'xfop' => $_POST['xfop'],
			'cfopOposto' => $_POST['cfopOposto']
		);
		$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao == "alterar") {
		$apiEntrada = array(
			'idOperacao' => $_POST['idOperacao'],
			'nomeOperacao' => $_POST['nomeOperacao'],
			'idAtividade' => $_POST['idAtividade'],
			'idProcesso' => $_POST['idProcesso'],
			'idNatureza' => $_POST['idNatureza'],
			'idGrupoOper' => $_POST['idGrupoOper'],
			'idEntSai' => $_POST['idEntSai'],
			'xfop' => $_POST['xfop'],
			'cfopOposto' => $_POST['cfopOposto']
		);
		$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "excluir") {
		$apiEntrada = array(
			'idOperacao' => $_POST['idOperacao']
		);
		$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "filtrar") {

		$idOperacao = isset($_POST["idOperacao"]) ? $_POST["idOperacao"] : null;
		$FiltroTipoOp = isset($_POST["FiltroTipoOp"]) ? $_POST["FiltroTipoOp"] : null;
    	$dadosOp = isset($_POST["dadosOp"]) ? $_POST["dadosOp"] : null;
		$idAtividade = isset($_POST["idAtividade"]) ? $_POST["idAtividade"] : null;
    	$idProcesso = isset($_POST["idProcesso"]) ? $_POST["idProcesso"] : null;
    	$idNatureza = isset($_POST["idNatureza"]) ? $_POST["idNatureza"] : null;

		if ($FiltroTipoOp == "") {
			$FiltroTipoOp = null;
		}

		if ($dadosOp == "") {
			$dadosOp = null;
		}

		if ($idAtividade == "") {
			$idAtividade = null;
		}

		if ($idProcesso == "") {
			$idProcesso = null;
		}

		if ($idNatureza == "") {
			$idNatureza = null;
		}  
		if ($idOperacao == "") {
			$idOperacao = null;
		} 

		$apiEntrada = array(
			'FiltroTipoOp' => $FiltroTipoOp,
			'dadosOp' => $dadosOp,
			'idAtividade' => $idAtividade,
			'idProcesso' => $idProcesso,
			'idNatureza' => $idNatureza, 
			'idOperacao' => $idOperacao
		);

		$_SESSION['filtro_operacao'] = $apiEntrada;

		$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'GET');

		echo json_encode($operacao);
		return $operacao;

	}
	if ($operacao == "buscar") {
		$apiEntrada = array(
			'idOperacao' => $_POST['idOperacao']
		);
		$operacao = chamaAPI(null, '/impostos/fisoperacao', json_encode($apiEntrada), 'GET');

		echo json_encode($operacao);
		return $operacao;
	}




	header('Location: ../operacoes/fisoperacao.php');

}

?>
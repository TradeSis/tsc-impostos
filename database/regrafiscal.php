<?php
// lucas 08032024 - id876 passagem para progress
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";

function buscaCodigoRegra()
{

	$regra = array();

	$apiEntrada = array(
		'idRegra' => null
	);
	$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');
	return $regra;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {

		$apiEntrada = array(
			'codRegra' => $_POST['codRegra'],
			'codExcecao' => $_POST['codExcecao'],
			'dtVigIni' => $_POST['dtVigIni'],
			'dtVigFin' => $_POST['dtVigFin'],
			'cFOPCaracTrib' => $_POST['cFOPCaracTrib'],
			'cST' => $_POST['cST'],
			'cSOSN' => $_POST['cSOSN'],
			'aliqIcmsInterna' => $_POST['aliqIcmsInterna'],
			'aliqIcmsInterestadual' => $_POST['aliqIcmsInterestadual'],
			'reducaoBcIcms' => $_POST['reducaoBcIcms'],
			'reducaoBcIcmsSt' => $_POST['reducaoBcIcmsSt'],
			'redBcICMsInterestadual' => $_POST['redBcICMsInterestadual'],
			'aliqIcmsSt' => $_POST['aliqIcmsSt'],
			'iVA' => $_POST['iVA'],
			'iVAAjust' => $_POST['iVAAjust'],
			'fCP' => $_POST['fCP'],
			'codBenef' => $_POST['codBenef'],
			'pDifer' => $_POST['pDifer'],
			'pIsencao' => $_POST['pIsencao'],
			'antecipado' => $_POST['antecipado'],
			'desonerado' => $_POST['desonerado'],
			'pICMSDeson' => $_POST['pICMSDeson'],
			'isento' => $_POST['isento'],
			'tpCalcDifal' => $_POST['tpCalcDifal'],
			'ampLegal' => $_POST['ampLegal'],
			'Protocolo' => $_POST['Protocolo'],
			'Convenio' => $_POST['Convenio'],
			'regraGeral' => $_POST['regraGeral'],
		);

		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'PUT');
		return $regra;

	}

	if ($operacao=="alterar") {

		$apiEntrada = array(
			'idRegra' => $_POST['idRegra'],
			'codRegra' => $_POST['codRegra'],
			'codExcecao' => $_POST['codExcecao'],
			'dtVigIni' => $_POST['dtVigIni'],
			'dtVigFin' => $_POST['dtVigFin'],
			'cFOPCaracTrib' => $_POST['cFOPCaracTrib'],
			'cST' => $_POST['cST'],
			'cSOSN' => $_POST['cSOSN'],
			'aliqIcmsInterna' => $_POST['aliqIcmsInterna'],
			'aliqIcmsInterestadual' => $_POST['aliqIcmsInterestadual'],
			'reducaoBcIcms' => $_POST['reducaoBcIcms'],
			'reducaoBcIcmsSt' => $_POST['reducaoBcIcmsSt'],
			'redBcICMsInterestadual' => $_POST['redBcICMsInterestadual'],
			'aliqIcmsSt' => $_POST['aliqIcmsSt'],
			'iVA' => $_POST['iVA'],
			'iVAAjust' => $_POST['iVAAjust'],
			'fCP' => $_POST['fCP'],
			'codBenef' => $_POST['codBenef'],
			'pDifer' => $_POST['pDifer'],
			'pIsencao' => $_POST['pIsencao'],
			'antecipado' => $_POST['antecipado'],
			'desonerado' => $_POST['desonerado'],
			'pICMSDeson' => $_POST['pICMSDeson'],
			'isento' => $_POST['isento'],
			'tpCalcDifal' => $_POST['tpCalcDifal'],
			'ampLegal' => $_POST['ampLegal'],
			'Protocolo' => $_POST['Protocolo'],
			'Convenio' => $_POST['Convenio'],
			'regraGeral' => $_POST['regraGeral'],
		);

		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'POST');
		return $regra;

	}
	// lucas 08032024 - id876 união das operações filtrar e buscar
	if ($operacao == "buscar") {
		// lucas 08032024 - id876 alterado teste de entrada
		$idRegra = isset($_POST["idRegra"])  && $_POST["idRegra"] !== "" && $_POST["idRegra"] !== "null" ? $_POST["idRegra"]  : null;
		$codRegra = isset($_POST["codRegra"])  && $_POST["codRegra"] !== "" && $_POST["codRegra"] !== "null" ? $_POST["codRegra"]  : null;
		
		$apiEntrada = array(
			'idRegra' => $idRegra,
			'codRegra' => $codRegra,
		);
		
		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;
	}



}

?>
<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$conexaogeral = conectaMysql(null);
$notas = array();

$sql = "SELECT fisnota.*, 
        emitente.cpfCnpj AS emitente_cpfCnpj, destinatario.cpfCnpj AS destinatario_cpfCnpj FROM fisnota
        LEFT JOIN pessoas AS emitente ON fisnota.idPessoaEmitente = emitente.idPessoa
        LEFT JOIN pessoas AS destinatario ON fisnota.idPessoaDestinatario = destinatario.idPessoa";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
  $sql = $sql . $where . " fisnota.idNota = " . $jsonEntrada["idNota"];
  $where = " and ";
}

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  $emitente_cpfCnpj = $row['emitente_cpfCnpj'];
  $destinatario_cpfCnpj = $row['destinatario_cpfCnpj'];
  $idStatusNota = $row['idStatusNota'];

  $sql_emitente = "SELECT geralpessoas.* FROM geralpessoas WHERE geralpessoas.cpfCnpj = $emitente_cpfCnpj";
  $buscar_emitente = mysqli_query($conexaogeral, $sql_emitente);

  while ($row_emitente = mysqli_fetch_array($buscar_emitente, MYSQLI_ASSOC)) {
      foreach ($row_emitente as $dadosRow => $dadosEmitente) {
          $row["emitente_" . $dadosRow] = $dadosEmitente;
      }
  }

  $sql_destinatario = "SELECT geralpessoas.* FROM geralpessoas WHERE geralpessoas.cpfCnpj = $destinatario_cpfCnpj";
  $buscar_destinatario = mysqli_query($conexaogeral, $sql_destinatario);

  while ($row_destinatario = mysqli_fetch_array($buscar_destinatario, MYSQLI_ASSOC)) {
      foreach ($row_destinatario as $dadosRow => $dadosDestinatario) {
          $row["destinatario_" . $dadosRow] = $dadosDestinatario;
      }
  }

  $sql_status = "SELECT fisnotastatus.nomeStatusNota FROM fisnotastatus WHERE fisnotastatus.idStatusNota = $idStatusNota";
  $buscar_status = mysqli_query($conexaogeral, $sql_status);

  while ($row_status = mysqli_fetch_array($buscar_status, MYSQLI_ASSOC)) {
      foreach ($row_status as $dadosRow => $dadosStatus) {
        $row[$dadosRow] = $dadosStatus;
      }
  }

  array_push($notas, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idNota"]) && $rows==1) {
  $notas = $notas[0];
}
$jsonSaida = $notas;

?>
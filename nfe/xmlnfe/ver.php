<?php 

// Transformando arquivo XML em Objeto
$xml = simplexml_load_file("xml/".$_GET['arquivo']);
$NFe = $xml->NFe;
//var_dump($NFe);

$xml = $NFe;

// Exibe as informações do XML
echo "<h3>Informações da Nota Fiscal</h3>";
echo 'Chave de Acesso: ' . str_replace("NFe", "", $xml->infNFe['Id']) . '<br>';
echo 'Nota Fiscal: ' . $xml->infNFe->ide->nNF . '<br>';
echo 'Série: ' . $xml->infNFe->ide->serie . '<br>';
echo 'Data de Emissão: ' . date('d/m/Y', strtotime($xml->infNFe->ide->dEmi)) . '<br>';

echo "<h3>Emitente</h3>";
echo 'Emitente/CNPJ: ' . $xml->infNFe->emit->CNPJ . '<br>';
echo 'Emitente/Razão Social: ' . $xml->infNFe->emit->xNome . '<br>';
echo 'Endereço: ' . $xml->infNFe->emit->enderEmit->xLgr . ', ' . $xml->infNFe->emit->enderEmit->nro . '<br>';

echo "<h3>Destinatário</h3>";
echo 'Destinatario/Doc: ' . $xml->infNFe->dest->CNPJ . '<br>';
echo 'Destinatario/Nome: ' . $xml->infNFe->dest->xNome . '<br>';
echo 'Endereço: ' . $xml->infNFe->dest->enderDest->xLgr . ', ' . $xml->infNFe->dest->enderDest->nro . '<br>';

echo "<h3>Produtos</h3>";
echo "<table cellspacing='2' cellpadding='2' border='1'>";
echo "<tr>";
echo "<td><b>#</b></td>";
echo "<td><b>Código</b></td>";
echo "<td><b>Produto</b></td>";
echo "<td><b>Quantidade</b></td>";
echo "<td><b>Unitario</b></td>";
echo "<td><b>Valor Total</b></td>";
echo "<td><b>CFOP</b></td>";
echo "<td><b>NCM</b></td>";
echo "<td><b>CEST</b></td>";
echo "</tr>";
foreach($xml->infNFe->det as $item) {
	echo "<tr>";
	echo "<td>#{$item['nItem']}</td>";
	echo "<td>{$item->prod->cProd}</td>";
	echo "<td>{$item->prod->xProd}</td>";
	echo "<td>{$item->prod->qCom}</td>"; 
	echo "<td>{$item->prod->vUnCom}</td>";
	echo "<td>{$item->prod->vProd}</td>";	
	echo "<td>{$item->prod->CFOP}</td>";
	echo "<td>{$item->prod->NCM}</td>";
	echo "<td>{$item->prod->CEST}</td>";
	
	
	echo "</tr>";
}
echo "</table>";

echo "<h3>Valores</h3>";
echo 'Base de Cálculo: ' . $xml->infNFe->total->ICMSTot->vBC . '<br>';
echo 'Valor Produtos: ' . $xml->infNFe->total->ICMSTot->vProd . '<br>';
echo 'PIS: ' . $xml->infNFe->total->ICMSTot->vPIS . '<br>';
echo 'COFINS: ' . $xml->infNFe->total->ICMSTot->vCOFINS . '<br>';

echo "<br>";

echo "<a href='lista.php'>Ir para lista de arquivos.</a><br />";
echo "<a href='index.php'>Ir para envio de arquivos.</a><br />";

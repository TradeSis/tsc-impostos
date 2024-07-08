<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscarNota($_GET['idNota']);
$produtos = buscarNotaProduto($_GET['idNota']);
$impostoTotal = buscarNotaImpostos($_GET['idNota']);
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <?php include_once ROOT . "/vendor/head_css.php"; ?>
</head>

<body>
    <div class="card container-fluid mt-2">
        <div class="row mt-3"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Nota Fiscal <?php echo $notas['NF'] ?></h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="nfe.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <div id="ts-tabs">
                <div class="tab whiteborder" id="tab-nfe">Dados NF-e</div>
                <?php if ($notas['idStatusNota'] != 0) {  ?>
                <div class="tab" id="tab-imposto">Imposto</div>
                <div class="tab" id="tab-produ">Produtos</div>
                <?php } ?>
                
                <div class="line"></div>

                <div class="tabContent">
                <!-- *****************NOTAFISCAL***************** -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label ts-label">Chave de Acesso</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['chaveNFe'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label ts-label">Natureza da operação</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['naturezaOp'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label ts-label">Modelo</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['modelo'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Série</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['serie'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Nota Fiscal</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['NF'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Data de Emissão</label>
                            <input type="text" class="form-control ts-input" value="<?php echo date('d/m/Y', strtotime($notas['dtEmissao'])) ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Emitente</h6>
                        <div class="col-md-4">
                            <label class="form-label ts-label">CNPJ</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_cpfCnpj'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">IE</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_IE'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Razão Social</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_nomePessoa'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Municipio</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_municipio'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">UF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_codigoEstado'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">País</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_pais'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Destinatário</h6>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Doc</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_cpfCnpj'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">IE</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_IE'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Nome</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_nomePessoa'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Municipio</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_municipio'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">UF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_codigoEstado'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">País</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_pais'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Valores</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vNF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vNF'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vProd</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vProd'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFrete</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vFrete'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vSeg</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vSeg'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vDesc</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vDesc'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vOutro</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['vOutro'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                </div>

                <?php if ($notas['idStatusNota'] != 0) {  ?>
                <div class="tabContent">
                <!-- *****************IMPOSTOS***************** -->
                    <div class="row">
                        <h6>ICMS</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vBC</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vBC'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMS'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSDeson</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSDeson'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSUFDest</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSUFDest'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSUFRemet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSUFRemet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>FCP</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCP</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vFCP'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCPUFDest</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vFCPUFDest'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCPSTRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vFCPSTRet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>ICMS ST</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vBCST</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vBCST'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vST</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vST'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>ICMS Monofasico</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMono</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['qBCMono'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMono</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSMono'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMonoReten</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['qBCMonoReten'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMonoReten</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSMonoReten'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMonoRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['qBCMonoRet'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMonoRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vICMSMonoRet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md">
                            <h6>Imposto Importacao</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vII</label>
                                <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vII'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="row">
                                <h6>IPI</h6>
                                <div class="col-md">
                                    <label class="form-label ts-label">vIPI</label>
                                    <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vIPI'], 2, ',', '.') ?>" readonly>
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">vIPIDevol</label>
                                    <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vIPIDevol'], 2, ',', '.') ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>PIS</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vPIS</label>
                                <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vPIS'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>COFINS</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vCOFINS</label>
                                <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vCOFINS'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>Valor Estimado Impostos</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vTotTrib</label>
                                <input type="text" class="form-control ts-input" value="<?php echo number_format($impostoTotal['vTotTrib'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                    </div>
                   
                </div>

                <div class="tabContent">
                <!-- *****************PRODUTOS***************** -->
                    <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
                        <table class="table table-sm table-hover">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>#</th>
                                    <th>EAN</th>
                                    <th>refProduto</th>
                                    <th>Produto</th>
                                    <th>Qnt</th>
                                    <th>Un Comercial</th>
                                    <th>Vl Unitario</th>
                                    <th>Vl Total</th>
                                    <th>CFOP</th>
                                    <th>NCM</th>
                                    <th>CEST</th>
                                </tr>
                            </thead>

                            <tbody id='dados' class="fonteCorpo">

                            </tbody>
                        </table>
                        <div id="pagination"></div>
                        <div id="impostosdiv"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include 'modalVisualizarProdu'; ?>
   

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        var tab;
        var tabContent;

        window.onload = function () {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'imposto') {
                showTabsContent(1);
            }
            if (id === 'produ') {
                showTabsContent(2);
            }
        }

        document.getElementById('ts-tabs').onclick = function (event) {
            var target = event.target;
            if (target.className == 'tab') {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(a) {
            for (var i = a; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(b) {
            if (tabContent[b].classList.contains('hide')) {
                hideTabsContent(0);
                tab[b].classList.add('whiteborder');
                tabContent[b].classList.remove('hide');
                tabContent[b].classList.add('show');
            }
        }
    </script>

    <script>
        var currentPage = 1;
        var rowsPerPage = 5;

        buscar(<?php echo $notas['idNota'] ?>);

        function buscar(idNota) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: "../database/fisnota.php?operacao=buscarNotaProduto",
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    idNota: idNota
                },
                success: function(msg) {
                    var json = JSON.parse(msg);
                    var totalRows = json.length;
                    var totalPages = Math.ceil(totalRows / rowsPerPage);
                    var start = (currentPage - 1) * rowsPerPage;
                    var end = start + rowsPerPage;
                    var linha = "";
                    for (var $i = start; $i < end && $i < totalRows; $i++) {
                        var object = json[$i];
                        linha += "<tr>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.nItem + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.eanProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.refProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.nomeProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.quantidade).toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.unidCom + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.valorUnidade).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.valorTotal).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.cfop + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.codigoNcm + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.codigoCest + "</td>";
                        linha += "</tr>";
                    }

                    $("#dados").html(linha);
                    
                    var pagination = "<div class='d-flex justify-content-end'>";
                    pagination += "<ul class='pagination'>";
                    for (var i = 1; i <= totalPages; i++) {
                        if (i === currentPage) {
                            pagination += "<li class='page-item active'><button class='page-link' onclick='goToPage(" + i + ")'>" + i + "</button></li>";
                        } else {
                            pagination += "<li class='page-item'><button class='page-link' onclick='goToPage(" + i + ")'>" + i + "</button></li>";
                        }
                    }
                    pagination += "</ul>";
                    pagination += "</div>";
                    $("#pagination").html(pagination);
                }
            });
        }

        function goToPage(page) {
            currentPage = page;
            buscar(<?php echo $notas['idNota'] ?>);
        }


        $(document).on('click', '.ts-click', function() {
            var idNota = $(this).attr("data-idNota");
            var nItem = $(this).attr("data-nItem");
            var idProduto = $(this).attr("data-idProduto");

            $('#impostosdiv').html('');

            var conteudoCollapse = "<div class='container'>" +
                "<div class='card'>" +
                "<div class='row'>" +
                "<h5>ICMS Produto " + nItem + "</h5>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover'>" +
                "<thead class='ts-headertabelafixo'>" +
                "<tr>" +
                "<th>imposto</th>" +
                "<th>nomeImposto</th>" +
                "<th>vTotTrib</th>" +
                "<th>orig</th>" +
                "<th>CSOSN</th>" +
                "<th>modBCST</th>" +
                "<th>pMVAST</th>" +
                "<th>vBCST</th>" +
                "<th>pICMSST</th>" +
                "<th>vICMSST</th>" +
                "<th>CST</th>" +
                "<th>modBC</th>" +
                "<th>vBC</th>" +
                "<th>pICMS</th>" +
                "<th>vICMS</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='icms_" + idNota + "_" + nItem + "_" + idProduto + "' class='fonteCorpo'></tbody>" +
                "</table>" +
                "</div>" +
                "</div>" +
                "<div class='row'>" +
                "<h5>Impostos Produto " + nItem + "</h5>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover'>" +
                "<thead class='ts-headertabelafixo'>" +
                "<tr>" +
                "<th>imposto</th>" +
                "<th>nomeImposto</th>" +
                "<th>cEnq</th>" +
                "<th>CST</th>" +
                "<th>vBC</th>" +
                "<th>percentual</th>" +
                "<th>valor</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='impostos_" + idNota + "_" + nItem + "_" + idProduto + "' class='fonteCorpo'></tbody>" +
                "</table>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

                $('#impostosdiv').html(conteudoCollapse);

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisnota.php?operacao=buscarProduICMS',
                    data: {
                        idNota: idNota,
                        nItem: nItem
                    },
                    success: function (data) {
                        var linha = "";
                        for (var i = 0; i < data.length; i++) {
                            var object = data[i];
                            linha += "<tr>";
                            linha += "<td>" + (object.imposto !== null ? object.imposto : "") + "</td>";
                            linha += "<td>" + (object.nomeImposto !== null ? object.nomeImposto : "") + "</td>";
                            linha += "<td>" + (object.vTotTrib !== null ? object.vTotTrib : "") + "</td>";
                            linha += "<td>" + (object.orig !== null ? object.orig : "") + "</td>";
                            linha += "<td>" + (object.CSOSN !== null ? object.CSOSN : "") + "</td>";
                            linha += "<td>" + (object.modBCST !== null ? object.modBCST : "") + "</td>";
                            linha += "<td>" + (object.pMVAST !== null ? parseFloat(object.pMVAST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.vBCST !== null ? parseFloat(object.vBCST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.pICMSST !== null ? parseFloat(object.pICMSST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.vICMSST !== null ? parseFloat(object.vICMSST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.CST !== null ? object.CST : "") + "</td>";
                            linha += "<td>" + (object.modBC !== null ? object.modBC : "") + "</td>";
                            linha += "<td>" + (object.vBC !== null ? parseFloat(object.vBC).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.pICMS !== null ? parseFloat(object.pICMS).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.vICMS !== null ? parseFloat(object.vICMS).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "</tr>";
                        }
                        $("#icms_" + idNota + "_" + nItem + "_" + idProduto).html(linha);
                    }
                });
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisnota.php?operacao=buscarProduImposto',
                    data: {
                        idNota: idNota,
                        nItem: nItem
                    },
                    success: function (data) {
                        var linha = "";
                        for (var i = 0; i < data.length; i++) {
                            var object = data[i];
                            linha += "<tr>";
                            linha += "<td>" + (object.imposto !== null ? object.imposto : "") + "</td>";
                            linha += "<td>" + (object.nomeImposto !== null ? object.nomeImposto : "") + "</td>";
                            linha += "<td>" + (object.cEnq !== null ? parseFloat(object.cEnq).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.CST !== null ? parseFloat(object.CST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.vBC !== null ? parseFloat(object.vBC).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.percentual !== null ? parseFloat(object.percentual).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "<td>" + (object.valor !== null ? parseFloat(object.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha += "</tr>";
                        }
                        $("#impostos_" + idNota + "_" + nItem + "_" + idProduto).html(linha);
                    }
                });
            });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
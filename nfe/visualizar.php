<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscarNota($_GET['idNota']);
//echo json_encode($notas);
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

        <!-- MODAL FISCAL GRUPO -->
        <?php include_once '../cadastros/modalfiscalgrupo.php' ?>

        <!-- MODAL REGRA FISCAl -->
        <?php include_once '../cadastros/modalregrafiscal.php' ?>

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
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vNF'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vProd</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vProd'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFrete</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vFrete'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vSeg</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vSeg'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vDesc</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vDesc'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vOutro</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($notas['vOutro'], 2, ',', '.') ?>" readonly>
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
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vBC'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMS</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMS'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSDeson</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSDeson'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSUFDest</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSUFDest'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSUFRemet</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSUFRemet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>FCP</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCP</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vFCP'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCPUFDest</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vFCPUFDest'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vFCPSTRet</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vFCPSTRet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>ICMS ST</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">vBCST</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vBCST'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vST</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vST'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6>ICMS Monofasico</h6>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMono</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['qBCMono'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMono</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSMono'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMonoReten</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['qBCMonoReten'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMonoReten</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSMonoReten'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">qBCMonoRet</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['qBCMonoRet'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md">
                            <label class="form-label ts-label">vICMSMonoRet</label>
                            <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vICMSMonoRet'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md">
                            <h6>Imposto Importacao</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vII</label>
                                <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vII'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="row">
                                <h6>IPI</h6>
                                <div class="col-md">
                                    <label class="form-label ts-label">vIPI</label>
                                    <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vIPI'], 2, ',', '.') ?>" readonly>
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">vIPIDevol</label>
                                    <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vIPIDevol'], 2, ',', '.') ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>PIS</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vPIS</label>
                                <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vPIS'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>COFINS</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vCOFINS</label>
                                <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vCOFINS'], 2, ',', '.') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md">
                            <h6>Valor Estimado Impostos</h6>
                            <div class="col-md">
                                <label class="form-label ts-label">vTotTrib</label>
                                <input type="text" class="form-control ts-input ts-value" value="<?php echo number_format($impostoTotal['vTotTrib'], 2, ',', '.') ?>" readonly>
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
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>


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
        var rowsPerPage = 10;

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
                        //alert(object.idNota)
                        linha += "<tr>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.nItem + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.eanProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.refProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.nomeProduto + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + parseFloat(object.quantidade).toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.unidCom + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + parseFloat(object.valorUnidade).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + parseFloat(object.valorTotal).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.cfop + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.codigoNcm + "</td>";
                        linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "'>" + object.codigoCest + "</td>";
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
            var idGeralProduto = $(this).attr("data-idGeralProduto");

            var collapseId = 'collapse_' + idNota + "_" + nItem + "_" + idProduto;

            var conteudoCollapse = "<tr class='collapse-row bg-light'><td colspan='15'><div class='collapse show' id='" + collapseId + "'>" +
                "<div class='container'>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover ts-tablecenter'>" +
                "<thead>" +
                "<tr>" +
                "<th>idGeralProduto</th>" +
                "<th>idGrupo</th>" +
                "<th>nomeGrupo</th>" +
                "<th>grupo.NCM</th>" +
                "<th>grupo.CEST</th>" +
                "<th></th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='fiscalgrupo' class='fonteCorpo'></tbody>" +
                "</table>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover ts-tablecenter'>" +
                "<thead>" +
                "<tr>" +
                "<th>codigoEstado</th>" +
                "<th>cFOP</th>" +
                "<th>codigoCaracTrib</th>" +
                "<th>finalidade</th>" +
                "<th>origem</th>" +
                "<th>idRegra</th>" +
                "<th></th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='fiscaloperacao' class='fonteCorpo'></tbody>" +
                "</table>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover table-warning ts-tablecenter'>" +
                "<thead>" +
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
                "<th>nomeCST</th>" +
                "<th>modBC</th>" +
                "<th>vBC</th>" +
                "<th>pICMS</th>" +
                "<th>vICMS</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='icms_" + idNota + "_" + nItem + "_" + idProduto + "' class='fonteCorpo'></tbody>" +
                "</table>" +
                "<table class='table table-sm table-hover table-warning ts-tablecenter' style='margin-top: -10px;'>" +
                "<thead>" +
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
                "</div></td></tr>";

            if ($('#' + collapseId).length === 0) {
                $('.collapse-row').remove();
                $(this).closest('tr').after(conteudoCollapse);
            
                var cpfCnpjEmitente = "<?php echo $notas['emitente_cpfCnpj'] ?>";
                
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisnota.php?operacao=buscarNotaProdutoOperacao',
                    data: {
                        idGeralProduto: idGeralProduto,
                        cpfCnpj: cpfCnpjEmitente
                    },
                    success: function(data) {
                        var linha_grupo = "";
                        for (var i = 0; i < data["fiscalgrupo"].length; i++) {
                            var object_grupo = data["fiscalgrupo"][i];
                           
                            linha_grupo += "<tr>";
                            linha_grupo += "<td style='text-align: center;'>" + object_grupo.idGeralProduto + "</td>";
                            linha_grupo += "<td style='text-align: center;'>" + object_grupo.idGrupo + "</td>";
                            linha_grupo += "<td style='text-align: center;'>" + object_grupo.nomeGrupo + "</td>";
                            linha_grupo += "<td style='text-align: center;'>" + object_grupo.codigoNcm + "</td>";
                            linha_grupo += "<td style='text-align: center;'>" + object_grupo.codigoCest + "</td>";
                            linha_grupo += "<td style='text-align: center;'><button type='button' class='btn btn-sm m-0 p-0' title='visualizar Grupo' data-bs-toggle='modal' data-bs-target='#visualizarGrupoProdutoModal' data-idGrupo='" + object_grupo.idGrupo + "'><i class='bi bi-eye'></i></button></td>";
                            linha_grupo += "</tr>";
                        }
                        $("#fiscalgrupo").html(linha_grupo);

                        var linha_operacao = "";
                        for (var i = 0; i < data["produtooperacao"].length; i++) {
                            var object_operacao = data["produtooperacao"][i];
                            //alert(object_operacao.idRegra)
                            linha_operacao += "<tr>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.codigoEstado + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.cFOP + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.codigoCaracTrib + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.finalidade + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.origem + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + object_operacao.idRegra + "</td>";
                            linha_operacao += "<td style='text-align: center;'>" + "<button type='button' class='btn btn-sm m-0 p-0' title='visualizar Regra' data-bs-toggle='modal' data-bs-target='#modalRegraFiscal' data-idRegra='" + object_operacao.idRegra + "'><i class='bi bi-eye'></i></button> ";
                            linha_operacao += "</tr>";
                        }
                        $("#fiscaloperacao").html(linha_operacao);
                    }
                });

              
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisnota.php?operacao=buscarProdutoImposto',
                    data: {
                        idNota: idNota,
                        nItem: nItem
                    },
                    success: function(data) {
                        //alert(JSON.stringify(data));
                        var linha_icms = "";
                        for (var i = 0; i < data["fisnotaproduicms"].length; i++) {
                            var object_icms = data["fisnotaproduicms"][i];
                            linha_icms += "<tr>";
                            linha_icms += "<td style='text-align: left;'>" + (object_icms.imposto !== null ? object_icms.imposto : "") + "</td>";
                            linha_icms += "<td style='text-align: left;'>" + (object_icms.nomeImposto !== null ? object_icms.nomeImposto : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.vTotTrib !== null ? object_icms.vTotTrib : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.orig !== null ? object_icms.orig : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.CSOSN !== null ? object_icms.CSOSN : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.modBCST !== null ? object_icms.modBCST : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pMVAST !== null ? parseFloat(object_icms.pMVAST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vBCST !== null ? parseFloat(object_icms.vBCST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pICMSST !== null ? parseFloat(object_icms.pICMSST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vICMSST !== null ? parseFloat(object_icms.vICMSST).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.CST !== null ? object_icms.CST : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.nomeCST !== null ? object_icms.nomeCST : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.modBC !== null ? object_icms.modBC : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vBC !== null ? parseFloat(object_icms.vBC).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pICMS !== null ? parseFloat(object_icms.pICMS).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vICMS !== null ? parseFloat(object_icms.vICMS).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_icms += "</tr>";
                        }
                        $("#icms_" + idNota + "_" + nItem + "_" + idProduto).html(linha_icms);

                        var linha_imposto = "";
                        for (var i = 0; i < data["fisnotaproduimposto"].length; i++) {
                            var object_imposto = data["fisnotaproduimposto"][i];
                            linha_imposto += "<tr>";
                            linha_imposto += "<td style='text-align: left;'>" + (object_imposto.imposto !== null ? object_imposto.imposto : "") + "</td>";
                            linha_imposto += "<td style='text-align: left;'>" + (object_imposto.nomeImposto !== null ? object_imposto.nomeImposto : "") + "</td>";
                            linha_imposto += "<td style='text-align: center;'>" + (object_imposto.cEnq !== null ? object_imposto.cEnq : "") + "</td>";
                            linha_imposto += "<td style='text-align: center;'>" + (object_imposto.CST !== null ? object_imposto.CST : "") + "</td>";
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.vBC !== null ? parseFloat(object_imposto.vBC).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.percentual !== null ? (parseFloat(object_imposto.percentual)).toLocaleString('pt-BR').replace(',', '.') + "%" : "") + "</td>";
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.valor !== null ? parseFloat(object_imposto.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : "") + "</td>";
                            linha_imposto += "</tr>";
                        }
                        
                        $("#impostos_" + idNota + "_" + nItem + "_" + idProduto).html(linha_imposto);
                    }
                });
            } else {
                $('#' + collapseId).collapse('toggle');
                $('#' + 'impostos_' + collapseId).collapse('toggle');
                $(this).closest('tr').nextAll('.collapse-row').remove();
            }
          
        });


        $(document).on('click', 'button[data-bs-target="#visualizarGrupoProdutoModal"]', function() {
            var vidGrupo = $(this).attr("data-idGrupo");

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/admin/database/grupoproduto.php?operacao=buscar',
                data: {
                    idGrupo: vidGrupo
                },
                success: function(data) {
                    //alert(data)
                    $('#codigoGrupo').val(data.codigoGrupo);
                    $vcodigoGrupo = data.codigoGrupo;
                    var texto = $("#textoCodigoGrupo");
                    texto.html($vcodigoGrupo);

                    $('#nomeGrupo').val(data.nomeGrupo);
                    $('#codigoNcm').val(data.codigoNcm);
                    $('#codigoCest').val(data.codigoCest);
                    $('#impostoImportacao').val(data.impostoImportacao);
                    $('#piscofinscstEnt').val(data.piscofinscstEnt);
                    $('#piscofinscstSai').val(data.piscofinscstSai);
                    $('#aliqPis').val(data.aliqPis);
                    $('#aliqCofins').val(data.aliqCofins);
                    $('#nri').val(data.nri);
                    $('#ampLegal').val(data.ampLegal);
                    $('#redPIS').val(data.redPIS);
                    $('#redCofins').val(data.redCofins);
                    $('#ipicstEnt').val(data.ipicstEnt);
                    $('#ipicstSai').val(data.ipicstSai);
                    $('#aliqipi').val(data.aliqipi);
                    $('#codenq').val(data.codenq);
                    $('#ipiex').val(data.ipiex);
                    $('#visualizarGrupoProdutoModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }
            });
        });

        $(document).on('click', 'button[data-bs-target="#modalRegraFiscal"]', function() {
            var idRegra = $(this).attr("data-idRegra");
            //alert(idRegra)
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/regrafiscal.php?operacao=buscar',
                data: {
                    idRegra: idRegra
                },
                success: function(data) {
                    
                    $vcodRegra = data.codRegra;
                    var texto = $("#textocodRegra");
                    texto.html($vcodRegra);

                    $('#codRegra_regrafiscal').val(data.codRegra);
                    $('#codExcecao_regrafiscal').val(data.codExcecao);
                    $('#dtVigIni_regrafiscal').val(data.dtVigIni);
                    $('#dtVigFin_regrafiscal').val(data.dtVigFin);
                    $('#cFOPCaracTrib_regrafiscal').val(data.cFOPCaracTrib);
                    $('#cST_regrafiscal').val(data.cST);
                    $('#cSOSN_regrafiscal').val(data.cSOSN);
                    $('#aliqIcmsInterna_regrafiscal').val(data.aliqIcmsInterna);
                    $('#aliqIcmsInterestadual_regrafiscal').val(data.aliqIcmsInterestadual);
                    $('#reducaoBcIcms_regrafiscal').val(data.reducaoBcIcms);
                    $('#reducaoBcIcmsSt_regrafiscal').val(data.reducaoBcIcmsSt);
                    $('#redBcICMsInterestadual_regrafiscal').val(data.redBcICMsInterestadual);
                    $('#aliqIcmsSt_regrafiscal').val(data.aliqIcmsSt);
                    $('#iVA_regrafiscal').val(data.iVA);
                    $('#iVAAjust_regrafiscal').val(data.iVAAjust);
                    $('#fCP_regrafiscal').val(data.fCP);
                    $('#codBenef_regrafiscal').val(data.codBenef);
                    $('#pDifer_regrafiscal').val(data.pDifer);
                    $('#pIsencao_regrafiscal').val(data.pIsencao);
                    $('#antecipado_regrafiscal').val(data.antecipado);
                    $('#desonerado_regrafiscal').val(data.desonerado);
                    $('#pICMSDeson_regrafiscal').val(data.pICMSDeson);
                    $('#isento_regrafiscal').val(data.isento);
                    $('#tpCalcDifal_regrafiscal').val(data.tpCalcDifal);
                    $('#ampLegal_regrafiscal_regrafiscal').val(data.ampLegal);
                    $('#Protocolo_regrafiscal').val(data.Protocolo);
                    $('#Convenio_regrafiscal').val(data.Convenio);
                    $('#regraGeral_regrafiscal').val(data.regraGeral);

                    $('#modalRegraFiscal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }

            });
        });

    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
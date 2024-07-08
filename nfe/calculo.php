<?php
//Lucas 19042023 criado
include_once(__DIR__ . '/../header.php');

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

    <div class="container-fluid">

        <div class="row ">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> BOTOES AUXILIARES -->
        </div>

        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-3">
                <h2 class="ts-tituloPrincipal">Calculo</h2>
            </div>

            <div class="col-9">
                <div class="row">

                    <div class="col-md-2 col-6">
                        <select class="form-select ts-input" name="imposto" id="FiltroImposto">
                            <option value="null">Todos</option>
                            <option value="COFINS">COFINS</option>
                            <option value="ICMS">ICMS</option>
                            <option value="PIS">PIS</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="porcst" id="porcst" value="false">
                            <label class="form-check-label">Por CST</label>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <input type="text" class="form-control ts-input" name="anoImposto" id="FiltroDataAno" placeholder="Ano" autocomplete="off" required>
                    </div>

                    <div class="col-md-2 col-4">
                        <select class="form-select ts-input" name="mesImposto" id="FiltroDataMes">
                            <option value="01">Janeiro</option>
                            <option value="02">Fevereiro</option>
                            <option value="03">Março</option>
                            <option value="04">Abril</option>
                            <option value="05">Maio</option>
                            <option value="06">Junho</option>
                            <option value="07">Julho</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-3">
                        <button class="btn btn-sm btn-primary" type="button" id="filtrardata">Filtrar</button>
                    </div>
                </div>
            </div>

        </div><!-- ROW -->

        <!--------- VISUALIZAR ITENS --------->
        <div class="modal fade bd-example-modal-lg" id="modal_ItemNotas" aria-labelledby="modal_ItemNotasLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-xxl-down">
                <div class="modal-content">

                    <div class="modal-header pt-0">
                        <table class="table table-sm table-hover mb-0 pb-0">
                            <thead>
                                <tr>
                                    <th>data Emissao</th>
                                    <th>Imposto</th>
                                    <th></th>
                                    <th>CST</th>
                                    <th>nomeCST</th>
                                    <th>vBC</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody class="fonteCorpo">
                                <tr>
                                    <td><span class="titulo" id="txtmesano"></span></td>
                                    <td><span class="titulo" id="txtIDXimposto"></span></td>
                                    <td><span class="titulo" id="txtimposto"></span></td>
                                    <td><span class="titulo" id="txtcst"></span></td>
                                    <td><span class="titulo" id="txtnomecst"></span></td>
                                    <td><span class="titulo" id="txtvbc"></span></td>
                                    <td><span class="titulo" id="txtvalor"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn-close mb-0 pb-0" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body mt-0 pt-0">
                        <div class="table mt-0 pt-0 ts-divTabela ts-tableFiltros text-center">
                            <table class="table table-sm table-hover">
                                <thead class="ts-headertabelafixo">
                                    <tr class="ts-headerTabelaLinhaCima">
                                        <th>Nota Fiscal</th>
                                        <th>#</th>
                                        <th>CPF Emit</th>
                                        <th>EAN</th>
                                        <th>refProduto</th>
                                        <th>Produto</th>
                                    </tr>
                                </thead>

                                <tbody id='dadosItemNotas' class="fonteCorpo"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL FISCAL GRUPO -->
        <?php include_once '../cadastros/modalfiscalgrupo.php' ?>

        <!-- MODAL REGRA FISCAl -->
        <?php include_once '../cadastros/modalregrafiscal.php' ?>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>data Emissao</th>
                        <th colspan="2" class="text-start">Imposto</th>
                        <th>CST</th>
                        <th>nomeCST</th>
                        <th>vBC</th>
                        <th>Valor</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
            <!-- div de loading -->
            <div class="text-center" id="div-load" style="margin-top: 200px; display: none">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>


    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        function buscar(FiltroDataAno, FiltroDataMes, FiltroImposto, porcst) {

            if (FiltroDataAno == '') {
                alert("Informar campo Ano")
            } else {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../database/calculo.php?operacao=filtrar',
                    beforeSend: function() {
                        setTimeout(function() {
                            $("#div-load").css("display", "block");
                        }, 500);
                    },
                    data: {
                        anoImposto: FiltroDataAno,
                        mesImposto: FiltroDataMes,
                        FiltroImposto: FiltroImposto,
                        porcst: porcst
                    },
                    success: function(msg) {

                        var json = JSON.parse(msg);
                        //alert(JSON.stringify(json));
                        if (json == '') {
                            alert("Nenhum registro retornado!")
                            linha = "";
                            $("#dados").html(linha);
                            $("#div-load").css("display", "none");

                        } else {

                            var linha = "";
                            for (var $i = 0; $i < json.length; $i++) {
                                var object = json[$i];

                                // Formatando campo imposto, ficando apenas com (CALC, DIF)  
                                var imposto = object.imposto;
                                imposto = imposto.replace("_", '')
                                imposto = imposto.replace("COFINS", '')
                                imposto = imposto.replace("ICMS", '')
                                imposto = imposto.replace("PIS", '')

                                linha = linha + "<tr";
                                if (imposto == "DIF") {
                                    linha += " style='background-color: #dfdfdf' >";
                                } else {
                                    linha += ">";
                                }

                                linha = linha + "<td>" + object.mes + "/" + object.ano + "</td>";
                                linha = linha + "<td class='text-start'>" + object.IDX_imposto + "</td>";
                                linha = linha + "<td class='text-start'>" + imposto + "</td>";
                                linha = linha + "<td>" + (object.CST !== null ? object.CST : "-") + "</td>";
                                linha = linha + "<td>" + object.nomeCST + "</td>";
                                linha = linha + "<td class='text-end'>" + (object.vBC !== null ? parseFloat(object.vBC).toFixed(2) : "-") + "</td>";
                                linha = linha + "<td class='text-end'>" + (object.valor !== null ? parseFloat(object.valor).toFixed(2) : "-") + "</td>";

                                var jsonNotas = object.notas;
                                if (jsonNotas != null) {
                                    //alert(JSON.stringify(object.notas));
                                    itemNota = JSON.stringify(object.notas);
                                    linha = linha + "<td>" + "<button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#visualizarItemNota' ";
                                    linha = linha + "data-itemNota='" + itemNota + "' data-mesano='" + object.mes + "/" + object.ano + "' data-imposto='" + imposto + "' data-IDXimposto='" + object.IDX_imposto + "'";
                                    linha = linha + "data-cst='" + object.CST + "' data-nomeCST='" + object.nomeCST + "' data-vbc='" + object.vBC + "' data-valor='" + object.valor + "' >";
                                    linha = linha + "<i class='bi bi-eye'></i></button></td>";
                                } else {
                                    linha = linha + "<td></td>";
                                }

                                linha = linha + "</tr>";

                            }

                            $("#dados").html(linha);
                            $("#div-load").css("display", "none");
                        }

                    }
                });
            }

        }

        $(document).on('click', 'button[data-bs-target="#visualizarItemNota"]', function() {
            var itemNota = $(this).attr("data-itemNota");
            var mesano = $(this).attr("data-mesano");
            var imposto = $(this).attr("data-imposto");
            var IDXimposto = $(this).attr("data-IDXimposto");
            var cst = $(this).attr("data-cst");
            var nomecst = $(this).attr("data-nomeCST");
            var vbc = $(this).attr("data-vbc");
            var valor = $(this).attr("data-valor");

            var textoModal = $("#txtmesano");
            var text = mesano;
            textoModal.html(text);

            var textoModal = $("#txtimposto");
            var text = imposto;
            textoModal.html(text);

            var textoModal = $("#txtIDXimposto");
            var text = IDXimposto;
            textoModal.html(text);

            var textoModal = $("#txtcst");
            var text = cst;
            textoModal.html(text);

            var textoModal = $("#txtnomecst");
            var text = nomecst;
            textoModal.html(text);

            var textoModal = $("#txtvbc");
            var text = (vbc !== null ? parseFloat(vbc).toFixed(2) : "-");
            textoModal.html(text);

            var textoModal = $("#txtvalor");
            var text = (valor !== null ? parseFloat(valor).toFixed(2) : "-");
            textoModal.html(text);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/fisnota.php?operacao=buscaItemNotas',
                data: {
                    itemnota: itemNota
                },
                success: function(data) {
                    //alert(JSON.stringify(data));
                    var linha_itemNota = "";
                    for (var i = 0; i < data.length; i++) {
                        var object = data[i];

                        linha_itemNota += "<tr>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.NF + "</td>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.nItem + "</td>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.Cnpj + "</td>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.eanProduto + "</td>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.refProduto + "</td>";
                        linha_itemNota += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "' data-idGeralProduto='" + object.idGeralProduto + "' data-cnpjEmit='" + object.Cnpj + "'>" + object.nomeProduto + "</td>";

                        linha_itemNota += "</tr>";
                    }

                    $("#dadosItemNotas").html(linha_itemNota);
                    $('#modal_ItemNotas').modal('show');
                }
            });
        });


        $(document).on('click', '.ts-click', function() {

            var idNota = $(this).attr("data-idNota");
            var nItem = $(this).attr("data-nItem");
            var idProduto = $(this).attr("data-idProduto");
            var idGeralProduto = $(this).attr("data-idGeralProduto");
            

            var collapseId = 'collapse_' + idNota + "_" + nItem + "_" + idProduto;

            var conteudoCollapse = "<tr class='collapse-row bg-light subCollapse'><td colspan='15' ><div class='collapse show' id='" + collapseId + "'>" +
                "<div class='container-fluid'>" +
                "<div class='table'>" +
                "<table class='table table-sm table-hover table-secondary ts-tablecenter'>" +
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
                "<table class='table table-sm table-hover table-secondary ts-tablecenter' style='margin-top: -10px;'>" +
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

                var cpfCnpjEmitente = $(this).attr("data-cnpjEmit");

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
                            if (object_grupo.idGrupo != 0) {
                                linha_grupo += "<td style='text-align: center;'><button type='button' class='btn btn-sm m-0 p-0' title='visualizar Grupo' data-bs-toggle='modal' data-bs-target='#visualizarGrupoProdutoModal' data-idGrupo='" + object_grupo.idGrupo + "'><i class='bi bi-eye'></i></button></td>";
                            } else {
                                linha_grupo += "<td></td>";
                            }
                            linha_grupo += "</tr>";
                        }
                        $("#fiscalgrupo").html(linha_grupo);

                        var linha_operacao = "";
                        for (var i = 0; i < data["produtooperacao"].length; i++) {
                            var object_operacao = data["produtooperacao"][i];

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
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pMVAST !== null ? parseFloat(object_icms.pMVAST).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vBCST !== null ? parseFloat(object_icms.vBCST).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pICMSST !== null ? parseFloat(object_icms.pICMSST).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vICMSST !== null ? parseFloat(object_icms.vICMSST).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.CST !== null ? object_icms.CST : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.nomeCST !== null ? object_icms.nomeCST : "") + "</td>";
                            linha_icms += "<td style='text-align: center;'>" + (object_icms.modBC !== null ? object_icms.modBC : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vBC !== null ? parseFloat(object_icms.vBC).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.pICMS !== null ? parseFloat(object_icms.pICMS).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_icms += "<td style='text-align: right;'>" + (object_icms.vICMS !== null ? parseFloat(object_icms.vICMS).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
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
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.vBC !== null ? parseFloat(object_imposto.vBC).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.percentual !== null ? (parseFloat(object_imposto.percentual)).toLocaleString('pt-BR').replace(',', '.') + "%" : "") + "</td>";
                            linha_imposto += "<td style='text-align: right;'>" + (object_imposto.valor !== null ? parseFloat(object_imposto.valor).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : "") + "</td>";
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


        $("#filtrardata").click(function() {
            buscar($("#FiltroDataAno").val(), $("#FiltroDataMes").val(), $("#FiltroImposto").val(), $('[name="porcst"]:checked').val());
        });

        // DADOS MODAL FISCAL GRUPO
        $(document).on('click', 'button[data-bs-target="#visualizarGrupoProdutoModal"]', function() {
            var idGrupo = $(this).attr("data-idGrupo");

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/admin/database/grupoproduto.php?operacao=buscar',
                data: {
                    idGrupo: idGrupo
                },
                success: function(data) {
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

        // DADOS MODAL REGRA FISCAL
        $(document).on('click', 'button[data-bs-target="#modalRegraFiscal"]', function() {
            var idRegra = $(this).attr("data-idRegra");

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

        // Ao iniciar o programa, inseri os valores de ano(input) e mes(select) atuais. 
        $(document).ready(function() {
            const date = new Date();
            const year = date.getFullYear();
            const currentMonth = date.getMonth() + 1;

            const FiltroDataAno = document.getElementById("FiltroDataAno");
            FiltroDataAno.value = year;

            const FiltroDataMes = document.getElementById("FiltroDataMes");
            FiltroDataMes.value = (currentMonth <= 9 ? "0" + currentMonth : currentMonth);

        });

        function refreshPage() {
            window.location.reload();
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
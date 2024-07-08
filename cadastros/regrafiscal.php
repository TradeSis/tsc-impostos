<?php
// lucas 08032024 - id876 passagem para progress
//Lucas 29022024 - id862 Empresa Administradora
// lucas 15012024 criado
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

            <div class="col-6 col-lg-6">
                <h2 class="ts-tituloPrincipal">Regra Fiscal</h2>
            </div>

            <div class="col-6 col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaCodigoGrupo" placeholder="Buscar por cÃ³digo">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <!-- Lucas 29022024 - condição Administradora -->
                    <?php if ($_SESSION['administradora'] == 1) { ?>
                        <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirRegraFiscalModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    <?php } ?>
                </div>
            </div>

        </div>

        <!-- MODAL REGRA FISCAl -->
        <?php include_once 'modalregrafiscal.php' ?>

        <!--------- INSERIR --------->
        <div class="modal fade bd-example-modal-lg" id="inserirRegraFiscalModal" tabindex="-1" aria-labelledby="inserirRegraFiscalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Regra Fiscal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-inserirRegraFiscal">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md">
                                        <label class="form-label ts-label">codRegra</label>
                                        <input type="text" class="form-control ts-input" name="codRegra" required>
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">codExcecao</label>
                                        <input type="text" class="form-control ts-input" name="codExcecao" required>
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">dtVigIni</label>
                                        <input type="date" class="form-control ts-input" name="dtVigIni">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">dtVigFin</label>
                                        <input type="date" class="form-control ts-input" name="dtVigFin">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">cFOPCaracTrib</label>
                                        <input type="text" class="form-control ts-input" name="cFOPCaracTrib">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">cST</label>
                                        <input type="text" class="form-control ts-input" name="cST">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">cSOSN</label>
                                        <input type="text" class="form-control ts-input" name="cSOSN">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">aliqIcmsInterna</label>
                                        <input type="text" class="form-control ts-input" name="aliqIcmsInterna">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">aliqIcmsInterestadual</label>
                                        <input type="text" class="form-control ts-input" name="aliqIcmsInterestadual">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">reducaoBcIcms</label>
                                        <input type="text" class="form-control ts-input" name="reducaoBcIcms">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">reducaoBcIcmsSt</label>
                                        <input type="text" class="form-control ts-input" name="reducaoBcIcmsSt">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">redBcICMsInterestadual</label>
                                        <input type="text" class="form-control ts-input" name="redBcICMsInterestadual">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">aliqIcmsSt</label>
                                        <input type="text" class="form-control ts-input" name="aliqIcmsSt">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">iVA</label>
                                        <input type="text" class="form-control ts-input" name="iVA">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">iVAAjust</label>
                                        <input type="text" class="form-control ts-input" name="iVAAjust">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">fCP</label>
                                        <input type="text" class="form-control ts-input" name="fCP">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">codBenef</label>
                                        <input type="text" class="form-control ts-input" name="codBenef">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">pDifer</label>
                                        <input type="text" class="form-control ts-input" name="pDifer">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">pIsencao</label>
                                        <input type="text" class="form-control ts-input" name="pIsencao">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">antecipado</label>
                                        <input type="text" class="form-control ts-input" name="antecipado">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">desonerado</label>
                                        <input type="text" class="form-control ts-input" name="desonerado">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">pICMSDeson</label>
                                        <input type="text" class="form-control ts-input" name="pICMSDeson">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">isento</label>
                                        <input type="text" class="form-control ts-input" name="isento">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">tpCalcDifal</label>
                                        <input type="text" class="form-control ts-input" name="tpCalcDifal">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label ts-label">ampLegal</label>
                                        <input type="text" class="form-control ts-input" name="ampLegal">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">Protocolo</label>
                                        <input type="text" class="form-control ts-input" name="Protocolo">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">Convenio</label>
                                        <input type="text" class="form-control ts-input" name="Convenio">
                                    </div>
                                    <div class="col-md">
                                        <label class="form-label ts-label">regraGeral</label>
                                        <input type="text" class="form-control ts-input" name="regraGeral">
                                    </div>
                                </div>
                            </div>

                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-formInserir">Cadastrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- lucas 08032024 - id876 modal de alteração -->
        <!-- Alterar -->
        <div class="modal fade" id="alterarRegraFiscal" tabindex="-1" aria-labelledby="alterarRegraFiscalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Regra Fiscal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-alterarRegraFiscal">
                            <div class="row">
                                <div class="col-md">
                                    <label class="form-label ts-label">codRegra</label>
                                    <input type="text" class="form-control ts-input" name="codRegra" id="codRegra_regrafiscal_alterar" readonly>
                                    <input type="hidden" class="form-control ts-input" name="idRegra" id="idRegra_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">codExcecao</label>
                                    <input type="text" class="form-control ts-input" name="codExcecao" id="codExcecao_regrafiscal_alterar" readonly>
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">dtVigIni</label>
                                    <input type="date" class="form-control ts-input" name="dtVigIni" id="dtVigIni_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">dtVigFin</label>
                                    <input type="date" class="form-control ts-input" name="dtVigFin" id="dtVigFin_regrafiscal_alterar">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md">
                                    <label class="form-label ts-label">cFOPCaracTrib</label>
                                    <input type="text" class="form-control ts-input" name="cFOPCaracTrib" id="cFOPCaracTrib_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">cST</label>
                                    <input type="text" class="form-control ts-input" name="cST" id="cST_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">cSOSN</label>
                                    <input type="text" class="form-control ts-input" name="cSOSN" id="cSOSN_regrafiscal_alterar">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqIcmsInterna</label>
                                    <input type="text" class="form-control ts-input" name="aliqIcmsInterna" id="aliqIcmsInterna_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqIcmsInterestadual</label>
                                    <input type="text" class="form-control ts-input" name="aliqIcmsInterestadual" id="aliqIcmsInterestadual_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">reducaoBcIcms</label>
                                    <input type="text" class="form-control ts-input" name="reducaoBcIcms" id="reducaoBcIcms_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">reducaoBcIcmsSt</label>
                                    <input type="text" class="form-control ts-input" name="reducaoBcIcmsSt" id="reducaoBcIcmsSt_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">redBcICMsInterestadual</label>
                                    <input type="text" class="form-control ts-input" name="redBcICMsInterestadual" id="redBcICMsInterestadual_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqIcmsSt</label>
                                    <input type="text" class="form-control ts-input" name="aliqIcmsSt" id="aliqIcmsSt_regrafiscal_alterar">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md">
                                    <label class="form-label ts-label">iVA</label>
                                    <input type="text" class="form-control ts-input" name="iVA" id="iVA_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">iVAAjust</label>
                                    <input type="text" class="form-control ts-input" name="iVAAjust" id="iVAAjust_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">fCP</label>
                                    <input type="text" class="form-control ts-input" name="fCP" id="fCP_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">codBenef</label>
                                    <input type="text" class="form-control ts-input" name="codBenef" id="codBenef_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">pDifer</label>
                                    <input type="text" class="form-control ts-input" name="pDifer" id="pDifer_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">pIsencao</label>
                                    <input type="text" class="form-control ts-input" name="pIsencao" id="pIsencao_regrafiscal_alterar">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md">
                                    <label class="form-label ts-label">antecipado</label>
                                    <input type="text" class="form-control ts-input" name="antecipado" id="antecipado_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">desonerado</label>
                                    <input type="text" class="form-control ts-input" name="desonerado" id="desonerado_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">pICMSDeson</label>
                                    <input type="text" class="form-control ts-input" name="pICMSDeson" id="pICMSDeson_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">isento</label>
                                    <input type="text" class="form-control ts-input" name="isento" id="isento_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">tpCalcDifal</label>
                                    <input type="text" class="form-control ts-input" name="tpCalcDifal" id="tpCalcDifal_regrafiscal_alterar">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label ts-label">ampLegal</label>
                                    <input type="text" class="form-control ts-input" name="ampLegal" id="ampLegal_regrafiscal_regrafiscal_alterar">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md">
                                    <label class="form-label ts-label">Protocolo</label>
                                    <input type="text" class="form-control ts-input" name="Protocolo" id="Protocolo_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">Convenio</label>
                                    <input type="text" class="form-control ts-input" name="Convenio" id="Convenio_regrafiscal_alterar">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">regraGeral</label>
                                    <input type="text" class="form-control ts-input" name="regraGeral" id="regraGeral_regrafiscal_alterar">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-formInserir">Salvar</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>id</th>
                        <th>codRegra</th>
                        <th>codExcecao</th>
                        <th>cFOPCaracTrib</th>
                        <th>cST</th>
                        <th>cSOSN</th>
                        <th>aliqIcmsInterna</th>
                        <th>aliqIcmsInterestadual</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody> 
            </table>
        </div>
        <!-- div de loading -->
        <div class="text-center" id="div-load" style="margin-top: -200px;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div> 


    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#buscaCodigoGrupo").val());

        function limpar() {
            buscar(null, null, null, null);
            window.location.reload();
        }

        function buscar(buscaCodigoGrupo) {
            //alert(buscaCodigoGrupo);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/regrafiscal.php?operacao=buscar',
                beforeSend: function() {
                    setTimeout(function() {
                        $("#div-load").css("display", "none");
                    }, 500);
                },
                data: {
                    codRegra: buscaCodigoGrupo
                },
                success: function(msg) {
                    //alert(msg)
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.idRegra + "</td>";
                        linha = linha + "<td>" + object.codRegra + "</td>";
                        linha = linha + "<td>" + object.codExcecao + "</td>";
                        linha = linha + "<td>" + object.cFOPCaracTrib + "</td>";
                        linha = linha + "<td>" + object.cST + "</td>";
                        linha = linha + "<td>" + object.cSOSN + "</td>";
                        linha = linha + "<td>" + object.aliqIcmsInterna + "</td>";
                        linha = linha + "<td>" + object.aliqIcmsInterestadual + "</td>";
                        linha += "<td>";
                        linha += "<button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modalRegraFiscal' data-idRegra='" + object.idRegra + "'><i class='bi bi-eye'></i></button> ";
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        linha += "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarRegraFiscal' data-idRegra='" + object.idRegra + "'><i class='bi bi-pencil-square'></i></button> ";    
                        <?php } ?>
                        linha += "<td>";
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaCodigoGrupo").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaCodigoGrupo").val());
            }
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

        $(document).on('click', 'button[data-bs-target="#alterarRegraFiscal"]', function() {
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
                    $('#idRegra_regrafiscal_alterar').val(data.idRegra);
                    $('#codRegra_regrafiscal_alterar').val(data.codRegra);
                    $('#codExcecao_regrafiscal_alterar').val(data.codExcecao);
                    $('#dtVigIni_regrafiscal_alterar').val(data.dtVigIni);
                    $('#dtVigFin_regrafiscal_alterar').val(data.dtVigFin);
                    $('#cFOPCaracTrib_regrafiscal_alterar').val(data.cFOPCaracTrib);
                    $('#cST_regrafiscal_alterar').val(data.cST);
                    $('#cSOSN_regrafiscal_alterar').val(data.cSOSN);
                    $('#aliqIcmsInterna_regrafiscal_alterar').val(data.aliqIcmsInterna);
                    $('#aliqIcmsInterestadual_regrafiscal_alterar').val(data.aliqIcmsInterestadual);
                    $('#reducaoBcIcms_regrafiscal_alterar').val(data.reducaoBcIcms);
                    $('#reducaoBcIcmsSt_regrafiscal_alterar').val(data.reducaoBcIcmsSt);
                    $('#redBcICMsInterestadual_regrafiscal_alterar').val(data.redBcICMsInterestadual);
                    $('#aliqIcmsSt_regrafiscal_alterar').val(data.aliqIcmsSt);
                    $('#iVA_regrafiscal_alterar').val(data.iVA);
                    $('#iVAAjust_regrafiscal_alterar').val(data.iVAAjust);
                    $('#fCP_regrafiscal_alterar').val(data.fCP);
                    $('#codBenef_regrafiscal_alterar').val(data.codBenef);
                    $('#pDifer_regrafiscal_alterar').val(data.pDifer);
                    $('#pIsencao_regrafiscal_alterar').val(data.pIsencao);
                    $('#antecipado_regrafiscal_alterar').val(data.antecipado);
                    $('#desonerado_regrafiscal_alterar').val(data.desonerado);
                    $('#pICMSDeson_regrafiscal_alterar').val(data.pICMSDeson);
                    $('#isento_regrafiscal_alterar').val(data.isento);
                    $('#tpCalcDifal_regrafiscal_alterar').val(data.tpCalcDifal);
                    $('#ampLegal_regrafiscal_regrafiscal_alterar').val(data.ampLegal);
                    $('#Protocolo_regrafiscal_alterar').val(data.Protocolo);
                    $('#Convenio_regrafiscal_alterar').val(data.Convenio);
                    $('#regraGeral_regrafiscal_alterar').val(data.regraGeral);

                    $('#alterarRegraFiscal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }

            });
        });

        $(document).ready(function() {
            $("#form-inserirRegraFiscal").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/regrafiscal.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                    error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
                    }
                });
            });

            $("#form-alterarRegraFiscal").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/regrafiscal.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                    error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
                    }
                });
            });



            function refreshPage() {
                window.location.reload();
            }

        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
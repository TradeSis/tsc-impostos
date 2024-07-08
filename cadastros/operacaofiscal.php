<?php
//Lucas 29022024 - id862 Empresa Administradora
// lucas 15012024 criado
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/regrafiscal.php');
include_once(ROOT . '/admin/database/grupoproduto.php');
//preparado para select de estados
//include_once(ROOT . '/sistema/database/estados.php');
//$estados = buscaEstados();
$regrasfiscais = buscaCodigoRegra();
$grupos = buscaCodigoGrupos(null, null, null);

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
                <h2 class="ts-tituloPrincipal">Operação Fiscal</h2>
            </div>

            <div class="col-6 col-lg-6 d-none">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaCodigoGrupo" placeholder="Buscar por código">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <div class="col-6 col-lg-6 text-end">
                <!-- Lucas 29022024 - condi��o Administradora -->
                <?php if ($_SESSION['administradora'] == 1) { ?>
                    <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirOperacaoFiscalModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                <?php } ?>
            </div>

        </div>

        <!-- MODAL REGRA FISCAl -->
        <?php include_once 'modalregrafiscal.php' ?>

        <!--------- INSERIR --------->
        <div class="modal fade bd-example-modal-lg" id="inserirOperacaoFiscalModal" tabindex="-1" aria-labelledby="inserirOperacaoFiscalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Operação Fiscal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-inserirOperacaoFiscal">
                            <div class="row">
                                <div class="col-md">
                                    <label class="form-label ts-label">Grupo</label>
                                    <select class="form-select ts-input" name="idGrupo">
                                        <option value="<?php echo null ?>"></option>
                                        <?php
                                        foreach ($grupos as $grupo) {
                                        ?>
                                            <option value="<?php echo $grupo['idGrupo'] ?>">
                                                <?php echo $grupo['idGrupo'] . " - " . $grupo['nomeGrupo'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label ts-label">codigoEstado</label>
                                    <input type="text" class="form-control ts-input" name="codigoEstado">
                                </div>
                                <!--    PREPADO SELECT DE ESTADOS 
                                    <div class="col-md">
                                    <label class="form-label ts-label">codigoEstado</label>
                                    <select class="form-select ts-input" name="codigoEstado">
                                        <option value="<?php echo null ?>"></option>
                                        <?php
                                        foreach ($estados as $estado) {
                                        ?>
                                            <option value="<?php echo $estado['codigoEstado'] ?>">
                                                <?php echo $estado['codigoEstado'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div> -->
                                <div class="col-md-2">
                                    <label class="form-label ts-label">cFOP</label>
                                    <input type="text" class="form-control ts-input" name="cFOP">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">codigoCaracTrib</label>
                                    <input type="text" class="form-control ts-input" name="codigoCaracTrib">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">finalidade</label>
                                    <input type="text" class="form-control ts-input" name="finalidade">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">idRegra</label>
                                    <select class="form-select ts-input" name="idRegra">
                                        <option value="<?php echo null ?>"></option>
                                        <?php
                                        foreach ($regrasfiscais as $regrasfiscal) {
                                        ?>
                                            <option value="<?php echo $regrasfiscal['idRegra'] ?>">
                                                <?php echo "id: " .$regrasfiscal['idRegra'] . " - codigo: " . $regrasfiscal['codRegra']  ?>
                                            </option>
                                        <?php } ?>
                                    </select>
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

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>id</th>
                        <th>idGrupo</th>
                        <th>nomeGrupo</th>
                        <th>codigoEstado</th>
                        <th>cFOP</th>
                        <th>codigoCaracTrib</th>
                        <th>finalidade</th>
                        <th>idRegra</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>


    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#buscaCodigoGrupo").val());

        function limpar() {
            buscar(null);
            window.location.reload();
        }

        function buscar(buscaCodigoGrupo) {
            //alert(buscaCodigoGrupo);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/operacaofiscal.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    idGrupo: buscaCodigoGrupo
                },
                success: function(msg) {

                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.idoperacaofiscal + "</td>";
                        linha = linha + "<td>" + object.idGrupo + "</td>";
                        linha = linha + "<td>" + object.nomeGrupo + "</td>";
                        linha = linha + "<td>" + object.codigoEstado + "</td>";
                        linha = linha + "<td>" + object.cFOP + "</td>";
                        linha = linha + "<td>" + object.codigoCaracTrib + "</td>";
                        linha = linha + "<td>" + object.finalidade + "</td>";
                        linha = linha + "<td>" + object.idRegra + "</td>";
                        linha = linha + "<td>" + "<button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modalRegraFiscal' data-idRegra='" + object.idRegra + "'><i class='bi bi-eye'></i></button> ";
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


        $(document).ready(function() {
            $("#form-inserirOperacaoFiscal").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/operacaofiscal.php?operacao=inserir",
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
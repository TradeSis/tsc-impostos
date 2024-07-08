<?php
// lucas 20032024 criado
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
                <h2 class="ts-tituloPrincipal">Historico</h2>
            </div>

            <div class="col-6 col-lg-6 text-end">
                <!-- Lucas 29022024 - condição Administradora -->
                <?php if ($_SESSION['administradora'] == 1) { ?>
                    <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirHistorico"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                <?php } ?>
            </div>

        </div>

        <div class="modal fade bd-example-modal-lg" id="visualizarHistorico" tabindex="-1" aria-labelledby="visualizarHistoricoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Historico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md">
                                <label class="form-label ts-label">idHistorico</label>
                                <input type="text" class="form-control ts-input" name="idHistorico" id="idHistorico" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">dtHistorico</label>
                                <input type="text" class="form-control ts-input" name="dtHistorico" id="dtHistorico" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">sugestao</label>
                                <input type="text" class="form-control ts-input" name="sugestao" id="sugestao" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">amb</label>
                                <input type="text" class="form-control ts-input" name="amb" id="amb" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cnpj</label>
                                <input type="text" class="form-control ts-input" name="cnpj" id="cnpj" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">dthr</label>
                                <input type="text" class="form-control ts-input" name="dthr" id="dthr" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">transacao</label>
                                <input type="text" class="form-control ts-input" name="transacao" id="transacao" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">mensagem</label>
                                <input type="text" class="form-control ts-input" name="mensagem" id="mensagem" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">prodEnv</label>
                                <input type="text" class="form-control ts-input" name="prodEnv" id="prodEnv" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">prodRet</label>
                                <input type="text" class="form-control ts-input" name="prodRet" id="prodRet" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">prodNaoRet</label>
                                <input type="text" class="form-control ts-input" name="prodNaoRet" id="prodNaoRet" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">comportamentosParceiro</label>
                                <input type="text" class="form-control ts-input" name="comportamentosParceiro" id="comportamentosParceiro" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">comportamentosCliente</label>
                                <input type="text" class="form-control ts-input" name="comportamentosCliente" id="comportamentosCliente" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">versao</label>
                                <input type="text" class="form-control ts-input" name="versao" id="versao" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">duracao</label>
                                <input type="text" class="form-control ts-input" name="duracao" id="duracao" readonly>
                            </div>
                        </div>

                    </div><!--body-->

                </div>
            </div>
        </div>

        <!-- MODAL INSERIR  -->
        <div class="modal fade bd-example-modal-lg" id="inserirHistorico" tabindex="-1" aria-labelledby="inserirHistoricoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Historico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-inserirHistorico">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label ts-label">dtHistorico *</label>
                                <input type="datetime-local" class="form-control ts-input" name="dtHistorico">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">sugestao</label>
                                <input type="text" class="form-control ts-input" name="sugestao">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">amb</label>
                                <input type="text" class="form-control ts-input" name="amb">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cnpj</label>
                                <input type="text" class="form-control ts-input" name="cnpj">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">dthr</label>
                                <input type="datetime-local" class="form-control ts-input" name="dthr">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">transacao</label>
                                <input type="text" class="form-control ts-input" name="transacao">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">mensagem</label>
                                <input type="text" class="form-control ts-input" name="mensagem">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">prodEnv</label>
                                <input type="text" class="form-control ts-input" name="prodEnv">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">prodRet</label>
                                <input type="text" class="form-control ts-input" name="prodRet">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">prodNaoRet</label>
                                <input type="text" class="form-control ts-input" name="prodNaoRet">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">comportamentosParceiro</label>
                                <input type="text" class="form-control ts-input" name="comportamentosParceiro">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">comportamentosCliente</label>
                                <input type="text" class="form-control ts-input" name="comportamentosCliente">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">versao</label>
                                <input type="text" class="form-control ts-input" name="versao">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">duracao</label>
                                <input type="text" class="form-control ts-input" name="duracao">
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
                        <th>amb</th>
                        <th>cnpj</th>
                        <th>dthr</th>
                        <th>transacao</th>
                        <th>mensagem</th>
                        <th>prodEnv</th>
                        <th>prodRet</th>
                        <th>prodNaoRet</th>
                        <th></th>
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

        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo URLROOT ?>/impostos/database/fishistorico.php?operacao=buscar',
            beforeSend: function() {
                setTimeout(function() {
                    $("#div-load").css("display", "none");
                }, 500);
            },
            data: {},
            success: function(msg) {
                //alert("segundo alert: " + msg);
                var json = JSON.parse(msg);

                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    linha = linha + "<tr>";

                    linha = linha + "<td>" + object.idHistorico + "</td>";
                    linha = linha + "<td>" + object.amb + "</td>";
                    linha = linha + "<td>" + object.cnpj + "</td>";
                    linha = linha + "<td>" + object.dthr + "</td>";
                    linha = linha + "<td>" + object.transacao + "</td>";
                    linha = linha + "<td>" + object.mensagem + "</td>";
                    linha = linha + "<td>" + object.prodEnv + "</td>";
                    linha = linha + "<td>" + object.prodRet + "</td>";
                    linha = linha + "<td>" + object.prodNaoRet + "</td>";

                    linha += "<td><button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#visualizarHistorico' data-idHistorico='" + object.idHistorico + "'><i class='bi bi-eye'></i></button></td> ";

                    linha = linha + "</tr>";
                }
                $("#dados").html(linha);
            }
        });


        $(document).on('click', 'button[data-bs-target="#visualizarHistorico"]', function() {
            var idHistorico = $(this).attr("data-idHistorico");
            //alert(idHistorico);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/impostos/database/fishistorico.php?operacao=buscar',
                data: {
                    idHistorico: idHistorico
                },
                success: function(data) {
                    //alert(data)

                    $('#idHistorico').val(data.idHistorico);
                    $('#dtHistorico').val(data.dtHistorico);
                    $('#sugestao').val(data.sugestao);
                    $('#amb').val(data.amb);
                    $('#cnpj').val(data.cnpj);
                    $('#dthr').val(data.dthr);
                    $('#transacao').val(data.transacao);
                    $('#mensagem').val(data.mensagem);
                    $('#prodEnv').val(data.prodEnv);
                    $('#prodRet').val(data.prodRet);
                    $('#prodNaoRet').val(data.prodNaoRet);
                    $('#comportamentosParceiro').val(data.comportamentosParceiro);
                    $('#comportamentosCliente').val(data.comportamentosCliente);
                    $('#versao').val(data.versao);
                    $('#duracao').val(data.duracao);

                    $('#visualizarHistorico').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }
            });
        });

        $(document).ready(function() {
            $("#form-inserirHistorico").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fishistorico.php?operacao=inserir",
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
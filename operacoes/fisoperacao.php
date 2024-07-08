<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/fisoperacao.php');
include_once(__DIR__ . '/../database/fisatividade.php');
include_once(__DIR__ . '/../database/fisnatureza.php');
include_once(__DIR__ . '/../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();
$operacoes = buscaOperacao();

$filtroEntrada = null;
$dadosOp = null;
$FiltroTipoOp = null;
$idAtividade = null;
$idProcesso = null;
$idNatureza = null;


if (isset($_SESSION['filtro_operacao'])) {
    $filtroEntrada = $_SESSION['filtro_operacao'];
    $FiltroTipoOp = $filtroEntrada['FiltroTipoOp'];
    $dadosOp = $filtroEntrada['dadosOp'];
    $idAtividade = $filtroEntrada['idAtividade'];
    $idProcesso = $filtroEntrada['idProcesso'];
    $idNatureza = $filtroEntrada['idNatureza'];
}
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>

    <div class="container-fluid">

        <div class="row">
            <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!-- BOTOES AUXILIARES -->
        </div>

        <div class="row align-items-center">
            <div class="col-6 order-4 col-sm-6 col-md mt-1-6 order-md-4 col-lg-1 order-lg-1 mt-3 text-start">
                <button class="btn btn-outline-secondary ts-btnFiltros" type="button"><i class="bi bi-funnel"></i></button>
            </div>

            <div class="col-10 order-1 col-sm-11 col-md mt-1-11 order-md-1 col-lg-2 order-lg-2 mt-4">
                <h2 class="ts-tituloPrincipal">Operações Fiscais</h2>
                <h6 style="font-size: 10px;font-style:italic;text-align:left;"></h6>
            </div>

            <div class="col-12 order-3 col-sm-12 col-md mt-1-12 col-lg-5 order-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="busca" placeholder="Buscar por id">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;"
                                class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>

            <div class="col-2 order-2 col-sm-1 col-md mt-1-1 order-md-2 col-lg-2 order-lg-4">
            </div>
            <?php if ($_SESSION['administradora'] == 1) { ?>
                <div class="col-6 order-5 col-sm-6 col-md mt-1-6 order-md-4 col-lg-2 order-lg-5 mt-1 text-end">
                    <button type="button" class="btn btn-success mr-4" data-bs-toggle="modal"
                        data-bs-target="#inserirModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                </div>
            <?php }  ?>
            
        </div>

        <div class="ts-menuFiltros mt-2 px-3">
            <label>Filtrar por:</label>

            <div class="col-sm text-end mt-2">
                <a onClick="limpar()" role=" button" class="btn btn-sm bg-info text-white">Limpar</a>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros">
            <table class="table table-hover table-sm">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Operação</th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idAtividade"
                                    id="FiltroAtividade">
                                    <option value="<?php echo null ?>">
                                        <?php echo " Atividade" ?>
                                    </option>
                                    <?php
                                    foreach ($atividades as $atividade) {
                                        ?>
                                        <option <?php
                                        if ($atividade['idAtividade'] == $idAtividade) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $atividade['idAtividade'] ?>">
                                            <?php echo $atividade['nomeAtividade'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idProcesso"
                                    id="FiltroProcesso">
                                    <option value="<?php echo null ?>">
                                        <?php echo " Processo" ?>
                                    </option>
                                    <?php
                                    foreach ($processos as $processo) {
                                        ?>
                                        <option <?php
                                        if ($processo['idProcesso'] == $idProcesso) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $processo['idProcesso'] ?>">
                                            <?php echo $processo['nomeProcesso'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idNatureza"
                                    id="FiltroNatureza">
                                    <option value="<?php echo null ?>">
                                        <?php echo " Natureza" ?>
                                    </option>
                                    <?php
                                    foreach ($naturezas as $natureza) {
                                        ?>
                                        <option <?php
                                        if ($natureza['idNatureza'] == $idNatureza) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $natureza['idNatureza'] ?>">
                                            <?php echo $natureza['nomeNatureza'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th>idGrupoOper</th>
                        <th>idEntSai</th>
                        <th colspan="2">xfop</th>
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>Ação</th>
                        <?php } else{ ?>
                            <th></th> 
                        <?php }    ?>
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
    </div>

    <!--------- INSERIR --------->
    <div class="modal" id="inserirModal" tabindex="-1" aria-labelledby="inserirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir CP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form-inserirOperacao">

                        <label class="form-label ts-label">Nome da operação</label>
                        <input type="text" name="nomeOperacao" class="form-control ts-input" autocomplete="off">

                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">Atividade</label>
                                <select class="form-select ts-input" name="idAtividade">
                                    <?php
                                    foreach ($atividades as $atividade) {
                                        ?>
                                    <option value="<?php echo $atividade['idAtividade'] ?>">
                                        <?php echo $atividade['nomeAtividade'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">Processo</label>
                                <select class="form-select ts-input" name="idProcesso">
                                    <?php
                                    foreach ($processos as $processo) {
                                        ?>
                                    <option value="<?php echo $processo['idProcesso'] ?>">
                                        <?php echo $processo['nomeProcesso'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">Natureza</label>
                                <select class="form-select ts-input" name="idNatureza">
                                    <?php
                                    foreach ($naturezas as $natureza) {
                                        ?>
                                    <option value="<?php echo $natureza['idNatureza'] ?>">
                                        <?php echo $natureza['nomeNatureza'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">idGrupoOper</label>
                                <input type="number" name="idGrupoOper" class="form-control ts-input"
                                    autocomplete="off">
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">idEntSai</label>
                                <input type="number" name="idEntSai" class="form-control ts-input" autocomplete="off">
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">xfop</label>
                                <input type="text" name="xfop" class="form-control ts-input" autocomplete="off">
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cfop</label>
                                <input type="text" name="cfop" class="form-control ts-input" autocomplete="off">
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <div class="card-footer bg-transparent text-end">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--------- ALTERAR --------->
    <div class="modal" id="alterarmodal" tabindex="-1" aria-labelledby="alterarmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar CP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                    <form method="post" id="form-alterarOperacao">


                        <input type="hidden" class="form-control ts-input" name="idOperacao">

                        <label class="form-label ts-label">Nome da operação</label>
                        <input type="text" class="form-control ts-input" name="nomeOperacao" id="nomeOperacao">

                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">Atividade</label>
                                <select class="form-select ts-input" name="idAtividade" id="idAtividade" disabled>
                                    <?php
                                    foreach ($atividades as $atividade) { ?>
                                    <option value="<?php echo $atividade['idAtividade'] ?>">
                                        <?php echo $atividade['nomeAtividade'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">Processo</label>
                                <select class="form-select ts-input" name="idProcesso" id="idProcesso" disabled>
                                    <?php
                                    foreach ($processos as $processo) {
                                        ?>
                                    <option value="<?php echo $processo['idProcesso'] ?>">
                                        <?php echo $processo['nomeProcesso'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">Natureza</label>
                                <select class="form-select ts-input" name="idNatureza" id="idNatureza" disabled>
                                    <?php
                                    foreach ($naturezas as $natureza) {
                                        ?>
                                    <option value="<?php echo $natureza['idNatureza'] ?>">
                                        <?php echo $natureza['nomeNatureza'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">idGrupoOper</label>
                                <input type="text" class="form-control ts-input" name="idGrupoOper" id="idGrupoOper" disabled>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">idEntSai</label>
                                <input type="text" class="form-control ts-input" name="idEntSai" id="idEntSai" disabled>
                            </div>

                            <div class="col-md">
                                <label class="form-label ts-label">xfop</label>
                                <input type="text" class="form-control ts-input" name="xfop" id="xfop" disabled>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cfop</label>
                                <input type="text" class="form-control ts-input" name="cfop" id="cfop">
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <div class="card-footer bg-transparent text-end">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());

        function limpar() {
            buscar(null, null, null, null, null);
            window.location.reload();
        }

        function buscar(FiltroTipoOp, dadosOp, idAtividade, idProcesso, idNatureza) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/fisoperacao.php?operacao=filtrar',
                beforeSend: function() {
                    setTimeout(function() {
                        $("#div-load").css("display", "none");
                    }, 500);
                },
                data: {
                    FiltroTipoOp: FiltroTipoOp,
                    dadosOp: dadosOp,
                    idAtividade: idAtividade,
                    idProcesso: idProcesso,
                    idNatureza: idNatureza
                },
                success: function (msg) {
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.nomeOperacao + "</td>";
                        linha = linha + "<td>" + object.nomeAtividade + "</td>";
                        linha = linha + "<td>" + object.nomeProcesso + "</td>";
                        linha = linha + "<td>" + object.nomeNatureza + "</td>";
                        linha = linha + "<td>" + object.idGrupoOper + "</td>";
                        linha = linha + "<td>" + object.idEntSai + "</td>";
                        linha = linha + "<td>" + (object.xfop !== null ? object.xfop : "")  + "</td>";
                        linha = linha + "<td>" + (object.cfopOposto !== null ? object.cfopOposto : "") + "</td>";
                        linha = linha + "<td id='botao'>";
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        linha = linha + "<button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#alterarmodal' data-idOperacao='" + object.idOperacao + "'><i class='bi bi-pencil-square'></i></button>" 
                        linha = linha + "<a class='btn btn-danger btn-sm' href='fisoperacao_excluir.php?idOperacao=" + object.idOperacao + "' role='button'><i class='bi bi-trash'></i></i></a>"
                        <?php } ?>
                        linha = linha + "</td>";
                        linha = linha + "</tr>";

                    }

                    $("#dados").html(linha);
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $("#FiltroAtividade").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $("#FiltroProcesso").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $("#FiltroNatureza").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $(document).ready(function () {
            $("#buscar").click(function () {
                buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
            });

            $(document).keypress(function (e) {
                if (e.key === "Enter") {
                    buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
                }
            });
        });

        /*  $('.btnAbre').click(function () {
             $('.menuFiltros').toggleClass('mostra');
             $('.diviFrame').toggleClass('mostra');
         }); */
        /* Novo script para menu filtros */
        $('.ts-btnFiltros').click(function () {
            $('.ts-menuFiltros').toggleClass('mostra');
            $('.ts-tableFiltros').toggleClass('mostra');
        });

        $(document).on('click', 'button[data-target="#alterarmodal"]', function() {
            var idOperacao = $(this).attr("data-idOperacao");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/impostos/database/fisoperacao.php?operacao=buscar',
                data: {
                    idOperacao: idOperacao
                },
                success: function(data) {
                    $('#idOperacao').val(data.idOperacao);
                    $('#nomeOperacao').val(data.nomeOperacao);
                    $('#idAtividade').val(data.idAtividade);
                    $('#idProcesso').val(data.idProcesso);
                    $('#idNatureza').val(data.idNatureza);
                    $('#idGrupoOper').val(data.idGrupoOper);
                    $('#idEntSai').val(data.idEntSai);
                    $('#xfop').val(data.xfop);
                    $('#cfop').val(data.cfop);
                    $('#alterarmodal').modal('show');
                }
            });
        });

        $(document).ready(function () {
            $("#form-inserirOperacao").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisoperacao.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-alterarOperacao").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisoperacao.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
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
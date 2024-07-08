<?php
//Lucas 29022024 - id862 Empresa Administradora
//Lucas 13102023 novo padrao
// gabriel 060623 15:06
include_once(__DIR__ . '/../header.php');
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> BOTOES AUXILIARES -->
        </div>
        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Atividades Fiscais</h2>
            </div>
            <div class="col">
                <!-- FILTROS -->
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaAtividade" placeholder="Buscar por id ou titulo">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <!-- Lucas 29022024 - condição Administradora -->
            <?php if ($_SESSION['administradora'] == 1) { ?>
            <div class="col-2 text-end">
                <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirAtividadeModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
            <?php } ?>

        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Atividade</th>
                        <!-- Lucas 29022024 - condição Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>AÃ§Ã£o</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

            </table>
        </div>
        <!-- div de loading -->
        <div class="text-center" id="div-load" style="margin-top: -300px;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>

        <!--------- INSERIR --------->
       <div class="modal fade bd-example-modal-lg" id="inserirAtividadeModal" tabindex="-1" aria-labelledby="inserirAtividadeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Atividade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-inserirAtividade">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row mt-3">
                                        <div class="col-md">
                                            <label class="form-label ts-label">Nome Atividade</label>
                                            <input type="text" class="form-control ts-input" name="nomeAtividade">
                                        </div>
                                    </div><!--fim row 1-->
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

        <!--------- ALTERAR --------->
        <div class="modal fade bd-example-modal-lg" id="alterarAtividadeModal" tabindex="-1" aria-labelledby="alterarAtividadeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Atividade</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-alterarAtividade">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row mt-3">
                                        <div class="col-md">
                                            <label class="form-label ts-label">Nome Atividade</label>
                                            <input type="text" class="form-control ts-input" disabled name="nomeAtividade" id="nomeAtividade">
                                        </div>
                                    </div><!--fim row 1-->
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#buscaAtividade").val());

        function limpar() {
            buscar(null);
            window.location.reload();
        }

        function buscar(buscaAtividade) {
            //alert (buscaAtividade);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/impostos/database/fisatividade.php?operacao=filtrar',
                beforeSend: function() {
                    setTimeout(function() {
                        $("#div-load").css("display", "none");
                    }, 500);
                },
                data: {
                    buscaAtividade: buscaAtividade
                },
                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.nomeAtividade + "</td>";
                        linha = linha + "<td>" + "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarAtividadeModal' data-idAtividade='" + object.idAtividade + "'><i class='bi bi-pencil-square'></i></button> "
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaAtividade").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaAtividade").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#alterarAtividadeModal"]', function() {
                var idAtividade = $(this).attr("data-idAtividade");
                //alert(idAtividade)
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisatividade.php?operacao=buscar',
                    data: {
                        idAtividade: idAtividade
                    },
                    success: function(data) {
                        $('#idAtividade').val(data.idAtividade);
                        $('#nomeAtividade').val(data.nomeAtividade);
                        $('#alterarAtividadeModal').modal('show');
                    }
                });
            });


        $(document).ready(function() {
            $("#form-inserirAtividade").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisatividade.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-alterarAtividade").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisatividade.php?operacao=alterar",
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
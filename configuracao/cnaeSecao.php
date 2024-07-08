<?php
//Lucas 29022024 - id862 Empresa Administradora
//Lucas 13102023 novo padrao
// gabriel 060623 15:06
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/caracTrib.php');

$caracTribs = buscaCaracTrib();
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
                <h2 class="ts-tituloPrincipal">CNAE Secao</h2>
            </div>
            <div class="col">
                <!-- FILTROS -->
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscacnae" placeholder="Buscar por Secao">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <!-- Lucas 29022024 - condi��o Administradora -->
            <?php if ($_SESSION['administradora'] == 1) { ?>
            <div class="col-2 text-end">
                <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserircnaeModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
            <?php } ?>

        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>idcnSecao</th>
                        <th>descricao</th>
                        <th>caracTrib</th>
                        <!-- Lucas 29022024 - condi��o Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>Ação</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

            </table>
        </div>

        <!--------- INSERIR --------->
       <div class="modal fade bd-example-modal-md" id="inserircnaeModal" tabindex="-1" aria-labelledby="inserircnaeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Seções CNAE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-inserircnae">
                        <div class="container-fluid p-0 text-center">
                            <h4>Confirma atualização das Seções CNAE?</h4>
                        </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-formInserir">Atualizar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--------- ALTERAR --------->
        <div class="modal fade bd-example-modal-lg" id="alterarcnaeModal" tabindex="-1" aria-labelledby="alterarcnaeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Seção CNAE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-alterarcnae">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row mt-3">
                                        <div class="col-md">
                                            <label class="form-label ts-label">ID Seção CNAE</label>
                                            <input type="text" class="form-control ts-input" name="idcnSecao" id="idcnSecao" readonly>
                                        </div>
                                        <div class="col-md">
                                            <label class="form-label ts-label">Caracteristica Tributária</label>
                                            <select class="form-select ts-input" name="caracTrib" id="caracTrib">
                                                <?php foreach ($caracTribs as $caracTrib) { ?>
                                                <option value="<?php echo $caracTrib['caracTrib'] ?>"><?php echo $caracTrib['caracTrib'] ?> - <?php echo $caracTrib['descricaoCaracTrib'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div><!--fim row 1-->
                                    <div class="row mt-3">
                                        <div class="col-md">
                                            <label class="form-label ts-label">Descriçao Seção CNAE</label>
                                            <input type="text" class="form-control ts-input" name="descricao" id="descricao" readonly> 
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
        buscar($("#buscacnae").val());

        function limpar() {
            buscar(null);
            window.location.reload();
        }

        function buscar(buscacnae) {
            //alert (buscacnae);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/impostos/database/cnae.php?operacao=buscarSecao',
                beforeSend: function() {
                    setTimeout(function() {
                        $("#div-load").css("display", "none");
                    }, 500);
                },
                data: {
                    buscacnae: buscacnae
                },
                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.idcnSecao + "</td>";
                        linha = linha + "<td>" + object.descricao + "</td>";
                        linha = linha + "<td>" + object.caracTrib + "</td>";
                        linha = linha + "<td>" + "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarcnaeModal' data-idcnSecao='" + object.idcnSecao + "'><i class='bi bi-pencil-square'></i></button> "
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscacnae").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscacnae").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#alterarcnaeModal"]', function() {
                var idcnSecao = $(this).attr("data-idcnSecao");
                //alert(cnae)
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo URLROOT ?>/impostos/database/cnae.php?operacao=buscarSecao',
                    data: {
                        idcnSecao: idcnSecao
                    },
                    success: function(data) {
                        $('#idcnSecao').val(data.idcnSecao);
                        $('#descricao').val(data.descricao);
                        $('#caracTrib').val(data.caracTrib);
                        $('#alterarcnaeModal').modal('show');
                    }
                });
            });


        $(document).ready(function() {
            $("#form-inserircnae").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT ?>/impostos/database/cnae.php?operacao=atualizaSecao",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-alterarcnae").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT ?>/impostos/database/cnae.php?operacao=alterarSecao",
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
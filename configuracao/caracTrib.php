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
                <h2 class="ts-tituloPrincipal">Caracteristica Tribut√°ria</h2>
            </div>
            <div class="col">
                <!-- FILTROS -->
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaCaracTrib" placeholder="Buscar por caracTrib">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <!-- Lucas 29022024 - condiÁ„o Administradora -->
            <?php if ($_SESSION['administradora'] == 1) { ?>
            <div class="col-2 text-end">
                <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirCaracTribModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
            <?php } ?>

        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>caracTrib</th>
                        <th>descricaoCaracTrib</th>
                        <!-- Lucas 29022024 - condiÁ„o Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>A√ß√£o</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

            </table>
        </div>

        <!--------- INSERIR --------->
       <div class="modal fade bd-example-modal-lg" id="inserirCaracTribModal" tabindex="-1" aria-labelledby="inserirCaracTribModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Caracteristica Tribut√°ria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-inserirCaracTrib">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label ts-label">caracTrib</label>
                                            <input type="text" class="form-control ts-input" name="caracTrib">
                                        </div>
                                        <div class="col-md">
                                            <label class="form-label ts-label">Descri√ßao CaracTrib</label>
                                            <input type="text" class="form-control ts-input" name="descricaoCaracTrib">
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
        <div class="modal fade bd-example-modal-lg" id="alterarCaracTribModal" tabindex="-1" aria-labelledby="alterarCaracTribModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Caracteristica Tribut√°ria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="form-alterarCaracTrib">
                            <div class="row">
                                <div class="col-md">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label ts-label">caracTrib</label>
                                            <input type="text" class="form-control ts-input" name="caracTrib" id="caracTrib" readonly>
                                        </div>
                                        <div class="col-md">
                                            <label class="form-label ts-label">Descri√ßao CaracTrib</label>
                                            <input type="text" class="form-control ts-input" name="descricaoCaracTrib" id="descricaoCaracTrib"> 
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
        buscar($("#buscaCaracTrib").val());

        function limpar() {
            buscar(null);
            window.location.reload();
        }

        function buscar(buscaCaracTrib) {
            //alert (buscaCaracTrib);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/impostos/database/caracTrib.php?operacao=buscar',
                beforeSend: function() {
                    setTimeout(function() {
                        $("#div-load").css("display", "none");
                    }, 500);
                },
                data: {
                    buscaCaracTrib: buscaCaracTrib
                },
                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.caracTrib + "</td>";
                        linha = linha + "<td>" + object.descricaoCaracTrib + "</td>";
                        linha = linha + "<td>" + "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarCaracTribModal' data-caracTrib='" + object.caracTrib + "'><i class='bi bi-pencil-square'></i></button> "
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaCaracTrib").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaCaracTrib").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#alterarCaracTribModal"]', function() {
                var caracTrib = $(this).attr("data-caracTrib");
                //alert(caracTrib)
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo URLROOT ?>/impostos/database/caracTrib.php?operacao=buscar',
                    data: {
                        caracTrib: caracTrib
                    },
                    success: function(data) {
                        $('#caracTrib').val(data.caracTrib);
                        $('#descricaoCaracTrib').val(data.descricaoCaracTrib);
                        $('#alterarCaracTribModal').modal('show');
                    }
                });
            });


        $(document).ready(function() {
            $("#form-inserirCaracTrib").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT ?>/impostos/database/caracTrib.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-alterarCaracTrib").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT ?>/impostos/database/caracTrib.php?operacao=alterar",
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
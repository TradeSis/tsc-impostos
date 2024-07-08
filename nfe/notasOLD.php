<?php
include_once(__DIR__ . '/../header.php');

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Notas Fiscais</h2>
            </div>
            <div class="col-6">
                <!-- FILTROS -->
            </div>

            <div class="col-3 text-end">
                <div class="row">

                    <div class="col-6">
                        <button class="btn btn-warning" id="processarGeral-btn" type="button" title="Processar todos XMLs">
                            <span class="spinner-border-sm span-load" role="status" aria-hidden="true"></span>
                            Processar
                        </button>
                    </div>

                    <div class="col-6">
                        <form id="uploadForm" method="POST" enctype="multipart/form-data">
                            <input type="file" id="arquivo" class="custom-file-upload" name="file[]" style="color:#567381; display:none" multiple>
                            <label for="arquivo">
                                <a class="btn btn-primary">
                                    <i class="bi bi-file-earmark-arrow-down-fill" style="color:#fff"></i>&#32;<h7 style="color: #fff;">Arquivo</h7>
                                </a>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros">
            <table class="table table-hover table-sm">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>nNF</th>
                        <th>dhEmi</th>
                        <th>emit</th>
                        <th>emite</th>
                        <th>XML</th>
                        <th>total</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody id='dados' class="fonteCorpo">
                    <?php
                    if (!isset($notas['status'])) {
                        foreach ($notas as $nota) { ?>
                            <tr>
                                <td> <?php echo $nota['NF'] ?> </td>
                                <td> <?php echo date('d/m/Y', strtotime($nota['dtEmissao']))  ?> </td>
                                <td> <?php echo $nota['emitente_cpfCnpj'] ?> </td>
                                <td> <?php echo ($nota['emitente_nomeFantasia'] != null) ? $nota['emitente_nomeFantasia'] : $nota['emitente_nomePessoa']; ?> </td>
                                <td> <?php echo $nota['chaveNFe'] ?> </td>
                                <td> <?php echo number_format($nota['vNF'], 2, ',', '.') ?> </td>
                                <td> <?php echo $nota['nomeStatusNota'] ?> </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="visualizar.php?idNota=<?php echo $nota['idNota'] ?>" role="button"><i class="bi bi-eye-fill"></i></a>
                                    <button type="button" class="btn btn-success btn-sm" id="baixar" data-idNota="<?php echo $nota['idNota'] ?>" title="Baixar XML"><i class="bi bi-download"></i></button>
                                    <?php if ($nota['idStatusNota'] == 0) { ?>
                                        <button type="button" class="btn btn-warning btn-sm processar-btn" data-idNota="<?php echo $nota['idNota'] ?>" title="Processar XML"><i class="bi bi-check-circle-fill"></i></button>
                                    <?php } ?>
                                </td>

                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <!-- div de loading -->
        <div class="text-center" id="div-load" style="margin-top: -200px; display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>



    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function() {
            $('#arquivo').on('change', function() {
                $('body').css('cursor', 'progress');
                var fileInput = document.getElementById('arquivo');
                var files = fileInput.files;

                if (files.length > 0) {
                    var formData = new FormData();

                    for (var i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }

                    $.ajax({
                        type: 'POST',
                        url: "../database/fisnota.php?operacao=inserir",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            setTimeout(function() {
                                $("#div-load").css("display", "block");
                            }, 500);
                        },
                        success: function(msg) {
                            refreshPage();
                            /* console.log(msg);
                            $('body').css('cursor', 'default');
                            var message = JSON.parse(msg);
                            if (message.status === 200) {
                                refreshPage('xml');
                            }
                            if (message.status === 400) {
                                alert(message.retorno);
                                refreshPage('xml');
                            } */
                        }
                    });
                }
            });

            $('#baixar').click(function() {
                var idNota = $(this).attr("data-idNota");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=buscarNota",
                    data: {
                        idNota: idNota
                    },
                    success: function(msg) {
                        var xmlContent = msg.XML;
                        var blob = new Blob([xmlContent], {
                            type: 'application/xml'
                        });
                        var filename = msg.chaveNFe;
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();
                    }
                });
            });

            $('.processar-btn').click(function() {
                $('body').css('cursor', 'progress');
                var idNota = $(this).attr("data-idNota");

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=processarXML",
                    data: {
                        idNota: idNota
                    },
                    beforeSend: function() {
                        setTimeout(function() {
                            $("#div-load").css("display", "block");
                        }, 500);
                    },
                    success: function(msg) {
                        $('body').css('cursor', 'default');
                        if (msg.status === 200) {
                            refreshPage('xml');
                        }
                        if (msg.status === 400) {
                            alert(msg.retorno);
                            refreshPage('xml');
                        }
                    }
                });
            });


            $('#processarGeral-btn').click(function() {
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=processarGeral",
                    beforeSend: function() {
                        setTimeout(function() {
                            $("#processarGeral-btn").prop('disabled', true);
                            $(".span-load").addClass("spinner-border");

                        }, 500);
                    },
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

        function refreshPage(tab) {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab;
            window.location.href = newUrl;
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
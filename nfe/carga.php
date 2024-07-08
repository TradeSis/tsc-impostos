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
                <h2 class="ts-tituloPrincipal">Carga Notas Fiscais</h2>

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
                            <input type="file" id="arquivo" class="custom-file-upload" name="file[]" accept="text/xml" style="color:#567381; display:none" multiple>
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

        <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
            <table class="table table-hover table-sm">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>nNF</th>
                        <th>dhEmi</th>
                        <th>XML</th>
                        <th class="text-end">total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody id='dados' class="fonteCorpo">

                    <?php
                    $contador = 0;
                    if (!isset($cargas['status'])) {
                        foreach ($cargas as $nota) {
                            $contador += 1;  ?>

                            <tr>
                                <td> <?php echo $nota['NF'] ?> </td>
                                <td> <?php echo date('d/m/Y', strtotime($nota['dtEmissao']))  ?> </td>
                                <td> <?php echo $nota['chaveNFe'] ?> </td>
                                <td class="text-end"> <?php echo number_format($nota['vNF'], 2, ',', '.') ?> </td>
                                <td class="text-center statusnotaprocessando"> <?php echo $nota['nomeStatusNota'] ?> </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm" id="baixar" data-idNota="<?php echo $nota['idNota'] ?>" title="Baixar XML"><i class="bi bi-download"></i></button>
                                    <?php if ($nota['idStatusNota'] == 0) { ?>
                                        <button type="button" class="btn btn-warning btn-sm processar-btn" data-idNota="<?php echo $nota['idNota'] ?>" title="Processar XML">
                                            <i class="bi bi-check-circle-fill icon-processar"></i>
                                            <span class="spinner-border-sm span-load" role="status" aria-hidden="true"></span>
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>

        <h6 id="textocontadorCarga" style="color: #13216A;"></h6>
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
        var texto = $("#textocontadorCarga");
        texto.html('total de notas: ' + <?php echo $contador ?>);

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
                            $("#div-load").css("display", "none");
                           
                            var json = JSON.parse(msg);
                            //alert(JSON.stringify(msg));
                            //console.log(JSON.stringify(msg, null, 2));
                            var linha = "";
                            for (var $i = 0; $i < json.length; $i++) {
                                var object = json[$i];
                
                                linha = linha + "<tr>";
                                linha = linha + "<td>" + object.nomeXml + "</td>";
                                linha = linha + "<td>" + object.descricao + "</td>";
                                linha = linha + "</tr>";

                            }

                            $("#carga").html(linha);
                            $('#modalNfeCarga').modal('show');
                          
                        },
                        error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
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
                    success: refreshPage,
                    error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
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
                            $(".icon-processar").hide();

                        }, 500);
                    },
                    success: refreshPage
                    /* error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
                    } */
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
    <script>
        var tab;
        var tabContent;

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'xml') {
                showTabsContent(1);
            }
        }

        document.getElementById('ts-tabs').onclick = function(event) {
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
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
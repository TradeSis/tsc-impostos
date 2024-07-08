<?php
include_once(__DIR__ . '/../header.php');
?>

<body>


        <div class="container-fluid">
            <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                <div class="col-3 text-start">
                    <!-- TITULO -->
                    <h2 class="ts-tituloPrincipal">NCM</h2>
                </div>
                <div class="col-3">
                    <!-- FILTROS -->
                    <form method="post">
                        <select class="form-select ts-input" name="FiltroTipoNcm" id="FiltroTipoNcm">
                            <option <?php if ($FiltroTipoNcm == "Descricao") {
                                        echo "selected";
                                    } ?> value="Descricao">Descrição</option>
                            <option <?php if ($FiltroTipoNcm == "codigoNcm") {
                                        echo "selected";
                                    } ?> value="codigoNcm">Código Ncm</option>
                        </select>
                    </form>
                </div>
                <div class="col-4">
                    <!-- FILTROS -->
                    <div class="input-group">
                        <?php if (!empty($dadosNcm)) { ?>
                            <input type="text" class="form-control ts-input" id="dadosNcm" value="<?php echo $dadosNcm ?>">
                        <?php } else { ?>
                            <input type="text" class="form-control ts-input" id="dadosNcm" placeholder="Codigo">
                        <?php } ?>

                        <button class="btn btn-primary" id="buscarNcm" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>

                <div class="col-2 text-end">
                </div>
            </div>

            <div class="table mt-2 ts-divTabela ts-tableFiltros">
                <table class="table table-hover table-sm">
                    <thead class="ts-headertabelafixo">
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Superior</th>
                            <th>nivel</th>
                            <th>CEST</th>
                        </tr>
                    </thead>
                    <tbody id='dadosNcmTable' class="fonteCorpo">
                    </tbody>
                </table>
            </div>
        </div>

    <!-- LOCAL PARA COLOCAR OS JS -->
    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        <?php if (!empty($dadosNcm)) { ?>
            buscarNcm($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
        <?php } ?>

        function limpar() {
            buscarNcm(null, null);
            window.location.reload();
        }

        function buscarNcm(FiltroTipoNcm, dadosNcm) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/ncm.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dadosNcmTable").html("Carregando...");
                },
                data: {
                    FiltroTipoNcm: FiltroTipoNcm,
                    dadosNcm: dadosNcm
                },
                success: function(msg) {
                    var json = JSON.parse(msg);

                    json.sort(function(a, b) {
                        if (a.codigoNcm === b.codigoNcm) {
                            return a.nivel - b.nivel;
                        } else {
                            return a.codigoNcm.localeCompare(b.codigoNcm);
                        }
                    });

                    var linha = "";
                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];

                        var spacesDescricao = "&nbsp;&nbsp;".repeat((object.nivel - 1) * 2);
                        var spacesCodigoNcm = "&nbsp;&nbsp;".repeat((object.nivel - 1) * 2);


                        linha += "<tr>";
                        linha += "<td>" + spacesCodigoNcm + object.ncm + "</td>";
                        if ((dadosNcm && object.Descricao.toLowerCase().includes(dadosNcm.toLowerCase())) || object.pesquisado) {
                            linha += "<td><span style='font-weight: bold; white-space: pre;'>" + spacesDescricao + object.Descricao + "</span></td>";
                        } else {
                            linha += "<td>" + spacesDescricao + object.Descricao + "</td>";
                        }
                        linha += "<td>" + object.superior + "</td>";
                        linha += "<td>" + object.nivel + "</td>";
                        if (object.codigoCest) {
                            var codigoCestArray = object.codigoCest.split(',');
                            if (codigoCestArray.length > 1) {
                                linha += "<td><a href='javascript:void(0);' onclick='refreshPage(\"cest\", \"" + object.codigoNcm + "\");'>CEST</a></td>";
                            } else {
                                linha += "<td><a href='javascript:void(0);' onclick='refreshPage(\"cest\", \"" + object.codigoNcm + "\");'>" + codigoCestArray[0] + "</a></td>";
                            }
                        } else {
                            linha += "<td></td>";
                        }
                        linha += "</tr>";
                    }

                    $("#dadosNcmTable").html(linha);
                },
                error: function(e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $(document).ready(function() {
            $("#buscarNcm").click(function() {
                if ($("#dadosNcm").val() === "") {
                    alert("Campo Codigo vazio!");
                } else {
                    buscarNcm($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });

            $(document).keypress(function(e) {
                if (e.key === "Enter") {
                    buscarNcm($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });
        });
    </script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->


</body>

</html>
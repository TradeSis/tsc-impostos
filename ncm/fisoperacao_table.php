<?php
include_once(__DIR__ . '/../header.php');
?>

<body>


        <div class="container-fluid">
            <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                <div class="col-3 text-start">
                    <!-- TITULO -->
                    <h2 class="ts-tituloPrincipal">Operação</h2>
                </div>
                <div class="col-3">
                    <!-- FILTROS -->
                    <form method="post">
                        <select class="form-select ts-input" name="FiltroTipoOp" id="FiltroTipoOp">
                            <option <?php if ($FiltroTipoOp == "nomeOperacao") {
                                        echo "selected";
                                    } ?> value="nomeOperacao">Nome</option>
                            <option <?php if ($FiltroTipoOp == "idEntSai") {
                                        echo "selected";
                                    } ?> value="idEntSai">
                                idEntSai</option>
                            <option <?php if ($FiltroTipoOp == "xfop") {
                                        echo "selected";
                                    } ?> value="xfop">xfop
                            </option>
                        </select>
                    </form>
                </div>
                <div class="col-4">
                    <!-- FILTROS -->
                    <div class="input-group">
                        <?php if (!empty($dadosOp)) { ?>
                            <input type="text" class="form-control ts-input" id="dadosOp" value="<?php echo $dadosOp ?>">
                        <?php } else { ?>
                            <input type="text" class="form-control ts-input" id="dadosOp" placeholder="Operação">
                        <?php } ?>

                        <button class="btn btn-primary" id="buscarOperacao" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>

                <div class="col-2 text-end">
                    <button class="btn btn-warning" id="export" name="export" type="submit">Gerar
                        CSV</button>
                </div>
            </div>


            <div class="table mt-2 ts-divTabela ts-tableFiltros">
                <table class="table table-hover table-sm">
                    <thead class="ts-headertabelafixo">
                        <tr>
                            <th>Operação</th>
                            <th>
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idAtividade" id="FiltroAtividade">
                                        <option value="<?php echo null ?>"><?php echo " Atividade" ?></option>
                                        <?php
                                        foreach ($atividades as $atividade) {
                                        ?>
                                            <option <?php
                                                    if ($atividade['idAtividade'] == $idAtividade) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $atividade['idAtividade'] ?>"><?php echo $atividade['nomeAtividade'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th>
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idProcesso" id="FiltroProcesso">
                                        <option value="<?php echo null ?>"><?php echo " Processo" ?></option>
                                        <?php
                                        foreach ($processos as $processo) {
                                        ?>
                                            <option <?php
                                                    if ($processo['idProcesso'] == $idProcesso) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $processo['idProcesso'] ?>"><?php echo $processo['nomeProcesso'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th>
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idNatureza" id="FiltroNatureza">
                                        <option value="<?php echo null ?>"><?php echo " Natureza" ?></option>
                                        <?php
                                        foreach ($naturezas as $natureza) {
                                        ?>
                                            <option <?php
                                                    if ($natureza['idNatureza'] == $idNatureza) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $natureza['idNatureza'] ?>"><?php echo $natureza['nomeNatureza'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th>idGrupoOper</th>
                            <th>idEntSai</th>
                            <th>xfop</th>
                        </tr>
                    </thead>

                    <tbody id='dadosOpTable' class="fonteCorpo">

                    </tbody>
                </table>
            </div>
        </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

        <script>
            buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());

            function limpar() {
                buscarOp(null, null, null, null, null);
                window.location.reload();
            }

            function buscarOp(FiltroTipoOp, dadosOp, idAtividade, idProcesso, idNatureza) {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../database/fisoperacao.php?operacao=filtrar',
                    beforeSend: function() {
                        $("#dadosOpTable").html("Carregando...");
                    },
                    data: {
                        FiltroTipoOp: FiltroTipoOp,
                        dadosOp: dadosOp,
                        idAtividade: idAtividade,
                        idProcesso: idProcesso,
                        idNatureza: idNatureza
                    },
                    success: function(msg) {
                        var json = JSON.parse(msg);

                        var linha = "";
                        for (var $i = 0; $i < json.length; $i++) {
                            var object = json[$i];

                            linha = linha + "<TR>";
                            linha = linha + "<TD>" + object.nomeOperacao + "</TD>";
                            linha = linha + "<TD>" + object.nomeAtividade + "</TD>";
                            linha = linha + "<TD>" + object.nomeProcesso + "</TD>";
                            linha = linha + "<TD>" + object.nomeNatureza + "</TD>";
                            linha = linha + "<TD>" + object.idGrupoOper + "</TD>";
                            linha = linha + "<TD>" + object.idEntSai + "</TD>";
                            linha = linha + "<TD>" + object.xfop + "</TD>";
                            linha = linha + "</TR>";
                        }

                        $("#dadosOpTable").html(linha);
                    },
                    error: function(e) {
                        alert('Erro: ' + JSON.stringify(e));
                    }
                });
            }

            $("#FiltroAtividade").change(function() {
                buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
            })

            $("#FiltroProcesso").change(function() {
                buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
            })

            $("#FiltroNatureza").change(function() {
                buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
            })

            $(document).ready(function() {
                $("#buscarOperacao").click(function() {
                    buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
                });

                $(document).keypress(function(e) {
                    if (e.key === "Enter") {
                        buscarOp($("#FiltroTipoOp").val(), $("#dadosOp").val(), $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
                    }
                });
            });

            function exportToCSV() {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisoperacao.php?operacao=filtrar',
                    data: {
                        FiltroTipoOp: $("#FiltroTipoOp").val(),
                        dadosOp: $("#dadosOp").val(),
                        idAtividade: $("#FiltroAtividade").val(),
                        idProcesso: $("#FiltroProcesso").val(),
                        idNatureza: $("#FiltroNatureza").val()
                    },
                    success: function(json) {
                        var csvContent = "data:text/csv;charset=utf-8,\uFEFF";
                        csvContent += "NomeOperacao,NomeAtividade,NomeProcesso,NomeNatureza,IdGrupoOper,IdEntSai,XFOP\n";

                        for (var i = 0; i < json.length; i++) {
                            var object = json[i];
                            csvContent += object.nomeOperacao + "," +
                                object.nomeAtividade + "," +
                                object.nomeProcesso + "," +
                                object.nomeNatureza + "," +
                                object.idGrupoOper + "," +
                                object.idEntSai + "," +
                                object.xfop + "\n";
                        }

                        var encodedUri = encodeURI(csvContent);
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", "data.csv");
                        document.body.appendChild(link);

                        link.click();

                        document.body.removeChild(link);
                    },
                    error: function(e) {
                        alert('Erro: ' + JSON.stringify(e));
                    }
                });
            }

            $("#export").click(function() {
                exportToCSV();
            });
        </script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->


</body>

</html>
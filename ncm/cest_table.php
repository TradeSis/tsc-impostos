<?php
include_once(__DIR__ . '/../header.php');
?>

<body>


        <div class="container-fluid">
            <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                <div class="col-3 text-start">
                    <!-- TITULO -->
                    <h2 class="ts-tituloPrincipal">Cest</h2>
                </div>
                <div class="col-3">
                    <!-- FILTROS -->
                    <form method="post">
                        <select class="form-select ts-input" name="FiltroTipoCest" id="FiltroTipoCest">
                            <option <?php if ($FiltroTipoCest == "nomeCest") {
                                        echo "selected";
                                    } ?> value="nomeCest">Nome Cest</option>
                            <option <?php if ($FiltroTipoCest == "codigoNcm") {
                                        echo "selected";
                                    } ?> value="codigoNcm">Código Ncm</option>
                            <option <?php if ($FiltroTipoCest == "codigoCest") {
                                        echo "selected";
                                    } ?> value="codigoCest">Código Cest</option>
                        </select>
                    </form>
                </div>
                <div class="col-4">
                    <!-- FILTROS -->
                    <div class="input-group">
                        <?php if (!empty($dadosCest)) { ?>
                            <input type="text" class="form-control ts-input" id="dadosCest" value="<?php echo $dadosCest ?>">
                        <?php } else { ?>
                            <input type="text" class="form-control ts-input" id="dadosCest" placeholder="Codigo">
                        <?php } ?>

                        <button class="btn btn-primary" id="buscarCest" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </div>

                    <div class="col-2 text-end">

                    </div>
                </div>


                <div class="table mt-2 ts-divTabela ts-tableFiltros">
                    <table class="table table-hover table-sm">
                        <thead class="ts-headertabelafixo">
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Cest</th>
                                <th>superior</th>
                                <th>ncm</th>
                            </tr>
                        </thead>
                        <tbody id='dadosCestTable' class="fonteCorpo">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

        <script>
            <?php if (!empty($dadosCest)) { ?>
                buscarCest($("#FiltroTipoCest").val(), $("#dadosCest").val());
            <?php } ?>

            function limpar() {
                buscarCest(null, null);
                window.location.reload();
            }

            function buscarCest(FiltroTipoCest, dadosCest) {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../database/cest.php?operacao=filtrar',
                    beforeSend: function() {
                        $("#dadosCestTable").html("Carregando...");
                    },
                    data: {
                        FiltroTipoCest: FiltroTipoCest,
                        dadosCest: dadosCest
                    },
                    success: function(msg) {
                        console.log(msg);
                        var json = JSON.parse(msg);

                        var linha = "";
                        for (var i = 0; i < json.length; i++) {
                            var object = json[i];

                            linha += "<tr>";
                            linha += "<td>" + object.codigoCest + "</td>";
                            linha += "<td>" + object.nomeCest + "</td>";
                            linha += "<td>" + object.cest + "</td>";
                            linha += "<td>" + object.superior + "</td>";
                            linha += "<td>" + object.codigoNcm + "</td>";
                            linha += "</tr>";
                        }

                        $("#dadosCestTable").html(linha);
                    },
                    error: function(e) {
                        alert('Erro: ' + JSON.stringify(e));
                    }
                });
            }

            $(document).ready(function() {
                $("#buscarCest").click(function() {
                    if ($("#dadosCest").val() === "") {
                        alert("Campo Codigo vazio!");
                    } else {
                        buscarCest($("#FiltroTipoCest").val(), $("#dadosCest").val());
                    }
                });

                $(document).keypress(function(e) {
                    if (e.key === "Enter") {
                        buscarCest($("#FiltroTipoCest").val(), $("#dadosCest").val());
                    }
                });
            });
        </script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->


</body>

</html>
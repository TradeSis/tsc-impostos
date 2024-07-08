<?php
//Lucas 19042023 criado
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

        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">
            <div class="col-12 col-md-6">
                <h2 class="ts-tituloPrincipal">Notas</h2>
            </div>

            <div class="col-12 col-md-6 justify-content-end gap-1 d-flex">

                <input type="text" class="form-control ts-input" name="anoImposto" id="FiltroDataAno" placeholder="Ano" autocomplete="off" required style="width: 70px;">

                <select class="form-select ts-input" name="mesImposto" id="FiltroDataMes" style="width: 130px;">
                    <option value="01">Janeiro</option>
                    <option value="02">Fevereiro</option>
                    <option value="03">Mar�o</option>
                    <option value="04">Abril</option>
                    <option value="05">Maio</option>
                    <option value="06">Junho</option>
                    <option value="07">Julho</option>
                    <option value="08">Agosto</option>
                    <option value="09">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>

                <button class="btn btn-sm btn-primary" type="button" id="filtrardata" style="width: 60px;">Filtrar</button>

            </div>
        </div><!-- ROW -->

        <div class="table mt-2 ts-divTabela70 ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
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

                <tbody id='dados_notas' class="fonteCorpo">

                </tbody>
            </table>

            

        </div>
        <h6 id="textocontadorNotas" style="color: #13216A;"></h6>

        <!-- div de loading -->
        <div class="text-center" id="div-loadNotas" style="margin-top: -200px; display: none">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function() {
            var texto = $("#textocontadorNotas");
            texto.html('total de notas: ' + 0);
        });

        function buscar(FiltroDataAno, FiltroDataMes) {

            if (FiltroDataAno == '') {
                alert("Informar campo Ano")
            } else {
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: '../database/fisnota.php?operacao=filtrar',
                    beforeSend: function() {
                        setTimeout(function() {
                            $("#div-loadNotas").css("display", "block");
                        }, 500);
                    },
                    data: {
                        anoImposto: FiltroDataAno,
                        mesImposto: FiltroDataMes
                    },
                    success: function(msg) {

                        var json = JSON.parse(msg);
                        //alert(JSON.stringify(json));
                        if (json["status"] == 400) {
                            alert("Nenhum registro retornado!")
                            linha = "";
                            $("#dados_notas").html(linha);
                            $("#div-loadNotas").css("display", "none");
                            var texto = $("#textocontadorNotas");
                            texto.html('total de notas: ' + 0);

                        }else {
                            var contadorNotas = 0;
                            var linha = "";
                            for (var $i = 0; $i < json.length; $i++) {
                                var object = json[$i];
                                //alert(object.idStatusNota)
                                contadorNotas += 1;
                                linha = linha + "<tr>";

                                linha = linha + "<td>" + object.NF + "</td>";
                                linha = linha + "<td>" + formatDate(object.dtEmissao) + "</td>";
                                linha = linha + "<td>" + object.emitente_cpfCnpj + "</td>";
                                linha = linha + "<td>" + (object.emitente_nomeFantasia !== null ? object.emitente_nomeFantasia : object.emitente_nomePessoa) + "</td>";
                                linha = linha + "<td>" + object.chaveNFe + "</td>";
                                linha = linha + "<td class='text-end'>" + (object.vNF !== null ? parseFloat(object.vNF).toFixed(2) : "-") + "</td>";
                                linha = linha + "<td>" + object.nomeStatusNota + "</td>";
                                linha = linha + "<td>";
                                linha = linha + "<button type='button' class='btn btn-success btn-sm me-1' data-bs-target='#visualizarGrupoProdutoModal' id='baixarnotas' data-idNota=" + object.idNota + " title='Baixar XML'><i class='bi bi-download'></i></button>";
                                linha = linha + "<a class='btn btn-info btn-sm' href='visualizar.php?idNota=" + object.idNota + "' role='button'><i class='bi bi-eye-fill'></i></a>";
                                linha = linha + "</td>";
                                linha += "</tr>";
                            }

                            $("#dados_notas").html(linha);
                            $("#div-loadNotas").css("display", "none");
                            var texto = $("#textocontadorNotas");
                            texto.html('total de notas: ' + contadorNotas);
                        }

                    }
                });
            }

        }

        $("#filtrardata").click(function() {
            buscar($("#FiltroDataAno").val(), $("#FiltroDataMes").val());
        });

        $(document).on('click', 'button[data-bs-target="#visualizarGrupoProdutoModal"]', function() {
            var idNota = $(this).attr("data-idNota");
            //alert(idNota)
            $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=buscarNota",
                    data: {
                        idNota: idNota
                    },
                    success: function(msg) {
                        //console.log(JSON.stringify(msg, null, 2));
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
           
            

        // Ao iniciar o programa, inseri os valores de ano(input) e mes(select) atuais. 
        $(document).ready(function() {
            const date = new Date();
            const year = date.getFullYear();
            const currentMonth = date.getMonth() + 1;

            const FiltroDataAno = document.getElementById("FiltroDataAno");
            FiltroDataAno.value = year;

            const FiltroDataMes = document.getElementById("FiltroDataMes");
            FiltroDataMes.value = (currentMonth <= 9 ? "0" + currentMonth : currentMonth);

        });

        function refreshPage() {
            window.location.reload();
        }

        function formatDate(dateString) {
            if (dateString !== null && !isNaN(new Date(dateString))) {
                var date = new Date(dateString);
                var day = date.getUTCDate().toString().padStart(2, '0');
                var month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
                var year = date.getUTCFullYear().toString().padStart(4, '0');
                return day + "/" + month + "/" + year;
            }
            return "00/00/0000";
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
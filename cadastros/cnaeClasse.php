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

        <div class="row ">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> BOTOES AUXILIARES -->
        </div>
        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">CNAE Classe</h2>
            </div>
            <div class="col">
                <!-- FILTROS -->
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscacnaeClasse" placeholder="Buscar por CNAE">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-2 text-end">
                 <!--<BR> BOTOES AUXILIARES -->
            </div>

        </div>


        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Grupo</th>
                        <th>Grupo Desc.</th>
                        <th>Divisão</th>
                        <th>Div. Desc.</th>
                        <th>Seção</th>
                        <th>Seção Desc.</th>
                        <th>caracTrib</th>
                        <th>caracTrib Desc.</th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

            </table>
        </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        function limpar() {
            buscar(null);
            window.location.reload();
        }

        function buscar(buscacnaeClasse) {
            if (!buscacnaeClasse) {
                alert("Insira codigo CNAE");
                return;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json', 
                url: '<?php echo URLROOT ?>/impostos/database/cnae.php?operacao=buscaClasse',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    cnaeID: buscacnaeClasse
                },
                success: function(data) {
                   console.log(data);
                    var linha = "";

                    linha = linha + "<tr>";
                    linha = linha + "<td>" + data.ID + "</td>";
                    linha = linha + "<td>" + data.Descricao + "</td>";
                    linha = linha + "<td>" + data.grupoID + "</td>";
                    linha = linha + "<td>" + data.grupoDescricao + "</td>";
                    linha = linha + "<td>" + data.divisaoID + "</td>";
                    linha = linha + "<td>" + data.divisaoDescricao + "</td>";
                    linha = linha + "<td>" + data.secaoID + "</td>";
                    linha = linha + "<td>" + data.secaoDescricao + "</td>";
                    linha = linha + "<td>" + data.caracTrib + "</td>";
                    linha = linha + "<td>" + data.descricaoCaracTrib + "</td>";
                    
                    linha = linha + "</tr>";
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscacnaeClasse").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscacnaeClasse").val());
            }
        });

    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
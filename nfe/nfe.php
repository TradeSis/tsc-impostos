<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';

$cargas = buscarCarga();
//echo json_encode($cargas);
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<!-- ESTILO TEMPORARIO -->
<style>
.ts-divTabela60 {
  width: 100%;
  height: 60vh;
  overflow-y: scroll;
  overflow-x: auto;
}
</style>

<body>
    <!--------- MODAL CARGA --------->
    <div class="modal fade bd-example-modal-lg" id="modalNfeCarga" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log de Carga: </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="refreshPage()"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="table mt-2 ts-divTabela60 ts-tableFiltros">
                        <table class="table table-hover table-sm">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>nomeXml</th>
                                    <th>descricao</th>
                                </tr>
                            </thead>
                            <tbody id='carga' class="fonteCorpo"></tbody>
                        </table>
                    </div>

                </div><!--modal body-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="refreshPage()">Fechar</button>
                </div>

            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="container-fluid">
        <div class="pt-1" id="ts-tabs">
            <div class="tab whiteborder" id="tab-demanda">Carga</div>
            <div class="tab" id="tab-tarefas">Notas</div>
            <div class="line"></div>
            <div class="tabContent">
                <?php include_once 'carga.php'; ?>
            </div>
            <div class="tabContent">
                <?php include_once 'notas.php'; ?>
            </div>
        </div>
    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>


    <script>
        var myModal = new bootstrap.Modal(document.getElementById("modalDemandaVizualizar"), {});
        document.onreadystatechange = function() {
            myModal.show();
        };

        var tab;
        var tabContent;

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'tarefas') {
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
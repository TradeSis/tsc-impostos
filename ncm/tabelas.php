<?php
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/ncm.php');
include_once(__DIR__ . '/../database/cest.php');
include_once(__DIR__ . '/../database/fisoperacao.php');
include_once(__DIR__ . '/../database/fisatividade.php');
include_once(__DIR__ . '/../database/fisnatureza.php');
include_once(__DIR__ . '/../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();
$operacoes = buscaOperacao();


$filtroEntradaNcm = null;
$dadosNcm = null;
$FiltroTipoNcm = null;

if (isset($_SESSION['filtro_ncm'])) {
    $filtroEntradaNcm = $_SESSION['filtro_ncm'];
    $FiltroTipoNcm = $filtroEntradaNcm['FiltroTipoNcm'];
    $dadosNcm = $filtroEntradaNcm['dadosNcm'];
}

$filtroEntradaCest = null;
$dadosCest = null;
$FiltroTipoCest = null;

if (isset($_SESSION['filtro_cest'])) {
    $filtroEntradaCest = $_SESSION['filtro_cest'];
    $FiltroTipoCest = $filtroEntradaCest['FiltroTipoCest'];
    $dadosCest = $filtroEntradaCest['dadosCest'];
}

$filtroEntradaOp = null;
$dadosOp = null;
$FiltroTipoOp = null;
$idAtividade = null;
$idProcesso = null;
$idNatureza = null;

if (isset($_SESSION['filtro_operacao'])) {
    $filtroEntradaOp = $_SESSION['filtro_operacao'];
    $FiltroTipoOp = $filtroEntradaOp['FiltroTipoOp'];
    $dadosOp = $filtroEntradaOp['dadosOp'];
    $idAtividade = $filtroEntradaOp['idAtividade'];
    $idProcesso = $filtroEntradaOp['idProcesso'];
    $idNatureza = $filtroEntradaOp['idNatureza'];
}

if (isset($_GET['codigoNcm'])) {
    $FiltroTipoCest = "codigoNcm";
    $dadosCest = $_GET['codigoNcm'];
}

?>

<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>
    <div class="container-fluid">
        <div id="ts-tabs">
            <div class="tab whiteborder" id="tab-ncm">NCM</div>
            <div class="tab" id="tab-cest">Cest</div>
            <div class="tab" id="tab-fisoperacao">Operação</div>
            <div class="line"></div>
            <div class="tabContent">
                <?php include_once 'ncm_table.php'; ?>
            </div>
            <div class="tabContent">
                <?php  include_once 'cest_table.php'; ?>
            </div>
            <div class="tabContent">
                <?php include_once 'fisoperacao_table.php'; ?>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        var tab;
        var tabContent;

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'cest') {
                showTabsContent(1);
            }
            if (id === 'fisoperacao') {
                showTabsContent(2);
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

        function refreshPage(tab, codigoNcm) {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab + '&&codigoNcm=' + codigoNcm;
            window.location.href = newUrl;
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
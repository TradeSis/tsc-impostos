<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06

include_once('../header.php');
include_once('../database/fisprocesso.php');

$processos = buscaProcesso($_GET['idProcesso']);
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

    <div class="container-fluid">

        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Excluir Processo</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="../configuracao/?tab=configuracao&stab=fisprocesso" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>


        <form class="mb-4" action="../database/fisprocesso.php?operacao=excluir" method="post">
            <div class="col-md-12 form-group mb-4">

                <label class='control-label' for='inputNormal'></label>
                <div class="for-group">
                    <input type="text" class="form-control ts-input" name="nomeProcesso" value="<?php echo $processos['nomeProcesso'] ?>">
                </div>
                <input type="hidden" class="form-control ts-input" name="idProcesso" value="<?php echo $processos['idProcesso'] ?>">
            </div>

            <div class="text-end mt-4">
                <button type="submit" id="botao" class="btn btn-sm btn-danger"><i class="bi bi-x-octagon"></i>&#32;Excluir</button>
            </div>
        </form>


    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
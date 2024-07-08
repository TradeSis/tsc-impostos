<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06

include_once('../header.php');
include_once('../database/fisoperacao.php');
$operacao = buscaOperacao($_GET['idOperacao']);

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
                <h2 class="ts-tituloPrincipal">Alterar Operação</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="fisoperacao.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>


        <form action="../database/fisoperacao.php?operacao=excluir" method="post">

            <input type="hidden" class="form-control ts-input" name="idOperacao" value="<?php echo $operacao['idOperacao'] ?>">

            <label class="form-label ts-label">Nome da operação</label>
            <input type="text" class="form-control ts-input" name="nomeOperacao" value="<?php echo $operacao['nomeOperacao'] ?>">

            <div class="row">
                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="form-label ts-label">Atividade</label>
                    <select class="form-select ts-input" name="idAtividade">
                        <option value="<?php echo $operacao['idAtividade'] ?>"><?php echo $operacao['nomeAtividade'] ?></option>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="form-label ts-label">Processo</label>
                    <select class="form-select ts-input" name="idProcesso">
                        <option value="<?php echo $operacao['idProcesso'] ?>"><?php echo $operacao['nomeProcesso'] ?></option>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="form-label ts-label">Natureza</label>
                    <select class="form-select ts-input" name="idNatureza">
                        <option value="<?php echo $operacao['idNatureza'] ?>"><?php echo $operacao['nomeNatureza'] ?></option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="form-label ts-label">idGrupoOper</label>
                    <input type="text" class="form-control ts-input" name="idGrupoOper" value="<?php echo $operacao['idGrupoOper'] ?>">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="form-label ts-label">idEntSai</label>
                    <input type="text" class="form-control ts-input" name="idEntSai" value="<?php echo $operacao['idEntSai'] ?>">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="form-label ts-label">xfop</label>
                    <input type="text" class="form-control ts-input" name="xfop" value="<?php echo $operacao['xfop'] ?>">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-danger"><i class="bi bi-sd-card-fill"></i>&#32;Excluir</button>
            </div>
        </form>


    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
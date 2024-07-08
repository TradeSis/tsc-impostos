<?php
//lucas 09102023 novo padrao
include_once __DIR__ . "/../config.php";
include_once "header.php";
include_once ROOT . "/sistema/database/loginAplicativo.php";

$nivelMenuLogin = buscaLoginAplicativo($_SESSION['idLogin'], 'Impostos');

$configuracao = 1;

$nivelMenu = $nivelMenuLogin['nivelMenu'];

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
    <title>Impostos</title>

</head>

<body>

    <?php include_once  ROOT . "/sistema/painelmobile.php"; ?>

    <div class="d-flex">

        <?php include_once  ROOT . "/sistema/painel.php"; ?>

        <div class="container-fluid">
            <div class="row ">
                <div class="col-lg-10 d-none d-md-none d-lg-block pr-0 pl-0 ts-bgAplicativos">
                    <ul class="nav a" id="myTabs">

                        <?php
                        $tab = '';

                        if (isset($_GET['tab'])) {
                            $tab = $_GET['tab'];
                        }
                        ?>
                        <?php if ($nivelMenu >= 1) {
                            if ($tab == '') {
                                $tab = 'nfe';
                            } ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "nfe") {echo " active ";} ?>" 
                                href="?tab=nfe" role="tab">NFE </a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "calculo") {echo " active ";} ?>" 
                                href="?tab=calculo" role="tab">Calculo</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "operacaofiscal") {echo " active ";} ?>" 
                                href="?tab=operacaofiscal" role="tab">Operação Fiscal</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "regrafiscal") {echo " active ";} ?>" 
                                href="?tab=regrafiscal" role="tab">Regra Fiscal</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "ncm") {echo " active ";} ?>" 
                                href="?tab=ncm" role="tab">NCM/CEST</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "operacoes") {echo " active ";} ?>" 
                                href="?tab=operacoes" role="tab">Operações</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "cnaeClasse") {echo " active ";} ?>" 
                                href="?tab=cnaeClasse" role="tab">CNAE</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                        <!-- Lucas 30042024 - desabilitado Historico -->
                            <li class="nav-item mr-1 d-none">
                                <a class="nav-link 
                                <?php if ($tab == "fishistorico") {echo " active ";} ?>" 
                                href="?tab=fishistorico" role="tab">Api Historico</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 4) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link 
                                <?php if ($tab == "configuracao") {echo " active ";} ?>" 
                                href="?tab=configuracao" role="tab" data-toggle="tooltip" data-placement="top" title="Configurações"><i class="bi bi-gear"></i> Configurações</a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
                <!--Essa coluna só vai aparecer em dispositivo mobile-->
                <div class="col-7 col-md-9 d-md-block d-lg-none" style="background-color: #13216A;">
                    <!--atraves do GET testa o valor para selecionar um option no select-->
                    <?php if (isset($_GET['tab'])) {
                        $getTab = $_GET['tab'];
                    } else {
                        $getTab = '';
                    } ?>
                    <select class="form-select mt-2" id="subtabServices" style="color:#000; width:160px;text-align:center; ">
                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=ncm" 
                        <?php if ($getTab == "ncm") {echo " selected ";} ?>>NCM/CEST</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=operacoes" 
                        <?php if ($getTab == "operacoes") {echo " selected ";} ?>>Operações</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=cnaeClasse" 
                        <?php if ($getTab == "cnaeClasse") {echo " selected ";} ?>>CNAE</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=calculo" 
                        <?php if ($getTab == "calculo") {echo " selected ";} ?>>Calculo</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=operacaofiscal" 
                        <?php if ($getTab == "operacaofiscal") {echo " selected ";} ?>>Operação Fiscal</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=regrafiscal" 
                        <?php if ($getTab == "regrafiscal") {echo " selected ";} ?>>Regra Fiscal</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=fishistorico" 
                        <?php if ($getTab == "fishistorico") {echo " selected ";} ?>>Api Historico</option>

                        <option value="<?php echo URLROOT ?>/impostos/index.php?tab=configuracao" 
                        <?php if ($getTab == "configuracao") {echo " selected ";} ?>>Configurações</option>
                    </select>
                </div>

                <?php include_once  ROOT . "/sistema/novoperfil.php"; ?>

            </div><!--row-->

            <?php
            $src = "";

            if ($tab == "nfe") {
                $src = "nfe/nfe.php";
            }
            if ($tab == "calculo") {
                $src = "nfe/calculo.php";
            }
            if ($tab == "operacaofiscal") {
                $src = "cadastros/operacaofiscal.php";
            }
            if ($tab == "regrafiscal") {
                $src = "cadastros/regrafiscal.php";
            }
            if ($tab == "fishistorico") {
                $src = "cadastros/fishistorico.php";
            }
            if ($tab == "operacoes") {
                $src = "operacoes/fisoperacao.php";
            }
            if ($tab == "cnaeClasse") {
                $src = "cadastros/cnaeClasse.php";
            }
            if ($tab == "ncm") {
                $src = "ncm/tabelas.php";
            }
            if ($tab == "configuracao") {
                $src = "configuracao/";
                if (isset($_GET['stab'])) {
                    $src = $src . "?stab=" . $_GET['stab'];
                }
            }

            if ($src !== "") { ?>
                <div class="container-fluid p-0 m-0">
                    <iframe class="row p-0 m-0 ts-iframe" src="<?php echo URLROOT ?>/impostos/<?php echo $src ?>"></iframe>
                </div>
            <?php } ?>

        </div><!-- div container -->
    </div><!-- div class="d-flex" -->


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script src="<?php echo URLROOT ?>/sistema/js/mobileSelectTabs.js"></script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>
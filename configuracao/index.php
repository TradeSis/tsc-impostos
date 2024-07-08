<?php
//Lucas 17102023 novo padrao
include_once(__DIR__ . '/../header.php');
?>
<!doctype html>
<html lang="pt-BR">

<head>

  <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

  <div class="container-fluid">
    <div class="row pt-4">
      <div class="col-md-2 mb-3">
        <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
          <?php
          $stab = 'fisatividade';
          if (isset($_GET['stab'])) {
            $stab = $_GET['stab'];
          }
          //echo "<HR>stab=" . $stab;
          ?>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "fisatividade") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=fisatividade" role="tab">Atividade</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "fisnatureza") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=fisnatureza" role="tab">Natureza</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "fisprocesso") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=fisprocesso" role="tab">Processo</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "fisnotastatus") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=fisnotastatus" role="tab">Status Notas</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "caractrib") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=caractrib" role="tab">CaracTrib</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link ts-tabConfig <?php if ($stab == "cnaeSecao") {
                                              echo " active ";
                                            } ?>" href="?tab=configuracao&stab=cnaeSecao" role="tab">CNAE Secao</a>
          </li>


        </ul>
      </div>
      <div class="col-md-10">
        <?php
        $ssrc = "";

        if ($stab == "fisatividade") {
          $ssrc = "fisatividade.php";
        }
        if ($stab == "fisnatureza") {
          $ssrc = "fisnatureza.php";
        }
        if ($stab == "fisprocesso") {
          $ssrc = "fisprocesso.php";
        }
        if ($stab == "fisnotastatus") {
          $ssrc = "fisnotastatus.php";
        }
        if ($stab == "caractrib") {
          $ssrc = "caractrib.php";
        }
        if ($stab == "cnaeSecao") {
          $ssrc = "cnaeSecao.php";
        }

        if ($ssrc !== "") {
          //echo $ssrc;
          include($ssrc);
        }

        ?>

      </div>
    </div>

  </div>

  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT . "/vendor/footer_js.php"; ?>

  <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>
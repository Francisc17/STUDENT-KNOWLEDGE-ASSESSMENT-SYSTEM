<?php
    $tittle = "principal";
    require_once 'includes/header.php';

$lang = require 'includes/Language/lang_pt.php';

?>
<link rel="stylesheet" href="css/login_registo.css">
</head>
<body>
<div class="navbar bannerSite navbar-dark">
    <a class="navbar-brand " href="index.php">
        <img class="img_principal" src="imagens/siteIcone.png" alt="icone do site">
    </a>
    <span class="banner-titulo">
            BEM VINDO
    </span>
</div>

<div class="container">
            <div class="btn-group-vertical buttons-loginRegisto">
                <button type="button" act class="btn btn-lg btn-primary btn-login" onclick="clicar_login()">LOGIN</button>
                <button type="button" class="btn btn-lg btn-primary btn-registo" onclick="clicar_registo()">REGISTAR</button>
    </div>
</div>

<?php require_once 'includes/footer.php';?>


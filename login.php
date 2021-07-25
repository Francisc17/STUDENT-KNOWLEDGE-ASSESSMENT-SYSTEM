<?php
$tittle = "Login";
require_once 'includes/header.php';

$lang = require 'includes/Language/lang_pt.php';

?>
    <link rel="stylesheet" href="css/login_registo.css">
    </head>
    <body>

    <div class="navbar bannerSite navbar-dark mb-5">
        <a class="navbar-brand " href="index.php">
            <img class="img_principal" src="imagens/siteIcone.png" alt="icone do site">
        </a>
        <span class="banner-titulo">
            LOGIN
    </span>
    </div>

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block login-image">
                                <img src="imagens/profilePic.png" alt="User image" height="300" width="300">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="includes/login_user.php" method="post">
                                        <div class="form-group" id="email-feedback">
                                            <input type="email" class="form-control form-control-user" id="InputEmail" aria-describedby="emailHelp"
                                                   placeholder="Enter Email Address..." name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password"
                                            name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="submit">Login</button>

                                    </form>
                                    <hr>
                                    <?php
                                        if (isset($_SERVER["REQUEST_METHOD"])=="GET") {
                                            if (isset($_GET['erro'])) {
                                                $valor = $_GET['erro'];

                                                if ($valor == 1) {
                                                    echo "<div class=\"invalid-feedback\" style=\"display: block\" id=\"error-msg\">
                                                    Email incorreto!
                                                    </div>";
                                                }

                                                if ($valor == 2) {
                                                    echo "<div class=\"invalid-feedback\" style=\"display: block\" id=\"error-msg\">
                                                    Password incorreta!
                                                    </div>";
                                                }

                                                if ($valor == 3) {
                                                    echo "<div class=\"invalid-feedback\" style=\"display: block\" id=\"error-msg\">
                                                    Valide a sua conta antes de entrar!
                                                    </div>";
                                                }
                                            }
                                        }
                                    ?>

                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>




<?php require_once 'includes/footer.php';?>

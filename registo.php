<?php
$tittle = "Registo";
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
            REGISTO
    </span>
    </div>


<div class="container pt-3">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block register-image">
                    <a href="#">
                        <img src="https://56ib9w7ram-flywheel.netdna-ssl.com/wp-content/uploads/2018/07/default-user-icon.png"
                             alt="register pic" width="300" height="300" id="register-pic" onclick="upload_image(this)">
                    </a>

                </div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" action="includes/registar_user.php" method="post" id="form-registo" enctype="multipart/form-data">
                            <input type="file" id="file-upload" name="file-upload" class="d-none">
                            <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                           id="exampleFirstName" placeholder="Nome completo" name="nome" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="InputEmail" placeholder="Email Address"
                                       onblur="pedido_ajax_email(this)" name="email" required>
                                <div class="invalid-feedback" style="display: none" id="invalid-email">
                                    Email inválido ou já existente
                                </div>
                                <div class="valid-feedback" style="display: none" id="valid-email">
                                    Email valido
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="InputPassword" placeholder="Password"
                                    name="password" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="RepeatPassword"
                                           placeholder="Repeat Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input"
                                           name="tipo1" value="aluno" checked>
                                    <label class="custom-control-label" for="customRadioInline1">Aluno</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input"
                                           name="tipo2" value="professor">
                                    <label class="custom-control-label" for="customRadioInline2" >Professor</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block" name="submit">Submit</button>
                        </form>
                        <hr>
                        <div class="invalid-feedback" style="display: none" id="error-msg-registo">
                            Email incorreto!
                        </div>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="login.php">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php';?>
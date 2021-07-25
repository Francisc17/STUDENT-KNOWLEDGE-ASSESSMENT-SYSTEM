<?php
session_start();
$tittle = "Inicio";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Teste.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Aluno")
    header("Location: http://localhost/projeto/login.php");

$userDAO = new UserDAO();

$testes_db = $userDAO->obter_testes($_SESSION['id']);

if (isset($testes_db)){
    $i = 0;
    foreach ($testes_db as $value){
        $testes[$i] = new Teste($value['nome'],$value['observacoes'],$value['hora_disponivel'],
            $value['data_disponivel'],$value['duracao'],$value['id_disciplina'],$value['id'],
            $value['hash']);
        $i++;
    }
}

if (isset($testes)){
    foreach ($testes as $teste){
        if ($teste->getDataInicio() > date("Y-m-d")){
            $teste->setEstado(3);
        }

        if ($teste->getDataInicio() < date("Y-m-d")){
            $teste->setEstado(1);
        }

        if ($teste->getDataInicio() == date("Y-m-d")){

            date_default_timezone_set("Europe/Lisbon");
            $today = date("H:i:s");
            $hora_inicio = $teste->getHoraInicio();
            $duracao = ($teste->getDuracao());

            $hoje_arr = explode(':',$today);
            $duracao_arr = explode(':',$duracao);
            $hora_inicio_arr = explode(':',$hora_inicio);

            $minutos_comeca_teste = ($hora_inicio_arr[0] * 60) + $hora_inicio_arr[1];
            $minutos_duracao = (($duracao_arr[0] * 60) + $duracao_arr[1]);
            $minutos_sistema = (($hoje_arr[0] * 60) + $hoje_arr[1]);



            if ($minutos_sistema > $minutos_comeca_teste && $minutos_sistema < ($minutos_comeca_teste + $minutos_duracao)){
                $teste->setEstado(2);
            }else if ($minutos_sistema > $minutos_comeca_teste){
                $teste->setEstado(1);
            }else if ($minutos_sistema < $minutos_comeca_teste){
                $teste->setEstado(3);
            }
        }
    }
}
?>



<link rel="stylesheet" href="css/pagina_inicial.css">
</head>
<body>

<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img class="icone_site" src="imagens/siteIcone.png" alt="icone do site">
        </div>
        <ul class="list-unstyled components">
            <li class="active">
                <a href="criar_testes.php">Testes</a>
            </li>

            <li>
                <a href="Disciplinas.php">Disciplinas</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content">

        <div class="navbar navbar-expand">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
                <i class="fas fa-align-left"></i>
            </button>
            <ul>
                <li><a href="#">
                        <i class="fas fa-bell"></i>
                    </a></li>
                <li><a href="#">
                        <i class="fas fa-envelope"></i>
                    </a></li>
                <li><a href="#" data-toggle="modal" data-target="#Modal_alterar_dados">
                        <i class="fas fa-users-cog"></i></i>
                    </a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <img src="<?php echo $_SESSION['foto']?>" alt="sdasdas" class="profile-pic">
                <p class="username text-center">
                    <?php
                    echo $_SESSION['nome']."<br>".$_SESSION['tipo'];
                    ?></p>
            </ul>
        </div>

        <div class="title-a-decorrer text-center py-3">
            <h4>Testes a decorrer</h4>
        </div>

        <div class="list-group px-5 pb-5">

            <?php
            if (isset($testes)){
                foreach ($testes as $teste){
                    if ($teste->getEstado() == 2)
                        echo "
                                    
            <a href=\" estado_alunos_teste.php?teste=".$teste->getId()." \" class=\"list-group-item list-group-item-action flex-column align-items-start\">
                <div class=\"d-flex w-100 justify-content-between\">
                    <h5 class=\"mb-1\">".$teste->getNome()."</h5>
                </div>
                <p class=\"mb-1\">".$teste->getObservacao()."</p>
                <small>Começou às: ".$teste->getDataInicio()."/".$teste->getHoraInicio()."</small>
            </a>
    
                        ";
                }
            }

            ?>

        </div>

        <div class="title-passado text-center py-3">
            <h4>Testes passados</h4>
        </div>

        <div class="list-group px-5 pb-5">
            <?php
            if (isset($testes)){
                foreach ($testes as $teste){
                    if ($teste->getEstado() == 1)
                        echo "
                                    
            <a href=\"#\" class=\"list-group-item list-group-item-action flex-column align-items-start\">
                <div class=\"d-flex w-100 justify-content-between\">
                    <h5 class=\"mb-1\">".$teste->getNome()."</h5>
                </div>
                <p class=\"mb-1\">".$teste->getObservacao()."</p>
                <small>Foi realizado a: ".$teste->getDataInicio()."/".$teste->getHoraInicio()."</small>
            </a>
    
                        ";
                }
            }

            ?>
        </div>

        <div class="title-passado text-center py-3">
            <h4>Testes Futuros</h4>
        </div>
        <div class="list-group px-5 pb-5 ">

            <?php
            if (isset($testes)){
                foreach ($testes as $teste){
                    if ($teste->getEstado() == 3)
                        echo "
                                    
            <a href=\" testes_futuros.php?teste=".$teste->getId()." \" class=\"list-group-item list-group-item-action flex-column align-items-start\">
                <div class=\"d-flex w-100 justify-content-between\">
                    <h5 class=\"mb-1\">".$teste->getNome()."</h5>
                </div>
                <p class=\"mb-1\">".$teste->getObservacao()."</p>
                <small>Será realizado a: ".$teste->getDataInicio()."/".$teste->getHoraInicio()."</small>
            </a>
    
                        ";
                }
            }

            ?>

        </div>
<div class="btn-criar-test pb-5" align="center">
    <button type="button" onclick="window.location.href='http://localhost/projeto/criar_testes.php'" class="btn btn-primary">Criar teste</button>
</div>

    </div>
</div>

<!-- modal alterar dados utilizador -->
<div class="modal fade" id="Modal_alterar_dados" tabindex="-1" role="dialog" aria-labelledby="Modal_alterar_dados" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar dados da conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="includes/Controllers/forms/change_user_data.php" method="post" enctype="multipart/form-data">
                    <div class="image_upload_change">
                        <div class="image-profile-modal" align="center">
                            <a href="#">
                                <img src="<?php echo $_SESSION['foto']?>" alt="foto perfil" class="profile-pic-modal"
                                     onclick="upload_image(this)">
                            </a>
                        </div>
                        <input type="file" id="file-upload" name="file-upload" class="d-none">
                        <div class="button_submit_pic pt-3 pb-5" align="center">
                            <button type="submit" name="submit-image" class="btn btn-primary ">alterar foto</button>
                        </div>
                    </div>
                </form>


                <!-- form para o nome -->
                <form action="includes/Controllers/forms/change_user_data.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-9">
                                <input type="text" class="form-control" id="input-nome" placeholder="Novo nome"
                                       name="novo-nome" required>
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-nome" class="btn btn-primary">alterar nome</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- form para a password -->
                <form action="includes/Controllers/forms/change_user_data.php" method="post">
                    <div class="form-group pt-3">
                        <div class="row">
                            <div class="col-9">
                                <input type="password" class="form-control" id="input-pass" placeholder="Nova password"
                                       name="nova-pass" required>
                            </div>
                            <div class="col">
                                <button type="submit" name="submit-pass" class="btn btn-primary">alterar password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php require_once 'includes/footer.php';?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

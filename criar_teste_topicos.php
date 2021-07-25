<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Teste.php';
require_once 'includes/Models/Topico.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Aluno")
    header("Location: http://localhost/projeto/login.php");

$userDAO = new UserDAO();

if ($_SERVER['REQUEST_METHOD'] == "GET"){

    $teste_db = $userDAO->obter_teste($_GET['teste']);

    $teste = new Teste($teste_db['nome'],$teste_db['observacoes'],$teste_db['hora_disponivel'],$teste_db['data_disponivel'],
        $teste_db['duracao'],$teste_db['id_disciplina'],$teste_db['id'],$teste_db['hash']);
}

if (isset($teste)){

    $j = 0;
    $topicos_db = $userDAO->obter_topicos($teste->getIdDisciplina());
    $id_topicos_usados_bd = $userDAO->obter_topicos_teste($_GET['teste']);

    if (isset($id_topicos_usados_bd)) {
        foreach ($id_topicos_usados_bd as $id_topico){
            $topicos_usados[$j] = $id_topico['id_topico'];
            $j++;
        }
    }

    $i = 0;
    $j = 0;
    $saltar = false;

    if (isset($topicos_db)){
        foreach ($topicos_db as $value) {
            if (isset($topicos_usados)) {
                foreach ($topicos_usados as $topico) {
                    if ($value['id'] == $topico)
                        $saltar = true;
                }
                if (!$saltar) {
                    $topicos[$i] = new Topico($value['id'], $value['nome'], $value['id_disciplina']);
                    $i++;
                }

                $saltar = false;
            }else{
                $topicos[$i] = new Topico($value['id'], $value['nome'], $value['id_disciplina']);
                $i++;
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
                <a href="#">Testes</a>
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="criar_testes.php">Criar Testes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Escolher topicos</li>
            </ol>
        </nav>
        <div class="title-topicos pt-4" align="center">
            <h4>Adicionar topicos</h4>
        </div>
        <div class="container pb-5">
            <div class="row">
                <?php
                if (isset($topicos)) {
                    foreach ($topicos as $topico) {

                        $total_perguntas = $userDAO->contar_perguntas_topico($topico->getId())['Total'];
                        echo "
                                            <div class=\"col-4 pt-5\">
            <a href='perguntas.php?topico=".$topico->getId()."'>
                <div class=\"card border-secondary card-topico\">
                <div class=\"card-header\">".$topico->getNome()."</div>
                    <div class=\"card-body\">
                        <p class=\"card-text\">Total de perguntas = ".$total_perguntas."</p>
                    </div>
            </a>
            <form method='post' action='includes/Controllers/forms/add_teachers_components.php'>
                    <div class=\"card-footer text-center\">
                   <label for=\"example-number-input\" class=\"form-label\">Número de Perguntas</label>
  
                    <input class=\"form-control text-center\" type=\"number\" value=\"1\" min='1' id=\"example-number-input\"
                    name='nr-perguntas' max='".$total_perguntas."'>
                    <div class='btn-adc pt-3'>
                        <button class='btn btn-primary' type='submit' name='submit-teste-topicos'>Adicionar</button>
                  <input type=\"text\" class=\"d-none\" name=\"id-teste\" value=".$teste->getId().">
               <input type=\"text\" class=\"d-none\" name=\"id-topico\" value=".$topico->getId().">


                    </div>
                      
                    </div>
            </form>
        </div>
</div>
                    
                    ";
                    }
                }

                ?>
            </div>

        </div>
        <div class="btn-voltar pb-5" align="center">
            <button class="btn btn-primary" onclick="window.location.href='http://localhost/projeto/inicial_professores.php'" type="button">Terminar criação</button>
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


<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Disciplina.php';
require_once 'includes/Models/Topico.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Aluno")
    header("Location: http://localhost/projeto/login.php");

$userDao = new UserDAO();

if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $disciplina_bd = $userDao->obter_disciplina($_GET['disciplina']);
    $disciplina = new Disciplina($disciplina_bd['id'],$disciplina_bd['nome'],$disciplina_bd['descricao']);
}
if (isset($disciplina)){
    $topicos_db = $userDao->obter_topicos($disciplina->getId());
    $i = 0;
    if (isset($topicos_db)){
        foreach ($topicos_db as $value){
            $topicos[$i] =new Topico($value['id'],$value['nome'],$value['id_disciplina']);
            $i++;
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
            <li>
                <a href="inicial_professores.php">Testes</a>
            </li>
            <li class="active">
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
                <li class="breadcrumb-item"><a href="inicial_professores.php">Home</a></li>
                <li class="breadcrumb-item"><a href="Disciplinas.php">Disciplinas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Topicos</li>
            </ol>
        </nav>
        <div class="jumbotron jumbotron-fluid" >
            <div class="container" align="center">
                <h1 class="display-5"><?php echo $disciplina->getNome() ?></h1>
                <p class="lead"><?php echo $disciplina->getDescricao()?></p>
            </div>
        </div>
        <div class="info-topicos">
            <div class="title-topicos" align="center">
                <h4>Topicos</h4>
            </div>
            <div class="container">
                <div class="row">
                    <?php
                if (isset($topicos)) {
                    foreach ($topicos as $topico) {
                        echo "
                    <div class=\"col-4 pt-5\">
                                       <a href='perguntas.php?topico=".$topico->getId()."'>
                        <div class=\"card border-secondary mb-3 card-topico\">
                            <div class=\"card-header\">" . $topico->getNome() . "</div>
                                <p class=\"card-text\">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                        </a>
                    </div>
                    
                    ";
                    }
                }

                    ?>
                </div>
            </div>
            <div class="adicionar-topicos pb-5 pt-3" align="center">
                <button type="button" class="btn btn-primary"
                        data-toggle="modal" data-target="#modal-topicos">Adicionar Topico</button>
            </div>
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

<!-- modal para intrduzir um topico -->
<div class="modal fade" id="modal-topicos" tabindex="-1" role="dialog" aria-labelledby="modal-topicos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar topico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group">
                        <label for="nome-topico">Nome</label>
                        <input type="text" class="form-control" id="nome-topico" name="nome-topico" required>
                        <input type="text" class="d-none" name="id-disciplina" value="<?php echo $disciplina->getId() ?>">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" name="submit-topico">Confirmar</button>
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

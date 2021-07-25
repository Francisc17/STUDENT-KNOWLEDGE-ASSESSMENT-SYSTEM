<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Disciplina.php';
require_once 'includes/Models/Topico.php';
require_once 'includes/Models/Pergunta.php';

$lang = require 'includes/Language/lang_pt.php';


if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Aluno")
    header("Location: http://localhost/projeto/login.php");

$userDao = new UserDAO();

if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $topico_bd = $userDao->obter_topico($_GET['topico']);
    $topico = new Topico($topico_bd['id'],$topico_bd['nome'],$topico_bd['id_disciplina']);
}

if (isset($topico)){
    $perguntas_db = $userDao->obter_perguntas($topico->getId());
    if (isset($perguntas_db)){
        $i=0;
        foreach ($perguntas_db as $value){
            $perguntas[$i] = new Pergunta($value['id'],$value['texto_pergunta'],$value['cotacao'],
                                          $value['dificuldade'],$value['id_topico']);
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
                <li class="breadcrumb-item"><a href="topicos.php?disciplina=<?php echo $topico->getIdDisciplina()?>">Topicos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Perguntas</li>
            </ol>
        </nav>
        <div class="title-topico pt-3" align="center">
            <h4><?php echo $topico->getNome() ?></h4>
        </div>

        <div class="info-perguntas">
            <div class="container">
                <div class="row">
                    <?php
                    if (isset($perguntas)) {
                        $i = 1;
                        foreach ($perguntas as $pergunta) {
                            echo "
                    <div class=\"col-4 pt-5\">
                    <a href='respostas.php?pergunta=".$pergunta->getId()."'>
                        <div class=\"card border-secondary mb-3 card-perguntas\">
                            <div class=\"card-header\">Pergunta " . $i . "</div>
                                <p class=\"card-text\">".$pergunta->getTexto()."</p>
                                <div class='container'>
                             <div class='row card-footer text-muted'>
                             <div class='col-6'>
                             cotação: ".$pergunta->getCotacao()." 
                             </div>
                             <div class='col-6'>
                              Dificuldade: ".$pergunta->getDificuldade()."     
                             </div>
                             </div>
</div>

                        </div>
               </a>
                    </div>
                    
                    ";
                    $i++;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="adicionar-pergunta pb-5 pt-3" align="center">
            <button type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#modal-pergunta">Adicionar pergunta</button>
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

<!-- Modal para inserir pergunta -->
<div class="modal fade" id="modal-pergunta" tabindex="-1" role="dialog" aria-labelledby="modal-pergunta" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Pergunta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="respostas.php">
                    <div class="form-group">
                        <label for="texto-pergunta">Example textarea</label>
                        <textarea class="form-control" id="texto-pergunta" rows="3" name="texto-pergunta"></textarea>
                        <input type="text" class="d-none" name="id-topico" value="<?php echo $topico->getId() ?>">
                    </div>
                    <label for="cotacao">Cotacao</label>
                    <input type="text" class="form-control" id="cotacao" name="cotacao" required>
                    <label for="dificuldade">Dificuldade</label>
                    <input type="text" class="form-control" id="dificuldade" name="dificuldade" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" name="submit-pergunta" class="btn btn-primary">Confirmar</button>
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

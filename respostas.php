<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Disciplina.php';
require_once 'includes/Models/Topico.php';
require_once 'includes/Models/Pergunta.php';
require_once 'includes/Models/Resposta.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Aluno")
    header("Location: http://localhost/projeto/login.php");

$userDao = new UserDAO();

if ($_SERVER['REQUEST_METHOD']=="GET"){
    $pergunta_db = $userDao->obter_pergunta($_GET['pergunta']);
    $pergunta = new Pergunta($pergunta_db['id'],$pergunta_db['texto_pergunta'],$pergunta_db['cotacao'],
                             $pergunta_db['dificuldade'],$pergunta_db['id_topico']);
}else{
    if (isset($_POST['submit-pergunta'])){
        $id = $userDao->inserir_pergunta($_POST['texto-pergunta'],$_POST['cotacao'],$_POST['dificuldade'],$_POST['id-topico']);
        $pergunta = new Pergunta($id,$_POST['texto-pergunta'],$_POST['cotacao'],$_POST['dificuldade'],$_POST['id-topico']);
    }
}

if (isset($pergunta)){
    $respostas_db = $userDao->obter_respostas($pergunta->getId());
    $i = 0;
    if (isset($respostas_db)){
        foreach ($respostas_db as $value){
            $respostas[$i] = new Resposta($value['texto_resposta'],$value['correta'],$value['id'],$value['id_pergunta']);
            $i++;
        }
    }
}

if (isset($pergunta)){
    $topico = $userDao->obter_topico($pergunta->getIdTopico());
    if (isset($topico))
        $id_disciplina = $topico['id_disciplina'];
}

$nr_respostas = $userDao->contar_respostas($pergunta->getId())['Total'];
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
                <li class="breadcrumb-item"><a href="topicos.php?disciplina=<?php echo $id_disciplina?>">Topicos</a></li>
                <li class="breadcrumb-item"><a href="perguntas.php?topico=<?php echo $pergunta->getIdTopico()?>">Perguntas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Respostas</li>
            </ol>
        </nav>
        <div class="container">
        <div class="row justify-content-center">
        <div class="col-4 pt-5" align="center">
            <div class="card border-secondary mb-3 card-perguntas">
                <div class="card-header" align="center">Pergunta</div>
                    <p class=\"card-text\"> <?php echo $pergunta->getTexto() ?>"</p>
                <div class='container'>
                <div class='row card-footer text-muted'>
                    <div class='col-6'>
                        cotação: <?php echo $pergunta->getCotacao()?>
                    </div>
                    <div class='col-6'>
                        Dificuldade:<?php echo $pergunta->getDificuldade() ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
            <?php

            if ($userDao->pergunta_associada_testes($pergunta->getId())){
                echo "
                            <div class=\"alterar perguntas pb-5 pt-3\" align=\"center\">
                <button type=\"button\" class=\"btn btn-primary\"
                        data-toggle=\"modal\" data-target=\"#modal-alterar-pergunta\">Alterar pergunta</button>
            </div>
                ";
            }

            ?>

            <div class="title-topico pt-5 pb-4" align="center">
                <h4>Respostas</h4>
            </div>
            <ul class="list-group list-group-flush pb-3">
                <?php
                if (isset($respostas)){
                 foreach ($respostas as $value){
                     if ($value->getCorreta()) {
                         echo "
                         <li class=\"list-group-item list-group-item-success\">".$value->getTexto()."</li>
                         ";
                     }else {
                         echo "
                            <li class=\"list-group-item\"> ".$value->getTexto()." </li>
                         ";
                     }
                 }
                }
                ?>
            </ul>
        </div>
        <div class="adicionar-resposta pb-5 pt-3" align="center">
            <button type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#modal-perguntas">Adicionar resposta</button>
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
<div class="modal fade " id="adicionar-resposta" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="alert alert-warning" role="alert" id="alerta">
            Adicione pelo menos 2 respostas!
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Adicionar resposta</h5>
            </div>
            <div class="modal-body">
                <?php
                if (isset($respostas[0]) && $nr_respostas == 1){
                    if (!$respostas[0]->getCorreta()){
                        ?>
                <form onsubmit="return respostas_iniciais()" method="post" action="includes/Controllers/forms/add_teachers_components.php">
                    <?php
                    }else?>
                    <form method="post" action="includes/Controllers/forms/add_teachers_components.php">
                        <?php
                }else ?>
                    <form method="post" action="includes/Controllers/forms/add_teachers_components.php">

                <div class="form-group">
                    <label for="descricao-resposta">resposta</label>
                    <textarea class="form-control" id="descricao-resposta" rows="3" name="descricao-resposta"></textarea>
                    <input type="text" class="d-none" name="id-pergunta" value="<?php echo $pergunta->getId() ?>">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="correta" name="correta">
                    <label class="form-check-label" for="correta">correta</label>
                </div>
            </div>
            <div class="modal-footer">
                <button onsubmit="return respostas_iniciais()" type="submit" name="submit-resposta" class="btn btn-primary">Confirmar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-alterar-pergunta" tabindex="-1" role="dialog" aria-labelledby="modal-alterar-pergunta" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar pergunta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <textarea class="form-control" id="conteudo" rows="3" name="conteudo" required></textarea>
                                <input type="text" class="d-none" name="id" value="<?php echo $pergunta->getId() ?>">
                            </div>
                            <div class="col pt-3">
                                <button type="submit" value="submit" name="submit-pergunta-conteudo" class="btn btn-primary">alterar Conteudo</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="number" class="form-control" min="1" max="20" id="cotacao" name="cotacao"
                                       placeholder="cotação" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $pergunta->getId() ?>">
                            </div>
                            <div class="col">
                                    <button type="submit" value="submit" name="submit-pergunta-cot" class="btn btn-primary">alterar Cotação</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="number" class="form-control" min="1" max="20" id="dificuldade" name="dificuldade"
                                       placeholder="Dificuldade" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $pergunta->getId() ?>">
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-pergunta-dif" class="btn btn-primary">alterar Dificuldade</button>
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

    var x = <?php echo $nr_respostas ?>;
    <?php echo $nr_respostas ?>;

    if (x<2){
        $('#adicionar-resposta').modal('show');
    }


</script>



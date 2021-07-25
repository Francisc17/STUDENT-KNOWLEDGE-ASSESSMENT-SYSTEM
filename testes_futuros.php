<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Teste.php';
require_once 'includes/Models/Disciplina.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']))
    header("Location: http://localhost/projeto/login.php");

$userDao = new UserDAO();

if ($_SERVER['REQUEST_METHOD'] == "GET"){

  $teste_db = $userDao->obter_teste($_GET['teste']);
    $teste = new Teste($teste_db['nome'],$teste_db['observacoes'],$teste_db['hora_disponivel'],$teste_db['data_disponivel'],
        $teste_db['duracao'],$teste_db['id_disciplina'],$teste_db['id'],$teste_db['hash']);
}

if (isset($teste)){
    $disciplina_bd = $userDao->obter_disciplina($teste->getIdDisciplina());

    $disciplina = new Disciplina($disciplina_bd['id'],$disciplina_bd['nome'],$disciplina_bd['descricao']);
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
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Geral</a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a href="#">Home 1</a>
                    </li>
                    <li>
                        <a href="#">Home 2</a>
                    </li>
                    <li>
                        <a href="#">Home 3</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="Disciplinas.php">Disciplinas</a>
            </li>
            <li>
                <a href="criar_testes.php">Testes</a>
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
                <?php if ($_SESSION['tipo'] == "Aluno"){
                    echo "
                       <li class=\"breadcrumb-item\"><a href=\"inicial_alunos.php\">Home</a></li>
                    ";
                }else
                    echo "
                       <li class=\"breadcrumb-item\"><a href=\"inicial_professores.php\">Home</a></li>
                    "; ?>
                <li class="breadcrumb-item active" aria-current="page">Teste futuro</li>
            </ol>
        </nav>

        <div class="title-teste text-center py-3">
            <h4><?php echo $teste->getNome()?></h4>
        </div>

        <div class="Disciplina pl-5">
            <div class="tittle-disciplina">
                <h5>Disciplina:</h5>
            </div class>
            <p><?php echo $disciplina->getNome()?></p>
        </div>


        <div class="observacoes pl-5">
            <div class="tittle-observacoes">
                <h5>Observações:</h5>
            </div class>
            <p><?php echo $teste->getObservacao()?></p>
        </div>

        <div class="data_hora pl-5">
            <div class="tittle-dataHora">
                <h5>Data/hora:</h5>
            </div class>
            <p><?php echo $teste->getDataInicio()?>/<?php echo $teste->getHoraInicio()?></p>
        </div>

        <div class="duracao pl-5">
            <div class="tittle-duracao">
                <h5>Duração:</h5>
            </div class>
            <p><?php echo $teste->getDuracao()?></p>
        </div>

        <div class="duracao pl-5">
            <div class="tittle-duracao">
                <h5>Chave para realizar o exame:</h5>
            </div class>
            <p><?php echo $teste->getHash()?></p>
        </div>

        <?php
         if ($_SESSION['tipo'] == "Professor"){
             echo "
                     <div class=\"adicionar-testes pb-5 pt-3\" align=\"center\">
            <button type=\"button\" class=\"btn btn-primary\"
                    data-toggle=\"modal\" data-target=\"#alterar-exame\">Alterar teste</button>
        </div>

    </div>
</div>
             
             ";
         }
         ?>


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

<!-- modal para alteracao de um exame -->
<div class="modal fade" id="alterar-exame" tabindex="-1" role="dialog" aria-labelledby="alterar-exame" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar exame</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- form para o nome -->
                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="text" class="form-control" id="nome" placeholder="Novo nome do teste"
                                       name="teste-nome" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-nome" class="btn btn-primary">alterar nome</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="date" class="form-control" id="data" placeholder="Nova data de inicio"
                                       name="teste-data-inicio" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">

                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-data-inicio" class="btn btn-primary">alterar data incial</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="time" class="form-control" id="hora" placeholder="Nova hora de inicio"
                                       name="teste-hora-inicio" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-hora-inicio" class="btn btn-primary">alterar hora inicial</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="time" class="form-control" id="duracao" placeholder="Nova duração do teste"
                                       name="teste-duracao" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-duracao" class="btn btn-primary">alterar duração</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <textarea class="form-control" id="observacoes" rows="3" name="teste-obs" required></textarea>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">

                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-obs" class="btn btn-primary">alterar observações</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="includes/Controllers/forms/add_teachers_components.php" method="post">
                    <div class="form-group pt-4">
                        <div class="row">
                            <div class="col-8">
                                <input type="text" class="form-control" id="nome" placeholder="chave personalizada"
                                       name="teste-hash" required>
                                <input type="text" class="d-none" name="id" value="<?php echo $teste->getId() ?>">
                            </div>
                            <div class="col">
                                <button type="submit" value="submit" name="submit-teste-hash" class="btn btn-primary">alterar chave</button>
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

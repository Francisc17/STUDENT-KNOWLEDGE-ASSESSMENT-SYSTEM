<?php
session_start();
$tittle = "Login";
require_once 'includes/header.php';
require_once 'includes/Controllers/Database/UserDAO.php';
require_once 'includes/Models/Teste.php';
require_once 'includes/Models/Pergunta.php';
require_once 'includes/Models/Resposta.php';

$lang = require 'includes/Language/lang_pt.php';

if (!isset($_SESSION['nome']) || $_SESSION['tipo'] == "Professor")
    header("Location: http://localhost/projeto/login.php");

$userDao = new UserDAO();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $userDao->alterar_estado_teste(2,$_SESSION['id'],$_GET['teste']);

        $id_testes_alunos = $userDao->obter_id_testes_alunos($_SESSION['id'],$_GET['teste'])['id'];


        $perguntas_db_id = $userDao->obter_perguntas_teste_criado($_SESSION['id'], $_GET['teste']);

        if (isset($perguntas_db_id) && count($perguntas_db_id) > 0) {
            $i = 0;
            foreach ($perguntas_db_id as $value) {
                $perguntas_db[$i] = $userDao->obter_pergunta($value['id_pergunta']);
                $i++;
            }
            $i = 0;
            foreach ($perguntas_db as $value){
                $perguntas[$i] = new Pergunta($value['id'], $value['texto_pergunta'], $value['cotacao'],
                    $value['dificuldade'], $value['id_topico']);
                $i++;
            }
        } else {
            $topicos_db = $userDao->obter_topicos_teste($_GET['teste']);

            if (isset($topicos_db)) {
                $i = 0;
                $j = 0;
                foreach ($topicos_db as $topico) {
                    $perguntas_db[$i] = $userDao->perguntas_aleatorias_topico($topico['id_topico'], $topico['nr_perguntas']);

                    foreach ($perguntas_db[$i] as $value) {
                            $perguntas[$j] = new Pergunta($value['id'], $value['texto_pergunta'], $value['cotacao'],
                                $value['dificuldade'], $value['id_topico']);
                            $userDao->criar_teste_associado_aluno($_SESSION['id'],$topico['id'],$value['id']);
                            $j++;
                        }

                    $i++;
                }
            }
        }


        if (isset($perguntas))
            shuffle($perguntas);



        if (isset($perguntas)) {
            $i = 0;
            foreach ($perguntas as $pergunta) {
                $respostas_db[$i] = $userDao->obter_respostas($pergunta->getId());
                $i++;
            }


            if (isset($respostas_db)) {
                $i = 0;
                foreach ($respostas_db as $respostas_arr) {
                    foreach ($respostas_arr as $value) {
                        $respostas[$i] = new Resposta($value['texto_resposta'], $value['correta'], $value['id'],
                            $value['id_pergunta']);
                        $i++;
                    }
                }
            }
        }

        $teste = $userDao->obter_teste($_GET['teste']);
        $duracao = $teste['duracao'];
    }
?>



<link rel="stylesheet" href="css/pagina_inicial.css">
</head>
<body>

<div class="wrapper">

    <!-- Page Content  -->
    <div id="content">

        <div class="navbar navbar-expand">
            <ul class="navbar-nav ml-auto">
                <img src="<?php echo $_SESSION['foto']?>" alt="sdasdas" class="profile-pic">
                <p class="username text-center">
                    <?php
                    echo $_SESSION['nome']."<br>".$_SESSION['tipo'];
                    ?></p>
            </ul>
        </div>

        <div class="contdown">
            <div class="container">
                <div class="row">
                    <div class="col relogio py-4" align="right" id="relogio">
                       tempo restante:
                    </div>
                </div>
            </div>
        </div>
        <input type="text" class="d-none" name="duracao" id="duracao" value="<?php echo $duracao ?>">

        <div class="perguntas" align="center">

                    <?php

                    if (isset($perguntas)) {

                        $i = 0;
                        foreach ($perguntas as $pergunta) {

                            $id_teste_topicos_pergunta[$i] = $userDao->obter_teste_topicos_pergunta_id(
                                    $pergunta->getId(),$_SESSION['id'],$_GET['teste'],$pergunta->getIdTopico()
                            )['id'];

                            echo "
                    <div class=\"col-7 pt-5\">
                    
                        <div class=\"card border-secondary mb-3 card-perguntas\">
                            <div class=\"card-header\">" . $i . ". ".$pergunta->getTexto()."</div>
                            ";

                            echo "<form>";
                    foreach ($respostas as $resposta){

                        if ($pergunta->getId() == $resposta->getIdPergunta()){
                            echo "
                            
                            <div class=\"form-check pl-4 py-2\" align='left'>
  <input onblur='pedido_ajax_resposta(this.value,".$id_teste_topicos_pergunta[$i].")' class=\"form-check-input\" type=\"radio\" name=\"exampleRadios\" id=\"".$resposta->getId()."\" value=\" ".$resposta->getId()." \">
  <label class=\"form-check-label\" for=\"exampleRadios1\">
    ".$resposta->getTexto()."
  </label>
</div>

                            
                            ";
                        }

                    }
                            echo "</form>";
                    ?>


<?php echo "

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
              
                    </div>
                    
                    ";
                            $i++;
                        }
                    }
                    ?>
        </div>
        <div class="btn-enviar py-5" align="center">
            <button type="button" class="btn btn-primary" id="terminar" onclick=terminar_teste_ajax(<?php echo json_encode($id_teste_topicos_pergunta) ?>,<?php echo $_GET['teste'] ?>,<?php echo $_SESSION['id']?>)> Terminar teste </button>
        </div>
        <div class="btn-chat" align="right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-chat">chat</button>
        </div>

        </div>
</div>

<div class="modal fade" id="modal-chat" tabindex="-1" role="dialog" aria-labelledby="chat" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="mensagens" id="mensagens">


            </div>

            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-10">
                            <input type="text" class="form-control" id="input-msg-chat" placeholder="Mensagem a enviar"
                                   name="chat-msg" required>
                        </div>
                        <div class="col">
                            <button type="button" onclick="enviar_mensagem_chat(

                                <?php echo $id_testes_alunos ?> , false

                            )" class="btn btn-primary">enviar</button>
                        </div>
                    </div>
                </div>
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

<script src="scripts/AJAX_resposta.js"></script>
<script src="scripts/AJAX_chat.js"></script>


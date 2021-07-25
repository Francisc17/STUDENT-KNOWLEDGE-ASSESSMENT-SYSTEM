<?php
session_start();

include_once "../Database/UserDAO.php";

if (isset($_POST['submit-hash-aluno'])){
    $userDao = new UserDAO();

    $teste = $userDao->obter_id_teste_pela_hash($_POST['hash']);

    if (isset($teste)){
        if ($userDao->aluno_associado_teste($teste['id'],$_SESSION['id'])) {
            $id_teste_aluno = $userDao->inserir_teste_alunos($teste['id'], $_SESSION['id']);
        }
    }

    header("Location: http://localhost/projeto/inicial_alunos.php");
}

if ($_SERVER['REQUEST_METHOD'] == "GET"){
    $userDao = new UserDAO();


    if (isset($_GET['id_ttp']) && isset($_GET['resp'])){
        $id_inserted = $userDao->adicionar_resposta_temporaria($_GET['resp'],$_GET['id_ttp']);

        if ($id_inserted != null)
            echo 1;
        else
            echo 2;
    }

    if (isset($_GET['id_ttp']) && isset($_GET['final'])){
        $userDao->terminar_teste($_GET['id_ttp']);
    }

    if (isset($_GET['teste']) && isset($_GET['id_aluno'])){
        $var = $userDao->alterar_estado_teste(3,$_GET['id_aluno'],$_GET['teste']);

        echo $var;
    }


}

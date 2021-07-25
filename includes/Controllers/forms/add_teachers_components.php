<?php
session_start();

include_once "../Database/UserDAO.php";

if (isset($_POST['submit-disciplina'])){
    $userDAO = new UserDAO();

    $userDAO->inserir_disciplina($_POST['nome-disciplina'],$_POST['descricao-discplina'],$_SESSION['id']);

    header("Location: http://localhost/projeto/Disciplinas.php");
}

if (isset($_POST['submit-topico'])){
    $userDAO = new UserDAO();

    $userDAO->inserir_topico($_POST['nome-topico'],$_POST['id-disciplina']);

    header("Location: http://localhost/projeto/topicos.php?disciplina=".$_POST['id-disciplina']);
}

if (isset($_POST['submit-resposta'])){
    $userDAO = new UserDAO();

    if (isset($_POST['correta']))
        $_POST['correta'] = true;
    else
        $_POST['correta'] = false;


    $userDAO->inserir_resposta($_POST['descricao-resposta'],$_POST['correta'],$_POST['id-pergunta']);

    header("Location: http://localhost/projeto/respostas.php?pergunta=".$_POST['id-pergunta']);
}

if (isset($_POST['submit-teste'])){
    $userDAO = new UserDAO();

    $disciplina_id = $userDAO->obter_disciplina_id($_POST['disciplina'])['id'];
    echo $disciplina_id;

    $id_teste = $userDAO->inserir_testes($_POST['teste-nome'],$_POST['observacoes'],$_POST['data-inicio'],$_POST['hora-inicio'],
        $_POST['duracao'],$disciplina_id);

    if (isset($id_teste)){
        header("Location: http://localhost/projeto/criar_teste_topicos.php?teste=".$id_teste);
    }
}

if (isset($_POST['submit-teste-topicos'])){
    $userDAO = new UserDAO();
    $teste_topicos_id = $userDAO->inserir_testes_topicos($_POST['id-teste'],$_POST['id-topico'],$_POST['nr-perguntas']);

    header("Location: http://localhost/projeto/criar_teste_topicos.php?teste=".$_POST['id-teste']);
}

if (isset($_POST['submit-teste-nome'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-nome'],"nome",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);
}

if (isset($_POST['submit-teste-data-inicio'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-data-inicio'],"data_disponivel",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);

}

if (isset($_POST['submit-teste-duracao'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-duracao'],"duracao",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);

}

if (isset($_POST['submit-teste-hora-inicio'])){

        $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-hora-inicio'],"hora_disponivel",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);

}

if (isset($_POST['submit-teste-obs'])){

    $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-obs'],"observacoes",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);

}

if (isset($_POST['submit-teste-hash'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_teste($_POST['teste-hash'],"hash",$_POST['id']);

    header("Location: http://localhost/projeto/testes_futuros.php?teste=".$_POST['id']);
}

if (isset($_POST['submit-pergunta-conteudo'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_pergunta($_POST['conteudo'],'texto_pergunta',$_POST['id']);

    header("Location: http://localhost/projeto/respostas.php?pergunta=".$_POST['id']);
}

if (isset($_POST['submit-pergunta-cot'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_pergunta($_POST['cotacao'],'cotacao',$_POST['id']);

    header("Location: http://localhost/projeto/respostas.php?pergunta=".$_POST['id']);
}

if (isset($_POST['submit-pergunta-dif'])){
    $userDAO = new UserDAO();

    $userDAO->alterar_campo_pergunta($_POST['dificuldade'],'dificuldade',$_POST['id']);

    header("Location: http://localhost/projeto/respostas.php?pergunta=".$_POST['id']);
}
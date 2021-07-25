<script src="../scripts/scripts.js"></script>
<?php

session_start();

include_once 'Controllers/Database/UserDAO.php';


if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $retrieve_user = new UserDAO();

    $valor = $retrieve_user->getUser($email,$password);


    switch ($valor){
        case $valor === 1:
            header("Location: http://localhost/projeto/login.php?erro=1");
            break;
        case $valor === 2:
            header("Location: http://localhost/projeto/login.php?erro=2");
            break;
        case $valor === 3:
            header("Location: http://localhost/projeto/login.php?erro=3");
            break;
        case $valor === true:
            if ($_SESSION['tipo'] === "Aluno")
                header("Location: http://localhost/projeto/inicial_alunos.php");
            else
                header("Location: http://localhost/projeto/inicial_professores.php");
    }
}
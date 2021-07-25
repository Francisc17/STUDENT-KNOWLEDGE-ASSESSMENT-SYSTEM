<?php

session_start();

include_once "../Database/UserDAO.php";


if (isset($_POST['submit-nome'])) {
    $userDAO = new UserDAO();

    if ($userDAO->alterar_nome($_POST['novo-nome'], $_SESSION['id'])){
        if ($_SESSION['tipo'] === "Aluno")
            header("Location: http://localhost/projeto/inicial_alunos.php");
        else
            header("Location: http://localhost/projeto/inicial_professores.php");
    }
}

if (isset($_POST['submit-pass'])) {
    $userDAO = new UserDAO();

    if ($userDAO->alterar_password($_POST['nova-pass'], $_SESSION['id'])){
        if ($_SESSION['tipo'] === "Aluno")
            header("Location: http://localhost/projeto/inicial_alunos.php");
        else
            header("Location: http://localhost/projeto/inicial_professores.php");
    }
}

if (isset($_POST['submit-image'])){

    if (isset($_FILES['file-upload']['name']) && $_FILES['file-upload']['size']>0){
        $target_dir = "imagens/";
        $target_file = $target_dir . basename($_FILES["file-upload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["file-upload"]["tmp_name"]);

        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["file-upload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], "../../../" . $target_file)) {
                $userDAO = new UserDAO();
                $userDAO->alterar_foto($target_file,$_SESSION['id']);
                header("Location: http://localhost/projeto/inicial_alunos.php");
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }else{
        if ($_SESSION['tipo'] === "Aluno")
            header("Location: http://localhost/projeto/inicial_alunos.php");
        else
            header("Location: http://localhost/projeto/inicial_professores.php");
    }
}
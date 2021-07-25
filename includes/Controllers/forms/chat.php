<?php
session_start();

include_once "../Database/UserDAO.php";

$userDAO = new UserDAO();

if ($_SERVER['REQUEST_METHOD'] == "GET"){

    if (isset($_GET['id'])){
        $lastInsertedId = $userDAO->inserir_msg_chat($_GET['id'],$_GET['msg'],$_GET['teacher']);

        echo $lastInsertedId;
    }

    if (isset($_GET['last_id'])){
        $max_id = $userDAO->obter_msg_maior_id($_GET['last_id']);

        echo json_encode($max_id);

    }
}
<?php

include_once "../Controllers/Database/UserDAO.php";

$user = new UserDAO();

if (isset($_SERVER["REQUEST_METHOD"])=="GET"){
    $user->validar_email($_GET['email'],$_GET['token']);
}



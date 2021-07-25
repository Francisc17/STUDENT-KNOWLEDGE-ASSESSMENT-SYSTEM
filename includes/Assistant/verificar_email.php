<?php

include_once '../Controllers/Database/Bd.php';

    $conn = Bd::getInstance()->getConn();
    $result = 1;


    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $email = $_GET["email"];

        $stmt = $conn->prepare("SELECT * FROM utilizadores where email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_OBJ);

        if ($row = $stmt->fetch())
            $result = 0;

        echo $result;
    }

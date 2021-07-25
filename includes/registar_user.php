<script src="../scripts/scripts.js"></script>
<?php

include_once 'Controllers/Database/UserDAO.php';



if(isset($_POST['submit'])) { // Fetching variables of the form which travels in URL

    $uploadOk = 1;

    if (isset($_POST['customRadioInline1']) && isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['password'])) {

        $tipo = $_POST['customRadioInline1'];
        $name = $_POST['nome'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $insert_user = new UserDAO();

        if (strcmp("aluno",$tipo) == 0) {
            $tipo = true;
        }else
            $tipo = false;

        if (!(isset($_FILES['file-upload']['name']))) {
            $default_foto = "imagens/Default-user-picture.jpg";
            $insert_user->newUser($name, $email, $password, $tipo, $default_foto, false);
        }else {

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
                if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], "../" . $target_file)) {
                    $insert_user->newUser($name, $email, $password, $tipo, $target_file, false);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}





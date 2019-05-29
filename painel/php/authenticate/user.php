<?php
include_once "../config/database.php";
include_once "../model/user.php";
include_once "../controller/user.php";

session_start();
if (isset($_SESSION["user"])) {
    header("location: ../../../usuario");
}

if(isset($_POST["btn-login"])) {
    $cpf = isset($_POST["user"]) ? $_POST["user"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if(!empty($cpf) && !empty($password)) {
        $database = new Database();
        $conn = $database->getConn();
        
        if($conn != null) {
            $user = new User($cpf, null, $password, null, null, null, null, null);

            $userController = new UserController();
            $user = $userController->getUser($user, $conn);

            if($user) {
                $_SESSION["user"] = $user;
            } else {
                $_SESSION["msg"] = "Usuário/Senha incorretos.";
            }

        }
    } else {
        $_SESSION["msg"] = "Dados não preenchidos. Preencha os dados e tente novamente.";
    }

    header("location: ../../../usuario");
}

?>
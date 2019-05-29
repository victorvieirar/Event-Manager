<?php
include_once "../config/database.php";
include_once "../model/admin.php";
include_once "../controller/admin.php";

session_start();
if(isset($_SESSION["admin"])) {
    header("location: ../../");
}

if(isset($_POST["btn-login"])) {
    $user = isset($_POST["user"]) ? $_POST["user"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    
    if(!empty($user) && !empty($password)) {
        $database = new Database();
        $conn = $database->getConn();
        
        if($conn != null) {
            $admin = new Admin($user, $password, null);
            
            $adminController = new AdminController();
            $admin = $adminController->getAdmin($admin, $conn);

            if($admin) {
                $_SESSION["admin"] = $admin;
            } else {
                $_SESSION["msg"] = "Usuário/Senha incorretos.";
            }
        }
    } else {
        $_SESSION["msg"] = "Dados não preenchidos. Preencha os dados e tente novamente.";
    }

    header("location: ../../");
}

?>
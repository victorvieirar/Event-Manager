<?php
include_once "../model/user.php";
include_once "../config/database.php";
include_once "../controller/user.php";

session_start();
if(isset($_POST['btn-register'])) {
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $formation = isset($_POST['formation']) ? $_POST['formation'] : '';

    if(empty($cpf) || empty($name) || empty($email) || empty($password) || empty($phone) || empty($estado) || empty($course) || empty($formation)) {
        header("Location: ../../../");
        exit;
    } else {
        $database = new Database();
        $conn = $database->getConn();

        $user = new User($cpf, $name, $password, $email, $phone, $estado, $course, $formation);
        
        $userController = new UserController();
        try {
            $success = $userController->saveUser($user, $conn);
        } catch(Exception $e) {
            $_SESSION['msg'] = "Usuário já cadastrado. Faça login com seu CPF e senha.";
            header("Location: ../../../usuario");
        }
        if($success) {
            $_SESSION['user'] = $user;
            header("Location: ../../../usuario");
        } else {
            var_dump($user);
            $userDB = $userController->getUserInformation($user, $conn);
            var_dump($userDB);
            die();
            if($userDB != false) {
                $_SESSION['msg'] = "Usuário já cadastrado. Faça login com seu CPF e senha.";
                header("Location: ../../../usuario");
            } else {
                $_SESSION['msg'] = "Erro ao cadastrar conta. Tente novamente mais tarde.";
                header("Location: ../../../");
            }
        }
    }
}elseif(isset($_POST['btn-forgot'])){
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if(empty($cpf) || empty($email)) {
        $_SESSION['msg'] = "Dados incompletos. Preencha todos os dados e tente novamente.";
        header("Location: ../../../usuario/forgot");
        exit;
    } else {
        $database = new Database();
        $conn = $database->getConn();

        $user = new User($cpf, null, null, $email, null, null, null, null);
        
        $userController = new UserController();
        $user = $userController->getUserInformation($user, $conn);
        
        if($user->getEmail() == $email) {
            $success = sendRecoverMail($user);
            if($success) {
                $_SESSION['msg'] = "Sua senha foi enviada para seu e-mail, confira a caixa de entrada.";
                header("Location: ../../../usuario/forgot");
            } else {
                $_SESSION['msg'] = "Houve um erro desconhecido com a recuperação de senha. Tente novamente mais tarde.";
                header("Location: ../../../usuario/forgot");
            }
        } else {
            $_SESSION['msg'] = "E-mail ou CPF inválidos. Confira os dados e tente novamente.";
            header("Location: ../../../usuario/forgot");
            exit;
        }
        
        session_start();
        if($success) {
            header("Location: ../../../usuario");
        } else {
            $_SESSION['msg'] = "Erro ao recuperar conta. Tente novamente mais tarde.";
            header("Location: ../../../usuario/forgot");
        }
    }
}

function sendRecoverMail($user) {
    //Email information
    $email = "naoresponder@iids.com.br";
    $subject = 'Recuperação de Senha - Plataforma IIDS';
    $content = file_get_contents("http://iids.com.br/usuario/forgot/mail.php?name=".explode(' ', $user->getName())[0]."&password=".$user->getPassword());
    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Sistema Plait <$email>\r\n";
    return mail($user->getEmail(), "$subject", $content, $headers);
}

?>
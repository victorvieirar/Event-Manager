<?php

if(isset($_POST['send-mail'])) {
    $sender = isset($_POST['email']) ? $_POST['email'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $company = isset($_POST['company']) ? $_POST['company'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    $email = "sistema@iids.com.br";
    $content = "Nome: $name<br>E-mail: $sender<br>Empresa: $company<br>Telefone: $phone<br><br><a href='http://iids.com.br/evento/?event=".$event."'>Clique aqui para acessar o evento</a>";
    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Sistema Plait <$email>\r\n";
    mail('contato@iids.com.br', "$subject", $content, $headers);

    header("location: ../../../evento/?event=$event");
}

?>
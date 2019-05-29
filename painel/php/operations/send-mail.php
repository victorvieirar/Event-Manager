<?php

if(isset($_POST['send-mail'])) {
    $sender = isset($_POST['email']) ? $_POST['email'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    $email = "sistema@iids.com.br";
    $message = nl2br($message);
    $content = "Nome: ".$name."<br>E-mail: ".$sender."<br>Conteúdo:<br>".$message."";
    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Sistema Plait <$email>\r\n";
    mail('contato@iids.com.br', "$subject", $content, $headers);

    header('location: ../../../');
}

?>
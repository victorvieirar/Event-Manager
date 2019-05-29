<?php

session_start();
if(isset($_POST['send']) && isset($_SESSION['admin'])) {
    $email = "contato@iids.com.br";
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $userName = isset($_POST['userName']) ? $_POST['userName'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';
    
    $userName = explode(' ', $userName);

    $content = '<html> <body style="font-family: \'Helvetica\'; background-color: #f2f2f2; padding: 15px; margin: 0;"> <div style="display: flex; flex-direction: row; justify-content: center; padding: 25px; background-color: #0b2437; border-top-left-radius: 5px; border-top-right-radius: 5px;"> <img style="width: 294.66px; height: 65px;" src="http://iids.com.br/media/logo.png" alt=""> </div><div style="font-weight: 600; font-size: 2rem; padding: 15px; background-color: #eee;"> Olá, '.$userName[0].'</div><div style="padding: 15px; background-color: #eee;">'.$message.'</div><div style="display: flex; flex-direction: row; justify-content: center; padding: 25px; background-color: rgb(100,100,100); border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;"> <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="https://www.facebook.com/Instituto-Integrado-de-Desenvolvimento-em-Sa%C3%BAde-IIDS-1105375109565745/"> Nosso Facebook</a> <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="https://www.instagram.com/iidsintegrada/"> Nosso Instagram</a> <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="mailto:contato@iids.com.br"> E-mail</a> <span style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;"> (84) 99605.5403</span> </div></body></html>';
    $headers = "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $event <$email>\r\n";
    mail($user, "$subject", $content, $headers);

    echo $content;
}

?>
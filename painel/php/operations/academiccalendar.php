<?php
session_start();

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['add-calendar-button']) && isset($_SESSION['admin'])) {

    $_UP['folder'] = '/../../../calendar/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['calendar-file']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['calendar-file']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../');
        exit;
    }
    $extension = explode(".", $_FILES['calendar-file']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['calendar-file']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['calendar-file']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../');
        exit;
    }
    
    $filename = "calendar.".$extension;
    
    if(is_uploaded_file($_FILES['calendar-file']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['calendar-file']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $_SESSION['message'] = 'Calendário atualizado com sucesso!';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../');

}
?>
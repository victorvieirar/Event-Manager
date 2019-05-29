<?php
session_start();

include_once '../config/database.php';
include_once '../model/speaker.php';
include_once '../controller/speaker.php';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['add-speaker-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $speakerController = new SpeakerController();

    $name = isset($_POST['speaker-name']) ? $_POST['speaker-name'] : '';
    $description = isset($_POST['speaker-description']) ? $_POST['speaker-description'] : '';
    $linkBio = isset($_POST['speaker-link']) ? $_POST['speaker-link'] : '';
    $event = isset($_POST['event-id']) ? $_POST['event-id'] : '';

    $_UP['folder'] = '/../../speakers/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['speaker-image']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['speaker-image']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    $extension = explode(".", $_FILES['speaker-image']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['speaker-image']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['speaker-image']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['speaker-image']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['speaker-image']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/speakers/".$filename;
            $speaker = new Speaker($event, $name, $description, $link, $linkBio);
            $success = $speakerController->saveSpeaker($speaker, $conn);
            
            $_SESSION['message'] = 'Palestrante adicionado com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event);

} elseif(isset($_POST['update-speaker-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $speakerController = new SpeakerController();

    $oldName = isset($_POST['speaker-old-name']) ? $_POST['speaker-old-name'] : '';
    $name = isset($_POST['speaker-name']) ? $_POST['speaker-name'] : '';
    $description = isset($_POST['speaker-description']) ? $_POST['speaker-description'] : '';
    $linkBio = isset($_POST['speaker-link']) ? $_POST['speaker-link'] : '';
    $event = isset($_POST['event-id']) ? $_POST['event-id'] : '';

    $_UP['folder'] = '/../../speakers/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['speaker-image']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['speaker-image']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    $extension = explode(".", $_FILES['speaker-image']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['speaker-image']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['speaker-image']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['speaker-image']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['speaker-image']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/speakers/".$filename;
            $newSpeaker = new Speaker($event, $name, $description, $link, $linkBio);
            $oldSpeaker = new Speaker($event, $oldName, null, null, null);
            $success = $speakerController->updateSpeaker($newSpeaker, $oldSpeaker, $conn);
            
            $_SESSION['message'] = 'Palestrante atualizado com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event);
}
?>
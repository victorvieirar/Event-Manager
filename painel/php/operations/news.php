<?php
session_start();

include_once '../config/database.php';
include_once '../model/event.php';
include_once '../model/news.php';
include_once '../controller/news.php';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['new-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event = new Event($_POST['event-id'], null, null, null, null, null, null, null, null);

    $newsController = new NewsController();

    $name = isset($_POST['new-title']) ? $_POST['new-title'] : '';
    $content = isset($_POST['new-content']) ? $_POST['new-content'] : '';

    if(empty($name) || empty($message)) {
        $_SESSION['message'] = "Não foi possível postar a notícia. Dados insuficientes. Preencha todos os dados necessários e tente novamente.";
        header('location: ../../event/?event='.$event->getId());
    }

    $_UP['folder'] = '/../../news/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['new-file']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['new-file']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event->getId());
        exit;
    }
    $extension = explode(".", $_FILES['new-file']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['new-file']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['new-file']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event->getId());
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['new-file']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['new-file']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/news/".$filename;
            $event->setFeatured_image($link);
            $new = new News(null, $name, $content, $link, $event->getId());

            $success = $newsController->saveNews($new, $conn);
            
            $_SESSION['message'] = 'Notícia publicada com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event->getId());

} elseif(isset($_POST['edit-new-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event = new Event($_POST['event-id'], null, null, null, null, null, null, null, null);

    $newsController = new NewsController();

    $id = isset($_POST['news-id']) ? $_POST['news-id'] : '';
    $name = isset($_POST['new-title']) ? $_POST['new-title'] : '';
    $content = isset($_POST['new-content']) ? $_POST['new-content'] : '';

    if(empty($name) || empty($message)) {
        $_SESSION['message'] = "Não foi possível postar a notícia. Dados insuficientes. Preencha todos os dados necessários e tente novamente.";
        header('location: ../../event/?event='.$event->getId());
    }

    $_UP['folder'] = '/../../news/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['new-file']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['new-file']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event->getId());
        exit;
    }
    $extension = explode(".", $_FILES['new-file']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['new-file']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['new-file']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event->getId());
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['new-file']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['new-file']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/news/".$filename;
            $event->setFeatured_image($link);
            $new = new News($id, $name, $content, $link, $event->getId());

            $success = $newsController->updateNews($new, $conn);
            
            $_SESSION['message'] = 'Notícia atualizada com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event->getId());
}

?>
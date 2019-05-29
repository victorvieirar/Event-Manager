<?php
session_start();

include_once '../config/database.php';
include_once '../model/partner.php';
include_once '../controller/partner.php';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['add-partner-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $partnerController = new PartnerController();

    $name = isset($_POST['partner-name']) ? $_POST['partner-name'] : '';
    $url = isset($_POST['partner-link']) ? $_POST['partner-link'] : '';
    $event = isset($_POST['event-id']) ? $_POST['event-id'] : '';

    $_UP['folder'] = '/../../partners/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['partner-image']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['partner-image']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    $extension = explode(".", $_FILES['partner-image']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['partner-image']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['partner-image']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['partner-image']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['partner-image']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/partners/".$filename;
            $partner = new Partner($name, $url, $link, $event);
            $success = $partnerController->savePartner($partner, $conn);
            
            $_SESSION['message'] = 'Parceiro adicionado com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event);

} elseif(isset($_POST['update-partner-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $partnerController = new PartnerController();

    $oldName = isset($_POST['partner-old-name']) ? $_POST['partner-old-name'] : '';
    $name = isset($_POST['partner-name']) ? $_POST['partner-name'] : '';
    $url = isset($_POST['partner-link']) ? $_POST['partner-link'] : '';
    $event = isset($_POST['event-id']) ? $_POST['event-id'] : '';

    $_UP['folder'] = '/../../partners/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['partner-image']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['partner-image']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    $extension = explode(".", $_FILES['partner-image']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['partner-image']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['partner-image']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['partner-image']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['partner-image']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/painel/partners/".$filename;
            $newPartner = new Partner($name, $url, $link, $event);
            $oldPartner = new Partner($oldName, null, null, $event);
            $success = $partnerController->updatePartner($newPartner, $oldPartner, $conn);
            
            $_SESSION['message'] = 'Parceiro atualizado com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../event/?event='.$event);
}
?>
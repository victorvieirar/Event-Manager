<?php

include_once '../config/control.php';
include_once '../config/database.php';

session_start();
if(isset($_POST['event-location-btn'])) {  
    $database = new Database();
    $conn = $database->getConn();

    $location = isset($_POST['event-location']) ? $_POST['event-location'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    $_UP['folder'] = '/../../location/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['local-file']['error'] > 0 && $_FILES['local-file']['error'] < 4) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['local-file']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    $extension = explode(".", $_FILES['local-file']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['local-file']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['local-file']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['local-file']['tmp_name']) || $_FILES['local-file']['error'] == 4) {
        $success = move_uploaded_file($_FILES['local-file']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success || $_FILES['local-file']['error'] == 4) {
            $link = "/painel/location/".$filename;
            if($_FILES['local-file']['error'] == 4) {
                $link = ' ';
            }
            
            if(empty($location) && !empty($event)) {
                $sql = "delete from location where event_id = :event";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':event', $event);
                
                $success = $stmt->execute();

                $_SESSION['message'] = 'Localização removida com sucesso!';
            } elseif(!empty($event)) {
                $sql = "insert into location values (:event, :location, :link)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':event', $event);
                $stmt->bindValue(':location', $location);
                $stmt->bindValue(':link', $link);
                
                try { 
                    $success = $stmt->execute();
                } catch(PDOException $pdoe) {
                    $sql = "update location set location = :location , link = :link where event_id = :event";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':location', $location);
                    $stmt->bindValue(':link', $link);
                    $stmt->bindValue(':event', $event);

                    $success = $stmt->execute();
                }
                
                $_SESSION['message'] = 'Localização atualizada com sucesso!';
            } else {
                $_SESSION['message'] = 'Desculpe-nos, mas houve um erro com os dados. Tente novamente mais tarde.';
            }

        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }

    header('location: ../../event/?event='.$event);

} elseif(isset($_POST['event-location-remove-btn'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event = isset($_POST['event']) ? $_POST['event'] : '';

    $sql = "delete from location where event_id = :event";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':event', $event);
    
    $success = $stmt->execute();

    $_SESSION['message'] = 'Localização removida com sucesso!';
    header('location: ../../event/?event='.$event);
}

?>
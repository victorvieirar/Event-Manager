<?php
session_start();

include_once '../config/database.php';
include_once '../model/postgraduation.php';
include_once '../controller/postgraduation.php';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['postgraduation-name']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $postGraduationController = new PostGraduationController();

    $name = isset($_POST['postgraduation-name']) ? $_POST['postgraduation-name'] : '';
    $description = isset($_POST['postgraduation-description']) ? $_POST['postgraduation-description'] : '';
    $link = isset($_POST['postgraduation-link']) ? $_POST['postgraduation-link'] : '';

    $_UP['folder'] = '/../../postgraduations/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if(!empty($_FILES['postgraduation-image']['tmp_name'])) {   
        if ($_FILES['postgraduation-image']['error'] > 0) {
            $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['postgraduation-image']['error']];
            $_SESSION['message'] = $message;
            header('location: ../../');
            exit;
        }
        $extension = explode(".", $_FILES['postgraduation-image']['name']);
        $extension = strtolower(end($extension));
        
        if ($_UP['size'] < $_FILES['postgraduation-image']['size']) {
            $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['postgraduation-image']['size']/1024*1024)." MB";
            $_SESSION['message'] = $message;
            header('location: ../../');
            exit;
        }
        
        $filename = md5(time()).".".$extension;
        
        if(is_uploaded_file($_FILES['postgraduation-image']['tmp_name'])) {
            $success = move_uploaded_file($_FILES['postgraduation-image']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
            if($success) {
                $featured_image = "/painel/postgraduations/".$filename;
                $postGraduation = new PostGraduation(null, $name, $description, $link, $featured_image);
                
                if(isset($_POST['add-postgraduation-button'])) {
                    $success = $postGraduationController->savePostGraduation($postGraduation, $conn);
                    $_SESSION['message'] = 'Curso de pós-graduação inserido com sucesso!';
                } elseif(isset($_POST['update-postgraduation-button'])) {
                    $id = isset($_POST['postgraduation-id']) ? $_POST['postgraduation-id'] : '';
                    
                    if(empty($id)) {
                        $message = "Desculpe, mas nem todas as informações foram enviadas. Confira os dados e tente novamente.";
                        $_SESSION['message'] = $message;
                        header('location: ../../');
                        exit;
                    }
                    
                    $postGraduation->setId($id);
                    $success = $postGraduationController->updatePostGraduation($postGraduation, $conn);
                    $_SESSION['message'] = 'Curso de pós-graduação atualizado com sucesso!';
                }
                
            } else {
                $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
            }
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
        }
    } elseif(isset($_POST['update-postgraduation-button'])) {
        $id = isset($_POST['postgraduation-id']) ? $_POST['postgraduation-id'] : '';
        $postGraduation = new PostGraduation($id, null, null, null, null);
        $postGraduation = $postGraduationController->getPostGraduation($postGraduation, $conn);
                    
        if(empty($id)) {
            $message = "Desculpe, mas nem todas as informações foram enviadas. Confira os dados e tente novamente.";
            $_SESSION['message'] = $message;
            header('location: ../../');
            exit;
        }
        
        $postGraduation->setName($name);
        $postGraduation->setDescription($description);
        $postGraduation->setLink($link);
        $success = $postGraduationController->updatePostGraduation($postGraduation, $conn);
        $_SESSION['message'] = 'Curso de pós-graduação atualizado com sucesso!';
    }
        
    header('location: ../../');

}
?>
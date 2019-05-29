<?php
session_start();

include_once '../config/database.php';
include_once '../model/submission.php';
include_once '../controller/submission.php';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['submission-button']) && isset($_SESSION['user'])) {
    $database = new Database();
    $conn = $database->getConn();

    $submissionController = new SubmissionController();

    $title = isset($_POST['submission-title']) ? $_POST['submission-title'] : "";
    $description = isset($_POST['submission-description']) ? $_POST['submission-description'] : "";
    $keywords = isset($_POST['submission-keywords']) ? $_POST['submission-keywords'] : "";
    $authors = isset($_POST['author']) ? $_POST['author'] : "";
    $type = isset($_POST['submission-type']) ? $_POST['submission-type'] : "";
    $event_id = isset($_POST['event-id']) ? $_POST['event-id'] : "";
    $user_cpf = isset($_POST['user-cpf']) ? $_POST['user-cpf'] : "";

    if(empty($title) || empty($description) || empty($keywords)|| empty($authors)|| empty($type)|| empty($event_id) || empty($user_cpf)) {
        $_SESSION['message'] = "Não foi possível submeter o trabalho. Dados insuficientes. Preencha todos os dados necessários e tente novamente.";
        header('location: ../../../usuario/event/?event='.$event_id);
    }
    
    $_UP['folder'] = '/../../../usuario/submissions/';
    $_UP['size'] = 1024 * 1024 * 50; // 50MB
    
    $_UP['error'][0] = 'Não houve erro';
    $_UP['error'][1] = 'O arquivo no upload é maior do que o limite permitido';
    $_UP['error'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['error'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['error'][4] = 'Não foi feito o upload do arquivo';

    $message = null;

    if ($_FILES['submission-file']['error'] > 0) {
        $message = "Desculpe, aconteceu um erro com o seu envio. Erro: ".$_UP['error'][$_FILES['submission-file']['error']];
        $_SESSION['message'] = $message;
        header('location: ../../../usuario/event/?event='.$event_id);
        exit;
    }
    $extension = explode(".", $_FILES['submission-file']['name']);
    $extension = strtolower(end($extension));
    
    if ($_UP['size'] < $_FILES['submission-file']['size']) {
        $message = "Envie o arquivo com, no máximo, 50 MB. O arquivo enviado possuía: ".($_FILES['submission-file']['size']/1024*1024)." MB";
        $_SESSION['message'] = $message;
        header('location: ../../../usuario/event/?event='.$event_id);
        exit;
    }
    
    $filename = md5(time()).".".$extension;
    
    if(is_uploaded_file($_FILES['submission-file']['tmp_name'])) {
        $success = move_uploaded_file($_FILES['submission-file']['tmp_name'], dirname(__FILE__) . $_UP['folder'] . $filename);
        if($success) {
            $link = "/usuario/submissions/".$filename;

            $authorsCocat = "";
            foreach($authors as $author) {
                $authorsCocat .= $author.",";
            }
            $authors = rtrim($authorsCocat, ', ');

            $submission = new Submission($user_cpf, null, $event_id, $title, $description, $keywords, $authors, $type, $link, 0);
            $success = $submissionController->saveSubmission($submission, $conn);
            
            $_SESSION['message'] = 'Trabalho submetido com sucesso';
        } else {
            $_SESSION['message'] = 'Desculpe-nos, mas houve um erro desconhecido com seu upload. Tente novamente mais tarde.';
        }
    } else {
        $_SESSION['message'] = 'Desculpe-nos, mas não conseguimos salvar o seu arquivo no armazenamento interno do servidor.';
    }
    
    header('location: ../../../usuario/event/?event='.$event_id);

}
?>
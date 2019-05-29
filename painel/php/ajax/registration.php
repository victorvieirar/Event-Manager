<?php

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/event.php';
include_once '../controller/user.php';
include_once '../controller/event.php';
include_once '../controller/subscribes.php';

session_start();
if(isset($_POST['registration'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $event = isset($_POST['event']) ? $_POST['event'] : '';
    
    $user = new User($_SESSION['user']->getCpf(), null, null, null, null);
    $event = new Event($event, null, null, null, null, null, null);

    if(empty($user) || empty($event)) {
        echo json_encode(array('success' => false, 'message' => 'Usuário ou evento inválido.'));
        exit;
    }

    $subscribesController = new SubscribesController();
    $success = $subscribesController->saveSubscription($user, $event, $conn);
    
    if($success) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Falha ao registrar inscrição, tente novamente mais tarde.'));
    }
}

?>
<?php

require_once '../config/database.php';
require_once '../model/user.php';
require_once '../model/event.php';
require_once '../controller/subscribes.php';
require_once '../config/control.php';

if(isset($_POST['delete'])) {
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($cpf) || empty($event)) {
        echo json_encode(array('success' => false, 'message' => 'Dados importantes estão faltando. Tente novamente mais tarde.'));
        exit;
    }

    $database = new Database();
    $conn = $database->getConn();

    $user = new User($cpf, null, null, null, null, null, null, null);
    $event = new Event($event, null, null, null, null, null, null, null, null);

    $subscribesController = new SubscribesController();
    $success = $subscribesController->deleteSubscription($user, $event, $conn);

    echo json_encode(array('success' => $success));
}

?>
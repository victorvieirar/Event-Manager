<?php

require_once '../config/control.php';
require_once '../config/database.php';
require_once '../model/eventConfig.php';
require_once '../controller/eventConfig.php';

session_start();
if(!isset($_SESSION['admin'])) {
    session_destroy();
    header('location: ../../');
}

if(isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();

    $travel = isset($_POST['travel']) ? $_POST['travel'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($event_id)) {
        echo json_encode(array('success'=>false, 'message'=>'Alguns dados necessários estão ausentes. Tente novamente e verifique se preencheu os dados corretamente'));
        exit;
    }

    $eventConfigController = new EventConfigController();

    $eventConfig = new EventConfig($event_id, $travel);
    $success = $eventConfigController->updateEventConfig($eventConfig, $conn);

    echo json_encode(array('success'=>$success));
} 

?>

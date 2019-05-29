<?php

include_once '../config/database.php';
include_once '../model/speaker.php';
include_once '../controller/speaker.php';


if(isset($_POST['delete'])) {
    $database = new Database();
    $conn = $database->getConn();

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($name) || empty($event)) {
        $message = "Dados insuficientes. Tente novamente mais tarde.";
        echo json_encode(array('message' => $message));
        exit;
    }

    $speaker = new Speaker($event, $name, null, null, null);
    $speakerController = new SpeakerController();
    $success = $speakerController->deleteSpeaker($speaker, $conn);

    echo json_encode(array('success'=>$success));
}

?>
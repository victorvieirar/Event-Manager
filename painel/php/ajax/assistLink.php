<?php

include_once '../config/control.php';
include_once '../config/database.php';
include_once '../model/assistLink.php';
include_once '../controller/assistLink.php';

if(isset($_POST['update'])) {  
    $database = new Database();
    $conn = $database->getConn();

    $link = isset($_POST['link']) ? $_POST['link'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    $assistLink = new AssistLink($link, $event);
    $assistLinkController = new AssistLinkController(); 
    try {
        $success = $assistLinkController->saveAssistLink($assistLink, $conn);
    } catch(Exception $e) {
        $success = $assistLinkController->updateAssistLink($assistLink, $conn);
    }

    echo json_encode(array('success'=>$success));
}

?>
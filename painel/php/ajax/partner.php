<?php

include_once '../config/control.php';
include_once '../config/database.php';
include_once '../model/partner.php';
include_once '../controller/partner.php';

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

    $partner = new Partner($name, null, null, $event);
    $partnerController = new PartnerController();
    $success = $partnerController->deletePartner($partner, $conn);

    echo json_encode(array('success'=>$success));
}

?>
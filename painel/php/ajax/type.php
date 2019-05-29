<?php

include_once '../config/database.php';
include_once '../model/type.php';
include_once '../controller/type.php';

if(isset($_POST['add'])) {
    $database = new Database();
    $conn = $database->getConn();

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($name) || empty($event)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $typeController = new TypeController();
    $type = new Type(null, $name, $event);
    $success = $typeController->saveType($type, $conn);

    $type = $typeController->getTypeByName($type, $conn);

    echo json_encode(array("success" => true, "type"=>$type));
    exit;
} elseif(isset($_POST['delete'])) {
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $typeController = new TypeController();
    $type = new Type($id, null, null);
    $success = $typeController->deleteType($type, $conn);
    
    echo json_encode(array("success" => true));
    exit;
}

?>
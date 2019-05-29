<?php

include_once '../config/database.php';
include_once '../model/city.php';
include_once '../controller/city.php';

session_start();
if(isset($_POST['byState']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $state = isset($_POST['state']) ? $_POST['state'] : '';

    if(empty($state)) {
        echo json_encode(array('success' => false, 'message' => 'Código do estado não encontrado'));
        exit;
    }

    $cityController = new CityController();
    $cities = $cityController->getCitiesByState($conn, $state);
    
    echo json_encode(array('success' => true, 'cities' => $cities));
}

?>
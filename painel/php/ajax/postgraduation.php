<?php

include '../config/database.php';
include '../model/postgraduation.php';
include '../controller/postgraduation.php';

if(isset($_POST['delete'])) {
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array('success' => false, 'message' => 'Falha ao tentar excluir pós-graduação. Tente novamente mais tarde'));
    }

    $postGraduation = new PostGraduation($id, null, null, null, null);
    $postGraduationController = new PostGraduationController();
    $success = $postGraduationController->deletePostGraduation($postGraduation, $conn);

    echo json_encode(array('success' => $success));
} elseif(isset($_POST['search'])) {
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array('success' => false, 'message' => 'Falha ao tentar procurar pós-graduação. Tente novamente mais tarde'));
    }

    $postGraduation = new PostGraduation($id, null, null, null, null);
    $postGraduationController = new PostGraduationController();
    $postGraduation = $postGraduationController->getPostGraduation($postGraduation, $conn);

    echo json_encode(array('success' => true, 'postGraduation' => $postGraduation));
}

?>
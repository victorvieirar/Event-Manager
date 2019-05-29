<?php

include_once '../config/database.php';
include_once '../model/news.php';
include_once '../controller/news.php';

if(isset($_POST['delete'])) {
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $newsController = new NewsController();
    $news = new News($id, null, null, null, null);
    $news = $newsController->deleteNews($news, $conn);

    echo json_encode(array("success" => true, "news" => $news));
    exit;
} elseif(isset($_POST['getByEvent'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : '';

    if($empty($event_id)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $newsController = new NewsController();
    $news = new News(null, null, null, null, $event_id);
    $news = $newsController->getNewsByEvent($news, $conn);

    echo json_encode(array("success" => true, "news" => $news));
    exit;
} elseif(isset($_POST['get'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array("success" => false, "message" => "Dados insuficientes. Tente novamente mais tarde"));
        exit;
    }

    $newsController = new NewsController();
    $news = new News($id, null, null, null, null);
    $news = $newsController->getNews($news, $conn);

    echo json_encode(array("success" => true, "notice" => $news));
    exit;
}

?>
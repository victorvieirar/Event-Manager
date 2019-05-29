<?php

require_once '../config/database.php';
require_once '../model/city.php';
require_once '../model/state.php';
require_once '../model/event.php';
require_once '../model/eventConfig.php';
require_once '../controller/eventConfig.php';
require_once '../controller/coupon.php';
require_once '../controller/ticket.php';
require_once '../controller/transaction.php';
require_once '../controller/speaker.php';
require_once '../controller/submission.php';
require_once '../controller/subscribes.php';
require_once '../controller/type.php';
require_once '../controller/news.php';
require_once '../controller/schedule.php';
require_once '../controller/event.php';
require_once '../controller/partner.php';

session_start();
if(!isset($_SESSION['admin'])) {
    session_destroy();
    header('location: ../../');
}

if(isset($_POST['create'])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';

    if(empty($name) || empty($date) || empty($endDate) || empty($city)) {
        echo json_encode(array('success' => false, 'message' => 'Dados não preenchidos corretamente'));
        exit;
    }
    
    try {
        $database = new Database();
        $conn = $database->getConn();
    } catch(Exception $e) {
        echo json_encode(array('success' => false, 'message' => $e->getMessage()));
        exit;
    }

    $city = new City($city, null, null);
    
    $event = new Event(null, $name, $date, $endDate, $city, null, null, null, $date);
    
    $eventController = new EventController();
    try {
        $success = $eventController->saveEvent($event, $conn);
    } catch(Exception $e) {
        echo $e->getMessage();
    }

    $event = $eventController->getEventByName($event, $conn);
    $eventConfigController = new EventConfigController();
    $eventConfig = new EventConfig($event->getId(), null);
    $success = $success && $eventConfigController->saveEventConfig($eventConfig, $conn);

    echo json_encode(array('success'=>$success));
} elseif(isset($_POST['searchAll'])) {
    $database = new Database();
    $conn = $database->getConn();

    $eventController = new EventController();
    $events = $eventController->getAll($conn);

    if(empty($events)) {
        echo json_encode(array('success'=>false, 'message'=>'Nenhum evento encontrado'));
    } else {
        echo json_encode(array('success'=>true, 'events'=>$events));
    }
} elseif(isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $allow = isset($_POST['allowSubmissions']) ? $_POST['allowSubmissions'] : '';
    $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : '';
    $subscription = isset($_POST['subscription']) ? $_POST['subscription'] : '';

    $deadline = date("Y-m-d", strtotime($deadline))." 23:59:59";

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($name) || empty($date) || empty($endDate)) {
        echo json_encode(array('success'=>false, 'message'=>'Alguns dados necessários estão ausentes. Tente novamente e verifique se preencheu os dados corretamente'));
        exit;
    }

    if(!empty($allow) && empty($deadline)) {
        echo json_encode(array('success'=>false, 'message'=>'Por favor, se deseja habilitar a submissão de trabalhos, insira a data limite para a submissão'));
        exit;
    }

    $eventController = new EventController();

    $event = new Event($event_id, null, null, null, null, null, null, null, null);
    $event = $eventController->getEvent($conn, $event);
    $event->setName($name);
    $event->setDate($date);
    $event->setEndDate($endDate);
    $event->setDescription($description);
    $event->setDeadline($deadline);
    $event->setAllow_submissions($allow);
    $event->setSubscription_limit($subscription);
    $success = $eventController->updateEvent($event, $conn);

    echo json_encode(array('success'=>$success));
} elseif(isset($_POST['delete'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    
    if(empty($event_id)) {
        echo json_encode(array('success'=>false, 'message'=>'Falha ao deletar evento. Tente novamente mais tarde'));
        exit;
    }


    $eventController = new EventController();    
    $event = new Event($event_id, null, null, null, null, null, null, null, null);

    $couponController = new CouponController();
    $transactionController = new TransactionController();
    $ticketController = new TicketController();
    $speakerController = new SpeakerController();
    $submissionsController = new SubmissionController();
    $subscribesController = new SubscribesController();
    $typeController = new TypeController();
    $newsController = new NewsController();
    $scheduleController = new ScheduleController();
    $partnerController = new PartnerController();
    $eventConfigController = new EventConfigController();

    $couponController->deleteCouponsByEvent($event, $conn);
    $transactionController->deleteTransactionsByEvent($event, $conn);
    $ticketController->deleteTicketsByEvent($event, $conn);
    $speakerController->deleteSpeakersByEvent($event, $conn);
    $submissionsController->deleteSubmissionsByEvent($event, $conn);
    $subscribesController->deleteSubscriptionsByEvent($event, $conn);
    $typeController->deleteTypesByEvent($event, $conn);
    $newsController->deleteNewsByEvent($event, $conn);
    $scheduleController->deleteSchedulesByEvent($event, $conn);
    $partnerController->deletePartnersByEvent($event, $conn);
    $eventConfigController->deleteEventConfig(new EventConfig($event->getId(), null), $conn);

    $success = $eventController->deleteEvent($event, $conn);
    
    echo json_encode(array('success'=>$success));
}

?>

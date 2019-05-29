<?php

include_once '../config/database.php';
include_once '../model/schedule.php';
include_once '../controller/schedule.php';
include_once '../config/control.php';

if(isset($_POST['add']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';
    $finalTime = isset($_POST['finalTime']) ? $_POST['finalTime'] : '';

    if(empty($event_id) || empty($title) || empty($time) || empty($finalTime)) {
        echo json_encode(array('success' => false, 'message' => 'Dados incompletos. Preencha os dados e tente novamete.'));
        exit;
    }

    $schedule = new Schedule($event_id, $title, $time,$finalTime);
    
    $scheduleController = new ScheduleController();
    $scheduleController->saveSchedule($schedule, $conn);

    echo json_encode(array('success' => true));
} elseif(isset($_POST['delete']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';

    if(empty($event_id) || empty($title) || empty($time)) {
        echo json_encode(array('success' => false, 'message' => 'Dados incompletos. Tente novamete mais tarde.'));
        exit;
    }

    $schedule = new Schedule($event_id, $title, $time, null);
    
    $scheduleController = new ScheduleController();
    $scheduleController->deleteSchedule($schedule, $conn);

    echo json_encode(array('success'=>true));
} elseif(isset($_POST['search'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';

    if(empty($event_id) || empty($title) || empty($time)) {
        echo json_encode(array('success' => false, 'message' => 'Dados incompletos. Tente novamete mais tarde.'));
        exit;
    }

    $schedule = new Schedule($event_id, $title, $time, null);
    
    $scheduleController = new ScheduleController();
    $schedule = $scheduleController->getSchedule($schedule, $conn);

    echo json_encode(array('success'=>true, 'time'=>$schedule->getTime(), 'title'=>$schedule->getTitle(), 'finalTime' => $schedule->getFinalTime(), 'date' => $schedule->getDate()));
} elseif(isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';

    $dateUpdate = isset($_POST['dateUpdate']) ? $_POST['dateUpdate'] : '';
    $titleUpdate = isset($_POST['titleUpdate']) ? $_POST['titleUpdate'] : '';
    $timeUpdate = isset($_POST['timeUpdate']) ? $_POST['timeUpdate'] : '';
    $finalTimeUpdate = isset($_POST['finalTimeUpdate']) ? $_POST['finalTimeUpdate'] : '';
    
    $timeUpdate = $dateUpdate." ".$timeUpdate;
    $finalTimeUpdate = $dateUpdate." ".$finalTimeUpdate;

    if(empty($event_id) || empty($title) || empty($time)) {
        echo json_encode(array('success' => false, 'message' => 'Dados incompletos. Tente novamete mais tarde.'));
        exit;
    }

    $schedule = new Schedule($event_id, $title, $time, null);
    $scheduleUpdate = new Schedule($event_id, $titleUpdate, $timeUpdate, $finalTimeUpdate);

    $scheduleController = new ScheduleController();
    $success = $scheduleController->updateSchedule($scheduleUpdate, $schedule, $conn);

    echo json_encode(array('success'=>$success));
} elseif(isset($_POST['updateAsGroup'])) { 
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    $dateUpdate = isset($_POST['dateUpdate']) ? $_POST['dateUpdate'] : '';
    $date = isset($_POST['oldDate']) ? $_POST['oldDate'] : '';

    if(empty($date) || empty($dateUpdate)) {
        echo json_encode(array('success' => false, 'message' => 'Dados incompletos. Tente novamete mais tarde.'));
        exit;
    }

    $schedule = new Schedule($event_id, null, $date." 00:00", null);
    
    $scheduleController = new ScheduleController();
    $schedules = $scheduleController->getSchedulesByDay($schedule, $conn);

    $success = TRUE;
    foreach($schedules as $daySchedule) {
        $time = $daySchedule->getTime();
        $timeFinal = $daySchedule->getFinalTime();

        $newSchedule = new Schedule($daySchedule->getEvent_id(), $daySchedule->getTitle(), $dateUpdate.' '.$time, $dateUpdate.' '.$timeFinal);

        $success = $success && $scheduleController->updateSchedule($newSchedule, $daySchedule, $conn);
    }
    
    echo json_encode(array('success'=>$success));
}

?>
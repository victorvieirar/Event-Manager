<?php

include_once '../config/database.php';
include_once '../model/ticket.php';
include_once '../controller/ticket.php';

session_start();
if(isset($_POST['ticket'])) {
    $database = new Database();
    $conn = $database->getConn();

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['value']) ? $_POST['value'] : '';
    $initialDate = isset($_POST['initialDate']) ? $_POST['initialDate'] : '';
    $finalDate = isset($_POST['finalDate']) ? $_POST['finalDate'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($name) || empty($description) || empty($price) || empty($initialDate) || empty($finalDate) || empty($event_id)) {
        echo json_encode(array('success' => false, 'message' => 'Dados não preenchidos corretamente'));
        exit;
    }

    $ticketController = new TicketController();
    $ticket = new Ticket(null, $name, $description, $price, $initialDate, $finalDate, $event_id);
    $success = $ticketController->saveTicket($ticket, $conn);

    if($success) {
        $ticket = $ticketController->getTicketByName($ticket, $conn);
        echo json_encode(array('success' => true, 'ticket' => $ticket));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Não foi possível criar a entrada. Tente novamente mais tarde.'));
    }
    
} elseif(isset($_POST['delete'])) { 
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if(empty($id)) {
        echo json_encode(array('success' => false, 'message' => 'Erro ao deletar a entrada. Recarregue a página e tente novamente'));
        exit;
    }

    $ticketController = new TicketController();
    $ticket = new Ticket($id, null, null, null, null, null, null);
    $success = $ticketController->deleteTicket($ticket, $conn);

    echo json_encode(array('success' => $success));
} elseif(isset($_POST['update'])) { 
    $database = new Database();
    $conn = $database->getConn();

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $initialDate = isset($_POST['initialDate']) ? $_POST['initialDate'] : '';
    $finalDate = isset($_POST['finalDate']) ? $_POST['finalDate'] : '';

    if(empty($name) || empty($description) || empty($initialDate) || empty($finalDate)) {
        echo json_encode(array('success' => false, 'message' => 'Erro ao atualizar a entrada. Recarregue a página e tente novamente'));
        exit;
    }

    $ticketController = new TicketController();
    $ticket = new Ticket($id, $name, $description, $price, $initialDate, $finalDate, null);
    $success = $ticketController->updateTicket($ticket, $conn);

    echo json_encode(array('success' => $success));
}

?>
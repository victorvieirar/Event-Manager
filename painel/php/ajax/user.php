<?php

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../model/transaction.php';
include_once '../controller/user.php';
include_once '../controller/transaction.php';

session_start();
if(isset($_POST['search'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    
    if(empty($cpf)) {
        echo json_encode(array('success' => false, 'message' => 'Usuário não encontrado.'));
        exit;
    }

    $user = new User($cpf, null, null, null, null, null, null, null);

    $userController = new UserController();
    $user = $userController->getUserInformation($user, $conn);
    
    $transactionController = new TransactionController();
    $transactions = new Transaction(null, $user->getCpf(), null, null, null, null, null, null, null);
    $transactions = $transactionController->getTransactionsByUser($transactions, $conn);
    
    $arrTransactions = array();

    foreach($transactions as $key => $transaction) {
        if($event_id == $transaction->getEvent_id()) {
            array_push($arrTransactions, $transaction);
        }
    }
    
    if($user) {
        echo json_encode(array('success' => true, 'user' => $user, 'transactions' => $arrTransactions));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Falha ao buscar usuário. Tente novamente mais tarde.'));
    }
} elseif(isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $oldCpf = isset($_POST['oldCpf']) ? $_POST['oldCpf'] : $cpf;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $formation = isset($_POST['formation']) ? $_POST['formation'] : '';
    
    if(empty($cpf) || empty($name) || empty($email) || empty($phone) || empty($password) || empty($estado) || empty($course) || empty($formation)) {
        echo json_encode(array('success' => false, 'message' => 'Dados insuficientes. Insira todos os dados e tente novamente.'));
        exit;
    }

    $user = new User($cpf, $name, $password, $email, $phone, $estado, $course, $formation);

    $userController = new UserController();
    $success = $userController->updateUser($user, $conn, $oldCpf);
    
    echo json_encode(array('success' => $success));
}

?>

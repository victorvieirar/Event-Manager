<?php 

require_once '../config/database.php';
require_once '../model/transaction.php';
require_once '../controller/transaction.php';

if(isset($_GET['transactionId'])) {
    $database = new Database();
    $conn = $database->getConn();

    $transactionController = new TransactionController();
    $transaction = $_GET['transactionId'];
    $transaction = new Transaction($transaction, null, null, null, null, null, null, null, null);
    $transaction = $transactionController->getTransaction($transaction, $conn);

    $transaction->setStatus(1);
    $transactionController->updateTransaction($transaction, $conn);

    header('location: ../../usuario');
}

?>

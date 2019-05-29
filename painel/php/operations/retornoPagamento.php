<?php

require_once '../config/database.php';
require_once '../model/transaction.php';
require_once '../model/event.php';
require_once '../model/user.php';
require_once '../controller/transaction.php';
require_once '../controller/subscribes.php';
require_once '../../../vendor/autoload.php';

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("IDS")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("IDS")->setRelease("1.0.0");

\PagSeguro\Configuration\Configure::setEnvironment('production');//production or sandbox
\PagSeguro\Configuration\Configure::setAccountCredentials(
    'nekocampelo@gmail.com',
    '0A22DFA372274C65831F549565E1E559'
);
\PagSeguro\Configuration\Configure::setCharset('UTF-8');// UTF-8 or ISO-8859-1
\PagSeguro\Configuration\Configure::setLog(true, '../../../logfile.log');

try {
    $sessionId = \PagSeguro\Services\Session::create(
        \PagSeguro\Configuration\Configure::getAccountCredentials()
    );
    
    $credential = \PagSeguro\Configuration\Configure::getAccountCredentials();
} catch (Exception $e) {
    die($e->getMessage());
}

if(isset($_GET['transactionId'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $transactionController = new TransactionController();
    $transaction = $_GET['transactionId'];
    $transaction = new Transaction($transaction, null, null, null, null, null, null, null, null);
    $transaction = $transactionController->getTransaction($transaction, $conn);
    
    if(!$transaction) {
        echo json_encode(array('success' => false));
    }
}

if(isset($_POST['notificationCode']) && $_POST['notificationType']=='transaction') {
    
    $time = strtotime($transaction->getTransactionDate());
    
    $initialDate = date("Y-m-d", $time)."T".date("H:i", $time);
    $options = [
    'initial_date' => $initialDate,
    ];
    
    try {
        $response = \PagSeguro\Services\Transactions\Search\Reference::search(
            $credential,
            $reference,
            $options
        );
    } catch (Exception $e) {
        die($e->getMessage());
    }
    
    $psTransaction = $response->getTransactions()[0];
    
    $psCode = $psTransaction->getCode();
    $psStatus = $psTransaction->getStatus();
    
    $transaction->setPagseguro_code($psCode);
    $transaction->setStatus($psStatus);
    
    if($psStatus == 3) {
        $event = new Event($transaction->getEvent_id(), null, null, null, null, null, null, null);
        $user = new User($transaction->getUser_cpf(), null, null, null, null, null, null, null);
        
        $subscribesController = new SubscribesController();
        $subscribesController->updateSubscription($user, $event, 1, $conn);
        #transacao paga
        #mudar o campo access da tabela participant para 1
    } elseif($psStatus == 6 || $psStatus == 7 || $psStatus == 8) {
        $event = new Event($transaction->getEvent_id(), null, null, null, null, null, null, null);
        $user = new User($transaction->getUser_cpf(), null, null, null, null, null, null, null);
        
        $subscribesController = new SubscribesController();
        $subscribesController->deleteSubscription($user, $event, $conn);
        #dinheiro devolvido
        #remover o registro do user da tabela participant
    }
    
    
    
    $transactionController->updateTransaction($transaction, $conn);
    
}

?>
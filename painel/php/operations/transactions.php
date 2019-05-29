<?php 

require_once "../../../vendor/autoload.php";
require_once "../config/database.php";
require_once "../model/transaction.php";
require_once "../controller/transaction.php";

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("IDS")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("IDS")->setRelease("1.0.0");

\PagSeguro\Configuration\Configure::setEnvironment('sandbox');//production or sandbox
\PagSeguro\Configuration\Configure::setAccountCredentials(
    'atworkad@gmail.com',
    '4330ACF66D4048B4A0EB2CD9F3AEFB8D'
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
    $reference = $transaction;

    $transaction = new Transaction($transaction, null, null, null, null, null, null, null);
    $transaction = $transactionController->getTransaction($transaction, $conn);

    $time = strtotime($transaction->getTransactionDate());

    /**
     * Formato: Y-m-dTH:i
     *
     * @var string $initialDate
     */
    $initialDate = date('Y-m-d', $time).'T'.date('H:i', $time);
    /**
     * Formato: Y-m-dTH:i
     *
     * @var string $finalDate
     */
    //$finalDate = date('Y-m-d', $time).'T23:59';

    /** @var integer $page */
    $page = 1;

    /** @var integer $maxPerPage */
    $maxPerPage = 1;

    $options = [
        'initial_date' => $initialDate,
    ];

    try {
        $response = \PagSeguro\Services\Transactions\Search\Reference::search(
            /** @var \PagSeguro\Domains\AccountCredentials | \PagSeguro\Domains\ApplicationCredentials $credential */
            $credential,
            /**
             * Código de referência da transação. Informa o código que foi usado para fazer referência ao pagamento.
             * Este código foi fornecido no momento do pagamento e é útil para vincular as transações do PagSeguro às vendas
             * registradas no seu sistema.
             *
             * Presença: Opcional.
             * Tipo: Texto.
             * Formato: Livre, com o limite de 200 caracteres.
             *
             * @var string $reference
             */
            $reference,
            $options
        );
    } catch (Exception $e) {
        die($e->getMessage());
    }
    
    $updatedTransaction = $transaction;

    $transaction = $response->getTransactions()[0];
    $id = $transaction->getCode();
    $status = $transaction->getStatus();

    $updatedTransaction->setStatus($status);

    $transactionController->updateTransaction($id, $updatedTransaction, $conn);
}

?>
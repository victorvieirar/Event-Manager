<?php

require_once "../config/database.php";
require_once "../model/user.php";
require_once "../model/coupon.php";
require_once "../model/ticket.php";
require_once "../model/transaction.php";
require_once "../model/event.php";
require_once "../controller/subscribes.php";
require_once "../controller/coupon.php";
require_once "../controller/ticket.php";
require_once "../controller/transaction.php";
require_once "../../../vendor/autoload.php";

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

session_start();
if(isset($_POST['buy-button'])) {
    $database = new Database();
    $conn = $database->getConn();

    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    $couponController = new CouponController();
    $coupon = isset($_POST['coupon']) ? $_POST['coupon'] : '';
    $coupon = new Coupon($coupon, null, $event_id);
    $coupon = $couponController->getCoupon($coupon, $conn);
    
    $ticketController = new TicketController();
    $ticket = isset($_POST['ticket']) ? $_POST['ticket'] : '';
    $ticket = new Ticket($ticket, null, null, null, null, null, null);
    $ticket = $ticketController->getTicket($ticket, $conn);
    
    $senderName = $_SESSION['user']->getName();
    $senderEmail = $_SESSION['user']->getEmail();

    $senderName = preg_replace('/\d/', '', $senderName);
    $senderName = preg_replace('/[\n\t\r]', ' ', $senderName);
    $senderName = preg_replace('/\s(?=\s)/', '', $senderName);
    $senderName = trim($senderName);
    
    $currency = 'BRL';
    $extraAmount = 0.00;
    if(!empty($coupon)) {
        $extraAmount = -1 * (($coupon->getDiscount()/100) * $ticket->getPrice());
    }
    $reference = "REF".$ticket->getId()."_".$_SESSION['user']->getCpf()."_".$event_id."_".date('Y-m-dTH:i', time());
    $redirectUrl = "http://iids.com.br/painel/php/operations/confirm-payment.php?transactionId=".$reference;
    $notificationUrl = "http://iids.com.br/painel/php/operations/notification-payment.php?transactionId=".$reference;
    
    $transactionValue = $ticket->getPrice();
    if($coupon) $transactionValue = $ticket->getPrice()-(($coupon->getDiscount()/100) * $ticket->getPrice());

    $transactionController = new TransactionController();
    $transaction = new Transaction($reference, $_SESSION['user']->getCpf(), date('Y-m-d h:i', time()), '', $transactionValue, $event_id, -1, $coupon ? $coupon->getCode() : '' , null);

    if($ticket->getPrice() == 0 || $ticket->getPrice() + $extraAmount == 0) {
        $transaction->setStatus(4);
        $transactionController->saveTransaction($transaction, $conn);
        $subscribesController = new SubscribesController();
        
        $user = new User($_SESSION['user']->getCpf(), null, null, null, null, null, null, null);
        $event = new Event($event_id, null, null, null, null, null, null, null, null);        
        try {
            $subscribesController->saveSubscription($user, $event, 1, $conn);
        } catch(Exception $e) {
            //
        }
        header('location: ../../../usuario/event/?event='.$event->getId());
    }

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

        //echo "<strong>ID de sess&atilde;o criado: </strong>{$sessionCode->getResult()}";
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $payment = new \PagSeguro\Domains\Requests\Payment();

    /**
     * Nome completo do comprador. Especifica o nome completo do comprador que está realizando o pagamento. Este campo é
     * opcional e você pode enviá-lo caso já tenha capturado os dados do comprador em seu sistema e queira evitar que ele
     * preencha esses dados novamente no PagSeguro.
     *
     * Presença: Opcional.
     * Tipo: Texto.
     * Formato: No mínimo duas sequências de caracteres, com o limite total de 50 caracteres.
     *
     * @var string $senderName
     */
    $payment->setSender()->setName($senderName);

    /**
     * E-mail do comprador. Especifica o e-mail do comprador que está realizando o pagamento. Este campo é opcional e você
     * pode enviá-lo caso já tenha capturado os dados do comprador em seu sistema e queira evitar que ele preencha esses
     * dados novamente no PagSeguro.
     *
     * Presença: Opcional.
     * Tipo: Texto.
     * Formato: um e-mail válido (p.e., usuario@site.com.br), com no máximo 60 caracteres.
     *
     * @var string $senderEmail
     */
    $payment->setSender()->setEmail($senderEmail);

    $phone = new \PagSeguro\Domains\Phone();
    $document = new \PagSeguro\Domains\Document();
    $address = new \PagSeguro\Domains\Address();
    $shippingCost = new \PagSeguro\Domains\ShippingCost();
    $shippingType = new \PagSeguro\Domains\ShippingType();

    /** @var \PagSeguro\Domains\Phone $phone */
    $payment->setSender()->setPhone()->instance($phone);

    /** @var \PagSeguro\Domains\Document $document */
    $payment->setSender()->setDocument()->instance($document);

    /** @var \PagSeguro\Domains\Address $address */
    $payment->setShipping()->setAddress()->instance($address);

    /** @var \PagSeguro\Domains\ShippingCost $shippingCost */
    $payment->setShipping()->setCost()->instance($shippingCost);

    /** @var \PagSeguro\Domains\ShippingType $shippingType */
    $payment->setShipping()->setType()->instance($shippingType);

    /**
     * Lista de itens contidos na transação. O número de itens sob este elemento corresponde ao valor de itemCount.
     *
     * @var \PagSeguro\Domains\Item $item
     * @var array $items
     */

    $item = new \PagSeguro\Domains\Item();
    $item->setId($ticket->getId());
    $item->setAmount(number_format(floatval($ticket->getPrice()), 2, '.', ''));
    $item->setDescription($ticket->getName());
    $item->setQuantity(1);

    $items = [$item];

    $payment->setItems($items);

    /**
     * Moeda utilizada. Indica a moeda na qual o pagamento será feito. No momento, a única opção disponível é BRL (Real).
     *
     * Presença: Obrigatória.
     * Tipo: Texto.
     * Formato: Case sensitive. Somente o valor BRL é aceito.
     *
     * @var string $currency
     */
    $payment->setCurrency($currency);

    /**
     * Valor extra. Especifica um valor extra que deve ser adicionado ou subtraído ao valor total do pagamento. Esse valor
     * pode representar uma taxa extra a ser cobrada no pagamento ou um desconto a ser concedido, caso o valor seja
     * negativo.
     *
     * Presença: Opcional.
     * Tipo: Número.
     * Formato: Decimal (positivo ou negativo), com duas casas decimais separadas por ponto (p.e., 1234.56 ou -1234.56),
     * maior ou igual a -9999999.00 e menor ou igual a 9999999.00. Quando negativo, este valor não pode ser maior ou igual
     * à soma dos valores dos produtos.
     *
     * @var string $extraAmount
     */
    $payment->setExtraAmount($extraAmount);

    /**
     * Código de referência. Define um código para fazer referência ao pagamento. Este código fica associado à transação
     * criada pelo pagamento e é útil para vincular as transações do PagSeguro às vendas registradas no seu sistema.
     *
     * Presença: Opcional.
     * Tipo: Texto.
     * Formato: Livre, com o limite de 200 caracteres.
     *
     * @var string $reference
     */
    $payment->setReference($reference);

    /**
     * URL de redirecionamento após o pagamento. Determina a URL para a qual o comprador será redirecionado após o final do
     * fluxo de pagamento. Este parâmetro permite que seja informado um endereço de específico para cada pagamento
     * realizado.
     *
     * Presença: Opcional.
     * Tipo: Texto.
     * Formato: Uma URL válida, com limite de 255 caracteres.
     *
     * @var string $redirectUrl
     */
    $payment->setRedirectUrl($redirectUrl);

    /**
     * URL para envio de notificações sobre o pagamento. Determina a URL para a qual o PagSeguro enviará os códigos de
     * notificação relacionados ao pagamento. Toda vez que houver uma mudança no status da transação e que demandar sua
     * atenção, uma nova notificação será enviada para este endereço.
     *
     * Presença: Opcional.
     * Tipo: Texto.
     * Formato: Uma URL válida, com limite de 255 caracteres.
     *
     * @var string $notificationUrl
     */
    $payment->setNotificationUrl($notificationUrl);

    /*
    * ???
    * Custom info
    */
    $payment->addParameter()->withParameters('itemId', $ticket->getId())->index(1);
    $payment->addParameter()->withParameters('itemDescription', $ticket->getName())->index(1);
    $payment->addParameter()->withParameters('itemQuantity', '1')->index(1);
    $payment->addParameter()->withParameters('itemAmount', number_format(floatval($ticket->getPrice()), 2, '.', ''))->index(1);

    /*
    * ???
    * Set discount by payment method
    $payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::BOLETO,
        PagSeguro\Enum\PaymentMethod\Config\Keys::DISCOUNT_PERCENT,
        10.00
    );
    */
    
    
    /*
    * ???
    * Set max installments without fee
    $payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        PagSeguro\Enum\PaymentMethod\Config\Keys::MAX_INSTALLMENTS_NO_INTEREST,
        1
    );
    */
    
    /*
    * ???
    * Set max installments
    $payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        PagSeguro\Enum\PaymentMethod\Config\Keys::MAX_INSTALLMENTS_LIMIT,
        6
    );
    */

    /*
    * ???
    * Set accepted payments methods group
    */
    $payment->acceptPaymentMethod()->groups(
        \PagSeguro\Enum\PaymentMethod\Group::EFT,
        \PagSeguro\Enum\PaymentMethod\Group::BOLETO,
        \PagSeguro\Enum\PaymentMethod\Group::DEPOSIT,
        \PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        \PagSeguro\Enum\PaymentMethod\Group::BALANCE
    );

    /*
    * ???
    * Set accepted payments methods
    $payment->acceptPaymentMethod()->name(\PagSeguro\Enum\PaymentMethod\Name::DEBITO_ITAU);
    */

    /*
    * ???
    * Exclude accepted payments methods group
    $payment->excludePaymentMethod()->group(\PagSeguro\Enum\PaymentMethod\Group::BOLETO);
    */

    /*
    * Após realizar uma chamada com sucesso, você deve direcionar o comprador para o fluxo de
    * pagamento, usando a url de pagamento retornado.
    */
    try {
        /** @var \PagSeguro\Domains\Requests\Payment $payment */
        $response = $payment->register(
            /** @var \PagSeguro\Domains\AccountCredentials | \PagSeguro\Domains\ApplicationCredentials $credential */
            $credential
        );

        $transaction->setStatus(0);
        $transactionController->saveTransaction($transaction, $conn);
        $subscribesController = new SubscribesController();
        
        $user = new User($_SESSION['user']->getCpf(), null, null, null, null, null, null, null);
        $event = new Event($event_id, null, null, null, null, null, null, null, null);        
        try {
            $subscribesController->saveSubscription($user, $event, 0, $conn);
        } catch(Exception $e) {
            //
        }
        header('location: '.$response);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}


?>

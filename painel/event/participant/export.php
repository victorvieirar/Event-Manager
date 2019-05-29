<?php
include_once "../../php/config/database.php";
include_once "../../php/model/admin.php";
include_once "../../php/model/event.php";
include_once "../../php/model/user.php";
include_once "../../php/controller/user.php";
include_once "../../php/controller/event.php";
include_once "../../php/controller/subscribes.php";

session_start();
?>
<html>
    <head>
        <title>Plait</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i">
        <link type="text/css" rel="stylesheet" href="../../../css/styles.css">
        <link rel="shortcut icon" type="image/x-icon" href="../../../favicon.ico">
        
        <style>
            #topBar {
                height: 80px;
                width: 100%;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                margin: 90px 0 0 0; 
                background-color: #0b2437;
            }

            #topBar span {
                margin: 0 15px; 
            }

            #topBar .userButton {
                margin: 0 15px;
                padding: 5px 30px;
                border-radius: 60px;
                background-color: rgb(12, 148, 218);
                transition: .1s ease-in;
            }
            
            #topBar .userButton:hover {
                color: #0b2437;
                background-color: white;
                transition: .1s ease-in;
            }
        </style>
    </head>

    <body>
        <?php
        if(!isset($_SESSION["admin"])) {
            session_destroy();
            header('location: ../../');
        } elseif(isset($_GET["cpf"])) {
            $db = new Database();
            $conn = $db->getConn();

            $event = new Event($_GET["event"], null, null, null, null, null, null, null, null);
            $eventController = new EventController();
            $event = $eventController->getEvent($conn, $event);

            $userController = new UserController();
            $participant = new User($_GET['cpf'], null, null, null, null, null, null, null);
            $participant = $userController->getUserInformation($participant, $conn);

            $subscribesController = new SubscribesController();
        ?>

        <h3 class="bold uppercase" style="padding: 30px 30px 0 30px;">Ficha do usuário</h3>

        <table id="participantInfo">
            <tbody class="regular">
                <?php
                    $subscription = $subscribesController->getParticipantAccess($participant, $event, $conn);
                    $access = $subscription['access'] == 1;

                    $statusClass = "";
                    $statusText = "";

                    if(!$access) {
                        $userTransactions = new Transaction(null, $participant->getCpf(), null, null, null, null, null, null, null);
                        $userTransactions = $transactionController->getTransactionsByUser($userTransactions, $conn);

                        if(empty($userTransactions)) {
                            $statusText = "Sem boleto";
                            $statusClass = "primary";
                        } else {
                            foreach ($userTransactions as $trans) {
                                $status = $trans->getStatus();
                                if($status == 0) {
                                    $statusText = "Sem boleto";
                                    $statusClass = "primary";
                                } elseif($status == 2 || $status == 3 || $status == 6 || $status == 1) {
                                    $statusText = "Em espera";
                                    $statusClass = "warning";
                                } elseif($status > 6) {
                                    $statusText = "Cancelado";
                                    $statusClass = "danger";
                                } else {
                                    $statusText = "Inexistente";
                                    $statusClass = "info";
                                }
                            }
                        }
                    } else {
                        $statusText = "Pago";
                        $statusClass = "success";
                    }

                    ?>
                <tr><td class="bold"><?php echo $event->getName(); ?></td></tr>
                <tr><td><?php echo $participant->getCpf(); ?></td></tr>
                <tr><td><?php echo $participant->getName(); ?></td></tr>
                <tr><td><?php echo $participant->getPassword(); ?></td></tr>
                <tr><td><?php echo $participant->getEmail(); ?></td></tr>
                <tr><td><?php echo $participant->getPhone(); ?></td></tr>
                <tr><td><?php echo $participant->getCourse(); ?></td></tr>
                <tr><td><?php echo ucfirst($participant->getFormation()); ?></td></tr>
                <tr><td><?php echo $statusText; ?></span></td></tr>
            </tbody>
        </table>

        <?php } ?>

    </body>

    <script src="jquery.btechco.excelexport.js"></script>
    <script src="jquery.base64.js"></script>

    <script>
        $(window).ready(function () {
            $("#participantInfo").btechco_excelexport({
                containerid: "participantInfo"
            , datatype: $datatype.Table
            , filename: '<?php echo $user->getName();?>'
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../../js/functions.js" type="text/javascript"></script>
    <param id="event" data="<?php echo $event->getId(); ?>">
</html>

<?php
include_once "../../painel/php/config/database.php";
include_once "../../painel/php/model/user.php";
include_once "../../painel/php/model/city.php";
include_once "../../painel/php/model/event.php";
include_once "../../painel/php/model/ticket.php";
include_once "../../painel/php/controller/ticket.php";
include_once "../../painel/php/controller/event.php";
    
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
        <link type="text/css" rel="stylesheet" href="../../css/styles.css?<?php echo filemtime("../../css/styles.css")?>">
		<link rel="shortcut icon" type="image/x-icon" href="../../favicon.ico">
    </head>

    <body>
        <div id="dialogBox" class="<?php if(isset($_SESSION['msg'])) { echo 'active'; } ?>">
            <div class="frame">
                
                <i class="fas fa-times"></i><br>
                <i class="description green fas fa-exclamation-circle"></i><br/>
                <p class="message">
                    <?php if(isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
                </p>
            </div>
        </div>

        <header class="light scroll no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" src="../../media/logo-minimal.png" alt="">
                </div>
                <div id="mobile-navbar">
                    <a class="no-link white" href="#n"><span class="fa fa-2x fa-bars"></span></a>
                </div>
            </div>
        </header>
        <?php
        if(!isset($_SESSION["user"])) {
            session_destroy();
            header('location: ../');
        } elseif(isset($_GET["event"])) {
            $event = new Event($_GET["event"], null, null, null, null, null, null, null, null);

            $user = $_SESSION['user'];

            $database = new Database();
            $conn = $database->getConn();

            $eventController = new EventController();
            $event = $eventController->getEvent($conn, $event);

            $ticketsController = new TicketController();
            $tickets = new Ticket(null, null, null, null, null, null, $event->getId());
            $tickets = $ticketsController->getAvailablesTicketsByEvent($tickets, $conn);
        ?>
            <nav id="nav-wrap">
                <ul class="regular">
                    <li><a class="nav-link regular btn-page" href="../" page="#resume-page">Voltar</a></li>
                    <br>
                    <li><a class="nav-link regular" href="logout.php">Sair <span class="fas fa-sign-out-alt"></span></a></li>
                </ul>
            </nav>

            <div class="sidebar">
                <p class="light">Olá, <br><b><?php echo explode(" ",$user->getName())[0];?></b></p>
                <nav class="nav flex-column">
                    <a class="nav-link regular btn-page" href="../" page="#resume-page">Voltar</a>
                    <br>
                    <a class="nav-link regular" href="../logout.php">Sair <span class="fas fa-sign-out-alt"></span></a>
                </nav>
            </div>

            <section id="main-menu">
                <div class="visor" class="container">
                    <div id="ticket-page" class="active page">
                        <h5 class="black green">Inscrição - <?php echo $event->getName();?></h5>
                        <div class="card-columns col-8">
                            <?php foreach ($tickets as $ticket) { ?>
                            <div class="card" data-price="<?php echo $ticket->getPrice(); ?>">
                                <div class="card-body">
                                    <h6 class="card-title regular"><?php echo $ticket->getName();?></h6>
                                    <h6 class="card-subtitle mb-2 text-muted light"><?php echo $ticket->getDescription(); ?></h6>
                                    <h1 class="black price">R$ <?php echo $ticket->getPrice(); ?></h1>
                                    <a href="#" data-ticket="<?php echo $ticket->getId(); ?>" data-event="<?php echo $ticket->getEvent_id(); ?>" class="button-ticket card-link"><i class="fas fa-shopping-cart"></i> Comprar</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="payment-page" class="page">
                        <h5 class="black green">Pagamento - <?php echo $event->getName();?></h5>
                        <div>
                            <h6 class="light green uppercase">Pagamento confirmado <i class="fas fa-check-circle"></i></h6>
                        </div>
                    </div>
                </div>
            </section>

            <div id="coupon-setter">
                <div class="form-group">
                    <input type="text" name="coupon-name" id="coupon-name" placeholder="Adicionar cupom" class="form-control">
                    <button type="button" id="add-coupon" class="btn btn-primary">Adicionar</button>
                </div>
                <small class="info"></small>
            </div>

            <div id="cart">
                <h4 class="black green"><i class="fas fa-shopping-cart"></i> Carrinho <i class="fas fa-times"></i></h4>
                <hr>
                <h5 class="black center" id="name"></h5>
                <hr>
                <h5 class="black right" id="subtotal"><small class="uppercase">subtotal</small> </h5>
                <h5 class="black right" id="discount"><small class="uppercase">desconto</small> </h5>
                <hr>
                <h5 class="black right" id="total"><small class="uppercase">total</small> </h5>
                <hr>
                <button type="button" id="buy-button" class="btn btn-success col-12">Finalizar <i class="fas fa-check"></i></button>
            </div>

        <?php
        }
        ?>
    </body>

    <param id="event" value="<?php echo $event->getId(); ?>">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../../js/functions.js" type="text/javascript"></script>
</html>

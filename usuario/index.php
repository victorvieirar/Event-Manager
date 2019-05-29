<?php
    
require_once "../painel/php/config/database.php";
require_once "../painel/php/model/user.php";
require_once "../painel/php/model/state.php";
require_once "../painel/php/model/city.php";
require_once "../painel/php/model/event.php";
require_once "../painel/php/model/transaction.php";
require_once "../painel/php/controller/transaction.php";
require_once "../painel/php/controller/user.php";
require_once "../painel/php/controller/state.php";
require_once "../painel/php/controller/event.php";
require_once "../painel/php/controller/subscribes.php";
    
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
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>

    <body>
        <header class="light scroll no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" alt="" src="../media/logo-minimal.png" onclick="window.open('../', '_self')">
                </div>
                <div id="mobile-navbar">
                    <a class="no-link white" href="#n"><span class="fa fa-2x fa-bars"></span></a>
                </div>
            </div>
        </header>

        <div id="dialogBox" class="<?php if(isset($_SESSION['msg'])) { echo 'active'; } ?>">
            <div class="frame">
                
                <i class="fas fa-times"></i><br>
                <i class="description green fas fa-exclamation-circle"></i><br/>
                <p class="message">
                    <?php if(isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
                </p>
            </div>
        </div>
        <?php
        if(!isset($_SESSION['user'])) {
            session_destroy();
        ?>
        <section id="login" class="panel">
            <div class="container">
                <h2 class="title black green">área do usuário</h2>
                <form action="../painel/php/authenticate/user.php" method="post" class="masked-form">
                    <div class="form-group col-md-12">
                        <input type="text" name="user" id="user" placeholder="CPF" class="col-md-4 offset-md-4 col-sm-12 masked-input cpfmask">
                    </div>
                    <div class="form-group col-md-12">
                        <input type="password" name="password" id="password" placeholder="Senha" class="col-md-4 offset-md-4 col-sm-12">
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" value="login" name="btn-login" id="btn-login" class="regular col-md-4 offset-md-4 col-sm-12">Entrar <span class="fa fa-angle-right"></span></button>
                    </div>
                </form>
                <a class="col-md-4 offset-md-4 col-sm-12" href="forgot">Esqueci a senha <i class="fas fa-angle-right"></i></a>
            </div>
        </section>
        <?php
        } else {
            $database = new Database();
            $conn = $database->getConn();
            
            $stateController = new StateController();
            $states = $stateController->getStates($conn);

            $eventController = new EventController();
            $events = $eventController->getAllAvailable($conn);
            
            $userController = new UserController();
            $user = new User($_SESSION['user']->getCpf(), null, null, null, null, null, null, null);
            $user = $userController->getUserInformation($user, $conn);

            $transactions = new Transaction(null, $user->getCpf(), null, null, null, null, null, null, null);
            $transactionController = new TransactionController();
            $transactions = $transactionController->getTransactionsByUser($transactions, $conn);
            
            $subscribesController = new SubscribesController();
            $subscribedEvents = $subscribesController->getSubscribedEvents($user, $conn);
            
            $myEvents = array();
            foreach($subscribedEvents as $event) {
                $eventObj = new Event($event['event_id'], null, null, null, null, null, null, null, null);
                $eventObj = $eventController->getEvent($conn, $eventObj);
                if($event['access'] == '1') {
                    array_push($myEvents, $eventObj);
                }
            }
            
            $eventsNamed = array();
            foreach($events as $event) {
                $eventsNamed[$event->getId()] = $event->getName();
            }
            
            foreach($events as $key => $event) {
                foreach($myEvents as $myEvent) {
                    if($event->getId() == $myEvent->getId()) {
                        unset($events[$key]);
                    }
                }
            }

            $availableForSubmissionEvents = $eventController->getAvailableForSubmissionEvents($conn);
        ?>
            <div class="sidebar">
                <p class="light">Olá, <br><b><?php echo explode(" ",$_SESSION['user']->getName())[0];?></b></p>
                <nav class="nav flex-column">
                    <a class="nav-link regular btn-page" href="#" page="#events-available">Eventos disponíveis</a>
                    <a class="nav-link regular btn-page" href="#" page="#my-subscriptions">Minhas inscrições</a>
                    <a class="nav-link regular btn-page" href="#" page="#my-transactions">Minhas transações</a>
                    <a class="nav-link regular btn-page" href="#" page="#profile">Meus dados</a>
                    <br>
                    <a class="nav-link regular" href="logout.php">Sair <span class="fas fa-sign-out-alt"></span></a>
                </nav>
            </div>

            <nav id="nav-wrap">
                <ul class="regular">
                    <li><a class="nav-link regular btn-page" href="#" page="#events-available">Eventos disponíveis</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#my-subscriptions">Minhas inscrições</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#my-transactions">Minhas transações</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#profile">Meus dados</a></li>
                    <br>
                    <li><a class="nav-link regular" href="logout.php">Sair <span class="fas fa-sign-out-alt"></span></a></li>
                </ul>
            </nav>
            
            <section id="main-menu">   
                <div class="visor" class="container">
                    <div id="events-available" class="page active">
                            <h5 class="black green">Eventos disponíveis para compra</h5>
                            <?php
                                foreach ($events as $event) {
                                ?>
                            <button type="button" class="event-registration button green" id="<?php echo $event->getId(); ?>"><?php echo $event->getName(); ?></button>
                                <?php
                                }
                            ?>
                            <h5 class="black green">Disponíveis para submissão</h5>
                            <?php
                                foreach ($availableForSubmissionEvents as $event) {
                                ?>
                            <button type="button" class="event-submission button green" id="<?php echo $event->getId(); ?>"><?php echo $event->getName(); ?></button>
                                <?php
                                }
                            ?>
                    </div>
                    <div id="my-subscriptions" class="page">
                        <h5 class="black green">Minhas inscrições</h5>
                        <?php
                            foreach ($myEvents as $event) {
                            ?>
                        <button type="button" class="event button green" id="<?php echo $event->getId(); ?>"><?php echo $event->getName(); ?></button>
                            <?php
                            }
                        ?>
                    </div>
                    <div id="my-transactions" class="page">
                        <h5 class="black green">Minhas transações</h5>
                        <table>
                            <thead>
                                <tr class="bold">
                                    <td width="35%">Evento</td>
                                    <td width="25%">Data do pedido</td>
                                    <td width="20%">Valor</td>
                                    <td width="20%">Status</td>
                                </tr>
                            </thead>
                            <tbody class="regular">
                                <?php
                                    foreach($transactions as $transaction) {
                                        $date = strtotime($transaction->getTransactionDate());
                                        $date = date("d/m/Y H:i", $date);
                                        
                                        $value = "R$ ".number_format($transaction->getValue(), 2, ',', '.');
                                        
                                        $status = array(
                                            -1 => 'Não processada',
                                            0 => 'Em espera',
                                            1 => 'Aguardando pagamento',
                                            2 => 'Em análise',
                                            3 => 'Paga',
                                            4 => 'Disponível',
                                            5 => 'Em disputa',
                                            6 => 'Devolvida',
                                            7 => 'Cancelada',
                                            8 => 'Debitado',
                                            9 => 'Retenção temporária'
                                        );
                                        
                                        $status = $status[$transaction->getStatus()];
                                        $statusColor = 'pending';
                                        
                                        if($transaction->getStatus() == 3 || $transaction->getStatus() == 4) {
                                            $statusColor = 'ok';
                                        }  elseif($transaction->getStatus() == -1 || $transaction->getStatus() == 6 || $transaction->getStatus() == 7) {
                                            $statusColor = 'error';
                                        }
                                        
                                    
                                ?>
                                <tr>
                                    <td><?php echo $eventsNamed[$transaction->getEvent_id()]; ?></td>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $value; ?></td>
                                    <td><i class="fas fa-circle <?php echo $statusColor; ?>"></i> <?php echo $status; ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="profile" class="page">
                        <h5 class="black green">Meu perfil</h5>
                        <form id="profile-form" class="col-md-6">
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="name" id="name" placeholder="Nome" class="form-control col-md-12 col-xs-12" value="<?php echo $user->getName(); ?>">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="email" name="email" id="email" placeholder="E-mail" class="form-control col-md-12 col-xs-12" value="<?php echo $user->getEmail(); ?>">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="phone" id="phone" placeholder="Telefone" class="form-control col-md-12 col-xs-12 phonemask" value="<?php echo $user->getPhone(); ?>">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="password" name="password" id="password" placeholder="Senha" class="form-control col-md-12 col-xs-12" value="<?php echo $user->getPassword(); ?>">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="course" id="course" placeholder="Curso" class="form-control col-md-12 col-xs-12" value="<?php echo $user->getCourse(); ?>">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="formation" class="col-form-label">Formação:</label>
                                <select class="custom-select" id="formation" name="formation" required>
                                    <option value="graduado" <?php echo $user->getFormation() == 'graduado' ? 'selected' : ''; ?>>Graduado</option>
                                    <option value="graduando" <?php echo $user->getFormation() == 'graduando' ? 'selected' : ''; ?>>Graduando</option>
                                    <option value="técnico" <?php echo $user->getFormation() == 'técnico' ? 'selected' : ''; ?>>Técnico</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="estado" class="col-form-label">Estado:</label>
                                <select class="custom-select" id="estado" name="estado" required>
                                    <?php
                                    foreach ($states as $state) {
                                    ?>
                                    <option value="<?php echo $state->getId(); ?>" <?php echo $state->getId() == $user->getEstado_id() ? 'selected' : ''; ?>><?php echo $state->getNome();?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="cpf" id="cpf" value="<?php echo $user->getCpf(); ?>">
                            <div class="col-md-12 col-xs-12">
                                <button type="button" value="register" name="btn-update" id="btn-update" class="regular btn btn-success col-md-12 col-xs-12">Atualizar meus dados</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        <?php
        }
        ?>
    </body>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../js/functions.js" type="text/javascript"></script>
</html>

<?php
include_once "../php/config/database.php";
include_once "../php/model/admin.php";
include_once "../php/model/state.php";
include_once "../php/model/city.php";
include_once "../php/model/event.php";
include_once "../php/model/user.php";
include_once "../php/model/coupon.php";
include_once "../php/model/ticket.php";
include_once "../php/model/schedule.php";
include_once "../php/model/news.php";
include_once "../php/model/speaker.php";
include_once "../php/model/partner.php";
include_once "../php/model/type.php";
include_once "../php/model/transaction.php";
include_once "../php/model/submission.php";
include_once "../php/model/eventConfig.php";
include_once "../php/controller/eventConfig.php";
include_once "../php/controller/submission.php";
include_once "../php/controller/transaction.php";
include_once "../php/controller/news.php";
include_once "../php/controller/schedule.php";
include_once "../php/controller/ticket.php";
include_once "../php/controller/coupon.php";
include_once "../php/controller/subscribes.php";
include_once "../php/controller/user.php";
include_once "../php/controller/state.php";
include_once "../php/controller/event.php";
include_once "../php/controller/speaker.php";
include_once "../php/controller/type.php";
include_once "../php/controller/partner.php";

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
        <link type="text/css" rel="stylesheet" href="../../css/styles.css">
        <link rel="shortcut icon" type="image/x-icon" href="../../favicon.ico">
        <link rel="stylesheet" href="../../js/ui/trumbowyg.min.css">
        <link rel="stylesheet" href="../../js/plugins/table/ui/trumbowyg.table.min.css">
        <link rel="stylesheet" href="../../js/plugins/colors/ui/trumbowyg.colors.min.css">
        <link rel="stylesheet" href="../../js/plugins/emoji/ui/trumbowyg.emoji.min.css">
    </head>

    <body>
        <div id="dialogBox" class="<?php if(isset($_SESSION['message'])) { echo 'active'; } ?>">
            <div class="frame">
                
                <i class="fas fa-times"></i><br>
                <i class="description green fas fa-exclamation-circle"></i><br/>
                <p class="message">
                    <?php if(isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); } ?>
                </p>
            </div>
        </div>

        <header class="light scroll no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" src="../../media/logo-minimal.png" alt="">
                </div>
            </div>
        </header>
        <?php
        function cmp($a, $b) {
            return strcmp($a->getName(), $b->getName());
        }

        if(!isset($_SESSION["admin"])) {
            session_destroy();
            header('location: ../');
        } elseif(isset($_GET["event"])) {
            $event = new Event($_GET["event"], null, null, null, null, null, null, null, null);

            $admin = $_SESSION['admin'];

            $database = new Database();
            $conn = $database->getConn();
            
            $userController = new UserController();

            $stateController = new StateController();
            $states = $stateController->getStates($conn);

            $eventController = new EventController();
            $event = $eventController->getEvent($conn, $event);
            
            $subscribesController = new SubscribesController();
            $subscriptions = $subscribesController->getParticipants($event, $conn);

            $confirmed = 0;
            $pending = 0;

            $participants = array();
            foreach($subscriptions as $participant) {
                $user = new User($participant['user_cpf'], null, null, null, null, null, null, null);
                $user = $userController->getUserInformation($user, $conn);
                array_push($participants, $user);

                if($participant['access'] == 1) {
                    $confirmed += 1;
                }
                /* else {
                    $pending += 1;
                }*/
            }
            
            usort($participants, "cmp");

            $couponController = new CouponController();
            $coupons = $couponController->getCouponsByEvent(new Coupon(null, null, $event->getId()), $conn);

            $ticketController = new TicketController();
            $tickets = $ticketController->getTicketsByEvent(new Ticket(null, null, null, null, null, null, $event->getId()), $conn);

            $scheduleController = new ScheduleController();
            $schedule = new Schedule($event->getId(), null, null, null);
            $schedules = $scheduleController->getSchedulesByEvent($schedule, $conn);
            try {
                $days = $scheduleController->getDaysOfEvent($schedule, $conn);
            } catch(Exception $e) {
                //
            }
            $newsController = new NewsController();
            $news = new News(null, null, null, null, $event->getId());
            $news = $newsController->getNewsByEvent($news, $conn);

            $speakerController = new SpeakerController();
            $speakers = new Speaker($event->getId(), null, null, null, null);
            $speakers = $speakerController->getSpeakersByEvent($speakers, $conn);

            $typesController = new TypeController();
            $types = new Type(null, null, $event->getId());
            $types = $typesController->getEventTypes($conn, $types);

            $typesKey = array();
            foreach($types as $type) {
                $typesKey[$type->getId()] = $type->getName();
            }

            $transactions = new Transaction(null, null, null, null, null, $event->getId(), null, null, null);
            $transactionController = new TransactionController();
            $transactions = $transactionController->getTransactionsByEvent($transactions, $conn);

            $revenues = 0.0;
            $pendingRevenues = 0.0;
            foreach ($transactions as $transaction) {
                if($transaction->getStatus() == 3 || $transaction->getStatus() == 4) {
                    $revenues += $transaction->getValue();
                } elseif(($transaction->getStatus() > 0 && $transaction->getStatus() < 3) || $transaction->getStatus() == 5) {
                    $pendingRevenues += $transaction->getValue();
                    $pending += 1;
                }
            }

            $partnerController = new PartnerController();
            $partners = new Partner(null, null, null, $event->getId());
            $partners = $partnerController->getPartnersByEvent($partners, $conn);

            $submissionController = new SubmissionController();
            $submissions = new Submission(null, null, $event->getId(), null, null, null, null, null, null, null);
            $submissions = $submissionController->getSubmissionsByEvent($submissions, $conn);

            $eventConfig = new EventConfig($event->getId(), null);
            $eventConfigController = new EventConfigController();
            $eventConfig = $eventConfigController->getEventConfig($eventConfig, $conn);

            $sql = 'select * from location where event_id = :event';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':event', $event->getId());
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $location = $stmt->fetch();
        ?>

            <div class="sidebar">
                <p class="light">Olá, <br><b><?php echo explode(" ",$admin->getName())[0];?></b></p>
                <nav class="nav flex-column">
                    <a class="nav-link regular btn-page" href="#" page="#resume-page">Resumo</a>
                    <a class="nav-link regular btn-page" href="#" page="#event-page">Página do Evento</a>
                    <a class="nav-link regular btn-page" href="#" page="#submissions-page">Trabalhos</a>
                    <a class="nav-link regular btn-page" href="#" page="#participants-page">Participantes</a>
                    <a class="nav-link regular btn-page" href="#" page="#financial-page">Financeiro</a>
                    <a class="nav-link regular btn-page" href="#" page="#schedule-page">Programação</a>
                    <a class="nav-link regular btn-page" href="#" page="#mailing-page">Mailing</a>
                    <a class="nav-link regular btn-page" href="#" page="#news-page">Notícias</a>
                    <a class="nav-link regular btn-page" href="../">Ver eventos</a>
                    <br>
                    <a class="nav-link regular" href="../php/authenticate/logout.php">Sair <span class="fas fa-sign-out-alt"></span></a>
                    <br>
                    <a class="nav-link black regular" id="delete-event" href="#"><i class="fas fa-exclamation-circle"></i> Excluir evento</a>
                </nav>
            </div>

            <section id="main-menu">
                <div class="visor" class="container">
                    <div id="resume-page" class="page active">
                        <h5 class="black green">Resumo do <?php echo $event->getName();?></h5>
                        <div id="blocks">
                            <div class="block" onclick="window.open('participant/filter.php?event=<?php echo $event->getId(); ?>&type=1', '_self');">
                                <h1 class="black info"><?php echo count($participants); ?></h1>
                                <p class="regular name">participantes</p>
                            </div>
                            <div class="block" onclick="window.open('participant/filter.php?event=<?php echo $event->getId(); ?>&type=2', '_self');">
                                <h1 class="black info"><?php echo $confirmed; ?></h1>
                                <p class="regular name">confirmados</p>
                            </div>
                            <div class="block" onclick="window.open('participant/filter.php?event=<?php echo $event->getId(); ?>&type=3', '_self');">
                                <h1 class="black info"><?php echo $pending; ?></h1>
                                <p class="regular name">pendentes</p>
                            </div>
                            <!--
                            <div class="block">
                                <p class="regular name">R$</p>
                                <h4 class="black info">0,00</h4>
                                <p class="regular name">valor faturado</p>
                            </div>
                            <div class="block">
                                <p class="regular name">R$</p>
                                <h4 class="black info">0,00</h4>
                                <p class="regular name">valor esperado</p>
                            </div>
                            -->
                        </div>
                        <a href="#" class="button link btn-page regular" page="#participants-page"><span class="fas fa-angle-right"></span> Participantes</a>
                        <a href="#" class="button link btn-page regular" page="#financial-page"><span class="fas fa-angle-right"></span> Financeiro</a>
                        <a href="#" class="button link btn-page regular" page="#schedule-page"><span class="fas fa-angle-right"></span> Programação</a>
                    </div>

                    <div id="event-page" class="page">
                        <h5 class="black green">Página do <?php echo $event->getName();?></h5>
                        <div id="informations" class="col-12">
                            <h6 class="col-12 bold uppercase green">informações</h6>
                            <form id="information-form">
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="event-name" name="event-name" placeholder="Nome" value="<?php echo $event->getName(); ?>" required>
                                </div>
                                <div class="form-group col-3">
                                    <label for="event-date" class="bold uppercase green">data de início</label>
                                    <input type="date" class="form-control" id="event-date" name="event-date" placeholder="Data" value="<?php echo $event->getDate(); ?>" required>
                                </div>
                                <div class="form-group col-3">
                                    <label for="event-end-date" class="bold uppercase green">data de término</label>
                                    <input type="date" class="form-control" id="event-end-date" name="event-end-date" placeholder="Data final" value="<?php echo $event->getEndDate(); ?>" required>
                                </div>
                                <div class="form-group col-3">
                                    <textarea class="form-control" id="event-description" name="event-description" placeholder="Descrição" required><?php echo $event->getDescription(); ?></textarea>
                                </div>
                            </form>
                        </div>
                        <div id="dates" class="col-12">
                            <h6 class="col-12 bold uppercase green">inscrições</h6>
                            <form class="col-6">
                                <div class="form-group">
                                    <label for="event-subscription" class="bold uppercase green">data de término</label>
                                    <input type="date" class="form-control col-6" id="event-subscription" name="event-subscription" placeholder="Data" value="<?php echo $event->getSubscription_limit(); ?>" required>
                                </div>
                            </form>
                        </div>
                        <div id="configurations" class="col-12">
                            <h6 class="col-12 bold uppercase green">configurações</h6>
                            <form id="config-submission-form" class="col-6">
                                <div class="form-group custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="allow-event-submissions" <?php if($event->getAllow_submissions()==1) { echo 'checked'; } ?>>
                                    <label class="custom-control-label regular" for="allow-event-submissions">Permitir submissões</label>
                                </div>
                                <div class="form-group">
                                    <label for="event-deadline" class="bold uppercase green">data de término</label>
                                    <input type="date" class="form-control col-6" id="event-deadline" name="event-deadline" placeholder="Data" value="<?php if($event->getAllow_submissions() == 1) echo explode(" ", $event->getDeadline())[0]; ?>" <?php if($event->getAllow_submissions()==0) { echo 'disabled'; } ?> required>
                                </div>
                                <button type="button" class="col-6 btn btn-success" id="event-config-button" name="event-config-button">Atualizar informações</button>
                            </form>
                            <form id="location-form" class="col-6" action="../php/operations/location.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="event-location" class="bold uppercase green">local</label>
                                    <input type="text" class="form-control col-12" id="event-location" name="event-location" placeholder="Local" value="<?php echo $location['name']; ?>">
                                </div>
                                <div class="form-group custom-file col-12">
                                    <input type="file" class="custom-file-input" id="local-file" name="local-file" lang="pt-br">
                                    <label class="custom-file-label" for="local-file">Imagem do local</label>
                                </div>
                                <input type="hidden" name="event" value="<?php echo $event->getId(); ?>">
                                <button type="submit" style="margin-top: 15px;" class="col-12 btn btn-success" id="event-location-btn" name="event-location-btn">Atualizar local</button>
                                <button type="submit" style="margin-top: 15px;" class="col-12 btn btn-success" id="event-location-btn" name="event-location-remove-btn">Retirar local</button>
                            </form>
                            <h6 class="col-12 bold uppercase green">áreas de estudo</h6>
                            <table id="types-table">
                                <thead class="bold">
                                    <tr>
                                        <td width="80%">Nome</td>
                                        <td width="20%">Ações</td>
                                    </tr>
                                </thead>
                                <tbody class="regular">
                                    <?php
                                    foreach($types as $type) {
                                    ?>
                                    <tr>
                                        <td><?php echo $type->getName(); ?></td>
                                        <td data-id="<?php echo $type->getId(); ?>"><i class="fas fa-trash pointer"></i></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <h6 class="col-12 bold uppercase green">adicionar áreas de estudo</h6>
                            <form id="types-form" class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control col-6" id="type-name" name="type-name" placeholder="Nome da área" <?php if($event->getAllow_submissions()==0) { echo 'disabled'; } ?> required>
                                </div>
                                <button type="button" class="col-6 btn btn-success" id="type-button" name="event-config-button" <?php if($event->getAllow_submissions()==0) { echo 'disabled'; } ?>>Adicionar área</button>
                            </form>
                            <h6 class="col-12 bold uppercase green">imagem de destaque</h6>
                            <form action="../php/operations/event.php" method="post" id="config-image-form" class="col-6" enctype="multipart/form-data">
                                <div class="form-group custom-file col-12">
                                    <input type="file" class="custom-file-input" id="featured-image" name="featured-image" lang="pt-br">
                                    <label class="custom-file-label" for="featured-image">Imagem de destaque (356 x 203 px)</label>
                                </div>
                                <input type="hidden" value="<?php echo $event->getId(); ?>" name="event-id">
                                <button type="submit" class="col-12 btn btn-success" id="image-button" name="image-button">Salvar imagem <span class="fas fa-check"></span></button>
                            </form>
                            <h6 class="col-12 bold uppercase green">imagem de fundo</h6>
                            <form action="../php/operations/event.php" method="post" id="config-image-form" class="col-6" enctype="multipart/form-data">
                                <div class="form-group custom-file col-12">
                                    <input type="file" class="custom-file-input" id="background-image" name="background-image" lang="pt-br">
                                    <label class="custom-file-label" for="background-image">Imagem de fundo (1280 x 720 px)</label>
                                </div>
                                <input type="hidden" value="<?php echo $event->getId(); ?>" name="event-id">
                                <button type="submit" class="col-12 btn btn-success" id="background-image-button" name="background-image-button">Salvar imagem <span class="fas fa-check"></span></button>
                            </form>
                        </div>
                        <div id="speakers-section" class="col-12">
                            <h6 class="col-12 bold uppercase green">adicionar palestrantes</h6>
                            <form action="../php/operations/speakers.php" method="post" id="speakers-form" enctype="multipart/form-data">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control col-6" id="speaker-name" name="speaker-name" placeholder="Nome do palestrante" required>
                                </div>
                                <div class="form-group col-12">
                                    <textarea type="text" class="form-control col-6" id="speaker-description" name="speaker-description" placeholder="Descrição" required></textarea>
                                </div>
                                <div class="form-group col-12">
                                    <input type="url" class="form-control col-6" id="speaker-link" name="speaker-link" placeholder="Link" required>
                                </div>
                                <div class="form-group col-12">
                                    <div class="custom-file col-6">
                                        <input type="file" class="custom-file-input" id="speaker-image" name="speaker-image" lang="pt-br">
                                        <label class="custom-file-label" for="speaker-image">Imagem do palestrante</label>
                                    </div>
                                </div>
                                <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                                <div class="form-group col-12">
                                    <button type="submit" class="col-6 btn btn-success" id="add-speaker-button" name="add-speaker-button">Salvar <span class="fas fa-check"></span></button>
                                </div>
                            </form>
                        </div>
                        <div id="speakers-list">
                            <h6 class="col-12 bold uppercase green">palestrantes</h6>
                            <table id="speakers-table">
                                <thead class="bold">
                                    <tr>
                                        <td width="35%">Nome</td>
                                        <td width="25%">Descrição</td>
                                        <td width="25%">Ações</td>
                                    </tr>
                                </thead>
                                <tbody class="regular">
                                    <?php
                                    foreach($speakers as $speaker) {
                                    ?>
                                    <tr>
                                        <td><?php echo $speaker->getName(); ?></td>
                                        <td><?php echo $speaker->getDescription(); ?></td>
                                        <td data-name="<?php echo $speaker->getName(); ?>" data-description="<?php echo $speaker->getDescription(); ?>"><i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="partners-section" class="col-12">
                            <h6 class="col-12 bold uppercase green">adicionar parceiros</h6>
                            <form action="../php/operations/partner.php" method="post" id="partners-form" enctype="multipart/form-data">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control col-6" id="partner-name-input" name="partner-name" placeholder="Nome do parceiro" required>
                                </div>
                                <div class="form-group col-12">
                                    <input type="url" class="form-control col-6" id="partner-link" name="partner-link" placeholder="URL do parceiro" required>
                                </div>
                                <div class="form-group col-12">
                                    <div class="custom-file col-6">
                                        <input type="file" class="custom-file-input" id="partner-image" name="partner-image" lang="pt-br">
                                        <label class="custom-file-label" for="partner-image">Marca do parceiro</label>
                                    </div>
                                </div>
                                <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                                <div class="form-group col-12">
                                    <button type="submit" class="col-6 btn btn-success" id="add-partner-button" name="add-partner-button">Salvar <span class="fas fa-check"></span></button>
                                </div>
                            </form>
                        </div>
                        <div id="partners-list">
                            <h6 class="col-12 bold uppercase green">parceiros</h6>
                            <table id="partners-table">
                                <thead class="bold">
                                    <tr>
                                        <td width="35%">Nome</td>
                                        <td width="25%">Link</td>
                                        <td width="25%">Ações</td>
                                    </tr>
                                </thead>
                                <tbody class="regular">
                                    <?php
                                    foreach($partners as $partner) {
                                    ?>
                                    <tr>
                                        <td><?php echo $partner->getName(); ?></td>
                                        <td><?php echo $partner->getLink(); ?></td>
                                        <td data-name="<?php echo $partner->getName(); ?>" data-link="<?php echo $partner->getLink(); ?>"><i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="assistlink-section" class="col-12">
                            <h6 class="col-12 bold uppercase green">Link de hospedagem</h6>
                            <form method="post" id="assistlink-form" enctype="multipart/form-data">
                                <div class="form-group col-12">
                                    <input type="url" class="form-control col-6" id="assistlink-link" name="assistlink-link" placeholder="URL do site" required>
                                </div>
                                <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                                <div class="form-group col-12">
                                    <button type="button" class="col-6 btn btn-success" id="add-assistlink-button" name="add-assistlink-button">Atualizar <span class="fas fa-check"></span></button>
                                </div>
                            </form>
                            <?php 
                                if($eventConfig->getTraveling() == 1) {
                            ?>
                            <div class="form-group col-12">
                                <button type="button" class="col-6 btn btn-danger" id="set-traveling" name="set-traveling" value="0">Desabilitar hospedagem <span class="fas fa-times"></span></button>
                            </div>

                            <?php 
                                } else {
                            ?>
                                <div class="form-group col-12">
                                    <button type="button" class="col-6 btn btn-success" id="set-traveling" name="set-traveling" value="1">Habilitar hospedagem <span class="fas fa-check"></span></button>
                                </div>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>

                    <div id="participants-page" class="page">
                        <h5 class="black green">Participantes</h5>
                        <table id="participant-table">
                            <thead class="bold">
                                <tr>
                                    <td width="35%">Nome</td>
                                    <td width="20%">CPF</td>
                                    <td width="30%">E-mail</td>
                                    <td width="7%">Acesso</td>
                                    <td width="5%">Ações</td>
                                </tr>
                            </thead>
                            <tbody class="regular">
                                <?php
                                foreach($participants as $participant) {
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
                                <tr>
                                    <td><?php echo $participant->getName(); ?></td>
                                    <td><?php echo $participant->getCpf(); ?></td>
                                    <td><?php echo $participant->getEmail(); ?></td>
                                    <td width="5%"><span class="badge badge-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                    <td data-cpf="<?php echo $participant->getCpf(); ?>"><a href="participant/?event=<?php echo $event->getId(); ?>&cpf=<?php echo $participant->getCpf(); ?>" target="_blank"><i class="fas fa-search green pointer"></i></a> <i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div id="participant-info">
                            <div id="info-box">
                                <i class="fas fa-times pointer"></i>
                                <p class="bold uppercase"><span class="description-label regular">Nome</span><br/><span id="user-name"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">E-mail</span><br/><span id="user-email"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">CPF</span><br/><span id="user-cpf"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">Telefone</span><br/><span id="user-phone"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">Estado</span><br/><span id="user-estado"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">Curso</span><br/><span id="user-course"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">Formação</span><br/><span id="user-formation"></span></p>
                                <p class="bold uppercase"><span class="description-label regular">Pagamento</span><br/><p id="user-status"></p></p>
                            </div>
                        </div>
                    </div>

                    <div id="submissions-page" class="page">
                        <h5 class="black green">Trabalhos</h5>
                        <table id="submissions-table">
                            <thead class="bold">
                                <tr>
                                    <td width="30%">Título</td>
                                    <td width="25%">Palavras-chave</td>
                                    <td width="20%">Status</td>
                                    <td width="15%">Área</td>
                                    <td width="7%">Ações</td>
                                </tr>
                            </thead>
                            <tbody class="regular">
                                <?php
                                foreach($submissions as $submission) {
                                    $status = $submission->getStatus();
                                    switch($status) {
                                        case 0:
                                            $class = "pending";
                                            $text = "Em avaliação";
                                            break;
                                        case 1:
                                            $class = "ok";
                                            $text = "Aprovado";
                                            break;
                                        case -1:
                                            $class = "error";
                                            $text = "Reprovado";
                                            break;
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $submission->getTitle(); ?></td>
                                    <td><?php echo $submission->getKeywords(); ?></td>
                                    <td id="submission-status-td"><i class="fas fa-circle <?php echo $class; ?>"></i> <?php echo $text; ?></td>
                                    <td><?php echo $typesKey[$submission->getType()]; ?></td>
                                    <td data-id="<?php echo $submission->getId(); ?>"><i onclick="window.open('<?php echo $submission->getFile(); ?>', '_blank')" class="fas fa-file green pointer"></i> <i class="fas fa-check green pointer"></i> <i class="fas fa-times red pointer"></i></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="financial-page" class="page">
                        <h5 class="black green">Financeiro</h5>
                        
                            <div id="blocks">
                            <!--
                            <div class="block">
                                <p class="regular name">R$</p>
                                <h4 class="black info">0,00</h4>
                                <p class="regular name">ticket médio</p>
                            </div>
                            -->
                            <div class="block">
                                <p class="regular name">R$</p>
                                <h4 class="black info"><?php echo number_format($revenues, 2, ',', '.'); ?></h4>
                                <p class="regular name">valor faturado</p>
                            </div>
                            <div class="block">
                                <p class="regular name">R$</p>
                                <h4 class="black info"><?php echo number_format($pendingRevenues, 2, ',', '.'); ?></h4>
                                <p class="regular name">valor esperado</p>
                            </div>
                        </div>
                        <div id="coupon-section" class="col-12">
                            <div class="col-3">
                                <h6 class="bold uppercase green">Adicionar cupom</h6>
                                <form id="coupon-form">
                                    <div class="form-group col-12">
                                        <input type="text" class="form-control" id="coupon-name" name="coupon-name" placeholder="Código" required>
                                    </div>
                                    <div class="form-group col-12">
                                        <input type="text" class="form-control" id="coupon-discount" name="coupon-discount" placeholder="Desconto (%)" required>
                                    </div>
                                    <button type="button" class="col-12 btn btn-success" id="coupon-button" name="coupon-button">Adicionar cupom</button>
                                </form>
                            </div>
                            <div class="col-8">
                                <h6 class="bold uppercase green">Cupons ativos</h6>
                                <table class="col-12" id="coupon-table">
                                    <thead class="bold">
                                        <tr>
                                            <td width="45%">Código</td>
                                            <td width="35%">Desconto (%)</td>
                                            <td width="20%">Ações</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($coupons as $coupon) {
                                        ?>
                                        <tr>
                                            <td><?php echo $coupon->getCode(); ?></td>
                                            <td><?php echo $coupon->getDiscount(); ?>%</td>
                                            <td data-code="<?php echo $coupon->getCode(); ?>" data-discount="<?php echo $coupon->getDiscount(); ?>"><i class="fas fa-trash pointer red"></i> <i class="fas fa-pencil-alt pointer green"></i></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="tickets" class="col-12">
                                <h6 class="bold uppercase green">Entradas</h6>
                                <table class="col-12" id="tickets-table">
                                    <thead class="bold">
                                        <tr>
                                            <td width="20%">Nome</td>
                                            <td width="10%">Valor</td>
                                            <td width="15%">Início</td>
                                            <td width="15%">Término</td>
                                            <td width="30%">Descrição</td>
                                            <td width="10%">Ações</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($tickets as $ticket) {
                                            $initialDate = strtotime($ticket->getInitialDate());
                                            $initialDate = strftime("%d/%m/%Y", $initialDate);

                                            $finalDate = strtotime($ticket->getFinalDate());
                                            $finalDate = strftime("%d/%m/%Y", $finalDate);
                                        ?>
                                        <tr>
                                            <td><?php echo $ticket->getName(); ?></td>
                                            <td>R$ <?php echo $ticket->getPrice(); ?></td>
                                            <td><?php echo $initialDate; ?></td>
                                            <td><?php echo $finalDate; ?></td>
                                            <td><?php echo $ticket->getDescription(); ?></td>
                                            <td data-id="<?php echo $ticket->getId(); ?>" data-name="<?php echo $ticket->getName(); ?>" data-price="<?php echo $ticket->getPrice(); ?>" data-description="<?php echo $ticket->getDescription(); ?>" data-initial-date="<?php echo $ticket->getInitialDate(); ?>" data-final-date="<?php echo $ticket->getFinalDate(); ?>"><i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table><br>
                                <h6 class="bold uppercase green">Criar entrada</h6>
                                <form id="tickets-form">
                                    <div class="form-group col-8">
                                        <input type="text" class="form-control" id="ticket-name" name="ticket-name" placeholder="Nome" required>
                                    </div>
                                    <div class="form-group col-2">
                                        <input type="text" class="form-control" id="ticket-value" name="ticket-value" placeholder="Valor (R$)" required>
                                    </div>
                                    <br>
                                    <div class="form-group col-10">
                                        <textarea type="text" class="form-control" id="ticket-description" name="ticket-description" placeholder="Descrição" required></textarea>
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="ticket-initial-date" class="bold uppercase green">início</label>
                                        <input type="date" class="form-control" id="ticket-initial-date" name="ticket-initial-date" required>
                                    </div>
                                    <div class="form-group col-2">
                                        <label for="ticket-final-date" class="bold uppercase green">final</label>
                                        <input type="date" class="form-control" id="ticket-final-date" name="ticket-final-date" required>
                                    </div>
                                    <br>
                                    <button type="button" class="col-2 btn btn-success" id="ticket-button" name="ticket-button">Adicionar entrada</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="schedule-page" class="page">
                        <h5 class="black green">Programação</h5>
                        <div id="day-insert">
                            <form id="day-form" enctype="multipart/form-data">
                                <div class="form-group">
                                    <i class="fas fa-times"></i>
                                    <input type="date" class="form-control" id="day-input-title" name="day-input-title" placeholder="" required>
                                    <button type="button" class="col-12 btn btn-success" id="day-add-button" name="day-add-button">Adicionar</button>
                                </div>
                            </form>
                        </div>
                        <div id="days">
                            <?php
                            foreach ($days as $day) {
                            ?>
                            <div class="day-block" data-time="<?php echo $day->getDate(); ?>">                       
                                <h1 class="black"><?php echo $day->getDay(); ?></h1>                          
                                <p class="regular"><?php echo $day->getFormattedMonth(); ?></p>
                                <i class="editDayGroup fas fa-pencil-alt"></i>
                            </div>   
                            <?php
                            }
                            ?> 
                            <div id="add-day">
                                <h1 class="fas fa-plus-circle pointer green"></h1>
                            </div>
                        </div>
                        <h6 class="bold uppercase green">Ações</h6>
                        <div id="acts">
                            <?php
                            foreach ($schedules as $schedule) {
                            ?>
                            <div class="act" data-time-ref="<?php echo $schedule->getDate(); ?>" data-full-time="<?php echo $schedule->getScheduleTime(); ?>" data-title="<?php echo $schedule->getTitle(); ?>">
                                <h5 class="bold uppercase"><?php echo $schedule->getTitle(); ?> <span class="regular"><?php echo substr($schedule->getTime(), 0, 5); ?> ~ <?php echo substr($schedule->getFinalTime(), 0, 5); ?></span></h5>
                                <div class="uppercase regular pointer button blue"><i class="fas fa-pencil-alt pointer"></i> Editar</div>
                                <div class="uppercase regular pointer button red"><i class="fas fa-trash pointer"></i> Excluir</div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <h6 class="bold uppercase green">Criar ação</h6>
                        <div id="add-act">
                            <form id="day-form">
                                <div class="row">
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="act-name" name="act-name" placeholder="Título" required>
                                    </div>
                                    <div class="form-group col-2">
                                        <input type="time" class="form-control" id="act-time" name="act-time" placeholder="Horário" required>
                                    </div>
                                    <div class="form-group col-2">
                                        <input type="time" class="form-control" id="act-time-final" name="act-time-final" placeholder="Horário" required>
                                    </div>
                                    <button type="button" class="col-3 btn btn-success" id="act-add-button" name="act-add-button">Adicionar ação</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div id="mailing-page" class="page">
                        <h5 class="black green">Mailing</h5>
                        <form class="col-12">
                            <div class="form-group col-12">
                                <input type="text" class="form-control" name="mail-subject" id="mail-subject" placeholder="Assunto" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea name="mail-message" id="mail-message"></textarea>
                            </div>
                            <input type="hidden" name="event" value="<?php echo $event->getName(); ?>">
                            <div class="form-group col-12">
                                <button type="button" class="btn btn-success" id="send-mail" name="send-mail">Enviar <i class="fas fa-send"></i></button>
                            </div>
                        </form>
                        
                        <div id="mailing-popup" class="col-8">
                            <table id="mailing-table">
                                <thead class="bold">
                                    <tr>
                                        <td width="2%"></td>
                                        <td width="35%">Nome</td>
                                        <td width="20%">CPF</td>
                                        <td width="30%">E-mail</td>
                                        <td width="7%">Acesso</td>
                                    </tr>
                                </thead>
                                <tbody class="regular">
                                    <?php
                                    foreach($participants as $participant) {
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
                                    <tr data-name="<?php echo $participant->getName(); ?>" data-email="<?php echo $participant->getEmail(); ?>">
                                        <td><input type="checkbox" style="height: auto;"></td>
                                        <td><?php echo $participant->getName(); ?></td>
                                        <td><?php echo $participant->getCpf(); ?></td>
                                        <td><?php echo $participant->getEmail(); ?></td>
                                        <td width="5%"><span class="badge badge-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary" id="confirm-mailing-popup" style="margin: 0 15px 10px 15px;">Escolher usuários</button>
                            <button type="button" class="btn btn-secondary" id="close-mailing-popup" style="margin: 0 15px 10px 15px;">Cancelar</button>
                        </div>

                        <div id="progress-mailing" class="col-4">
                            <span class="bold uppercase">Enviando e-mails...</span>
                            <span class="yellow uppercase regular pending"></span>
                            <span class="green uppercase regular success"></span>
                            <button type="button" class="btn btn-primary">Fechar</button>
                        </div>
                    </div>

                    <div id="editAct">
                        <form id="day-form" class="col-6">
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control" id="act-name" name="act-name" placeholder="Título" required>
                                </div>
                                <div class="form-group col-12">
                                    <input type="date" class="form-control" id="act-date" name="act-date" placeholder="Data" required>
                                </div>
                                <div class="form-group col-12">
                                    <input type="time" class="form-control" id="act-time" name="act-time" placeholder="Horário" required>
                                </div>
                                <div class="form-group col-12">
                                    <input type="time" class="form-control" id="act-time-final" name="act-time-final" placeholder="Horário" required>
                                </div>
                                <div class="col-6"><button type="button" class="col-12 btn btn-success" id="act-edit-button" name="act-edit-button">Atualizar ação</button></div>
                                <div class="col-6"><button type="button" class="col-12 btn btn-primary" id="act-cancel-button" name="act-cancel-button">Cancelar</button></div>
                            </div>
                        </form>
                    </div>

                    <div id="editNews">
                        <form action="../php/operations/news.php" method="post" id="edit-news-form" class="col-6" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <input type="text" class="form-control" id="edit-new-title" name="new-title" placeholder="Nome" required>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="edit-new-file" name="new-file" lang="pt-br">
                                    <label class="custom-file-label" for="new-file">Escolher anexo</label>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <textarea type="text" class="form-control" id="edit-new-content" name="new-content" placeholder="Conteúdo da postagem" required></textarea>
                            </div>
                            <input type="hidden" name="news-id" value="<?php echo $event->getId(); ?>">
                            <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                            <button type="submit" class="col-12 btn btn-success" id="edit-new-button" name="edit-new-button">Salvar <span class="fas fa-check"></span> </button>
                            <button type="button" class="col-12 btn btn-primary" style="margin-top: 10px;" id="edit-new-cancel-button" name="new-cancel-button">Cancelar</button>
                        </form>
                    </div>

                    <div id="editDayAsGroup">
                        <form id="day-form" class="col-6">
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="date" class="form-control" id="day-date" name="day-date" placeholder="Data" required>
                                </div>
                                <div class="col-6"><button type="button" class="col-12 btn btn-success" id="day-edit-button" name="day-edit-button">Atualizar ação</button></div>
                                <div class="col-6"><button type="button" class="col-12 btn btn-primary" id="day-cancel-button" name="day-cancel-button">Cancelar</button></div>
                            </div>
                        </form>
                    </div>

                    <div id="editCoupon">
                        <form id="coupon-form" class="col-6">
                            <div class="form-group col-12">
                                <input type="text" class="form-control" id="coupon-name" name="coupon-name" placeholder="Código" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" id="coupon-discount" name="coupon-discount" placeholder="Desconto (%)" required>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-success" id="update-coupon-button" name="update-coupon-button">Atualizar cupom</button>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-primary" id="cancel-coupon-button" name="cancel-coupon-button">Cancelar</button>
                            </div>
                        </form>
                    </div>

                    <div id="editTicket">
                        <form id="tickets-form" class="col-6">
                            <div class="form-group col-12">
                                <input type="text" class="form-control" id="ticket-name" name="ticket-name" placeholder="Nome" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" id="ticket-value" name="ticket-value" placeholder="Valor (R$)" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="date" class="form-control" id="ticket-initial-date" name="ticket-initial-date" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="date" class="form-control" id="ticket-final-date" name="ticket-final-date" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea type="text" class="form-control" id="ticket-description" name="ticket-description" placeholder="Descrição" required></textarea>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-success" id="update-ticket-button" name="update-ticket-button">Atualizar entrada</button>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-primary" id="cancel-ticket-button" name="cancel-ticket-button">Cancelar</button>
                            </div>
                        </form>
                    </div>

                    <div id="editSpeaker">
                        <form action="../php/operations/speakers.php" class="col-6" method="post" id="speakers-form" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-12" id="speaker-name" name="speaker-name" placeholder="Nome do palestrante" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea type="text" class="form-control col-12" id="speaker-description" name="speaker-description" placeholder="Descrição" required></textarea>
                            </div>
                            <div class="form-group col-12">
                                <input type="url" class="form-control col-12" id="speaker-link" name="speaker-link" placeholder="Link" required>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-file col-12">
                                    <input type="file" class="custom-file-input" id="speaker-image" name="speaker-image" lang="pt-br">
                                    <label class="custom-file-label" for="speaker-image">Imagem do palestrante</label>
                                </div>
                            </div>
                            <input type="hidden" name="speaker-old-name" id="speaker-old-name">
                            <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                            <div class="form-group col-12">
                                <button type="submit" class="col-12 btn btn-success" id="update-speaker-button" name="update-speaker-button">Salvar <span class="fas fa-check"></span></button>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-primary" id="cancel-speaker-button" name="cancel-speaker-button">Cancelar <span class="fas fa-times"></span></button>
                            </div>
                        </form>
                    </div>
 
                    <div id="editParticipant"> 
                        <form id="participant-form" class="masked-form col-6" action="../painel/php/operations/user.php" method="post">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <input type="text" name="cpf" id="participant-cpf" placeholder="CPF" class="form-control col-md-12 col-xs-12 masked-input cpfmask">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="name" id="participant-name" placeholder="Nome" class="form-control col-md-12 col-xs-12">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="email" name="email" id="participant-email" placeholder="E-mail" class="form-control col-md-12 col-xs-12">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="password" name="password" id="participant-password" placeholder="Senha" class="form-control col-md-12 col-xs-12">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="phone" id="participant-phone" placeholder="Telefone" class="form-control col-md-12 col-xs-12 masked-input phonemask">
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="text" name="course" id="participant-course" placeholder="Curso" class="form-control col-md-12 col-xs-12">
                            </div>  
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="formation" class="col-form-label">Formação:</label>
                                <select class="custom-select" id="participant-formation" name="formation" required>
                                    <option disabled selected>Selecione</option>
                                    <option value="graduado">Graduado</option>
                                    <option value="graduando">Graduando</option>
                                    <option value="técnico">Técnico</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="estado" class="col-form-label">Estado:</label>
                                <select class="custom-select" id="participant-estado" name="estado" required>
                                    <option disabled selected>Selecione</option>
                                    <?php
                                    foreach ($states as $state) {
                                    ?>
                                    <option value="<?php echo $state->getId(); ?>"><?php echo $state->getNome();?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" id="participant-old-cpf">
                            <div class="col-md-12 col-xs-12">
                                <button type="button" value="register" name="btn-register" id="participant-update-button" class="btn btn-success regular col-md-12 col-xs-12">Atualizar dados <span class="fa fa-angle-right"></span></button>
                                <button type="button" class="col-12 btn btn-primary" style="margin-top: 10px;" id="participant-cancel-button" name="participant-cancel-button">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    
                    <div id="editPartner">
                        <form action="../php/operations/partner.php" class="col-6" method="post" id="partners-form" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-12" id="partner-name-input" name="partner-name" placeholder="Nome do parceiro" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea type="url" class="form-control col-12" id="partner-link" name="partner-link" placeholder="URL do parceiro" required></textarea>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-file col-12">
                                    <input type="file" class="custom-file-input" id="partner-image" name="partner-image" lang="pt-br">
                                    <label class="custom-file-label" for="partner-image">Marca do parceiro</label>
                                </div>
                            </div>
                            <input type="hidden" name="partner-old-name" id="partner-old-name">
                            <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                            <div class="form-group col-12">
                                <button type="submit" class="col-12 btn btn-success" id="update-partner-button" name="update-partner-button">Salvar <span class="fas fa-check"></span></button>
                            </div>
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-primary" id="cancel-partner-button" name="cancel-partner-button">Cancelar <span class="fas fa-times"></span></button>
                            </div>
                        </form>
                    </div>

                    <div id="news-page" class="page">
                        <h5 class="black green">Notícias</h5>
                        <div id="news-section" class="col-12">
                            <div id="news" class="col-12">
                                <h6 class="bold uppercase green">Criar notícia</h6>
                                <form action="../php/operations/news.php" method="post" id="news-form" enctype="multipart/form-data">
                                    <div class="form-group col-10">
                                        <input type="text" class="form-control" id="new-title" name="new-title" placeholder="Nome" required>
                                    </div>
                                    <div class="form-group col-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="new-file" name="new-file" lang="pt-br">
                                            <label class="custom-file-label" for="new-file">Escolher anexo</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-10">
                                        <textarea type="text" class="form-control" id="new-content" name="new-content" placeholder="Conteúdo da postagem" required></textarea>
                                    </div>
                                    <input type="hidden" name="event-id" value="<?php echo $event->getId(); ?>">
                                    <button type="submit" class="col-10 btn btn-success" id="new-button" name="new-button">Publicar <span class="fas fa-check"></span> </button>
                                </form><br>
                                <h6 class="bold uppercase green">Notícias publicadas</h6>
                                <table class="col-12" id="news-table">
                                    <thead class="bold">
                                        <tr>
                                            <td width="25%">Título</td>
                                            <td width="55%">Conteúdo</td>
                                            <td width="20%">Ações</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($news as $notice) {
                                        ?>
                                        <tr>
                                            <td><?php echo $notice->getTitle(); ?></td>
                                            <td><?php echo $notice->getMessage(); ?></td>
                                            <td data-id="<?php echo $notice->getId(); ?>" data-title="<?php echo $notice->getTitle(); ?>" data-content="<?php echo $notice->getMessage(); ?>"><a href="<?php echo '../../'.$notice->getFile(); ?>" target="_blank" onclick="void();"><i class="fas fa-file red pointer"></i></a> <i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    <script src="../../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../../js/jquery-resizable.min.js" type="text/javascript"></script>
    <script src="../../js/functions.js" type="text/javascript"></script>
    <param id="event" data="<?php echo $event->getId(); ?>">
    
    <script src="../../js/trumbowyg.min.js"></script>
    <script src="../../js/langs/pt_br.min.js"></script>
    <script src="../../js/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
    <script src="../../js/plugins/resizimg/trumbowyg.resizimg.min.js"></script>
    <script src="../../js/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
    <script src="../../js/plugins/emoji/trumbowyg.emoji.min.js"></script>
    <script src="../../js/plugins/colors/trumbowyg.colors.min.js"></script>
    <script src="../../js/plugins/pasteimage/trumbowyg.pasteimage.min.js"></script>
    <script src="../../js/plugins/table/trumbowyg.table.min.js"></script>
    
    <script type="text/javascript">
        const states = [
        <?php foreach($states as $state) { ?>
            {
                id: <?php echo $state->getId(); ?>,
                name: "<?php echo $state->getNome(); ?>"
            },
        <?php } ?>
        ];

        $('#mail-message').trumbowyg({
            lang: 'pt_br',
            btnsDef: {
                createButton: {
                    fn: function() {
                        let btn = $(document.createElement('a')).attr('target', '_blank').css({
                                padding: '10px 20px',
                                color: 'white',
                                backgroundColor: '#0b2437'
                            });
                        
                        let link = prompt('Digite o link desejado:');
                        let texto = prompt('Digite o texto desejado:');
                        btn.attr('href', link);
                        btn.text(texto);
                        $('.trumbowyg-editor').append(btn);
                    },
                    tag: 'a',
                    title: 'Criar botão',
                    text: 'Criar botão',
                    isSupported: function () { return true; },
                    forceCSS: false,
                    hasIcon: false
                }
            },
            btns: [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em', 'del'],
                ['fontsize'],
                ['superscript', 'subscript'],
                ['link'],
                ['insertImage'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['foreColor', 'backColor'],
                ['emoji'],
                ['table'],
                ['createButton'],
                ['fullscreen']
            ]
        });
    </script>
</html>

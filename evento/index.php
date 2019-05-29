<?php
    include_once "../painel/php/config/database.php";
    include_once "../painel/php/model/user.php";
    include_once "../painel/php/model/event.php";
    include_once "../painel/php/model/city.php";
    include_once "../painel/php/model/state.php";
    include_once "../painel/php/model/schedule.php";
    include_once "../painel/php/model/news.php";
    include_once "../painel/php/model/speaker.php";
    include_once "../painel/php/model/partner.php";
    include_once "../painel/php/model/ticket.php";
    include_once "../painel/php/model/assistLink.php";
    include_once "../painel/php/model/eventConfig.php";
    include_once "../painel/php/controller/user.php";
    include_once "../painel/php/controller/event.php";
    include_once "../painel/php/controller/city.php";
    include_once "../painel/php/controller/state.php";
    include_once "../painel/php/controller/schedule.php";
    include_once "../painel/php/controller/news.php";
    include_once "../painel/php/controller/speaker.php";
    include_once "../painel/php/controller/partner.php";
    include_once "../painel/php/controller/ticket.php";
    include_once "../painel/php/controller/assistLink.php";
    include_once "../painel/php/controller/eventConfig.php";

    $database = new Database();

    $conn = $database->getConn();

    $event = $_GET['event'];
    $event = new Event($event, null, null, null, null, null, null, null, null);

    $eventController = new EventController();
    $event = $eventController->getEvent($conn, $event);

    $sql = "select * from location where event_id = :event";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':event', $event->getId());
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $location = $stmt->fetch();

    $stateController = new StateController();
    $states = $stateController->getStates($conn);

    $scheduleController = new ScheduleController();
    $schedule = new Schedule($event->getId(), null, null, null);
    $schedules = $scheduleController->getSchedulesByEvent($schedule, $conn);  
    try {
        $days = $scheduleController->getDaysOfEvent($schedule, $conn);
    } catch(Exception $e) {
        //
    }

    $tickets = new Ticket(null, null, null, null, null, null, $event->getId());
    $ticketController = new TicketController();
    $tickets = $ticketController->getTicketsByEvent($tickets, $conn);
    $unavailableTickets = $ticketController->getUnavailablesTicketsByEvent($tickets[0], $conn);

    if(!empty($unavailableTickets)) {
        foreach ($tickets as $key => $ticket) {
            foreach ($unavailableTickets as $unavailableTicket) {
                if($ticket->getId() == $unavailableTicket->getId()) {
                    unset($tickets[$key]);
                    break;
                }
            }
        }
    }
    /*
    $availablesTickets = $ticketController->getAvailablesTicketsByEvent($tickets[0], $conn);

    if(!empty($availablesTickets)) {
        foreach ($tickets as $key => $ticket) {
            $remove = FALSE;
            foreach ($availablesTickets as $aKey => $aTicket) {
                if($ticket->getId() == $aTicket->getId()) {
                    $remove = FALSE;
                    break;
                } else {
                    $remove = TRUE;
                }
            }

            if($remove) {
                unset($tickets[$key]);
            }
        }
    }
    */

    /**
    * $initialDate = $event->getDate();
    *
    * $firstSchedule = reset($schedules);
    * if($firstSchedule) $initialDate .= " ".$firstSchedule->getTime();
    */
    $initialDate = reset($tickets)->getFinalDate();
 
    $date1 = new DateTime($initialDate." 23:59:59");

    $date2 = new DateTime(date('Y-m-d H:i:s'));
    $interval = $date2->diff($date1);

    //Calculate difference
    $diff = strtotime($initialDate) - time();   //time returns current time in seconds
    $leftDays = $interval->d;        //seconds/minute*minutes/hour*hours/day)

    if($interval->m > 0) $leftDays += ($interval->m * 30);

    $newsController = new NewsController();
    $news = new News(null, null, null, null, $event->getId());
    $news = $newsController->getNewsByEvent($news, $conn);

    $speakerController = new SpeakerController();
    $speakers = new Speaker($event->getId(), null, null, null, null);
    $speakers = $speakerController->getSpeakersByEvent($speakers, $conn);

    $partnerController = new PartnerController();
    $partners = new Partner(null, null, null, $event->getId());
    $partners = $partnerController->getPartnersByEvent($partners, $conn);

    $assistLink = new AssistLink(null, $event->getId());
    $assistLinkController = new AssistLinkController();
    $assistLink = $assistLinkController->getAssistLinkByEvent($assistLink, $conn);

    $eventConfig = new EventConfig($event->getId(), null);
    $eventConfigController = new EventConfigController();
    $eventConfig = $eventConfigController->getEventConfig($eventConfig, $conn);
?>
<html>
    <head>
        <title><?php echo $event->getName();?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
        <link type="text/css" rel="stylesheet" href="../css/slide.css">
        <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
        <noscript class="page-head"></noscript>
    </head>

    <body>
        <div id="loader"><img src="../media/symbol-color.png" class="symbol"></div>

        <header class="light scrolled no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo upfolder" alt="" src="../media/logo-minimal.png" onclick="window.open('../', '_self')">
                </div>
                <div id="navbar">
                    <nav>
                        <ul class="green">
                            <li><a href="#home">início</a></li>
                            <li><a href="#evento">o evento</a></li>
                            <li><a href="#programacao">programação</a></li>
                            <li><a href="#informacoes">informações</a></li>
                            <li><a class="no-link" href="../usuario"><i class="fas fa-user-circle"></i></a></li>
                        </ul>
                    </nav>
                </div>
                <div id="mobile-navbar">
                    <a class="no-link white" href="#n"><span class="fa fa-2x fa-bars"></span></a>
                </div>
            </div>
            <nav id="nav-wrap">
                <a class="no-link green" href="#n"><span class="fa fa-2x fa-times"></span></a>
                <ul class="regular">
                    <li><a href="#home">início</a></li>
                    <li><a href="#evento">o evento</a></li>
                    <li><a href="#programacao">programação</a></li>
                    <li><a href="#informacoes">informações</a></li>
                    <li><a href="../usuario">entrar</a></li>
                </ul>
            </nav>
        </header>

        <!-- WhatsHelp.io widget -->
        <script type="text/javascript">
            (function () {
                var options = {
                    facebook: "1105375109565745", // Facebook page ID
                    whatsapp: "+55 (84) 996055403", // WhatsApp number
                    call_to_action: "Fale conosco!", // Call to action
                    button_color: "#0b2437", // Color of button 
                    position: "right", // Position may be 'right' or 'left'
                    order: "facebook,whatsapp", // Order of buttons
                };
                var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
                var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
                s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
                var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
            })();
        </script>
        <!-- /WhatsHelp.io widget -->

        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Cadastre-se</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="register-form-mobile" class="masked-form" action="../painel/php/operations/user.php" method="post">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <input type="text" name="cpf" id="cpf-mobile" placeholder="CPF" class="col-md-12 col-xs-12 masked-input cpfmask">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <input type="text" name="name" id="name-mobile" placeholder="Nome" class="col-md-12 col-xs-12">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <input type="email" name="email" id="email-mobile" placeholder="E-mail" class="col-md-12 col-xs-12">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <input type="password" name="password" id="password-mobile" placeholder="Senha" class="col-md-12 col-xs-12">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <input type="text" name="phone" id="phone-mobile" placeholder="Telefone" class="col-md-12 col-xs-12 masked-input phonemask">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <input type="text" name="course" id="course-mobile" placeholder="Curso" class="col-md-12 col-xs-12">
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <label for="formation" class="col-form-label">Formação:</label>
                            <select class="custom-select" id="formation-mobile" name="formation" required>
                                <option disabled selected>Selecione</option>
                                <option value="graduado">Graduado</option>
                                <option value="graduando">Graduando</option>
                                <option value="técnico">Técnico</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <label for="estado" class="col-form-label">Estado:</label>
                            <select class="custom-select" id="estado-mobile" name="estado" required>
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
                        <div class="col-md-12 col-xs-12">
                            <button type="submit" value="register" name="btn-register" id="btn-register-mobile" class="regular col-md-12 col-xs-12">Criar minha conta <span class="fa fa-angle-right"></span></button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-register-modal" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            </div>
        </div>
    

        <section id="home" class="panel event" data-image="<?php if(count(explode(';', $event->getFeatured_image())) == 2) { echo explode(';', $event->getFeatured_image())[1]; } elseif(strpos($event->getFeatured_image(), 'background') !== false || strpos($event->getFeatured_image(), 'none') !== false) { echo $event->getFeatured_image(); } else { echo '/painel/uploads/none.png'; } ?>">
            <div id="register-panel">
                <h2 class="title black green">Cadastre-se</h2><h5 class="italic green uppercase">E participe do evento</h5>
                <form id="register-form" class="masked-form" action="../painel/php/operations/user.php" method="post">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <input type="text" name="cpf" id="cpf" placeholder="CPF" class="col-md-12 col-xs-12 masked-input cpfmask">
                    </div>
                    <div class="form-group col-md-12 col-xs-12">
                        <input type="text" name="name" id="name" placeholder="Nome" class="col-md-12 col-xs-12">
                    </div>
                    <div class="form-group col-md-12 col-xs-12">
                        <input type="email" name="email" id="email" placeholder="E-mail" class="col-md-12 col-xs-12">
                    </div>
                    <div class="form-group col-md-12 col-xs-12">
                        <input type="text" name="phone" id="phone" placeholder="Telefone" class="col-md-12 col-xs-12 masked-input phonemask">
                    </div>
                    <div class="form-group col-md-12 col-xs-12">
                        <input type="password" name="password" id="password" placeholder="Senha" class="col-md-12 col-xs-12">
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <button type="submit" value="register" name="btn-register" id="btn-register" class="regular col-md-12 col-xs-12">Criar minha conta <span class="fa fa-angle-right"></span></button>
                    </div>
                </form>
            </div>
            <div id="register-button-mobile">
                <button type="button" class="btn-register btn bold uppercase" data-toggle="modal" data-target="#registerModal">Quero me inscrever</button>
            </div>
            <div class="container">
                <a href="#evento" id="see-more" class="no-link"><span class="fa fa-angle-down"></span></a>
            </div> 
        </section>
        
        <section id="evento" class="panel">
            <div class="container">
                <h2 class="title black green">O evento</h2>
                <hr class="separator">
                <p class="description green"><?php echo $event->getDescription(); ?></p>
                <?php if(!empty($speakers)) { ?>
                    <hr class="separator">
                    <h4 id="know-speakers" class="title black green" style="margin-top: 40px;">Conheça os palestrantes</h2>
                    <div id="arrows">
                        <i class="green fas fa-angle-left"></i>
                        <i class="green fas fa-angle-right"></i>
                    </div>
                    <div id="speakers">
                    <?php
                    $count = 0;
                    foreach ($speakers as $speaker) {
                    ?>
                    <div class="speaker pointer <?php echo $count < 8 ? 'active' : ''; echo $count == 0 ? ' mobile' : ''; $count += 1;?>" <?php if(!empty($speaker->getLink())) { ?> onclick="window.open('<?php echo $speaker->getLink(); ?>', '_blank')" <?php } ?>>
                        <div class="icon"><img src="..<?php echo $speaker->getImage();?>"></div>
                        <h4 class="description bold green"><?php echo $speaker->getName();?></h4>
                        <p class="description green"><?php echo $speaker->getDescription(); ?></p>
                    </div>
                    <?php
                    }
                    ?>
                </div> 
                <?php } ?>
            </div>
        </section>

        <?php if(!empty($location)) { ?>
        <section id="location" class="panel">
            <div class="container">
                <h2 class="title black green" style="margin-bottom: 20px;">local</h2>
                <hr class="separator">
                <?php if($location['link'] != ' ') { ?>
                <div id="location-logo">
                    <img class="thumb" src="<?php echo $location['link']; ?>">
                </div>
                <?php } ?>
                <span id="location-name" class="regular"><?php echo $location['name']; ?></span>
            </div>
        </section>
        <?php } ?>

        <!--
        <section id="programacao" class="panel">
            <div>
                <div class="container">
                    <h2 class="title black green">programação</h2>
                    <hr class="separator">
                </div>
                <div id="slides">
                    <div class="container">
                        <div id="navs">
                            <a id="left-button" href="#p"><span class="fa fa-angle-left"></span></a>
                            <a id="right-button" href="#n"><span class="fa fa-angle-right"></span></a>
                        </div>
                    </div>
                    <?php
                    /*foreach ($days as $day) {
                    ?>
                        <div class="slide container" data-image="">
                            <div class="information-day">
                                <span class="date light"><?php echo $day->getDay(); ?> | <?php echo $day->getFormattedMonth(); ?></span>
                            </div>
                            <div class="slide-day">
                                <?php
                                $schedulesByDay = $scheduleController->getSchedulesByDay($day, $conn);
                                foreach ($schedulesByDay as $schedule) {
                                    $time = explode(':', $schedule->getTime()); 
                                    $time = $time[0]."h".$time[1];
                                    $finalTime = explode(':', $schedule->getFinalTime());
                                    $finalTime = $finalTime[0]."h".$finalTime[1];
                                ?>
                                <div class="slide-act">
                                    <h4 class="act-title uppercase bold"><?php echo $schedule->getTitle(); ?></h4><h4 class="bold uppercase act-hour"><?php echo $time." ~ ".$finalTime; ?></h4>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }*/
                    ?>
                </div>               
            </div>
        </section>
                -->
        <?php if(!empty($days)) { ?>
        <section id="programacao" class="panel">
            <div>
                <div class="container">
                    <h2 class="title black green">programação</h2>
                    <hr class="separator">
                </div>
                <div id="schedule-slides">
                    <nav class="day-selector">
                        <ul class="regular uppercase">
                        <?php
                        $count = 0;
                        foreach ($days as $day) {
                        ?>
                            <li data-day="<?php echo $day->getDate(); ?>" <?php if($count == 0) { ?>class="active"<?php } ?>><?php echo $day->getDay(); ?> | <?php echo $day->getFormattedMonth(); ?></li>
                        <?php
                        $count++; 
                        }
                        ?>
                        </ul>
                    </nav>
                    <?php
                    $count = 0;
                    foreach ($days as $day) {
                    ?>
                    <ul class="day <?php if($count == 0) echo 'active'; ?>" data-day="<?php echo $day->getDate(); ?>">
                        <?php
                        $schedulesByDay = $scheduleController->getSchedulesByDay($day, $conn);
                        foreach ($schedulesByDay as $schedule) {
                            $time = explode(':', $schedule->getTime()); 
                            $time = $time[0]."h".$time[1];
                            $finalTime = explode(':', $schedule->getFinalTime());
                            $finalTime = $finalTime[0]."h".$finalTime[1];
                        ?>
                        <li class="act">
                            <span class="act-title bold uppercase"><?php echo $schedule->getTitle(); ?></span>
                            <span class="act-hour bold green uppercase"><?php echo $time." - ".$finalTime?></span>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                    <?php
                    $count++;
                    }
                    ?>
                </div>               
            </div>
        </section>
        <?php } ?>
        
        <?php if(!empty($tickets)) { ?>
        <section id="entradas" class="panel">
            <div class="container">
                <h2 class="title black green">valores de inscrições</h2>
                <hr class="separator">
                <div id="tickets-panel">
                    <?php 
                    foreach ($tickets as $ticket) {
                        $date = utf8_encode(strftime('%d de %B de %Y', strtotime($ticket->getFinalDate())));
                        $price = number_format($ticket->getPrice(), 2, ',', '.');
                    ?>
                    <div class="ticket-info" onclick="$(body).animate({scrollTop: 0}, 500)">
                        <div class="info">
                            <h4 class="ticket-title uppercase bold"><?php echo $ticket->getName(); ?></h4>
                            <h6 class="ticket-description uppercase regular"><?php echo $ticket->getDescription(); ?></h6>
                            <h6 class="small green uppercase regular">Disponível até <?php echo $date; ?></h6>
                        </div>
                        <div class="price">
                            <h4 class="ticket-title green uppercase bold">R$ <?php echo $price; ?></h4>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                </div>
        </section>
        <?php } ?>
        
        <?php if(!empty($news)) {?>
        <section id="informacoes" class="panel">
            <div class="container">
                <h2 class="title black green">informações de evento</h2>
                <hr class="separator">
                <div id="news-feed">
                    <?php
                    foreach ($news as $notice) {
                        ?>
                    <div class="news pointer col-md-4" data-id="<?php echo $notice->getId(); ?>">
                            <p class="regular"><i class="fas fa-search"></i> <?php echo $notice->getTitle(); ?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <?php } ?>

        <?php if($eventConfig->getTraveling() == 1) { ?>
        <section id="hospedagem" class="panel">
            <h2 class="title black green">passagem e hospedagem</h2>
            <hr class="separator">
            <iframe class="assistLink" src="capturePage.php?link=<?php if($assistLink) echo trim($assistLink->getLink()); ?>" frameborder="0" onload="resizeIframe(this)"></iframe>
        </section>
        <?php } ?>

        <section id="creditos" class="panel">
            <div class="container">
                <?php if(!empty($partners)) { ?>
                <h2 class="title black green">patrocinadores</h2>
                <hr class="separator">
                <div id="partners-logos">
                    <?php foreach($partners as $partner) { ?>
                    <img class="thumb" data-partner-name="<?php echo $partner->getName(); ?>" src="../<?php echo $partner->getImage(); ?>" onclick="window.open('<?php echo $partner->getLink(); ?>', '_blank');">
                    <?php } ?>
                </div>
                <span id="partner-name" class="light">.</span>
                <?php } ?>
                <h2 class="title black green">seja um parceiro</h2>
                <hr class="separator">
                <button type="button" class="btn-partner-modal btn bold uppercase" data-toggle="modal" data-target="#partnerModal">Seja nosso parceiro</button>
            </div>
        </section>

        <div class="modal fade" id="partnerModal" tabindex="-1" role="dialog" aria-labelledby="partnerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="partnerModalLabel">Seja nosso parceiro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="col-md-12" action="../painel/php/operations/send-propose.php" method="post" id="partner-form">
                            <div class="form-group col-md-12">
                                <input type="text" name="name" id="email-name" placeholder="Nome" class="col-md-12 col-sm-12" required>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="email" name="email" id="email-email" placeholder="E-mail" class="col-md-12 col-sm-12" required>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" name="company" id="email-company" placeholder="Empresa" class="col-md-12 col-sm-12" required>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" name="phone" id="email-phone" placeholder="Telefone" class="col-md-12 col-sm-12" required>
                            </div>
                            <input type="hidden" name="event" value="<?php echo $event->getId(); ?>">
                            <input type="hidden" name="subject" value="Proposta de parceria">
                            <div class="form-group col-md-12">
                                <button type="submit" value="send-mail" name="send-mail" id="send-mail" class="regular col-md-12 col-sm-12">Quero receber uma proposta <span class="fa fa-check"></span></button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close-partner-modal" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <section id="contagem" class="panel">
            <div class="container">
                    <h2 class="title black white"><?php if($leftDays >= 0) { ?>Contagem regressiva para o próximo lote<?php } else { ?>Os lotes acabaram<?php } ?></h2>
                <div id="counter" class="black">
                <?php if($leftDays >= 0) { ?>
                    <div><span id="counter-days" class="counter-title"><?php echo $leftDays; ?></span><span>dias</span></div>
                    <div><span id="counter-hours" class="counter-title"><?php if(strlen($interval->h) == 1) { echo "0"; } echo $interval->h; ?></span><span>horas</span></div>
                    <div><span id="counter-min" class="counter-title"><?php if(strlen($interval->i) == 1) { echo "0"; } echo $interval->i; ?></span><span>minutos</span></div>
                    <div><span id="counter-seconds" class="counter-title"><?php if(strlen($interval->s) == 1) { echo "0"; } echo $interval->s; ?></span><span>segundos</span></div>
                <?php } else { ?>
                    <div><span id="counter-days" class="counter-title">0</span><span>dias</span></div>
                    <div><span id="counter-hours" class="counter-title">0</span><span>horas</span></div>
                    <div><span id="counter-min" class="counter-title">0</span><span>minutos</span></div>
                    <div><span id="counter-seconds" class="counter-title">0</span><span>segundos</span></div>
                <?php } ?>
                </div>
                <a href="#home" class="btn-register btn bold uppercase">Quero me inscrever</a>
            </div>
        </section>

        <div id="notice-panel">
            <div id="notice-frame">
                <i class="fas fa-times pointer"></i>
                <h3 class="blue black uppercase"></h3>
                <p class="blue regular"></p>
                <a id="notice-file" class="uppercase green button" href="#">Visualizar anexo</a>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div>
                        <h1 class="black uppercase">contato</h1>
                    </div>
                    <div>
                        <span class="light footer-label">WhatsApp</span>
                        <span class="bold light footer-data">(84) 99605.5403</span>
                    </div>
                    <div>
                        <span class="light footer-label">E-mail</span>
                        <span class="bold light footer-data">contato@iids.com.br</span>
                    </div>
                    <div>
                        <span class="light footer-label">Redes Sociais</span>
                        <span class="bold light footer-data"><i class="fab fa-facebook-f pointer" onclick="window.open('https://www.facebook.com/Instituto-Integrado-de-Desenvolvimento-em-Sa%C3%BAde-IIDS-1105375109565745/', '_blank');"></i> <i class="fab fa-instagram pointer" onclick="window.open('https://www.instagram.com/iidsintegrada/', '_blank');"></i></span>
                    </div>
                </div>
                <div class="row"><span id="credit">Todos os direitos reservados. Desenvolvido por <a href="http://instagram.com/atworkagenciadigital" title="Atwork Agência Digital" target="_blank"><img src="../media/atwork.svg" alt="atwork" class="atwork"></a></span></div>
            </div>
        </footer>
    </body>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../js/functions.js" type="text/javascript"></script>

    <script>
        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }
    </script>
</html>


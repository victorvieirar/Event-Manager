<?php
    include_once "../painel/php/config/database.php";
    include_once "../painel/php/model/user.php";
    include_once "../painel/php/model/event.php";
    include_once "../painel/php/model/city.php";
    include_once "../painel/php/model/state.php";
    include_once "../painel/php/model/schedule.php";
    include_once "../painel/php/model/news.php";
    include_once "../painel/php/model/speaker.php";
    include_once "../painel/php/controller/user.php";
    include_once "../painel/php/controller/event.php";
    include_once "../painel/php/controller/city.php";
    include_once "../painel/php/controller/state.php";
    include_once "../painel/php/controller/schedule.php";
    include_once "../painel/php/controller/news.php";
    include_once "../painel/php/controller/speaker.php";
    
    $database = new Database();
    $conn = $database->getConn();

    $eventController = new EventController();
    $events = $eventController->getAll($conn);

    $citiesController = new CityController();

?>
<html>
    <head>
        <title>NCS</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link type="text/css" rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>

    <body>
        <header class="light scrolled">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" alt="">
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
                    <a class="no-link white" href="#!"><span class="fa fa-2x fa-bars"></span></a>
                </div>
            </div>
            <nav id="nav-wrap">
                <ul class="regular">
                    <li><a href="#home">início</a></li>
                    <li><a href="#evento">o evento</a></li>
                    <li><a href="#programacao">programação</a></li>
                    <li><a href="#informacoes">informações</a></li>
                </ul>
            </nav>
        </header>

        <section id="eventos" class="panel">
            <div class="container">
                <h2 class="title black green">Eventos e cursos</h2>
                <hr class="separator">
                <div id="events">
                    <div class="event col-4">
                        <h4 class="description black"></h4>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div id="footer-sidebar-1" class="col-md-5 col-sm-12 col-xs-12">
                    <span class="black title font-halfx">Fique por dentro</span><span class="light italic white title"> de todas as novidades</span>
                    <form action="php/subscribe.php" method="post">
                        <div class="form-group">
                            <input type="email" placeholder="E-mail" name="email-subscribe" id="email-subscribe" class="col-md-12 col-sm-12 col-xs-12 green-o">
                            <label for="email-subscribe" class="fa fa-angle-right"></label>
                        </div>
                    </form>
                    <nav id="footer-menu">
                        <ul>
                            <li><a href="#home" class="no-link green black title font-halfx">início</a></li>
                            <li><a href="#nos" class="no-link green black title font-halfx">nós</a></li>
                            <li><a href="#eventos" class="no-link green black title font-halfx">eventos</a></li>
                            <li><a href="#parceiros" class="no-link green black title font-halfx">parceiros</a></li>
                            <li><a href="#contato" class="no-link green black title font-halfx">contato</a></li>
                        </ul>
                    </nav>
                </div>
                <div id="footer-sidebar-2" class="col-md-4 col-sm-12 col-xs-12">
                    <span class="black title font-halfx">Redes sociais</span>
                    <a href="#" class="no-link white bold col-md-12 col-sm-12 col-xs-12" id="facebook-button"><span class="fab fa-facebook-f"></span> Facebook</a>
                    <a href="#" class="no-link white bold col-md-12 col-sm-12 col-xs-12" id="instagram-button"><span class="fab fa-instagram"></span> Instagram</a>
                    <span class="small white col-md-12 col-sm-12 col-xs-12" id="footer-email">contato@idsintegrada.com.br</span>
                    <span class="small white col-md-12 col-sm-12 col-xs-12" id="footer-number">+55 84 9.9848.5791</span>
                    <span class="small green col-md-12 col-sm-12 col-xs-12" id="credit">Todos os direitos reservados. Desenvolvido por <a href="#" title="Atwork Agência Digital" target="_blank"><img alt="atwork" class="atwork"></a></span>
                </div>
            </div>
        </footer>
    </body>

    <script src="../js/jquery-3.2.1.js" type="text/javascript"></script>
    <script src="../js/bootstrap.js" type="text/javascript"></script>
    <script src="../js/functions.js" type="text/javascript"></script>
</html>
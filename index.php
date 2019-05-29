<?php
include_once "painel/php/config/database.php";
include_once "painel/php/model/event.php";
include_once "painel/php/model/city.php";
include_once "painel/php/model/state.php";
include_once "painel/php/controller/event.php";
include_once "painel/php/controller/city.php";
include_once "painel/php/controller/state.php";

$database = new Database();
$conn = $database->getConn();

$eventController = new EventController();
$events = $eventController->getAll($conn);

$citiesController = new CityController();
$stateController = new StateController();

session_start();
?>
<html>

<head>
    <title>Instituto Integrado de Desenvolvimento em Saúde</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/styles.css?<?php echo filemtime("css/styles.css") ?>">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
</head>

<body>
    <div id="loader"><img src="media/symbol-color.png" class="symbol"></div>

    <header class="light">
        <div class="container">
            <div id="logo-container">
                <img class="logo" src="media/logo.png" alt="">
            </div>
            <div id="navbar">
                <nav>
                    <ul class="green">
                        <li><a class="no-link" href="#home"><i class="fas fa-home"></i></a></li>
                        <li><a href="#nos">nós</a></li>
                        <li><a href="#eventos">eventos e cursos</a></li>
                        <li><a href="#pos-graduacao">pós-graduação</a></li>
                        <!--<li><a href="#parceiros">parceiros</a></li>-->
                        <li><a href="#contato">contato</a></li>
                        <li><a class="no-link" href="usuario"><i class="fas fa-user-circle"></i></a></li>
                    </ul>
                </nav>
            </div>
            <div id="mobile-navbar">
                <a class="no-link white" href="#!"><span class="fa fa-2x fa-bars"></span></a>
            </div>
        </div>
        <nav id="nav-wrap">
            <a class="no-link green" href="#n"><span class="fa fa-2x fa-times"></span></a>
            <ul class="regular">
                <li><a href="#home"><i class="fas fa-home"></i> início</a></li>
                <li><a href="#nos">nós</a></li>
                <li><a href="#eventos">eventos e cursos</a></li>
                <li><a href="#pos-graduacao">pós-graduação</a></li>
                <!--<li><a href="#parceiros">parceiros</a></li>-->
                <li><a href="#contato">contato</a></li>
                <li><a class="no-link" href="usuario"><i class="fas fa-user-circle"></i> entrar</a></li>
            </ul>
        </nav>
    </header>

    <!-- WhatsHelp.io widget -->
    <script type="text/javascript">
        (function() {
            var options = {
                facebook: "1105375109565745", // Facebook page ID
                whatsapp: "+55 (84) 996055403", // WhatsApp number
                call_to_action: "Fale conosco!", // Call to action
                button_color: "#0b2437", // Color of button 
                position: "right", // Position may be 'right' or 'left'
                order: "facebook,whatsapp", // Order of buttons
            };
            var proto = document.location.protocol,
                host = "whatshelp.io",
                url = proto + "//static." + host;
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = url + '/widget-send-button/js/init.js';
            s.onload = function() {
                WhWidgetSendButton.init(host, proto, options);
            };
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        })();
    </script>
    <!-- /WhatsHelp.io widget -->

    <div id="dialogBox" class="<?php if (isset($_SESSION['msg'])) {
                                    echo 'active';
                                } ?>">
        <div class="frame">

            <i class="fas fa-times"></i><br>
            <i class="description green fas fa-exclamation-circle"></i><br />
            <p class="message">
                <?php if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                } ?>
            </p>
        </div>
    </div>

    <section id="home" class="panel">
        <div class="container">
            <img class="ncs-symbol" src="media/symbol.png">
            <a href="#nos" id="see-more" class="no-link"><span class="fa fa-angle-down"></span></a>
        </div>
    </section>

    <section id="nos" class="panel">
        <div class="container">
            <h2 class="title black green">quem somos?</h2>
            <hr class="separator">
            <p class="description green">O Instituto de Desenvolvimento em Saúde é uma empresa de eventos, consultoria, pós-graduação e formação continuada, com ênfase nas áreas de Nutrição, Medicina e Educação física. Somos uma empresa fundada em 2015 que vem mudando o cenário de cursos e eventos científicos no Brasil. Apesar de sermos uma equipe jovem, somos apaixonados por saúde, bem-estar e sustentabilidade e queremos levar o que há de mais atual para você!</p>
            <!--
                <div id="features">
                    <div class="featured col-md-3 col-xs-12">
                        <div class="icon"></div>
                        <p class="description green">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                    <div class="featured col-md-3 col-xs-12">
                        <div class="icon"></div>
                        <p class="description green">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                    <div class="featured col-md-3 col-xs-12">
                        <div class="icon"></div>
                        <p class="description green">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                    <div class="featured col-md-3 col-xs-12">
                        <div class="icon"></div>
                        <p class="description green">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                </div>
                -->
        </div>
        <div id="background-about"></div>
    </section>

    <section id="eventos" class="panel">
        <div>
            <div class="container">
                <h2 class="title black green">eventos e cursos</h2>
                <hr class="separator">
            </div>
            <div id="events-cards" class="container">
                <?php
                foreach ($events as $event) {
                    $date = strtotime($event->getDate());
                    $endDate = strtotime($event->getEndDate());

                    if ($date != $endDate) {
                        if (strftime("%B", $date) == strftime("%B", $endDate)) {
                            $date = utf8_encode(strftime("%d", $date)) . " a ";
                            $endDate = utf8_encode(strftime("%d de %B de %Y", $endDate));
                        } else {
                            $date = utf8_encode(strftime("%d de %B", $date)) . " a ";
                            $endDate = utf8_encode(strftime("%d de %B de %Y", $endDate));
                        }
                        $date = $date . $endDate;
                    } else {
                        $date = utf8_encode(strftime("%d de %B de %Y", $date));
                    }

                    $city = $citiesController->getCityById($conn, new City($event->getCity(), null, null));
                    $cityName = $city->getNome();
                    $stateName = $stateController->getStateById(new State($city->getEstado(), null, null, null), $conn)->getUf();
                    ?>
                    <div class="card fixed-height">
                        <img class="card-img-top" src="<?php if (count(explode(';', $event->getFeatured_image())) == 2) {
                                                            echo explode(';', $event->getFeatured_image())[0];
                                                        } elseif (strpos($event->getFeatured_image(), 'featured') !== false || strpos($event->getFeatured_image(), 'none') !== false) {
                                                            echo $event->getFeatured_image();
                                                        } else {
                                                            echo 'painel/uploads/none.png';
                                                        } ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title black"><?php echo $event->getName(); ?></h5>
                            <p class="card-text"><?php echo "$date em $cityName/$stateName" ?></p>
                            <a href="evento/<?php echo $event->getId(); ?>" class="btn btn-primary col-12">Ver evento</a>
                        </div>
                    </div>
                <?php
            }
            ?>
            </div>
            <!--
                <div id="slides">
                    <div class="container">
                        <div id="navs">
                            <a id="left-button" href="#p"><span class="fa fa-angle-left"></span></a>
                            <a id="right-button" href="#n"><span class="fa fa-angle-right"></span></a>
                        </div>
                    </div>
                    <?php
                    /*foreach ($events as $event) {
                    ?>
                    <div class="slide" data-image="<?php echo $event->getFeatured_image(); ?>">
                        <div class="information">
                            <span class="location light"><?php echo $citiesController->getCityById($conn, new City($event->getCity(), null, null))[0]->getNome();?></span>
                            <a href="evento/?event=<?php echo $event->getId(); ?>" class="name black no-link"><?php echo $event->getName();?></a>
                            <p class="description regular"><?php echo $event->getDescription(); ?></p>
                        </div>
                    </div>
                    <?php
                    }*/
                    ?>
                </div>  
                -->
        </div>
    </section>

    <section id="pos-graduacao" class="panel">
        <div class="container">
            <h2 class="title black green">pós-graduação</h2>
            <hr class="separator">
            <p class="description green">Os Cursos de Pós-graduação são oferecidos na modalidade presencial, com encontros duas vezes por mês em diversas cidades do país. Na metodologia adotada o aluno será avaliado nos encontros presenciais e poderá acompanhar as notas obtidas através do AVA virtual de Aprendizagem.</p>
            <a id="pos-button" href="posgraduacoes" class="button green">Ver cursos de Pós-graduação</a>
            <span id="partner-name" class="light"></span>
        </div>
    </section>

    <!--
        <section id="parceiros" class="panel">
            <div class="container">
                <h2 class="title black green">parceiros</h2>
                <hr class="separator">
                <div id="features">
                    <div class="featured col-md-3 col-sm-6 col-xs-12">
                        <div class="icon" data-partner-name="empresa a"></div>
                    </div>
                </div>
                <span id="partner-name" class="light"></span>
            </div>
        </section>
        -->

    <section id="contato" class="panel">
        <div class="container">
            <h2 class="title black green">dúvidas? fale conosco!</h2>
            <hr class="separator">
            <form action="painel/php/operations/send-mail.php" method="post">
                <div class="form-group col-md-12">
                    <input type="email" name="email" id="email" placeholder="E-mail" class="col-md-4 offset-md-4 col-sm-12" required>
                </div>
                <div class="form-group col-md-12">
                    <input type="text" name="name" id="name" placeholder="Nome" class="col-md-4 offset-md-4 col-sm-12" required>
                </div>
                <div class="form-group col-md-12">
                    <input type="text" name="subject" id="subject" placeholder="Assunto" class="col-md-4 offset-md-4 col-sm-12" required>
                </div>
                <div class="form-group col-md-12">
                    <textarea name="message" id="message" placeholder="Mensagem" class="col-md-4 offset-md-4 col-sm-12" rows="10" required></textarea>
                </div>
                <div class="form-group col-md-12">
                    <button type="submit" value="send-mail" name="send-mail" id="send-mail" class="regular col-md-4 offset-md-4 col-sm-12">Enviar <span class="fa fa-angle-right"></span></button>
                </div>
            </form>
        </div>
    </section>

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
            <div class="row"><span id="credit">Todos os direitos reservados. Desenvolvido por <a href="http://instagram.com/atworkagenciadigital" title="Atwork Agência Digital" target="_blank"><img alt="atwork" src="media/atwork.svg" class="atwork"></a></span></div>
        </div>
    </footer>
</body>

<script src="js/jquery-3.2.1.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/jquery.mask.min.js" type="text/javascript"></script>
<script src="js/functions.js" type="text/javascript"></script>

</html>
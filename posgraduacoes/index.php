<?php
    include_once "../painel/php/config/database.php";
    include_once "../painel/php/model/postgraduation.php";
    include_once "../painel/php/controller/postgraduation.php";
    
    $database = new Database();
    $conn = $database->getConn();

    $postGraduationController= new PostGraduationController();
    $postGraduations = $postGraduationController->getAll($conn);

?>
<html>
    <head>
        <title>Instituto Integrado de Desevolvimento em Saúde</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="../css/styles.css">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    </head>

    <body>
        <header class="light scrolled">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" src="../media/logo-minimal.png" alt="" onclick="window.open('../', '_self');">
                </div>
                <div id="navbar">
                    <nav>
                        <ul class="green">
                            <li><a href="../">início</a></li>
                            <li><a href="../#nos">nós</a></li>
                            <li><a href="../#eventos">eventos e cursos</a></li>
                            <li><a href="#">pós-graduações</a></li>
                            <li><a href="../#contato">contato</a></li>
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
                    <li><a href="../">início</a></li>
                    <li><a href="../#nos">nós</a></li>
                    <li><a href="../#eventos">eventos e cursos</a></li>
                    <li><a href="#">pós-graduações</a></li>
                    <li><a href="../#contato">contato</a></li>
                    <li><a class="no-link" href="../usuario"><i class="fas fa-user-circle"></i></a></li>
                </ul>
            </nav>
        </header>

        <section id="posgraduacoes" class="panel" style="margin-top: 130px">
            <div class="container">
                <h2 class="title black green">Pós-graduações</h2>
                <hr class="separator">
                <div id="postgraduations-cards" class="container">
                    <div class="card-columns">
                        <?php
                        foreach ($postGraduations as $postGraduation) {
                        ?>
                        <div class="card">
                            <img class="card-img-top" src="../<?php echo $postGraduation->getFeatured_image(); ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $postGraduation->getName(); ?></h5>
                                <p class="card-text"><?php echo $postGraduation->getDescription(); ?></p>
                                <a target="_blank" href="<?php echo $postGraduation->getLink(); ?>" class="btn btn-primary col-12"><i class="fas fa-external-link-square-alt"></i> Acessar</a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
<!--
        <section id="calendarioacademico" class="panel">
            <div class="container">
                <h2 class="title black green">Calendário acadêmico</h2>
                <hr class="separator">
                <p class="description green">Acesse o calendário acadêmico e fique por dentro de tudo que acontecerá durante o ano.</p>
                <a id="calendar-button" href="../calendar/calendar.pdf" class="button green">Ver calendário</a>
                <span id="partner-name" class="light"></span>
            </div>
        </section>
                    -->
        <section id="matricula" class="panel">
            <div class="container">
                <h2 class="title black green">O que levar para a matrícula?</h2>
                <hr class="separator">
                <p id="items" class="description green">
                Os documentos obrigatórios que deverão ser entregues no ato da matrícula são:
                </p>

                <li class="green">2 cópias simples do RG e CPF (não pode ser CNH e Carteira Profissional);</li>
                <li class="green">2 cópias autenticadas do Diploma de Graduação;</li>
                <li class="green">2 cópias simples do Histórico de Graduação;</li>
                <li class="green">2 cópias simples do Título de Eleitor;</li>
                <li class="green">2 cópias simples do certificado de reservista (apenas para homens liberados do serviço militar);</li>
                <li class="green">2 cópias do comprovante de residência atual ( caso o comprovante não esteja no seu nome, entregar um via de declaração a próprio punho do titular)</li>
                <li class="green">2 cópias simples do registro civil (certidão de nascimento ou casamento).</li>
                <li class="green">1 via do atestado com previsão de formatura (apenas para alunos especiais);</li>

                <p class="description green">Qualquer dúvida, estamos à disposição para esclarecer.<br>Seja bem vindo ao <b>IIDS INTEGRADA</b></p>

                <span id="partner-name" class="light"></span>
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
                <div class="row"><span id="credit">Todos os direitos reservados. Desenvolvido por <a href="http://instagram.com/atworkagenciadigital" title="Atwork Agência Digital" target="_blank"><img alt="atwork" class="atwork" src="../media/atwork.svg"></a></span></div>
            </div>
        </footer>
    </body>

    <script src="../js/jquery-3.2.1.js" type="text/javascript"></script>
    <script src="../js/bootstrap.js" type="text/javascript"></script>
    <script src="../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../js/functions.js" type="text/j
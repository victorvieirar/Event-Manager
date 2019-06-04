<?php
include_once "../../painel/php/config/database.php";
include_once "../../painel/php/model/user.php";
include_once "../../painel/php/model/state.php";
include_once "../../painel/php/model/city.php";
include_once "../../painel/php/model/event.php";
include_once "../../painel/php/model/submission.php";
include_once "../../painel/php/model/type.php";
include_once "../../painel/php/model/certified.php";
include_once "../../painel/php/controller/subscribes.php";
include_once "../../painel/php/controller/submission.php";
include_once "../../painel/php/controller/type.php";
include_once "../../painel/php/controller/state.php";
include_once "../../painel/php/controller/event.php";
include_once "../../painel/php/controller/certified.php";

session_start();

if (!isset($_SESSION["user"])) {
    session_destroy();
    header('location: ../');
} elseif (isset($_GET["event"])) {
    $event = new Event($_GET["event"], null, null, null, null, null, null, null, null);

    $user = $_SESSION['user'];

    $database = new Database();
    $conn = $database->getConn();

    $stateController = new StateController();
    $states = $stateController->getStates($conn);

    $eventController = new EventController();
    $event = $eventController->getEvent($conn, $event);

    $typeController = new TypeController();
    $types = new Type(null, null, $event->getId());
    $types = $typeController->getEventTypes($conn, $types);

    $typesTagged = array();
    foreach ($types as $type) {
        $typesTagged[$type->getId()] = $type->getName();
    }

    $subscribesController = new SubscribesController();
    $subscribedEvents = $subscribesController->getSubscribedEvents($user, $conn);

    $isParticipant = false;
    foreach ($subscribedEvents as $subscribedEvent) {
        if ($subscribedEvent['access'] == 1 && $subscribedEvent['event_id'] == $event->getId()) {
            $isParticipant = true;
        }
    }

    if (!$isParticipant) {
        session_write_close();
        header('location: ../');
    }

    $submissionController = new SubmissionController();
    $submissions = $submissionController->getUserSubmissions($conn, $_SESSION['user'], $event);

    $certifiedController = new CertifiedController();
    $certified = $certifiedController->getCertifiesByUserEvent($conn, $user, $event);
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
    </head>

    <body>
        <header class="light scroll no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" src="../media/logo-minimal.png" alt="">
                </div>
                <div id="mobile-navbar">
                    <a class="no-link white" href="#n"><span class="fa fa-2x fa-bars"></span></a>
                </div>
            </div>
            <nav id="nav-wrap">
                <ul class="regular">
                    <li><a class="nav-link regular btn-page" href="../">Meu painel</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#resume-page">Início</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#my-submissions">Meus trabalhos</a></li>
                    <li><a class="nav-link regular btn-page" href="#" page="#my-certificates">`cados</a></li>
                    <br>
                    <li><a class="nav-link regular" href="logout.php">Sair <span class="fas fa-sign-out-alt"></span></a></li>
                </ul>
            </nav>
        </header>
        <div id="dialogBox" class="<?php if (isset($_SESSION['message'])) {
                                        echo 'active';
                                    } ?>">
            <div class="frame">
                <i class="fas fa-times"></i><br>
                <i class="description green fas fa-exclamation-circle"></i><br />
                <p class="message">
                    <?php if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    } ?>
                </p>
            </div>
        </div>

        <div class="sidebar">
            <p class="light">Olá, <br><b><?php echo explode(" ", $user->getName())[0]; ?></b></p>
            <nav class="nav flex-column">
                <a class="nav-link regular btn-page" href="../">Meu painel</a>
                <a class="nav-link regular btn-page" href="#" page="#resume-page">Início</a>
                <a class="nav-link regular btn-page" href="#" page="#my-submissions">Meus trabalhos</a>
                <a class="nav-link regular btn-page" href="#" page="#my-certificates">Meus certificados</a>
                <!-- <a class="nav-link regular btn-page" href="#" page="#event-page">Minicursos</a> -->
                <!-- <a class="nav-link regular btn-page" href="#" page="#my-submissions">Participantes</a> -->
                <!-- <a class="nav-link regular btn-page" href="#" page="#financial-page">Financeiro</a> -->
                <!-- <a class="nav-link regular btn-page" href="#" page="#schedule-page">Programação</a> -->
                <!-- <a class="nav-link regular btn-page" href="#" page="#mailing-page">Mailing</a> -->
                <!-- <a class="nav-link regular btn-page" href="#" page="#news-page">Notícias</a> -->
                <br>
                <a class="nav-link regular" href="../logout.php">Sair <span class="fas fa-sign-out-alt"></span></a>
            </nav>
        </div>

        <section id="main-menu">
            <div class="visor" class="container">
                <div id="resume-page" class="active page">
                    <h5 class="black green"><?php echo $event->getName(); ?></h5>
                    <?php if ($event->getAllow_submissions()) { ?><a href="#" class="button link btn-page regular" page="#my-submissions"><span class="fas fa-angle-right"></span> Meus trabalhos</a><?php } ?>
                    <a href="#" class="button link btn-page regular" page="#my-certificates"><span class="fas fa-angle-right"></span> Meus certificados</a>
                </div>
                <?php if ($event->getAllow_submissions()) { ?>
                    <div id="my-submissions" class="page">
                        <h5 class="black green">Minhas submissões</h5>
                        <table>
                            <thead class="bold">
                                <tr>
                                    <td width="40%">Título</td>
                                    <td width="30%">Área de Submissão</td>
                                    <td width="20%">Status</td>
                                    <td width="10%">Ações</td>
                                </tr>
                            </thead>
                            <tbody class="regular">
                                <?php
                                foreach ($submissions as $submission) {
                                    $status = $submission->getStatus();
                                    switch ($status) {
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
                                        <td><?php echo $typesTagged[$submission->getType()]; ?></td>
                                        <td><i class="fas fa-circle <?php echo $class; ?>"></i> <?php echo $text; ?></td>
                                        <td onclick="window.open('<?php echo $submission->getFile(); ?>', '_blank');"><i class="fas fa-search pointer"></i></td>
                                    </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                        <h5 class="black green">Submeter trabalho</h5>
                        <form action="../../painel/php/operations/submissions.php" method="post" class="col-12" id="submission-form" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-md-6" id="submission-title" name="submission-title" placeholder="Título" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea class="form-control col-md-6" id="submission-description" name="submission-description" placeholder="Resumo" required></textarea>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-md-6" id="submission-keywords" name="submission-keywords" placeholder="Palavras-chave (separados por vírgula)" required>
                                <label for="submission-authors" class="regular green small">Ex.: Palavra-chave 1, Palavra-chave 2</label>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-md-6" id="submission-author" placeholder="Autor" required>
                            </div>
                            <div class="form-group col-12">
                                <select class="custom-select col-md-6" id="submission-type" name="submission-type">
                                    <option selected>Área de Submissão</option>
                                    <?php foreach ($types as $type) { ?>
                                        <option value="<?php echo $type->getId(); ?>"><?php echo $type->getName(); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12" id="submission-file-div">
                                <div class="form-group custom-file col-md-6">
                                    <input type="file" class="custom-file-input" id="submission-file" name="submission-file" lang="pt-br">
                                    <label class="custom-file-label" for="submission-file">Arquivo</label>
                                </div>
                            </div>
                            <hr>
                            <h6 class="col-12 black uppercase green">Co-autores</h6>
                            <div id="co-authors-group">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control col-md-6" id="submission-authors-name" placeholder="Nome do co-autor">
                                </div>
                                <div class="form-group col-12">
                                    <input type="email" class="form-control col-md-6" id="submission-authors-email" placeholder="E-mail do co-autor">
                                </div>
                                <div class="form-group col-12">
                                    <button type="button" class="btn btn-primary col-md-6" id="add-author-button">Adicionar</button>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" value="<?php echo $event->getId(); ?>" name="event-id">
                            <input type="hidden" value="<?php echo $user->getCpf(); ?>" name="user-cpf">
                            <div class="col-12">
                                <button type="submit" class="col-md-6 btn btn-success" id="submission-button" name="submission-button">Submeter trabalho <span class="fas fa-check"></span></button>
                            </div>
                        </form>
                    </div>
                    <div id="my-certificates" class="page">
                        <h5 class="black green">Meus certificados</h5>
                        <?php
                        if ($certified) { ?>
                            <a target="_blank" href="../../painel/php/operations/pdf/<?php echo $certified->getLink() . ".pdf"; ?>" class="no-link"><button class='btn btn-large' type='submit' id="event-certified-btn" name="event-certified-btn">Baixar certificado <i class="fas fa-download"></i></button></a>
                        <?php } else { ?>
                            <p class="green">Você não possui certificados nesse curso. Tente novamente mais tarde! :(</p>
                        <?php } ?>
                    </div>
                </div>
            </section>
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
<?php } ?>
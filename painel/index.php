<?php
include_once "php/config/database.php";
include_once "php/model/admin.php";
include_once "php/model/state.php";
include_once "php/model/city.php";
include_once "php/model/event.php";
include_once "php/model/postgraduation.php";
include_once "php/controller/postgraduation.php";
include_once "php/controller/state.php";
include_once "php/controller/event.php";

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
                    <img class="logo" src="../media/logo-minimal.png" alt="">
                </div>
            </div>
        </header>

        <div id="dialogBox" class="<?php if(isset($_SESSION['message'])) { echo 'active'; } ?>">
            <div class="frame">
                
                <i class="fas fa-times"></i><br>
                <i class="description green fas fa-exclamation-circle"></i><br/>
                <p class="message">
                    <?php if(isset($_SESSION['message'])) { echo $_SESSION['message']; unset($_SESSION['message']); } ?>
                </p>
            </div>
        </div>
        <?php
        if(!isset($_SESSION["admin"])) {
        ?>
        <section id="login" class="panel">
            <div class="container">
                <h2 class="title black green">área administrativa</h2>
                <form action="php/authenticate/auth.php" method="post">
                    <div class="form-group col-md-12">
                        <input type="text" name="user" id="user" placeholder="Usuário" class="col-md-4 offset-md-4 col-sm-12">
                    </div>
                    <div class="form-group col-md-12">
                        <input type="password" name="password" id="password" placeholder="Senha" class="col-md-4 offset-md-4 col-sm-12">
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" value="login" name="btn-login" id="btn-login" class="regular col-md-4 offset-md-4 col-sm-12">Entrar <span class="fa fa-angle-right"></span></button>
                    </div>
                </form>
            </div>
        </section>
        <?php
        } else {
            $admin = $_SESSION['admin'];

            $database = new Database();
            $conn = $database->getConn();
            
            $stateController = new StateController();
            $states = $stateController->getStates($conn);

            $eventController = new EventController();
            $events = $eventController->getAll($conn);

            $postGraduationController = new PostGraduationController();
            $postGraduations = $postGraduationController->getAll($conn);
        ?>
            <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Novo evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="createEventForm">
                            <div class="form-group">
                                <label for="event-name" class="col-form-label">Nome:</label>
                                <input type="text" class="form-control" id="event-name" name="event-name" required>
                            </div>
                            <div class="form-group">
                                <label for="event-date" class="col-form-label">Data inicial:</label>
                                <input type="date" class="form-control" id="event-date" name="event-date" required>
                            </div>
                            <div class="form-group">
                                <label for="event-end-date" class="col-form-label">Data final:</label>
                                <input type="date" class="form-control" id="event-end-date" name="event-end-date" required>
                            </div>
                            <div class="form-group">
                                <label for="event-state" class="col-form-label">Estado:</label>
                                <select class="custom-select" id="event-state" name="event-state" required>
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
                            <div class="form-group">
                                <label for="event-city" class="col-form-label">Cidade:</label>
                                <select class="custom-select" id="event-city" name="event-city" disabled required>
                                    <option disabled selected>Selecione</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close-event-modal" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-success" id="submit-event">Criar</button>
                    </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <p class="light">Olá, <br><b><?php echo explode(" ",$admin->getName())[0];?></b></p>
                <nav class="nav flex-column">
                    <a class="nav-link regular btn-page" href="#" page="#events-page">Eventos</a>
                    <a class="nav-link regular btn-page" href="#" page="#postgraduation-page">Pós-graduação</a>
                    <br>
                    <a class="nav-link regular" href="php/authenticate/logout.php">Sair <span class="fas fa-sign-out-alt"></span></a>
                </nav>
            </div>
            
            <section id="main-menu">   
                <div class="visor" class="container">
                    <div id="events-page" class="page active">
                        <h5 class="black green">Meus eventos</h5>
                        <div id="events">
                            <?php
                            foreach ($events as $event) {
                            ?>
                            <button type="button" class="event button green" id="<?php echo $event->getId(); ?>"><?php echo $event->getName(); ?></button>
                            <?php
                            }
                            ?>
                        </div>
                        <div id="create-event">
                            <button type="button" class="button green" data-toggle="modal" data-target="#createEventModal"><span class="fa fa-plus"></span> Criar evento</button>
                        </div>
                    </div>

                    <div id="postgraduation-page" class="page">
                        <h5 class="black green">Pós-graduação</h5>
                        <form action="php/operations/postgraduation.php" method="post" id="postgraduation-form" class="col-12" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-6" id="postgraduation-name" name="postgraduation-name" placeholder="Título" required>
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control col-6" id="postgraduation-link" name="postgraduation-link" placeholder="URL de redirecionamento" required>
                            </div>
                            <div class="form-group col-12">
                                <textarea type="text" class="form-control col-6" id="postgraduation-description" name="postgraduation-description" placeholder="Descrição" required></textarea>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-file col-6">
                                    <input type="file" class="custom-file-input" id="postgraduation-image" name="postgraduation-image" lang="pt-br">
                                    <label class="custom-file-label" for="postgraduation-image">Imagem de destaque</label>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <button type="submit" class="col-6 btn btn-success" id="add-postgraduation-button" name="add-postgraduation-button">Salvar <span class="fas fa-check"></span></button>
                            </div>
                        </form>
                        <h5 class="black green">Calendário acadêmico</h5>
                        <form action="php/operations/academiccalendar.php" method="post" id="calendar-form" class="col-12" enctype="multipart/form-data">
                            <div class="form-group col-12">
                                <div class="custom-file col-6">
                                    <input type="file" class="custom-file-input" id="calendar-file" name="calendar-file" lang="pt-br">
                                    <label class="custom-file-label" for="calendar-file">Arquivo do calendário</label>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <button type="submit" class="col-6 btn btn-success" id="add-calendar-button" name="add-calendar-button">Salvar <span class="fas fa-check"></span></button>
                            </div>
                        </form>
                        <h5 class="black green">Pós-graduações cadastradas</h5>
                        <table id="postgraduations-table">
                            <thead class="bold">
                                <tr>
                                    <td width="40%">Nome</td>
                                    <td width="40%">Descrição</td>
                                    <td width="20%">Ação</td>
                                </tr>
                            </thead>
                            <tbody class="regular">
                                <?php
                                foreach ($postGraduations as $postGraduation) {
                                ?>
                                <tr>
                                    <td><?php echo $postGraduation->getName(); ?></td>
                                    <td><?php echo $postGraduation->getDescription(); ?></td>
                                    <td data-id="<?php echo $postGraduation->getId(); ?>"><i class="red pointer fas fa-trash"></i> <i class="green pointer fas fa-pencil-alt"></i></td>
                                </tr> 
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <div id="editPostGraduation">
                <div class="container">
                    <form action="php/operations/postgraduation.php" method="post" id="postgraduation-form" class="col-12" enctype="multipart/form-data">
                        <div class="form-group col-12">
                            <input type="text" class="form-control col-12" id="postgraduation-name" name="postgraduation-name" placeholder="Título" required>
                        </div>
                        <div class="form-group col-12">
                            <input type="text" class="form-control col-12" id="postgraduation-link" name="postgraduation-link" placeholder="URL de redirecionamento" required>
                        </div>
                        <div class="form-group col-12">
                            <textarea type="text" class="form-control col-12" id="postgraduation-description" name="postgraduation-description" placeholder="Descrição" required></textarea>
                        </div>
                        <div class="form-group col-12">
                            <div class="custom-file col-12">
                                <input type="file" class="custom-file-input" id="postgraduation-image" name="postgraduation-image" lang="pt-br">
                                <label class="custom-file-label" for="postgraduation-image">Imagem de destaque</label>
                            </div>
                        </div>
                        <input type="hidden" id="postgraduation-id" name="postgraduation-id">
                        <div class="form-group col-12">
                            <button type="submit" class="col-12 btn btn-success" id="update-postgraduation-button" name="update-postgraduation-button">Salvar <span class="fas fa-check"></span></button>
                        </div>
                        <div class="form-group col-12">
                            <button type="button" class="col-12 btn btn-danger" id="cancel-postgraduation-button"><span class="fas fa-times"></span> Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
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

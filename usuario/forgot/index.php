<?php
    
require_once "../../painel/php/model/user.php";    
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
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>

    <body>
        <header class="light scroll no-animation">
            <div class="container">
                <div id="logo-container">
                    <img class="logo" src="../../media/logo-minimal.png" alt="">
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
                <form action="../../painel/php/operations/user.php" method="post">
                    <div class="form-group col-md-12">
                        <input type="text" name="cpf" id="cpf" placeholder="CPF" class="col-md-4 offset-md-4 col-sm-12">
                    </div>
                    <div class="form-group col-md-12">
                        <input type="email" name="email" id="email" placeholder="E-mail" class="col-md-4 offset-md-4 col-sm-12">
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" value="forgot" name="btn-forgot" id="btn-forgot" class="regular col-md-4 offset-md-4 col-sm-12">Recuperar <span class="fa fa-angle-right"></span></button>
                    </div>
                </form>
            </div>
        </section>
        <?php 
        } else {
            header('location: ../');
        }
        ?>
    </body>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="../../js/jquery.mask.min.js" type="text/javascript"></script>
    <script src="../../js/functions.js" type="text/javascript"></script>
</html>

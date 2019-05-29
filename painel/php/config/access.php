<?php

session_start();
if(!isset($_SESSION['user'])) {
    header('location: http://iids.com.br/');
}

?>
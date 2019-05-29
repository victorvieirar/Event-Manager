<?php

session_start();
if(!isset($_SESSION['admin'])) {
    header('location: http://iids.com.br/');
}

?>
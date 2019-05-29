<?php
session_start();

include_once '../config/database.php';
include_once '../model/coupon.php';
include_once '../controller/coupon.php';

if(isset($_POST['add-coupon-button']) && isset($_SESSION['admin'])) {
    $database = new Database();
    $conn = $database->getConn();

    $couponController = new CouponController();

    $code = isset($_POST['coupon-code']) ? $_POST['coupon-code'] : '';
    $discount = isset($_POST['coupon-discount']) ? $_POST['coupon-discount'] : '';
    $event = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($code) || empty($discount) || empty($event)) {
        $message = "Desculpe, mas sentimos falta de alguns dados, insira os dados corretamente e tente novamente.";
        $_SESSION['message'] = $message;
        header('location: ../../event/?event='.$event);
    }   

    $coupon = new Coupon($code, $discount, $event);
}
?>
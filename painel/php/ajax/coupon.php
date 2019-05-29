<?php

include_once '../config/database.php';
include_once '../model/coupon.php';
include_once '../controller/coupon.php';

session_start();
if(isset($_POST['coupon'])) {
    $database = new Database();
    $conn = $database->getConn();

    $code = isset($_POST['name']) ? $_POST['name'] : '';
    $discount = isset($_POST['discount']) ? $_POST['discount'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($code) || empty($discount)) {
        echo json_encode(array('success' => false, 'message' => 'Dados não preenchidos corretamente'));
        exit;
    }

    $couponController = new CouponController();
    $coupon = new Coupon($code, $discount, $event_id);
    $success = $couponController->saveCoupon($coupon, $conn);
    
    echo json_encode(array('success' => $success));
} elseif(isset($_POST['delete'])) { 
    $database = new Database();
    $conn = $database->getConn();

    $code = isset($_POST['name']) ? $_POST['name'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';

    if(empty($code) || empty($event_id)) {
        echo json_encode(array('success' => false, 'message' => 'Erro ao deletar, atualize a página e tente novamente'));
        exit;
    }

    $couponController = new CouponController();
    $coupon = new Coupon($code, null, $event_id);
    $success = $couponController->deleteCoupon($coupon, $conn);

    echo json_encode(array('success' => $success));
} elseif(isset($_POST['get'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $code = isset($_POST['code']) ? $_POST['code'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    
    if(empty($code) || empty($event_id)) {
        echo json_encode(array('success' => false, 'message' => 'Erro ao procurar cupom. Tente novamente mais tarde'));
        exit;
    }
    
    $couponController = new CouponController();
    $coupon = new Coupon($code, null, $event_id);
    $coupon = $couponController->getCoupon($coupon, $conn);
    
    if($coupon) echo json_encode(array('success' => true, 'coupon'=>$coupon));
    else echo json_encode(array('success' => false));
} elseif(isset($_POST['update'])) {
    $database = new Database();
    $conn = $database->getConn();
    
    $oldCode = isset($_POST['oldCode']) ? $_POST['oldCode'] : '';
    $code = isset($_POST['code']) ? $_POST['code'] : '';
    $discount = isset($_POST['discount']) ? $_POST['discount'] : '';
    $event_id = isset($_POST['event']) ? $_POST['event'] : '';
    
    if(empty($code) || empty($event_id) || empty($discount)) {
        echo json_encode(array('success' => false, 'message' => 'Erro ao atualizar cupom. Tente novamente mais tarde'));
        exit;
    }
    
    $couponController = new CouponController();
    $coupon = new Coupon($oldCode, null, $event_id);
    $newCoupon = new Coupon($code, $discount, $event_id);
    $success = $couponController->updateCoupon($newCoupon, $coupon, $conn);
    
    echo json_encode(array('success' => $success));
}

?>
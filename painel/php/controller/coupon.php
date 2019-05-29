<?php

class CouponController {

    public function saveCoupon($coupon, $conn) {
        $sql = "INSERT INTO ".CouponEntries::TBNAME."(".CouponEntries::CODE.",".CouponEntries::DISCOUNT.",".CouponEntries::EVENTID.")";
        $sql = $sql." VALUES (:code, :discount, :event)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":code", $coupon->getCode());
        $stmt->bindValue(":discount", $coupon->getDiscount());
        $stmt->bindValue(":event", $coupon->getEvent_id());

        return $stmt->execute();
    }

    public function deleteCoupon($coupon, $conn) {
        $sql = "DELETE FROM ".CouponEntries::TBNAME." WHERE ".CouponEntries::CODE." = :code AND ".CouponEntries::EVENTID." = :event";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":code", $coupon->getCode());
        $stmt->bindValue(":event", $coupon->getEvent_id());

        return $stmt->execute();
    }

    public function getCouponsByEvent($coupon, $conn) {
        $sql = "SELECT * FROM ".CouponEntries::TBNAME." WHERE ".CouponEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $coupon->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Coupon", array(CouponEntries::CODE,CouponEntries::DISCOUNT,CouponEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCoupon($coupon, $conn) {
        $sql = "SELECT * FROM ".CouponEntries::TBNAME." WHERE ".CouponEntries::CODE." = :code AND ".CouponEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":code", $coupon->getCode());
        $stmt->bindValue(":event", $coupon->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Coupon", array(CouponEntries::CODE,CouponEntries::DISCOUNT,CouponEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function updateCoupon($newCoupon, $coupon, $conn) {
        $sql = "UPDATE ".CouponEntries::TBNAME." SET ".CouponEntries::CODE." = :newCode , ".CouponEntries::DISCOUNT." = :discount WHERE ".CouponEntries::CODE." = :code AND ".CouponEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":newCode", $newCoupon->getCode());
        $stmt->bindValue(":discount", $newCoupon->getDiscount());
        $stmt->bindValue(":code", $coupon->getCode());
        $stmt->bindValue(":event", $coupon->getEvent_id());
        return $stmt->execute();
    }

    //

    public function deleteCouponsByEvent($event, $conn) {
        $sql = "DELETE FROM ".CouponEntries::TBNAME." WHERE ".CouponEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class CouponEntries { 

    const TBNAME = "coupons";
    const CODE = "code";
    const DISCOUNT = "discount";
    const EVENTID = "event_id";

}

?>
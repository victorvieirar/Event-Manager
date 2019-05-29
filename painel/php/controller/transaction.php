<?php

class TransactionController {

    public function saveTransaction($transaction, $conn) {
        $sql = "INSERT INTO ".TransactionEntries::TBNAME."(".TransactionEntries::ID.",".TransactionEntries::USERID.",".TransactionEntries::DATE.",".TransactionEntries::METHOD.",".TransactionEntries::VALUE.",".TransactionEntries::EVENTID.",".TransactionEntries::STATUS.",".TransactionEntries::COUPON.", ".TransactionEntries::PAGSEGURO.")";
        $sql = $sql." VALUES (:id, :user, :date, :method, :value, :event, :status, :coupon, :pagseguro)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $transaction->getId());
        $stmt->bindValue(":user", $transaction->getUser_cpf());
        $stmt->bindValue(":date", $transaction->getTransactionDate());
        $stmt->bindValue(":method", $transaction->getPaymentMethod());
        $stmt->bindValue(":value", $transaction->getValue());
        $stmt->bindValue(":event", $transaction->getEvent_id());
        $stmt->bindValue(":status", $transaction->getStatus());
        $stmt->bindValue(":coupon", $transaction->getCoupon_code());
        $stmt->bindValue(":pagseguro", $transaction->getPagseguro_code());

        return $stmt->execute();
    }

    public function updateTransaction($transaction, $conn) {
        $sql = "UPDATE ".TransactionEntries::TBNAME." SET ".TransactionEntries::STATUS." = :status , ".TransactionEntries::PAGSEGURO." = :pagseguro WHERE ".TransactionEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":status", $transaction->getStatus());
        $stmt->bindValue(":pagseguro", $transaction->getPagseguro_code);
        $stmt->bindValue(":id", $transaction->getId());
        return $stmt->execute();
    }
    
    public function getTransaction($transaction, $conn) {
        $sql = "SELECT * FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $transaction->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Transaction", array(TransactionEntries::ID,TransactionEntries::USERID,TransactionEntries::DATE,TransactionEntries::METHOD,TransactionEntries::VALUE, TransactionEntries::EVENTID,TransactionEntries::STATUS,TransactionEntries::COUPON,TransactionEntries::PAGSEGURO));
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getTransactionsByEvent($transaction, $conn) {
        $sql = "SELECT * FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $transaction->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Transaction", array(TransactionEntries::ID,TransactionEntries::USERID,TransactionEntries::DATE,TransactionEntries::METHOD,TransactionEntries::VALUE, TransactionEntries::EVENTID,TransactionEntries::STATUS,TransactionEntries::COUPON,TransactionEntries::PAGSEGURO));
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getTransactionsByUser($transaction, $conn) {
        $sql = "SELECT * FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::USERID." = :user";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $transaction->getUser_cpf());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Transaction", array(TransactionEntries::ID,TransactionEntries::USERID,TransactionEntries::DATE,TransactionEntries::METHOD,TransactionEntries::VALUE, TransactionEntries::EVENTID,TransactionEntries::STATUS,TransactionEntries::COUPON,TransactionEntries::PAGSEGURO));
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getUserEventTransactions($user, $event, $conn) {
        $sql = "SELECT * FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::USERID." = :user AND ".TransactionEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Transaction", array(TransactionEntries::ID,TransactionEntries::USERID,TransactionEntries::DATE,TransactionEntries::METHOD,TransactionEntries::VALUE, TransactionEntries::EVENTID,TransactionEntries::STATUS,TransactionEntries::COUPON,TransactionEntries::PAGSEGURO));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTransactionsByPagseguro($transaction, $conn) {
        $sql = "SELECT * FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::PAGSEGURO." = :pagseguro";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $transaction->getPagseguro_code());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Transaction", array(TransactionEntries::ID,TransactionEntries::USERID,TransactionEntries::DATE,TransactionEntries::METHOD,TransactionEntries::VALUE, TransactionEntries::EVENTID,TransactionEntries::STATUS,TransactionEntries::COUPON,TransactionEntries::PAGSEGURO));
        $stmt->execute();
        return $stmt->fetch();
    }

    //

    public function deleteTransactionsByEvent($event, $conn) {
        $sql = "DELETE FROM ".TransactionEntries::TBNAME." WHERE ".TransactionEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class TransactionEntries {
    const TBNAME = "transactions";
    const ID = "id";
    const USERID = "user_cpf";
    const DATE = "transactionDate";
    const METHOD = "paymentMethod";
    const VALUE = "value";
    const EVENTID = "event_id";
    const STATUS = "status";
    const COUPON = "coupon_code";
    const PAGSEGURO = "pagseguro_code";
}
?>

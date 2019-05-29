<?php
/**
 * Requires 
 * controller/event.php,
 * model/user.php and
 * config/database.php
 * to works perfectly
 */
class SubscribesController {

    public function getSubscribedEvents($user, $conn) {
        $sql = "SELECT * FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::USERID." = :user_cpf";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user_cpf", $user->getCpf());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function saveSubscription($user, $event, $access, $conn) {
        $sql = "INSERT INTO ".SubscribeEntries::TBNAME."(".SubscribeEntries::USERID.",".SubscribeEntries::EVENTID.",".SubscribeEntries::ACCESS.")";
        $sql = $sql." VALUES (:user, :event, :access)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        $stmt->bindValue(":access", $access);
        
        return $stmt->execute();
    }
    
    public function deleteSubscription($user, $event, $conn) {
        $sql = "DELETE FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::USERID." = :user AND ".SubscribeEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        
        return $stmt->execute();
    }
    
    public function updateSubscription($user, $event, $access, $conn) {
        $sql = "UPDATE ".SubscribeEntries::TBNAME." SET ".SubscribeEntries::ACCESS." = :access WHERE ".SubscribeEntries::USERID." = :user AND ".SubscribeEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":access", $access);
        $stmt->bindValue(":user", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        
        return $stmt->execute();
    }

    public function getParticipants($event, $conn) {
        $sql = "SELECT * FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::EVENTID." = :event_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event_id", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getParticipantsConfirmed($event, $conn) {
        $sql = "SELECT * FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::EVENTID." = :event_id AND ".SubscribeEntries::ACCESS." = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event_id", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getParticipantsPending($event, $conn) {
        $sql = "SELECT * FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::EVENTID." = :event_id AND ".SubscribeEntries::ACCESS." = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event_id", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getParticipantAccess($user, $event, $conn) {
        $sql = "SELECT * FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::USERID." = :user AND ".SubscribeEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    //

    public function deleteSubscriptionsByEvent($event, $conn) {
        $sql = "DELETE FROM ".SubscribeEntries::TBNAME." WHERE ".SubscribeEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        
        return $stmt->execute();
    }

}

class SubscribeEntries {
    const TBNAME = "participants";
    const USERID = "user_cpf";
    const EVENTID = "event_id";
    const ACCESS = "access";
}

?>

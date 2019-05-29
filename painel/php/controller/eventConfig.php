<?php

class EventConfigController {

    public function saveEventConfig($event, $conn) {
        $sql = "INSERT INTO ".EventConfigEntries::TBNAME."(".EventConfigEntries::ID.",".EventConfigEntries::TRAVEL.")";
        $sql = $sql." VALUES (:event, default)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getEvent_id());

        return $stmt->execute();
    }

    public function updateEventConfig($event, $conn) {
        $sql = "UPDATE ".EventConfigEntries::TBNAME." SET ".EventConfigEntries::TRAVEL." = :travel WHERE ".EventConfigEntries::ID." = :event";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":travel", $event->getTraveling());
        $stmt->bindValue(":event", $event->getEvent_id());

        return $stmt->execute();
    }

    public function deleteEventConfig($event, $conn) {
        $sql = "DELETE FROM ".EventConfigEntries::TBNAME." WHERE ".EventConfigEntries::ID." = :event";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getEvent_id());

        return $stmt->execute();
    }

    public function getEventConfig($event, $conn) {
        $sql = "SELECT * FROM ".EventConfigEntries::TBNAME." WHERE ".EventConfigEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $event->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "EventConfig", array(EventConfigEntries::ID,EventConfigEntries::TRAVEL));
        $stmt->execute();
        return $stmt->fetch();
    }

}

class EventConfigEntries {
    const TBNAME = "event_config";
    const ID = "event_id";
    const TRAVEL = "traveling";
}

?>
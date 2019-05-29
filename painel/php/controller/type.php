<?php

class TypeController {

    public function getTypes($conn) {
        $sql = "SELECT * FROM ".TypeEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Type", array(TypeEntries::ID,TypeEntries::NAME,TypeEntries::EVENT));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEventTypes($conn, $type) {
        $sql = "SELECT * FROM ".TypeEntries::TBNAME." WHERE ".TypeEntries::EVENT." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $type->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Type", array(TypeEntries::ID,TypeEntries::NAME,TypeEntries::EVENT));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function saveType($type, $conn) {
        $sql = "INSERT INTO ".TypeEntries::TBNAME."(".TypeEntries::NAME.",".TypeEntries::EVENT.")";
        $sql = $sql." VALUES (:name, :event)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $type->getName());
        $stmt->bindValue(":event", $type->getEvent_id());

        return $stmt->execute();
    }

    public function getType($type, $conn) {
        $sql = "SELECT * FROM ".TypeEntries::TBNAME." WHERE ".TypeEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $type->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Type", array(TypeEntries::ID,TypeEntries::NAME,TypeEntries::EVENT));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getTypeByName($type, $conn) {
        $sql = "SELECT * FROM ".TypeEntries::TBNAME." WHERE ".TypeEntries::NAME." = :name";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $type->getName());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Type", array(TypeEntries::ID,TypeEntries::NAME,TypeEntries::EVENT));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function deleteType($type, $conn) {
        $sql = "DELETE FROM ".TypeEntries::TBNAME." WHERE ".TypeEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $type->getId());
        return $stmt->execute();
    }

    //

    public function deleteTypesByEvent($event, $conn) {
        $sql = "DELETE FROM ".TypeEntries::TBNAME." WHERE ".TypeEntries::EVENT." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class TypeEntries {

    const TBNAME = "types";
    const ID = "id";
    const NAME = "name";
    const EVENT = "event_id";

}

?>
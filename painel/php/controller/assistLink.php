<?php

class AssistLinkController {

    public function saveAssistLink($assist, $conn) {
        $sql = "INSERT INTO ".AssistLinkEntries::TBNAME."(".AssistLinkEntries::LINK.", ".AssistLinkEntries::EVENT.")";
        $sql .= " VALUES (:link, :event)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":link", $assist->getLink());
        $stmt->bindValue(":event", $assist->getEvent_id());
        return $stmt->execute();
    }

    public function updateAssistLink($assist, $conn) {
        $sql = "UPDATE ".AssistLinkEntries::TBNAME." SET ".AssistLinkEntries::LINK." = :link WHERE ".AssistLinkEntries::EVENT." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":link", $assist->getLink());
        $stmt->bindValue(":event", $assist->getEvent_id());
        return $stmt->execute();
    }

    public function getAssistLinkByEvent($assist, $conn) {
        $sql = "SELECT * FROM ".AssistLinkEntries::TBNAME." WHERE ".AssistLinkEntries::EVENT." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $assist->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "AssistLink", array(AssistLinkEntries::LINK, AssistLinkEntries::EVENT));
        $stmt->execute();
        return $stmt->fetch();
    }

    //
    public function deleteAssistLinkByEvent($assist, $conn) {
        $sql = "DELETE FROM ".AssistLinkEntries::TBNAME." WHERE ".AssistLinkEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $assist->getId());
        return $stmt->execute();
    }

}

class AssistLinkEntries {
    const TBNAME = "assist_link";
    const LINK = "link";
    const EVENT = "event_id";
}

?>
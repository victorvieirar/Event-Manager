<?php

class PartnerController {

    public function savePartner($partner, $conn) {
        $sql = "INSERT INTO ".PartnerEntries::TBNAME."(".PartnerEntries::EVENTID.",".PartnerEntries::IMAGE.",".PartnerEntries::NAME.",".PartnerEntries::LINK.")";
        $sql = $sql." VALUES (:event, :image, :name, :link)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $partner->getEvent_id());
        $stmt->bindValue(":image", $partner->getImage());
        $stmt->bindValue(":name", $partner->getName());
        $stmt->bindValue(":link", $partner->getLink());

        return $stmt->execute();
    }

    public function getPartnersByEvent($partner, $conn) {
        $sql = "SELECT * FROM ".PartnerEntries::TBNAME." WHERE ".PartnerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $partner->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Partner", array(PartnerEntries::NAME,PartnerEntries::LINK,PartnerEntries::IMAGE,PartnerEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deletePartner($partner, $conn) {
        $sql = "DELETE FROM ".PartnerEntries::TBNAME." WHERE ".PartnerEntries::NAME." = :name AND ".PartnerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $partner->getName());
        $stmt->bindValue(":event", $partner->getEvent_id());
        return $stmt->execute();
    }

    public function updatePartner($newPartner, $partner, $conn) {
        $sql = "UPDATE ".PartnerEntries::TBNAME." SET ".PartnerEntries::NAME." = :name ,".PartnerEntries::IMAGE." = :image ,".PartnerEntries::LINK." = :link WHERE ".PartnerEntries::NAME." = :oldName AND ".PartnerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $newPartner->getName());
        $stmt->bindValue(":image", $newPartner->getImage());
        $stmt->bindValue(":link", $newPartner->getLink());
        $stmt->bindValue(":oldName", $partner->getName());
        $stmt->bindValue(":event", $partner->getEvent_id());
        return $stmt->execute();
    }

    //

    public function deletePartnersByEvent($event, $conn) {
        $sql = "DELETE FROM ".PartnerEntries::TBNAME." WHERE ".PartnerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class PartnerEntries {
    const TBNAME = "partners";
    const EVENTID = "event_id";
    const NAME = "name";
    const LINK = "link";
    const IMAGE = "image";
}

?>
<?php

class SpeakerController {

    public function saveSpeaker($speaker, $conn) {
        $sql = "INSERT INTO ".SpeakerEntries::TBNAME."(".SpeakerEntries::EVENTID.",".SpeakerEntries::IMAGE.",".SpeakerEntries::NAME.",".SpeakerEntries::DESCRIPTION.",".SpeakerEntries::LINK.")";
        $sql = $sql." VALUES (:event, :image, :name, :description, :link)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $speaker->getEvent_id());
        $stmt->bindValue(":image", $speaker->getImage());
        $stmt->bindValue(":name", $speaker->getName());
        $stmt->bindValue(":description", $speaker->getDescription());
        $stmt->bindValue(":link", $speaker->getLink());

        return $stmt->execute();
    }

    public function getSpeakersByEvent($speaker, $conn) {
        $sql = "SELECT * FROM ".SpeakerEntries::TBNAME." WHERE ".SpeakerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $speaker->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Speaker", array(SpeakerEntries::EVENTID,SpeakerEntries::IMAGE,SpeakerEntries::NAME,SpeakerEntries::DESCRIPTION,SpeakerEntries::LINK));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteSpeaker($speaker, $conn) {
        $sql = "DELETE FROM ".SpeakerEntries::TBNAME." WHERE ".SpeakerEntries::NAME." = :name AND ".SpeakerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $speaker->getName());
        $stmt->bindValue(":event", $speaker->getEvent_id());
        return $stmt->execute();
    }

    public function updateSpeaker($newSpeaker, $speaker, $conn) {
        $sql = "UPDATE ".SpeakerEntries::TBNAME." SET ".SpeakerEntries::NAME." = :name ,".SpeakerEntries::IMAGE." = :image ,".SpeakerEntries::DESCRIPTION." = :description ,".SpeakerEntries::LINK." = :link WHERE ".SpeakerEntries::NAME." = :oldName AND ".SpeakerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $newSpeaker->getName());
        $stmt->bindValue(":image", $newSpeaker->getImage());
        $stmt->bindValue(":description", $newSpeaker->getDescription());
        $stmt->bindValue(":link", $newSpeaker->getLink());
        $stmt->bindValue(":oldName", $speaker->getName());
        $stmt->bindValue(":event", $speaker->getEvent_id());
        return $stmt->execute();
    }

    //

    public function deleteSpeakersByEvent($event, $conn) {
        $sql = "DELETE FROM ".SpeakerEntries::TBNAME." WHERE ".SpeakerEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class SpeakerEntries {
    const TBNAME = "speakers";
    const EVENTID = "event_id";
    const IMAGE = "image";
    const NAME = "name";
    const DESCRIPTION = "description";
    const LINK = "link";
}

?>
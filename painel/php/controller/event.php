<?php 
/**
 * Requires 
 * model/event.php and
 * config/database.php
 * to works perfectly
 */
class EventController { 

    public function saveEvent($event, $conn) {
        $sql = "INSERT INTO ".EventEntries::TBNAME."(".EventEntries::NAME.",".EventEntries::DATE.",".EventEntries::ENDDATE.",".EventEntries::CITY.")";
        $sql = $sql." VALUES (:name, :date, :endDate, :city)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $event->getName());
        $stmt->bindValue(":date", $event->getDate());
        $stmt->bindValue(":endDate", $event->getEndDate());
        $stmt->bindValue(":city", $event->getCity()->getId());

        return $stmt->execute();
    }

    public function getAll($conn) {
        $sql = "SELECT * FROM ".EventEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Event", array(EventEntries::ID,EventEntries::NAME,EventEntries::DATE,EventEntries::ENDDATE,EventEntries::CITY,EventEntries::DESCRIPTION, EventEntries::DEADLINE,EventEntries::IMAGE,EventEntries::SUBMISSIONS,EventEntries::SUBSCRIPTION));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllAvailable($conn) {
        $sql = "SELECT * FROM ".EventEntries::TBNAME." WHERE ".EventEntries::SUBSCRIPTION." >= :subscription";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":subscription", date("Y-m-d", time()));
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Event", array(EventEntries::ID,EventEntries::NAME,EventEntries::DATE,EventEntries::ENDDATE,EventEntries::CITY,EventEntries::DESCRIPTION, EventEntries::DEADLINE,EventEntries::IMAGE,EventEntries::SUBMISSIONS,EventEntries::SUBSCRIPTION));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAvailableForSubmissionEvents($conn) {
        $sql = "SELECT * FROM ".EventEntries::TBNAME." WHERE ".EventEntries::SUBSCRIPTION." >= :subscription AND ".EventEntries::DEADLINE." >= :deadline AND ".EventEntries::SUBMISSIONS." = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":subscription", date("Y-m-d", time()));
        $stmt->bindValue(":deadline", date("Y-m-d", time()));
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Event", array(EventEntries::ID,EventEntries::NAME,EventEntries::DATE,EventEntries::ENDDATE,EventEntries::CITY,EventEntries::DESCRIPTION, EventEntries::DEADLINE,EventEntries::IMAGE,EventEntries::SUBMISSIONS,EventEntries::SUBSCRIPTION));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEvent($conn, $event) {
        $sql = "SELECT * FROM ".EventEntries::TBNAME." WHERE ".EventEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Event", array(EventEntries::ID,EventEntries::NAME,EventEntries::DATE,EventEntries::ENDDATE,EventEntries::CITY,EventEntries::DESCRIPTION, EventEntries::DEADLINE,EventEntries::IMAGE,EventEntries::SUBMISSIONS,EventEntries::SUBSCRIPTION));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getEventByName($event, $conn) {
        $sql = "SELECT * FROM ".EventEntries::TBNAME." WHERE ".EventEntries::NAME." = :name";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $event->getName());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Event", array(EventEntries::ID,EventEntries::NAME,EventEntries::DATE,EventEntries::ENDDATE,EventEntries::CITY,EventEntries::DESCRIPTION, EventEntries::DEADLINE,EventEntries::IMAGE,EventEntries::SUBMISSIONS,EventEntries::SUBSCRIPTION));
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function deleteEvent($event, $conn) {
        $sql = "DELETE FROM ".EventEntries::TBNAME." WHERE ".EventEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $event->getId());
        return $stmt->execute();
    }

    public function updateEvent($event, $conn) {
        $sql = "UPDATE ".EventEntries::TBNAME." SET ".EventEntries::NAME." = :name , ".EventEntries::DATE." = :date , ".EventEntries::ENDDATE." = :endDate , ".EventEntries::CITY." = :city , ".EventEntries::DESCRIPTION." = :description , ".EventEntries::DEADLINE." = :deadline , ".EventEntries::IMAGE." = :image , ".EventEntries::SUBSCRIPTION." = :subscription , ".EventEntries::SUBMISSIONS." = :submissions WHERE ".EventEntries::ID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $event->getName());
        $stmt->bindValue(":date", $event->getDate());
        $stmt->bindValue(":endDate", $event->getEndDate());
        $stmt->bindValue(":city", $event->getCity());
        $stmt->bindValue(":description", $event->getDescription());
        $stmt->bindValue(":deadline", $event->getDeadline());
        $stmt->bindValue(":image", $event->getFeatured_image());
        $stmt->bindValue(":subscription", $event->getSubscription_limit());
        $stmt->bindValue(":submissions", $event->getAllow_submissions());
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class EventEntries {

    const TBNAME = "event";
    const ID = "id";
    const NAME = "name";
    const DESCRIPTION = "description";
    const DATE = "date";
    const ENDDATE = "endDate";
    const CITY = "city";
    const DEADLINE = "deadline";
    const IMAGE = "featured_image";
    const SUBMISSIONS = "allow_submissions";
    const SUBSCRIPTION = "subscription_limit";

}

?>

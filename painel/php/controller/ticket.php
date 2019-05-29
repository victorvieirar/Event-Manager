<?php

class TicketController {

    public function saveTicket($ticket, $conn) {
        $sql = "INSERT INTO ".TicketEntries::TBNAME." (".TicketEntries::NAME.",".TicketEntries::DESCRIPTION.",".TicketEntries::PRICE.",".TicketEntries::IDATE.",".TicketEntries::FDATE.",".TicketEntries::EVENTID.")";
        $sql .= " VALUES(:name, :description, :price, :initialDate, :finalDate, :event)";
        
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":name", $ticket->getName());
        $stmt->bindValue(":description", $ticket->getDescription());
        $stmt->bindValue(":price", $ticket->getPrice());
        $stmt->bindValue(":initialDate", $ticket->getInitialDate());
        $stmt->bindValue(":finalDate", $ticket->getFinalDate());
        $stmt->bindValue(":event", $ticket->getEvent_id());

        return $stmt->execute();
    }

    public function getTicket($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $ticket->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getTicketByName($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::NAME." = :name AND ".TicketEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $ticket->getName());
        $stmt->bindValue(":event", $ticket->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getTicketsByEvent($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::EVENTID." = :event ORDER BY ".TicketEntries::FDATE;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $ticket->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAvailableTicketsByEvent($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::EVENTID." = :event AND ".TicketEntries::IDATE." <= :initialDate AND ".TicketEntries::FDATE." >= :finalDate ORDER BY ".TicketEntries::IDATE." ASC";
        $stmt = $conn->prepare($sql);

        $date = date("Y-m-d");

        $stmt->bindValue(":event", $ticket->getEvent_id());
        $stmt->bindValue(":initialDate", $date);
        $stmt->bindValue(":finalDate", $date);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAvailablesTicketsByEvent($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::EVENTID." = :event AND ".TicketEntries::IDATE." <= :initialDate AND ".TicketEntries::FDATE." >= :finalDate";
        $stmt = $conn->prepare($sql);

        $date = date("Y-m-d");

        $stmt->bindValue(":event", $ticket->getEvent_id());
        $stmt->bindValue(":initialDate", $date);
        $stmt->bindValue(":finalDate", $date);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnavailablesTicketsByEvent($ticket, $conn) {
        $sql = "SELECT * FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::EVENTID." = :event AND ".TicketEntries::FDATE." < :finalDate";
        $stmt = $conn->prepare($sql); 

        $date = date("Y-m-d");

        $stmt->bindValue(":event", $ticket->getEvent_id());
        $stmt->bindValue(":finalDate", $date);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Ticket", array(TicketEntries::ID, TicketEntries::NAME, TicketEntries::DESCRIPTION, TicketEntries::PRICE, TicketEntries::IDATE, TicketEntries::FDATE, TicketEntries::EVENTID));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteTicket($ticket, $conn) {
        $sql = "DELETE FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $ticket->getId());

        return $stmt->execute();
    }

    public function updateTicket($ticket, $conn) {
        $sql = "UPDATE ".TicketEntries::TBNAME." SET ".TicketEntries::NAME." = :name , ".TicketEntries::DESCRIPTION." = :description , ".TicketEntries::PRICE." = :price , ".TicketEntries::IDATE." = :initialDate , ".TicketEntries::FDATE." = :finalDate WHERE ".TicketEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $ticket->getName());
        $stmt->bindValue(":description", $ticket->getDescription());
        $stmt->bindValue(":price", $ticket->getPrice());
        $stmt->bindValue(":initialDate", $ticket->getInitialDate());
        $stmt->bindValue(":finalDate", $ticket->getFinalDate());
        $stmt->bindValue(":id", $ticket->getId());

        return $stmt->execute();
    }

    //

    public function deleteTicketsByEvent($event, $conn) {
        $sql = "DELETE FROM ".TicketEntries::TBNAME." WHERE ".TicketEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());

        return $stmt->execute();
    }

}

class TicketEntries {
    const TBNAME = "ticket";
    const ID = "id";
    const NAME = "name";
    const DESCRIPTION = "description";
    const PRICE = "price";
    const IDATE = "initialDate";
    const FDATE = "finalDate";
    const EVENTID = "event_id";
}

?>
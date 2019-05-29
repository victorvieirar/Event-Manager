<?php
/**
 * Requires 
 * model/schedule.php and
 * config/database.php
 * to works perfectly
 */
class ScheduleController { 

    public function saveSchedule($schedule, $conn) {
        $sql = "INSERT INTO ".ScheduleEntries::TBNAME."(".ScheduleEntries::EVENTID.",".ScheduleEntries::TITLE.",".ScheduleEntries::TIME.",".ScheduleEntries::FINALTIME.")";
        $sql = $sql." VALUES (:event, :title, :time, :finalTime)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $schedule->getEvent_id());
        $stmt->bindValue(":title", $schedule->getTitle());
        $stmt->bindValue(":time", $schedule->getScheduleTime());
        $stmt->bindValue(":finalTime", $schedule->getFinalScheduleTime());

        return $stmt->execute();
    }

    public function getAll($conn) {
        $sql = "SELECT * FROM ".ScheduleEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Schedule", array(ScheduleEntries::EVENTID,ScheduleEntries::TITLE,ScheduleEntries::TIME,ScheduleEntries::FINALTIME));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSchedule($schedule, $conn) {
        $sql = "SELECT * FROM ".ScheduleEntries::TBNAME." WHERE ".ScheduleEntries::TITLE." = :title AND ".ScheduleEntries::TIME." = :time AND ".ScheduleEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":title", $schedule->getTitle());
        $stmt->bindValue(":time", $schedule->getScheduleTime());
        $stmt->bindValue(":event", $schedule->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Schedule", array(ScheduleEntries::EVENTID,ScheduleEntries::TITLE,ScheduleEntries::TIME,ScheduleEntries::FINALTIME));
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getDaysOfEvent($schedule, $conn) {
        $sql = "SELECT * FROM ".ScheduleEntries::TBNAME." WHERE ".ScheduleEntries::EVENTID." = :event GROUP BY YEAR(".ScheduleEntries::TIME."), MONTH(".ScheduleEntries::TIME."), DAY(".ScheduleEntries::TIME.")";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':event', $schedule->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Schedule", array(ScheduleEntries::EVENTID,ScheduleEntries::TITLE,ScheduleEntries::TIME,ScheduleEntries::FINALTIME));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSchedulesByDay($schedule, $conn) {
        $sql = "SELECT * FROM ".ScheduleEntries::TBNAME." WHERE DAY(".ScheduleEntries::TIME.") = :day AND MONTH(".ScheduleEntries::TIME.") = :month AND YEAR(".ScheduleEntries::TIME.") = :year AND ".ScheduleEntries::EVENTID." = :event ";
        $sql .= "ORDER BY ".ScheduleEntries::TIME." ASC";
        $stmt = $conn->prepare($sql);

        $time = strtotime($schedule->getScheduleTime());
        $day = date("d", $time);
        $month = date("m", $time);
        $year = date("Y", $time);

        $stmt->bindValue(":day", $day);
        $stmt->bindValue(":month", $month);
        $stmt->bindValue(":year", $year);
        $stmt->bindValue(":event", $schedule->getEvent_id());

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Schedule", array(ScheduleEntries::EVENTID,ScheduleEntries::TITLE,ScheduleEntries::TIME,ScheduleEntries::FINALTIME));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSchedulesByEvent($schedule, $conn) {
        $sql = "SELECT * FROM ".ScheduleEntries::TBNAME." WHERE ".ScheduleEntries::EVENTID." = :event ";
        $sql .= "ORDER BY ".ScheduleEntries::TIME." ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $schedule->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Schedule", array(ScheduleEntries::EVENTID,ScheduleEntries::TITLE,ScheduleEntries::TIME,ScheduleEntries::FINALTIME));
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function deleteSchedule($schedule, $conn) {
        $sql = "DELETE FROM ".ScheduleEntries::TBNAME." WHERE ".ScheduleEntries::TITLE." = :title AND ".ScheduleEntries::TIME." = :time AND ".ScheduleEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":title", $schedule->getTitle());
        $stmt->bindValue(":time", $schedule->getScheduleTime());
        $stmt->bindValue(":event", $schedule->getEvent_id());
        return $stmt->execute();
    }

    public function updateSchedule($newSchedule, $schedule, $conn) {
        $sql = "UPDATE ".ScheduleEntries::TBNAME." SET ".ScheduleEntries::TITLE." = :titleUpdate , ".ScheduleEntries::TIME." = :timeUpdate , ".ScheduleEntries::FINALTIME." = :finalTimeUpdate WHERE ".ScheduleEntries::TITLE." = :title AND ".ScheduleEntries::TIME." = :time AND ".ScheduleEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":titleUpdate", $newSchedule->getTitle());
        $stmt->bindValue(":timeUpdate", $newSchedule->getScheduleTime());
        $stmt->bindValue(":finalTimeUpdate", $newSchedule->getFinalScheduleTime());
        $stmt->bindValue(":title", $schedule->getTitle());
        $stmt->bindValue(":time", $schedule->getScheduleTime());
        $stmt->bindValue(":event", $schedule->getEvent_id());
        return $stmt->execute();
    }

    //

    public function deleteSchedulesByEvent($event, $conn) {
        $sql = "DELETE FROM ".ScheduleEntries::TBNAME." WHERE ".ScheduleEntries::EVENTID." = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }
}

class ScheduleEntries {

    const TBNAME = "schedule";
    const EVENTID = "event_id";
    const TITLE = "title";
    const TIME = "scheduleTime";
    const FINALTIME = "finalScheduleTime";

}

?>

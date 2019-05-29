<?php

class NewsController {

    public function saveNews($news, $conn) {
        $sql = "INSERT INTO ".NewsEntries::TBNAME."(".NewsEntries::TITLE.", ".NewsEntries::MESSAGE.", ".NewsEntries::FILE.", ".NewsEntries::EVENTID.")";
        $sql .= " VALUES (:title, :message, :file, :event)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":title", $news->getTitle());
        $stmt->bindValue(":message", $news->getMessage());
        $stmt->bindValue(":file", $news->getFile());
        $stmt->bindValue(":event", $news->getEvent_id());

        return $stmt->execute();
    }

    public function updateNews($news, $conn) {
        $sql = "UPDATE ".NewsEntries::TBNAME." SET ".NewsEntries::TITLE." = :title, ".NewsEntries::MESSAGE." = :message, ".NewsEntries::FILE." = :file WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":title", $news->getTitle());
        $stmt->bindValue(":message", $news->getMessage());
        $stmt->bindValue(":file", $news->getFile());
        $stmt->bindValue(":id", $news->getId());

        return $stmt->execute();
    }

    public function getNewsByEvent($news, $conn) {
        $sql = "SELECT * FROM ".NewsEntries::TBNAME." WHERE ".NewsEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $news->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "News", array(NewsEntries::ID,NewsEntries::TITLE,NewsEntries::MESSAGE,NewsEntries::FILE,NewsEntries::EVENTID));
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getNews($news, $conn) {
        $sql = "SELECT * FROM ".NewsEntries::TBNAME." WHERE ".NewsEntries::ID." = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $news->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "News", array(NewsEntries::ID,NewsEntries::TITLE,NewsEntries::MESSAGE,NewsEntries::FILE,NewsEntries::EVENTID));
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function deleteNews($news, $conn) {
        $sql = "DELETE FROM ".NewsEntries::TBNAME." WHERE ".NewsEntries::ID." = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $news->getId());
        return $stmt->execute();
    }

    public function deleteNewsByEvent($event, $conn) {
        $sql = "DELETE FROM ".NewsEntries::TBNAME." WHERE ".NewsEntries::EVENTID." = :event";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }

}

class NewsEntries {
    const TBNAME = "news";
    const ID = "id";
    const TITLE = "title";
    const MESSAGE = "message";
    const FILE = "file";
    const EVENTID = "event_id";
}

?>
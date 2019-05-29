<?php

class SubmissionController
{

    public function getUserSubmissions($conn, $user, $event)
    {
        $sql = "SELECT * FROM " . SubmissionEntries::TBNAME . " WHERE " . SubmissionEntries::USER . " = :cpf AND " . SubmissionEntries::EVENT . " = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cpf", $user->getCpf());
        $stmt->bindValue(":event", $event->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Submission", array(SubmissionEntries::USER, SubmissionEntries::ID, SubmissionEntries::EVENT, SubmissionEntries::TITLE, SubmissionEntries::DESCRIPTION, SubmissionEntries::KEYWORDS, SubmissionEntries::AUTHORS, SubmissionEntries::TYPE, SubmissionEntries::FILE, SubmissionEntries::STATUS));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSubmissionsByEvent($submission, $conn)
    {
        $sql = "SELECT * FROM " . SubmissionEntries::TBNAME . " WHERE " . SubmissionEntries::EVENT . " = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $submission->getEvent_id());
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Submission", array(SubmissionEntries::USER, SubmissionEntries::ID, SubmissionEntries::EVENT, SubmissionEntries::TITLE, SubmissionEntries::DESCRIPTION, SubmissionEntries::KEYWORDS, SubmissionEntries::AUTHORS, SubmissionEntries::TYPE, SubmissionEntries::FILE, SubmissionEntries::STATUS));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateSubmission($submission, $conn)
    {
        $sql = "UPDATE " . SubmissionEntries::TBNAME . " SET " . SubmissionEntries::STATUS . " = :status WHERE " . SubmissionEntries::ID . " = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":status", $submission->getStatus());
        $stmt->bindValue(":id", $submission->getId());
        return $stmt->execute();
    }

    public function saveSubmission($submission, $conn)
    {
        $sql = "INSERT INTO " . SubmissionEntries::TBNAME . "(" . SubmissionEntries::USER . ", " . SubmissionEntries::EVENT . ", " . SubmissionEntries::TITLE . ", " . SubmissionEntries::DESCRIPTION . ", " . SubmissionEntries::KEYWORDS . ", " . SubmissionEntries::AUTHORS . ", " . SubmissionEntries::TYPE . ", " . SubmissionEntries::FILE . ", " . SubmissionEntries::STATUS . ")";
        $sql .= " VALUES (:user, :event, :title, :description, :keywords, :authors, :type, :file, :status)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $submission->getUser_cpf());
        $stmt->bindValue(":event", $submission->getEvent_id());
        $stmt->bindValue(":title", $submission->getTitle());
        $stmt->bindValue(":description", $submission->getDescription());
        $stmt->bindValue(":keywords", $submission->getKeywords());
        $stmt->bindValue(":authors", $submission->getAuthors());
        $stmt->bindValue(":type", $submission->getType());
        $stmt->bindValue(":file", $submission->getFile());
        $stmt->bindValue(":status", $submission->getStatus());

        return $stmt->execute();
    }

    public function deleteSubmissionsByEvent($event, $conn)
    {
        $sql = "DELETE FROM " . SubmissionEntries::TBNAME . " WHERE " . SubmissionEntries::EVENT . " = :event";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":event", $event->getId());
        return $stmt->execute();
    }
}

class SubmissionEntries
{
    const TBNAME = "submissions";
    const ID = "id";
    const USER = "user_cpf";
    const EVENT = "event_id";
    const TITLE = "title";
    const DESCRIPTION = "description";
    const KEYWORDS = "keywords";
    const AUTHORS = "authors";
    const TYPE = "type";
    const FILE = "file";
    const STATUS = "status";
}

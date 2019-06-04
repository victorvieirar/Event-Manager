<?php
/**
 * Requires 
 * model/certified.php and
 * config/database.php
 * to works perfectly
 */
class CertifiedController
{

  public function getCertifies($conn)
  {
    $sql = "SELECT * FROM " . CertifiedEntries::TBNAME;
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Certified", array(CertifiedEntries::USER_CPF, CertifiedEntries::EVENT_ID, CertifiedEntries::LINK));
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getCertifiesByUser($conn, $user)
  {
    $sql = "SELECT * FROM " . CertifiedEntries::TBNAME . " WHERE " . CertifiedEntries::USER_CPF . " = :user";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":user", $user->getCpf());
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Certified", array(CertifiedEntries::USER_CPF, CertifiedEntries::EVENT_ID, CertifiedEntries::LINK));
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getCertifiesByUserEvent($conn, $user, $event)
  {
    $sql = "SELECT * FROM " . CertifiedEntries::TBNAME . " WHERE " . CertifiedEntries::USER_CPF . " = :user AND " . CertifiedEntries::EVENT_ID . " = :event ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":user", $user->getCpf());
    $stmt->bindValue(":event", $event->getId());
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Certified", array(CertifiedEntries::USER_CPF, CertifiedEntries::EVENT_ID, CertifiedEntries::LINK));
    $stmt->execute();
    return $stmt->fetch();
  }

  public function getCertifiesByEvent($conn, $event)
  {
    $sql = "SELECT * FROM " . CertifiedEntries::TBNAME . " WHERE " . CertifiedEntries::EVENT_ID . " = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $event->getId());
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Certified", array(CertifiedEntries::USER_CPF, CertifiedEntries::EVENT_ID, CertifiedEntries::LINK));
    $stmt->execute();
    return $stmt->fetch();
  }

  public function insertCertified($conn, $certified)
  {
    $sql = "INSERT INTO " . CertifiedEntries::TBNAME . "(id_participant, id_event, link) VALUES (:user, :event, :link) ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":user", $certified->getUser_cpf());
    $stmt->bindValue(":event", $certified->getEvent_id());
    $stmt->bindValue(":link", $certified->getLink());
    return $stmt->execute();
  }
}

class CertifiedEntries
{

  const TBNAME = "certified";
  const USER_CPF = "id_participant";
  const EVENT_ID = "id_event";
  const LINK = "link";
}

<?php
/**
 * Requires 
 * model/state.php and
 * config/database.php
 * to works perfectly
 */
class StateController 
{

    public function getStates($conn) 
    {
        $sql = "SELECT * FROM ".StateEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "State", array(StateEntries::ID,StateEntries::NAME,StateEntries::FU,StateEntries::COUNTRY));
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getStateById($state, $conn) 
    {
        $sql = "SELECT * FROM ".StateEntries::TBNAME." WHERE ".StateEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $state->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "State", array(StateEntries::ID,StateEntries::NAME,StateEntries::FU,StateEntries::COUNTRY));
        $stmt->execute();
        return $stmt->fetch();
    }

}

class StateEntries 
{

    const TBNAME = "estado";
    const ID = "id";
    const NAME = "nome";
    const FU = "uf";
    const COUNTRY = "pais";

}

?>
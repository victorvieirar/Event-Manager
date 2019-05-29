<?php
/**
 * Requires 
 * model/city.php and
 * config/database.php
 * to works perfectly
 */
class CityController 
{

    public function getCities($conn) 
    {
        $sql = "SELECT * FROM ".CityEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "City", array(CityEntries::ID,CityEntries::NAME,CityEntries::STATE));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCitiesByState($conn, $state) 
    {
        $sql = "SELECT * FROM ".CityEntries::TBNAME." WHERE ".CityEntries::STATE." = :state";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":state", $state);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "City", array(CityEntries::ID,CityEntries::NAME,CityEntries::STATE));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCityById($conn, $city) 
    {
        $sql = "SELECT * FROM ".CityEntries::TBNAME." WHERE ".CityEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $city->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "City", array(CityEntries::ID,CityEntries::NAME,CityEntries::STATE));
        $stmt->execute();
        return $stmt->fetch();
    }

}

class CityEntries 
{

    const TBNAME = "cidade";
    const ID = "id";
    const NAME = "nome";
    const STATE = "estado";

}

?>
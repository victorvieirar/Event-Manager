<?php
/**
 * Requires 
 * model/admin.php and
 * config/database.php
 * to works perfectly
 */
class AdminController 
{
    
    public function getAdmin($admin, $conn) {
        $sql = "SELECT * FROM ".AdminEntries::TBNAME." WHERE ".AdminEntries::USER." = :user AND ".AdminEntries::PASSWORD." = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $admin->getUser());
        $stmt->bindValue(":password", $admin->getPassword());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Admin", array(AdminEntries::USER,AdminEntries::PASSWORD,AdminEntries::NAME));
        $stmt->execute(); 
        return $stmt->fetch();
    }

}

class AdminEntries 
{
    const TBNAME = "admin";
    const USER = "user";
    const PASSWORD = "password";
    const NAME = "name";
}

?>
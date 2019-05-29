<?php
/**
 * Requires 
 * model/user.php and
 * config/database.php
 * to works perfectly
 */
class UserController  
{
    
    public function saveUser($user, $conn) {
        $sql = "INSERT INTO ".UserEntries::TBNAME."(".UserEntries::ID.", ".UserEntries::NAME.", ".UserEntries::PASSWORD.", ".UserEntries::EMAIL.", ".UserEntries::PHONE.", ".UserEntries::ESTADO.", ".UserEntries::COURSE.", ".UserEntries::FORMATION.") VALUES (:cpf, :name, :password, :email, :phone, :estado, :course, :formation)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cpf", $user->getCpf());
        $stmt->bindValue(":name", $user->getName());
        $stmt->bindValue(":password", $user->getPassword());
        $stmt->bindValue(":email", $user->getEmail());
        $stmt->bindValue(":phone", $user->getPhone());
        $stmt->bindValue(":estado", $user->getEstado_id());
        $stmt->bindValue(":course", $user->getCourse());
        $stmt->bindValue(":formation", $user->getFormation()); 
        
        return $stmt->execute(); 
    }

    public function updateUser($user, $conn, $oldCpf) {
        $sql = "UPDATE ".UserEntries::TBNAME." SET ".UserEntries::NAME." = :name , ".UserEntries::PASSWORD." = :password , ".UserEntries::EMAIL." = :email , ".UserEntries::PHONE." = :phone , ".UserEntries::ESTADO." = :estado , ".UserEntries::COURSE." = :course , ".UserEntries::FORMATION." = :formation , ".UserEntries::ID." = :cpf WHERE ".UserEntries::ID." = :oldcpf";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":name", $user->getName());
        $stmt->bindValue(":password", $user->getPassword());
        $stmt->bindValue(":email", $user->getEmail());
        $stmt->bindValue(":phone", $user->getPhone());
        $stmt->bindValue(":estado", $user->getEstado_id());
        $stmt->bindValue(":course", $user->getCourse());
        $stmt->bindValue(":formation", $user->getFormation());
        $stmt->bindValue(":cpf", $user->getCpf());
        $stmt->bindValue(":oldcpf", $oldCpf);
        
        return $stmt->execute(); 
    }

    public function getUser($user, $conn) {
        $sql = "SELECT * FROM ".UserEntries::TBNAME." WHERE ".UserEntries::ID." = :cpf AND ".UserEntries::PASSWORD." = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cpf", $user->getCpf());
        $stmt->bindValue(":password", $user->getPassword());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "User", array(UserEntries::ID,UserEntries::NAME,UserEntries::PASSWORD,UserEntries::EMAIL,UserEntries::PHONE,UserEntries::ESTADO,UserEntries::COURSE,UserEntries::FORMATION));
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getUserInformation($user, $conn) {
        $sql = "SELECT * FROM ".UserEntries::TBNAME." WHERE ".UserEntries::ID." = :cpf";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cpf", $user->getCpf());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "User", array(UserEntries::ID,UserEntries::NAME,UserEntries::PASSWORD,UserEntries::EMAIL,UserEntries::PHONE,UserEntries::ESTADO,UserEntries::COURSE,UserEntries::FORMATION));
        $stmt->execute(); 
        return $stmt->fetch();
    }
}

class UserEntries 
{
    const TBNAME = "user";
    const ID = "cpf";
    const NAME = "name";
    const PASSWORD = "password";
    const EMAIL = "email";
    const PHONE = "phone";
    const ESTADO = "estado_id";
    const COURSE = "course";
    const FORMATION = "formation";
}

?>
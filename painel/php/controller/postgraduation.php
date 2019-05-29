<?php

class PostGraduationController {

    public function savePostGraduation($postGraduation, $conn) {
        $sql = "INSERT INTO ".PostGraduationEntries::TBNAME."(".PostGraduationEntries::NAME.",".PostGraduationEntries::DESCRIPTION.",".PostGraduationEntries::LINK.",".PostGraduationEntries::IMAGE.")";
        $sql = $sql." VALUES (:name, :description, :link, :image)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $postGraduation->getName());
        $stmt->bindValue(":description", $postGraduation->getDescription());
        $stmt->bindValue(":link", $postGraduation->getLink());
        $stmt->bindValue(":image", $postGraduation->getFeatured_image());

        return $stmt->execute();
    }

    public function updatePostGraduation($postGraduation, $conn) {
        $sql = "UPDATE ".PostGraduationEntries::TBNAME." SET ".PostGraduationEntries::NAME." = :name,".PostGraduationEntries::DESCRIPTION." = :description, ".PostGraduationEntries::LINK." = :link,".PostGraduationEntries::IMAGE." = :image";
        $sql = $sql." WHERE ".PostGraduationEntries::ID." = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":name", $postGraduation->getName());
        $stmt->bindValue(":description", $postGraduation->getDescription());
        $stmt->bindValue(":link", $postGraduation->getLink());
        $stmt->bindValue(":image", $postGraduation->getFeatured_image());
        $stmt->bindValue(":id", $postGraduation->getId());

        return $stmt->execute();
    }

    public function deletePostGraduation($postGraduation, $conn) {
        $sql = "DELETE FROM ".PostGraduationEntries::TBNAME." WHERE ".PostGraduationEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $postGraduation->getId());
        return $stmt->execute();
    }

    public function getAll($conn) {
        $sql = "SELECT * FROM ".PostGraduationEntries::TBNAME;
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "PostGraduation", array(PostGraduationEntries::ID,PostGraduationEntries::NAME,PostGraduationEntries::DESCRIPTION,PostGraduationEntries::LINK,PostGraduationEntries::IMAGE));
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPostGraduation($postGraduation, $conn) {
        $sql = "SELECT * FROM ".PostGraduationEntries::TBNAME." WHERE ".PostGraduationEntries::ID." = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $postGraduation->getId());
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "PostGraduation", array(PostGraduationEntries::ID,PostGraduationEntries::NAME,PostGraduationEntries::DESCRIPTION,PostGraduationEntries::LINK,PostGraduationEntries::IMAGE));
        $stmt->execute();
        return $stmt->fetch();
    }

}

class PostGraduationEntries {
    const TBNAME = "postgraduations";
    const ID = "id";
    const NAME = "name";
    const DESCRIPTION = "description";
    const LINK = "link";
    const IMAGE = "featured_image";
}

?>
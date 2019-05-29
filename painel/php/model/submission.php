<?php

class Submission implements JsonSerializable{

    private $user_cpf;
    private $id;
    private $event_id;
    private $title;
    private $description;
    private $keywords;
    private $authors;
    private $type;
    private $file;
    private $status;

    public function __construct($user_cpf, $id, $event_id, $title, $description, $keywords, $authors, $type, $file, $status)
    {   
        $this->user_cpf = $user_cpf;
        $this->id = $id;
        $this->event_id = $event_id;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->authors = $authors;
        $this->type = $type;
        $this->file = $file;
        $this->status = $status;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of keywords
     */ 
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set the value of keywords
     *
     * @return  self
     */ 
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get the value of authors
     */ 
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set the value of authors
     *
     * @return  self
     */ 
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of user_cpf
     */ 
    public function getUser_cpf()
    {
        return $this->user_cpf;
    }

    /**
     * Set the value of user_cpf
     *
     * @return  self
     */ 
    public function setUser_cpf($user_cpf)
    {
        $this->user_cpf = $user_cpf;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of event_id
     */ 
    public function getEvent_id()
    {
        return $this->event_id;
    }

    /**
     * Set the value of event_id
     *
     * @return  self
     */ 
    public function setEvent_id($event_id)
    {
        $this->event_id = $event_id;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}

?>
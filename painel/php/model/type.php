<?php

class Type implements JsonSerializable {

    private $id;
    private $name;
    private $event_id;

    public function __construct($id, $name, $event_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->event_id = $event_id;
    }   

    public function jsonSerialize()
    {
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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

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
}

?>
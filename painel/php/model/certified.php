<?php

class Certified implements JsonSerializable { 

    private $user_cpf;
    private $event_id;
    private $link;

    public function __construct($user_cpf, $event_id, $link) { 
        $this->user_cpf = $user_cpf;
        $this->event_id = $event_id;
        $this->link = $link;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
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
     * Get the value of link
     */ 
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @return  self
     */ 
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }
}

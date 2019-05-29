<?php

class AssistLink implements JsonSerializable {

    private $link;
    private $event_id;

    public function __construct($link, $event_id)
    {   
        $this->link = $link;
        $this->event_id = $event_id;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
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
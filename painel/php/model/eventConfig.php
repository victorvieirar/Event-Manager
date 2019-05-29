<?php

class EventConfig implements JsonSerializable {

    private $event_id;
    private $traveling;

    public function __construct($event_id, $traveling)
    {
        $this->event_id = $event_id;
        $this->traveling = $traveling;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
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
     * Get the value of traveling
     */ 
    public function getTraveling()
    {
        return $this->traveling;
    }

    /**
     * Set the value of traveling
     *
     * @return  self
     */ 
    public function setTraveling($traveling)
    {
        $this->traveling = $traveling;

        return $this;
    }
}

?>
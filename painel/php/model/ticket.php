<?php

class Ticket implements JsonSerializable {

    private $id;
    private $name;
    private $description;
    private $price;
    private $initialDate;
    private $finalDate;
    private $event_id;

    public function __construct($id, $name, $description, $price, $initialDate, $finalDate, $event_id) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->initialDate = $initialDate;
        $this->finalDate = $finalDate;
        $this->event_id = $event_id;
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
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

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
     * Get the value of initialDate
     */ 
    public function getInitialDate()
    {
        return $this->initialDate;
    }

    /**
     * Set the value of initialDate
     *
     * @return  self
     */ 
    public function setInitialDate($initialDate)
    {
        $this->initialDate = $initialDate;

        return $this;
    }

    /**
     * Get the value of finalDate
     */ 
    public function getFinalDate()
    {
        return $this->finalDate;
    }

    /**
     * Set the value of finalDate
     *
     * @return  self
     */ 
    public function setFinalDate($finalDate)
    {
        $this->finalDate = $finalDate;

        return $this;
    }
}

?>
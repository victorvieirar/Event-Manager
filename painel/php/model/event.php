<?php

/**
 * Requires 
 * model/city.php and
 * model/state.php
 * to works perfectly
 */
class Event implements JsonSerializable {

    private $id;
    private $name;
    private $date;
    private $endDate;
    private $city;
    private $description;
    private $deadline;
    private $featured_image;
    private $allow_submissions;
    private $subscription_limit;

    public function __construct($id, $name, $date, $endDate, $city, $description, $featured_image, $allow_submissions, $subscription_limit) {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->endDate = $endDate;
        $this->city = $city;
        $this->description = $description;
        $this->featured_image = $featured_image;
        $this->allow_submissions = $allow_submissions;
        $this->subscription_limit = $subscription_limit;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
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
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
    
    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
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
     * Get the value of deadline
     */ 
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set the value of deadline
     *
     * @return  self
     */ 
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get the value of featured_image
     */ 
    public function getFeatured_image()
    {
        return $this->featured_image;
    }

    /**
     * Set the value of featured_image
     *
     * @return  self
     */ 
    public function setFeatured_image($featured_image)
    {
        $this->featured_image = $featured_image;

        return $this;
    }

    /**
     * Get the value of allow_submissions
     */ 
    public function getAllow_submissions()
    {
        return $this->allow_submissions;
    }

    /**
     * Set the value of allow_submissions
     *
     * @return  self
     */ 
    public function setAllow_submissions($allow_submissions)
    {
        $this->allow_submissions = $allow_submissions;

        return $this;
    }

    /**
     * Get the value of subscription_limit
     */ 
    public function getSubscription_limit()
    {
        return $this->subscription_limit;
    }

    /**
     * Set the value of subscription_limit
     *
     * @return  self
     */ 
    public function setSubscription_limit($subscription_limit)
    {
        $this->subscription_limit = $subscription_limit;

        return $this;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */ 
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}

?>
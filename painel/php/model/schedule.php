<?php

class Schedule {

    private $event_id;
    private $title;
    private $scheduleTime;
    private $finalScheduleTime;

    private $months = array(
        'jan',
        'fev',
        'mar',
        'abr',
        'mai',
        'jun',
        'jul',
        'ago',
        'set',
        'out',
        'nov',
        'dez'
    );

    public function __construct($event_id, $title, $scheduleTime, $finalScheduleTime)
    {
        $this->event_id = $event_id;
        $this->title= $title;
        $this->scheduleTime = $scheduleTime;
        $this->finalScheduleTime = $finalScheduleTime;
    }

    public function getFormattedMonth() {
        $month = explode("-", $this->scheduleTime)[1];
        return $this->months[$month-1];
    }

    public function getDate() {
        $date = explode(" ", $this->scheduleTime)[0];
        return $date;
    }

    public function getDay() {
        $day = explode('-', explode(" ", $this->scheduleTime)[0])[2];
        return $day;
    }

    public function getTime() {
        $time = explode(" ", $this->scheduleTime)[1];
        return $time;
    }

    public function getFinalTime() {
        $time = explode(" ", $this->finalScheduleTime)[1];
        return $time;
    }

    public function getEvent_id() {
        return $this->event_id;
    }

    public function setEvent_id($event_id) {
        $this->event_id = $event_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getScheduleTime() {
        return $this->scheduleTime;
    }

    public function setScheduleTime($scheduleTime) {
        $this->scheduleTime = $scheduleTime;
    }


    /**
     * Get the value of finalScheduleTime
     */ 
    public function getFinalScheduleTime()
    {
        return $this->finalScheduleTime;
    }

    /**
     * Set the value of finalScheduleTime
     *
     * @return  self
     */ 
    public function setFinalScheduleTime($finalScheduleTime)
    {
        $this->finalScheduleTime = $finalScheduleTime;

        return $this;
    }
}

?>
<?php

class Transaction implements JsonSerializable {

    private $id;
    private $user_cpf;
    private $transactionDate;
    private $paymentMethod;
    private $value;
    private $event_id;
    private $status;
    private $coupon_code;
    private $pagseguro_code;

    public function __construct($id, $user_cpf, $transactionDate, $paymentMethod, $value, $event_id, $status, $coupon_code, $pagseguro_code)
    {
        $this->id = $id;
        $this->user_cpf = $user_cpf;
        $this->transactionDate = $transactionDate;
        $this->paymentMethod = $paymentMethod;
        $this->value = $value;
        $this->event_id = $event_id;
        $this->status = $status;
        $this->coupon_code = $coupon_code;
        $this->pagseguro_code = $pagseguro_code;
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
     * Get the value of transactionDate
     */ 
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set the value of transactionDate
     *
     * @return  self
     */ 
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get the value of paymentMethod
     */ 
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set the value of paymentMethod
     *
     * @return  self
     */ 
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */ 
    public function setValue($value)
    {
        $this->value = $value;

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
     * Get the value of coupon_code
     */ 
    public function getCoupon_code()
    {
        return $this->coupon_code;
    }

    /**
     * Set the value of coupon_code
     *
     * @return  self
     */ 
    public function setCoupon_code($coupon_code)
    {
        $this->coupon_code = $coupon_code;

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
    
    /**
     * Get the value of pagseguro_code
     */
    public function getPagseguro_code()
    {
        return $this->pagseguro_code;
    }
    
    /**
     * Set the value of pagseguro_code
     *
     * @return  self
     */
    public function setPagseguro_code($pagseguro_code)
    {
        $this->pagseguro_code = $pagseguro_code;
        
        return $this;
    }
}

?>

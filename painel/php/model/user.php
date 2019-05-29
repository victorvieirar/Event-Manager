<?php

class User implements JsonSerializable {

    private $cpf;
    private $name;
    private $password;
    private $email;
    private $phone;
    private $estado_id;
    private $course;
    private $formation;

    public function __construct($cpf, $name, $password, $email, $phone, $estado_id, $course, $formation)
    {
        $this->cpf = $cpf;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->estado_id = $estado_id;
        $this->course = $course;
        $this->formation = $formation;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

    /**
     * Get the value of cpf
     */ 
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     *
     * @return  self
     */ 
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

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
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of estado_id
     */ 
    public function getEstado_id()
    {
        return $this->estado_id;
    }

    /**
     * Set the value of estado_id
     *
     * @return  self
     */ 
    public function setEstado_id($estado_id)
    {
        $this->estado_id = $estado_id;

        return $this;
    }

    /**
     * Get the value of course
     */ 
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set the value of course
     *
     * @return  self
     */ 
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get the value of formation
     */ 
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * Set the value of formation
     *
     * @return  self
     */ 
    public function setFormation($formation)
    {
        $this->formation = $formation;

        return $this;
    }
}

?>
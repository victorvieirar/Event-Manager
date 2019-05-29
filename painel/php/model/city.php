<?php

class City implements JsonSerializable { 

    private $id;
    private $nome;
    private $estado;

    public function __construct($id, $nome, $estado) { 
        $this->id = $id;
        $this->nome = $nome;
        $this->estado = $estado;
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
     * Get the value of nome
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of fu
     */ 
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of fu
     *
     * @return  self
     */ 
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }
}

?>
<?php 

namespace Application\Jwt;

class Payload
{
    private $sub;

    private $iss;

    private $exp;

    private $iat;

    private $aud;

    /**
     * @param object $data
     */
    public function __construct(\stdClass $data)
    {   
        $this->sub = isset($data->sub) ? $data->sub : '';
        $this->iss = isset($data->iss) ? $data->iss : '';
        $this->exp = isset($data->exp) ? $data->exp : '';
        $this->iat = isset($data->iat) ? $data->iat : '';
        $this->aud = isset($data->aud) ? $data->aud : '';
    }

    /**
     * Get the value of sub
     */ 
    public function getSub()
    {
        return $this->sub;
    }

    /**
     * Set the value of sub
     *
     * @return  self
     */ 
    public function setSub($sub)
    {
        $this->sub = $sub;
        return $this;
    }

    /**
     * Get the value of iss
     */ 
    public function getIss()
    {
        return $this->iss;
    }

    /**
     * Set the value of iss
     *
     * @return  self
     */ 
    public function setIss($iss)
    {
        $this->iss = $iss;
        return $this;
    }

    /**
     * Get the value of exp
     */ 
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * Set the value of exp
     *
     * @return  self
     */ 
    public function setExp($exp)
    {
        $this->exp = $exp;
        return $this;
    }

    /**
     * Get the value of iat
     */ 
    public function getIat()
    {
        return $this->iat;
    }

    /**
     * Set the value of iat
     *
     * @return  self
     */ 
    public function setIat($iat)
    {
        $this->iat = $iat;
        return $this;
    }

    /**
     * Get the value of aud
     */ 
    public function getAud()
    {
        return $this->aud;
    }

    /**
     * Set the value of aud
     *
     * @return  self
     */ 
    public function setAud($aud)
    {
        $this->aud = $aud;
        return $this;
    }
}
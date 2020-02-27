<?php 

namespace Application\Jwt;

class Payload
{
    private $sub;

    private $name;

    private $admin;

    private $issuedAt;

    private $expiration;

    private $audience;
    
    /**
     * @param object $data
     */
    public function __construct(\stdClass $data)
    {   
        $this->sub = isset($data->sub) ? $data->sub : '';
        $this->name = isset($data->name) ? $data->name : '';
        $this->admin = isset($data->admin) ? $data->admin : '';
        $this->issuedAt = isset($data->issued_at) ? $data->issued_at : '';
        $this->expiration = isset($data->expiration) ? $data->expiration : '';
        $this->audience = isset($data->audience) ? $data->audience : '';
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
     * Get the value of admin
     */ 
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */ 
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get the value of issuedAt
     */ 
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set the value of issuedAt
     *
     * @return  self
     */ 
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get the value of expiration
     */ 
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set the value of expiration
     *
     * @return  self
     */ 
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get the value of audience
     */ 
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * Set the value of audience
     *
     * @return  self
     */ 
    public function setAudience($audience)
    {
        $this->audience = $audience;

        return $this;
    }
}
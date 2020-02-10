<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="tags")
 **/
class Tag
{
    /** 
     * @Id @Column(type="integer") 
     * @GeneratedValue 
     */
    protected $id;

    /** 
     * @Column(type="string") 
     */
    protected $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
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
}
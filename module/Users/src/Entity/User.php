<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Hydrator\ClassMethods;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity 
 **/
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=50, nullable=true)
     */
    protected $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=70, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    protected $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=50, nullable=true)
     */
    protected $active;

    /**
     * Cartao constructor.
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        if (!empty($input)) {
            $this->exchangeArray($input);
            $this->createdAt = new \Datetime();
            $this->updatedAt = new \Datetime();
        }
    }

    /**
     * @param $input
     */
    public function exchangeArray(array $input)
    {
        $hydrator = new ClassMethods(false);
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());
        $hydrator->hydrate($input, $this);
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        $extractData =  (new ClassMethods(true))->extract($this);
        $extractData['password'] = '';    
        return $extractData;
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
     * Get the value of nickname
     */ 
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set the value of nickname
     *
     * @return  self
     */ 
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }
    
    /**
     * Get the value of slug email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of slug email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;
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
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $password;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedAtFormat(): string
    {
        $createdAt = $this->createdAt;

        if ($createdAt instanceof \DateTime) {
            $createdAt = $createdAt->format('Y-m-d H:i:s');
        }
        return (string)$createdAt;
    }

    /**
     * @return self
     */
    public function setCreatedAt(): User
    {
        $this->createdAt = (new \DateTime)->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }   

    /**
     * @return string
     */
    public function getUpdatedAtFormat(): string
    {
        $updatedAt = $this->updatedAt;

        if ($updatedAt instanceof \DateTime) {
            $updatedAt = $updatedAt->format('Y-m-d H:i:s');
        }
        return (string)$updatedAt;
    }

    /**
     * @return self
     */
    public function setUpdatedAt(): User
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get the value of active
     */ 
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @return  self
     */ 
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
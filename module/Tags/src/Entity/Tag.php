<?php

namespace Tags\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Hydrator\ClassMethods;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @ORM\Table(name="tags")
 * @ORM\Entity 
 **/
class Tag
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    protected $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=60, nullable=true)
     */
    protected $slug;

    /**
     * @ORM\Column(name="creation_date", type="date", nullable=true)
     */
    protected $creationDate;

    /**
     * @ORM\Column(name="creation_time", type="time", nullable=true)
     */
    private $creationTime;


    /**
     * Cartao constructor.
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        if (!empty($input)) {
            $this->exchangeArray($input);
            $this->creationDate = new \Datetime();
            $this->creationTime = new \Datetime();
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
        //unset($extractData['created_at']);
        //unset($extractData['updated_at']);
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
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of user id
     */ 
    public function getUserId()
    {
        return $this->userId;
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
     * Get the value of slug name
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug name
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @return string
     */
    public function setCreationDate(): string
    {
        $creationDate = $this->creationDate;

        if ($creationDate instanceof \DateTime) {
            $creationDate = $creationDate->format('Y-m-d H:i:s');
        }
        return (string)$creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreationTime(): \DateTime
    {
        return $this->creationTime;
    }

    /**
     * @return string
     */
    public function setCreationTime(): string
    {
        $creationTime = $this->creationTime;

        if ($creationTime instanceof \DateTime) {
            $creationTime = $creationTime->format('Y-m-d H:i:s');
        }
        return (string)$creationTime;
    }
}
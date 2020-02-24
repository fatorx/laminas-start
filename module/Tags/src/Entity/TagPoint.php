<?php

namespace Tags\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Hydrator\ClassMethods;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @ORM\Entity 
 * @ORM\Table(name="tags_points")
 **/
class TagPoint
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
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     */
    protected $tagId;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    protected $points;

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
     * Set the value of tag id
     *
     * @return  self
     */ 
    public function setTagId($tagId)
    {
        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Get the value of points
     */ 
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set the value of points
     *
     * @return  self
     */ 
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get the value of tag id
     */ 
    public function getTagId()
    {
        return $this->tagId;
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
    public function setCreatedAt(): TagPoint
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
    public function setUpdatedAt(): TagPoint
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }
}
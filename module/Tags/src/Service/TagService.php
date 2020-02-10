<?php declare(strict_types=1);

namespace Tags\Service;

use Doctrine\ORM\EntityManager;

use Tags\Entity\Tag;

/**
 * Class TagService
 * @package Tags\Service
 */
class TagService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * TagService constructor.
     */
    public function __construct() 
    {

    }

    /**
     * @return array
     */
    public function getListTags()
    {
        return $this->executeSql("SELECT * FROM tags LIMIT 10", "all");
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function saveTag(array $pars)
    {
        $tag = new Tag($pars);
        $this->em->persist($tag);
        $this->em->flush();
        
        return $tag->getId() > 0;
    }

    /**
     * @param EntityManager
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * @param $sql
     * @param string $get
     * @return array|bool|mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function executeSql($sql, $get = 'unique')
    {
        $stmt = $this->em->getConnection()->prepare($sql);

        try {
            $stmt->execute();
        } catch (\Exception $e) {
            echo $e->getMessage(); exit();
        }

        if ($get == 'all') {
            return $stmt->fetchAll();
        }

        if ($get == null) {
            return true;
        }
        
        return $stmt->fetch();
    }
}    
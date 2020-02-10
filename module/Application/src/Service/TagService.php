<?php declare(strict_types=1);

namespace Application\Service;

use Doctrine\ORM\EntityManager;

use Application\Entity\Tag;

/**
 * Class TagService
 * @package Application\Service
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
     * @param EntityManager
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getListTags()
    {
        return $this->executeSql("SELECT * FROM tags LIMIT 10", "all");
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
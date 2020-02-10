<?php declare(strict_types=1);

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class TagService
 * @package Application\Service
 */
class BaseService
{
    /**
     * @var EntityManager
     */
    protected $em;

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
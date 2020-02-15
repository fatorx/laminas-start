<?php

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
     * Flag for check status operation in services.
     * 
     * @var bool
     */
    protected $status = true;

    /**
     * @var int
     */
    protected $userId = 0;

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

    /**
     * @return bool
     */
    public function getStatus() : bool
    {
        return $this->status;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}    
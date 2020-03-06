<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class TagService
 * @package Application\Service
 */
class BaseService implements IService
{
    /**
     * @var array
     */
    protected $config = [];

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
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param EntityManager
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
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
     * @param $table
     * @param $data
     * @return bool
     */
    public function insert($table, $data)
    {
        $status = true;
        try {
            $this->em->getConnection()->insert($table, $data);
        } catch (\Exception $e) {
            //$this->logError(__CLASS__, __METHOD__, $e->getMessage());
            $status = false;
        }
        return $status;
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

    /**
     * @return string
     */
    public function getDateTime()
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}    
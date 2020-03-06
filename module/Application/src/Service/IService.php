<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;

interface IService 
{
    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em);

    /**
     * @param array $config
     */
    public function setConfig(array $config);
}
<?php

namespace Application\Service;

use Laminas\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

class Config 
{
    const CONFIG_KEY = 'config';

    /**
     * @param ServiceManager $serviceManager
     * @param IService $service
     * 
     * @return IService
     */
    public function setup(ServiceManager $serviceManager, IService $service)
    {
        $config = $serviceManager->get(self::CONFIG_KEY);
        $service->setConfig($config['app']);

        $entityManager = $serviceManager->get(EntityManager::class);
        $service->setEm($entityManager);
        
        return $service;
    }
}
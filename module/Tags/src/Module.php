<?php

namespace Tags;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventInterface;

use Laminas\Escaper\Escaper;
use Doctrine\ORM\EntityManager;

use Tags\Service\TagService;

class User {

}

class Module
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritDoc
     */
    public function onBootstrap(EventInterface $e)
    {
        

    }    

    public function getServiceConfig()
    {
        return [
            'factories' => [
                TagService::class => function(ServiceManager $serviceManager) {
                    $tagService = new TagService();
                    $entityManager = $serviceManager->get(EntityManager::class);
                    $tagService->setEm($entityManager);
                    return $tagService;
                }
            ]
        ];
    }     
    
    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'Application'
        ];
    }
}

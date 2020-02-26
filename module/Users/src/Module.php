<?php

namespace Users;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManager;
use Laminas\Mvc\MvcEvent;
use Laminas\Escaper\Escaper;
use Doctrine\ORM\EntityManager;

use Users\Service\UserService;

class Module
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}"); 
            }
            exit(0);
        }
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserService::class => function(ServiceManager $serviceManager) {
                    $userService = new UserService();
                    $entityManager = $serviceManager->get(EntityManager::class);
                    $userService->setEm($entityManager);
                    return $userService;
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

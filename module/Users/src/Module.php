<?php

namespace Users;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManager;
use Laminas\Mvc\MvcEvent;
use Laminas\Escaper\Escaper;
use Doctrine\ORM\EntityManager;

use Users\Controller\UsersController;
use Users\Controller\TokenController;

use Application\Service\Config as ConfigService;
use Users\Service\UserService;
use Users\Service\TokenService;

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
                    return (new ConfigService())->setup($serviceManager, new UserService());
                },
                TokenService::class => function(ServiceManager $serviceManager) {
                    return (new ConfigService())->setup($serviceManager, new TokenService());
                }
            ]
        ];
    }   

    public function getControllerConfig()
    {
        return [
            'factories' => [
                UsersController::class => function(ServiceManager $serviceManager) {
                    $userService = $serviceManager->get(UserService::class);

                    $controller = new UsersController(
                        $userService
                    );

                    return $controller;
                },
                TokenController::class => function(ServiceManager $serviceManager) {
                    $tokenService = $serviceManager->get(TokenService::class);

                    $controller = new TokenController(
                        $tokenService
                    );
                    return $controller;
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

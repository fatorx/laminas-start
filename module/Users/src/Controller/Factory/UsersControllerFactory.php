<?php

namespace Users\Controller\Factory;

use Laminas \ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

use Users\Controller\UsersController as Controller;
use Users\Service\UserService;

class UsersControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Controller(
            $container->get(UserService::class),
        );
    }
}

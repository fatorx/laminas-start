<?php

namespace Users\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

use Users\Controller\TokenController as Controller;
use Users\Service\TokenService;

class TokenControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Controller(
            $container->get(TokenService::class),
        );
    }
}

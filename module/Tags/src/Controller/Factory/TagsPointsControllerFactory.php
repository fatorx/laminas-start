<?php

namespace Tags\Controller\Factory;

use Laminas \ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

use Tags\Controller\TagsPointsController as Controller;
use Tags\Service\TagService;

class TagsPointsControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Controller(
            $container->get(TagService::class),
        );
    }
}

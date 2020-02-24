<?php

// include the composer autoloader for autoloading packages
require_once(__DIR__ . '/vendor/autoload.php');

function getEntityManager() : \Doctrine\ORM\EntityManager
{
    $entityManager = null;

    if ($entityManager === null)
    {
        $paths = [
            __DIR__ . '/module/Application/src/Entity',
            __DIR__ . '/module/Tags/src/Entity',
        ];
        $isDevMode = true;
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        
        $configLocal = require_once __DIR__ .'/config/autoload/local.php';
        $dbParams = $configLocal['doctrine']['connection']['orm_default']['params'];
        $dbParams['driver'] = 'pdo_mysql';
        
        $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
    }

    return $entityManager;
}
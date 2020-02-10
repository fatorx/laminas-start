<?php

// include the composer autoloader for autoloading packages
require_once(__DIR__ . '/vendor/autoload.php');

function getEntityManager() : \Doctrine\ORM\EntityManager
{
    $entityManager = null;

    if ($entityManager === null)
    {
        $paths = array(__DIR__ . '/src/Entities');
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);
        
        $configLocal = require_once __DIR__ .'/config/autoload/local.php';
        $dbParams = $configLocal['doctrine']['connection']['orm_default']['params'];
        $dbParams['driver'] = 'pdo_mysql';
        
        $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config);
    }

    return $entityManager;
}
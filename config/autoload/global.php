<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'      => 'localhost',
                    'dbname'    => 'database',
                    'user'      => 'user',
                    'password'  => 'password',
                    'port' => '3306',
                    
                ]
            ],            
        ],        
    ],
    'ApiRequest' => [
        'responseFormat' => [
            'statusKey' => 'status',
            'statusOkText' => true,
            'statusNokText' => false,
            'resultKey' => 'result',
            'messageKey' => 'message',
            'defaultMessageText' => 'Empty response!',
            'errorKey' => 'error',
            'defaultErrorText' => 'Unknown request!',
            'authenticationRequireText' => 'Authentication Required.',
            'pageNotFoundKey' => 'Request Not Found.'
        ],
        'jwtAuth' => [
            'cypherKey' => 'your_cypher_key',
            'tokenAlgorithm' => 'HS256'
        ],
    ],
    'app' => [
        'app-key' => '',
    ],
];
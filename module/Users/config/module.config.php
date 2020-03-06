<?php

declare(strict_types=1);

namespace Users;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'token' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/token',
                    'defaults' => [
                        'controller' => Controller\TokenController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/users[/:id]',
                    'defaults' => [
                        'controller'    => Controller\UsersController::class,
                        'isAuthorizationRequired' => true, 
                        'methodsAuthorization'    => ['GET', 'PUT', 'DELETE'], 
                    ],
                    'constraints' => [
                        'formatter' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ],
    ],
];

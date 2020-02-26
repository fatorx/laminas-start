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
                        'action' => 'token',
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
                        'methodsAuthorization'    => ['GET', 'PUT'], 
                    ],
                    'constraints' => [
                        'formatter' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\TokenController::class => InvokableFactory::class,
            Controller\UsersController::class => Controller\Factory\UsersControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => array(
            'ViewJsonStrategy',
        )
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

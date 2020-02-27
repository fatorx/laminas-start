<?php

declare(strict_types=1);

namespace Tags;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'tags' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/tags[/:id]',
                    'defaults' => [
                        'controller'    => Controller\TagsController::class,
                        'isAuthorizationRequired' => true 
                    ],
                    'constraints' => [
                        'formatter' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
            'tags-points' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/tags-points',
                    'defaults' => [
                        'controller'    => Controller\TagsPointsController::class,
                        'isAuthorizationRequired' => true 
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
            Controller\IndexController::class => InvokableFactory::class,
            Controller\TagsController::class => Controller\Factory\TagsControllerFactory::class,
            Controller\TagsPointsController::class => Controller\Factory\TagsPointsControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
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
    'data-fixture' => [
        'Tags_fixture' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Fixture',
    ],
];

<?php

return array(
	'doctrine' => array(
		'driver' => array(
			'criteriadesigner_entity'  => array(
				'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'paths' => array(
				    __DIR__ . '/../CriteriaDesigner/src/CriteriaDesigner/Entity'
				)
			),
			'orm_default' => array(
				'drivers' => array(
					'CriteriaDesigner\Entity'  => 'criteriadesigner_entity',
				)
			)
		)
	),
    'router' => array(
        'routes' => array(
            'criteriadesigner' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/criteriadesigner',
                    'defaults' => array(
                        'controller' => 'IndexController',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'letsgo' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/letsgo',
                            'defaults' => array(
                                'controller' => 'IndexController',
                                'action' => 'criteriadesigner',
                            ),
                        ),
                    ),
                    'schemabuilder' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/schemabuilder',
                            'defaults' => array(
                                'controller' => 'IndexController',
                                'action' => 'schemabuilder',
                            ),
                        ),
                    ),
                    'classrooms' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/classrooms',
                            'defaults' => array(
                                'controller' => 'ExamplesController',
                                'action'     => 'allclassrooms',
                            ),
                        ),
                    ),
                    'students' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/students',
                            'defaults' => array(
                                'controller' => 'ExamplesController',
                                'action'     => 'allstudents',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'cd_navigation' => 'CriteriaDesigner\Navigation\CriteriaDesignerNavigation'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'IndexController' => 'CriteriaDesigner\Controller\IndexController',
            'ExamplesController' => 'CriteriaDesigner\Controller\ExamplesController',
        ),
    ),
    'view_manager' => array(        
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/criteriadesigner'           => __DIR__ . '/../view/layout/criteriadesigner.phtml',
            'criteriadesigner/index/index' => __DIR__ . '/../view/criteriadesigner/index/index.phtml',
            'criteriadesigner/index/criteriadesigner' => __DIR__ . '/../view/criteriadesigner/index/criteriadesigner.phtml',
            'criteriadesigner/index/schemabuilder' => __DIR__ . '/../view/criteriadesigner/index/schemabuilder.phtml',
            'criteriadesigner/examples/allclassrooms' => __DIR__ . '/../view/criteriadesigner/examples/allclassrooms.phtml',
            'criteriadesigner/examples/allstudents' => __DIR__ . '/../view/criteriadesigner/examples/allstudents.phtml'
        ),
    ),
    'navigation' => array(
        'cd_navigation' => array(
            "Criteria Designer" => array(
                'label' => "Home",
                'route' => 'criteriadesigner',
            ),
            "Let's start" => array(
                'label' => "Let's start",
                'route' => 'criteriadesigner/letsgo',
            ),
            "SchemaBuilder" => array(
                'label' => "Schema Builder",
                'route' => 'criteriadesigner/schemabuilder',
            ),
            "All Classes" => array(
                'label' => "All Classes",
                'route' => 'criteriadesigner/classrooms',
            ),
            "All Students" => array(
                'label' => "All Students",
                'route' => 'criteriadesigner/students',
            )
        )
    )
);

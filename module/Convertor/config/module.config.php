<?php

return array(
     'controllers' => array(
         'invokables' => array(
             'Convertor\Controller\Convertor' => 'Convertor\Controller\ConvertorController',
         ),
     ),
     'router' => array(
         'routes' => array(
             'convertor' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '',
                     'defaults' => array(
                         'controller' => 'Convertor\Controller\Convertor',
                         'action'     => 'index',
                     ),
                 ),
         ),
             'home' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '',
                     'defaults' => array(
                         'controller' => 'Convertor\Controller\Convertor',
                         'action'     => 'index',
                     ),
                 ),
             ),
/*
             'dataready' => array(
                 'type'    => 'segment',
                 'options' => array(
                     //'route'    => '/convertor[/:action][/:id]',
                     'route'    => '',
                     'defaults' => array(
                         'controller' => 'Convertor\Controller\Convertor',
                         'action'     => 'dataready',
                     ),
                 ),
             ),
*/
     ),
    ),
     'view_manager' => array(
         'template_path_stack' => array(
             'convertor' => __DIR__.'/../view',
         ),
     ),

 );

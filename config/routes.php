<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'Pages',
    function (RouteBuilder $routes) {
        $routes->fallbacks();

        $routes->connect('/pages/:action/*', ['controller' => 'Pages']);
        $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'index']);
        $routes->connect('/pages', ['controller' => 'Pages', 'action' => 'index']);

        $routes->prefix(
            'admin',
            function (RouteBuilder $routes) {
                $routes->connect('/:controller', ['action' => 'index']);
                $routes->connect('/:controller/:action/*');

                $routes->connect('/pages/:action/*', ['controller' => 'Pages']);
                $routes->connect('/pages/', ['controller' => 'Pages', 'action' => 'index']);
                $routes->connect('/pages', ['controller' => 'Pages', 'action' => 'index']);
            }
        );
    }
);

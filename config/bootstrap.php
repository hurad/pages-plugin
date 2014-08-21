<?php

use Utils\Lib\Navigation;

Navigation::addMenu(
    'pages',
    __d('pages', 'Pages'),
    '#',
    'manage_pages',
    ['class' => 'glyphicon glyphicon-file']
);
Navigation::addSubMenu(
    'pages',
    'all_pages',
    __d('pages', 'All Pages'),
    ['plugin' => 'Pages', 'prefix' => 'admin', 'controller' => 'Pages', 'action' => 'index'],
    'manage_pages'
);
Navigation::addSubMenu(
    'pages',
    'add_new_page',
    __d('pages', 'Add New'),
    ['plugin' => 'Pages', 'prefix' => 'admin', 'controller' => 'Pages', 'action' => 'add'],
    'publish_pages'
);

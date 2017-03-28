<?php
/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:29
 */


/**
 * Store route
 */
return [
    'create' => 'index/create',
    'list'  => 'index/list',
    'delete/' => 'index/delete/$1',
//    'delete/([0-9]+)' => 'index/delete/$1',
    'destroy' => 'index/destroy',
];
<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:37
 */
namespace Acme\Controller;

use App\Controller;
use App\Logger\Logger;

/**
 * Class IndexController
 * @package Acme\Controller
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        $this->logger()->info('test');

        $this->render('index');
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:37
 */
namespace Acme\Controller;

use App\Controller;
use App\Redis\RedisClient;
use App\Session\SessionManager;

/**
 * Class IndexController
 * @package Acme\Controller
 */
class IndexController extends Controller
{
    public function createAction()
    {
        $data = $this->getSendData();

        // sended data
        if ($data) {

            $key = $data['key'];       // session key
            $value = $data['value'];    // session value

            $this->session->set($key, $value);

            return $this->redirect('/create');
        }

        return $this->render('create');
    }

    /**
     * List session data
     *
     * @return bool
     */
    public function listAction()
    {
        // session data as array
        $data = $this->session->getListData();

        return $this->render('list', $data);
    }

    /**
     * Delete session data by $key
     *
     * @param $key
     */
    public function deleteAction($key)
    {
        $this->session->unsetKey($key);

        return $this->redirect('/create');
    }
}
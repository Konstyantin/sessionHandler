<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:37
 */
namespace Acme\Controller;

use App\Controller;

/**
 * Class IndexController
 *
 * @package Acme\Controller
 */
class IndexController extends Controller
{
    /**
     * Create new session data
     *
     * @return resource|bool
     */
    public function createAction()
    {
        $data = $this->getSendData();

        if ($this->isSubmit()) {
            // sended data
            $data = $this->getSendData();

            if ($data) {

                $key = $data['key'];        // session key
                $value = $data['value'];    // session value

                // set data by key and value
                $this->session->set($key, $value);

                return $this->redirect('/list');
            }

            $message = $this->setFlashMessage('data is empty');
            return $this->render('create', $message);

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
     * Destroys all data registered to a session
     *
     * @return resource redirect to create page
     */
    public function destroyAction()
    {
        //destroy all session data
        $this->session->destroy();

        return $this->redirect('/create');
    }

    /**
     * Delete session data by $key
     *
     * @param $key
     *
     * @return resource redirect to create page
     */
    public function deleteAction($key)
    {
        $this->session->unsetKey($key);

        return $this->redirect('/create');
    }
}
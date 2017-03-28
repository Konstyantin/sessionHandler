<?php
/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:52
 */

namespace App;

use App\Logger\Logger;
use App\Redis\RedisClient;
use App\Session\SessionManager;

/**
 * Class Controller
 * @package App
 */
abstract class Controller
{
    /**
     * Project path
     *
     * @var string $path
     */
    protected $path = '/kostya.nagula/project/sessionHandler/';

    /**
     * SessionManager
     *
     * @var SessionManager|bool
     */
    protected $session = false;

    /**
     * Controller constructor.
     *
     * Init session manager
     */
    public function __construct()
    {
        $redis = new RedisClient();

        $this->session = new SessionManager($redis);
    }

    /**
     * Return redirect response to the given URL
     *
     * @param string $url
     */
    protected function redirect(string $url)
    {
        return header('Location: ' . $this->path . $url);
    }

    /**
     * Return a render view
     *
     * @param string $view
     * @param null $data
     *
     * @return bool
     */
    protected function render(string $view, $data = null)
    {
        // path to directory which is store view files
        $path = ROOT . '/src/View/' . $view . '.phtml';
        if (file_exists($path)) {
            require_once (ROOT . '/app/layout/header.phtml');   // include header layout
            require_once ($path);
            require_once (ROOT . '/app/layout/footer.phtml');   // include footer layout
        }

        return false;
    }

    /**
     * Get send form data if data was send
     *
     * @return bool
     */
    protected function getSendData()
    {
        // check submit form
        if ($this->isSubmit()) {

            $data['key'] = $_POST['key'];       // key
            $data['value'] = $_POST['value'];   // value

            return $data; // array data
        }

        return false;
    }

    /**
     * Check is submitted form or not
     *
     * @return bool
     */
    protected function isSubmit()
    {
        return isset($_POST['submit']) ? true : false;
    }
}
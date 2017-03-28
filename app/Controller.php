<?php
/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:52
 */

namespace App;

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
    protected $path = '/kostya.nagula/project/sessionHandler';

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
     * Set custom redis client
     *
     * @param RedisClient $redisClient
     */
    protected function setRedisClient(RedisClient $redisClient)
    {
        $this->session  = new SessionManager($redisClient);
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
            require_once(ROOT . '/app/layout/header.phtml');   // include header layout
            require_once($path);
            require_once(ROOT . '/app/layout/footer.phtml');   // include footer layout
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
        if (isset($_POST['key']) && isset($_POST['value'])) {

            if (!$this->emptyData($_POST['key'])) {
                $data['key'] = $_POST['key'];       // key
            }

            if (!$this->emptyData($_POST['value'])) {
                $data['value'] = $_POST['value'];   // value
            }

            return isset($data) ? $data : false;    // array data
        }
    }

    /**
     * Return flash message
     *
     * @param string $message
     * @return string
     */
    protected function setFlashMessage(string $message)
    {
        return (string)$message;
    }

    /**
     * Check data is empty
     *
     * @param string $data
     * @return bool
     */
    private function emptyData(string $data)
    {
        return (empty($data)) ? true : false;
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
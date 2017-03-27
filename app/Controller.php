<?php
/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 27.03.17
 * Time: 21:52
 */

namespace App;
use App\Logger\Logger;

/**
 * Class Controller
 * @package App
 */
abstract class Controller
{
    /**
     * Return redirect response to the given URL
     *
     * @param string $url
     */
    protected function redirect(string $url)
    {
        return header('Location: ' . $url);
    }

    /**
     * Return a render view
     *
     * @param string $view
     * @param $data
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
    }

    /**
     * Return logger component
     *
     * @return Logger
     */
    protected function logger()
    {
        $logger = new Logger(ROOT . '/logs');
        return $logger;
    }
}
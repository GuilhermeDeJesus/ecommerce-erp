<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Util;

use Configuration\Configuration;
require_once 'config/Configuration.php';

class ApplicationUtil
{

    /**
     *
     * @param String $controller            
     */
    public static function controller($module, $controller)
    {
        $controller = ucfirst($controller);
        
        $file = str_replace('\\', '/', Configuration::PATH_SOURCE) . '/' . ucfirst(MODULE) . '/Controller/' . $controller . 'Controller.php';
        if (file_exists($file)) {
            require_once ($file);
            return $controller . "Controller";
        } else {
            die("Class " . $controller . "Controller not fount - 404");
        }
    }
}

?>
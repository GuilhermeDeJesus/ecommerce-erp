<?php
namespace Krypitonite\Controller;

require_once 'krypitonite/src/Util/ApplicationUtil.php';

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */

use Configuration\Configuration;
use Krypitonite\Util\ApplicationUtil;
use Krypitonite\Http\Request;
use Krypitonite\Util\FrameworkUtil;
use Krypitonite\Util\HTMLUtil;
require_once 'krypitonite/src/Util/FrameworkUtil.php';
require_once 'krypitonite/src/Util/HTMLUtil.php';
require_once 'config/Configuration.php';

abstract class AbstractController extends ApplicationUtil
{

    private $_controller;

    private $_response;

    private $_request;

    protected $daos;

    public $name;

    protected $_admin;

    public function __construct($admin = TRUE)
    {
        $securit = $this->isAdmin($admin);
        $this->_admin = FALSE;
        if ($securit == TRUE) {
            $this->_admin = TRUE;
            $this->isLogged($_SESSION);
        }
    }

    protected function dao($moduleName = null, $nameClass = null)
    {
        if (! $nameClass)
            $nameClass = $this->name;
        if (! isset($this->daos[$nameClass]))
            $this->daos[$nameClass] = FrameworkUtil::factoryDAO($moduleName, $nameClass);

        return $this->daos[$nameClass];
    }

    public function execute()
    {
        if (defined('ACTION')) {
            $action = ACTION . 'Action';
            if (method_exists($this, $action)) {
                return $this->$action();
            } else {
                die("Action " . $action . " not found - 404");
            }
        }
    }

    public function isAdmin($admin = TRUE, $renderView = TRUE)
    {
        $param = Request::get('c');
        $c = FALSE;
        if ($admin === TRUE) {
            if ($this->isLogged($_SESSION) === FALSE && $param != 'login') {
                $this->redirect('sistema', 'login');
            }
            $c = TRUE;
        }

        return $c;
    }

    public function hasAuthentication($session)
    {
        if (isset($session['cliente']) && $session['cliente']) {
            return true;
            // continue
        } else {
            $this->redirect('cliente', 'cliente', 'informacoes');
        }
    }

    public function requiredAuthentication()
    {
        return true;
    }

    public function renderView($view, Array $data = NULL, $_title = NULL, $_description = NULL, $modal = FALSE)
    {
        $dir = explode("\\", get_called_class());
        $this->_controller = str_replace('Controller', '', array_pop($dir));
        $dir = str_replace('\\', '/', Configuration::PATH_SOURCE) . '/' . $dir[1] . '/View/' . $this->_controller;

        if (! is_dir($dir)) {
            mkdir($dir);
        }

        $view = $dir . '/' . $view . '.php';
        if (file_exists($view)) {
            if ($this->_admin == FALSE && $modal == FALSE) {
                // if ($_description == NULL) {
                // $_description = "Milhares de apostadores jogam na Mega-Sena, Lotofácil, Quina e demais loterias do Brasil através do Lotérica Premiada. Aposte hoje de forma fácil e segura!";
                // }

                // if ($_title == NULL) {
                // $_title = "Loterias Online: Jogos de Loterias Online | Lotérica Premiada!";
                // }

                // HTMLUtil::headDefault($_title, $_description);
                // require_once 'src/Template/View/menu.php';
            }

            require_once ($view);
            return $data;
        } else {
            die("<div class='row'>
            <div class='col-md-7'>View " . $view . " not found - 404 <br> please create new view file '.$view'</div></div>");
        }
    }

    public function redirect($module = NULL, $controller = NULL, $action = NULL, $param = NULL)
    {
        $url = "?m=$module&c=$controller&a=$action";
        if (isset($param)) {
            $url = "?m=$module&c=$controller&a=$action&$param";
        }

        if (! headers_sent())
            header('Location: ' . $url);
        else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit();
        }
    }

    public function redirect_new($action = '', $param = '')
    {
        $url = "$action";
        if (isset($param)) {
            $url = "$action&$param";
        }

        if (! headers_sent())
            header('Location: ' . $url);
        else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit();
        }
    }

    public function isLogged($session)
    {
        if (isset($session['usuario']) && isset($session['senha'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    protected function post($name)
    {
        if (!isset($_POST['data'])) {
            return NULL;
        }

        $post = json_decode($_POST['data'], true);
        if (!is_array($post)) {
            return NULL;
        }

        foreach ($post as $p) {
            if (in_array($name, $p)) {
                if (isset($p['value']))
                    return $p['value'];
                else
                    return NULL;
            }
        }

        return NULL;
    }

    public function _setFilterRadio($_get = [])
    {
        $where = [];
        foreach ($_get as $key => $val) {
            if ($_get[$key] == 'sim' && $_get[$key] != 'n') {
                array_push($where, [
                    $key,
                    '=',
                    TRUE
                ]);
            } else if ($_get[$key] == 'nao' && $_get[$key] != 'n') {
                array_push($where, [
                    $key,
                    '=',
                    FALSE
                ]);
            }
        }

        return $where;
    }
}
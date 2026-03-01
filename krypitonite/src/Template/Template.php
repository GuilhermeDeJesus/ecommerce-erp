<?php
/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 26/05/2016
 * Time: 17:16
 */
namespace Krypitonite\Template;

use Krypitonite\Dao\Dao;
use Configuration\Configuration;
define('TAB', "\t");
define('NEW_LINE', "\n");

require_once 'krypitonite/src/Dao/Dao.php';

class Template extends Dao
{

    public function __construct()
    {}

    /**
     *
     * @param String $dir            
     */
    protected function _isDir($dir = NULL)
    {
        if (! is_dir($dir)) {
            mkdir($dir);
        }
    }

    /**
     *
     * @param String $moduleDir            
     * @return $dir
     */
    protected function _create_folders_module($moduleDir = NULL)
    {
        $dir = str_replace('\\', '/', Configuration::PATH_APPICATION) . '/src/' . ucfirst($moduleDir);
        
        $this->_isDir($dir);
        
        // FOLDER DAO
        $this->_isDir($dir . '/Dao');
        
        // FOLDER CONTROLLER
        // $this->_isDir($dir . '/Controller');
        
        // FOLDER VIEW
        // $this->_isDir($dir . '/View');
        
        // FOLDER CRALER
        // $this->_isDir($dir . '/Crawler');
        
        // FOLDER CRALER
        $this->_isDir($dir . '/Model');
    }

    /**
     *
     * @param String $fields            
     * @return $data
     */
    protected function _formatFieldName($field = NULL)
    {
        $data = [];
        $n = explode("_", $field);
        foreach ($n as $f) {
            array_push($data, ucfirst($f));
        }
        $data = implode('', $data);
        return $data;
    }
}
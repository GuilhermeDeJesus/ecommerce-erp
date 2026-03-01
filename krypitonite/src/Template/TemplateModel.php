<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Template;

use Krypitonite\Template\Template;
use Configuration\Configuration;

class TemplateModel extends Template
{

    public function tag($tableName, $nameModel, $fieldsModel, $moduleTable)
    {
        
        // \t tab - four espace
        // \n enter - new line
        $tag = "<?php " . NEW_LINE . NEW_LINE;
        
        // autor by class
        $tag .= '/** ' . NEW_LINE . ' * Created by Eclipse. ' . NEW_LINE . ' * User: guilherme ' . NEW_LINE . ' * Date : ' . date("d/m/Y H:i:s") . NEW_LINE . " */ " . NEW_LINE . NEW_LINE;
        
        // namespace and class Dao father
        $tag .= "namespace Store\\" . ucfirst($moduleTable) . "\Model;" . NEW_LINE . NEW_LINE;
        // $tag .= "use Krypitonite\Dao\Dao; " . NEW_LINE . NEW_LINE;
        
        // this class model,
        $tag .= "class $nameModel " . NEW_LINE . "{" . NEW_LINE . NEW_LINE;
        
        // PROPERTS
        foreach ($fieldsModel as $field) {
            // METHOD GET
            $tag .= TAB . 'public $' . $field . ' = "' . $field . '";' . NEW_LINE;
        }
        
        $tag .= NEW_LINE;
        
        // function __construct
        $tag .= TAB . 'public function __construct(){}' . NEW_LINE . NEW_LINE;
        
        // METHODS GET AND SET
        foreach ($fieldsModel as $field) {
            
            // METHOD GET
            $tag .= TAB . 'public function get' . $this->_formatFieldName($field) . '(){' . NEW_LINE . NEW_LINE;
            $tag .= TAB . TAB . 'return $this->' . $field . ';';
            $tag .= NEW_LINE . TAB . "}" . NEW_LINE . NEW_LINE;
            
            // METHOD SET
            $tag .= TAB . 'public function set' . $this->_formatFieldName($field) . '($' . $field . '){' . NEW_LINE . NEW_LINE;
            $tag .= TAB . TAB . '$this->' . $field . ' = $' . $field . ';' . NEW_LINE;
            $tag .= TAB . TAB . 'return $this->' . $field . ';';
            $tag .= NEW_LINE . TAB . "}" . NEW_LINE . NEW_LINE;
        }
        
        $tag .= "}"; // end class dao
        
        $filename = str_replace('\\', '/', Configuration::PATH_APPICATION) . '/src/' . ucfirst($moduleTable) . '/Model/' . $nameModel . '.php';
        
        if (file_exists($filename)) {
            return false;
        } else {
            file_put_contents($filename, $tag, FILE_APPEND);
            chmod($filename, 0755);
            echo "\n" . '<a href="#" style="font-size: 16px;">' . $filename . '</a>' . "<br>";
        }
    }

    public function generateModel($module = NULL)
    {
        $_models = $this->showTables();
        $data_count_tables = [];
        echo "<div class='alert alert-info' role='alert'><h4>Models classes created from database tables </h4></div>";
        foreach ($_models as $keyModel => $valueModel) {
            foreach ($valueModel as $_tableName => $fieldsTable) {
                
                $_table = explode('_', ucfirst($_tableName));
                $_modelName = '';
                
                // Table with five names
                if (count($_table) === 5) {
                    $_modelName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . ucfirst($_table[3]) . ucfirst($_table[4]) . 'CoreMODEL';
                }
                
                // Table with four names
                if (count($_table) === 4) {
                    $_modelName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . ucfirst($_table[3]) . 'CoreMODEL';
                }
                
                // Table with three names
                if (count($_table) === 3) {
                    $_modelName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . 'CoreMODEL';
                }
                
                // table with compound name
                if (count($_table) === 2) {
                    $_modelName = ucfirst($_table[0]) . ucfirst($_table[1]) . 'CoreMODEL';
                }
                
                // table so with a name
                if (count($_table) === 1) {
                    $_modelName = ucfirst($_table[0]) . 'CoreMODEL';
                }
                
                array_push($data_count_tables, count($_models)); // total models created
                                                                 
                // create modules
                                                                 // $module_table = explode("_", $_tableName);
                                                                 
                // if ($module == $module_table[0]) {
                $this->_create_folders_module($module);
                $this->tag($_tableName, $_modelName, $fieldsTable, $module);
                // }
            }
        }
    }
}
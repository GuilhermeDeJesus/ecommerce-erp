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
require_once 'krypitonite/src/Template/Template.php';

class TemplateDao extends Template
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     */
    public function tag($tableName, $nameDao, $fieldsDao, $moduleTable)
    {

        // \t tab - four espace
        // \n enter - new line
        $tag = "<?php " . NEW_LINE . NEW_LINE;

        // autor by class
        $tag .= '/** ' . NEW_LINE . ' * Created by Eclipse. ' . NEW_LINE . ' * User: guilherme ' . NEW_LINE . ' * Date : ' . date("d/m/Y H:i:s") . NEW_LINE . " */ " . NEW_LINE . NEW_LINE;

        // namespace and class Dao father
        $tag .= "namespace Store\\" . ucfirst($moduleTable) . "\Dao;" . NEW_LINE;
        $tag .= "use Krypitonite\Dao\Dao; " . NEW_LINE . NEW_LINE;
        $tag .= "require_once 'krypitonite/src/Dao/Dao.php'; " . NEW_LINE . NEW_LINE;

        // this class dao
        $tag .= "class $nameDao extends Dao " . NEW_LINE . "{" . NEW_LINE . NEW_LINE;

        // name table this class
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @var Constant $tableName ' . NEW_LINE . TAB . ' * Required - Is the table name refers to this class ' . NEW_LINE . TAB . ' */' . NEW_LINE;
        $tag .= TAB . 'const TABLE = ' . '"' . $tableName . '";' . NEW_LINE . NEW_LINE;

        // function __construct
        $tag .= TAB . 'public function __construct(){}' . NEW_LINE . NEW_LINE;

        // metodo magico __clone evita que a classe seja clonada
        $tag .= TAB . 'private function __clone(){}' . NEW_LINE . NEW_LINE;

        // function update dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @param Array $form ' . NEW_LINE . TAB . ' * @param Array $conditions ' . NEW_LINE . TAB . ' * @return Integer count changes ' . NEW_LINE . TAB . ' */' . NEW_LINE;
        $tag .= TAB . 'public function update(Array $form, Array $conditions){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::queryUpdate(self::TABLE, $form, $conditions);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function insert dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @param Array $form ' . NEW_LINE . TAB . NEW_LINE . ' */' . NEW_LINE;
        $tag .= TAB . 'public function insert(Array $form){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::queryInsert(self::TABLE, $form);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function insert dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @param Array $form ' . NEW_LINE . TAB . ' */' . NEW_LINE;
        $tag .= TAB . 'public function insertORUpdate(Array $form){' . NEW_LINE;
        $tag .= TAB . TAB . 'if (isset($form["id"]) && $form["id"] != NULL && $form["id"] != 0) {' . NEW_LINE;
        $tag .= TAB . TAB . TAB . 'self::queryUpdate(self::TABLE, $form, ["id", "=", (int) $form["id"]], FALSE);' . NEW_LINE;
        $tag .= TAB . TAB . TAB . 'return (int) $form["id"];' . NEW_LINE;
        $tag .= TAB . TAB . '} else {' . NEW_LINE;
        $tag .= TAB . TAB . TAB . 'unset($form["id"]);' . NEW_LINE;
        $tag .= TAB . TAB . TAB . 'return self::queryInsert(self::TABLE, $form);' . NEW_LINE;
        $tag .= TAB . TAB . '}' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function find dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . '* @param Array $fields ' . NEW_LINE . TAB . '* @param Array $conditions ' . NEW_LINE . TAB . '* @param Array $orderBy ' . NEW_LINE . TAB . '* @param Integer $limit ' . NEW_LINE . TAB . '* @return Array $fetchAll ' . NEW_LINE . TAB . '*/' . NEW_LINE;
        $tag .= TAB . 'public function select(Array $fields, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL, $groupBy = NULL){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::querySelect(self::TABLE, $fields, $conditions, $orderBy, $limit, $amount, $groupBy);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function find dao
        $tag .= TAB . 'public function getField($field = NULL, $id = NULL){' . NEW_LINE;
        $tag .= TAB . TAB . '$value = $this->select([$field], ["id", "=", $id]);' . NEW_LINE;
        $tag .= TAB . TAB . 'if(sizeof($value) != 0){' . NEW_LINE;
        $tag .= TAB . TAB . TAB . 'return $value[0][$field];' . NEW_LINE;
        $tag .= TAB . TAB . '}' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function find dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . '* @param Array $tableJoin ' . NEW_LINE . TAB . '* @param Array $on ' . NEW_LINE . TAB . '* @param Array $conditions ' . NEW_LINE . TAB . '* @param Array $orderBy ' . NEW_LINE . TAB . '* @param Integer $limit ' . NEW_LINE . TAB . '* @return Array $fetchAll ' . NEW_LINE . TAB . '*/' . NEW_LINE;
        $tag .= TAB . 'public function selectJoin($tableJoin, Array $on, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::querySelectInnerJOIN(self::TABLE, $tableJoin, $on, $conditions, $orderBy, $limit, $amount);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function delete dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @param Array $conditions' . NEW_LINE . TAB . ' */' . NEW_LINE;
        $tag .= TAB . 'public function delete(Array $conditions){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::queryDelete(self::TABLE, $conditions);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function countOcurrence dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . '* @param String $field ' . NEW_LINE . TAB . '* @param Array $conditions ' . NEW_LINE . TAB . '*/' . NEW_LINE;
        $tag .= TAB . 'public static function countOcurrence($field = "*", Array $conditions = NULL){' . NEW_LINE;
        $tag .= TAB . TAB . 'return self::queryCountOcurrence(self::TABLE, $field = "*", $conditions);' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE;

        // function fields dao
        $tag .= TAB . '/** ' . NEW_LINE . TAB . ' * @return Array All Fields' . NEW_LINE . TAB . ' */' . NEW_LINE;
        $tag .= TAB . 'public function getAllFields(){' . NEW_LINE . TAB . TAB . 'return [' . NEW_LINE;

        // inputs this table dao
        foreach ($fieldsDao as $field) {
            $tag .= TAB . TAB . TAB . TAB . '"' . $field . '",' . NEW_LINE;
        }

        $tag .= TAB . TAB . TAB . '];' . NEW_LINE;
        $tag .= TAB . "}" . NEW_LINE . NEW_LINE . "}"; // end class dao

        $filename = str_replace('\\', '/', Configuration::PATH_APPICATION) . '/src/' . ucfirst($moduleTable) . '/Dao/' . $nameDao . '.php';

        if (file_exists($filename)) {
            unlink($filename);
            file_put_contents($filename, $tag, FILE_APPEND);
            chmod($filename, 0755);
            echo '<a href="#" style="font-size: 16px;">' . $filename . '</a><br>';
        } else {
            file_put_contents($filename, $tag, FILE_APPEND);
            chmod($filename, 0755);
            echo '<a href="#" style="font-size: 16px;">' . $filename . '</a><br>';
        }
    }

    public function generateDao($module = NULL)
    {
        $_daos = $this->showTables();
        $data_count_tables = [];
        echo "<div class='alert alert-danger' role='alert'><h4>Daos classes created from database tables </h4></div>";
        foreach ($_daos as $keyDao => $valueDao) {
            foreach ($valueDao as $_tableName => $fieldsTable) {

                $_table = explode('_', ucfirst($_tableName));
                $_daoName = '';

                // Table with five names
                if (count($_table) === 5) {
                    $_daoName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . ucfirst($_table[3]) . ucfirst($_table[4]) . 'CoreDAO';
                }

                // Table with four names
                if (count($_table) === 4) {
                    $_daoName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . ucfirst($_table[3]) . 'CoreDAO';
                }

                // Table with three names
                if (count($_table) === 3) {
                    $_daoName = ucfirst($_table[0]) . ucfirst($_table[1]) . ucfirst($_table[2]) . 'CoreDAO';
                }

                // table with compound name
                if (count($_table) === 2) {
                    $_daoName = ucfirst($_table[0]) . ucfirst($_table[1]) . 'CoreDAO';
                }

                // table so with a name
                if (count($_table) === 1) {
                    $_daoName = ucfirst($_table[0]) . 'CoreDAO';
                }

                array_push($data_count_tables, count($_daos)); // total daos created

                // create modules
                // $module_table = explode("_", $_tableName);
                // if ($module == $module_table[0]) {
                $this->_create_folders_module($module);
                $this->tag($_tableName, $_daoName, $fieldsTable, $module);
                // }
            }
        }
    }
}
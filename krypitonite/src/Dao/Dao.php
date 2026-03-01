<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Dao;

use Doctrine\DBAL\Connection;
use Configuration\Configuration;
use Doctrine\DBAL\Driver\PDOException;
use Krypitonite\Log\Log;
use Doctrine\DBAL\Driver\SQLSrv\LastInsertId;
use PDO;
require_once 'krypitonite/src/Log/Log.php';
require_once 'config/Configuration.php';

/*
 * Only daos classes can extend this class
 * @name Abstract class Dao
 *
 */
abstract class Dao
{

    private static $_conection;

    // Dao
    private static $_pdo;

    private static $_table = [];

    public function __construct()
    {
        // self::$_conection = DataBaseAcces::conn();
        // self::$_table = self::showTable(); // o nome da classe sera o nome da tabela
    }

    protected static function getPDO()
    {
        $_host = Configuration::MYSQL_HOST;
        $_db = Configuration::MYSQL_DB;

        try {
            if (! isset(self::$_pdo)) {
                self::$_pdo = new PDO("mysql:host=$_host;dbname=$_db", Configuration::MYSQL_USER, Configuration::MYSQL_PASS, array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ));

                // self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$_pdo;
        } catch (PDOException $e) {
            lp($e->getMessage());
            Log::error('Error PDO ' . $e->getMessage());
        }
    }

    /**
     * Query Other Framework
     *
     * @param string $params
     * @return multitype:|boolean
     */
    public function query($query, $params = null, $log = true)
    {
        $sth = $this->getPDO()->prepare($query);

        if ($params) {
            for ($i = 0; $i < sizeof($params); $i ++) {
                $sth->bindParam($i + 1, $params[$i]);
            }
        }

        try {
            // execute
            $sth->execute();
        } catch (PDOException $e) {
            throw new PDOException($e);
        }

        if ('SELECT' == substr($query, 0, 6) || 'SHOW' == substr($query, 0, 4) || 'DESCRIBE' == substr($query, 0, 8)) {

            $data = $sth->fetchAll();
            foreach ($data as $id => $details)
                foreach ($details as $key => $value)
                    if (is_int($key))
                        unset($data[$id][$key]);
            return $data;
        }

        if ('INSERT' == substr($query, 0, 6)) {
            return $this->getPDO()->lastInsertId();
        }

        return true;
    }

    /**
     *
     * @param String $table
     * @param Array $form
     *            | ['field1' => 'value', 'field2' => 'value']
     * @return LastInsertId
     */
    protected static function queryInsert($table, Array $form)
    {
        try {

            if (count($form) == 0)
                die('Missing values in $form');

            $fieldsKey = implode(',', array_keys($form));
            $fieldsValue = implode(', :', array_keys($form));

            $sql = "INSERT INTO " . $table . " (" . $fieldsKey . ') VALUES (:' . $fieldsValue . ') ';
            Log::logError($sql);
            $smtp = self::getPDO()->prepare($sql);
            foreach ($form as $key => $values) {
                $smtp->bindValue(':' . $key, $values);
                Log::logError($sql);
            }

            $smtp->execute();
            return self::getPDO()->lastInsertId();
        } catch (PDOException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     *
     * @param String $table
     * @param Array $form
     *            | ['field1' => 'value', 'field2' => 'value']
     * @param Array $conditions
     *            | // [['id', '>', 0], ['nome', '=', 'guilherme']]
     * @return Integer count changes
     */
    protected static function queryUpdate($table, Array $form, Array $conditions, $returnRowCount = FALSE)
    {
        try {

            $sql = "UPDATE " . $table . " SET ";

            if (array_key_exists('id', $form))
                unset($form['id']);

            foreach ($form as $key => $value) {
                $sql .= $key . ' = :' . $key . ', ';
            }

            $sql = substr($sql, 0, strlen($sql) - 2);
            $sql .= self::clauseConditions($conditions);

            Log::logError($sql);
            $smtp = self::getPDO()->prepare($sql);

            foreach ($form as $keyf => $values) {
                $smtp->bindValue(':' . $keyf, $values);
                self::setBindValuesConditions($conditions, $smtp);
            }

            return $smtp->execute();

            if ($returnRowCount == TRUE)
                return $smtp->rowCount();
        } catch (PDOException $e) {
            Log::error('Error change ' . $e->getMessage());
        }
    }

    /**
     *
     * @param String $table
     * @param Array $conditions
     *            | // [['id', '>', 0], ['nome', '=', 'guilherme']]
     */
    protected static function queryDelete($table, Array $conditions)
    {
        try {

            if (FALSE === is_string($table))
                die('param $table pass given string');

            $sql = "DELETE FROM " . $table;

            $sql .= self::clauseConditions($conditions); // SET CONDITIONS

            Log::logError($sql);
            $smtp = self::getPDO()->prepare($sql);

            self::setBindValuesConditions($conditions, $smtp);

            $smtp->execute();

            return TRUE;
        } catch (PDOException $e) {
            Log::error('Error change ' . $e->getMessage());
        }
    }

    /**
     *
     * @param String $table
     * @param Array $fields
     *            | array('field1', 'field2', 'field3')
     * @param Array $conditions
     *            | // [['id', '>', 0], ['nome', '=', 'guilherme']]
     * @param Array $orderBy
     *            | ['field', 'DESC']
     * @param Integer $limit
     *            | VALUE INTEGER
     * @return Array $fetchAll
     */
    protected static function querySelect($table, Array $fields, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL, $groupBy = NULL)
    {
        try {
            // show code
            $sql = 'SELECT SQL_NO_CACHE ';

            if (count($fields) !== 0) {
                foreach ($fields as $field) {
                    $sql .= $field . ', '; // SET FIELDS
                }

                $sql = substr($sql, 0, strlen($sql) - 2);
            }

            if (FALSE === is_string($table))
                die('param $table pass given string in ' . __FILE__);

            $sql .= ' FROM ' . $table; // SET TABLE
            $where = self::clauseWhere($conditions);
            $sql .= $where['sql']; // SET CONDITIONS

            $sql .= self::clauseGroupBy($groupBy); // SER GROUP BY
            $sql .= self::clauseOrderBy($orderBy); // SER ORDER BY

            if (is_int($limit) && ! $amount) {
                $sql .= " LIMIT $limit"; // SET LIMIT
            } else if ($amount != NULL && is_int($amount) && is_int($limit)) {
                $sql .= " LIMIT $limit, $amount";
            } else if ($limit != NULL) {
                $sql .= " LIMIT $limit";
            }

            Log::logError($sql);
            $smtp = self::getPDO()->prepare($sql);

            if ($conditions !== NULL)
                self::setParams($where['params'], $smtp);

            $smtp->execute();
            return $smtp->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Log::error('Error select ' . $e->getMessage());
        }
    }

    /**
     *
     * @param String $table
     * @param Array $fields
     *            | array('field1', 'field2', 'field3')
     * @param Array $conditions
     *            | // [['id', '>', 0], ['nome', '=', 'guilherme']]
     * @param Array $orderBy
     *            | ['field', 'DESC']
     * @param Integer $limit
     *            | VALUE INTEGER
     * @return Array $fetchAll
     */
    public static function querySelectInnerJOIN($table, $tableJoin, Array $on, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL, $groupBy = NULL)
    {
        try {
            // show code
            $sql = 'SELECT ';

            // set fields
            $_table = self::showTables();
            foreach ($_table as $key => $val) {
                // TABLE SELECT
                foreach ($val[$table] as $field) {
                    $sql .= 't1.' . $field . ', ';
                }

                // TABLE INNER JOIN
                foreach ($val[$tableJoin] as $field) {
                    if ($field != 'id') {
                        $sql .= 't2.' . $field . ', ';
                    }
                }
            }

            $sql = substr($sql, 0, strlen($sql) - 2);

            if (FALSE === is_string($table))
                die('param $table pass given string in ' . __FILE__);

            $sql .= ' FROM ' . $table; // SET TABLE

            $sql .= ' t1 INNER JOIN ' . $tableJoin . ' t2 ON ';
            $sql .= self::clauseONInnerJoin($on);
            // $sql .= self::clauseConditions($conditions); // SET CONDITIONS

            $where = self::clauseWhere($conditions);
            $sql .= $where['sql'];

            $sql .= self::clauseGroupBy($groupBy); // SER GROUP BY
            $sql .= self::clauseOrderBy($orderBy); // SER ORDER BY

            if (is_int($limit) && ! $amount) {
                $sql .= " LIMIT $limit"; // SET LIMIT
            } else if ($amount != NULL && is_int($amount) && is_int($limit)) {
                $sql .= " LIMIT $limit, $amount";
            } else if ($limit != NULL) {
                $sql .= " LIMIT $limit";
            }
            Log::logError($sql);

            $smtp = self::getPDO()->prepare($sql);

            if ($conditions !== NULL)
                // self::setBindValuesConditions($conditions, $smtp);
                self::setParams($where['params'], $smtp);

            $smtp->execute();
            return $smtp->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Log::error('Error select ' . $e->getMessage());
        }
    }

    /**
     *
     * @param String $table
     * @param String $field
     * @param Array $conditions
     *            | // [['id', '>', 0], ['nome', '=', 'guilherme']]
     * @return Array $fetchAll
     */
    protected static function queryCountOcurrence($table, $field = '*', Array $conditions = NULL)
    {
        try {

            // show code
            $sql = 'SELECT COUNT(' . $field . ') as total ';

            if (FALSE === is_string($table))
                die('param $table pass given string in ' . __FILE__);

            $sql .= ' FROM ' . $table; // SET TABLE
            $where = self::clauseWhere($conditions);
            // $sql .= self::clauseConditions($conditions); // SET CONDITIONS
            $sql .= $where['sql'];

            Log::logError($sql);
            $smtp = self::getPDO()->prepare($sql);

            if ($conditions !== NULL)
                // self::setBindValuesConditions($conditions, $smtp);
                self::setParams($where['params'], $smtp);

            $smtp->execute();
            $result = $smtp->fetchAll(PDO::FETCH_ASSOC);
            return $result[0]['total'];
        } catch (PDOException $e) {
            Log::error('Error select ' . $e->getMessage());
        }
    }

    /**
     *
     * @param Array $orderBy
     * @return String $sql
     */
    private static function clauseOrderBy($orderBy = [])
    {
        if (is_array($orderBy) && count($orderBy) !== 0) {
            switch (count($orderBy)) {
                case 1:
                    return ' ORDER BY ' . $orderBy[0] . ' DESC ';
                    break;
                case 2:
                    return ' ORDER BY ' . $orderBy[0] . ' ' . $orderBy[1] . ' ';
                    break;
                default:
                    return ' ';
            }
        }
    }

    private static function clauseONInnerJoin(Array $conditions = NULL)
    {
        $sql = ''; // query sql

        if ($conditions !== NULL) {
            if (count($conditions) > 0 && is_array($conditions)) { // ISSET CONDITIONS
                $sql .= 't1.' . $conditions[0] . ' = ' . 't2.' . $conditions[1];
            } else if (FALSE === is_array($conditions)) {
                die('require param condition Array');
            }
        }

        return $sql;
    }

    /**
     * Tenho que melhorar esse conditions, pois achei um erro nele hahaha
     * DEPRECARED
     *
     * @param Array $conditions
     * @return String $sql
     */
    public static function clauseConditions(Array $conditions = NULL)
    {
        $sql = ''; // query sql

        if ($conditions !== NULL) {
            if (count($conditions) > 0 && is_array($conditions)) { // ISSET CONDITIONS
                $sql .= ' WHERE ';
                // SE O PRIMEIRO INDICE FOR UMA ARRAY
                // [['id', '>', 0], ['nome', '=', 'guilherme']]
                if (TRUE === is_array($conditions[0])) {
                    for ($i = 0; $i < count($conditions); $i ++) {
                        $sql .= $conditions[$i][0] . ' ' . $conditions[$i][1] . ' :' . $conditions[$i][0] . ' AND ';
                    }
                } else if (FALSE === is_array($conditions[0])) {
                    $sql .= $conditions[0] . ' ' . $conditions[1] . ' :' . $conditions[0] . ' AND ';
                }

                $sql = substr($sql, 0, strlen($sql) - 4); // HIDE ( AND )
            } else if (FALSE === is_array($conditions)) {
                die('require param condition Array');
            }
        }

        return $sql;
    }

    /**
     * WHERE Clause
     *
     * @return multitype:string multitype:unknown
     */
    public static function clauseWhere($conditions)
    {
        if (! $conditions || empty($conditions))
            return array(
                'key' => '',
                'sql' => '',
                'params' => array()
            );

        if (! is_array($conditions[0]))
            $conditions = [
                $conditions
            ];

        $sql = ' WHERE ';
        $key = ' WHERE ';
        $params = array();
        foreach ($conditions as $index => $array) {
            $string = $array[1];
            $operator = strtoupper("$string");
            if ($operator == '=' && is_array($array[2])) {
                $array[1] = 'IN';
                $operator = "IN";
            } else if ($operator == '!=' && is_array($array[2])) {
                $array[1] = 'NOT IN';
                $operator = "NOT IN";
            }

            if ($operator == 'IN' && sizeof($array[2]) == 1) {
                $array[1] = "=";
                $operator = "=";
                $array[2] = $array[2][0];
            } else if ($operator == 'NOT IN' && sizeof($array[2]) == 1) {
                $array[1] = "!=";
                $operator = "!=";
                $array[2] = $array[2][0];
            }

            switch ($operator) {
                case "=":
                case "!=":
                    if ($array[2] === true || $array[2] === false) {
                        if ($operator == '=')
                            $operator = ' IS ';
                        if ($operator == '!=')
                            $operator = ' IS NOT ';

                        $sql .= $array[0] . ' ' . $operator . ' ' . ($array[2] ? 'true' : 'false') . ' AND ';
                        $key .= $array[0] . ' ' . $operator . ' ' . ($array[2] ? 'true' : 'false') . ' AND ';
                        break;
                    }
                case "<":
                case ">":
                case ">=":
                case "<=":
                    if ($operator == '=' && $array[2] === null) {
                        $sql .= $array[0] . ' IS NULL AND ';
                        break;
                    }

                    if ($operator == '!=' && $array[2] === null) {
                        $sql .= $array[0] . ' IS NOT NULL AND ';
                        break;
                    }

                    $sql .= $array[0] . ' ' . $array[1] . ' ? AND ';
                    $key .= $array[0] . ' ' . $array[1] . ' ' . $array[2] . ' AND ';
                    $params[] = $array[2];
                    break;

                case 'IN':
                    $sql .= $array[0] . ' IN(';
                    foreach ($array[2] as $val) {
                        $sql .= '?,';
                        $params[] = $val;
                    }
                    $sql = substr($sql, 0, - 1);
                    $sql .= ") AND ";

                    // Query
                    $key .= $array[0] . ' IN(';
                    foreach ($array[2] as $val) {
                        $key .= $val . ',';
                    }
                    $key = substr($key, 0, - 1);
                    $key .= ") AND ";
                    break;

                case 'NOT IN':
                    $sql .= $array[0] . ' NOT IN(';
                    foreach ($array[2] as $val) {
                        $sql .= '?,';
                        $params[] = $val;
                    }
                    $sql = substr($sql, 0, - 1);
                    $sql .= ") AND ";

                    $key .= $array[0] . ' NOT IN(';
                    foreach ($array[2] as $val) {
                        $key .= $val . ',';
                    }
                    $key = substr($key, 0, - 1);
                    $key .= ") AND ";
                    break;

                case 'LIKE':
                    $sql .= $array[0] . ' ' . $array[1] . ' ? AND ';
                    $key .= $array[0] . ' ' . $array[1] . ' ' . $array[2] . ' AND ';
                    $params[] = '%' . $array[2] . '%';
                    break;

                case 'BETWEEN':
                    $sql .= $array[0] . ' ' . $array[1] . ' ? AND ? AND ';
                    $key .= $array[0] . ' ' . $array[1] . ' ' . $array[2][0] . ' AND ' . $array[2][1] . ' AND ';
                    $params[] = $array[2][0];
                    $params[] = $array[2][1];
                    break;

                default:
                    break;
            }
        }

        $sql = substr($sql, 0, - 5);
        $key = substr($key, 0, - 5);

        return array(
            'key' => $key,
            'sql' => $sql,
            'params' => $params
        );
    }

    /**
     * SORT Group By
     *
     * @param string $sort
     * @param string $dir
     * @return string
     */
    public static function clauseGroupBy($sort = null, $dir = null)
    {
        if (! $sort || $sort == '')
            return '';

        $sql = ' GROUP BY';

        if (is_string($sort) && substr($sort, 0, 1) == '[')
            $sort = json_decode($sort);

        // Array
        if (is_array($sort)) {
            foreach ($sort as $s)
                $sql .= ' ' . $s[0] . ($s[1] ? ' ' . $s[1] : ($dir ? ' ' . $dir : '')) . ',';

            $sql = substr($sql, 0, - 1);
            return $sql;

            // Simples
        } else {
            $sql .= ' ' . $sort;
            if ($dir)
                $sql .= ' ' . $dir;
            return $sql;
        }
    }

    /**
     * DEPRECARED
     *
     * @param Array $conditions
     * @param \PDOStatement $smtp
     */
    private static function setBindValuesConditions(Array $conditions, $smtp)
    {
        if (count($conditions) > 0) {
            if (TRUE === is_array($conditions[0])) { // here this array can many arrays
                for ($i = 0; $i < count($conditions); $i ++) {
                    $smtp->bindValue(':' . $conditions[$i][0], $conditions[$i][2]);
                }
            } else if (FALSE === is_array($conditions[0])) { // here array can three values
                $smtp->bindValue(':' . $conditions[0], $conditions[2]);
            }
        }
    }

    /**
     *
     * @param Array $params
     * @param \PDOStatement $smtp
     */
    private static function setParams(Array $params, $smtp)
    {
        if ($params)
            for ($i = 0; $i < sizeof($params); $i ++) {
                $smtp->bindParam($i + 1, $params[$i]);
            }
    }

    /*
     * Method retur name Dao child this Dao
     *
     */
    private static function showTable()
    {
        try {

            $table = strtolower(str_replace('Dao', '', array_pop(explode('\\', get_called_class())))); // get name class uses this Dao
            $sql = "SHOW TABLES";
            $smtp = self::getPDO()->prepare($sql);
            $smtp->execute();
            $result = $smtp->fetchAll(PDO::FETCH_ASSOC);

            $tables = [];
            foreach ($result as $r) {
                array_push($tables, $r[0]);
            }

            $tb = array_search($table, $tables);
            if ($tb === FALSE) // the table create missing
                die('Create the table ' . $table);
            return $table;
        } catch (PDOException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     */
    protected static function showTables()
    {
        try {

            // get all tables
            $sqlTable = "SHOW TABLES";
            $smtpTable = self::getPDO()->prepare($sqlTable);
            $smtpTable->execute();
            $resultTables = $smtpTable->fetchAll();

            foreach ($resultTables as $tableName) {

                // get fields table
                $sqlFields = "DESCRIBE " . $tableName[0] . "";
                $smtpFields = self::getPDO()->prepare($sqlFields);
                $smtpFields->execute();
                $resultFields = $smtpFields->fetchAll(PDO::FETCH_COLUMN);

                array_push(self::$_table, [
                    $tableName[0] => $resultFields
                ]);
            }

            return self::$_table;
        } catch (PDOException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     *
     * @param
     *            $sql
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function executeSqlAndCommit($sql)
    {
        $this->getConnection()->beginTransaction();
        $this->getConnection()->executeQuery($sql);
        $this->getConnection()->commit();
    }

    protected static function getConnection()
    {
        return self::$_conection;
    }

    protected static function setConnection(Connection $con)
    {
        return self::$_conection = $con;
    }
}

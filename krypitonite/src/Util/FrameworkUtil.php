<?php
namespace Krypitonite\Util;

use Configuration\Configuration;
require_once 'config/Configuration.php';

class FrameworkUtil
{

    public static function factoryDAO($moduleName, $nameClass)
    {
        // TODO : Tratar caseSensitive do name
        $file = str_replace('\\', '/', Configuration::PATH_SOURCE) . '/' . ucfirst($moduleName) . '/Dao/' . $nameClass . 'DAO.php';
        $fileCore = str_replace('\\', '/', Configuration::PATH_SOURCE) . '/Core/Dao/' . $nameClass . 'CoreDAO.php';

        if (file_exists($file)) {
            require_once $file;
            $class = '\Store\\' . ucfirst($moduleName) . '\\Dao\\' . $nameClass . 'DAO';
            return new $class();
        } else if (file_exists($fileCore)) {
            require_once $fileCore;
            $class = '\Store\\Core\\Dao\\' . $nameClass . 'CoreDAO';
            return new $class();
        }

        die('DAO not found: ' . $file . ' (try ?c=Database)');
    }

    public static function cacheFunction($className, $functionName, $params, $returnValue)
    {
        $cacheFunctionDAO = self::factoryDAO('CacheFunction');

        $cacheFunctionDAO->insertOrUpdate([
            'return_json' => json_encode($returnValue)
        ], [
            [
                'class',
                '=',
                $className
            ],
            [
                'function',
                '=',
                $functionName
            ],
            [
                'params_json',
                '=',
                json_encode($params)
            ]
        ]);
    }

    public static function getCachedFunction($className, $functionName, $params, $maxMinutes = null)
    {
        $cacheFunctionDAO = self::factoryDAO('CacheFunction');

        $conditions = [
            [
                'class',
                '=',
                $className
            ],
            [
                'function',
                '=',
                $functionName
            ],
            [
                'params_json',
                '=',
                json_encode($params)
            ]
        ];

        if ($maxMinutes && $maxMinutes > 0) {
            $date = new \DateTime();
            $date->modify('-' . $maxMinutes . ' minutes');
            $conditions[] = [
                '_date_created',
                '>=',
                $date->format('Y-m-d H:i:s')
            ];
        }

        $cacheFunction = $cacheFunctionDAO->getFields([
            'return_json'
        ], $conditions);
        if (empty($cacheFunction))
            return null;

        if ($cacheFunction[0]['return_json'][0] == '[' || $cacheFunction[0]['return_json'][0] == '{')
            return json_decode($cacheFunction[0]['return_json'], true);

        return $cacheFunction[0]['return_json'];
    }

    public static function deleteCachedFunction($className, $functionName, $params = null)
    {
        $cacheFunctionDAO = self::factoryDAO('CacheFunction');

        $conditions = [
            [
                'class',
                '=',
                $className
            ],
            [
                'function',
                '=',
                $functionName
            ]
        ];
        if ($params)
            $conditions[] = [
                'params_json',
                '=',
                json_encode($params)
            ];

        $cacheFunction = $cacheFunctionDAO->delete($conditions);
        return true;
    }

    // Acho que isso tem que ir pro SessionUtil
    public static function responseJSON($status, $content = null)
    {
        $tmp = new _json_model($status, $content);
        if (! $content)
            unset($tmp->content);
        return json_encode($tmp);
    }

    public static function meOrNull($me, $answer = null)
    {
        $ret = null;
        if ($me && $me != '')
            $ret = $me;
        if ($ret && is_string($me) && $me[0] != '{' && $me[0] != '[')
            $ret = "'" . $ret . "'";
        if (! $ret)
            $ret = 'null';
        return $ret;
    }

    public static function nameSqlToFramework($sql_name, $firstCapital = true)
    {
        $frameworkName = '';
        $tokens = explode('_', $sql_name);
        $firstToken = true;
        foreach ($tokens as $token) {
            if ($firstToken)
                if ($firstCapital)
                    $frameworkName .= strtoupper($token[0]) . substr($token, 1);
                else
                    $frameworkName .= strtolower($token[0]) . substr($token, 1);
            else
                $frameworkName .= strtoupper($token[0]) . substr($token, 1);
            $firstToken = false;
        }
        return $frameworkName;
    }
}

class _json_model
{

    public $header;

    public $content;

    function __construct($status, $content)
    {
        $this->header = [
            'status' => $status,
            'timestamp' => date_timestamp_get(new \DateTime())
        ];
        $this->content = $content;
    }
}

?>
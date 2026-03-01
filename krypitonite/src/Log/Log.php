<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Log;

use Configuration\Configuration;

class Log
{

    /**
     * Backtrace no phplog
     */
    public static function backtrace()
    {
        $debug_backtrace = array_reverse(debug_backtrace());
        self::logError();
        self::logErrorLine();
        foreach ($debug_backtrace as $item) {
            self::logError($item['file'] . ' (' . $item['line'] . ')');
        }
        self::logErrorLine();
    }

    public static function logError($str = '')
    {
        // error_log(($str!='' ? '> ' : ' ').$str);
    }

    public static function logErrorLine()
    {
        error_log('------------------------------------------------------------------------------------------------------------------------------------');
    }

    public static function error($message)
    {
        if (! is_dir(str_replace('\\', '/', Configuration::PATH_LOG))) {
            mkdir(Configuration::PATH_LOG);
        }

        $message = ' Log create in ' . date('d/m/Y H:i:s') . "\n Message: " . $message . " \n\n";
        $filename = Configuration::PATH_LOG . '/log.txt';

        file_put_contents($filename, $message, FILE_APPEND);
    }

    public static function write($message = "")
    {
        self::error($message);
    }
}

?>
<?php

class StringUtil
{

    public static function spacesAfter($string, $urlmount)
    {
        if (! $urlmount)
            return $string;
        
        $urlmount -= strlen($string);
        
        $spaces = '';
        for ($i = 0; $i < $urlmount; $i ++) {
            $spaces .= ' ';
        }
        
        return $string . $spaces;
    }

    public static function cleanText(&$str, $limit = 200)
    {
        $str = strip_tags($str, '<br><br/>');
        $str = str_replace('<br>', '&nbsp;', $str);
        $str = str_replace('<br/>', '&nbsp;', $str);
        $str = substr($str, 0, $limit);
    }

    public static function crop($txt, $nI, $nO)
    {
        if (strpos($txt, $nI) === false)
            return null;
        $pI = strpos($txt, $nI) + strlen($nI);
        $pO = strpos($txt, $nO, $pI);
        return substr($txt, $pI, $pO - $pI);
    }

    public static function cropN($txt, $nI, $nO)
    {
        $texts = array();
        $pI = strpos($txt, $nI);
        while ($pI !== false) {
            $pI += strlen($nI);
            $pO = strpos($txt, $nO, $pI);
            $texts[] = substr($txt, $pI, $pO - $pI);
            $pI = strpos($txt, $nI, $pO + strlen($nO));
        }
        return $texts;
    }

    public static function trim($str, $what = null, $with = ' ')
    {
        if ($what === null)
            $what = "\\x00-\\x20";
        $res = trim(preg_replace("/[" . $what . "]+/", $with, $str), $what);
        if ($res != '' && $res[strlen($res) - 1] == ' ')
            $res = substr($res, 0, - 1);
        if ($res != '' && $res[0] == ' ')
            $res = substr($res, 1);
        return $res;
    }

    public static function removeSpaces($string)
    {
        return preg_replace('/\s+/', '', $string);
        // return str_replace(' ','',$string);
    }

    public static function addLeadingZeros($value = 0, $count = 5)
    {
        return sprintf("%0" . $count . "d", $value);
    }

    public static function repeat($str, $count)
    {
        $res = '';
        for ($i = 0; $i < $count; $i ++)
            $res .= $str;
        return $res;
    }

    public static function capitalizeFirstLetters($text)
    {
        return ucwords(strtolower($text));
    }

    public static function urlToFilename($url)
    {
        // $url = str_replace('.', '%PE%', $url);
        // $url = str_replace(':', '%CO%', $url);
        // $url = str_replace('/', '%FS%', $url);
        // $url = str_replace('\\', '%BS%', $url);
        // $url = str_replace('?', '%QM%', $url);
        // $url = str_replace('=', '%EQ%', $url);
        // $url = str_replace('&', '%AN%', $url);
        // $url = str_replace('<', '%LT%', $url);
        // $url = str_replace('>', '%GT%', $url);
        // $url = str_replace('"', '%DQ%', $url);
        // $url = str_replace('&', '%AN%', $url);
        // $url = str_replace('|', '%PP%', $url);
        // $url = str_replace('*', '%AS%', $url);
        $url = str_replace('/', '%F', $url);
        $url = str_replace('\\', '%B', $url);
        $url = str_replace('&', '%A', $url);
        $url = str_replace('<', '%L', $url);
        $url = str_replace('>', '%G', $url);
        $url = str_replace('"', '%D', $url);
        $url = str_replace("'", '%S', $url);
        $url = str_replace('&', '%N', $url);
        $url = str_replace('|', '%P', $url);
        
        return $url;
    }

    public static function filenameToUrl($fileName)
    {
        // $fileName = str_replace('%PE%', '.', $fileName);
        // $fileName = str_replace('%CO%', ':', $fileName);
        // $fileName = str_replace('%FS%', '/', $fileName);
        // $fileName = str_replace('%BS%', '\\', $fileName);
        // $fileName = str_replace('%QM%', '?', $fileName);
        // $fileName = str_replace('%EQ%', '=', $fileName);
        // $fileName = str_replace('%AN%', '&', $fileName);
        // $fileName = str_replace('%LT%', '<', $fileName);
        // $fileName = str_replace('%GT%', '>', $fileName);
        // $fileName = str_replace('%DQ%', '"', $fileName);
        // $fileName = str_replace('%SQ%', "'", $fileName);
        // $fileName = str_replace('%AN%', '&', $fileName);
        // $fileName = str_replace('%PP%', '|', $fileName);
        // $fileName = str_replace('%AS%', '*', $fileName);
        $fileName = str_replace('%F', '/', $fileName);
        $fileName = str_replace('%B', '\\', $fileName);
        $fileName = str_replace('%A', '&', $fileName);
        $fileName = str_replace('%L', '<', $fileName);
        $fileName = str_replace('%G', '>', $fileName);
        $fileName = str_replace('%D', '"', $fileName);
        $fileName = str_replace('%S', "'", $fileName);
        $fileName = str_replace('%N', '&', $fileName);
        $fileName = str_replace('%P', '|', $fileName);
        
        return $fileName;
    }

    public static function changeAllCommasToDotsInNumbersOfAString($string)
    {
        return preg_replace('/(\d+),(\d+)/', '$1.$2', $string);
    }

    public static function convertToNumberIfPossible($input)
    {
        if (is_numeric($input))
            return intval($input);
        return $input;
    }
}

?>
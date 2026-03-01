<?php
namespace Krypitonite\Util;

use Krypitonite\Log\Log;

class SoapUtil
{

    public static function execute($url = '')
    {
        try {
            return new \SoapClient($url, array(
                'exceptions' => 0
            ));
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}


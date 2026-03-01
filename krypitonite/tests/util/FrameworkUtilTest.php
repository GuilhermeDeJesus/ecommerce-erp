<?php
namespace tests\util;

class FrameworkUtilTest extends \PHPUnit_Framework_TestCase
{

    public function testIncludeController()
    {
        $file = "controller";
        
        assertEqual("controller", $file);
    }
}

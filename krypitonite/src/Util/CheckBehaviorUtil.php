<?php
namespace Krypitonite\Util;

class CheckBehaviorUtil
{

    public static function addCard($item, $numerCard)
    {
        $_SESSION['customer_card'][$numerCard] = $item;
    }

    public static function countTotalCard()
    {
        if (isset($_SESSION['customer_card']))
            return count($_SESSION['customer_card']);
        else
            return 0;
    }
}
<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Util;

class CarrinhoUtil
{

    public static function addItem($item, $key)
    {
        $_SESSION['carrinho'][$key] = $item;
    }

    public static function getItens($key)
    {
        if (isset($_SESSION['carrinho'])) {
            return $_SESSION['carrinho'][$key];
        }
    }

    public static function getValorTotalCarrinho()
    {
        $v = [];
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $c => $carrinho) {
                array_push($v, $carrinho['combinacao']);
            }
        }
        return array_sum($v);
    }

    public static function getTotalItens()
    {
        if (isset($_SESSION['carrinho']))
            return count($_SESSION['carrinho']);
        else
            return 0;
    }

    public static function removeItemCarrinho($item, $key)
    {
        unset($_SESSION['carrinho'][$item][$key]);
    }

    public static function cleanItens()
    {
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $k => $v) {
                unset($_SESSION['carrinho'][$k]);
            }
        }
        return true;
    }
}
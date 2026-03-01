<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Http;

/*
 * Classe para tratar todas as requesi��es da aplica��o
 *
 */
class Request
{

    public static function get($id)
    {
        if (isset($_GET[$id])) {
            return htmlspecialchars(strip_tags($_GET[$id]));
        } else if (isset($_POST[$id])) {
            return strip_tags($_POST[$id]);
        }
    }

    // implements more methods
}
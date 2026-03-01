<?php
namespace Krypitonite\Util;

class PaginationUtil
{

    public static $_inicio;

    public static $_previous;

    public static $_posterior;

    public static $_page;

    public static $_total_pages;

    public static $_quantidade;

    public static $_exibir_por_pagina;

    public static function execute($results = NULL, $get = NULL, $qtd_for_page = NULL)
    {
        self::$_page = 0;
        if (isset($get['pagina'])) {
            self::$_page = $get['pagina'];
        }

        self::$_quantidade = (is_array($results)) ? count($results) : $results;
        self::$_total_pages = ceil(self::$_quantidade / $qtd_for_page) - 1;
        self::$_inicio = self::$_page * $qtd_for_page;

        // VALIDATE BUG
        if (self::$_total_pages == 0) {
            self::$_inicio = 0;
        }

        self::$_previous = ((self::$_page - 1) == 0) ? 1 : self::$_page - 1;
        self::$_posterior = ((self::$_page + 1) >= self::$_total_pages) ? self::$_page : self::$_page + 1;

        return self::getLinks(self::$_previous, self::$_posterior, self::$_page, self::$_total_pages, $qtd_for_page);
    }

    public static function getInicio()
    {
        if (isset(self::$_inicio))
            return self::$_inicio;
    }

    public static function getLinks($_previous, $_posterior, $page, $total_page, $total_for_page)
    {
        $url = "/?";

        // $lastUrl = $_SERVER['HTTP_REFERER'];
        // $lastUrl = explode('=', explode('?', $lastUrl)[1]);

        // foreach ($lastUrl as $_url) {
        // $url .= $_url . '=';
        // }

        // $url = substr($url, 0, strlen($url) - 1);

        foreach ($_GET as $k => $v) {
            $url .= "$k=$v&";
        }

        $html = '';
        // $html .= "<div class='_iten'><a href='$url&pagina=0'>Primeira</a></div>";
        // $html .= "<div class='_iten'><a href='$url&pagina=$_previous'>Anterior</a></div>";

        for ($i = $page - $total_for_page; $i <= $page - 1; $i ++) {
            if ($i > 0) {
                $html .= "<div class='_iten'><a href='$url&pagina=$i'> $i </a></div>";
            }
        }

        $pg = $page + 1;
        $html .= "<div class='_iten'><a style='font-weight: bold; color: #09f;' href='$url&pagina=$page'> <strong> $pg</strong></a></div>";
        for ($i = $page + 1; $i < $page + $total_for_page; $i ++) {
            if ($i <= $total_page && $total_page != 0) {
                $pge = $i + 1;
                $html .= "<div class='_iten'><a href='$url&pagina=$i'> $pge </a></div>";
            }
        }

        // $html .= "<div class='_iten'><a href=$url&pagina=$_posterior>Próxima</a></div>";
        // $html .= "<div class='_iten'><a href=$url&pagina=$total_page>Última</a></div>";

        return $html;
    }
}
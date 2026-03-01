<?php
namespace Krypitonite\Util;

class HTMLUtil
{

    /*
     * Libs
     */
    private static $_bootstrap = "public/vendors/bootstrap/dist/";

    private static $_datatable = "public/vendors/datatable/";

    private static $_jquery = "public/vendors/jquery/dist/";

    private static $_jqueryui = "public/vendors/jquery-ui/";

    private static $_datepicker_bootstrap = "public/vendors/bootstrap-datepicker/";

    private static $_title;

    public static function cssDefault()
    {
        $css = '';
        $css .= "\t" . '<link rel="stylesheet" href="public/css/style.css" type="text/css" />' . "\n";
        echo $css;
    }

    /*
     * load js
     * @return js
     */
    public static function load_JS($dirJs, $noMin = FALSE)
    {
        $js = '';
        $handle = '';
        $file = '';

        if ($handle = opendir($dirJs)) {
            while (false !== ($file = readdir($handle))) {
                if (is_file($dirJs . "/" . $file)) {
                    $explode = explode(".", $file);
                    if (end($explode) == 'js' && $explode[1] != 'min' && $noMin == TRUE && file_exists($dirJs . $file)) {
                        $js .= "\t" . '<script src="' . $dirJs . $file . '"></script>' . "\n";
                    } else if (end($explode) == 'js' && $noMin == FALSE && file_exists($dirJs . $file)) {
                        $js .= "\t" . '<script src="' . $dirJs . $file . '"></script>' . "\n";
                    }
                }
            }

            closedir($handle);
            echo $js;
        }
    }

    /*
     * load css
     * @return css
     */
    public static function load_CSS($dirCss)
    {
        $css = '';
        $handle = '';
        $file = '';

        if ($handle = opendir($dirCss)) {
            while (false !== ($file = readdir($handle))) {
                if (is_file($dirCss . "/" . $file)) {
                    if (end(explode(".", $file)) == 'css' && file_exists($dirCss . $file)) {
                        $css .= "\t" . '<link rel="stylesheet" href="' . $dirCss . $file . '" type="text/css" />' . "\n";
                    }
                }
            }

            closedir($handle);
            echo $css;
        }
    }

    public static function getNomeLoteria($loteria)
    {
        $cor = '';
        switch ($loteria) {
            case 'Dupla Sena':
                $cor = "#750710";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Mega Sena':
                $cor = "green";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Lotofácil':
                $cor = "#931b85";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Lotofácil da Independência':
                $cor = "#931b85";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Lotomania':
                $cor = "#f37912";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Quina':
                $cor = "#252470";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Timemania':
                $cor = "#f5ca19";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
            case 'Dia de Sorte':
                $cor = "#b38c41";
                $loteria = "<p style='color: $cor; text-align: left;'>$loteria</p>";
                break;
        }

        return $loteria;
    }

    public static function getStatus($value = 0)
    {
        $v = '';
        switch ($value) {
            case 1:
                $v = "<p style='color: green; text-align: left;'><b>Sim</b></p>";
                break;
            case 0:
                $v = "<p style='color: red; text-align: left;'><b>Não</b></p>";
                break;
        }

        return $v;
    }

    public static function headDefault($_title = NULL, $_description = NULL)
    {
        echo "<!DOCTYPE html>\n<html lang='pt-BR' xml:lang='pt-BR'><head>
        " . "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        " . "<title>" . $_title . "</title>
        " . "<meta name='keywords' content='Botas Femininas, Botas Cano Curso, Botas Coturno'/>
        " . "<meta name='description' content='" . $_description . "'>
        " . "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
        " . "<meta name='RATING' content='GENERAL' />
        " . "<meta name='ROBOTS' content='INDEX, ALL' />
        " . "<meta name='ROBOTS' content='INDEX, FOLLOW' />

        " . "<meta name='robots' content='index, follow' />
        " . "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
        " . "<meta property='og:site_name' content='Lotérica Premiada' />

        " . "<meta property='og:type' content='website'>
        " . "<meta property='og:title' content='" . $_title . "'>
        " . "<meta property='og:description' content='" . $_description . "'>
        " . "<meta property='og:url' content='https://www.shopvitas.com.br'>
        " . "<meta property='og:image' content='https://www.shopvitas.com.br/public/img/logo3.png'>
        " . "<meta property='fb:app_id' content='https://www.facebook.com/shopvitas/?ref=bookmarks'>
        " . "<meta name='facebook-domain-verification' content='3ckuftt6adkq6d548w7eogm24jz7ib' />";

        echo "
        " . "<script src='public/vendors/jquery/dist/jquery.min.js'></script>
        " . "<script src='public/vendors/jquery-ui/jquery-ui.min.js'></script>
        " . "<link rel='stylesheet' href='public/vendors/jquery-ui/jquery-ui.min.css' type='text/css' />
        " . "<link rel='stylesheet' href='public/vendors/jquery-ui/jquery-ui.structure.min.css' type='text/css' />
        " . "<link rel='stylesheet' href='public/vendors/jquery-ui/jquery-ui.theme.min.css' type='text/css' />
        " . "<link rel='stylesheet' href='public/vendors/bootstrap/dist/css/bootstrap-theme.min.css' type='text/css' />
        " . "<link rel='stylesheet' href='public/vendors/bootstrap/dist/css/bootstrap.min.css' type='text/css' />
        " . "<link rel='stylesheet' href='public/vendors/bootstrap/dist/css/normalize.css' type='text/css' />

        " . "<link rel='icon' href='public/img/ic-megasena.png' type='image/x-icon' />
        " . "<link rel='shortcut icon' href='public/img/ic-megasena.png' type='image/x-icon' />
        " . "<link rel='publisher' href='https://plus.google.com/u/1/b/111379725612047929708/111379725612047929708'>
        " . "<link href='public/css/main.min.css' rel='stylesheet'>
        " . "<link href='public/mobile/css/styles.css' rel='stylesheet' media='(max-width: 800px)'>
        " . "<script type='text/javascript' src='public/mobile/js/responsive-nav.js'></script>
        " . "<link href='public/css/responsive.min.css' rel='stylesheet'>
        " . "<link rel='stylesheet' href='public/css/font-awesome.min.css'>
        " . "<script src='public/js/bootstrap.min.js'></script>
        " . "<script src='public/js/jquery.scrollUp.min.js'></script>
        " . "<script src='public/js/main.js'></script>
       " . "<script src='//cdn.pn.vg/sites/9dc0382f-c00f-469f-aa51-1dbc68406d20.js' async></script>
        " . "<script>";

        echo "
                  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
                            
                  ga('create', 'UA-157111974-1', 'auto');
                  ga('send', 'pageview');";

        echo 'function add_chatinline(){
                	var hccid=69982952;
                	var nt=document.createElement("script");
                	nt.async=true;
                	nt.src="https://mylivechat.com/chatinline.aspx?hccid="+hccid;
                	var ct=document.getElementsByTagName("script")[0];
                	ct.parentNode.insertBefore(nt,ct);
                }
                add_chatinline(); 
        </script>';
        echo "</head>\n";
    }
}
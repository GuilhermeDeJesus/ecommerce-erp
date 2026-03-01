<?php
namespace Krypitonite\Util;

class ValidateUtil
{

    /**
     *
     * @param String $value
     */
    public static function getMaxValueRanger($value)
    {
        $v = explode(';', $value);
        if (isset($v[1])) {
            return $v[1];
        }
    }

    /**
     *
     * @param String $value
     */
    public static function getMinValueRanger($value)
    {
        $v = explode(';', $value);
        if (isset($v[0])) {
            return $v[0];
        }
    }

    /**
     *
     * @param Array $_POST
     * @return int $id
     */
    public static function getIntID($post)
    {
        return (int) array_keys($post)[0];
    }

    /**
     *
     * @param String $string
     */
    public static function getFormatDate($string = "")
    {
        $arData = explode('/', $string);
        return date('Y-m-d', mktime(0, 0, 0, $arData[1], $arData[0], $arData[2]));
    }

    /**
     *
     * @param String $string
     */
    public static function getSituacao($int = "")
    {
        $txt = 'Ativo';
        if ($int == 1) {
            $txt = 'Ativo';
        } else {
            $txt = 'Inativo';
        }

        return $txt;
    }

    public static function cleanString($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', ' ', $string); // Replaces multiple hyphens with single one.
    }

    public static function cleanInput($value = NULL, $nameInput = 'number')
    {
        $var = '';
        switch ($nameInput) {
            case 'number':
                $value = "$value"; // String
                $var = preg_replace("/\D+/", "", $value);
                return $var;
                break;
            case 'money': // Format money
                $cleanString = preg_replace('/([^0-9\.,])/i', '', $value);
                $onlyNumbersString = preg_replace('/([^0-9])/i', '', $value);

                $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

                $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
                $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

                $var = (float) str_replace(',', '.', $removedThousendSeparator);
                return $var;
                break;

            case 'string':
                $value = "$value"; // String
                $var = preg_replace("/\D+/", "", $value);
                return $var;
                break;
        }
    }

    public static function getNameMothCurrent()
    {
        $mes = '';
        switch (date("m")) {
            case "01":
                $mes = 'Janeiro';
                break;
            case "02":
                $mes = 'Fevereiro';
                break;
            case "03":
                $mes = 'Março';
                break;
            case "04":
                $mes = 'Abril';
                break;
            case "05":
                $mes = 'Maio';
                break;
            case "06":
                $mes = 'Junho';
                break;
            case "07":
                $mes = 'Julho';
                break;
            case "08":
                $mes = 'Agosto';
                break;
            case "09":
                $mes = 'Setembro';
                break;
            case "10":
                $mes = 'Outubro';
                break;
            case "11":
                $mes = 'Novembro';
                break;
            case "12":
                $mes = 'Dezembro';
                break;
        }

        return $mes;
    }

    public static function getDayMonthShort($date = null)
    {
        $date = new \DateTime($date);
        if ($date->format('d/m') == date('d/m')) {
            return 'Hoje';
        } else {
            return $date->format('d/m');
        }
    }

    /**
     *
     * @param String $date
     */
    public static function getDayDate($date = "", $full = FALSE, $detail = FALSE, $formateDate = FALSE)
    {
        if ($date) {

            $ret = '';
            $dat = str_replace('/', '', $date);

            $dia = substr($dat, 6, 8);
            $mes = substr($dat, 4, 2);
            $ano = substr($dat, 0, 4);

            $diasemana = date('w', mktime(0, 0, 0, $mes, $dia, $ano));
            switch ($diasemana) {
                case 0:
                    $diasemana = 'Domingo';
                    break;
                case 1:
                    $diasemana = 'Segunda-Feira';
                    break;
                case 2:
                    $diasemana = 'Terça-Feira';
                    break;
                case 3:
                    $diasemana = 'Quarta-Feira';
                    break;
                case 4:
                    $diasemana = 'Quinta-Feira';
                    break;
                case 5:
                    $diasemana = 'Sexta-Feira';
                    break;
                case 6:
                    $diasemana = 'Sábado';
                    break;
            }

            $ret = $diasemana;

            if ($detail && $formateDate == FALSE) {

                $timestamp = "$date";

                $today = new \DateTime(); // This object represents current date/time
                $today->setTime(0, 0, 0); // reset time part, to prevent partial comparison

                $match_date = \DateTime::createFromFormat("Y/m/d", $timestamp);
                $match_date->setTime(0, 0, 0); // reset time part, to prevent partial comparison

                $diff = $today->diff($match_date);
                $diffDays = (integer) $diff->format("%R%a"); // Extract days count in interval

                switch ($diffDays) {
                    case 0:
                        return 'Hoje';
                        break;
                    case - 1:
                        return 'Ontem';
                        break;
                    case + 1:
                        return 'Amanhã';
                        break;
                    default:
                        if ($ret == "Sábado") {
                            $p = "Próx.";
                        } else {
                            $p = "Próx.";
                        }
                        return "$p $ret";
                }
            } else if ($formateDate == FALSE) {
                return $ret;
            } else if ($formateDate == TRUE) {
                return date('d/m/Y', strtotime($date));
            }
        }
    }

    public static function setFormatMoney($value = NULL)
    {
        return number_format($value, 2, ',', '.');
    }

    public static function paraFloat($v)
    {
        $r = 0.0;
        $r = preg_replace('~[^\d\.,]~', '', $v);

        /*
         * Verificação se o número contem vigula e ponto
         * pois a transformação deve ser diferente neste caso
         * usei preg_match para não ter que usar strpos 2 vezes
         */
        if (preg_match('~.*\..+,~', $r)) {
            /*
             * Neste caso o ponto está separando as casas de milhar como em : 1.250,00
             * para transformar em float, vou eliminar o ponto e deixar só a virgula, que será tratada logo abaixo
             */
            $r = str_replace('.', '', $r);
        }

        /*
         * A virgula deve ser removida de qualquer maneira
         */
        $r = str_replace(',', '.', $r);

        return (double) $r;
    }

    public static function textificarMoney($v = NULL)
    {
        $v = self::paraFloat($v);
        $ret = $v;

        if ($v > 999999999) {
            $ret = 'R$ ' . round(($v / 1000000000), 1);
            $ret .= ($ret == 1 ? ' Bilhão' : ' Bilhões');
        } elseif ($v > 999999) {
            $ret = 'R$ ' . round(($v / 1000000), 1);
            $ret .= ($ret == 1 ? ' Milhão' : ' Milhões');
        } elseif ($v > 999) {
            $ret = 'R$ ' . round(($v / 1000), 1);
            $ret .= ' Mil';
        } elseif ($v == 0) {
            $ret = 'A definir';
        }

        return $ret;
    }

    public static function setFormatCPF($cpf = "")
    {
        $parte_um = substr($cpf, 0, 3);
        $parte_dois = substr($cpf, 3, 3);
        $parte_tres = substr($cpf, 6, 3);
        $parte_quatro = substr($cpf, 9, 2);

        $monta_cpf = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";

        return $monta_cpf;
    }

    public static function setFormatCEP($cep = "")
    {
        $parte_um = substr($cep, 0, 5);
        $parte_dois = substr($cep, 5, 3);

        $monta_cep = "$parte_um-$parte_dois";

        return $monta_cep;
    }

    public static function getDateString($date = '')
    {
        $date = new \DateTime("$date 00:00:40");

        return $date->format('d/m/Y');
    }

    public static function getSex($sigla = '')
    {
        if ($sigla == 'M') {
            return 'Masculino';
        } else if ($sigla == 'F') {
            return 'Feminino';
        }
    }

    public static function ifCPFisValid($cpf)
    {

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t ++) {
            for ($d = 0, $c = 0; $c < $t; $c ++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public static function setTextGratisNoZero($value = 0)
    {
        if ($value == 0 || $value == 'R$ 0,00' || $value == '0,00') {
            return 'Grátis';
        } else {
            return 'R$ ' . $value;
        }
    }

    public static function getDispositivo()
    {
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $symbian = strpos($_SERVER['HTTP_USER_AGENT'], "Symbian");

        if ($iphone) {
            return 'iPhone';
        } else if ($ipad) {
            return 'iPad';
        } else if ($android) {
            return 'Android';
        } else if ($palmpre) {
            return 'Palmpre';
        } else if ($ipod) {
            return 'iPod';
        } else if ($berry) {
            return 'Berry';
        } else if ($symbian) {
            return 'Symbian';
        } else {
            return "Desktop";
        }
    }

    public static function checkIfCustomerCanContinueTransaction($typeCustomer)
    {
        $approved_status = FALSE;
        switch ($typeCustomer) {
            case 'NVO':
                $approved_status = TRUE;
                break;
            case 'FD':
                $approved_status = FALSE;
                break;
            case 'RC':
                $approved_status = TRUE;
                break;
            default:
                $approved_status = TRUE;
                break;
        }

        return $approved_status;
    }

    public static function formatPercent($value = 100)
    {
        return substr($value, 0, 4) . "%";
    }
}

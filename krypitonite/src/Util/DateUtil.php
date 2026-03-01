<?php
namespace Krypitonite\Util;

class DateUtil
{

    /**
     * Calculate
     *
     * Ex:
     * DateUtil::calculate('2020-12-30', '-45 days')
     * DateUtil::calculate('2020-12-30', '+2 weeks')
     * DateUtil::calculate('2020-12-30', '+1 month')
     * DateUtil::calculate('2020-12-30', '+1 year')
     *
     */
    public static function calculate($date, $expr, $format = 'Y-m-d')
    {
        return date($format, strtotime($date . ' ' . $expr));
        // return date($format, strtotime($expr, strtotime($date)));
    }

    public static function now($hours = false)
    {
        $d = new \DateTime();
        
        if ($hours)
            return $d->format("Y-m-d\ H:i:s");
        
        return $d->format("Y-m-d");
    }

    public static function isAfterToday($date)
    {
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime($date);
        $interval = $datetime1->diff($datetime2);
        
        if ($interval->format('%R') == '+')
            return true;
        
        return false;
    }

    /**
     * 2017-01-25, 2016-01-24 = false
     * 2016-01-24, 2017-01-25 = true
     *
     */
    public static function isBiggerThan($date1, $date2)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        if ($interval->format('%R') == '+')
            return true;
        
        return false;
    }

    public static function calculateTimeDifferenceInDays($date1, $date2)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        
        return round(abs(strtotime($date1) - strtotime($date2)) / 86400);
    }

    public static function calculateTimeDifferenceRaw($date1, $date2)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        return [
            'signal' => $interval->format('%R'),
            'years' => $interval->format('%y'),
            'months' => $interval->format('%m'),
            'days' => $interval->format('%d'),
            'hours' => $interval->format('%h'),
            'minutes' => $interval->format('%i'),
            'seconds' => $interval->format('%s')
        ];
    }

    public static function calculateTimeDifference($date1, $date2, $ignoreSignal = false, $ignoreSeconds = false)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        $signal = $interval->format('%R');
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');
        
        $str = '';
        
        if (! $ignoreSignal)
            $str .= $signal . ' ';
        
        if ($years == 1)
            $str .= $years . ' ano, ';
        else if ($years > 1)
            $str .= $years . ' anos, ';
        
        if ($months == 1)
            $str .= $months . ' mês, ';
        else if ($months > 1)
            $str .= $months . ' meses, ';
        
        if ($days == 1)
            $str .= $days . ' dia, ';
        else if ($days > 1)
            $str .= $days . ' dias, ';
        
        if ($hours == 1)
            $str .= $hours . ' hora, ';
        else if ($hours > 1)
            $str .= $hours . ' horas, ';
        
        if ($minutes == 1)
            $str .= $minutes . ' minuto, ';
        else if ($minutes > 1)
            $str .= $minutes . ' minutos, ';
        
        if (! $ignoreSeconds) {
            if ($seconds == 1)
                $str .= $seconds . ' segundo, ';
            else if ($seconds > 1)
                $str .= $seconds . ' segundos, ';
        }
        
        return substr($str, 0, - 2);
    }

    public static function calculateTimeDifferenceTemplate1($date1, $date2)
    {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');
        
        if ($years < 1 && $months < 1 && $days < 1) {
            $str = '';
            
            if ($hours == 1)
                $str .= $hours . ' hora';
            else if ($hours > 1)
                $str .= $hours . ' horas';
            
            if ($hours >= 1 && $minutes >= 1)
                $str .= ' e ';
            
            if ($minutes == 1)
                $str .= $minutes . ' minuto';
            else if ($minutes > 1)
                $str .= $minutes . ' minutos';
            
            if ($hours < 1 && $minutes < 1) {
                if ($seconds == 1)
                    $str .= $seconds . ' segundo';
                else if ($seconds > 1)
                    $str .= $seconds . ' segundos';
            }
            
            return $str;
        }
        
        return self::dateLiteral($date1);
    }

    // public static function calculateDifferenceInMonths($date1, $date2) {
    // $datetime1 = date_create($date1);
    // $datetime2 = date_create($date2);
    // $interval = date_diff($datetime1, $datetime2);
    // return $interval->format('%m');
    // }
    public static function dateLiteral($date, $time = false)
    {
        $date = new \DateTime($date);
        $now = new \DateTime();
        
        $str = $date->format('d') . ' de ' . self::monthLiteral($date->format('m'));
        
        if ($date->format('Y') != $now->format('Y'))
            $str .= ' de ' . $date->format('Y');
        
        if ($time)
            $str .= ' às ' . $date->format('H') . ':' . $date->format('i') . ':' . $date->format('s');
        
        return $str;
    }

    public static function dateLiteralShort($date, $time = true)
    {
        $date = new \DateTime($date);
        $now = new \DateTime();
        
        return $date->format('j') . ' ' . self::monthLiteralShort($date->format('m')) . ' ' . ($date->format('Y') < $now->format('Y') ? $date->format('Y') . ' ' : '') . ($time ? $date->format('H') . ':' . $date->format('i') : '');
    }

    public static function dateLiteralShortTemplateChat($date, $time = true)
    {
        $date = new \DateTime($date);
        $now = new \DateTime();
        
        return '<span style="font-size:10px;color:#666;">' . $date->format('j') . ' ' . self::monthLiteralShort($date->format('m')) . ' ' . ($date->format('Y') < $now->format('Y') ? $date->format('Y') . ' ' : '') . '</span><br><span style="font-size:8px;color:#666;">' . ($time ? $date->format('H') . ':' . $date->format('i') : '') . '</span>';
    }

    public static function monthLiteral($month)
    {
        switch ($month) {
            case '01':
                return 'Janeiro';
            case '02':
                return 'Fevereiro';
            case '03':
                return 'Março';
            case '04':
                return 'Abril';
            case '05':
                return 'Maio';
            case '06':
                return 'Junho';
            case '07':
                return 'Julho';
            case '08':
                return 'Agosto';
            case '09':
                return 'Setembro';
            case '10':
                return 'Outubro';
            case '11':
                return 'Novembro';
            case '12':
                return 'Dezembro';
        }
    }

    public static function monthLiteralShort($month)
    {
        switch ($month) {
            case '01':
                return 'jan';
            case '02':
                return 'fev';
            case '03':
                return 'mar';
            case '04':
                return 'abr';
            case '05':
                return 'mai';
            case '06':
                return 'jun';
            case '07':
                return 'jul';
            case '08':
                return 'ago';
            case '09':
                return 'set';
            case '10':
                return 'out';
            case '11':
                return 'nov';
            case '12':
                return 'dez';
        }
    }

    /**
     * Date Literal From File
     *
     * expects something like '20150418235751'
     *
     */
    public static function dateLiteralFromFile($date)
    {
        $y = substr($date, 0, 4);
        $m = substr($date, 4, 2);
        $d = substr($date, 6, 2);
        $h = substr($date, 8, 2);
        $min = substr($date, 10, 2);
        $s = substr($date, 12, 2);
        
        return $d . ' de ' . self::monthLiteral($m) . ' de ' . $y . ' às ' . $h . ':' . $min . ':' . $s;
    }

    /**
     * Timestamp to Date
     *
     * @return string
     */
    public static function timestampToDate($timestamp, $format = 'Y-m-d H:i:s')
    {
        return date($format, $timestamp);
    }

    public static function convertDMYtoYMD($date, $separator = '/')
    {
        return substr($date, 6) . $separator . substr($date, 3, 2) . $separator . substr($date, 0, 2);
    }

    public static function convertYMDtoDMY($date)
    {
        return substr($date, 8) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);
    }

    public static function convertMYtoYMD($date)
    {
        return substr($date, 3) . '/' . substr($date, 0, 2) . '/01';
    }

    public static function getDateDMY($date = null)
    {
        $d = new \DateTime($date);
        return $d->format('d/m/Y');
    }

    public static function getDateYMD($date = null)
    {
        $d = new \DateTime($date);
        return $d->format('Y-m-d');
    }

    public static function getTime()
    {
        $d = new \DateTime();
        return $d->format('H:i');
    }

    public static function getYear($date = null)
    {
        $date = new \DateTime($date);
        return $date->format('Y');
    }

    public static function getMonth($date = null)
    {
        $date = new \DateTime($date);
        return $date->format('m');
    }

    public static function getDay($date = null)
    {
        $date = new \DateTime($date);
        return $date->format('d');
    }
    
    public static function getDayMonthShort($date = null)
    {
        $date = new \DateTime($date);
        return $date->format('d/m');
    }

    public static function getYearMonth($date)
    {
        $date = new \DateTime($date);
        return $date->format('Y/m');
    }

    public static function getMonthsArray()
    {
        $months = array(
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );
        
        return $months;
    }
}

?>
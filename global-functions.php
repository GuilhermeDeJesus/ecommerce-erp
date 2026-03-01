<?php
use Krypitonite\Util\FrameworkUtil;
use Configuration\Configuration;
use Krypitonite\Util\ValidateUtil;

function verificaSeFreteGratis($valorPedido = 0)
{
    if ($valorPedido >= VALOR_MINIMO_PARA_FRETE_GRATIS && $_SESSION['modalidade_envio'] == '03085') {
        return 1;
    } else {
        return 0;
    }
}

function seo($string)
{
    return preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', preg_replace(array(
        "/(á|à|ã|â|ä)/",
        "/(Á|À|Ã|Â|Ä)/",
        "/(é|è|ê|ë)/",
        "/(É|È|Ê|Ë)/",
        "/(í|ì|î|ï)/",
        "/(Í|Ì|Î|Ï)/",
        "/(ó|ò|õ|ô|ö)/",
        "/(Ó|Ò|Õ|Ô|Ö)/",
        "/(ú|ù|û|ü)/",
        "/(Ú|Ù|Û|Ü)/",
        "/(ñ)/",
        "/(Ñ)/",
        "/(Ç|ç)/"
    ), explode(" ", "a A e E i I o O u U n N C"), $string))));
}

function noSeo($string)
{
    return ucfirst(str_replace('-', ' ', $string));
}

function lp($var)
{
    echo "<br><br><br><div style='border:1px solid #CCC; border-radius: 5px; color:#FFF; background-color: #333; width: 945px; margin-left: 25.3%;'><pre>";
    print_r($var);
    echo "</div>";
    die();
}

function p($var)
{
    echo "<br><br><br><div style='border:1px solid #CCC; border-radius: 5px; color:#FFF; background-color: #333; width: 945px; margin-left: 25.3%;'><pre>";
    print_r($var);
    echo "</div>";
}

function dao($moduleName = null, $nameClass = null)
{
    $daos = [];
    if (! isset($daos[$nameClass]))
        $daos[$nameClass] = FrameworkUtil::factoryDAO($moduleName, $nameClass);

    return $daos[$nameClass];
}

function getImagensProduto($idProduto)
{
    $images = [];
    $path = Configuration::PATH_PRODUTO . '/' . $idProduto;
    $dir = dir($path);
    if ($dir != '') {
        while ($arquivo = $dir->read()) {
            if (strlen($arquivo) > 3) {
                $images[] = $arquivo;
            }
        }
    }

    return $images;
}

function getImagensMarca($idMarca)
{
    $images = [];
    $path = Configuration::PATH_MARCA . '/' . $idMarca;
    $dir = dir($path);
    if ($dir != '') {
        while ($arquivo = $dir->read()) {
            if (strlen($arquivo) > 3) {
                $images[] = $arquivo;
            }
        }
    }

    return $images;
}

function getSocialMidia()
{
    if (! isset($_SESSION['START_TIME'])) {

        $_SESSION['START_TIME'] = time() + 1200; // Expira em 20 Minutos
        $_SESSION['SOCIAL_MIDIA'] = $_SERVER;
    } else if (time() >= $_SESSION['START_TIME']) {

        $_SESSION['START_TIME'] = time();
        $_SESSION['SOCIAL_MIDIA'] = NULL;
    }

    if (isset($_SESSION['SOCIAL_MIDIA']['HTTP_REFERER']) || isset($_SESSION['SOCIAL_MIDIA']['HTTP_USER_AGENT'])) {
        if (isset($_SESSION['SOCIAL_MIDIA']['HTTP_REFERER']) && strpos($_SESSION['SOCIAL_MIDIA']['HTTP_REFERER'], 'facebook') !== false) {
            return 'Facebook';
        } else if (isset($_SESSION['SOCIAL_MIDIA']['HTTP_REFERER']) && strpos($_SESSION['SOCIAL_MIDIA']['HTTP_REFERER'], 'instagram') !== false) {
            return 'Instagram';
        } else {
            $instagram = strpos($_SERVER['HTTP_USER_AGENT'], "Instagram");
            if ($instagram) {
                return 'Instagram';
            }

            $facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBAN");

            if (! $facebook) {
                $facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBIOS");
            }

            if (! $facebook) {
                $facebook = strpos($_SERVER['HTTP_USER_AGENT'], "FBDV");
            }

            if ($facebook) {
                return 'Facebook';
            }

            $chrome = strpos($_SERVER['HTTP_USER_AGENT'], "Chrome");

            if ($chrome) {
                return 'Google Chrome';
            }
        }
    } else {
        return FALSE;
    }
}

function estadosBrasileiros()
{
    return array(
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    );
}

function getDescricaoTransacao($code, $clearstatus)
{
    $codigos_transacao = [
        0 => 'Transação autorizada',
        1000 => 'Transação não autorizada',
        1001 => 'Cartão vencido, tente outro cartão',
        1002 => 'Transação não permitida',
        1003 => 'Cartão rejeitado pelo emissor',
        1004 => 'Cartão com restrição, tente outro cartão',
        1005 => 'Transação não autorizada',
        1006 => 'Tentativas de senha excedidas',
        1007 => 'Cartão rejeitado pelo emissor',
        1008 => 'Cartão rejeitado pelo emissor',
        1009 => 'Transação não autorizada',
        1010 => 'Valor inválido',
        1011 => 'Número do cartão inválido, tente novamente',
        1013 => 'Transação não autorizada',
        1014 => 'Tipo de conta inválido ',
        1015 => 'Função não suportada',
        1016 => 'Saldo insuficiente',
        1017 => 'Senha inválida',
        1019 => 'Transação não permitida',
        1020 => 'Transação não permitida',
        1021 => 'Cartão rejeitado pelo emissor',
        1022 => 'Cartão com restrição',
        1023 => 'Cartão rejeitado pelo emissor',
        1024 => 'Transação não permitida',
        1025 => 'Cartão bloqueado, tente outro cartão',
        1027 => 'Excedida a quantidade de transações para o cartão.',
        1042 => 'Tipo de conta inválido',
        1045 => 'Código de segurança (CVV) inválido',
        1049 => 'Banco/emissor do cartão inválido',
        2000 => 'Cartão com restrição, favor tentar outro cartão',
        2001 => 'Cartão vencido, favor tentar outro cartão',
        2002 => 'Transação não permitida',
        2003 => 'Cartão rejeitado pelo emissor',
        2004 => 'Cartão com restrição',
        2005 => 'Transação não autorizada',
        2006 => 'Tentativas de senha excedidas',
        2007 => 'Cartão com restrição, tente outro cartão',
        2008 => 'Cartão com restrição, tente outro cartão',
        2009 => 'Cartão com restrição, tente outro cartão',
        5000 => 'Transação não autorizada',
        5003 => 'Erro interno, favor tentar novamente',
        5006 => 'Erro interno, favor tentar novamente',
        5025 => 'Código de segurança (CVV) do cartão não foi enviado',
        5054 => 'Erro interno',
        5062 => 'Transação não permitida para este produto ou serviço.',
        5086 => 'Cartão poupança inválido',
        5088 => 'Transação não autorizada Amex',
        5089 => 'Erro interno, favor tentar novamente',
        5092 => 'O valor solicitado para captura não é válido',
        5093 => 'Banco emissor Visa indisponível',
        5095 => 'Erro interno, tente novamente',
        5097 => 'Erro interno, tente novamente',
        9102 => 'Transação inválida',
        9103 => 'Cartão cancelado, tente outro cartão',
        9107 => 'O banco/emissor do cartão ou a conexão parece estar offline',
        9108 => 'Erro no processamento, favor tentar novamente',
        9109 => 'Erro no sistema do banco ou operadora de cartão, entre em contato com seu banco',
        9111 => 'Time-out na transação',
        9112 => 'Emissor indisponível',
        9113 => 'Transmissão duplicada',
        9124 => 'Código de segurança inválido',
        9988 => 'Cliente não permitido seguir pelo tipo',
        9989 => 'Cliente não permitido seguir pelo ip'
    ];

    if ($code != 0) {
        return '<b>Recusada' . $clearstatus . ': </b>' . $codigos_transacao[$code];
    } else {
        return '<b>Autorizada' . $clearstatus . ': </b>' . $codigos_transacao[$code];
    }
}

function getFormaEnvioPorCodigo($codigo = null)
{
    $formas = array(
        '03050' => 'SEDEX',
        '03085' => 'PAC',
        '04014' => 'SEDEX',
        '04510' => 'PAC'
    );

    return $formas[$codigo];
}

function getStatusClear($cod = null)
{
    $desc = array(
        "APA" => "Aprocação Automática",
        "APM" => "Aprovação Manual",
        "RPM" => "Reprovado Sem Suspeita",
        "AMA" => "Análise manual",
        "NVO" => "Novo",
        "SUS" => "Suspensão Manual",
        "CAN" => "Cancelado pelo Cliente",
        "FRD" => "Fraude Confirmada",
        "RPA" => "Reprovação Automática",
        "RPP" => "Reprovação Por Política",
        "APP" => "Aprovação Por Política"
    );

    return $desc[$cod];
}

function descontoBoleto($total = NULL, $percentual_desconto = 10, $formatMoney = FALSE)
{
    if ($total != NULL) {
        if ($total >= 100) {
            $total = (ValidateUtil::paraFloat($total) / 100) * (100 - $percentual_desconto);
        } else if ($total < 100) {
            // 2% DE DESCONTO
            $total = (ValidateUtil::paraFloat($total) / 100) * (100 - 2);
        }
    }

    switch ($formatMoney) {
        case FALSE:
            return $total;
            break;
        case TRUE:
            return ValidateUtil::setFormatMoney($total);
            break;
    }
}
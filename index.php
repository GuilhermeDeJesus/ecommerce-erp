<?php
use Krypitonite\Mail\Email;
use Krypitonite\Util\ValidateUtil;

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionSavePath = ini_get('session.save_path');

    if (empty($sessionSavePath) || !is_dir($sessionSavePath) || !is_writable($sessionSavePath)) {
        $fallbackSessionPath = sys_get_temp_dir() . '/php-sessions';

        if (!is_dir($fallbackSessionPath)) {
            @mkdir($fallbackSessionPath, 0777, true);
        }

        if (is_dir($fallbackSessionPath) && is_writable($fallbackSessionPath)) {
            session_save_path($fallbackSessionPath);
        }
    }

    session_cache_expire(3600);
    session_start();
}

require_once 'vendor/autoload.php';
require_once 'config/Configuration.php';
require_once 'krypitonite/src/Http/Request.php';
require_once 'krypitonite/src/Util/ApplicationUtil.php';
require_once 'krypitonite/src/Util/ValidateUtil.php';
require_once 'krypitonite/src/Util/DateUtil.php';
require_once 'krypitonite/src/Util/CarrinhoUtil.php';
require_once 'krypitonite/src/Util/SoapUtil.php';
require_once 'krypitonite/src/Log/Log.php';
require_once 'krypitonite/src/Util/ClearsaleUtil.php';
require_once 'krypitonite/src/Util/CorreiosUtil.php';
require_once 'krypitonite/src/Util/PaginationUtil.php';
require_once 'krypitonite/src/Mail/Email.php';
require_once 'krypitonite/src/Controller/AbstractController.php';
require_once 'global-functions.php';
require_once 'global-variables.php';

date_default_timezone_set('America/Sao_Paulo');
ini_set('max_execution_time', 10800);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
set_time_limit(0);
getSocialMidia();

define("MODULE", (Krypitonite\Http\Request::get('m') != null) ? ucfirst(Krypitonite\Http\Request::get('m')) : '');
define("CONTROLLER", (Krypitonite\Http\Request::get('c') != null) ? ucfirst(Krypitonite\Http\Request::get('c')) : '');
define("ACTION", (Krypitonite\Http\Request::get('a') != null) ? ucfirst(Krypitonite\Http\Request::get('a')) : '');
define("VIEW", (Krypitonite\Http\Request::get('v') != null) ? ucfirst(Krypitonite\Http\Request::get('v')) : '');

$configuracoesPlataforma = dao('Core', 'ConfiguracoesPlataforma')->select([
    '*'
]);

// PARAMÊTROS PADRÃO PARA O E-COMMERCE
if (sizeof($configuracoesPlataforma) != 0) {
    define("NOME_LOJA", $configuracoesPlataforma[0]['nome_loja']);
    define("LINK_LOJA", $configuracoesPlataforma[0]['url_loja']);
    define("NOME_LOGO", $configuracoesPlataforma[0]['nome_logo']);
    define("NOME_LOGO_MOBILE", $configuracoesPlataforma[0]['nome_logo_mobile']);
    define("EMAIL_CONTATO", $configuracoesPlataforma[0]['email_contato_loja']);
    define("TELEFONE_CONTATO", $configuracoesPlataforma[0]['telefone_contato_loja']);
    define("TAG_DESCRIPTION", $configuracoesPlataforma[0]['tag_description']);
    define("TAG_KEYWORDS", $configuracoesPlataforma[0]['tag_keywords']);
    define("COR_LOJA", $configuracoesPlataforma[0]['cor_loja']);
    define("PARCELAR_SEM_JUROS", $configuracoesPlataforma[0]['parcelar_sem_juros']);
    define("QTD_PARCELAS_SEM_JUROS", $configuracoesPlataforma[0]['quantidade_parcelas_sem_juros']);
    define("VALOR_MINIMO_PARA_FRETE_GRATIS", $configuracoesPlataforma[0]['valor_minimo_para_frete_gratis']);

    define("PIXEL_FACEBOOK", $configuracoesPlataforma[0]['numero_conta_anuncio_facebook']);
    define("TOKEN_PAGSEGURO", $configuracoesPlataforma[0]['token_pag_seguro']);
    define("EMAIL_PAGSEGURO", $configuracoesPlataforma[0]['email_conta_pag_seguro']);
    define("CLIENT_ID_MP", $configuracoesPlataforma[0]['cliente_id_mp']);
    define("CLIENT_SECRET_MP", $configuracoesPlataforma[0]['client_secret_mp']);

    define("TAF_D1_MP", $configuracoesPlataforma[0]['taf_d_1_mp']);
    define("TAF_D14_MP", $configuracoesPlataforma[0]['taf_d_14_mp']);
    define("TAF_D30_MP", $configuracoesPlataforma[0]['taf_d_30_mp']);

    define("GATEWAY", $configuracoesPlataforma[0]['gateway']);
    define("EMAIL_UOL", $configuracoesPlataforma[0]['email_envio']);
    define("SENHA_UOL", $configuracoesPlataforma[0]['senha_email_envio']);
    define("EMAIL_SKYHUB", 'gjsempreendedorismo4@gmail.com');
    define("SENHA_SKYHUB", 'syZTHfbQ25sqUyHdXUS_');

    define("CUPOM_LOJA", $configuracoesPlataforma[0]['cupom']);
    define("PERCENTUAL_CUPOM_DESCONTO", $configuracoesPlataforma[0]['percentual_desconto_cupom']);
    define("PERCENTUAL_DESCONTO_BOLETO", 5);
}

if (empty(MODULE) && empty(CONTROLLER)) {
    header("Location: /?m=site&c=site");
} else {
    $_namespace = "Store" . "\\" . MODULE . "\\" . "Controller" . "\\" . Krypitonite\Util\ApplicationUtil::controller(MODULE, CONTROLLER);
    $_controller = new $_namespace();
    $_controller->execute();
}


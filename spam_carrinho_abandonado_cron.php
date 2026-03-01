<?php
use Store\Sistema\Controller\TarefasCronController;
require_once 'src/Sistema/Controller/TarefasCronController.php';
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
}

// ENVIAR EMAILS DE CARRINHO ABANDONADOS
$tarefas = new TarefasCronController();

// Notificar Carrinhos Abandonados
$tarefas->notificarCarrinhosAbandonadosCron();

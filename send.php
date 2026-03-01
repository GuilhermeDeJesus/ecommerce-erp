<?php
use Krypitonite\Mail\Email;
use Krypitonite\Util\ValidateUtil;

// https://nfe.io/doc/integracao/clientes-sdk/php/
// https://imasters.com.br/back-end/emitindo-nfe-com-php
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
session_cache_expire(3600);
session_start();
error_reporting(0);

date_default_timezone_set('America/Sao_Paulo');
ini_set('max_execution_time', 3600);
set_time_limit(0);
ini_set('memory_limit', '-1');

getSocialMidia();

define("MODULE", ucfirst(Krypitonite\Http\Request::get('m')));
define("CONTROLLER", ucfirst(Krypitonite\Http\Request::get('c')));
define("ACTION", ucfirst(Krypitonite\Http\Request::get('a')));
define("VIEW", ucfirst(Krypitonite\Http\Request::get('v')));

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
    define("PERCENTUAL_DESCONTO_BOLETO", 10);
}

$email = new Email();

$_customer = dao('Core', 'Cliente')->select([
    '*'
], [
    'id',
    '=',
    $_SESSION['cliente']['id_cliente']
]);

$_endereco = dao('Core', 'Endereco')->select([
    '*'
], [
    'id_cliente',
    '=',
    $_SESSION['cliente']['id_cliente']
]);

$produtos = [
    [
        'id' => 19,
        'produto' => 'Vaca Gorda',
        'quantidade' => 7,
        'preco' => 129,
        'url' => 'https://www.com.br'
    ]
];

$body = $email->segundaViaBoletoPagarme($_customer[0]['nome'], 'https://www.php.net/manual/pt_BR/function.strip-tags.php', ValidateUtil::setFormatMoney(10 + 10), '234234234');
// $body = $email->confirmacaoPedido("Guilherme de Jesus", 123123123, $produtos, $_endereco);
// $body = $email->promocoes("Guilherme de Jesus", $produtos);
// $body = $email->pedidoEnviado('Guilherme de Jesus', '2342342342', 'LC93423434BR');
// $body = $email->contaCliente('Guilherme de Jesus Silva', '#querofazersexo', 'piruduro');
// $body = $email->confirmacaoCodigoRastreio('Guilherme de Jesus Silva', 'LP23423423BR', $produtos, $_endereco);
// $body = $email->confirmacaoPagamento('Guilherme de Jesus Silva', '23423423423');
// $body = $email->confirmacaoNewsletter('guilherme.malak@gmail.com');
// $body = $email->notificarCarrinhoAbandonado('Guilherme de Jesus', $produtos);
// $body = $email->cancelamentoPedido('Guilherme de Jesus Silva', '23423423423', $produtos, $_endereco);
// $body = $email->segundaViaBoletoMercadoPago($_customer[0]['nome'], 'https://www.php.net/manual/pt_BR/function.strip-tags.php', ValidateUtil::setFormatMoney(10 + 10));
// $body = $email->segundaViaBoleto($_customer[0]['nome'], 'https://www.php.net/manual/pt_BR/function.strip-tags.php', ValidateUtil::setFormatMoney(10 + 10));
// $body = $email->duvidas($_customer[0]['nome'], $_customer[0]['email']);
// $body = $email->pedidoCancelado($_customer[0]['nome'], 23423423423423);
$email->send(trim('guilherme.malak@gmail.com'), "Não perca tempo, pague seu boleto e Receba seu Produto - " . NOME_LOJA, $body, '1001');


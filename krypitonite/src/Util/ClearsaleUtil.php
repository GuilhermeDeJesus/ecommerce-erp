<?php
namespace Krypitonite\Util;

use ClearSale\Order;
use Krypitonite\Log\Log;

// API https://github.com/odesenvolvedor/clearsale-php-sdk/blob/master/examples/ecommerce-order-status.php
class ClearsaleUtil
{

    const LOGIN_SANDBOX = "Shopvitas";

    const SENHA_SANDBOX = "RvicJHU7NX";

    const MEU_APP_SANDBOX = 'wxcftq91ssei846pcv6h';

    const LOGIN_PRODUCTION = "Shopvitas";

    const SENHA_PRODUCTION = "eZ94JMhqAp";

    const MEU_APP_PRODUCTION = 'wxcftq91ssei846pcv6h';

    public static function sendOrder($data_credCard = [], $customer = [], $address = [], $itens = [], $nsu = NULL, $numeroPedido = NULL)
    {
        require ('vendor/odesenvolvedor/clearsale-php-sdk/autoload.php');

        try {

            $environment = new \ClearSale\Environment\Environment(new \ClearSale\Environment\Production());
            // $environment = new \ClearSale\Environment\Environment(new \ClearSale\Environment\Sandbox());

            $auth = new \ClearSale\Auth\Login(self::LOGIN_PRODUCTION, self::SENHA_PRODUCTION);
            // $auth = new \ClearSale\Auth\Login(self::LOGIN_SANDBOX, self::SENHA_SANDBOX);

            $orderRequest = new \ClearSale\Request\ClearSaleOrderRequest($environment, $auth);

            $json = json_encode(self::_montarJson($data_credCard, $customer, $address, $itens, $nsu, $numeroPedido));
            $order = Order::fromJson($json);
            $result = $orderRequest->send($order);

            return $result->orders[0];
        } catch (\ClearSale\Request\ClearSaleRequestException $exception) {
            $error = $exception->getClearSaleError();
            Log::error('ClearUtil deu erro no envio do pedido: ' . serialize($error));
            return false;
        }
    }

    public static function getOrderData($data_credCard = [], $customer = [], $address = [], $itens = [], $nsu = NULL, $numeroPedido = NULL)
    {
        return self::_montarJson($data_credCard, $customer, $address, $itens, $nsu, $numeroPedido);
    }

    // APA (Aprovação Automática) – Pedido foi aprovado automaticamente segundo parâmetros definidos na regra de aprovação automática
    // APM (Aprovação Manual) – Pedido aprovado manualmente por tomada de decisão de um analista
    // RPM (Reprovado Sem Suspeita) – Pedido Reprovado sem Suspeita por falta de contato com o cliente dentro do período acordado e/ou políticas restritivas de CPF (Irregular, SUS ou Cancelados)
    // AMA (Análise manual) – Pedido está em fila para análise
    // NVO (Novo) – Pedido importado e não classificado Score pela analisadora (processo que roda o Score de cada pedido)
    // SUS (Suspensão Manual) – Pedido Suspenso por suspeita de fraude baseado no contato com o “cliente” ou ainda na base ClearSale
    // CAN (Cancelado pelo Cliente) – Cancelado por solicitação do cliente ou duplicidade do pedido
    // FRD (Fraude Confirmada) – Pedido imputado como Fraude Confirmada por contato com a administradora de cartão e/ou contato com titular do cartão ou CPF do cadastro que desconhecem a compra
    // RPA (Reprovação Automática) – Pedido Reprovado Automaticamente por algum tipo de Regra de Negócio que necessite aplicá-la
    // RPP (Reprovação Por Política) – Pedido reprovado automaticamente por política estabelecida pelo cliente ou Clearsale
    // APP (Aprovação Por Política) – Pedido aprovado automaticamente por política estabelecida pelo cliente ou Clearsale
    public static function checkStatus($numeroPedido)
    {
        require ('vendor/odesenvolvedor/clearsale-php-sdk/autoload.php');

        try {
            $environment = new \ClearSale\Environment\Environment(new \ClearSale\Environment\Production());
            // $environment = new \ClearSale\Environment\Environment(new \ClearSale\Environment\Sandbox());

            $auth = new \ClearSale\Auth\Login(self::LOGIN_PRODUCTION, self::SENHA_PRODUCTION);
            // $auth = new \ClearSale\Auth\Login(self::LOGIN_SANDBOX, self::SENHA_SANDBOX);

            $orderRequest = new \ClearSale\Request\ClearSaleOrderRequest($environment, $auth);

            $orderCode = $numeroPedido;

            return $orderRequest->statusCheck($orderCode)->getStatus();
        } catch (\ClearSale\Request\ClearSaleRequestException $exception) {
            Log::error('ClearUtil: erro ao analisar pedido: ' . $numeroPedido);
            return false;
        }
    }

    private static function _montarJson($data_credCard = [], $customer = [], $address = [], $itens = [], $nsu = NULL, $numeroPedido = 0)
    {
        $_itens = [];
        $i = 0;
        $totalPedido = [];
        $quantidadeProdutos = [];
        foreach ($itens as $item) {
            $i ++;
            $valItem = floatval($item['valor']);
            $totalPedido[] = $valItem;
            $idCategoria = dao('Core', 'Produto')->getField('id_categoria', $item['codigo']);
            $nomeCategoria = dao('Core', 'Categoria')->getField('descricao', $idCategoria);
            $quantidadeProdutos[] = $item['quantidade'];
            // $SKU = dao('Core', 'Produto')->getField('sku', $item['codigo']);

            $_itens[] = [
                "code" => "$i",
                "name" => $item['descricao'],
                // "barCode" => "$SKU",
                "value" => $valItem,
                "amount" => 1,
                "categoryID" => intval($idCategoria),
                "categoryName" => $nomeCategoria,
                "isGift" => true,
                // "sellerName" => "GJS EMPREENDEDORISMO DIGITAL LTADA",
                // "sellerDocument" => "20747907000126",
                "isMarketPlace" => "false",
                "shippingCompany" => "Correios"
            ];
        }

        $amount = floatval($data_credCard['total']);
        $total_frete = floatval($data_credCard['total_frete']);
        $ip = $_SERVER["REMOTE_ADDR"];
        $SessionID = self::getIdSession();
        $QuantidadeParcelas = $data_credCard['quantidade_parcela'];
        $ObservacaoPedido = "Cliente realizou a compra de " . array_sum($quantidadeProdutos) . " produto(s)";
        $customerID = $customer['id'];

        $adress = [
            "street" => $address['endereco'],
            "number" => $address['numero'],
            "county" => $address['bairro'],
            "city" => $address['cidade'],
            "state" => $address['uf'],
            "zipcode" => $address['cep'],
            "country" => "Brasil"
            // "reference" => "Referencia do endereço"
        ];

        if ($address['complemento'] != NULL) {
            $adress['additionalInformation'] = $address['complemento'];
        }

        // purchaseInformation
        $purchaseInformation = [];
        if ($customer['data_hora_ultima_alteracao'] != NULL) {
            $purchaseInformation["lastDateInsertedMail"] = $customer['data_hora_ultima_alteracao'];
            $purchaseInformation["lastDateChangePassword"] = $customer['data_hora_ultima_alteracao'];
            $purchaseInformation["lastDateChangePhone"] = $customer['data_hora_ultima_alteracao'];
            $purchaseInformation["lastDateChangeMobilePhone"] = $customer['data_hora_ultima_alteracao'];
        }

        if ($address['data_hora_ultima_alteracao'] != NULL) {
            $purchaseInformation["lastDateInsertedAddress"] = $address['data_hora_ultima_alteracao'];
        }

        $purchaseInformation["purchaseLogged"] = true;
        $purchaseInformation["email"] = $customer['email'];
        $purchaseInformation["login"] = $customer['email'];

        $_data = [
            "code" => "$numeroPedido",
            "sessionID" => "$SessionID",
            "date" => date("Y-m-d") . "T" . date("H:i:s") . ".0000000",
            "email" => $customer['email'],
            "b2bB2c" => "B2C",
            "itemValue" => array_sum($totalPedido), // Valor Total dos Itens
            "totalValue" => $amount, // Valor Total do Pedido
            "numberOfInstallments" => intval($QuantidadeParcelas),
            "ip" => $ip,
            "isGift" => false,
            // "giftMessage" => "Mensagem de Presente",
            "observation" => $ObservacaoPedido,
            "status" => self::getStatusPedido(),
            "origin" => "E-commerce Shopvitas",
            "channelID" => self::getDispositivoPedido(),
            // "reservationDate" => "2017-03-21T22:36:36.0000000", // Data de reserva de Voo
            "country" => "Brasil",
            "nationality" => "Brasileiro",
            "product" => 4,
            // -1 Outros
            // 1 Application
            // 3 Total
            // 4 Total Garantido
            // 9 Score
            // 10 Realtime Decision
            // 11 Tickets
            "customSla" => 60,
            // "bankAuthentication" => "Aprovado 3DS",
            // "subAcquirer" => "Pagar.me",
            "list" => [
                "typeID" => 1,
                "id" => "Lista de Pedido de Produtos da Shopvitas"
            ],
            "purchaseInformation" => $purchaseInformation,
            "billing" => [
                "clientID" => "$customerID",
                "type" => 1,
                "primaryDocument" => $customer['cpf'],
                // "secondaryDocument" => "12345678" // RG ou Inscrição Estadual,
                "name" => $customer['nome'],
                "birthDate" => $customer['data_nascimento'] . "T00:00:00.000",
                "email" => $customer['email'],
                // "gender" => "M",
                "address" => $adress,
                "phones" => [
                    [
                        "type" => 1,
                        "ddi" => 55,
                        "ddd" => intval(substr($customer['telefone'], 0, 2)),
                        "number" => intval(substr($customer['telefone'], 2))
                        // "extension" => "1111"
                    ]
                ]
            ],
            "shipping" => [
                "clientID" => "$customerID",
                "type" => 1,
                "primaryDocument" => $customer['cpf'],
                // "secondaryDocument" => "12345678" // RG ou Inscrição Estadual,
                "name" => $customer['nome'],
                "birthDate" => $customer['data_nascimento'] . "T00:00:00.000",
                "email" => $customer['email'],
                // "gender" => "M",
                "address" => $adress,
                "phones" => [
                    [
                        "type" => 1,
                        "ddi" => 55,
                        "ddd" => intval(substr($customer['telefone'], 0, 2)),
                        "number" => intval(substr($customer['telefone'], 2))
                        // "extension" => "1111"
                    ]
                ],
                "deliveryType" => "11",
                "deliveryTime" => "15 dias úteis",
                "price" => $total_frete
                // "pickUpStoreDocument" => "CPF para retirada em loja"
            ],
            "payments" => [
                [
                    "sequential" => 1,
                    "date" => date("Y-m-d") . "T" . date("H:i:s") . ".0000000",
                    "value" => $amount,
                    "type" => 1,
                    "installments" => intval($data_credCard['quantidade_parcela']),
                    "interestRate" => $data_credCard['percetual_taxa'], // Taxa de Juros
                    "interestValue" => $data_credCard['valor_taxa'], // Valor dos Juros
                    "currency" => 986,
                    // "voucherOrderOrigin" => "123456",
                    "card" => [
                        "number" => $data_credCard['numero_cartao'],
                        // "hash" => "12345678945612301234569874563210",
                        "bin" => substr($data_credCard['numero_cartao'], 0, 6),
                        "end" => substr($data_credCard['numero_cartao'], - 1, 4),
                        "type" => self::getBandeiraCartao($data_credCard['numero_cartao']),
                        "validityDate" => $data_credCard['mes_expiracao'] . '/' . $data_credCard['ano_expiracao'],
                        "ownerName" => $data_credCard['titular'],
                        "document" => $customer['cpf'],
                        "nsu" => "$nsu" // COLOCAR NSU DO PAGAR.ME
                    ]
                    // "address" => $adress
                ]
            ],
            "items" => $_itens
        ];

        if ($customer['sexo'] != NULL || $customer['sexo'] == 'F' || $customer['sexo'] == 'M') {
            $_data["shipping"]["gender"] = $customer['sexo'];
            $_data["billing"]["gender"] = $customer['sexo'];
        }

        return $_data;
    }

    public static function getDispositivoPedido()
    {
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $symbian = strpos($_SERVER['HTTP_USER_AGENT'], "Symbian");

        if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
            return "Mobile";
        } else {
            return "Desktop";
        }
    }

    public static function getStatusPedido($idPedido = NULL)
    {
        // 0 Novo (será analisado pelo ClearSale)
        // 9 Aprovado (irá ao ClearSale já aprovado e não será analisado)
        // 41 Cancelado pelo cliente (irá ao ClearSale já cancelado e não será analisado)
        // 45 Reprovado (irá ao ClearSale já reprovado e não será analisado)
        $_status = [
            0,
            9,
            41,
            45
        ];

        if ($idPedido == NULL) {
            return $_status[0];
        } else {
            // CRIA A LÓGICA DE ACORDO COM O STATUS DO PEDIDO
        }
    }

    public static function _consultarStatusPedido()
    {}

    private static function getBandeiraCartao($numeroCartao = NULL)
    {
        $brands_pattern = [
            5 => "/^3[47][0-9]{13}/",
            1 => " /^3(?:0[0-5]|[68][0-9])[0-9]{11}/",
            10 => "/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})/",
            6 => " /^(606282\d{10}(\d{3})?)|(3841\d{15})/",
            2 => " /^5[1-5][0-9]{14}/",
            3 => "/^4[0-9]{12}(?:[0-9]{3})/"
        ];

        $_brand = NULL;
        foreach ($brands_pattern as $brand => $pattern) {
            $matches = array();
            if (preg_match($pattern, $numeroCartao, $matches)) {
                $_brand = $brand;
                break;
            }
        }

        if ($_brand == NULL) {
            // OUTROS
            $_brand = 4;
        }

        return $_brand;
    }

    public function getIdSession()
    {
        return $_SESSION['MY_ID_SESSION'];
    }

    public function unsetIDSession()
    {
        unset($_SESSION['MY_ID_SESSION']);
    }
}
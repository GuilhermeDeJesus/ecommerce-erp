<?php
namespace Store\Pagamento\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\ValidateUtil;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'lib/lib-mercadopago/lib/mercadopago.php';
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Mail\Email;
use Krypitonite\Util\CorreiosUtil;
use MercadoPago\SDK;
use MercadoPago;
use MercadoPago\Payer;
use MercadoPago\Item;

class PagamentoMPController extends AbstractController
{

    private $back_url = 'https://' . LINK_LOJA . '/?m=pagamento&c=pagamentoMP&a=statusPayment';

    private $public_key_teste = 'TEST-415438e8-0431-4c19-a45a-49e96ecf38ce';
    private $access_token_teste = 'TEST-1600805108870459-102015-ede126ad029b854dc7585d727a1d8d49-462582252';

    private $public_key_producao = 'APP_USR-6e2b47b3-0b96-48ab-b6bb-5a8df5447ff0';
    private $access_token_producao = 'APP_USR-1600805108870459-102015-eb4d51530885d3d1df8242380607f8b7-462582252';

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function checkoutTransparenteAction()
    {
        $mp = new SDK();
        $mp->setAccessToken($this->access_token_producao);

        $_customer = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_SESSION['cliente']['id_cliente']
        ]);

        $_address = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $this->post('endereco')
        ]);

        $numPedido = rand(110000000, 990000000);
        $amount = round((float) $this->_total_a_pagar($this->post('_total_float'), $this->post('_total_frete')), 2);
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $amount;
        $payment->token = $this->post('token');
        $payment->description = "Pedido " . NOME_LOJA . " #$numPedido";
        $payment->installments = (int) $this->post('installmentsOption');
        $payment->payment_method_id = $this->post('paymentMethodId');
        // $payment->issuer_id = '';
        $payment->payer = array(
            "email" => $_customer[0]['email']
        );

        $area_code = intval(substr($_customer[0]['telefone'], 0, 2));
        $number = trim(substr($_customer[0]['telefone'], 2));
        $cpf = $_customer[0]['cpf'];

        $payer = new Payer();
        $payer->name = trim($_customer[0]['nome']);
        $payer->surname = end(explode(" ", $_customer[0]['nome']));
        $payer->email = $_customer[0]['email'];
        // $payer->date_created = date('Y-m-d').'T'.date('h:i:s');
        $payer->phone = array(
            "area_code" => "$area_code",
            "number" => "$number"
        );

        $payer->identification = array(
            "type" => "CPF",
            "number" => "$cpf"
        );

        $cep = substr(str_replace('-', '', $_address[0]['cep']), 0, 5);
        $payer->address = array(
            "street_name" => $_address[0]['endereco'],
            "street_number" => intval($_address[0]['numero']),
            "zip_code" => "$cep"
        );

        $item = new Item();
        $ipmp = CarrinhoUtil::getItens('_itens');
        foreach ($ipmp as $it) {
            $pd = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $it['codigo']
            ]);

            $_id = $it['codigo'];
            $item->id = "$_id";
            $item->title = $pd[0]['descricao'];
            $item->quantity = intval($it['quantidade']);
            $item->currency_id = "BRL";
            $item->unit_price = floatval(number_format($it['valor_unitario'], 2));
        }

        $payment->save();

        // Check Status Payment
        $success = FALSE;
        $error = FALSE;
        switch ($payment->status) {
            case 'rejected':
                $success = FALSE;
                break;
            case 'cancelled':
                $success = FALSE;
                break;
            case 'approved':

                $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $this->post('endereco'));
                $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
                $_total = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], $data['_total_carrinho']);

                // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
                $_frete = $data['_frete_total'];
                if (! isset($_SESSION['modalidade_envio'])) {
                    if ((float) $_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                        $_frete = 0;
                        $_total = $_total - $_frete;
                    }
                } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
                    if ((float) $_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                        $_frete = 0;
                        $_total = $_total - $_frete;
                    }
                }

                $_hora = date("H:i:s");

                // Gerar Pedido
                $form_pedido = [
                    "data" => DateUtil::now(),
                    "hora" => "$_hora",
                    "valor" => $_total,
                    "frete" => $data['_frete_total'],
                    "frete_gratis" => verificaSeFreteGratis($_total),
                    "id_cliente" => $_SESSION['cliente']['id_cliente'],
                    "id_endereco" => $this->post('endereco'),
                    "id_situacao_pedido" => 1,
                    "id_pedido_status_fornecedor" => 1,
                    "numero_pedido" => $numPedido,
                    "gateway" => "Mercado Pago",
                    "dispositivo" => ValidateUtil::getDispositivo()
                ];

                $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

                // Envia para conferencia
                $email = new Email();

                // Itens
                $custoTotal = [];
                $itens_pedido = CarrinhoUtil::getItens('_itens');

                foreach ($itens_pedido as $item) {

                    // Limpar queridinhos, afinal o cliente fez a compra
                    $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho')->delete([
                        [
                            'id_produto',
                            '=',
                            $item['codigo']
                        ],
                        [
                            'id_cliente',
                            '=',
                            $_SESSION['cliente']['id_cliente']
                        ]
                    ]);

                    $form_item = [
                        'id_pedido' => $idPedido,
                        'id_situacao_item_pedido' => 1
                    ];

                    if (isset($item['quantidade'])) {
                        $form_item['quantidade'] = $item['quantidade'];
                    }

                    if (isset($item['codigo'])) {
                        $form_item['id_produto'] = $item['codigo'];
                    }

                    if (isset($item['valor'])) {
                        $form_item['preco'] = $item['valor'];
                    }

                    if (isset($item['cor']) && $item['cor'] != NULL) {
                        $form_item['id_cor_produto'] = $item['cor'];
                    }

                    if (isset($item['tamanho']) && $item['tamanho'] != NULL) {
                        $form_item['id_tamanho_produto'] = $item['tamanho'];
                        $tamanho = $this->dao('Core', 'TamanhoProduto')->select([
                            '*'
                        ], [
                            [
                                'id',
                                '=',
                                $item['tamanho']
                            ]
                        ]);

                        $_custo = $item['quantidade'] * $tamanho[0]['custo'];
                        $custoTotal[] = $_custo;
                        $lucro = $item['valor'] - $_custo;
                        $form_item['lucro'] = $lucro;
                        $form_item['custo'] = $_custo;

                        $this->dao('Core', 'Pedido')->update([
                            'lucro' => $lucro,
                            'custo' => $_custo
                        ], [
                            'id',
                            '=',
                            $idPedido
                        ]);
                    } else {

                        $produtoSelect = $this->dao('Core', 'Produto')->select([
                            '*'
                        ], [
                            'id',
                            '=',
                            $item['codigo']
                        ]);

                        $lucro = $item['valor'] - ($produtoSelect[0]['valor_compra'] * $item['quantidade']);
                        $form_item['lucro'] = $lucro;
                        $form_item['custo'] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

                        $custoTotal[] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);
                        $this->dao('Core', 'Pedido')->update([
                            'lucro' => $lucro,
                            'custo' => ($produtoSelect[0]['valor_compra'] * $item['quantidade'])
                        ], [
                            'id',
                            '=',
                            $idPedido
                        ]);
                    }

                    $this->dao('Core', 'ItemPedido')->insert($form_item);
                }

                $taxa = 0;
                $lucro = ($_total + $_frete) - (array_sum($custoTotal) + $taxa);

                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2,
                    'codigo_transacao' => $payment->id,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'tid' => $payment->id,
                    'response_code_gateway' => 0,
                    'percentual_taxa_total' => 0,
                    'valor_total_taxa' => 0,
                    'lucro' => $lucro
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $idPedido
                ]);

                // ENDEREÇO CLIENTE
                $endereco = $this->dao('Core', 'Endereco')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $this->dao('Core', 'Pedido')
                        ->getField('id_endereco', $idPedido)
                ]);

                // PRODUTOS
                $produtos = [];
                foreach ($itens as $item) {
                    $produtos[] = [
                        'produto' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']),
                        'quantidade' => $item['quantidade'],
                        'preco' => $item['preco']
                    ];
                }

                // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                $bodyConfirmacaoPedido = $email->confirmacaoPedido($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido), $produtos, $endereco);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                $bodyConfirmacao = $email->confirmacaoPagamento($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido));

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                $email->send($_customer[0]['email'], "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                $success = TRUE;
                break;
        }

        $retornoCartao = [
            'error' => $error,
            'situacao_pagamento' => $this->get_status_detail($payment->status_detail),
            'status' => $payment->status,
            'code' => "$numPedido",
            'date' => $payment->date_created,
            'parcela' => $_POST['installmentsOption'] . "x de R$ " . ValidateUtil::setFormatMoney($amount),
            'bandeira' => 'visa',
            'success' => $success,
            'pedido' => $numPedido,
            'total' => $amount
        ];

        echo json_encode($retornoCartao);
    }

    private function get_status_detail($status)
    {
        $mot = '';
        switch ($status) {
            case 'accredited':
                $mot = 'Pronto, seu pagamento foi aprovado!';
                break;
            case 'pending_contingency':
                $mot = 'Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado. Estamos processando seu pagamento!';
                break;
            case 'pending_review_manual':
                $mot = 'Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado ou se necessitamos de mais informação.';
                break;
            case 'cc_rejected_bad_filled_card_number':
                $mot = 'Revise o número do cartão.';
                break;
            case 'cc_rejected_bad_filled_date':
                $mot = 'Revise a data de vencimento.';
                break;
            case 'cc_rejected_bad_filled_other':
                $mot = 'Revise os dados.';
                break;
            case 'cc_rejected_blacklist':
                $mot = 'Não pudemos processar seu pagamento.';
                break;
            case 'cc_rejected_card_error':
                $mot = 'Não conseguimos processar seu pagamento.';
                break;
            case 'cc_rejected_duplicated_payment':
                $mot = 'Você já efetuou um pagamento com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.';
                break;
            case 'cc_rejected_high_risk':
                $mot = 'Seu pagamento foi recusado.';
                break;
            case 'cc_rejected_insufficient_amount':
                $mot = 'Saldo insuficiente';
                break;
            default:
                $mot = 'Não pudemos processar seu pagamento.';
                break;
        }

        return $mot;
    }

    public function getBandeira($numeroCartao = '')
    {
        $brands_pattern = [
            'amex' => "/^3[47][0-9]{13}/",
            'diners' => " /^3(?:0[0-5]|[68][0-9])[0-9]{11}/",
            'Discover' => "/^6(?:011|5[0-9]{2})[0-9]{12}/",
            'elo' => "/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})/",
            'hipercard' => " /^(606282\d{10}(\d{3})?)|(3841\d{15})/",
            'JCB' => "/^(?:2131|1800|35\d{3})\d{11}/",
            'mastercard' => " /^5[1-5][0-9]{14}/",
            'visa' => "/^4[0-9]{12}(?:[0-9]{3})/"
        ];

        $_brand = NULL;
        foreach ($brands_pattern as $brand => $pattern) {
            $matches = array();
            if (preg_match($pattern, $numeroCartao, $matches)) {
                $_brand = $brand;
                break;
            }
        }

        return $_brand;
    }

    public function statusPaymentAction()
    {
        $mp = new \MP(CLIENT_ID_MP, CLIENT_SECRET_MP);

        $email = new Email();

        $params = [
            "access_token" => $mp->get_access_token()
        ];

        $topic = "payment";

        $id = NULL;
        if (isset($_GET['collection_id'])) {
            $id = $_GET['collection_id'];
        } else if ($_GET['id']) {
            $id = $_GET['id'];
        }

        if ($id != NULL && $topic == "payment") {
            $payment_info = $mp->get("/collections/notifications/" . $id, $params, false);

            // GET STATUS
            $status = 1;
            $_total = $payment_info["response"]["collection"]["transaction_amount"];
            switch ($payment_info["response"]["collection"]['status']) {
                case "approved":
                    $this->setPurchaseFacebook($payment_info["response"]["collection"]["transaction_amount"]);
                    $status = 2;
                    break;
                case "rejected":
                    $status = 3;
                    break;
                case "cancelled":
                    $status = 3;
                    break;
                case "pending":
                    $this->setPurchaseFacebook($payment_info["response"]["collection"]["transaction_amount"]);
                    $status = 1;
                    break;
            }

            // TIPO DE PAGAMENTO
            $type = '';
            switch ($payment_info["response"]["collection"]["payment_type"]) {
                case "ticket":
                    $type = 'Boleto';
                    break;
                case "credit_card":
                    $type = 'Cartao';
                    break;
                case "account_money":
                    $type = 'Saldo em Conta';
                    break;
            }

            // NÚMERO DO PEDIDO
            $ref = $payment_info["response"]["collection"]["external_reference"];

            // SALVA O CÓDIGO DA TRANSAÇÃO
            $this->dao('Core', 'Pedido')->update([
                'codigo_transacao' => $payment_info["response"]["collection"]["order_id"],
                'tipo_pagamento' => $type,
                "id_situacao_pedido" => $status
            ], [
                'numero_pedido',
                '=',
                $ref
            ]);

            $pedido = $this->dao('Core', 'Pedido')->select([
                '*'
            ], [
                'numero_pedido',
                '=',
                $ref
            ]);

            $itens = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido[0]['id']
            ]);

            // PRODUTOS
            $produtos = [];
            foreach ($itens as $item) {
                $produtos[] = [
                    'produto' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']),
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco']
                ];
            }

            // ENDEREÇO CLIENTE
            $endereco = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido[0]['id_endereco']
            ]);

            // CLIENTE
            $cliente = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido[0]['id_cliente']
            ]);

            // SEND MAIL CONFIRM
            switch ($status) {
                case 2:
                    // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                    $bodyConfirmacaoPedido = $email->confirmacaoPedido($cliente[0]['nome'], $this->dao('Core', 'Pedido')
                        ->getField('numero_pedido', $ref), $produtos, $endereco);

                    // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                    $email->send(trim($cliente[0]['email']), "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                    break;

                case 1:
                    $_valorPedido = "R$ " . ValidateUtil::setFormatMoney($payment_info["response"]["collection"]["transaction_amount"]);
                    $notificaBoleto = $email->segundaViaBoletoMercadoPago($cliente[0]['nome'], "", $_valorPedido);
                    $email->send(trim($cliente[0]['email']), "Olá " . trim($cliente[0]['nome']) . ', não esqueça do seu boleto', $notificaBoleto, '1001');
                    break;
            }

            $status_ar = [
                1 => "Aguardando Pagamento",
                2 => "Aprovado",
                3 => "Não foi possível efetuar o pagamento"
            ];

            $types_payment = [
                'ticket' => "Boleto Bancário",
                'credit_card' => "Cartão de Crédito",
                'account_money' => "Saldo em Conta no Mercado Pago"
            ];

            $data = [
                'numero_pedido' => $ref,
                'tipo_pagamento' => $types_payment[$payment_info["response"]["collection"]["payment_type"]],
                "valor" => "R$ " . ValidateUtil::setFormatMoney($payment_info["response"]["collection"]["transaction_amount"]),
                '_total' => $_total,
                '_pixel' => $_SESSION['pixel_produto'],
                '_correspondencia_fbk' => $_SESSION['data_corresondencia_avancada'],
                "status" => $status_ar[$status]
            ];

            $this->renderView('checkout_final', $data);
        }
    }

    public function setPurchaseFacebook($value = 0)
    {
        $pixel = PIXEL_FACEBOOK;
        if ($_SESSION['pixel_produto'] != '') {
            $pixel = $_SESSION['pixel_produto'];
        }

        $face = "<script>!function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');";
        $face .= "fbq('init', '" . $pixel . "');</script>";
        $face .= "<script type='text/javascript'>fbq('track', 'Purchase', {value: " . $value . ", currency: 'BRL'});</script>";
        $face .= "<noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id='" . $pixel . "'&ev=Purchase&noscript=1'/></noscript>";
        echo $face;
    }

    public function _total_a_pagar($total_pedido = 0, $frete_total = 0)
    {
        $ref = $_SESSION['cliente']['id_cliente'] . mt_rand();
        $total_pedido = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], $total_pedido);

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
        if (! isset($_SESSION['modalidade_envio'])) {
            if ((float) $total_pedido >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $frete_total = 0;
            }
        } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
            if ((float) $total_pedido >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $frete_total = 0;
            }
        }

        return $total_pedido + $frete_total;
    }

    public function initChechotAction($unit_price = 0, $total_pedido = 0, $frete_total = 0, $idEndereco = 0)
    {
        $ref = $_SESSION['cliente']['id_cliente'] . mt_rand();
        $total_pedido = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], $total_pedido);

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
        if (! isset($_SESSION['modalidade_envio'])) {
            if ((float) $total_pedido >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $frete_total = 0;
            }
        } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
            if ((float) $total_pedido >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $frete_total = 0;
            }
        }

        $form_pedido = [
            "data" => DateUtil::now(),
            "hora" => date("H:i:s"),
            "valor" => $total_pedido,
            "frete" => $frete_total,
            "frete_gratis" => verificaSeFreteGratis($total_pedido + $frete_total),
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $idEndereco,
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => $ref,
            "gateway" => "Mercado Pago",
            "dispositivo" => ValidateUtil::getDispositivo()
        ];

        // GERA PEDIDO
        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        if ($idPedido) {
            $this->saveItensPedido($idPedido);
        }

        $mp = new \MP(CLIENT_ID_MP, CLIENT_SECRET_MP);
        $preference_data = array(
            "items" => array(
                array(
                    "title" => "Pedido " . NOME_LOJA . " #" . $ref,
                    "currency_id" => "BRL",
                    "category_id" => "Category",
                    "quantity" => 1,
                    "unit_price" => $total_pedido + $frete_total
                )
            ),
            "back_urls" => array(
                "success" => $this->back_url . "&status=success",
                "failure" => $this->back_url . "&status=failure",
                "pending" => $this->back_url . "&status=pending"
            ),
            "auto_return" => "approved",
            "notification_url" => $this->back_url,
            "external_reference" => $ref
        );

        $preference = $mp->create_preference($preference_data);

        return $preference;
    }

    public function saveItensPedido($idPedido)
    {
        $itens_pedido = CarrinhoUtil::getItens('_itens');

        // Itens
        foreach ($itens_pedido as $item) {

            // Limpar queridinhos, afinal o cliente fez a compra
            $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho')->delete([
                [
                    'id_produto',
                    '=',
                    $item['codigo']
                ],
                [
                    'id_cliente',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]
            ]);

            $form_item = [
                'id_pedido' => $idPedido,
                'id_situacao_item_pedido' => 1
            ];

            if (isset($item['quantidade'])) {
                $form_item['quantidade'] = $item['quantidade'];
            }

            if (isset($item['codigo'])) {
                $form_item['id_produto'] = $item['codigo'];
            }

            if (isset($item['valor'])) {
                $form_item['preco'] = $item['valor'];
            }

            if (isset($item['cor']) && $item['cor'] != NULL) {
                $form_item['id_cor_produto'] = $item['cor'];
            }

            if (isset($item['tamanho']) && $item['tamanho'] != NULL) {
                $form_item['id_tamanho_produto'] = $item['tamanho'];
                $tamanho = $this->dao('Core', 'TamanhoProduto')->select([
                    '*'
                ], [
                    [
                        'id',
                        '=',
                        $item['tamanho']
                    ]
                ]);

                // VOU CONSIDERAR QUE VOU SACAR O DINHEIRO E RECEBER EM 1 DIA
                $valor_liquido_recebido = ($item['valor'] / 100) * (100 - TAF_D1_MP);
                $_custo = $item['quantidade'] * $tamanho[0]['custo'];
                $lucro = floatval($valor_liquido_recebido) - $_custo;
                $form_item['lucro'] = $lucro;

                $this->dao('Core', 'Pedido')->update([
                    'lucro' => $lucro
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);
            } else {
                $produtoSelect = $this->dao('Core', 'Produto')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $item['codigo']
                ]);

                $lucro = $item['valor'] - ($produtoSelect[0]['valor_compra'] * $item['quantidade']);
                $form_item['lucro'] = $lucro;

                $this->dao('Core', 'Pedido')->update([
                    'lucro' => $lucro
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);
            }

            $this->dao('Core', 'ItemPedido')->insert($form_item);
        }
    }

    public function getFrete_e_ValorTotal_Carrinho($cepDestino = NULL)
    {
        $_frete_total = [];
        $_produtos = [];
        $_frete_gratis = FALSE;
        $_total = [];

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // SEGUNDO A REGRA, QUANDO O VALOR DA COMPRA FOR MAIOR QUE R$ 300,00, DEVO OFERECER FRETE GRÁTIS, ENTÃO
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            $_total[] = $_t['valor'];
        }

        // if ((float) array_sum($_total) >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
        // $_frete_gratis = TRUE;
        // }

        // DEFINO ACIMA SE O FRETE É GRÁTIS OU NÃO
        // ______________________________________________________

        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            if (! $_t['frete_gratis'] && $_frete_gratis == FALSE) {

                // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // CAPTURA O CEP DO FORNECEDOR DO PRODUTO
                // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $cep_origem = $this->dao('Core', 'Pessoa')->getField('cep', $this->dao('Core', 'Produto')
                    ->getField('id_fornecedor', $_t['codigo']));

                // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // PEGA TODOS OS PRODUTOS
                // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $produto = $this->dao('Core', 'Produto')->select([
                    'peso_bruto',
                    'peso_liquido',
                    'comprimento',
                    'largura',
                    'altura',
                    'produto_gratis',
                    'valor_venda'
                ], [
                    'id',
                    '=',
                    $_t['codigo']
                ]);

                if (sizeof($produto) != 0) {
                    // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // AGRUPA OS PRODUTOS POR CEP (FORNECEDOR)
                    // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $produto[0]['cep_fornecedor'] = $cep_origem;
                    $produto[0]['quantidade'] = $_t['quantidade'];
                    $_produtos[$cep_origem][] = $produto[0];
                }
            }
        }

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CALCULA O FRETE POR FORNECEDOR
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (sizeof($_produtos) != 0 && $_frete_gratis == FALSE) {
            foreach ($_produtos as $key_cepOrigem => $_produto) {
                $correiosUtil = new CorreiosUtil();
                $_frete_total[] = $correiosUtil->calcularPrecoPrazo($key_cepOrigem, str_replace(" ", "", ValidateUtil::cleanString($cepDestino)), TRUE, $_produtos[$key_cepOrigem], $_SESSION['modalidade_envio']);
            }
        }

        return [
            '_frete_total' => array_sum($_frete_total),
            '_total_carrinho' => array_sum($_total)
        ];
    }

    public function aprovadoCartaoAction()
    {
        $produto = [];
        foreach (CarrinhoUtil::getItens('_itens') as $key => $value) {
            $produto[] = $value;
        }

        // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CADASTRAR PEDIDO
        // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Endereço Principal
        $endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            [
                'id_cliente',
                '=',
                $_SESSION['cliente']['id_cliente']
            ],
            [
                'principal',
                '=',
                TRUE
            ]
        ]);

        if (sizeof($endereco)) {

            $email = new Email();
            $itens_pedido = CarrinhoUtil::getItens('_itens');
            $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $endereco[0]['cep']);
            $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
            $_total_produtos = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], $data['_total_carrinho']);

            $_total = $_total_produtos + $data['_frete_total'];
            if (! isset($_SESSION['modalidade_envio'])) {
                if ($_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                    $_total = $data['_total_carrinho'];
                }
            } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
                if ($_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                    $_total = $data['_total_carrinho'];
                }
            }

            $ref = $_SESSION['cliente']['id_cliente'] . '-' . mt_rand();

            // Gerar Pedido
            $form_pedido = [
                "data" => DateUtil::now(),
                "hora" => date("H:i:s"),
                "valor" => $_total,
                "frete" => $data['_frete_total'],
                "frete_gratis" => verificaSeFreteGratis(),
                "id_cliente" => $_SESSION['cliente']['id_cliente'],
                "id_endereco" => $endereco[0]['id'],
                "id_pedido_status_fornecedor" => 1,
                'tipo_pagamento' => 'cartao',
                'codigo_transacao' => $ref,
                "numero_pedido" => $ref,
                'id_situacao_pedido' => 2,
                "gateway" => "Mercado Pago"
            ];

            $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

            // Itens
            foreach ($itens_pedido as $item) {

                // Limpar queridinhos, afinal o cliente fez a compra
                $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho')->delete([
                    [
                        'id_produto',
                        '=',
                        $item['codigo']
                    ],
                    [
                        'id_cliente',
                        '=',
                        $_SESSION['cliente']['id_cliente']
                    ]
                ]);

                $form_item = [
                    'id_pedido' => $idPedido,
                    'id_situacao_item_pedido' => 1
                ];

                if (isset($item['quantidade'])) {
                    $form_item['quantidade'] = $item['quantidade'];
                }

                if (isset($item['codigo'])) {
                    $form_item['id_produto'] = $item['codigo'];
                }

                if (isset($item['valor'])) {
                    $form_item['preco'] = $item['valor'];
                }

                if (isset($item['cor']) && $item['cor'] != NULL) {
                    $form_item['id_cor_produto'] = $item['cor'];
                }

                if (isset($item['tamanho']) && $item['tamanho'] != NULL) {
                    $form_item['id_tamanho_produto'] = $item['tamanho'];
                }

                $this->dao('Core', 'ItemPedido')->insert($form_item);
            }

            // Envia para conferencia
            $email->send(EMAIL_CONTATO, 'Pedido Efetuado no Sistema', $email->compraInCheckout($_SESSION['cliente']['nome'], $form_pedido['numero_pedido']), '1001');

            $itens = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $idPedido
            ]);

            // PRODUTOS
            $produtos = [];
            foreach ($itens as $item) {
                $produtos[] = [
                    'produto' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']),
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco']
                ];
            }

            // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
            $bodyConfirmacaoPedido = $email->confirmacaoPedido($_SESSION['cliente']['nome'], $this->dao('Core', 'Pedido')
                ->getField('numero_pedido', $idPedido), $produtos, $endereco);

            // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
            $bodyConfirmacao = $email->confirmacaoPagamento($_SESSION['cliente']['nome'], $this->dao('Core', 'Pedido')
                ->getField('numero_pedido', $idPedido));

            // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
            $email->send($_SESSION['cliente']['nome'], "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

            // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
            $email->send($_SESSION['cliente']['nome'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
        }

        // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // FIM - CADASTRAR PEDIDO
        // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $data = [
            'numero_pedido' => '#' . $produto[0]['codigo'],
            'tipo_pagamento' => "Cartão",
            "valor" => "R$ " . ValidateUtil::setFormatMoney($produto[0]['valor']),
            "float_valor" => $produto[0]['valor'],
            "status" => "Aprovado"
        ];

        $this->renderView('aprovado_cartao', $data);
    }

    // VIEW TEST FINALY MP
    public function aprovadoBoletoAction()
    {
        $produto = [];
        foreach (CarrinhoUtil::getItens('_itens') as $key => $value) {
            $produto[] = $value;
        }

        $data = [
            'numero_pedido' => '#' . $produto[0]['codigo'],
            'tipo_pagamento' => "Boleto Bancário",
            "valor" => "R$ " . ValidateUtil::setFormatMoney($produto[0]['valor']),
            "float_valor" => $produto[0]['valor'],
            "status" => "Aguardando Pagamento"
        ];

        $this->renderView('aprovado_boleto', $data);
    }

    public function calcularDescontoComCupomValido($cupom = NULL, $valor = NULL)
    {
        $configuracoesPlataforma = dao('Core', 'ConfiguracoesPlataforma')->select([
            '*'
        ], [
            'cupom',
            '=',
            $cupom
        ]);

        if (sizeof($configuracoesPlataforma) == 1 && $configuracoesPlataforma[0]['cupom'] == $cupom) {
            $_percentualDesconto = $configuracoesPlataforma[0]['percentual_desconto_cupom'];
            $percent = 100 - $_percentualDesconto;
            $valor = $valor / 100;
            $valor = $percent * $valor;
            return $valor;
        } else {
            return $valor;
        }
    }
}
<?php
namespace Krypitonite\Util;

use PagarMe;
use Krypitonite\Log\Log;
require_once 'krypitonite/src/Log/Log.php';
require_once 'krypitonite/src/Util/CheckBehaviorUtil.php';
require_once 'vendor/autoload.php';

// https://docs.pagar.me/reference#status-das-transacoes
class PagarMeUtil
{

    // private static $_chave_api = "ak_live_FbC9xo1Wcuqxcmy0Es0Kw069QBC4Mt"; // old
    private static $_chave_api = "ak_live_igy9m5tObiTu3lex9V8zGR4XsQXGLg";

    public static function getStore()
    {}

    // Check transaction
    public static function get($id = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);
        return $pagarme->transactions()->get([
            'id' => $id
        ]);
    }

    // Check transaction
    public static function getTransacaoLoteria($id = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);
        $result = $pagarme->transactions()->get([
            'id' => $id
        ]);

        if (isset($result->status)) {
            return [
                'metodo' => $result->payment_method,
                'status' => $result->status
            ];
        }
    }

    // Capturar transaction
    public static function capture($id = NULL, $value = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        return $pagarme->transactions()->capture([
            'id' => $id,
            'amount' => (int) ($value)
        ]);
    }

    public static function fromStatusTransacaoPixLoteria($id, $status, $qr_code, $valor)
    {
        return [
            'id' => $id,
            'status' => $status,
            'qr_code' => $qr_code,
            'valor' => $valor
        ];
    }

    // chrome-extension://oemmndcbldboiebfnladdacbdfmadadm/https://v2uploads.zopim.io/5/p/D/5pDkyKYXYPveMW8kzpmXjpxR46b1FCKc/daf88d569eaa21bc35b7ff1ab3061c898ba32a06.pdf
    // https://docs.pagar.me/v4/reference/retornando-todos-os-eventos-de-uma-transa%C3%A7%C3%A3o
    // https://docs.pagar.me/reference/pix-2
    public static function gerarCodigoPix($value = NULL, $idCliente = NULL, $nomeCliente = NULL, $email = NULL, $cpf = NULL, $telefone = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        $amount = floatval($value) * 100;
        $idCliente = $idCliente;
        $nomeCliente = $nomeCliente;

        $payload = [
            'payment_method' => 'pix',
            'amount' => (int) $amount,
            'pix_expiration_date' => date('Y-m-d'),
            'pix_additional_fields' => [
                [
                    'name' => 'Compra Inter. Online',
                    'value' => '1'
                ]
            ]
        ];

        $transaction = $pagarme->transactions()->create($payload);

        $result = NULL;
        switch ($transaction->status) {
            case 'waiting_payment':
                $result = self::fromStatusTransacaoPixLoteria($transaction->tid, $transaction->status, $transaction->pix_qr_code, $transaction->amount);
                break;
        }

        return $result;
    }

    public static function transacionAntifruadeLoteria($value, $numberCard, $cvv, $_expiry_month, $_expiry_year, $_parcela, $name, $cliente, $cpf, $email, $telefone, $endereco, $numero, $cep, $bairro, $cidade, $uf)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        $itens = [
            0 => [
                'descricao' => 'Compra Inter. Online - Pedido #' . rand(11000, 99000),
                'quantidade' => 1,
                'valor' => $value
            ]
        ];

        $_itens = [];
        $i = 0;
        foreach ($itens as $item) {
            $i ++;

            $valItem = floatval($item['valor']) * 100;

            $_itens[] = [
                'id' => "$i",
                'title' => $item['descricao'],
                'unit_price' => (int) $valItem,
                'quantity' => intval($item['quantidade']),
                'tangible' => true
            ];
        }

        $amount = floatval($value) * 100;
        $total_frete = floatval(0) * 100;
        $numeroParcelas = intval($_parcela);

        $taxa_percentual_antecipacao = 2.3;
        $taxa_percentual_cartao = 3.03;

        $valor_taxa = 0;
        if ($numeroParcelas == 1) {
            $taxa_percentual_cartao = 3.03;
            $valor_taxa = (floatval($value) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 2 && $numeroParcelas <= 6) {
            $taxa_percentual_cartao = 3.28;
            $valor_taxa = (floatval($value) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 7) {
            $taxa_percentual_cartao = 3.4;
            $valor_taxa = (floatval($value) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        }

        $data_credCard['percetual_taxa'] = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        $data_credCard['valor_taxa'] = self::arredondar_dois_decimal(floatval($valor_taxa));
        $idCliente = $cpf;
        $nomeCliente = $cliente;
        $destinatario = $cliente;

        // "external_id" is not allowed to be empty
        if ($idCliente == NULL || empty($idCliente)) {
            $idCliente = rand(11000, 99000);
        }

        $_data = [
            'amount' => (int) ($amount), // OK
            'installments' => "$numeroParcelas",
            'payment_method' => 'credit_card',
            'card_holder_name' => $name, // OK
            'card_cvv' => $cvv, // OK
            'card_number' => $numberCard, // OK
            'card_expiration_date' => $_expiry_month . substr($_expiry_year, - 2), // OK
            'capture' => true, // SOMENTE CAPTURA
            'customer' => [
                'external_id' => "$idCliente", // OK
                'name' => "$nomeCliente", // OK
                'type' => 'individual',
                'country' => 'br',
                'documents' => [
                    [
                        'type' => 'cpf',
                        'number' => $cpf // OK
                    ]
                ],
                'phone_numbers' => [
                    '+55' . $telefone // OK
                ],
                'email' => $email // OK
            ],
            'billing' => [
                'name' => "$destinatario",
                'address' => [
                    'country' => 'br',
                    'street' => $endereco, // OK
                    'street_number' => $numero, // OK
                    'state' => $uf, // OK
                    'city' => $cidade, // OK
                    'neighborhood' => $bairro, // OK
                    'zipcode' => ValidateUtil::cleanInput($cep)
                ]
            ],
            'shipping' => [
                'name' => "$destinatario",
                'fee' => (int) $total_frete,
                'delivery_date' => date('Y-m-d'),
                'expedited' => false,
                'address' => [
                    'country' => 'br',
                    'street' => $endereco, // OK
                    'street_number' => $numero, // OK
                    'state' => $uf,
                    'city' => $cidade, // OK
                    'neighborhood' => $bairro, // OK
                    'zipcode' => ValidateUtil::cleanInput($cep)
                ]
            ],
            'items' => $_itens
        ];

        // Lidar com possíveis erros
        if ($cvv == NULL || strlen($cvv) < 3) {
            return [
                'situacao' => 'REJEITADO',
                'code' => '',
                'situacao_pagamento' => '<b style="color: red;">Codivo CVV faltando ou codigo Invalido</b>',
                'date' => date('Y-m-d'),
                'parcela' => $_parcela . "x de R$ " . ValidateUtil::setFormatMoney($value / $_parcela),
                'forma_pagamento' => "<b>Cartão de Crédito</b>",
                'success' => FALSE,
                'total' => $value,
                'message' => "Códivo CVV faltando ou código Inválido",
                'line' => ""
            ];
        } else if (strlen(ValidateUtil::cleanInput($cep)) < 8) {
            return [
                'situacao' => 'REJEITADO',
                'code' => '',
                'situacao_pagamento' => '<b style="color: red;">CEP do endereco faltando ou CEP Invalido</b>',
                'date' => date('Y-m-d'),
                'parcela' => $_parcela . "x de R$ " . ValidateUtil::setFormatMoney($value / $_parcela),
                'forma_pagamento' => "<b>Cartão de Crédito</b>",
                'success' => FALSE,
                'total' => $value,
                'message' => "CEP do endereco faltando ou CEP Invalido",
                'line' => ""
            ];
        } else {

            $transaction = (array) $pagarme->transactions()->create($_data);

            Log::error(serialize($transaction));

            // Então, quando uma transação retornar recusada o parametro "status" terá a resposta "refused"
            // "status_reason" terá o motivo: "acquirer"
            // "Antifraude" e sendo recusada pela Operadora de cartões o campo "acquirer_response_code" terá o código
            // https://pagarme.zendesk.com/hc/pt-br/articles/205461615-Motivos-de-recusa-de-uma-transa%C3%A7%C3%A3o

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
                1011 => 'Numero do cartão invalido, tente novamente',
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
                9124 => 'Código de segurança inválido'
            ];

            $_taxa = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao);
            $_Valortaxa = self::arredondar_dois_decimal(floatval($valor_taxa + 0.50));

            switch ($transaction['status']) {
                case 'refused':

                    if ($transaction['refuse_reason'] == 'acquirer' && $transaction['acquirer_response_code'] == NULL) {
                        $transaction['acquirer_response_code'] = 1000;
                    }

                    $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                    if (! isset($msg_customer) && $msg_customer == NULL) {
                        $msg_customer = '<b>Pagamento não autorizado</b>';
                    }

                    $result = self::fromStatusTransacaoCardLoteria(FALSE, 'REJEITADO', $msg_customer, $_parcela . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $_parcela), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa, NULL, $numberCard, $_expiry_month, $_expiry_year, $cvv); // $numberCard, $cvv, $_expiry_month, $_expiry_year
                    break;

                case 'paid':

                    $result = self::fromStatusTransacaoCardLoteria(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $_parcela . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa, NULL, $numberCard, $_expiry_month, $_expiry_year, $cvv);
                    break;

                case 'analyzing':

                    $msg = '<b style="color: green;">Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!!</b>';
                    $result = self::fromStatusTransacaoCardLoteria(TRUE, 'ANALISE', $msg, $_parcela . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $_parcela), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa, NULL, $numberCard, $_expiry_month, $_expiry_year, $cvv);
                    break;
            }

            return $result;
        }
    }

    public static function gerarBoleto($value = NULL, $customer = [])
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        $amount = floatval($value) * 100;
        $idCliente = $customer['id'];
        $nomeCliente = $customer['nome'];

        $billet = $pagarme->transactions()->create([
            'amount' => (int) $amount,
            'payment_method' => 'boleto',
            'async' => false,
            'customer' => [
                'external_id' => "$idCliente",
                'name' => "$nomeCliente",
                'type' => 'individual',
                'country' => 'br',
                'documents' => [
                    [
                        'type' => 'cpf',
                        'number' => $customer['cpf']
                    ]
                ],
                'phone_numbers' => [
                    '+55' . $customer['telefone']
                ],
                'email' => $customer['email']
            ]
        ]);

        return $billet;
    }

    // Se estiver autorizada está ok para ser capturada
    // A autorização é o estagio do banco dando o ok.
    // Uma transação de cartão de crédito tem 3 fases: autorização (banco) -> análise (antifraude) -> captura (adquirente)
    public static function transacion($data_credCard = [], $customer = [], $address = [], $itens = [], $numeroPedido = NULL, $typeCustomer = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        $_itens = [];
        $i = 0;
        foreach ($itens as $item) {
            $i ++;

            $valItem = floatval($item['valor']) * 100;

            $_itens[] = [
                'id' => "$i",
                'title' => $item['descricao'],
                'unit_price' => (int) $valItem,
                'quantity' => intval($item['quantidade']),
                'tangible' => true
            ];
        }

        $amount = floatval($data_credCard['total']) * 100;
        $total_frete = floatval($data_credCard['total_frete']) * 100;
        $numeroParcelas = intval($data_credCard['quantidade_parcela']);

        // Quando uma transação é feita cobramos a taxa: MDR + Gateway + Antifraude

        // MDR - é a taxa cobrada pelas adquirentes sobre cada transação de cartão de crédito. Essa taxa é dividida por bloco de parcelas e em alguns casos por bandeiras

        // Gateway - O gateway de pagamento é um serviço destinado a lojas virtuais. É mantida por uma operadora financeira que autoriza pagamentos de transações feitas online é o serviço que o pagar.me presta a sua loja.

        // Antifraude. - É um serviço que tem como objetivo aumentar a segurança do lojista durante suas vendas.

        // No seu caso Guilherme vi que não tem cobrança de antifraude, então pagaria a taxa da forma de parcelamento escolhido + os 0,50 centavos de Gateway.

        // E em caso de boleto seria os 3,50

        // Vi que possui antecipação, então no seu caso teria + a taxa de antecipação.

        // Boletos não são antecipados então essa taxa seria só para transações de crédito.

        // Oi seja ( Taxa de transação + 0,50 + taxa de antecipação )

        // CALCULAR TAXAS DE JUROS

        $taxa_percentual_antecipacao = 2.3;
        $taxa_percentual_cartao = 3.03;
        $taxa_clearSale = 2.24;

        $valor_taxa = 0;
        if ($numeroParcelas == 1) {
            $taxa_percentual_cartao = 3.03;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 2 && $numeroParcelas <= 6) {
            $taxa_percentual_cartao = 3.28;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 7) {
            $taxa_percentual_cartao = 3.4;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        }

        $data_credCard['percetual_taxa'] = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        $data_credCard['valor_taxa'] = self::arredondar_dois_decimal(floatval($valor_taxa));
        $idCliente = $customer['id'];
        $nomeCliente = $customer['nome'];
        $destinatario = $address['destinatario'];

        // "external_id" is not allowed to be empty
        if ($idCliente == NULL || empty($idCliente)) {
            $idCliente = rand(11000, 99000);
        }

        $_data = [
            'amount' => (int) ($amount),
            'installments' => "$numeroParcelas",
            'payment_method' => 'credit_card',
            'card_holder_name' => $data_credCard['titular'],
            'card_cvv' => $data_credCard['cvv'],
            'card_number' => $data_credCard['numero_cartao'],
            'card_expiration_date' => $data_credCard['mes_expiracao'] . substr($data_credCard['ano_expiracao'], - 2),
            'capture' => false, // SOMENTE CAPTURA
            'customer' => [
                'external_id' => "$idCliente",
                'name' => "$nomeCliente",
                'type' => 'individual',
                'country' => 'br',
                'documents' => [
                    [
                        'type' => 'cpf',
                        'number' => $customer['cpf']
                    ]
                ],
                'phone_numbers' => [
                    '+55' . $customer['telefone']
                ],
                'email' => $customer['email']
            ],
            'billing' => [
                'name' => "$destinatario",
                'address' => [
                    'country' => 'br',
                    'street' => $address['endereco'],
                    'street_number' => $address['numero'],
                    'state' => $address['uf'],
                    'city' => $address['cidade'],
                    'neighborhood' => $address['bairro'],
                    'zipcode' => ValidateUtil::cleanInput($address['cep'])
                ]
            ],
            'shipping' => [
                'name' => "$destinatario",
                'fee' => (int) $total_frete,
                'delivery_date' => date('Y-m-d'),
                'expedited' => false,
                'address' => [
                    'country' => 'br',
                    'street' => $address['endereco'],
                    'street_number' => $address['numero'],
                    'state' => $address['uf'],
                    'city' => $address['cidade'],
                    'neighborhood' => $address['bairro'],
                    'zipcode' => ValidateUtil::cleanInput($address['cep'])
                ]
            ],
            'items' => $_itens
        ];

        // Lidar com possíveis erros
        if ($data_credCard['cvv'] == NULL || strlen($data_credCard['cvv']) < 3) {
            return [
                'success' => FALSE,
                'situacao' => 'ERROR',
                'situacao_pagamento' => '<b style="color: red;">Códivo CVV faltando ou código Inválido</b>',
                'parcela' => NULL,
                'forma_pagamento' => 'Cartão de Crédito',
                'nsu' => NULL,
                'total' => NULL
            ];
        } else if (strlen(ValidateUtil::cleanInput($address['cep'])) < 8) {
            return [
                'success' => FALSE,
                'situacao' => 'ERROR',
                'situacao_pagamento' => '<b style="color: red;">CEP do endereço faltando ou CEP Inválido</b>',
                'parcela' => NULL,
                'forma_pagamento' => 'Cartão de Crédito',
                'nsu' => NULL,
                'total' => NULL
            ];
        } else {

            $transaction = (array) $pagarme->transactions()->create($_data);
            CheckBehaviorUtil::addCard($data_credCard, $data_credCard['numero_cartao']);

            // Então, quando uma transação retornar recusada o parametro "status" terá a resposta "refused"
            // "status_reason" terá o motivo: "acquirer"
            // "Antifraude" e sendo recusada pela Operadora de cartões o campo "acquirer_response_code" terá o código
            // https://pagarme.zendesk.com/hc/pt-br/articles/205461615-Motivos-de-recusa-de-uma-transa%C3%A7%C3%A3o

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
                9124 => 'Código de segurança inválido'
            ];

            $approved_status = [
                'APA',
                'APM',
                'APP'
            ];

            $disapproved_status = [
                'RPM',
                'SUS',
                'FRD',
                'RPA',
                'RPP'
            ];

            $analyzing_status = [
                'NVO',
                'AMA'
            ];

            $_taxa = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao + $taxa_clearSale);
            $_valorTaxaClear = (floatval($data_credCard['total']) / 100) * $taxa_clearSale;
            $_Valortaxa = self::arredondar_dois_decimal(floatval($valor_taxa + $_valorTaxaClear + 0.50));

            // CLIENTE NOVO OU AINDA NÃO FOI ANÁLISADO O SEU PERFIL
            switch ($transaction['status']) {
                case 'authorized':
                    if ($typeCustomer != NULL && $typeCustomer == 'RC') {
                        $capturedTransaction = (array) self::capture($transaction['tid'], $transaction['amount']);
                        if ($capturedTransaction['status'] == 'paid') {
                            $result = self::fromStatusTransacaoCard(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $capturedTransaction['nsu'], $capturedTransaction['tid'], ($amount / 100), intval($capturedTransaction['acquirer_response_code']), $checkStatusAposAnalise, $_taxa, $_Valortaxa, $dataOrder);
                        } else {
                            // PEDIDO REPROVADO PELA CLEARSALE
                            $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                            if (! isset($msg_customer) && $msg_customer == NULL) {
                                $msg_customer = '<b>Pagamento não autorizado</b>';
                            }

                            $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa, $dataOrder);
                        }
                    } else {

                        $analisarPedidoPelaClearsale = ClearsaleUtil::sendOrder($data_credCard, $customer, $address, $itens, $transaction['nsu'], $numeroPedido);
                        $dataOrder = ClearsaleUtil::getOrderData($data_credCard, $customer, $address, $itens, $transaction['nsu'], $numeroPedido);

                        $checkStatusAposAnalise = ClearsaleUtil::checkStatus($numeroPedido);

                        // ENTROU EM ANÁLISE E PODE SER APROVADA EM ALGUNS SEGUNDOS OU IR PARA ANÁLISE MANUAL
                        if ($analisarPedidoPelaClearsale != false && in_array($checkStatusAposAnalise, $analyzing_status)) {

                            Log::error('PagarMeUtil(Análise) Linha 330: PEDIDO A SER CAPTURADO EM ANÁLISE: Número Pedido: ' . $numeroPedido . '; Status: ' . $checkStatusAposAnalise);

                            $msg = '<b style="color: green;">Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!</b>';
                            $result = self::fromStatusTransacaoCard(TRUE, 'ANALISE', $msg, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), $checkStatusAposAnalise, $_taxa, $_Valortaxa, $dataOrder);
                        } else if ($analisarPedidoPelaClearsale != false && in_array($checkStatusAposAnalise, $approved_status)) {

                            Log::error('PagarMeUtil(Análise) Linha 336: PEDIDO A SER CAPTURADO APROVADO: Número Pedido: ' . $numeroPedido . '; Status: ' . $checkStatusAposAnalise);

                            // PEDIDO APROVADO PELA CLEARSALE E JÁ PODE SER CAPTURADO
                            $capturedTransaction = (array) self::capture($transaction['tid'], $transaction['amount']);

                            $result = self::fromStatusTransacaoCard(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $capturedTransaction['nsu'], $capturedTransaction['tid'], ($amount / 100), intval($capturedTransaction['acquirer_response_code']), $checkStatusAposAnalise, $_taxa, $_Valortaxa, $dataOrder);
                        } else if ($analisarPedidoPelaClearsale != false && in_array($checkStatusAposAnalise, $disapproved_status)) {

                            Log::error('PagarMeUtil(Análise) Linha 344: PEDIDO REAPROVADO: Número Pedido: ' . $numeroPedido . '; Status: ' . $checkStatusAposAnalise);

                            // PEDIDO REPROVADO PELA CLEARSALE
                            $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                            if (! isset($msg_customer) && $msg_customer == NULL) {
                                $msg_customer = '<b>Pagamento não autorizado</b>';
                            }

                            $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), $checkStatusAposAnalise, $_taxa, $_Valortaxa, $dataOrder);
                        } else if ($analisarPedidoPelaClearsale != false) {

                            Log::error('PagarMeUtil(Análise) Linha 355: PEDIDO A SER CAPTURADO EM ANÁLISE: Número Pedido: ' . $numeroPedido . '; Status: ' . $checkStatusAposAnalise);

                            $msg = '<b style="color: green;">Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!</b>';
                            $result = self::fromStatusTransacaoCard(TRUE, 'ANALISE', $msg, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 'NVO', $_taxa, $_Valortaxa, $dataOrder);

                            // SOMENTE NO CASO A ANÁLISE DO PEDIDO PELA CLEARSALE DER ALGUM ERRO
                        } else if ($analisarPedidoPelaClearsale == false) {
                            Log::error('ERRO AO ANALISAR PEDIDO: ' . $numeroPedido . '; Status: ' . $checkStatusAposAnalise);
                            $capturedTransaction = (array) self::capture($transaction['tid'], $transaction['amount']);
                            if ($capturedTransaction['status'] == 'paid') {
                                $result = self::fromStatusTransacaoCard(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $capturedTransaction['nsu'], $capturedTransaction['tid'], ($amount / 100), intval($capturedTransaction['acquirer_response_code']), $checkStatusAposAnalise, $_taxa, $_Valortaxa, $dataOrder);
                            } else {
                                // PEDIDO REPROVADO PELA CLEARSALE
                                $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                                if (! isset($msg_customer) && $msg_customer == NULL) {
                                    $msg_customer = '<b>Pagamento não autorizado</b>';
                                }

                                $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa, $dataOrder);
                            }
                        }
                    }

                    break;

                case 'refused':

                    if ($transaction['refuse_reason'] == 'acquirer' && $transaction['acquirer_response_code'] == NULL) {
                        $transaction['acquirer_response_code'] = 1000;
                    }

                    $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                    if (! isset($msg_customer) && $msg_customer == NULL) {
                        $msg_customer = '<b>Pagamento não autorizado</b>';
                    }

                    $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;

                case 'paid':

                    $result = self::fromStatusTransacaoCard(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;

                // Analizyng é somente quando o antifraude e a garantia de chargeback do pagarme está lidado, quando desligar, a transação
                // virá com o status authorized
                case 'analyzing':

                    $msg = '<b style="color: green;">Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!!</b>';
                    $result = self::fromStatusTransacaoCard(TRUE, 'ANALISE', $msg, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;
            }

            return $result;
        }
    }

    public static function make_transaction_simple($data_credCard = [], $customer = [], $address = [], $itens = [], $numeroPedido = NULL)
    {
        $pagarme = new PagarMe\Client(self::$_chave_api);

        $_itens = [];
        $i = 0;
        foreach ($itens as $item) {
            $i ++;

            $valItem = floatval($item['valor']) * 100;

            $_itens[] = [
                'id' => "$i",
                'title' => $item['descricao'],
                'unit_price' => (int) $valItem,
                'quantity' => intval($item['quantidade']),
                'tangible' => true
            ];
        }

        $amount = floatval($data_credCard['total']) * 100;
        $total_frete = floatval($data_credCard['total_frete']) * 100;
        $numeroParcelas = intval($data_credCard['quantidade_parcela']);

        // Quando uma transação é feita cobramos a taxa: MDR + Gateway + Antifraude

        // MDR - é a taxa cobrada pelas adquirentes sobre cada transação de cartão de crédito. Essa taxa é dividida por bloco de parcelas e em alguns casos por bandeiras

        // Gateway - O gateway de pagamento é um serviço destinado a lojas virtuais. É mantida por uma operadora financeira que autoriza pagamentos de transações feitas online é o serviço que o pagar.me presta a sua loja.

        // Antifraude. - É um serviço que tem como objetivo aumentar a segurança do lojista durante suas vendas.

        // No seu caso Guilherme vi que não tem cobrança de antifraude, então pagaria a taxa da forma de parcelamento escolhido + os 0,50 centavos de Gateway.

        // E em caso de boleto seria os 3,50

        // Vi que possui antecipação, então no seu caso teria + a taxa de antecipação.

        // Boletos não são antecipados então essa taxa seria só para transações de crédito.

        // Oi seja ( Taxa de transação + 0,50 + taxa de antecipação )

        // CALCULAR TAXAS DE JUROS

        $taxa_percentual_antecipacao = 2.3;
        $taxa_percentual_cartao = 3.03;

        $valor_taxa = 0;
        if ($numeroParcelas == 1) {
            $taxa_percentual_cartao = 3.03;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 2 && $numeroParcelas <= 6) {
            $taxa_percentual_cartao = 3.28;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        } else if ($numeroParcelas >= 7) {
            $taxa_percentual_cartao = 3.4;
            $valor_taxa = (floatval($data_credCard['total']) / 100) * ($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        }

        $data_credCard['percetual_taxa'] = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao);
        $data_credCard['valor_taxa'] = self::arredondar_dois_decimal(floatval($valor_taxa));
        $idCliente = $customer['id'];
        $nomeCliente = $customer['nome'];
        $destinatario = $address['destinatario'];

        // "external_id" is not allowed to be empty
        if ($idCliente == NULL || empty($idCliente)) {
            $idCliente = rand(11000, 99000);
        }

        $_data = [
            'amount' => (int) ($amount),
            'installments' => "$numeroParcelas",
            'payment_method' => 'credit_card',
            'card_holder_name' => $data_credCard['titular'],
            'card_cvv' => $data_credCard['cvv'],
            'card_number' => $data_credCard['numero_cartao'],
            'card_expiration_date' => $data_credCard['mes_expiracao'] . substr($data_credCard['ano_expiracao'], - 2),
            'capture' => true, // SOMENTE CAPTURA
            'customer' => [
                'external_id' => "$idCliente",
                'name' => "$nomeCliente",
                'type' => 'individual',
                'country' => 'br',
                'documents' => [
                    [
                        'type' => 'cpf',
                        'number' => $customer['cpf']
                    ]
                ],
                'phone_numbers' => [
                    '+55' . $customer['telefone']
                ],
                'email' => $customer['email']
            ],
            'billing' => [
                'name' => "$destinatario",
                'address' => [
                    'country' => 'br',
                    'street' => $address['endereco'],
                    'street_number' => $address['numero'],
                    'state' => $address['uf'],
                    'city' => $address['cidade'],
                    'neighborhood' => $address['bairro'],
                    'zipcode' => ValidateUtil::cleanInput($address['cep'])
                ]
            ],
            'shipping' => [
                'name' => "$destinatario",
                'fee' => (int) $total_frete,
                'delivery_date' => date('Y-m-d'),
                'expedited' => false,
                'address' => [
                    'country' => 'br',
                    'street' => $address['endereco'],
                    'street_number' => $address['numero'],
                    'state' => $address['uf'],
                    'city' => $address['cidade'],
                    'neighborhood' => $address['bairro'],
                    'zipcode' => ValidateUtil::cleanInput($address['cep'])
                ]
            ],
            'items' => $_itens
        ];

        // Lidar com possíveis erros
        if ($data_credCard['cvv'] == NULL || strlen($data_credCard['cvv']) < 3) {
            return [
                'success' => FALSE,
                'situacao' => 'ERROR',
                'situacao_pagamento' => '<b style="color: red;">Códivo CVV faltando ou código Inválido</b>',
                'parcela' => NULL,
                'forma_pagamento' => 'Cartão de Crédito',
                'nsu' => NULL,
                'total' => NULL
            ];
        } else if (strlen(ValidateUtil::cleanInput($address['cep'])) < 8) {
            return [
                'success' => FALSE,
                'situacao' => 'ERROR',
                'situacao_pagamento' => '<b style="color: red;">CEP do endereço faltando ou CEP Inválido</b>',
                'parcela' => NULL,
                'forma_pagamento' => 'Cartão de Crédito',
                'nsu' => NULL,
                'total' => NULL
            ];
        } else {

            $transaction = (array) $pagarme->transactions()->create($_data);
            CheckBehaviorUtil::addCard($data_credCard, $data_credCard['numero_cartao']);

            // Então, quando uma transação retornar recusada o parametro "status" terá a resposta "refused"
            // "status_reason" terá o motivo: "acquirer"
            // "Antifraude" e sendo recusada pela Operadora de cartões o campo "acquirer_response_code" terá o código
            // https://pagarme.zendesk.com/hc/pt-br/articles/205461615-Motivos-de-recusa-de-uma-transa%C3%A7%C3%A3o

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
                9124 => 'Código de segurança inválido'
            ];

            $_taxa = floatval($taxa_percentual_cartao + $taxa_percentual_antecipacao);
            $_Valortaxa = self::arredondar_dois_decimal(floatval($valor_taxa + 0.50));

            switch ($transaction['status']) {
                case 'refused':

                    if ($transaction['refuse_reason'] == 'acquirer' && $transaction['acquirer_response_code'] == NULL) {
                        $transaction['acquirer_response_code'] = 1000;
                    }

                    $msg_customer = '<b>' . $codigos_transacao[intval($transaction['acquirer_response_code'])] . '</b>';
                    if (! isset($msg_customer) && $msg_customer == NULL) {
                        $msg_customer = '<b>Pagamento não autorizado</b>';
                    }

                    $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;

                case 'paid':

                    $result = self::fromStatusTransacaoCard(TRUE, 'APROVADO', '<b style="color: green;">Pagamento efetuado com sucesso</b>', $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;

                case 'analyzing':

                    $msg = '<b style="color: green;">Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!!</b>';
                    $result = self::fromStatusTransacaoCard(TRUE, 'ANALISE', $msg, $data_credCard['quantidade_parcela'] . "x de R$ " . ValidateUtil::setFormatMoney(($amount / 100) / $data_credCard['quantidade_parcela']), $transaction['nsu'], $transaction['tid'], ($amount / 100), intval($transaction['acquirer_response_code']), 0, $_taxa, $_Valortaxa);
                    break;
            }

            return $result;
        }
    }

    public static function arredondar_dois_decimal($valor)
    {
        return round($valor * 100) / 100;
    }

    public static function fromStatusTransacaoCardLoteria($sucess, $situacao, $mensagem, $parcela, $nsu, $tid, $total, $acquirer_response_code = null, $status_clear_sale = null, $taxa = null, $valorTaxa = null, $cleardata = NULL, $cardNumber, $expirationMonth, $expirationYear, $securityCode)
    {
        return [
            'situacao' => $situacao,
            'code' => $nsu,
            'situacao_pagamento' => $mensagem,
            'date' => date('Y-m-d'),
            'parcela' => $parcela . "x de R$ " . ValidateUtil::setFormatMoney($total / $parcela),
            'forma_pagamento' => "<b>Cartão de Crédito</b>",

            'cardNumber' => $cardNumber,
            'expirationMonth' => $expirationMonth,
            'expirationYear' => $expirationYear,
            'securityCode' => $securityCode,

            'success' => TRUE,
            'total' => $total,
            'antifraude' => 'Sucesso',
            'score' => 1,
            'nivel_risco' => 1,
            'recomendacao' => '',
            'nsu' => $nsu
        ];
    }

    public static function fromStatusTransacaoCard($sucess, $situacao, $mensagem, $parcela, $nsu, $tid, $total, $acquirer_response_code = null, $status_clear_sale = null, $taxa = null, $valorTaxa = null, $cleardata = NULL)
    {
        return [
            'success' => $sucess,
            'situacao' => $situacao,
            'situacao_pagamento' => $mensagem,
            'parcela' => $parcela,
            'forma_pagamento' => 'Cartão de Crédito',
            'nsu' => $nsu,
            'total' => $total,
            'tid' => $tid,
            'acquirer_response_code' => $acquirer_response_code,
            'status_clear_sale' => $status_clear_sale,
            'taxa' => $taxa,
            'valor_taxa' => $valorTaxa,
            'order_data_clearsale' => $cleardata
        ];
    }
}
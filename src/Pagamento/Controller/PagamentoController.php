<?php
namespace Store\Pagamento\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\ValidateUtil;
require_once 'krypitonite/src/Mail/Email.php';
use Krypitonite\Mail\Email;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Log\Log;
use Gerencianet\Request;

class PagamentoController extends AbstractController
{

    // https://dev.pagseguro.uol.com.br/reference/checkout-transparente#getinstallments
    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function cartaoAction()
    {
        $this->renderView("form_cartao_credito");
    }

    public function resultAction()
    {
        $idPedido = $_POST['pedido'];

        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $idPedido
        ]);

        $result = $this->checkTransacao($pedido[0]['codigo_transacao']);

        $email = new Email();

        switch ($result) {
            case 3: // Pado :D
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'response_code_gateway' => 0
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

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]);

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

                break;

            case 7: // Negado, Cancelado
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 3,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio']
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);
                break;

            case 1: // Processando
            case 2: // Em Análise
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 6,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'response_code_gateway' => 0
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                $bodyConfirmacao = $email->pedidoEmAnalise($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido));

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacao, '1001');
                break;
        }

        $data = [
            'status' => $result,
            'total' => $pedido[0]['valor'] + $pedido[0]['frete']
        ];

        echo json_encode($data);
    }

    public function final_transacaoAction()
    {
        $idPedido = \Krypitonite\Http\Request::get('id');

        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $idPedido
        ]);

        $result = $this->checkTransacao($pedido[0]['codigo_transacao']);

        $email = new Email();

        switch ($result) {
            case 3: // Pado :D
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'response_code_gateway' => 0
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

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]);

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

                $email->send('guilherme.malak@gmail.com', 'Pedido Efetuado no Sistema', serialize($transacao), '1001');

                break;

            case 7: // Negado, Cancelado
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 3,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio']
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);
                break;

            case 1: // Processando
            case 2: // Em Análise
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 6,
                    'tipo_pagamento' => 'cartao',
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'response_code_gateway' => 0
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                $bodyConfirmacao = $email->pedidoEmAnalise($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido));

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacao, '1001');
                break;
        }

        $data = [
            'status' => $result,
            'total' => $pedido[0]['valor'] + $pedido[0]['frete']
        ];

        $this->renderView("end_checkout_cartao", $data);
    }

    public function iniciaPagamentoAction()
    {
        $data['token'] = TOKEN_PAGSEGURO;

        $emailPagseguro = EMAIL_PAGSEGURO;

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/sessions';

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);
        $idSessao = $xml->id;
        echo $idSessao;
        exit();
    }

    public function efetuaPagamentoCartao($itens, $total, $frete, $endereco = NULL, $nomeCliente = NULL, $hashPagSeguro = NULL, $tokenPagamentoCartao = NULL, $bandeiraCartao = NULL, $parcela = NULL, $cpfPagador = NULL, $idPedido = NULL, $custo = NULL)
    {
        $_endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $endereco
        ]);

        $_endereco = $_endereco[0];
        $_cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_endereco['id_cliente']
        ]);

        $_cliente = $_cliente[0];
        $_valor_total = number_format($total, 2);

        // Parcelamento
        $qtd = 1;
        $_valor_parcela = $_valor_total;
        if ($parcela != NULL) {
            $_parcela = explode('x', $parcela);
            if (sizeof($_parcela) == 2) {
                $qtd = $_parcela[0];
                $_valor_parcela = number_format(str_replace(',', '.', str_replace('R$', '', $_parcela[1])), 2, '.', '');
            }
        }

        $data['token'] = TOKEN_PAGSEGURO;
        $data['paymentMode'] = 'default';
        $data['senderHash'] = $hashPagSeguro; // Identificador do usuário
        $data['creditCardToken'] = $tokenPagamentoCartao; // gerado via javascript
        $data['paymentMethod'] = 'creditCard';
        $data['receiverEmail'] = EMAIL_PAGSEGURO;
        $data['senderName'] = htmlspecialchars(strip_tags(trim($nomeCliente))); // nome do cliente completo

        $data['senderAreaCode'] = substr(trim($_cliente['telefone']), 0, 2); // DDD
        $data['senderPhone'] = substr(trim($_cliente['telefone']), 2); // Telefone do cliente
        $data['senderEmail'] = trim($_cliente['email']);
        // $data['senderEmail'] = 'c24902204126876931598@sandbox.pagseguro.com.br';
        $data['senderCPF'] = ValidateUtil::cleanInput($cpfPagador);
        $data['installmentQuantity'] = "$qtd";
        // $data['noInterestInstallmentQuantity'] = '2'; // 2 VEZES SEM JUROS
        $data['installmentValue'] = "$_valor_parcela"; // Valor da Parcela

        $data['creditCardHolderName'] = htmlspecialchars(strip_tags(trim($nomeCliente))); // nome do titular
        $data['creditCardHolderCPF'] = ValidateUtil::cleanInput($cpfPagador);
        $data['creditCardHolderBirthDate'] = DateUtil::getDateDMY($_cliente['data_nascimento']);
        $data['creditCardHolderAreaCode'] = substr(trim($_cliente['telefone']), 0, 2); // DDD

        $data['creditCardHolderPhone'] = substr(trim($_cliente['telefone']), 2); // Telefone do Cliente

        $data['billingAddressStreet'] = trim($_endereco['numero']);

        $data['billingAddressNumber'] = trim($_endereco['numero']);

        $data['billingAddressDistrict'] = trim($_endereco['bairro']);
        $data['billingAddressPostalCode'] = trim(str_replace('-', '', str_replace('.', '', $_endereco['cep'])));

        $data['billingAddressCity'] = trim($_endereco['cidade']);

        $data['billingAddressState'] = trim($_endereco['uf']);

        $data['billingAddressCountry'] = 'Brasil';

        $data['shippingAddressRequired'] = 'false';
        $data['currency'] = 'BRL';

        // Itens
        $i = 1;
        foreach ($itens as $item) {
            if ($item['valor'] != 0) {
                $_i = $i ++;
                $_quantidade = $item['quantidade'];

                $_valot_item = number_format(($item['valor'] / $_quantidade), 2, '.', '');
                $data['itemId' . $_i] = "$_i";
                $data['itemQuantity' . $_i] = "$_quantidade";
                $data['itemDescription' . $_i] = $item['descricao'];
                $data['reference' . $_i] = $_cliente['id']; // referencia qualquer do produto
                $data['itemAmount' . $_i] = "$_valot_item";
            }
        }

        $_frete = number_format($frete, 2);
        if ($_frete != 0) {
            $_if = ($_i + 1);
            $data['itemId' . ($_i + 1)] = "$_if";
            $data['itemQuantity' . ($_i + 1)] = "1";
            $data['itemDescription' . ($_i + 1)] = 'Taxa';
            $data['reference' . ($_i + 1)] = "Correios"; // referencia qualquer do produto
            $data['itemAmount' . ($_i + 1)] = "$_frete";
        }

        // $_SERVER['REMOTE_ADDR']
        $emailPagseguro = EMAIL_PAGSEGURO;

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; // URL de teste

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);

        $retrn = (array) $xml;
        $success = TRUE;
        if (isset($retrn['error']) && sizeof($retrn['error']) > 0) {
            Log::write(serialize((array) $xml->message));
            $success = FALSE;

            $retornoCartao = [
                'error' => TRUE,
                'situacao_pagamento' => 'Pagamento não processado, insira seus dados corretamente',
                'status' => NULL,
                'code' => NULL,
                'date' => date('Y-m-d'),
                'parcela' => $qtd . "x de R$ " . ValidateUtil::setFormatMoney($_valor_parcela),
                'bandeira' => $bandeiraCartao,
                'success' => $success,
                'pedido' => $idPedido
            ];

            echo json_encode($retornoCartao);
        } else {

            $statusPagamento = [
                1 => 'Estamos Processando seu pagamento, confira emsSeu e-mail e em seu painel em nosso site.',
                2 => 'Em Análise',
                3 => 'Paga',
                7 => 'Cancelada'
            ];

            $statusParaCancelarPedido = [
                7
            ];

            $status = (array) $xml->status[0];
            $code = (array) $xml->code[0];
            $data = (array) $xml->date[0];

            // SALVA O CÓDIGO DA TRANSAÇÃO
            $this->dao('Core', 'Pedido')->update([
                'codigo_transacao' => $code[0],
                'tipo_pagamento' => 'cartao',
                'lucro' => $total - $custo
            ], [
                'id',
                '=',
                $idPedido
            ]);

            // PAGO | ATUALIZA PEDIDO
            if ($status[0] == 3) {
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $email = new Email();
                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $idPedido
                ]);

                // NOME CLIENTE
                $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $idPedido));

                // E-MAIL CLIENTE
                $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $idPedido));

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
                    $this->dao('Core', 'Pedido')
                        ->getField('id_endereco', $idPedido)
                ]);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                $bodyConfirmacaoPedido = $email->confirmacaoPedido($nomeCliente, $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido), $produtos, $endereco);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                $bodyConfirmacao = $email->confirmacaoPagamento($nomeCliente, $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $idPedido));

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                $email->send($emailCliente, "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($emailCliente, "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                $retornoCartao = [
                    'error' => FALSE,
                    'situacao_pagamento' => '<b style="color: green;">Pagamento efetuado com sucesso</b>',
                    'status' => $status[0],
                    'code' => $code[0],
                    'date' => $data[0],
                    'parcela' => $qtd . "x de R$ " . ValidateUtil::setFormatMoney($_valor_parcela),
                    'bandeira' => $bandeiraCartao,
                    'success' => $success,
                    'pedido' => $idPedido
                ];

                echo json_encode($retornoCartao);
            } else if (in_array($status[0], $statusParaCancelarPedido) && $idPedido != NULL) {
                $success = FALSE;

                // DELETE PEDIDO POIS O PAGAMENTO NÃO DEU :(
                $this->dao('Core', 'Pedido')->delete([
                    'id',
                    '=',
                    $idPedido
                ]);

                // DELETE ITENS PEDIDO POIS O PAGAMENTO NÃO DEU :(
                $this->dao('Core', 'ItemPedido')->delete([
                    'id_pedido',
                    '=',
                    $idPedido
                ]);

                $retornoCartao = [
                    'error' => FALSE,
                    'situacao_pagamento' => '<b>Pagamento não autorizado</b>',
                    'status' => $status[0],
                    'code' => $code[0],
                    'date' => $data[0],
                    'parcela' => $qtd . "x de R$ " . ValidateUtil::setFormatMoney($_valor_parcela),
                    'bandeira' => $bandeiraCartao,
                    'success' => $success,
                    'pedido' => $idPedido
                ];

                echo json_encode($retornoCartao);
            } else {

                $retornoCartao = [
                    'error' => FALSE,
                    'situacao_pagamento' => $statusPagamento[$status[0]],
                    'status' => $status[0],
                    'code' => $code[0],
                    'date' => $data[0],
                    'parcela' => $qtd . "x de R$ " . ValidateUtil::setFormatMoney($_valor_parcela),
                    'bandeira' => $bandeiraCartao,
                    'success' => $success,
                    'pedido' => $idPedido
                ];

                echo json_encode($retornoCartao);
            }
        }
    }

    public function _getStatusPagamentoAction($transacao = NULL, $statusAnteriorAoTime = NULL)
    {
        if ($transacao != NULL) {
            $_url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $transacao . "?email=" . EMAIL_PAGSEGURO . "&token=" . TOKEN_PAGSEGURO;
            $curl = curl_init("$_url");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $xml = simplexml_load_string(curl_exec($curl));
            curl_close($curl);

            $status = (array) $xml->status[0];

            return $status[0];
        } else {
            return $statusAnteriorAoTime;
        }
    }

    public function efetuaPagamentoBoleto($endereco = NULL, $hash = NULL, $itens = NULL, $total = 0, $frete = 0, $cpfPagador = NULL, $idPedido = NULL, $custo = NULL)
    {
        $_endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $endereco
        ]);

        $_endereco = $_endereco[0];
        $_cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_endereco['id_cliente']
        ]);

        $_cliente = $_cliente[0];

        $data['token'] = TOKEN_PAGSEGURO;
        $data['paymentMode'] = 'default';
        $data['senderHash'] = $hash;
        $data['paymentMethod'] = 'boleto';
        $data['receiverEmail'] = EMAIL_PAGSEGURO;
        $data['senderName'] = trim($_cliente['nome']);
        $data['senderAreaCode'] = '61';
        $data['senderPhone'] = '98493256';
        $data['senderEmail'] = trim($_cliente['email']);

        $data['senderCPF'] = ValidateUtil::cleanInput($cpfPagador);
        $data['shippingAddressRequired'] = 'false';
        $data['currency'] = 'BRL';

        // Itens
        $i = 1;
        foreach ($itens as $item) {
            if ($item['valor'] != 0) {
                $_i = $i ++;
                $_quantidade = $item['quantidade'];
                $valor_item = number_format((descontoBoleto($item['valor'], PERCENTUAL_DESCONTO_BOLETO) / $item['quantidade']) - 1, 2);
                // $valor_item = number_format((number_format($item['valor'] / $_quantidade)), 2, '.', '');
                $data['itemId' . $_i] = "$_i";
                $data['itemQuantity' . $_i] = "$_quantidade";
                $data['itemDescription' . $_i] = $item['descricao'];
                $data['reference' . $_i] = $_cliente['id']; // referencia qualquer do produto
                $data['itemAmount' . $_i] = "$valor_item";
            }
        }

        // CAGADA, COLOQUEI O FRETE COMO PRODUTO, TO NEM AI
        $_frete = number_format($frete, 2);
        if ($_frete != 0) {
            $_if = ($_i + 1);
            $data['itemId' . $_if] = "$_if";
            $data['itemQuantity' . $_if] = "1";
            $data['itemDescription' . $_if] = 'Taxa';
            $data['reference' . $_if] = "Taxa"; // referencia qualquer do produto
            $data['itemAmount' . $_if] = "$_frete";
        }

        // $_SERVER['REMOTE_ADDR']
        $emailPagseguro = EMAIL_PAGSEGURO;

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; // URL de teste

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);

        Log::write($xml);

        $boletoLink = (array) $xml->paymentLink[0];

        $code = (array) $xml->code[0];
        $date = (array) $xml->date[0];
        $paymentLink = $boletoLink;

        $retornoBoleto = [
            'code' => $code[0],
            'date' => $date[0],
            'paymentLink' => $paymentLink[0],
            'total' => descontoBoleto($total, PERCENTUAL_DESCONTO_BOLETO)
        ];

        // ATUALIZA EMAIL CLIENTE
        $this->dao('Core', 'Cliente')->update([
            'cpf' => ValidateUtil::cleanInput($cpfPagador)
        ], [
            'id',
            '=',
            $_cliente['id']
        ]);

        // SALVA O CÓDIGO DA TRANSAÇÃO
        $this->dao('Core', 'Pedido')->update([
            'codigo_transacao' => $code[0],
            'tipo_pagamento' => 'boleto',
            'link_boleto' => $paymentLink[0],
            'lucro' => $total - $custo
        ], [
            'id',
            '=',
            $idPedido
        ]);

        echo json_encode($retornoBoleto);
    }

    public function checkTransacao($transacao = NULL)
    {
        if ($transacao != NULL) {
            $_url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . trim($transacao) . "?email=" . EMAIL_PAGSEGURO . "&token=" . TOKEN_PAGSEGURO;
            $curl = curl_init("$_url");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $xml = simplexml_load_string(curl_exec($curl));
            curl_close($curl);

            $status = (array) $xml->status[0];
            $recebimento = (array) $xml->grossAmount[0];
            $recebimento_liquido = (array) $xml->netAmount[0];
            $taxas = $recebimento[0] - $recebimento_liquido[0];

            return $status[0];
        }
    }
}
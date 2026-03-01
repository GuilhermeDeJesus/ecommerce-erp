<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Mail\Email;
use Krypitonite\Util\PagarMeUtil;
use Krypitonite\Util\DateUtil;
use Krypitonite\Log\Log;
use Krypitonite\Util\ClearsaleUtil;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Http\Request;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'krypitonite/src/Controller/AbstractController.php';
require_once 'krypitonite/src/Util/PagarMeUtil.php';
require_once 'krypitonite/src/Util/ClearsaleUtil.php';
require_once 'krypitonite/src/Util/ValidateUtil.php';
require_once 'krypitonite/src/Log/Log.php';

class TarefasCronController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function promocoesParaMulheres()
    {
        $email = new Email();

        // ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // MULHERES
        // ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $mulheres = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'sexo',
            '=',
            'F'
        ], [
            'id',
            'DESC'
        ]);

        foreach ($mulheres as $c) {
            $produtos_para_mulheres = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id_categoria',
                'IN',
                [
                    18,
                    16,
                    22,
                    26
                ]
            ], [
                'id',
                'DESC'
            ], 50);

            $_produtos_mulheres = [];
            foreach ($produtos_para_mulheres as $prod) {
                $valor = explode('.', $prod['valor_venda']);
                $_produtos_mulheres[] = [
                    'id' => $prod['id'],
                    'produto' => $prod['descricao'],
                    'valor' => $valor[0],
                    'centavos' => $valor[1],
                    'url' => LINK_LOJA . '/produto/' . $prod['id'] . '/' . seo($prod['descricao'])
                ];
            }

            $nomeDaCara = explode(' ', $c['nome']);
            $email->send($c['email'], 'Olá ' . $nomeDaCara[0] . '! Seu sapato preferido está aqui e com Frete Grátis, vai perder ? 🤔', $email->promocoes($nomeDaCara[0], $_produtos_mulheres), '1001', $_produtos_mulheres);
        }
    }

    public function promocoesParaHomens()
    {
        $email = new Email();

        // ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // HOMENS
        // ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $homens = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'sexo',
            '=',
            'M'
        ], [
            'id',
            'DESC'
        ]);

        foreach ($homens as $c) {
            $produtos_para_homens = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id_categoria',
                'IN',
                [
                    18,
                    16,
                    22,
                    26
                ]
            ], [
                'id',
                'DESC'
            ], 50);

            $_produtos_homens = [];
            foreach ($produtos_para_homens as $prod) {
                $valor = explode('.', $prod['valor_venda']);
                $_produtos_homens[] = [
                    'id' => $prod['id'],
                    'produto' => $prod['descricao'],
                    'valor' => $valor[0],
                    'centavos' => $valor[1],
                    'url' => LINK_LOJA . '/produto/' . $prod['id'] . '/' . seo($prod['descricao'])
                ];
            }

            $nomeDoCara = explode(' ', $c['nome']);
            $email->send($c['email'], 'Olá ' . $nomeDoCara[0] . '! Seu sapato preferido está aqui e com Frete Grátis, vai perder ? 🤔', $email->promocoes($nomeDoCara[0], $_produtos_homens), '1001', $_produtos_homens);
        }
    }

    public function extras()
    {
        $email = new Email();

        $this->promocoesParaMulheres();
        $this->promocoesParaHomens();

        // // ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // // NEWSLATTER
        // // ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $newsletters = $this->dao('Core', 'Newsletter')->select([
            '*'
        ]);

        foreach ($newsletters as $c) {
            $produtos = $this->dao('Core', 'Produto')->select([
                '*'
            ], NULL, [
                'id',
                'DESC'
            ], 50);

            $_produtos = [];
            foreach ($produtos as $prod) {
                $valor = explode('.', $prod['valor_venda']);
                $_produtos[] = [
                    'id' => $prod['id'],
                    'produto' => $prod['descricao'],
                    'valor' => $valor[0],
                    'centavos' => $valor[1],
                    'url' => LINK_LOJA . '/produto/' . $prod['id'] . '/' . seo($prod['descricao'])
                ];
            }
            $email->send($c['email'], 'Seu perfume preferido está aqui e com Frete Grátis, vai perder ? 🤔', $email->promocoes('', $_produtos), '1001', $_produtos);
        }
    }

    // Tarefa Cron Tab
    public function notificarCarrinhosAbandonadosCron()
    {
        $email = new Email();

        $this->isAdmin(FALSE);
        $queridinho = $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho');
        $carrinhosAbandonados = $queridinho->select([
            '*'
        ]);

        $produtos = [];
        $clientes = [];
        foreach ($carrinhosAbandonados as $prod) {
            $clientes[] = $prod['id_cliente'];
            $produtos[$prod['id_cliente']][] = [
                'id' => $prod['id_produto'],
                'produto' => $this->dao('Core', 'Produto')->getField('descricao', $prod['id_produto']),
                'url' => LINK_LOJA . '/produto/' . $prod['id_produto'] . '/' . seo($this->dao('Core', 'Produto')->getField('descricao', $prod['id_produto']))
            ];
        }

        $clientes = array_unique($clientes);

        foreach ($clientes as $c) {
            $cliente = $this->dao('Core', 'Cliente');
            $customer = $cliente->select([
                '*'
            ], [
                'id',
                '=',
                $c
            ]);

            $cart = $queridinho->select([
                '*'
            ], [
                'id_cliente',
                '=',
                $c
            ]);

            if (date('Y-m-d') <= date($cart[0]['data_expira_envio_email']) || $cart[0]['data_expira_envio_email'] == NULL) {
                // $tresHorasAMais = date('H:i:s', strtotime('+3 hours', strtotime(date($cart[0]['ultima_hora_envio_emal_recuperacao_carrinho']))));
                // $ultimaHoraEnvio = date('H:i:s', strtotime('+0 hours', strtotime(date($cart[0]['ultima_hora_envio_emal_recuperacao_carrinho']))));
                // if ($ultimaHoraEnvio <= $tresHorasAMais) {
                $queridinho->update([
                    "ultima_hora_envio_emal_recuperacao_carrinho" => date('H:i:s'),
                    "ultima_data_envio_emal_recuperacao_carrinho" => date('Y-m-d'),
                    "data_expira_envio_email" => date('Y-m-d', strtotime('+7 days', strtotime(date('d-m-Y'))))
                ], [
                    'id_cliente',
                    '=',
                    $c
                ]);

                $bodyNotificarCarrinhoAbandonado = $email->notificarCarrinhoAbandonado($customer[0]['nome'], $produtos[$c]);
                $name = explode(' ', $customer[0]['nome']);
                $email->send($customer[0]['email'], 'Olá ' . trim($name[0]) . '! Seu carrinho está esperando. Finalize sua compra agora...', $bodyNotificarCarrinhoAbandonado, '1001', $produtos[$c]);
                // }
            }
        }
    }

    public function cancelarPedidoAction($idPedido = NULL)
    {
        $email = new Email();
        $id = Request::get('id');

        if ($id == NULL || $id == '') {
            $id = $idPedido;
        }

        $this->dao('Core', 'Pedido')->update([
            'id_situacao_pedido' => 3
        ], [
            'id',
            '=',
            $id
        ]);

        // NOME CLIENTE
        $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
            ->getField('id_cliente', $id));

        // E-MAIL CLIENTE
        $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
            ->getField('id_cliente', $id));

        // CORPO EMAIL DE PEDIDO CANCELADO
        $pedidoCancelado = $email->pedidoCancelado($nomeCliente, $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $id));

        $email->send($emailCliente, "Pedido Cancelado :( " . NOME_LOJA, $pedidoCancelado, '1001');

        $this->redirect('sistema', 'venda', 'form', 'id=' . $id);
    }

    public function aprovarPagamentoAction($idPedido = NULL)
    {
        $email = new Email();
        $id = Request::get('id');
        if (! isset($id)) {
            $id = $_POST['id'];
        }

        if ($id == NULL || $id == '') {
            $id = $idPedido;
        }

        $this->dao('Core', 'Pedido')->update([
            'id_situacao_pedido' => 2
        ], [
            'id',
            '=',
            $id
        ]);

        $itens = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $id
        ]);

        // NOME CLIENTE
        $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
            ->getField('id_cliente', $id));

        // E-MAIL CLIENTE
        $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
            ->getField('id_cliente', $id));

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
                ->getField('id_endereco', $id)
        ]);

        $email = new Email();

        // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
        $bodyConfirmacaoPedido = $email->confirmacaoPedido($nomeCliente, $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $id), $produtos, $endereco);

        // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
        $bodyConfirmacao = $email->confirmacaoPagamento($nomeCliente, $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $id));

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
        $email->send($emailCliente, "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($emailCliente, "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

        $this->redirect('sistema', 'venda', 'form', 'aprovado=1&id=' . $id);
    }

    public function checarPedidosPagSeguro()
    {
        $data_ultimos_60_dias = date('Y-m-d', strtotime('-60 days', strtotime(date('d-m-Y'))));
        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'codigo_transacao',
                '!=',
                NULL
            ],
            [
                'data',
                '>',
                $data_ultimos_60_dias
            ],
            [
                'id_situacao_pedido',
                'IN',
                [
                    1, // PENDENTE
                    6 // EM ANÁLISE
                ]
            ],
            [
                'gateway',
                '=',
                'PagSeguro'
            ]
        ]);

        foreach ($pedidos as $pedido) {
            if ($pedido['codigo_transacao'] != NULL) {
                $codigo_transacao = trim(str_replace('#', '', $pedido['codigo_transacao']));

                $_url = "https://ws.pagseguro.uol.com.br/v3/transactions/" . $codigo_transacao . "?email=" . EMAIL_PAGSEGURO . "&token=" . TOKEN_PAGSEGURO;
                $curl = curl_init("$_url");
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $xml = @simplexml_load_string(curl_exec($curl));
                curl_close($curl);

                $status = (array) $xml->status[0];
                $recebimento = (array) $xml->grossAmount[0];
                $recebimento_liquido = (array) $xml->netAmount[0];
                $taxas = $recebimento[0] - $recebimento_liquido[0];

                switch ($status[0]) {
                    case 1:
                        $email = new Email();
                        $_customer = $this->dao('Core', 'Cliente')->select([
                            '*'
                        ], [
                            'id',
                            '=',
                            $pedido['id_cliente']
                        ]);

                        $bodySegundaViaBoleto = $email->segundaViaBoleto($_customer[0]['nome'], $pedido['link_boleto'], ValidateUtil::setFormatMoney($pedido['valor'] + $pedido['frete']), NULL);

                        $email->send($_customer[0]['email'], "Não perca tempo, pague seu boleto e Receba seu Produto - " . NOME_LOJA, $bodySegundaViaBoleto, '1001');
                        break;
                    case 3:
                        $this->aprovarPagamentoAction($pedido['id']);
                        $this->dao('Core', 'Pedido')->update([
                            'conferido_pag_seguro' => TRUE,
                            'taxa_pag_seguro' => $taxas
                        ], [
                            'id',
                            '=',
                            $pedido['id']
                        ]);
                        break;
                    case 7:
                        $this->cancelarPedidoAction($pedido['id']);
                        $this->dao('Core', 'Pedido')->update([
                            'conferido_pag_seguro' => TRUE,
                            'taxa_pag_seguro' => $taxas
                        ], [
                            'id',
                            '=',
                            $pedido['id']
                        ]);
                        break;
                }
            }
        }
    }

    // Tarefa Cron Tab
    public function checarStatusDosPedidos()
    {
        $email = new Email();

        $data_ultimos_60_dias = date('Y-m-d', strtotime('-60 days', strtotime(date('d-m-Y'))));
        $pedidos_pagarme = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'codigo_transacao',
                '!=',
                NULL
            ],
            [
                'data',
                '>',
                $data_ultimos_60_dias
            ],
            [
                'id_situacao_pedido',
                'IN',
                [
                    1, // PENDENTE
                    2, // PAGO
                    6 // EM ANÁLISE
                ]
            ],
            [
                'gateway',
                '=',
                'Pagar.me'
            ]
        ]);

        foreach ($pedidos_pagarme as $pedido) {
            if ($pedido['tid'] != NULL && $pedido['id_situacao_pedido'] != 2) {

                $transacao = PagarMeUtil::get($pedido['tid']);

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $pedido['id_cliente']
                ]);

                switch ($transacao->status) {

                    // COMPRA AUTORIZADA, ENTÃO VAMOS VER O STATUS DA ANÁLISE
                    case 'authorized':
                        $checkStatusAposAnalise = ClearsaleUtil::checkStatus($pedido['numero_pedido']);
                        if ($checkStatusAposAnalise) {
                            $approved_status = [
                                'APA',
                                'APM',
                                'APP'
                            ];

                            $suspeito_status = [
                                'SUS'
                            ];

                            $disapproved_status = [
                                'RPM',
                                'FRD',
                                'RPA',
                                'RPP'
                            ];

                            $analyzing_status = [
                                'NVO',
                                'AMA'
                            ];

                            // Ainda em análise
                            if (in_array($checkStatusAposAnalise, $analyzing_status)) {
                                $this->dao('Core', 'Pedido')->update([
                                    'status_clear_sale' => $checkStatusAposAnalise
                                ], [
                                    'id',
                                    '=',
                                    $pedido['id']
                                ]);
                            }

                            // Compra suspeita
                            if (in_array($checkStatusAposAnalise, $suspeito_status)) {
                                $this->dao('Core', 'Pedido')->update([
                                    'status_clear_sale' => $checkStatusAposAnalise
                                ], [
                                    'id',
                                    '=',
                                    $pedido['id']
                                ]);
                            }

                            // Compra não aprovada pela Clearsale
                            if (in_array($checkStatusAposAnalise, $disapproved_status)) {
                                $this->dao('Core', 'Pedido')->update([
                                    'id_situacao_pedido' => 3,
                                    'status_clear_sale' => $checkStatusAposAnalise
                                ], [
                                    'id',
                                    '=',
                                    $pedido['id']
                                ]);
                            }

                            // Análise finalizada pela clearsale e já pode ser capturada
                            if (in_array($checkStatusAposAnalise, $approved_status)) {
                                $cap = (array) PagarMeUtil::capture($pedido['tid'], $transacao->amount);

                                // Captura realizada com sucesso
                                if ($cap['status'] == 'paid') {
                                    $this->dao('Core', 'Pedido')->update([
                                        'id_situacao_pedido' => 2,
                                        'data' => DateUtil::now(),
                                        'hora' => date("H:i:s"),
                                        'status_clear_sale' => $checkStatusAposAnalise
                                    ], [
                                        'id',
                                        '=',
                                        $pedido['id']
                                    ]);

                                    $itens = $this->dao('Core', 'ItemPedido')->select([
                                        '*'
                                    ], [
                                        'id_pedido',
                                        '=',
                                        $pedido['id']
                                    ]);

                                    // ENDEREÇO CLIENTE
                                    $endereco = $this->dao('Core', 'Endereco')->select([
                                        '*'
                                    ], [
                                        'id',
                                        '=',
                                        $this->dao('Core', 'Pedido')
                                            ->getField('id_endereco', $pedido['id'])
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
                                        ->getField('numero_pedido', $pedido['id']), $produtos, $endereco);

                                    // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                                    $bodyConfirmacao = $email->confirmacaoPagamento($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                                        ->getField('numero_pedido', $pedido['id']));

                                    // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                                    $email->send($_customer[0]['email'], "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

                                    // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                                    $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                                } else if ($cap['status'] !== 'paid') {
                                    Log::error('VendaController Linhda 2787: A captura da transação deu diferente de paid ' . $pedido['numero_pedido'] . '; Status: ' . $checkStatusAposAnalise);
                                }
                            }
                            // A Análise da clearsale não deu certo, o que pode ser que o pedido não foi enviado, vamos ver ? sim
                        }
                        // End Authorized
                        break;
                    case 'waiting_payment':

                        $bodySegundaViaBoleto = $email->segundaViaBoletoPagarme($_customer[0]['nome'], $pedido['link_boleto'], ValidateUtil::setFormatMoney($pedido['valor'] + $pedido['frete']), NULL);
                        $email->send($_customer[0]['email'], "Não perca tempo, pague seu boleto e Receba seu Produto - " . NOME_LOJA, $bodySegundaViaBoleto, '1001');
                        break;

                    case 'refused':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 3
                        ], [
                            'id',
                            '=',
                            $pedido['id']
                        ]);
                        break;

                    case 'paid':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 2,
                            // 'data' => DateUtil::now(),
                            'hora' => date("H:i:s")
                        ], [
                            'id',
                            '=',
                            $pedido['id']
                        ]);

                        $itens = $this->dao('Core', 'ItemPedido')->select([
                            '*'
                        ], [
                            'id_pedido',
                            '=',
                            $pedido['id']
                        ]);

                        // ENDEREÇO CLIENTE
                        $endereco = $this->dao('Core', 'Endereco')->select([
                            '*'
                        ], [
                            'id',
                            '=',
                            $this->dao('Core', 'Pedido')
                                ->getField('id_endereco', $pedido['id'])
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
                            ->getField('numero_pedido', $pedido['id']), $produtos, $endereco);

                        // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                        $bodyConfirmacao = $email->confirmacaoPagamento($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                            ->getField('numero_pedido', $pedido['id']));

                        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                        $email->send($_customer[0]['email'], "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

                        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                        $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                        break;
                }
            } else if ($pedido['tid'] != NULL && $pedido['id_situacao_pedido'] == 2) {
                $transacao = PagarMeUtil::get($pedido['tid']);
                switch ($transacao->status) {
                    case 'chargedback':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 4,
                            "data" => DateUtil::now(),
                            "hora" => date("H:i:s")
                        ], [
                            'id',
                            '=',
                            $pedido['id']
                        ]);
                        break;
                }
            }
        }
    }

    // Tarefa Cron Tab
    public function verificarPostagens()
    {
        $email = new Email();

        $etiquetas = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'postada',
            '!=',
            TRUE
        ]);

        foreach ($etiquetas as $etq) {
            $idPedido = $etq['id_pedido'];
            $rastreio = $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                [
                    'postado',
                    '!=',
                    TRUE
                ],
                [
                    'id_pedido',
                    '=',
                    $idPedido
                ]
            ]);

            // VERIFICA SE O CÓDIGO INFORMADO JÁ FOI POSTADO PELOS CORREIOS
            if ($this->_checarPostagemCodigoRastreio($rastreio[0]['codigo'])) {

                $this->dao('Core', 'Rastreiamento')->update([
                    'postado' => TRUE
                ], [
                    'id',
                    '=',
                    $etq['id_rastreamento']
                ]);

                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $idPedido
                ]);

                $this->dao('Core', 'Pedido')->update([
                    "id_pedido_status_fornecedor" => 2
                ], [
                    'id',
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
                $bodyConfirmacaoPedido = $email->confirmacaoCodigoRastreio($nomeCliente, $rastreio[0]['codigo'], $produtos, $endereco);

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($emailCliente, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                $etiquetas = self::dao('Core', 'Etiqueta')->update([
                    'postada' => TRUE
                ], [
                    'id',
                    '=',
                    $etq['id']
                ]);
            }
        }
    }

    private function _checarPostagemCodigoRastreio($codigo = '')
    {
        $post = array(
            'Objetos' => $codigo
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $result = curl_exec($ch);
        curl_close($ch);

        if (strpos($result, 'Objeto postado') !== false) {
            return true;
        } else {
            return false;
        }
    }
}
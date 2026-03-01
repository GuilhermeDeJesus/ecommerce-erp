<?php
namespace Store\Pagamento\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\DateUtil;
use Krypitonite\Mail\Email;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\PagarMeUtil;
use Krypitonite\Util\ClearsaleUtil;
use Krypitonite\Log\Log;
use PagarMe;
use Krypitonite\Util\CheckBehaviorUtil;
use Krypitonite\Http\Request;
require_once 'krypitonite/src/Util/PagarMeUtil.php';
require_once 'krypitonite/src/Mail/Email.php';

class PagamentoPagarmeController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function getTransacaoPixAction()
    {
        $transacao = PagarMeUtil::getTransacaoLoteria($_POST['id']);
        echo json_encode($transacao);
    }

    //
    // Função para Lotérica Premiada
    // Não alterar o nome da funcão
    //
    public function processarPagamentoPixParaLotericaAction()
    {
        $transacao = PagarMeUtil::gerarCodigoPix($_POST['valor'], $_POST['id'], $_POST['nome'], $_POST['email'], $_POST['cpf'], $_POST['telefone']);
        echo json_encode($transacao);
    }

    //
    // Função para Lotérica Premiada
    // Não alterar o nome da funcão
    //
    public function processarPagamentoAntifraudeAction()
    {
        $transacao = PagarMeUtil::transacionAntifruadeLoteria($_POST['valor'], $_POST['numberCard'], $_POST['cvv'], $_POST['expiry_month'], $_POST['expiry_year'], $_POST['parcela'], $_POST['name'], $_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['telefone'], $_POST['endereco'], $_POST['numero'], $_POST['cep'], $_POST['bairro'], $_POST['cidade'], $_POST['uf']);
        echo json_encode($transacao);
    }

    //
    // Função WEBHOOK
    // Não alterar o nome da funcão
    //
    public function receberStatusTransacaoClearAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data_webhooks = file_get_contents('php://input');

            $json = json_encode($data_webhooks);

            $js = explode(':', json_decode($json));
            $code = preg_replace('/[^0-9]/', '', $js[1]);

            if (isset($json['code'])) {
                Log::write('Webhook: CODE: ' . $json['code']);
            } else {

                $pedido = $this->dao('Core', 'Pedido')->select([
                    '*'
                ], [
                    [
                        'numero_pedido',
                        '=',
                        $code
                    ],
                    [
                        'id_situacao_pedido',
                        '!=',
                        2
                    ]
                ]);

                $_customer = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $pedido[0]['id_cliente']
                ]);

                $pedido = $pedido[0];

                $transacao = PagarMeUtil::get($pedido['tid']);

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

                            $email = new Email();

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

                Log::write('Webhook: New Status ' . $checkStatusAposAnalise . '  - Pedido ' . $code);
            }

            echo "success";
        }
    }

    public function pay_cred_cardAction()
    {
        $_name = $this->post('_name');
        $_number_card = ValidateUtil::cleanInput($this->post('_number_card'));
        $_parcela = $this->post('_parcela');
        $_expiry_month = $this->post('_expiry_month');
        $_expiry_year = $this->post('_expiry_year');
        $_cvv = $this->post('_cvv');
        $_parcela = $this->post('_parcela');

        $itens_pedido = CarrinhoUtil::getItens('_itens');
        $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $this->post('_endereco'));
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

        $datacard = [
            'titular' => $_name,
            'numero_cartao' => $_number_card,
            'mes_expiracao' => $_expiry_month,
            'ano_expiracao' => $_expiry_year,
            'cvv' => $_cvv,
            'quantidade_parcela' => $_parcela,
            'total' => $_total + $_frete,
            'total_frete' => $_frete
        ];

        $_quantas_tentativas_por_cartao = CheckBehaviorUtil::countTotalCard();
        // Cliente tem direito de tentar apenas com 3 cartões
        if ($_quantas_tentativas_por_cartao > 3) {
            echo json_encode([
                'success' => FALSE,
                'situacao_pagamento' => '<b style="color: red;">Transação não autorizada</b>',
                'parcela' => $_parcela . "x de R$ " . ValidateUtil::setFormatMoney(floatval($datacard['total']) / $datacard['quantidade_parcela']),
                'forma_pagamento' => 'Cartão de Crédito',
                'total' => floatval($datacard['total'])
            ]);
        } else {

            $_hora = date("H:i:s");
            $numPedido = rand(110000000, 990000000);

            // Gerar Pedido
            $form_pedido = [
                "data" => DateUtil::now(),
                "hora" => "$_hora",
                "valor" => $_total,
                "frete" => $data['_frete_total'],
                "frete_gratis" => verificaSeFreteGratis($_total),
                "id_cliente" => $_SESSION['cliente']['id_cliente'],
                "id_endereco" => $this->post('_endereco'),
                "id_situacao_pedido" => 1,
                "id_pedido_status_fornecedor" => 1,
                "numero_pedido" => $numPedido,
                "gateway" => "Pagar.me",
                "dispositivo" => ValidateUtil::getDispositivo(),
                "social_midia" => getSocialMidia()
            ];

            $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

            // Envia para conferencia
            $email = new Email();

            // Itens
            $custoTotal = [];
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

            $_customer = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $_SESSION['cliente']['id_cliente']
            ]);

            $_addreess = $this->dao('Core', 'Endereco')->select([
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

            // VERIFICA O TIPO DO CLIENTE ANTES DE CONTINUAR A TRANSACAO
            $typeCustomer = NULL;
            if ($_customer[0]['id_tipo_cliente'] != NULL) {
                $typeCustomer = $this->dao('Core', 'TipoCliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $_customer[0]['id_tipo_cliente']
                ]);

                $typeCustomer = $typeCustomer[0]['sigla'];
            }

            $canCustomerContinue = ValidateUtil::checkIfCustomerCanContinueTransaction($typeCustomer);
            // $temComprasComMesmoIP = $this->verificarComprasComMesmoIP($_customer[0]['id']);
            $temComprasComMesmoIP = FALSE;

            if ($canCustomerContinue == FALSE) {
                $this->dao('Core', 'Pedido')->update([
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'codigo_transacao' => 1111111111,
                    'response_code_gateway' => 9988,
                    'tipo_pagamento' => 'cartao',
                    'id_situacao_pedido' => 3
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $msg_customer = '<b>Pagamento não autorizado</b>';
                $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $_parcela . "x de R$ " . ValidateUtil::setFormatMoney($_total / $_parcela), NULL, NULL, ($_total / 100), NULL, 0, 0, 0);

                echo json_encode($result);
            } else if ($temComprasComMesmoIP == TRUE) {
                $this->dao('Core', 'Pedido')->update([
                    'codigo_envio' => $_SESSION['modalidade_envio'],
                    'codigo_transacao' => 1111111111,
                    'response_code_gateway' => 9989,
                    'tipo_pagamento' => 'cartao',
                    'id_situacao_pedido' => 3
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                $msg_customer = '<b>Pagamento não autorizado</b>';
                $result = self::fromStatusTransacaoCard(FALSE, 'REJEITADO', $msg_customer, $_parcela . "x de R$ " . ValidateUtil::setFormatMoney($_total / $_parcela), NULL, NULL, ($_total / 100), NULL, 0, 0, 0);

                echo json_encode($result);
            } else {

                // SET PAYMENT CRED CARD WITH REDE
                $transacao = PagarMeUtil::transacion($datacard, $_customer[0], $_addreess[0], $itens_pedido, $numPedido, $typeCustomer);
                $lucro = ($_total + $_frete) - (array_sum($custoTotal) + $transacao['valor_taxa']);

                switch ($transacao['situacao']) {
                    case 'APROVADO':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 2,
                            'codigo_transacao' => $transacao['nsu'],
                            'tipo_pagamento' => 'cartao',
                            'codigo_envio' => $_SESSION['modalidade_envio'],
                            'tid' => $transacao['tid'],
                            'response_code_gateway' => 0,
                            'status_clear_sale' => $transacao['status_clear_sale'],
                            'percentual_taxa_total' => $transacao['taxa'],
                            'valor_total_taxa' => $transacao['valor_taxa'],
                            'lucro' => $lucro
                        ], [
                            'id',
                            '=',
                            $idPedido
                        ]);

                        // Save card
                        $this->dao('Core', 'CartaoCliente')->delete([
                            'numero',
                            '=',
                            $_number_card
                        ]);

                        $this->dao('Core', 'CartaoCliente')->insert([
                            'nome_titular' => $_name,
                            'numero' => $_number_card,
                            'mes_validade' => $_expiry_month,
                            'ano_validade' => $_expiry_year,
                            'cvv' => $_cvv,
                            'bandeira' => $this->getBandeiraCartao($_number_card),
                            'id_cliente' => $_SESSION['cliente']['id_cliente']
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

                        break;

                    case 'REJEITADO':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 3,
                            'codigo_transacao' => $transacao['nsu'],
                            'tipo_pagamento' => 'cartao',
                            'codigo_envio' => $_SESSION['modalidade_envio'],
                            'tid' => $transacao['tid'],
                            'response_code_gateway' => $transacao['acquirer_response_code'],
                            'status_clear_sale' => $transacao['status_clear_sale'],
                            'percentual_taxa_total' => $transacao['taxa'],
                            'valor_total_taxa' => $transacao['valor_taxa'],
                            'lucro' => $lucro
                        ], [
                            'id',
                            '=',
                            $idPedido
                        ]);
                        break;

                    case 'ANALISE':
                        $this->dao('Core', 'Pedido')->update([
                            'id_situacao_pedido' => 6,
                            'codigo_transacao' => $transacao['nsu'],
                            'tipo_pagamento' => 'cartao',
                            'codigo_envio' => $_SESSION['modalidade_envio'],
                            'tid' => $transacao['tid'],
                            'response_code_gateway' => 0,
                            'status_clear_sale' => $transacao['status_clear_sale'],
                            'percentual_taxa_total' => $transacao['taxa'],
                            'valor_total_taxa' => $transacao['valor_taxa'],
                            'lucro' => $lucro
                        ], [
                            'id',
                            '=',
                            $idPedido
                        ]);

                        // Save card
                        $this->dao('Core', 'CartaoCliente')->delete([
                            'numero',
                            '=',
                            $_number_card
                        ]);

                        $this->dao('Core', 'CartaoCliente')->insert([
                            'nome_titular' => $_name,
                            'numero' => $_number_card,
                            'mes_validade' => $_expiry_month,
                            'ano_validade' => $_expiry_year,
                            'cvv' => $_cvv,
                            'bandeira' => $this->getBandeiraCartao($_number_card),
                            'id_cliente' => $_SESSION['cliente']['id_cliente']
                        ]);

                        // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                        $bodyConfirmacao = $email->pedidoEmAnalise($_customer[0]['nome'], $this->dao('Core', 'Pedido')
                            ->getField('numero_pedido', $idPedido));

                        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                        $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacao, '1001');
                        break;
                }

                // DEBUG DATA CLEARSALE
                if ($transacao['order_data_clearsale'] != NULL && is_array($transacao['order_data_clearsale'])) {
                    $this->dao('Core', 'Pedido')->update([
                        'session_id' => $_SESSION['MY_ID_SESSION'],
                        'debug_order_clearsale' => serialize($transacao['order_data_clearsale'])
                    ], [
                        'id',
                        '=',
                        $idPedido
                    ]);
                }

                echo json_encode($transacao);
            }
        }
    }

    public function _gerar_boleto_general($idEndereco = NULL)
    {
        $itens_pedido = CarrinhoUtil::getItens('_itens');
        $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $idEndereco);
        $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
        $_total = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], descontoBoleto($data['_total_carrinho'], PERCENTUAL_DESCONTO_BOLETO));

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
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
        $numPedido = rand(110000000, 990000000);

        // Gerar Pedido
        $form_pedido = [
            "data" => DateUtil::now(),
            "hora" => "$_hora",
            "valor" => $_total,
            "frete" => $data['_frete_total'],
            "frete_gratis" => verificaSeFreteGratis($_total),
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $idEndereco,
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => $numPedido,
            "gateway" => "Pagar.me",
            "dispositivo" => ValidateUtil::getDispositivo(),
            "social_midia" => getSocialMidia()
        ];

        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        // Custo Total
        $custoTotal = [];

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

                $_custo = $item['quantidade'] * $tamanho[0]['custo'];
                $custoTotal[] = $_custo;

                $lucro = $item['valor'] - $_custo;
                $form_item['lucro'] = $lucro;

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
                $custoTotal[] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

                $form_item['lucro'] = $lucro;

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

        $_customer = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_SESSION['cliente']['id_cliente']
        ]);

        $billet = (array) PagarMeUtil::gerarBoleto($_total + $_frete, $_customer[0]);

        // SALVA O CÓDIGO DA TRANSAÇÃO
        $this->dao('Core', 'Pedido')->update([
            'codigo_transacao' => $billet['nsu'],
            'tipo_pagamento' => 'boleto',
            'link_boleto' => $billet['boleto_url'],
            'codigo_envio' => $_SESSION['modalidade_envio'],
            'tid' => $billet['tid'],
            'valor_total_taxa' => 3.5,
            'lucro' => ($_total + $_frete) - array_sum($custoTotal)
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
                'preco' => $item['preco'],
                'codigo_envio' => $_SESSION['modalidade_envio']
            ];
        }

        // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
        $bodyConfirmacaoPedido = $email->confirmacaoPedido($_customer[0]['nome'], $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $idPedido), $produtos, $endereco);

        $bodySegundaViaBoleto = $email->segundaViaBoletoPagarme($_customer[0]['nome'], $billet['boleto_url'], ValidateUtil::setFormatMoney($_total + $_frete), $billet['boleto_barcode']);

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($_customer[0]['email'], "Não perca tempo, pague seu boleto e Receba seu Produto - " . NOME_LOJA, $bodySegundaViaBoleto, '1001');

        $retornoBoleto = [
            'code' => '<b>' . $billet['boleto_barcode'] . '</b>',
            'date' => date('d/m/Y h:i:s'),
            'paymentLink' => $billet['boleto_url'],
            'total' => $_total + $_frete
        ];

        echo json_encode($retornoBoleto);
    }

    public function gerar_pixAction()
    {
        $itens_pedido = CarrinhoUtil::getItens('_itens');
        $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $this->post('_endereco'));
        $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
        $_total = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], descontoBoleto($data['_total_carrinho'], PERCENTUAL_DESCONTO_BOLETO));

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
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
        $numPedido = rand(110000000, 990000000);

        // Gerar Pedido
        $form_pedido = [
            "data" => DateUtil::now(),
            "hora" => "$_hora",
            "valor" => $_total,
            "frete" => $data['_frete_total'],
            "frete_gratis" => verificaSeFreteGratis($_total),
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $this->post('_endereco'),
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => $numPedido,
            "gateway" => "Pagar.me",
            "dispositivo" => ValidateUtil::getDispositivo(),
            "social_midia" => getSocialMidia()
        ];

        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        // Custo Total
        $custoTotal = [];

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

                $_custo = $item['quantidade'] * $tamanho[0]['custo'];
                $custoTotal[] = $_custo;

                $lucro = $item['valor'] - $_custo;
                $form_item['lucro'] = $lucro;

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
                $custoTotal[] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

                $form_item['lucro'] = $lucro;

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

        $_customer = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_SESSION['cliente']['id_cliente']
        ]);

        // $billet = (array) PagarMeUtil::gerarBoleto($_total + $_frete, $_customer[0]);

        // SALVA O CÓDIGO DA TRANSAÇÃO
        $this->dao('Core', 'Pedido')->update([
            'codigo_transacao' => rand(110000, 9900000),
            'tipo_pagamento' => 'pix',
            'link_boleto' => '',
            'codigo_envio' => $_SESSION['modalidade_envio'],
            'tid' => rand(110000, 9900000),
            'valor_total_taxa' => 3.5,
            'lucro' => ($_total + $_frete) - array_sum($custoTotal)
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
                'preco' => $item['preco'],
                'codigo_envio' => $_SESSION['modalidade_envio']
            ];
        }

        // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
        $bodyConfirmacaoPedido = $email->confirmacaoPedido($_customer[0]['nome'], $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $idPedido), $produtos, $endereco);

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

        $retornoPix = [
            'pix' => '<b>20.747.907/0001-26</b>',
            'date' => date('d/m/Y h:i:s'),
            'paymentLink' => '',
            'total' => $_total + $_frete
        ];

        echo json_encode($retornoPix);
    }

    public function gerar_boletoAction()
    {
        $itens_pedido = CarrinhoUtil::getItens('_itens');
        $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $this->post('_endereco'));
        $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
        $_total = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], descontoBoleto($data['_total_carrinho'], PERCENTUAL_DESCONTO_BOLETO));

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
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
        $numPedido = rand(110000000, 990000000);

        // Gerar Pedido
        $form_pedido = [
            "data" => DateUtil::now(),
            "hora" => "$_hora",
            "valor" => $_total,
            "frete" => $data['_frete_total'],
            "frete_gratis" => verificaSeFreteGratis($_total),
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $this->post('_endereco'),
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => $numPedido,
            "gateway" => "Pagar.me",
            "dispositivo" => ValidateUtil::getDispositivo(),
            "social_midia" => getSocialMidia()
        ];

        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        // Custo Total
        $custoTotal = [];

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

                $_custo = $item['quantidade'] * $tamanho[0]['custo'];
                $custoTotal[] = $_custo;

                $lucro = $item['valor'] - $_custo;
                $form_item['lucro'] = $lucro;

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
                $custoTotal[] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

                $form_item['lucro'] = $lucro;

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

        $_customer = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_SESSION['cliente']['id_cliente']
        ]);

        $billet = (array) PagarMeUtil::gerarBoleto($_total + $_frete, $_customer[0]);

        // SALVA O CÓDIGO DA TRANSAÇÃO
        $this->dao('Core', 'Pedido')->update([
            'codigo_transacao' => $billet['nsu'],
            'tipo_pagamento' => 'boleto',
            'link_boleto' => $billet['boleto_url'],
            'codigo_envio' => $_SESSION['modalidade_envio'],
            'tid' => $billet['tid'],
            'valor_total_taxa' => 3.5,
            'lucro' => ($_total + $_frete) - array_sum($custoTotal)
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
                'preco' => $item['preco'],
                'codigo_envio' => $_SESSION['modalidade_envio']
            ];
        }

        // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
        $bodyConfirmacaoPedido = $email->confirmacaoPedido($_customer[0]['nome'], $this->dao('Core', 'Pedido')
            ->getField('numero_pedido', $idPedido), $produtos, $endereco);

        $bodySegundaViaBoleto = $email->segundaViaBoletoPagarme($_customer[0]['nome'], $billet['boleto_url'], ValidateUtil::setFormatMoney($_total + $_frete), $billet['boleto_barcode']);

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($_customer[0]['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
        $email->send($_customer[0]['email'], "Não perca tempo, pague seu boleto e Receba seu Produto - " . NOME_LOJA, $bodySegundaViaBoleto, '1001');

        $retornoBoleto = [
            'code' => '<b>' . $billet['boleto_barcode'] . '</b>',
            'date' => date('d/m/Y h:i:s'),
            'paymentLink' => $billet['boleto_url'],
            'total' => $_total + $_frete
        ];

        echo json_encode($retornoBoleto);
    }

    public function verificarComprasComMesmoIP($idCliente)
    {
        // VERIFICA SE O CLIENTE FEZ COMPRAS COM OUTROS NOMES DIFERENTES COM O MESMO IP
        $comprasDiferentesComMesmoIP = [];
        if ($idCliente != NULL) {
            $enderecoCompra = $this->dao('Core', 'EnderecoLocalizacaoCliente')->select([
                '*'
            ], [
                'id_cliente',
                '=',
                $idCliente
            ]);

            if ($enderecoCompra[0]['ip'] != NULL) {
                $outrosEnderecosComEsseIP = $this->dao('Core', 'EnderecoLocalizacaoCliente')->select([
                    '*'
                ], [
                    'ip',
                    '=',
                    $enderecoCompra[0]['ip']
                ]);

                foreach ($outrosEnderecosComEsseIP as $ends) {
                    $cpfCliente = $this->dao('Core', 'Cliente')->getField('cpf', $ends['id_cliente']);
                    $comprasDiferentesComMesmoIP[$cpfCliente] = [
                        'ip' => $ends['ip'],
                        'cpf' => $cpfCliente
                    ];
                }
            }
        }

        if (sizeof($comprasDiferentesComMesmoIP) > 1) {
            return TRUE;
        } else if (sizeof($comprasDiferentesComMesmoIP) == 1) {
            return FALSE;
        } else {
            return FALSE;
        }
    }

    public function getFrete_e_ValorTotal_Carrinho($cepDestino = NULL)
    {
        $_frete_total = [];
        $_produtos = [];
        $_total = [];

        // Total dos produtos no carrinho
        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            $_total[] = $_t['valor'];
        }

        // Total do frete no carrinho
        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            if (! $_t['frete_gratis']) {

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
        if (sizeof($_produtos) != 0) {
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

    private function getBandeiraCartao($numero = null)
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
            if (preg_match($pattern, $numero, $matches)) {
                $_brand = $brand;
                break;
            }
        }

        return $_brand;
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
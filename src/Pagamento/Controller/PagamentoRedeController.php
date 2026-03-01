<?php
namespace Store\Pagamento\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\RedeUtil;
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\DateUtil;
use Krypitonite\Mail\Email;
use Krypitonite\Util\ValidateUtil;
require_once 'krypitonite/src/Util/RedeUtil.php';

class PagamentoRedeController extends AbstractController
{

    private $_rede;

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function receved_pay_premiadaAction()
    {
        $transacao = RedeUtil::transacion($_POST['valor'], $_POST['numberCard'], $_POST['cvv'], $_POST['expiry_month'], $_POST['expiry_year'], 1, $_POST['name']);
        echo json_encode($transacao);
    }

    public function processarPagamentoAntifraudeAction()
    {
        $transacao = RedeUtil::transacionAntifruadeLoteria($_POST['valor'], $_POST['numberCard'], $_POST['cvv'], $_POST['expiry_month'], $_POST['expiry_year'], 1, $_POST['name'], $_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['telefone'], $_POST['endereco'], $_POST['numero'], $_POST['cep'], $_POST['bairro'], $_POST['cidade'], $_POST['uf']);
        echo json_encode($transacao);
    }

    public function realizar_ransacao_cartao_de_credito($_total, $_customer, $_endereco, $itens, $_number_card, $_cvv, $_expiry_month, $_expiry_year, $_parcela, $_name, $id_pedido)
    {
        // SET PAYMENT CRED CARD WITH REDE
        $transacao = RedeUtil::transacionAntifruadeDefault($_total, $itens, $_number_card, $_cvv, $_expiry_month, $_expiry_year, $_parcela, $_name, $_customer['nome'], $_customer['cpf'], $_customer['email'], $_customer['telefone'], $_endereco['endereco'], $_endereco['numero'], $_endereco['cep'], $_endereco['bairro'], $_endereco['cidade'], $_endereco['uf']);

        switch ($transacao['situacao']) {
            case 'APROVADO':
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2,
                    'tipo_pagamento' => 'cartao'
                ], [
                    'id',
                    '=',
                    $id_pedido
                ]);

                $email = new Email();
                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $id_pedido
                ]);

                // ENDEREÇO CLIENTE
                $endereco = $this->dao('Core', 'Endereco')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $this->dao('Core', 'Pedido')
                        ->getField('id_endereco', $id_pedido)
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

                // NOME CLIENTE
                $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $id_pedido));

                // E-MAIL CLIENTE
                $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $id_pedido));

                // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                $bodyConfirmacaoPedido = $email->confirmacaoPedido($nomeCliente, $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $id_pedido), $produtos, $endereco);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PAGAMENTO
                $bodyConfirmacao = $email->confirmacaoPagamento($nomeCliente, $this->dao('Core', 'Pedido')
                    ->getField('numero_pedido', $id_pedido));

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PAGAMENTO | SEND
                $email->send($emailCliente, "Confirmação de Pagamento - " . NOME_LOJA, $bodyConfirmacao, '1001');

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($emailCliente, "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                break;
            case 'REJEITADO':
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 3,
                    'tipo_pagamento' => 'cartao'
                ], [
                    'id',
                    '=',
                    $id_pedido
                ]);
                break;
        }

        return $transacao;
    }

    public function pay_cred_card_redeAction()
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
        $_total = $data['_total_carrinho'];

        // Gerar Pedido
        $form_pedido = [
            "data" => DateUtil::now(),
            "valor" => $_total,
            "frete" => $data['_frete_total'],
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $this->post('_endereco'),
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => $_SESSION['cliente']['id_cliente'] . '-' . mt_rand(),
            "gateway" => "Rede",
            "dispositivo" => ValidateUtil::getDispositivo()
        ];

        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        // Envia para conferencia
        $email = new Email();
        $email->send(EMAIL_CONTATO, 'Pedido Efetuado no Sistema - Shopvitas', $email->compraInCheckout($_SESSION['cliente']['nome'], $form_pedido['numero_pedido']), '1001');

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
                $lucro = $item['valor'] - $_custo;
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

        echo json_encode($this->realizar_ransacao_cartao_de_credito($_total, $_customer[0], $_addreess[0], $itens_pedido, $_number_card, $_cvv, $_expiry_month, $_expiry_year, $_parcela, $_name, $idPedido));
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
                $_frete_total[] = $correiosUtil->calcularPrecoPrazo($key_cepOrigem, str_replace(" ", "", ValidateUtil::cleanString($cepDestino)), TRUE, $_produtos[$key_cepOrigem]);
            }
        }

        return [
            '_frete_total' => array_sum($_frete_total),
            '_total_carrinho' => array_sum($_total)
        ];
    }
}
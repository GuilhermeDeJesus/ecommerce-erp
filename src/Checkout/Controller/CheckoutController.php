<?php
namespace Store\Checkout\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Http\Request;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\DateUtil;
use Store\Pagamento\Controller\PagamentoController;
use Krypitonite\Mail\Email;
use Krypitonite\Util\ClearsaleUtil;
use Store\Pagamento\Controller\PagamentoPagarmeController;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'src/Pagamento/Controller/PagamentoController.php';

class CheckoutController extends AbstractController
{

    private $_pagamento = NULL;

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->_pagamento = new PagamentoController();
    }

    public function validarCompraSimplesAction()
    {
        $link_venda = $_POST['link_upnid'];
        if ($_POST['id_produto'] != NULL) {
            $produto = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $_POST['id_produto']
            ]);

            if ($produto[0]['cupom_desconto'] != '' && $_POST['cupom'] != '' && $produto[0]['cupom_desconto'] == $_POST['cupom']) {
                header('Location: ' . $produto[0]['link_compra_upnid_cupom']);
            } else {
                if ($link_venda != '') {
                    header('Location: ' . $link_venda);
                } else {
                    header('Location: ' . $produto[0]['link_compra_upnid']);
                }
            }
        }
    }

    // Está função processa o pagamento via PAG SEGURO, ELA DEVIA ESTAR EM OUTRO LUGAR
    public function finalizarPedidoAction()
    {
        // $email = new Email();
        $itens_pedido = CarrinhoUtil::getItens('_itens');
        if (!is_array($itens_pedido) || sizeof($itens_pedido) === 0 || !isset($_SESSION['cliente']['id_cliente'])) {
            $this->redirect('checkout', 'checkout', 'cesta', 'carrinho_vazio=1');
            return;
        }

        $idEndereco = $this->post('endereco');
        if (!$idEndereco) {
            $this->redirect('checkout', 'checkout', 'finalizar');
            return;
        }

        $cep_destino = $this->dao('Core', 'Endereco')->getField('cep', $idEndereco);
        $modalidadeEnvio = $_SESSION['modalidade_envio'] ?? NULL;
        $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino, $modalidadeEnvio);
        $_total = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'] ?? NULL, $data['_total_carrinho']);

        // Gerar Pedido
        $form_pedido = [
            "data" => DateUtil::now(),
            "hora" => date("H:i:s"),
            "valor" => $_total,
            "frete" => $data['_frete_total'],
            "frete_gratis" => verificaSeFreteGratis($_total),
            "id_cliente" => $_SESSION['cliente']['id_cliente'],
            "id_endereco" => $idEndereco,
            "id_situacao_pedido" => 1,
            "id_pedido_status_fornecedor" => 1,
            "numero_pedido" => rand(110000000, 990000000),
            "gateway" => "PagSeguro",
            "codigo_envio" => $modalidadeEnvio,
            "dispositivo" => ValidateUtil::getDispositivo(),
            "social_midia" => getSocialMidia()
        ];

        $idPedido = $this->dao('Core', 'Pedido')->insert($form_pedido);

        // Envia para conferencia
        // $email->send(EMAIL_CONTATO, 'Pedido Efetuado no Sistema - Shopvitas', $email->compraInCheckout($_SESSION['cliente']['nome'], $form_pedido['numero_pedido']), '1001');

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
                $lucro = $item['valor'] - $_custo;
                $form_item['lucro'] = $lucro;
                $form_item['custo'] = $_custo;

                $custoTotal[] = $_custo;

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
                $form_item['custo'] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

                $custoTotal[] = ($produtoSelect[0]['valor_compra'] * $item['quantidade']);

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

        // SE O FRETE FOR GRÁTIS, NÃO COBRAR O FRETE NO GATEWAY DE PAGAMENTO DO PAGSEGURTO
        $_frete = $data['_frete_total'];
        if (! isset($_SESSION['modalidade_envio'])) {
            if ((float) $_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $_frete = 0;
            }
        } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
            if ((float) $_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $_frete = 0;
            }
        }

        // Pagamento via boleto
        switch ($this->post('tipo_pagamento')) {
            case 'boleto':

                // $pagarme = new PagamentoPagarmeController();
                // $pagarme->_gerar_boleto_general($this->post('endereco'));
                $this->_pagamento->efetuaPagamentoBoleto($this->post('endereco'), $this->post('hash'), $itens_pedido, $_total, $_frete, $this->post('cpf_boleto'), $idPedido, array_sum($custoTotal));
                break;
            case 'cartao':
                $this->_pagamento->efetuaPagamentoCartao($itens_pedido, $_total, $_frete, $this->post('endereco'), $this->post('name'), $this->post('hashPagSeguro'), $this->post('tokenPagamentoCartao'), $this->post('bandeira_cartao'), $this->post('parcela'), $this->post('cpf'), $idPedido, array_sum($custoTotal));
                break;
        }
    }

    public function calcularPrecoPrazoAction()
    {
        $cep_destino = str_replace('-', '', $_POST['cep']);

        // VALOR TOTAL CARRINHO
        $_total = [];
        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            $_total[] = $_t['valor'];
        }

        $_total = array_sum($_total);

        if ($_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
            echo 'R$ ' . ValidateUtil::setFormatMoney(0);
        } else {
            $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
            echo 'R$ ' . ValidateUtil::setFormatMoney($data['_frete_total']);
        }
    }

    public function calcularPrecoPrazoCartAction()
    {
        $cep_destino = str_replace('-', '', $_POST['cep']);

        $itensCarrinho = CarrinhoUtil::getItens('_itens');
        if (!is_array($itensCarrinho)) {
            $itensCarrinho = [];
        }

        // VALOR TOTAL CARRINHO
        $_total = [];
        foreach ($itensCarrinho as $_t) {
            $_total[] = $_t['valor'];
        }

        $_total = array_sum($_total);

        if ($_total >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
            echo json_encode([
                'frete_total' => 0
            ]);
        } else {
            $data = $this->getFrete_e_ValorTotal_Carrinho($cep_destino);
            echo json_encode([
                'frete_total' => $data['_frete_total']
            ]);
        }
    }

    public function cestaAction()
    {
        $removeItem = Request::get('remover');

        // REMOVE ITEM
        if (isset($removeItem) && $removeItem != NULL) {
            CarrinhoUtil::removeItemCarrinho('_itens', $removeItem);
        }

        $itensCarrinho = CarrinhoUtil::getItens('_itens');
        if (!is_array($itensCarrinho)) {
            $itensCarrinho = [];
        }

        // VALOR TOTAL CARRINHO
        $_total = [];
        foreach ($itensCarrinho as $_t) {
            $_total[] = $_t['valor'];
        }

        $cupomCliente = $_SESSION['CUPOM_CLIENTE'] ?? null;
        $_total = $this->calcularDescontoComCupomValido($cupomCliente, array_sum($_total));
        $qtdItens = sizeof($itensCarrinho);

        $data = [
            '_total' => ValidateUtil::setFormatMoney($_total),
            '_total_float' => $_total,
            '_qtd' => ($qtdItens > 1) ? $qtdItens . ' produtos' : $qtdItens . ' produto',
            '_itens' => $itensCarrinho,
            '_frete' => NULL
        ];

        $this->renderView("cart", $data);
    }

    public function cartAction()
    {
        if (isset($_POST) && sizeof($_POST) != 0) {
            $_cod_product = $_POST['cod_produto'];
            $product = $this->dao('Core', 'Produto')->select([
                'id',
                'descricao',
                'valor_venda',
                'cod_url_produto',
                'frete_gratis',
                'produto_gratis',
                'lucro',
                'link_compra_mercado_pago',
                'pixel'
            ], [
                'id',
                '=',
                $_cod_product
            ]);

            // ACIONAR PIXEL DO PRODUTO AO ADICIONA-LO AO CARRINHO
            if (isset($_SESSION['pixel_produto']) && $_SESSION['pixel_produto'] != '') {
                unset($_SESSION['pixel_produto']);
                $_SESSION['pixel_produto'] = $product[0]['pixel'];
            } else if ($product[0]['pixel'] != '') {
                $_SESSION['pixel_produto'] = $product[0]['pixel'];
            }

            // Selected tamanho
            $_hasTamanho = $this->dao('Core', 'TamanhoProduto')->countOcurrence('*', [
                'id_produto',
                '=',
                $_cod_product
            ]);

            $cart = CarrinhoUtil::getItens('_itens');
            foreach ($product as $check) {
                $imgs = getImagensProduto($check['id']);

                // SE TER OFERTA, COLOCAR NO CARRINHO
                if ($check['produto_gratis']) {
                    $check['valor_venda'] = 0;
                }

                $valor_tamanho = $check['valor_venda'];
                if ($_hasTamanho > 0 && $_POST['tamanho'] && $_POST['tamanho'] != NULL) {
                    $tamanhoProduto = $this->dao('Core', 'TamanhoProduto')->select([
                        'valor'
                    ], [
                        'id',
                        '=',
                        $_POST['tamanho']
                    ]);

                    if ($tamanhoProduto[0]['valor'] != NULL) {
                        $valor_tamanho = $tamanhoProduto[0]['valor'];
                    }
                }

                // TENTA CORRIGIR O BUG DO TAMANHO
                if ($_POST['tamanho'] != NULL) {
                    $tamanhoProduto = $this->dao('Core', 'TamanhoProduto')->select([
                        'valor'
                    ], [
                        'id',
                        '=',
                        $_POST['tamanho']
                    ]);

                    $valor_tamanho = $tamanhoProduto[0]['valor'];
                }

                $cart[$check['id']] = [
                    'codigo' => $check['id'],
                    'cod_url_produto' => $check['cod_url_produto'],
                    'descricao' => $check['descricao'],
                    'valor' => $valor_tamanho,
                    'link_compra_mercado_pago' => $check['link_compra_mercado_pago'],
                    'valor_unitario' => $valor_tamanho,
                    'quantidade' => 1,
                    'imagem' => $imgs[0],
                    'cor' => $_POST['cor'],
                    'tamanho' => $_POST['tamanho'],
                    'frete_gratis' => $check['frete_gratis']
                ];

                // Passa um Pente Fino
                foreach ($cart as $idProduto => $it) {
                    $tmp = $this->dao('Core', 'TamanhoProduto')->select([
                        '*'
                    ], [
                        [
                            'id',
                            '=',
                            $it['tamanho']
                        ]
                    ]);

                    if ($it['tamanho'] != NULL) {
                        $cart[$idProduto]['valor'] = $tmp[0]['valor'];
                        $cart[$idProduto]['valor_unitario'] = $tmp[0]['valor'];
                    }
                }

                if ($_SESSION['cliente']['id_cliente']) {
                    // Gravar Produto de Entereçe do cliente
                    $queridinho = $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho');
                    $has = $queridinho->countOcurrence('*', [
                        [
                            "id_produto",
                            '=',
                            $check['id']
                        ],
                        [
                            "id_cliente",
                            '=',
                            $_SESSION['cliente']['id_cliente']
                        ]
                    ]);

                    if ($has == 0) {
                        $queridinho->insert([
                            "id_produto" => $check['id'],
                            "id_cliente" => $_SESSION['cliente']['id_cliente'],
                            "data_expira_envio_email" => date('Y-m-d', strtotime('+7 days', strtotime(date('d-m-Y'))))
                        ]);
                    }
                }
            }

            CarrinhoUtil::addItem($cart, '_itens');
        }

        $this->redirect('checkout', 'checkout', 'cesta');
    }

    public function calculateTotalProductsAction()
    {
        $idProduto = Request::get('id_produto');
        $quantidade = Request::get('quantidade');

        if ($quantidade != NULL) {
            $_SESSION['prod_' . $idProduto] = $quantidade;
        }

        $itens = CarrinhoUtil::getItens('_itens');
        if (!is_array($itens)) {
            $itens = [];
        }
        $sum = [];
        foreach ($itens as $prod => $item) {
            $qtt = $item['quantidade'];
            if (isset($_SESSION['prod_' . $prod]) && $_SESSION['prod_' . $prod] != NULL) {
                $qtt = $_SESSION['prod_' . $prod];
            }

            $_SESSION['carrinho']['_itens'][$prod]['quantidade'] = $qtt;
            $_SESSION['carrinho']['_itens'][$prod]['valor'] = ($item['valor'] / $item['quantidade']) * $qtt;

            $valor_unitario = ($item['valor'] / $item['quantidade']);
            $sum[] = $valor_unitario * $qtt;
        }

        echo json_encode([
            'value_total_cart' => $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'] ?? null, array_sum($sum))
        ]);
    }

    public function current_checkoutAction()
    {
        $itens = CarrinhoUtil::getItens('_itens');
        if (!is_array($itens)) {
            $itens = [];
        }

        if (isset($_POST['codigo']) && is_array($_POST['codigo']) && sizeof($_POST['codigo']) != 0) {
            foreach ($_POST['codigo'] as $key => $val) {
                $itens[$val]['quantidade'] = $_POST['qtd'][$key];
                $itens[$val]['valor'] = ($itens[$val]['valor_unitario'] * $_POST['qtd'][$key]);
                $itens[$val]['valor_unitario'] = $this->dao('Core', 'Produto')->getField('valor_venda', $_POST['codigo'][$key]);
            }

            CarrinhoUtil::cleanItens();
            CarrinhoUtil::addItem($itens, '_itens');
        }

        $this->redirect('checkout', 'checkout', 'finalizar');
    }

    public function informacoesAction()
    {
        $this->renderView('informacoes');
    }

    public function mataAction()
    {
        unset($_SESSION['SOCIAL_MIDIA']);
        unset($_SESSION['START_TIME']);
    }

    public function finalizarAction()
    {
        $itensCarrinho = CarrinhoUtil::getItens('_itens');
        if (!is_array($itensCarrinho)) {
            $itensCarrinho = [];
        }

        // Require Authentication
        if (sizeof($itensCarrinho) != 0) {
            $this->hasAuthentication($_SESSION);

            // Endereço Principal
            $endereco_principal = $this->dao('Core', 'Endereco')->select([
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

            // Endereços do cliente
            $enderecos = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                [
                    'id_cliente',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ]
            ]);

            // Correspondência avançada | Facebook Ads
            $_nome_completo_cliente = explode(" ", $this->dao('Core', 'Cliente')->getField('nome', $_SESSION['cliente']['id_cliente']));
            $_email = $this->dao('Core', 'Cliente')->getField('email', $_SESSION['cliente']['id_cliente']);
            $_telefone = $this->dao('Core', 'Cliente')->getField('telefone', $_SESSION['cliente']['id_cliente']);

            $fbq_correspondecia_avancada = [
                'em' => strtolower(trim($_email)),
                'fn' => strtolower(trim($_nome_completo_cliente[0])),
                'ln' => strtolower(trim($_nome_completo_cliente[1] . ' ' . $_nome_completo_cliente[2] . ' ' . $_nome_completo_cliente[3] . ' ' . $_nome_completo_cliente[4])),
                'country' => 'BR',
                'ct' => strtolower(trim($endereco_principal[0]['cidade'])),
                'ph' => '55' . trim(ValidateUtil::cleanInput($_telefone)),
                'st' => strtolower(trim($endereco_principal[0]['uf'])),
                'zp' => substr(trim(ValidateUtil::cleanInput($endereco_principal[0]['cep'])), 0, 5)
            ];

            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CALCULANDO O FRETE
            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if (! isset($_SESSION['modalidade_envio'])) {
                $_SESSION['modalidade_envio'] = "03085";
            }

            $_frete_and_total_carrinho = $this->getFrete_e_ValorTotal_Carrinho($endereco_principal[0]['cep'], $_SESSION['modalidade_envio']);
            $_frete = $_frete_and_total_carrinho['_frete_total'];

            if (! isset($_SESSION['modalidade_envio'])) {
                if ((float) $_frete_and_total_carrinho['_total_carrinho'] >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                    $_frete = 0;
                }
            } else if (isset($_SESSION['modalidade_envio']) && $_SESSION['modalidade_envio'] == "03085") {
                if ((float) $_frete_and_total_carrinho['_total_carrinho'] >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                    $_frete = 0;
                }
            }

            $_total_produtos = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], $_frete_and_total_carrinho['_total_carrinho']);
            $_total_produtos_no_boleto = $this->calcularDescontoComCupomValido($_SESSION['CUPOM_CLIENTE'], descontoBoleto($_frete_and_total_carrinho['_total_carrinho'], PERCENTUAL_DESCONTO_BOLETO, FALSE));

            $data = [
                '_cpf_cliente' => $this->dao('Core', 'Cliente')->getField('cpf', $_SESSION['cliente']['id_cliente']),
                '_total' => ValidateUtil::setFormatMoney($_total_produtos),
                '_total_float' => $_total_produtos,
                '_total_frete' => $_frete_and_total_carrinho['_frete_total'],
                '_qtd' => (sizeof(CarrinhoUtil::getItens('_itens')) > 1) ? sizeof(CarrinhoUtil::getItens('_itens')) . ' produtos' : sizeof(CarrinhoUtil::getItens('_itens')) . ' produto',
                '_itens' => CarrinhoUtil::getItens('_itens'),
                '_frete' => ValidateUtil::setFormatMoney($_frete),
                '_valor_frete_sem_promocao' => $_frete_and_total_carrinho['_frete_total'],
                '_total_a_pagar' => ValidateUtil::setFormatMoney($_total_produtos + $_frete),
                '_total_a_pagar_no_boleto' => ValidateUtil::setFormatMoney($_total_produtos_no_boleto + $_frete),
                '_enderecos_cliente' => $enderecos,
                '_endereco_principal' => $endereco_principal[0],
                '_correspondencia_fbk' => $fbq_correspondecia_avancada,
                '_detalhes_entrega' => $_frete_and_total_carrinho['_detalhes_entrega'][0]
                // 'id_session_clearsale' => ClearsaleUtil::createIdSession()
            ];

            switch (GATEWAY) {
                case 'mercadopago':
                    $this->renderView("current_checkout_mercado_pago", $data);
                    break;
                case 'pagseguro':
                    $this->renderView("current_checkout_pag_seguro", $data);
                    break;
                case 'pagarme':
                    $this->renderView("current_checkout_pagarme", $data);
                    break;
                case 'rede':
                    $this->renderView("current_checkout_rede", $data);
                    break;
                default:
                    $this->renderView("current_checkout", $data);
                    break;
            }
        } else {
            $this->redirect('checkout', 'checkout', 'cesta', 'carrinho_vazio=1');
        }
    }

    public function getFrete_e_ValorTotal_Carrinho($cepDestino = NULL, $modalidadeEnvio = NULL)
    {
        $_frete_total = [];
        $_produtos = [];
        $_total = [];
        $detalhes_frete = [];

        $itensCarrinho = CarrinhoUtil::getItens('_itens');
        if (!is_array($itensCarrinho)) {
            $itensCarrinho = [];
        }

        // Total dos produtos no carrinho
        foreach ($itensCarrinho as $_t) {
            $_total[] = $_t['valor'];
        }

        // Total do frete no carrinho
        foreach ($itensCarrinho as $_t) {
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
                $_frete_total[] = $correiosUtil->calcularPrecoPrazo($key_cepOrigem, str_replace(" ", "", ValidateUtil::cleanString($cepDestino)), TRUE, $_produtos[$key_cepOrigem], $modalidadeEnvio);
                $detalhes_frete[] = $correiosUtil->getValorPrazoPAC_e_SEDEX($key_cepOrigem, str_replace(" ", "", ValidateUtil::cleanString($cepDestino)), TRUE, $_produtos[$key_cepOrigem]);
            }
        }

        return [
            '_frete_total' => array_sum($_frete_total),
            '_total_carrinho' => array_sum($_total),
            '_detalhes_entrega' => $detalhes_frete
        ];
    }

    public function alterarEnderecoEntregaAction()
    {
        $idEndereco = $_POST['endereco_principal'];

        // Tira os endereços como principal
        $this->dao('Core', 'Endereco')->update([
            'principal' => 0
        ], [
            'id_cliente',
            '=',
            $_SESSION['cliente']['id_cliente']
        ]);

        // Coloca o endereço escolhido com principal
        $this->dao('Core', 'Endereco')->update([
            'principal' => TRUE
        ], [
            [
                'id',
                '=',
                $idEndereco
            ],
            [
                'id_cliente',
                '=',
                $_SESSION['cliente']['id_cliente']
            ]
        ]);

        $this->redirect('checkout', 'checkout', 'finalizar');
    }

    public function alterarModalidadeDeEnvioPedidoAction()
    {
        $transporte = Request::get('pedido_transporte');

        if (isset($_SESSION['modalidade_envio'])) {
            unset($_SESSION['modalidade_envio']);
        }

        $_SESSION['modalidade_envio'] = $transporte;

        echo true;
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

    public function removeCupomAction()
    {
        unset($_SESSION['CUPOM_VALIDADO']);
        unset($_SESSION['CUPOM_CLIENTE']);
        $data = $this->getFrete_e_ValorTotal_Carrinho();

        echo json_encode([
            'success' => TRUE,
            'valor_total_sem_cupom' => ValidateUtil::setFormatMoney($data['_total_carrinho'])
        ]);
    }

    public function checkCupomAction()
    {
        $cupom = strtoupper(Request::get('meu_cupom'));
        $configuracoesPlataforma = dao('Core', 'ConfiguracoesPlataforma')->select([
            '*'
        ], [
            'cupom',
            '=',
            $cupom
        ]);

        if (sizeof($configuracoesPlataforma) == 1 && $configuracoesPlataforma[0]['cupom'] == $cupom) {

            $data = $this->getFrete_e_ValorTotal_Carrinho();
            $_total = $data['_total_carrinho'];
            $_percentualDesconto = $configuracoesPlataforma[0]['percentual_desconto_cupom'];
            if ($_percentualDesconto != 0) {
                $sub = 100 - $_percentualDesconto;
                $_total = ($_total / 100) * $sub;
            }

            $_SESSION['CUPOM_VALIDADO'] = TRUE;
            $_SESSION['CUPOM_CLIENTE'] = $configuracoesPlataforma[0]['cupom'];
            echo json_encode([
                'cupom_valido' => TRUE,
                'message' => 'Cupom aplicado, confira sua sacola.',
                'total_com_desconto' => 'R$ ' . ValidateUtil::setFormatMoney($_total),
                'percentual_desconto' => $_percentualDesconto
            ]);
        } else {

            $_SESSION['CUPOM_VALIDADO'] = FALSE;
            $_SESSION['CUPOM_CLIENTE'] = $cupom;

            echo json_encode([
                'cupom_valido' => FALSE,
                'message' => 'Cupom inválido'
            ]);
        }
    }
}


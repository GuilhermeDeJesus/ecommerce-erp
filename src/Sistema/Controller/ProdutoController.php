<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Krypitonite\Util\ValidateUtil;
use Configuration\Configuration;
use SkyHub\Api;
include_once ('lib/PHP_XLSXWriter-master/xlsxwriter.class.php');

class ProdutoController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [
            'produtos' => $this->dao('Core', 'Produto')->select([
                '*'
            ])
        ];

        $this->renderView("index", $data);
    }

    public function attAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            'ativo',
            '=',
            TRUE
        ]);

        foreach ($produtos as $p) {

            $_valor_venda = ($p['valor_compra'] / 100) * 150;
            $_valor_venda = intval($_valor_venda);

            $ultimo_digito_preco = substr($_valor_venda, - 1);

            switch ($ultimo_digito_preco) {
                case 1:
                    $_valor_venda = ($_valor_venda + 3) + 0.9;
                    break;
                case 2:
                    $_valor_venda = ($_valor_venda + 2) + 0.9;
                    break;
                case 3:
                    $_valor_venda = ($_valor_venda + 1) + 0.9;
                    break;
                case 4:
                    $_valor_venda = $_valor_venda + 0.9;
                    break;
                case 5:
                    $_valor_venda = ($_valor_venda + 4) + 0.9;
                    break;
                case 6:
                    $_valor_venda = ($_valor_venda + 3) + 0.9;
                    break;
                case 7:
                    $_valor_venda = ($_valor_venda + 2) + 0.9;
                    break;
                case 8:
                    $_valor_venda = ($_valor_venda + 1) + 0.9;
                    break;
                case 9:
                    $_valor_venda = $_valor_venda + 0.9;
                    break;
            }

            // Valor Fake
            $_valor_sem_oferta = ($_valor_venda / 100) * 140;

            $tamanhos = $this->dao('Core', 'TamanhoProduto')->select([
                '*'
            ], [
                'id_produto',
                '=',
                $p['id']
            ]);

            $this->dao('Core', 'Produto')->update([
                'valor_venda' => $_valor_venda,
                'valor_venda_b2w' => $_valor_venda,
                'valor_sem_oferta' => $_valor_sem_oferta
            ], [
                'id',
                '=',
                $p['id']
            ]);

            if (sizeof($tamanhos) > 0) {
                foreach ($tamanhos as $t) {
                    $this->dao('Core', 'TamanhoProduto')->update([
                        "valor" => $_valor_venda,
                        "custo" => $p['valor_compra']
                    ], [
                        'id',
                        '=',
                        $t['id']
                    ]);
                }
            }
        }
    }

    public function tabelaAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            'ativo',
            '=',
            TRUE
        ]);

        $data = [
            'produtos' => $produtos
        ];

        $this->renderView('tabela', $data);
    }

    public function atualizarPrecoProdutoAction()
    {
        $idProduto = $this->post('id_produto');

        $produto = [];
        $produto['lucro_reais'] = ValidateUtil::paraFloat($this->post('lucro_reais_' . $idProduto));
        $produto['valor_compra'] = ValidateUtil::paraFloat($this->post('custo_produto_' . $idProduto));
        $produto['valor_venda'] = ValidateUtil::paraFloat($this->post('venda_produto_' . $idProduto));

        $tamanhos = $this->dao('Core', 'TamanhoProduto')->select([
            '*'
        ], [
            'id_produto',
            '=',
            $idProduto
        ]);

        $this->dao('Core', 'Produto')->update($produto, [
            'id',
            '=',
            $idProduto
        ]);

        if (sizeof($tamanhos) > 0) {
            foreach ($tamanhos as $t) {
                $custo = ValidateUtil::paraFloat($this->post('custo_tamanho_' . $t['id']));
                $venda = ValidateUtil::paraFloat($this->post('venda_tamanho_' . $t['id']));
                $this->dao('Core', 'TamanhoProduto')->update([
                    "valor" => $venda,
                    "custo" => $custo
                ], [
                    'id',
                    '=',
                    $t['id']
                ]);
            }
        }

        echo true;
    }

    public function downloadPlanilhaProdutosAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            'id_categoria',
            'IN',
            [
                2,
                3,
                4,
                5,
                6
            ]
        ]);

        $header = [
            'Id' => 'string',
            'Descricao' => 'string',
            'Custo' => 'string'
        ];

        $planilha = [];
        foreach ($produtos as $p) {
            $planilha[] = [
                'Id' => $p['id'],
                'Descricao' => $p['descricao'],
                'Custo' => ValidateUtil::setFormatMoney($p['valor_compra'])
            ];
        }

        $this->planilhaXLSX($planilha, $header, 'Produtos - ' . date('d-m-Y'));
    }

    public function planilhaXLSX($array, $header, $nome_arquivo)
    {
        $filename = $nome_arquivo . "_" . date('d_m_Y') . ".xlsx";
        header('Content-disposition: attachment; filename="' . \XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);

        $writer->writeSheetRow('Sheet1', $array);
        $writer->writeSheet($array, 'Sheet1', $header);
        $writer->writeToStdOut();

        exit(0);
    }

    public function metricasAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            'id'
        ]);

        $ESTADOS = estadosBrasileiros();

        $_aprovadosPorEstado = [];
        $_AprovadoCartaoPorEstado = [];
        $_AprovadoBoletoPorEstado = [];
        $_BoletoNaoPagosPorEstado = [];
        $_BoletosPendentesPorEstadoBar = [];
        $_AprovadosPorDia = [];
        $ultimo_dia = date("t", mktime(0, 0, 0, date("m"), '01', date("Y"))); // Mágica, plim!

        foreach ($produtos as $produto) {

            foreach (range(1, $ultimo_dia) as $dia) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.id_situacao_pedido = 2 AND i.id_produto = "' . $produto['id'] . '" AND p.data = "' . date("Y-m-" . $dia) . '"');
                $_AprovadosPorDia[$produto['id']][] = (int) $get[0]['total'];
            }

            // PEDIDOS APROVADOS POR ESTADO
            foreach ($ESTADOS as $UF => $U) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.id_situacao_pedido = 2 AND i.id_produto = "' . $produto['id'] . '" and e.uf = "' . $UF . '" AND p.data BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -7 DAY) AND CURRENT_DATE()');
                if ($get[0]['total'] != 0) {
                    $_aprovadosPorEstado[$produto['id']][] = [
                        'name' => $U,
                        'value' => (int) $get[0]['total']
                    ];
                }
            }

            // CARTÕES APROVADOS POR ESTADO
            foreach ($ESTADOS as $UF => $U) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.tipo_pagamento = "Cartao" AND p.id_situacao_pedido = 2 AND i.id_produto = "' . $produto['id'] . '" and e.uf = "' . $UF . '" AND p.data BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()');
                $_AprovadoCartaoPorEstado[$produto['id']][] = (int) $get[0]['total'];
            }

            // BOLETOS APROVADOS POR ESTADO
            foreach ($ESTADOS as $UF => $U) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.tipo_pagamento = "Boleto" AND p.id_situacao_pedido = 2 AND i.id_produto = "' . $produto['id'] . '" and e.uf = "' . $UF . '" AND p.data BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()');
                $_AprovadoBoletoPorEstado[$produto['id']][] = (int) $get[0]['total'];
            }

            // BOLETOS NÃO PAGOS POR ESTADO
            foreach ($ESTADOS as $UF => $U) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.tipo_pagamento = "Boleto" AND p.id_situacao_pedido = 1 AND i.id_produto = "' . $produto['id'] . '" and e.uf = "' . $UF . '" AND p.data BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()');
                $_BoletosPendentesPorEstadoBar[$produto['id']][] = (int) $get[0]['total'];
            }

            // BOLETOS NÃO PAGOS POR ESTADO
            foreach ($ESTADOS as $UF => $U) {
                $get = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido INNER JOIN endereco e ON e.id = p.id_endereco WHERE p.tipo_pagamento = "Boleto" AND p.id_situacao_pedido = 1 AND i.id_produto = "' . $produto['id'] . '" and e.uf = "' . $UF . '" AND p.data BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL -30 DAY) AND CURRENT_DATE()');
                $_BoletoNaoPagosPorEstado[$produto['id']][] = [
                    'name' => $U,
                    'value' => (int) $get[0]['total']
                ];
            }
        }

        $data = [
            'produtos' => $this->dao('Core', 'Produto')->select([
                'descricao',
                'id'
            ]),
            '_estados' => json_encode(array_values(estadosBrasileiros())),
            '_pedidos_aprovados_por_estado' => $_aprovadosPorEstado,
            '_cartoes_aprovado_por_estado' => $_AprovadoCartaoPorEstado,
            '_boletos_aprovado_por_estado' => $_AprovadoBoletoPorEstado,
            '_boletos_nao_pagos_por_estado' => $_BoletoNaoPagosPorEstado,
            '_boletos_nao_pagos_por_estado_bars' => $_BoletosPendentesPorEstadoBar,
            '_pedidos_aprovados_por_dia_mes_atual' => $_AprovadosPorDia
        ];

        $this->renderView("metricas", $data);
    }

    public function _getEmailsPorStatusDeVendaAction()
    {
        $data = [];
        $idProduto = $_GET['id'];
        $idSituacaoPedido = (int) $_GET['situacao'];
        $tipo = $_GET['tipo'];

        $where = [];

        if ($idProduto != '') {
            array_push($where, [
                'id_produto',
                '=',
                $idProduto
            ]);
        }

        if ($tipo != '') {
            array_push($where, [
                'tipo_pagamento',
                '=',
                $tipo
            ]);
        }

        if ($idSituacaoPedido != '') {
            array_push($where, [
                'id_situacao_pedido',
                '=',
                $idSituacaoPedido
            ]);
        }

        $pedidos = $this->dao('Core', 'Pedido')->selectJoin('item_pedido', [
            'id',
            'id_pedido'
        ], $where);

        foreach ($pedidos as $pedido) {
            if ($pedido['id_cliente']) {
                $cliente = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $pedido['id_cliente']
                ]);

                $data[] = $cliente[0]['email'];
            }
        }

        $data = array_unique($data);

        echo "<pre>";

        $ars = array_chunk($data, 1);
        echo 'Total: ' . sizeof($data) . ', <br>';
        echo 'Email, <br>';
        foreach ($ars as $ar) {
            $comma_separated = implode(",", $ar);
            echo $comma_separated . ",<br>";
        }
    }

    public function inserirEditarAction()
    {
        $id = Request::get('id');

        $data = [
            'categorias' => $this->dao('Core', 'Categoria')->select([
                '*'
            ]),
            'marcas' => $this->dao('Core', 'Marca')->select([
                '*'
            ]),
            'tamanhos' => $this->dao('Core', 'TamanhoProduto')->select([
                '*'
            ], [
                'id_produto',
                '=',
                $id
            ]),
            'fornecedores' => $this->dao('Core', 'Pessoa')->select([
                '*'
            ], [
                'id_classe',
                '=',
                2
            ])
        ];

        if ($id) {
            $data['produto'] = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $id
            ]);
        }

        $this->renderView("inserir", $data);
    }

    public function deletarAction()
    {
        $id = Request::get('id_produto');
        $this->dao('Core', 'Lancamento')->delete([
            'id_produto',
            '=',
            $id
        ]);
        $this->dao('Core', 'HistoricoVisualizacaoProdutoCarrinho')->delete([
            'id_produto',
            '=',
            $id
        ]);
        $this->dao('Core', 'TamanhoProduto')->delete([
            'id_produto',
            '=',
            $id
        ]);
        $this->dao('Core', 'ItemPedido')->delete([
            'id_produto',
            '=',
            $id
        ]);
        $this->dao('Core', 'CorProduto')->delete([
            'id_produto',
            '=',
            $id
        ]);
        $this->dao('Core', 'Produto')->delete([
            'id',
            '=',
            $id
        ]);

        $dir = Configuration::PATH_PRODUTO . '/' . $id;
        $dirColors = Configuration::PATH_PRODUTO . '/' . $id . '/cor';
        rmdir($dirColors);
        rmdir($dir);
    }

    public function atualizarValoresAction()
    {
        $_produtos = $this->dao('Core', 'Produto')->select([
            'id',
            'descricao',
            'valor_compra',
            'valor_venda',
            'valor_venda_b2w'
        ], [
            'valor_venda_b2w',
            '!=',
            NULL
        ]);

        foreach ($_produtos as $produto) {
            $valor_venda = ($produto['valor_venda_b2w'] / 100) * 97;
            $this->dao('Core', 'Produto')->update([
                'valor_venda' => $valor_venda
            ], [
                'id',
                '=',
                $produto['id']
            ]);
        }
    }

    public function gerarSkuProdutosSemSkuAction()
    {
        $_produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            'id',
            '!=',
            0
        ]);

        $produto = [];
        foreach ($_produtos as $produto) {
            if ($produto['SKU'] == NULL) {

                $SKU = rand(13, 13);
                $SKU = substr(mt_rand() . mt_rand() . mt_rand(), 0, 12);
                $this->dao('Core', 'Produto')->update([
                    'SKU' => $SKU
                ], [
                    'id',
                    '=',
                    $produto['id']
                ]);
            }
        }
    }

    public function cadastrarAction()
    {
        $produto = [
            'id' => $_POST['id'],
            'descricao' => $_POST['descricao']
        ];

        // ATIVO
        if ($_POST['ativo'] == 'on') {
            $produto['ativo'] = TRUE;
        } else {
            $produto['ativo'] = 0;
        }

        // SkyHub
        if (isset($_POST['skyhub']) && $_POST['skyhub'] == 'on') {
            $produto['skyhub'] = TRUE;
        } else {
            $produto['skyhub'] = 0;
        }

        // FRETE GRÁTIS
        if (isset($_POST['frete_gratis']) && $_POST['frete_gratis'] == 'on') {
            $produto['frete_gratis'] = TRUE;
        } else {
            $produto['frete_gratis'] = 0;
        }

        // PRODUTO GRÁTIS
        if (isset($_POST['produto_gratis']) && $_POST['produto_gratis'] == 'on') {
            $produto['produto_gratis'] = TRUE;
        } else {
            $produto['produto_gratis'] = 0;
        }

        // LUCRO
        if ($_POST['lucro']) {
            $produto['lucro'] = ValidateUtil::paraFloat($_POST['lucro']);
        }

        // LUCRO EM REAIS
        if ($_POST['lucro_reais']) {
            $produto['lucro_reais'] = ValidateUtil::paraFloat($_POST['lucro_reais']);
        }

        // VALOR VENDA
        if ($_POST['valor_venda']) {
            $valor_venda = (int) ValidateUtil::paraFloat($_POST['valor_venda']);
            $produto['valor_venda'] = $valor_venda + 0.90;
        }

        // VALOR VENDA IDEIAL NA B2W
        if ($_POST['valor_venda_b2w']) {
            $valor_venda_b2w = (int) ValidateUtil::paraFloat($_POST['valor_venda_b2w']);
            $produto['valor_venda_b2w'] = $valor_venda_b2w + 0.90;
        }

        // VALOR SEM OFERTA
        if ($_POST['valor_sem_oferta']) {
            $valor_sem_oferta = (int) ValidateUtil::paraFloat($_POST['valor_sem_oferta']);
            $produto['valor_sem_oferta'] = $valor_sem_oferta + 0.90;
        }

        // VALOR COMPRA
        if ($_POST['valor_compra']) {
            $produto['valor_compra'] = ValidateUtil::paraFloat($_POST['valor_compra']);
        }

        // PREÇO EM DOLAR PARA PAGAR PARA O FORNECEDOR
        if ($_POST['preco_dolar_fornecedor']) {
            $produto['preco_dolar_fornecedor'] = ValidateUtil::paraFloat($_POST['preco_dolar_fornecedor']);
        }

        // SKU
        if ($_POST['sku']) {
            $produto['sku'] = $_POST['sku'];
        }

        // EAN
        if ($_POST['ean']) {
            $produto['ean'] = $_POST['ean'];
        }

        // SE EU NÃO DEFINIR NO FORMULÁRIO, É GERADO O SKU AUTOMATICAMENTE
        if (empty(($_POST['sku'])) || $_POST['sku'] == NULL) {

            $SKU = rand(13, 13);
            $SKU = substr(mt_rand() . mt_rand() . mt_rand(), 0, 12);

            $_has_product = $this->dao('Core', 'Produto')->countOcurrence('*', [
                'SKU',
                '=',
                $SKU
            ]);

            if ($_has_product == 0) {
                $produto['sku'] = $SKU;
            }
        }

        // CUPOM
        if ($_POST['cupom_desconto']) {
            $produto['cupom_desconto'] = $_POST['cupom_desconto'];
        } else {
            $produto['cupom_desconto'] = NULL;
        }

        // LINKCUPOM
        if ($_POST['link_compra_upnid_cupom']) {
            $produto['link_compra_upnid_cupom'] = $_POST['link_compra_upnid_cupom'];
        } else {
            $produto['link_compra_upnid_cupom'] = NULL;
        }

        // PIXEL
        if ($_POST['pixel']) {
            $produto['pixel'] = $_POST['pixel'];
        }

        // NOME PRODUTO
        if ($_POST['nome_produto']) {
            $produto['nome_produto'] = $_POST['nome_produto'];
        }

        // PESO LIQUIDO
        if ($_POST['peso_liquido']) {
            $produto['peso_liquido'] = ValidateUtil::paraFloat($_POST['peso_liquido']);
        }

        // PEDO BRUTO
        if ($_POST['peso_bruto']) {
            $produto['peso_bruto'] = ValidateUtil::paraFloat($_POST['peso_bruto']);
        }

        // CATEGORIA
        if ($_POST['id_categoria']) {
            $produto['id_categoria'] = $_POST['id_categoria'];
        }

        // MARCA
        if ($_POST['id_marca'] && $_POST['cad_marca'] == NULL) {
            $produto['id_marca'] = $_POST['id_marca'];
        } else if ($_POST['id_marca'] == NULL && $_POST['cad_marca'] != NULL) {
            $hasMarca = $this->dao('Core', 'Marca')->select([
                'id'
            ], [
                'nome',
                'LIKE',
                $_POST['cad_marca']
            ]);

            if (sizeof($hasMarca) == 0) {
                $idMarca = $this->dao('Core', 'Marca')->insert([
                    'nome' => $_POST['cad_marca']
                ]);

                $produto['id_marca'] = $idMarca;
            }
        }

        // FORNECEDOR
        if ($_POST['id_fornecedor']) {
            $produto['id_fornecedor'] = $_POST['id_fornecedor'];
        }

        // LINK PRODUTO
        if ($_POST['link_compra']) {
            $produto['link_compra'] = $_POST['link_compra'];
        }

        // LINK UPNID
        if ($_POST['link_compra_upnid']) {
            $produto['link_compra_upnid'] = $_POST['link_compra_upnid'];
        } else if ($_POST['link_compra_upnid'] == NULL) {
            $produto['link_compra_upnid'] = NULL;
        }

        // LINK UPNID PARA BOLETO
        if ($_POST['link_compra_upnid_boleto']) {
            $produto['link_compra_upnid_boleto'] = $_POST['link_compra_upnid_boleto'];
        } else if ($_POST['link_compra_upnid_boleto'] == NULL) {
            $produto['link_compra_upnid_boleto'] = NULL;
        }

        // LINK MERCADO PAGO
        if ($_POST['link_compra_mercado_pago']) {
            $produto['link_compra_mercado_pago'] = $_POST['link_compra_mercado_pago'];
        } else if ($_POST['link_compra_mercado_pago'] == NULL) {
            $produto['link_compra_mercado_pago'] = NULL;
        }

        // OBSERVAÇÃO
        if (isset($_POST['observacao']) && $_POST['observacao']) {
            $produto['observacao'] = $_POST['observacao'];
        }

        // NCM
        if ($_POST['ncm']) {
            $produto['ncm'] = $_POST['ncm'];
        }

        // UNIDADE
        if ($_POST['unidade']) {
            $produto['unidade'] = (int) $_POST['unidade'];
        }

        // Redução IVA ST
        if ($_POST['reducao_iva_st']) {
            $produto['reducao_iva_st'] = $_POST['reducao_iva_st'];
        }

        // Cod URL Produto
        if ($_POST['descricao']) {
            $produto['cod_url_produto'] = seo($_POST['descricao']);
        }

        // Despacho
        if ($_POST['descricao_despacho']) {
            $produto['descricao_despacho'] = $_POST['descricao_despacho'];
        }

        // Prazo de Entrega
        if ($_POST['prazo_entrega']) {
            $produto['prazo_entrega'] = $_POST['prazo_entrega'];
        }

        // Descrição do Cabeçalho
        if ($_POST['descricao_cabecalho']) {
            $produto['descricao_cabecalho'] = $_POST['descricao_cabecalho'];
        }

        // Código de Barras
        if ($_POST['codigo_de_barras']) {
            $produto['codigo_de_barras'] = $_POST['codigo_de_barras'];
        }

        // Comprimento
        if ($_POST['comprimento']) {
            $produto['comprimento'] = $_POST['comprimento'];
        }

        // Largura
        if ($_POST['largura']) {
            $produto['largura'] = $_POST['largura'];
        }

        // Altura
        if ($_POST['altura']) {
            $produto['altura'] = $_POST['altura'];
        }

        // Sobre
        if ($_POST['sobre']) {
            $produto['sobre'] = $_POST['sobre'];
        }

        // Descrição
        if ($_POST['descricao']) {
            $produto['descricao'] = trim($_POST['descricao']);
        }

        if ($_POST['descricao'] != '' && $idProduto = $this->dao('Core', 'Produto')->insertORUpdate($produto)) {

            // QUANTO EU QUERO DUPLICAR O PRODUTO
            if (isset($_POST['duplicar']) && $_POST['duplicar'] == 1 && $_POST['descricao_dup'] != '') {
                unset($produto['id']);

                $NSKU = substr(mt_rand() . mt_rand() . mt_rand(), 0, 12);
                if ($produto['descricao'] != '') {
                    $produto['descricao'] = $_POST['descricao_dup'];
                    $produto['cod_url_produto'] = seo($_POST['descricao_dup']);
                    $produto['nome_produto'] = $_POST['descricao_dup'];
                    $produto['sku'] = $NSKU;
                }

                $newIdProduto = $this->dao('Core', 'Produto')->insert($produto);

                // DUP SIZE
                $tamanhosd = $this->dao('Core', 'TamanhoProduto')->select([
                    '*'
                ], [
                    [
                        'id_produto',
                        '=',
                        $_POST['id']
                    ]
                ]);

                if (sizeof($tamanhosd) != 0) {
                    foreach ($tamanhosd as $t) {
                        $this->dao('Core', 'TamanhoProduto')->insert([
                            'descricao' => $t['descricao'],
                            'valor' => ValidateUtil::paraFloat($t['valor']),
                            'custo' => ValidateUtil::paraFloat($t['custo']),
                            'sku' => ValidateUtil::paraFloat(substr(mt_rand() . mt_rand() . mt_rand(), 0, 12)),
                            'estoque' => 10,
                            'id_produto' => $newIdProduto
                        ]);
                    }
                }

                $dird = Configuration::PATH_PRODUTO . '/' . $newIdProduto;

                if (! is_dir($dird)) {
                    rmdir($dird);
                }

                if (isset($_FILES)) {
                    foreach ($_FILES as $key => $file) {
                        $filename = basename($file['name']);
                        move_uploaded_file($file['tmp_name'], $dird . '/' . $filename);
                    }
                }

                header("Location: ?m=sistema&c=produto&a=inserirEditar&id=$newIdProduto");
            }

            // SALVAR IMAGES DO PRODUTO
            $dir = Configuration::PATH_PRODUTO . '/' . $idProduto;
            $dirColors = Configuration::PATH_PRODUTO . '/' . $idProduto . '/cor';

            if (! is_dir($dir)) {
                rmdir($dir);
            }

            if (! is_dir($dirColors)) {
                rmdir($dirColors);
            }

            // CATEGORIA | MARCA
            if ($_POST['id_categoria'] && $_POST['id_marca']) {
                $has = $this->dao('Core', 'MarcaCategoria')->countOcurrence('*', [
                    [
                        'id_marca',
                        '=',
                        $_POST['id_marca']
                    ],
                    [
                        'id_categoria',
                        '=',
                        $_POST['id_categoria']
                    ]
                ]);

                if ($has == 0) {
                    $this->dao('Core', 'MarcaCategoria')->insert([
                        'id_marca' => $_POST['id_marca'],
                        'id_categoria' => $_POST['id_categoria']
                    ]);
                }
            }

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            if (! is_dir($dirColors)) {
                mkdir($dirColors);
            }

            // SALVAR CORES
            $cores = array(
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                12,
                13,
                14,
                15
            );

            if (isset($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    $filename = basename($file['name']);
                    if ($filename != '' && substr($key, 0, 4) == 'cors') {
                        $cores[substr($key, 5, 1)] = $_POST['cor_' . substr($key, 5, 1)];
                        move_uploaded_file($file['tmp_name'], $dirColors . '/' . $cores[substr($key, 5, 1)] . '.jpg');
                    } else if ($filename != '' && substr($key, 0, 4) != 'cors') {
                        move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);
                    }
                }
            }

            foreach ($cores as $kc => $name) {
                if (isset($_POST['cor_' . $kc]) && $_POST['cor_' . $kc]) {
                    $link_venda_cor = $_POST['link_venda_cor_' . $kc];
                    $hasColor = $this->dao('Core', 'CorProduto')->select([
                        '*'
                    ], [
                        [
                            'id_produto',
                            '=',
                            $idProduto
                        ],
                        [
                            'nome',
                            '=',
                            $_POST['cor_' . $kc]
                        ]
                    ]);

                    if (sizeof($hasColor) == 0) {
                        $this->dao('Core', 'CorProduto')->insert([
                            'id_produto' => $idProduto,
                            'link_venda' => $link_venda_cor,
                            'nome' => $_POST['cor_' . $kc],
                            'url_img' => 'data/products/' . $idProduto . '/cor/' . $name . '.jpg'
                        ]);
                    } else {
                        $this->dao('Core', 'CorProduto')->update([
                            'id_produto' => $idProduto,
                            'link_venda' => $link_venda_cor,
                            'nome' => $_POST['cor_' . $kc],
                            'url_img' => 'data/products/' . $idProduto . '/cor/' . $name . '.jpg'
                        ], [
                            [
                                'id_produto',
                                '=',
                                $idProduto
                            ],
                            [
                                'nome',
                                '=',
                                $_POST['cor_' . $kc]
                            ]
                        ]);
                    }
                }
            }

            // SALVAR TAMANHOS
            $tamanhos = array(
                1,
                2,
                3,
                4,
                5,
                6
            );

            foreach ($tamanhos as $t) {
                if (isset($_POST['tamanho_' . $t]) && $_POST['tamanho_' . $t]) {
                    $link_venda = $_POST['link_venda_' . $t];
                    $_valor = ValidateUtil::paraFloat($_POST['valor_tamanho_' . $t]);
                    $_custo = ValidateUtil::paraFloat($_POST['custo_tamanho_' . $t]);
                    $_sku = ValidateUtil::paraFloat($_POST['sku_' . $t]);
                    $_estoque = ValidateUtil::paraFloat($_POST['estoque_' . $t]);
                    $_venda = ($_valor / 100) * 100;

                    $_venda = (int) $_venda;
                    $_venda = $_venda + 0.90;

                    $hasSize = $this->dao('Core', 'TamanhoProduto')->select([
                        '*'
                    ], [
                        [
                            'id_produto',
                            '=',
                            $idProduto
                        ],
                        [
                            'descricao',
                            '=',
                            $_POST['tamanho_' . $t]
                        ]
                    ]);

                    if (sizeof($hasSize) == 0) {
                        $this->dao('Core', 'TamanhoProduto')->insert([
                            'descricao' => $_POST['tamanho_' . $t],
                            'valor' => $_venda,
                            'custo' => $_custo,
                            'sku' => $_sku,
                            'estoque' => $_estoque,
                            'link_venda' => $link_venda,
                            'id_produto' => $idProduto
                        ]);
                    } else {
                        $this->dao('Core', 'TamanhoProduto')->update([
                            'descricao' => $_POST['tamanho_' . $t],
                            'valor' => $_venda,
                            'custo' => $_custo,
                            'sku' => $_sku,
                            'estoque' => $_estoque,
                            'link_venda' => $link_venda,
                            'id_produto' => $idProduto
                        ], [
                            [
                                'id_produto',
                                '=',
                                $idProduto
                            ],
                            [
                                'descricao',
                                '=',
                                $_POST['tamanho_' . $t]
                            ]
                        ]);
                    }
                }
            }

            // SkyHub
            if (isset($_POST['skyhub']) && $_POST['skyhub'] == 'on') {

                // Associar produto a SkyHub
                $api = new Api(EMAIL_SKYHUB, SENHA_SKYHUB);

                $requestHandler = $api->product();

                // IMAGS
                $images = [];
                $path = Configuration::PATH_PRODUTO . '/' . $_POST['id'];
                $dir = dir($path);
                if ($dir != '') {
                    while ($arquivo = $dir->read()) {
                        $ar = explode('.', $arquivo);
                        if (strlen($arquivo) > 3 && sizeof($ar) > 1) {
                            $images[] = 'https://www.shopvitas.com.br/data/products/' . $_POST['id'] . '/' . $arquivo;
                        }
                    }
                }

                $attributes = array(
                    'sku' => $_POST['sku'],
                    'name' => $_POST['descricao'],
                    'description' => $_POST['descricao'],
                    'status' => 'enabled',
                    'qty' => 1000,
                    'promotional_price' => 0,
                    'brand' => $this->dao('Core', 'Categoria')->getField('nome', $_POST['id_marca'])
                    // 'nbm' => '11234567890'
                );

                if ($_POST['valor_venda_b2w']) {
                    $valor_venda_b2w_ = (int) ValidateUtil::paraFloat($_POST['valor_venda_b2w']);
                    $attributes['price'] = $valor_venda_b2w_ + 0.90;
                }

                if ($_POST['valor_compra']) {
                    $cost_ = ValidateUtil::paraFloat($_POST['valor_compra']);
                    $attributes['cost'] = $cost_;
                }

                if ($_POST['ean']) {
                    $attributes['ean'] = $_POST['ean'];
                }

                if ($_POST['largura']) {
                    $attributes['width'] = $_POST['largura'];
                }

                if ($_POST['comprimento']) {
                    $attributes['length'] = $_POST['comprimento'];
                }

                if ($_POST['altura']) {
                    $attributes['height'] = $_POST['altura'];
                }

                if ($_POST['peso_bruto']) {
                    $attributes['weight'] = ($_POST['peso_bruto'] / 1000);
                    // GRAMAS PARA KILOS
                }

                if ($_POST['sku'] != NULL) {

                    $response = $requestHandler->product($_POST['sku']);

                    if ($response->success()) {
                        $response = $requestHandler->update($_POST['sku'], $attributes, $images);
                    } else {
                        $response = $requestHandler->create($_POST['sku'], $attributes, $images);
                    }
                }
            }

            $msg = "";
            if ($_POST['id'] != NULL) {
                $msg = "Produto Editado com Sucesso";
            } else {
                $msg = "Produto Cadastrado com Sucesso";
            }

            $this->renderView("index", [
                'produtos' => $this->dao('Core', 'Produto')
                    ->select([
                    '*'
                ]),
                'error' => FALSE,
                'msg' => $msg
            ], "Produto");
        } else {
            $this->renderView("index", [
                'produtos' => $this->dao('Core', 'Produto')
                    ->select([
                    '*'
                ]),
                'error' => TRUE,
                'msg' => 'Algum error ocorreuo durante o processo'
            ], "Produto");
        }
    }

    public function download_imagemAction()
    {
        $arquivo = "data/products/" . $_GET["produto"] . "/" . $_GET["imagem"];

        if (isset($arquivo) && file_exists($arquivo)) {
            switch (strtolower(substr(strrchr(basename($arquivo), "."), 1))) {
                case "pdf":
                    $tipo = "application/pdf";
                    break;
                case "exe":
                    $tipo = "application/octet-stream";
                    break;
                case "zip":
                    $tipo = "application/zip";
                    break;
                case "doc":
                    $tipo = "application/msword";
                    break;
                case "xls":
                    $tipo = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $tipo = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $tipo = "image/gif";
                    break;
                case "png":
                    $tipo = "image/png";
                    break;
                case "jpg":
                    $tipo = "image/jpg";
                    break;
                case "mp3":
                    $tipo = "audio/mpeg";
                    break;
                case "php": // deixar vazio por seurança
                case "htm": // deixar vazio por seurança
                case "html": // deixar vazio por seurança
            }
            header("Content-Type: " . $tipo);
            header("Content-Length: " . filesize($arquivo));
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            readfile($arquivo); // lê o arquivo
            exit(); // aborta pós-ações
        }
    }

    public function catalogoXmlAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            [
                'ativo',
                '=',
                TRUE
            ]
            // [
            // 'id_categoria',
            // 'IN',
            // [
            // 2,
            // 3,
            // 4,
            // 5,
            // 6,
            // 8,
            // 9
            // ]
            // ]
        ]);

        $itens = '';

        foreach ($produtos as $p) {
            $idProduto = $p['id'];
            $descricao = $p['descricao'];
            $descricaoSeo = seo($p['descricao']);
            $price = number_format($p['valor_venda'], 2);
            $dozeX = number_format($p['valor_venda'] / 12, 2);
            $idCategoria = $p['id_categoria'];
            $categoria = $this->dao('Core', 'Categoria')->getField('descricao', $idCategoria);
            $sku = $p['sku'];
            $sexo = 'Female';

            $cateriaMulher = [
                3,
                5
            ];

            $cateriaHomem = [
                4,
                5,
                6
            ];

            if (in_array($p['id_categoria'], $cateriaMulher)) {
                $sexo = 'Female';
            }

            if (in_array($p['id_categoria'], $cateriaHomem)) {
                $sexo = 'Male';
            }

            $itens .= <<<XML
            <item>
            <title>
            <![CDATA[ $descricao ]]>
            </title>
            <g:id>$idProduto</g:id>
            <g:item_group_id></g:item_group_id>
            <g:availability>in stock</g:availability>
            <g:brand>
            <![CDATA[ Shopvitas ]]>
            </g:brand>
            <g:condition>new</g:condition>
            <description>
            <![CDATA[ $descricao ]]>
            </description>
            <g:image_link>https://www.shopvitas.com.br/data/products/$idProduto/principal.jpg</g:image_link>
            <link>https://www.shopvitas.com.br/produto/$idProduto/$descricaoSeo</link>
            <g:mpn>
            <![CDATA[ $sku ]]>
            </g:mpn>
            <g:price>$price BRL</g:price>
            <g:installment>
            <g:months>12</g:months>
            <g:amount>$dozeX BRL</g:amount>
            </g:installment>
            <g:product_type>
            <![CDATA[ Calçado ]]>
            </g:product_type>
            <g:quantity>1000</g:quantity>
            <g:age_group>Adult</g:age_group>
            <g:gender>$sexo</g:gender>
            <g:google_product_category>
            <![CDATA[ $categoria ]]>
            </g:google_product_category>
            </item>
            XML;
        }

        $xml = <<<XML
        <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
        <channel>
        <title>Catálogo Shopvitas</title>
        <description><![CDATA[ ]]></description>
        <link>http://www.shopvitas.com.br</link>
        $itens
        </channel>
        </rss>
        XML;

        file_put_contents('data/produtos.xml', $xml);
        header('Content-Type: text/xml');
        $xml = new \SimpleXMLElement($xml);
    }

    public function deletarImagemAction()
    {
        $idProduto = Request::get('idProduto');
        $imagem = Request::get('imagem');
        $imagem = "data/products/" . $idProduto . "/" . $imagem;
        $sucess = false;
        if (file_exists($imagem)) {
            unlink($imagem);
            $sucess = true;
        }

        echo $sucess;
    }
}
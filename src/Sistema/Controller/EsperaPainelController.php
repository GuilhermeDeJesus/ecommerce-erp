<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\ValidateUtil;

class PainelController extends AbstractController
{

    public function __construct()
    {
        $this->isAdmin(TRUE);
    }

    public function filter_dashAction()
    {
        $this->Action($_GET['situacao'], $_GET['produto'], $_GET['categoria'], $_GET['de'], $_GET['ate'], $_GET['meta_faturamento']);
    }

    public function Action($situacao = FALSE, $produto = FALSE, $categoria = FALSE, $de = FALSE, $ate = FALSE, $previsao_faturamento = FALSE)
    {
        // By Filter Dashboard
        $_where = ' AND YEAR(p.data) = "' . date('Y') . '"';
        $_where_today = ' AND p.data = "' . date('Y-m-d') . '"';
        $_where_situacao = ' p.id_situacao_pedido = 2 ';

        $_where_only_data = ' AND YEAR(p.data) = "' . date('Y') . '"';
        $_where_only_product = NULL;
        $_where_only_product_facebook_ads = NULL;

        // FILTER :> STATUS PEDIDOS
        if (is_array($situacao) && sizeof($situacao) != 0) {
            $idSit = implode(',', $situacao);
            $_where_situacao = ' p.id_situacao_pedido IN(' . $idSit . ') ';
        }

        // FILTER :> DATA
        if ($de && $ate && $de != $ate) {
            $_where = ' AND p.data BETWEEN "' . $de . '" AND "' . $ate . '"';
            $_where_only_data = ' AND p.data BETWEEN "' . $de . '" AND "' . $ate . '"';
            $_where_today = ' AND p.data BETWEEN "' . $de . '" AND "' . $ate . '"';
        } else if ($de && $ate && $de == $ate) {
            $_where = ' AND p.data = "' . $de . '"';
            $_where_only_data = ' AND p.data = "' . $de . '"';
            $_where_today = ' AND p.data BETWEEN "' . $de . '" AND "' . $ate . '"';
        } else if ($de && ! $ate) {
            $_where = ' AND p.data = "' . $de . '"';
            $_where_only_data = ' AND p.data = "' . $de . '"';
            $_where_today = ' AND p.data BETWEEN "' . $de . '" AND "' . $ate . '"';
        }

        // PRODUTO E CATEGORIA
        if (is_array($produto) && sizeof($produto) != 0 && is_array($categoria) && sizeof($categoria) != 0) {

            // FILHOS
            foreach ($categoria as $ct) {
                $catt = self::dao('Core', 'Categoria')->select([
                    '*'
                ], [
                    'categoria_pai',
                    '=',
                    $ct
                ]);

                if (sizeof($catt) != 0) {
                    $categoria[] = $catt[0]['id'];
                }
            }

            $_cats = self::dao('Core', 'Produto')->select([
                '*'
            ], [
                'id_categoria',
                'IN',
                $categoria
            ]);

            $ids = [];
            if (sizeof($_cats) != 0) {
                foreach ($_cats as $cat) {
                    $ids[] = $cat['id'];
                }
            }

            $newsIds = array_merge($produto, $ids);

            $idS = implode(',', $newsIds);

            $_where .= ' AND i.id_produto IN(' . $idS . ')';
            $_where_only_product = ' AND i.id_produto IN (' . $idS . ')';
            $_where_today .= ' AND i.id_produto IN (' . $idS . ')';

            $_gastos = self::dao('Core', 'Lancamento')->select([
                '*'
            ], [
                'id_produto',
                'IN',
                $newsIds
            ]);

            if (sizeof($_gastos) != 0) {
                $_where_only_product_facebook_ads = ' AND d.id IN (' . $idS . ')';
            }

            // SOMENTE CATEGORIA
        } else if (is_array($categoria) && sizeof($categoria) != 0 && ! is_array($produto)) {

            // FILHOS
            foreach ($categoria as $ct) {
                $catt = self::dao('Core', 'Categoria')->select([
                    '*'
                ], [
                    'categoria_pai',
                    '=',
                    $ct
                ]);

                if (sizeof($catt) != 0) {
                    $categoria[] = $catt[0]['id'];
                }
            }

            $_cats = self::dao('Core', 'Produto')->select([
                'id',
                'id_categoria'
            ], [
                'id_categoria',
                'IN',
                $categoria
            ]);

            $ids = [];
            if (sizeof($_cats) != 0) {
                foreach ($_cats as $cat) {
                    $ids[] = $cat['id'];
                }
            }

            $idS = implode(',', $ids);

            $_where .= ' AND i.id_produto IN(' . $idS . ')';
            $_where_only_product = ' AND i.id_produto IN (' . $idS . ')';
            $_where_today .= ' AND i.id_produto IN (' . $idS . ')';

            $_gastos = self::dao('Core', 'Lancamento')->select([
                '*'
            ], [
                'id_produto',
                'IN',
                $ids
            ]);

            if (sizeof($_gastos) != 0) {
                $_where_only_product_facebook_ads = ' AND d.id IN (' . $idS . ')';
            }

            // SOMENTE PRODUTO
        } else if (is_array($produto) && sizeof($produto) != 0 && ! is_array($categoria)) {

            $idS = implode(',', $produto);

            $_where .= ' AND i.id_produto IN(' . $idS . ')';
            $_where_only_product = ' AND i.id_produto IN (' . $idS . ')';
            $_where_today .= ' AND i.id_produto IN (' . $idS . ')';

            $_gastos = self::dao('Core', 'Lancamento')->select([
                '*'
            ], [
                'id_produto',
                'IN',
                $produto
            ]);

            if (sizeof($_gastos) != 0) {
                $_where_only_product_facebook_ads = ' AND d.id IN (' . $idS . ')';
            }
        }

        $_wt = explode('AND', $_where_today);
        if (! $de && ! $ate && is_array($produto) || is_array($categoria) && sizeof($_wt) == 3) {
            $_where_today = 'AND' . $_wt[2];
        }

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Vendas
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $resumo_geral = "Vendas de " . date('Y');

        $_vendas = [

            'Faturamento' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _vendas_total_aprovadas FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND YEAR(p.data) = "' . date("Y") . '"'),

            'Pedidos Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _pedidos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2, 6) AND YEAR(p.data) = "' . date("Y") . '"'),
            'Pedidos Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _pedidos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2, 6) AND YEAR(p.data) = "' . date("Y") . '"'),

            'Cartão Aprovado Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _cartoes_aprovados_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Cartao" AND YEAR(p.data) = "' . date("Y") . '"'),
            'Cartão Aprovado Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _cartoes_aprovados_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Cartao" AND YEAR(p.data) = "' . date("Y") . '"'),

            'Boleto Pendente Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _quantidade_boletos_pendentes FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 1 AND p.tipo_pagamento = "Boleto" AND YEAR(p.data) = "' . date("Y") . '"'),
            'Boleto Pendente Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _valor_boletos_pendentes FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 1 AND p.tipo_pagamento = "Boleto" AND YEAR(p.data) = "' . date("Y") . '"'),

            'Boleto Pago Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _quantidade_boletos_pagos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Boleto" AND YEAR(p.data) = "' . date("Y") . '"'),
            'Boleto Pago Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _valor_boletos_pagos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Boleto" AND YEAR(p.data) = "' . date("Y") . '"'),

            'Prejuízos (Chargeback/Reembolso) Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _prejus_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (4, 5) AND YEAR(p.data) = "' . date("Y") . '"'),
            'Prejuízos (Chargeback/Reembolso) Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _prejus_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (4, 5) AND YEAR(p.data) = "' . date("Y") . '"'),

            'Prejuízos Prod Env (Chargeback/Reembolso) Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _prejus_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_pedido_status_fornecedor = 2 AND p.id_situacao_pedido IN (4, 5) AND YEAR(p.data) = "' . date("Y") . '"'),
            'Prejuízos Prod Env (Chargeback/Reembolso) Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _prejus_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_pedido_status_fornecedor = 2 AND p.id_situacao_pedido IN (4, 5) AND YEAR(p.data) = "' . date("Y") . '"'),

            'Lucro Líquido' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.lucro) as _lucro FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND YEAR(p.data) = "' . date("Y") . '"'),
            'Lucro Desejado' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.lucro) as _lucro_desejado FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2) AND YEAR(p.data) = "' . date("Y") . '"'),

            'Facebook Ads' => $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as _valor FROM lancamento p WHERE p.id_tipo_lancamento = 1 AND YEAR(p.data) = "' . date("Y") . '"')
        ];

        if (is_array($produto) || is_array($categoria) || $de != NULL || $ate != NULL) {
            $_vendas = [];
            $resumo_geral = "Vendas " . DateUtil::dateLiteralShort($de, FALSE) . "/ " . DateUtil::dateLiteralShort($ate, FALSE);

            $innerJoinFacebook = '';
            if ($_where_only_product_facebook_ads != NULL) {
                $innerJoinFacebook = 'INNER JOIN produto d ON d.id = p.id_produto';
            } else if ($_where_only_product_facebook_ads == NULL && $de == NULL || $ate == NULL) {
                $_where_only_product_facebook_ads = ' AND p.id_produto IS NULL';
            } else if ($_where_only_product_facebook_ads == NULL && (is_array($categoria) || is_array($produto))) {
                $_where_only_product_facebook_ads = ' AND p.id_produto IS NULL';
            }

            lp('SELECT SUM(p.valor) as _vendas_total_aprovadas FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_only_product . $_where_only_data . '');

            $_vendas = [

                'Faturamento' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _vendas_total_aprovadas FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_only_product . $_where_only_data . ''),

                'Pedidos Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _pedidos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2, 6) ' . $_where_only_product . $_where_only_data . ''),
                'Pedidos Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _pedidos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2, 6) ' . $_where_only_product . $_where_only_data . ''),

                'Cartão Aprovado Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _cartoes_aprovados_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Cartao" ' . $_where_only_product . $_where_only_data . ''),
                'Cartão Aprovado Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _cartoes_aprovados_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Cartao" ' . $_where_only_product . $_where_only_data . ''),

                'Boleto Pendente Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _quantidade_boletos_pendentes FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 1 AND p.tipo_pagamento = "Boleto" ' . $_where_only_product . $_where_only_data . ''),
                'Boleto Pendente Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _valor_boletos_pendentes FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 1 AND p.tipo_pagamento = "Boleto" ' . $_where_only_product . $_where_only_data . ''),

                'Boleto Pago Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _quantidade_boletos_pagos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Boleto" ' . $_where_only_product . $_where_only_data . ''),
                'Boleto Pago Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _valor_boletos_pagos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND p.tipo_pagamento = "Boleto" ' . $_where_only_product . $_where_only_data . ''),

                'Prejuízos (Chargeback/Reembolso) Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _prejus_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (4, 5) ' . $_where_only_product . $_where_only_data . ''),
                'Prejuízos (Chargeback/Reembolso) Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _prejus_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (4, 5) ' . $_where_only_product . $_where_only_data . ''),

                'Prejuízos Prod Env (Chargeback/Reembolso) Quantidade' => $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.valor) as _prejus_quantidade FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_pedido_status_fornecedor = 2 AND p.id_situacao_pedido = 4 ' . $_where_only_product . $_where_only_data . ''),
                'Prejuízos Prod Env (Chargeback/Reembolso) Valor' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as _prejus_valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_pedido_status_fornecedor = 2 AND p.id_situacao_pedido = 4 ' . $_where_only_product . $_where_only_data . ''),

                'Lucro Líquido' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.lucro) as _lucro FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_only_product . $_where_only_data . ''),
                'Lucro Desejado' => $this->dao('Core', 'Pedido')->query('SELECT SUM(p.lucro) as _lucro_desejado FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2) ' . $_where_only_product . $_where_only_data . ''),

                'Facebook Ads' => $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as _valor FROM lancamento p ' . $innerJoinFacebook . ' WHERE p.id_tipo_lancamento = 1 ' . $_where_only_product_facebook_ads . $_where_only_data . '')
            ];
        }

        $qtt_boleto_pago = $_vendas['Boleto Pago Quantidade'][0]['_quantidade_boletos_pagos'];
        $qtt_boleto_pendente = $_vendas['Boleto Pendente Quantidade'][0]['_quantidade_boletos_pendentes'];
        $qtt_cartao_pago = $_vendas['Cartão Aprovado Quantidade'][0]['_cartoes_aprovados_quantidade'];

        $ROI = ($_vendas['Lucro Líquido'][0]['_lucro'] - $_vendas['Facebook Ads'][0]['_valor']) / $_vendas['Facebook Ads'][0]['_valor'];
        $PERCENTUAL_CONVERSAO_BOLETO = ($qtt_boleto_pago / ($qtt_boleto_pendente + $qtt_boleto_pago)) * 100;
        $PERCENTUAL_CARTAO = ($qtt_cartao_pago / ($qtt_boleto_pago + $qtt_boleto_pendente + $qtt_cartao_pago)) * 100;

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Fim Vendas
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $ultimo_dia = date("t", mktime(0, 0, 0, date("m"), '01', date("Y")));

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Faturamento
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $_faturamento = [];
        $diasMes = [];
        foreach (range(1, $ultimo_dia) as $dia) {
            $get = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as valor, SUM(p.frete) as frete FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data = "' . date("Y-m-" . $dia) . '"');
            $_faturamento[$dia] = (int) round($get[0]['valor'] + $get[0]['frete']);
            $diasMes[] = $dia . '/' . DateUtil::monthLiteral(date('m'));
        }

        if (is_array($produto) || is_array($categoria) || $de != NULL || $ate != NULL) {
            $_faturamento = [];
            $diasMes = [];
            $fat = $this->dao('Core', 'Pedido')->query('SELECT MONTH(p.data) as mes, YEAR(p.data) as ano, SUM(p.valor) as valor, SUM(p.frete) as frete FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . $_where_only_product . $_where_only_data . ' GROUP BY MONTH(p.data)');

            foreach ($fat as $f) {
                $_faturamento[$f['mes'] . '/' . $f['ano']] = (int) round($f['valor']);
                $diasMes[] = DateUtil::monthLiteral($f['mes']) . '/' . $f['ano'];
            }
        }

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Fim -> Faturamento
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Capturar Pedidos por Faixa Etária dos Últimos 90 dias
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ultimos_90_dias = date('Y-m-d', strtotime('-90 days', strtotime(date('d-m-Y'))));
        $pedidoPorIdade = $this->dao('Core', 'Pedido')->query('SELECT p.id_cliente, p.valor FROM pedido p WHERE ' . $_where_situacao . ' AND p.data > "' . $ultimos_90_dias . '"');

        // If Set Filter With Product, update my SQL
        if (is_array($produto) || is_array($categoria) || $de != NULL || $ate != NULL) {
            $pedidoPorIdade = $this->dao('Core', 'Pedido')->query('SELECT p.id_cliente, p.valor FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . $_where_only_product . $_where_only_data . '');
        }

        $ultimos_18_anos = date('Y-m-d', strtotime('-18 years', strtotime(date('d-m-Y'))));
        $ultimos_24_anos = date('Y-m-d', strtotime('-24 years', strtotime(date('d-m-Y'))));
        $ultimos_25_anos = date('Y-m-d', strtotime('-25 years', strtotime(date('d-m-Y'))));
        $ultimos_34_anos = date('Y-m-d', strtotime('-34 years', strtotime(date('d-m-Y'))));
        $ultimos_35_anos = date('Y-m-d', strtotime('-35 years', strtotime(date('d-m-Y'))));
        $ultimos_44_anos = date('Y-m-d', strtotime('-44 years', strtotime(date('d-m-Y'))));
        $ultimos_45_anos = date('Y-m-d', strtotime('-45 years', strtotime(date('d-m-Y'))));
        $ultimos_54_anos = date('Y-m-d', strtotime('-54 years', strtotime(date('d-m-Y'))));
        $ultimos_55_anos = date('Y-m-d', strtotime('-55 years', strtotime(date('d-m-Y'))));
        $ultimos_64_anos = date('Y-m-d', strtotime('-64 years', strtotime(date('d-m-Y'))));
        $ultimos_65_anos = date('Y-m-d', strtotime('-65 years', strtotime(date('d-m-Y'))));
        $ultimos_74_anos = date('Y-m-d', strtotime('-74 years', strtotime(date('d-m-Y'))));

        $pedidos_clientes_com_18_24_anos = [];
        $pedidos_clientes_com_25_34_anos = [];
        $pedidos_clientes_com_35_44_anos = [];
        $pedidos_clientes_com_45_54_anos = [];
        $pedidos_clientes_com_55_64_anos = [];
        $pedidos_clientes_com_65_74_anos = [];

        foreach ($pedidoPorIdade as $pdido) {
            $clientes_com_18_24_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_24_anos,
                        $ultimos_18_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_18_24_anos) == 1) {
                $pedidos_clientes_com_18_24_anos[] = $pdido['valor'];
            }

            $clientes_com_25_34_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_34_anos,
                        $ultimos_25_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_25_34_anos) == 1) {
                $pedidos_clientes_com_25_34_anos[] = $pdido['valor'];
            }

            $clientes_com_35_44_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_44_anos,
                        $ultimos_35_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_35_44_anos) == 1) {
                $pedidos_clientes_com_35_44_anos[] = $pdido['valor'];
            }

            $clientes_com_45_54_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_54_anos,
                        $ultimos_45_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_45_54_anos) == 1) {
                $pedidos_clientes_com_45_54_anos[] = $pdido['valor'];
            }

            $clientes_com_55_64_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_64_anos,
                        $ultimos_55_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_55_64_anos) == 1) {
                $pedidos_clientes_com_55_64_anos[] = $pdido['valor'];
            }

            $clientes_com_65_74_anos = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $pdido['id_cliente']
                ],
                [
                    'data_nascimento',
                    'BETWEEN',
                    [
                        $ultimos_74_anos,
                        $ultimos_65_anos
                    ]
                ]
            ]);

            if (sizeof($clientes_com_65_74_anos) == 1) {
                $pedidos_clientes_com_65_74_anos[] = $pdido['valor'];
            }
        }

        $faturamentoIdade = [
            '18_24' => $pedidos_clientes_com_18_24_anos,
            '25_34' => $pedidos_clientes_com_25_34_anos,
            '35_44' => $pedidos_clientes_com_35_44_anos,
            '45_54' => $pedidos_clientes_com_45_54_anos,
            '55_64' => $pedidos_clientes_com_55_64_anos,
            '65_74' => $pedidos_clientes_com_65_74_anos
        ];

        // END FATURAMENTO POR IDADE

        // RESUMO PEDIDOS HOJE OU PELO FILTRO
        $resumo = "Resumo " . date('d') . ' de ' . DateUtil::monthLiteral(date('m'));
        $situacoes_pedidos_hoje = [
            'aprovado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data = "' . date('Y-m-d') . '"'),
            'analise' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 6 AND p.data = "' . date('Y-m-d') . '"'),
            'boletoGerado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 1 AND p.data = "' . date('Y-m-d') . '"'),
            'chargeback' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 4 AND p.data = "' . date('Y-m-d') . '"'),
            'recusado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 3 AND p.data = "' . date('Y-m-d') . '"')
        ];

        if (is_array($produto) || is_array($categoria) || $de != NULL || $ate != NULL) {
            $resumo = "Resumo " . DateUtil::dateLiteralShort($de, FALSE) . "/ " . DateUtil::dateLiteralShort($ate, FALSE);
            $situacoes_pedidos_hoje = [
                'aprovado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_only_product . $_where_only_data . ''),
                'analise' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 6 ' . $_where_only_product . $_where_only_data . ''),
                'boletoGerado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 1 ' . $_where_only_product . $_where_only_data . ''),
                'chargeback' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 4 ' . $_where_only_product . $_where_only_data . ''),
                'recusado' => $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 3 ' . $_where_only_product . $_where_only_data . '')
            ];
        }

        // FIM RESUMO PEDIDOS HOJE OU PELO FILTRO

        // VENDAS POR ESTADO
        $_estados = estadosBrasileiros();
        $_pagosPorEstado = [];
        foreach ($_estados as $uf => $estado) {
            $vendasPorEstado = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN endereco e ON p.id_endereco = e.id INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . ' AND e.uf = "' . $uf . '" ' . $_where . '');
            if ($vendasPorEstado[0]['total'] != 0) {
                $_pagosPorEstado[] = [
                    'name' => $estado,
                    'value' => (int) $vendasPorEstado[0]['total']
                ];
            }
        }

        usort($_pagosPorEstado, function ($a, $b) {
            return $b['value'] - $a['value'];
        });

        // FIM VENDAS POR ESTADO

        // VENDAS POR SEXO
        $total_vendas_sexo_feminino = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . ' AND c.sexo = "F" ' . $_where . '');
        $total_vendas_sexo_masculino = $this->dao('Core', 'Pedido')->query('SELECT count(p.id) as total FROM pedido p INNER JOIN cliente c ON p.id_cliente = c.id INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . ' AND c.sexo = "M" ' . $_where . '');

        $vendas_por_sexo = [];
        $vendas_por_sexo[] = [
            'name' => 'Masculino',
            'value' => (int) $total_vendas_sexo_masculino[0]['total']
        ];

        $vendas_por_sexo[] = [
            'name' => 'Feminino',
            'value' => (int) $total_vendas_sexo_feminino[0]['total']
        ];
        // FIM VENDAS POR SEXO

        // VENDAS POR DISPOSITIVO
        $vendas_por_dispositivo = [];
        foreach ([
            'Desktop',
            'iPhone',
            'Android'
        ] as $d) {
            $vendasPorDispositivo = $this->dao('Core', 'Pedido')->query('SELECT count(*) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . ' AND p.dispositivo =  "' . $d . '" ' . $_where . '');
            $vendas_por_dispositivo[] = [
                'name' => $d,
                'value' => (int) $vendasPorDispositivo[0]['total']
            ];
        }
        // FIM VENDAS POR DISPOSITIVO

        // VENDAS POR MIDIA
        $vendas_por_midia = [];
        foreach ([
            'Instagram',
            'Facebook',
            'Google Chrome'
        ] as $m) {
            $vendasPorMidia = $this->dao('Core', 'Pedido')->query('SELECT count(*) as total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE ' . $_where_situacao . ' AND p.social_midia =  "' . $m . '" ' . $_where . '');
            $vendas_por_midia[] = [
                'name' => $m,
                'value' => (int) $vendasPorMidia[0]['total']
            ];
        }
        // FIM VENDAS POR MIDIA

        if (! $previsao_faturamento) {
            $previsao_faturamento = $_vendas['Faturamento'][0]['_vendas_total_aprovadas'];
        }

        // INFORMAÇÕES TOPO
        $totalPedidos = $this->dao('Core', 'Pedido')->query('SELECT count(*) as total_pedidos FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido IN (1, 2, 6) AND p.codigo_transacao IS NOT NULL ' . $_where_today . '');
        $totalPedidosAprovados = $this->dao('Core', 'Pedido')->query('SELECT count(*) as total_aprovados FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_today . '');
        $valorPedidosAprovados = $this->dao('Core', 'Pedido')->query('SELECT SUM(valor) as valor_total FROM pedido p INNER JOIN item_pedido i ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 ' . $_where_today . '');

        $data = [
            '_vendas' => $_vendas,
            '_ROI' => substr($ROI, 0, 4),
            '_PERCENTUAL_CONVERSAO_BOLETO' => substr($PERCENTUAL_CONVERSAO_BOLETO, 0, 5),
            '_PERCENTUAL_CARTAO' => substr($PERCENTUAL_CARTAO, 0, 5),
            '_produtos' => $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'ativo',
                '=',
                TRUE
            ]),
            '_categorias' => $this->dao('Core', 'Categoria')->select([
                '*'
            ]),
            '_situacoes' => $this->dao('Core', 'SituacaoPedido')->select([
                '*'
            ]),
            '_vendas_por_estado' => $_pagosPorEstado,
            '_vendas_por_sexo' => $vendas_por_sexo,
            '_vendas_por_dispositivo' => $vendas_por_dispositivo,
            '_vendas_por_midia' => $vendas_por_midia,
            '_dias_mes' => json_encode(array_values($diasMes)),
            '_total_pedidos_ultimos_90_dias' => sizeof($pedidoPorIdade),
            '_faturamento' => json_encode(array_values($_faturamento)),
            '_faixa_etaria' => $faturamentoIdade,
            '_total_hoje' => $situacoes_pedidos_hoje,
            '_titulo_resumo' => $resumo,
            '_resumo_geral' => $resumo_geral,
            '_valor_meta_faturamento' => ValidateUtil::paraFloat($previsao_faturamento),
            'qtd_cliente' => $this->dao('Cliente', 'Cliente')->countOcurrence('*'),
            'produto_disponivel' => $this->dao('Core', 'Produto')->countOcurrence('*', [
                'ativo',
                '=',
                TRUE
            ]),
            'pedidos_total' => $totalPedidos[0]['total_pedidos'],
            'pedidos_pago_total' => $totalPedidosAprovados[0]['total_aprovados'],
            'pedidos_pago_valor' => ValidateUtil::setFormatMoney($valorPedidosAprovados[0]['valor_total']),
            'pedidos_pago_hoje' => $this->dao('Core', 'Pedido')->countOcurrence('*', [
                [
                    [
                        'YEAR(data)',
                        '=',
                        date('Y')
                    ],
                    [
                        'MONTH(data)',
                        '=',
                        date('m')
                    ],
                    [
                        'DAY(data)',
                        '=',
                        date('d')
                    ]
                ],
                [
                    'id_situacao_pedido',
                    '=',
                    2
                ],
                [
                    'codigo_transacao',
                    '!=',
                    NULL
                ]
            ])
        ];

        $this->renderView("index", $data);
    }
}
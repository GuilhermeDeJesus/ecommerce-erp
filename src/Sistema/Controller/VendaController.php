<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\ValidateUtil;
use DateTime;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'krypitonite/src/Util/MessageUtil.php';
use Krypitonite\Mail\Email;
use Krypitonite\Util\MessageUtil;
use Configuration\Configuration;
use Krypitonite\Util\DateUtil;
use Store\Cliente\Dao\ClienteDAO;
use Krypitonite\Util\PaginationUtil;
use Click4Web\DeclaracaoConteudo\Entities\Pessoa;
use Click4Web\DeclaracaoConteudo\Core\ItemBag;
use Click4Web\DeclaracaoConteudo\DeclaracaoConteudo;
use Krypitonite\Util\PagarMeUtil;
use Krypitonite\Util\ClearsaleUtil;
use Krypitonite\Log\Log;
use Behat\Testwork\Output\Exception\PrinterException;
require_once 'src/Cliente/Dao/ClienteDAO.php';
require_once 'lib/simplexlsx-master/src/SimpleXLSX.php';
require_once 'krypitonite/src/Util/PaginationUtil.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Entities/Pessoa.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Core/ItemBag.php';
require_once 'lib/declaracao-conteudo-correios-master/src/DeclaracaoConteudo.php';
include_once ('lib/PHP_XLSXWriter-master/xlsxwriter.class.php');
require_once 'krypitonite/src/Util/PagarMeUtil.php';

class VendaController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $cartoes = $this->dao('Core', 'CartaoCliente')->select([
            '*'
        ]);

        $idsClientes = [];

        foreach ($cartoes as $c) {
            $idsClientes[] = $c['id_cliente'];
        }

        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'id_situacao_pedido',
                '=',
                2
            ],
            [
                'tipo_pagamento',
                '=',
                'Cartao'
            ],
            [
                'id_cliente',
                'IN',
                $idsClientes
            ]
        ]);

        $peds = [];
        foreach ($pedidos as $p) {

            $_dataNascimento = dao('Core', 'Cliente')->getField('data_nascimento', $p['id_cliente']);
            $_idade = DateUtil::calculateTimeDifferenceRaw($_dataNascimento, date('Y-m-d'));

            $peds[$p['valor']][] = [
                'Comprado' => $p['valor'],
                'Idade' => $_idade['years'],
                'link' => "Link do pedido: <br> <a href='https://www.shopvitas.com.br/?m=sistema&c=venda&a=form&num=" . $p['numero_pedido'] . "' target='new'>" . $p['numero_pedido'] . "<a/><br><br>"
            ];
        }

        ksort($peds);

        foreach ($peds as $p) {
            // if ($p[0]['Idade'] > 45) {
            print_r($p[0]['Comprado']);
            echo "<br>";
            print_r($p[0]['Idade']);
            echo "<br>";
            print_r($p[0]['link']);
            // }
        }
    }

    public function relatorioAction()
    {
        $taxa_clearSale = 2.24;

        // ANO
        $chargebackAno = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor + p.frete) as total FROM pedido p WHERE p.id_situacao_pedido = 4 AND p.id_pedido_status_fornecedor = 2 AND YEAR(p.data) = "' . date("Y") . '"');
        $gastoFacebookAno = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 1 AND YEAR(p.data) = "' . date("Y") . '"');
        $pagamentoFornecedorAnual = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 2 AND YEAR(p.data) = "' . date("Y") . '"');
        $clientesCadastradosAno = $this->dao('Core', 'Cliente')->query('SELECT COUNT(p.id) as total FROM cliente p WHERE YEAR(p.date_create) = "' . date("Y") . '"');
        $relatorioAnual = [];
        $totalPedidosFeitosAnual = $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 2 AND YEAR(p.data) = "' . date("Y") . '"');
        $freteAnual = [];
        foreach (range(1, 12) as $mes) {
            $r = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as valor, SUM(p.frete) as frete, MONTH(p.data) as mes FROM pedido p WHERE p.id_situacao_pedido = 2 AND YEAR(p.data) = "' . date("Y") . '" AND MONTH(p.data) = "' . $mes . '" GROUP BY MONTH(p.data)');
            if (sizeof($r) != 0) {
                $relatorioAnual[$mes] = str_replace(',', '', number_format(($r[0]['valor'] + $r[0]['frete']), 2));
            } else {
                $relatorioAnual[$mes] = 0;
            }
        }

        $pedidosDoAno = $this->dao('Core', 'Pedido')->query('SELECT * FROM pedido p WHERE p.id_situacao_pedido = 2 AND YEAR(p.data) = "' . date("Y") . '"');
        $itensPedidoTotalAnual = [];
        $lucroLiquidoAnual = [];
        foreach ($pedidosDoAno as $pa) {
            $item = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pa['id']
            ]);

            foreach ($item as $i) {
                $itensPedidoTotalAnual[] = $i['quantidade'];
            }

            // VERIFICA O VALOR TOTAL COBRADO
            $valorCobrado = $pa['valor'];
            $ValorTaxaClearSale = 0;
            if (! $pa['frete_gratis']) {
                $valorCobrado = $pa['valor'] + $pa['frete'];
            }

            // VERIFICAR SE O PEDIDO TIVER APROVARDO E SE SUSPEITO, NÃO ABATAR O VALOR DA TAXA NO LUCRO DO PEDIDO
            if ($pa['status_clear_sale'] == 'SUS') {
                $ValorTaxaClearSale = ($valorCobrado / 100) * $taxa_clearSale;
            }

            $freteAnual[] = $pa['frete'];
            $lucroLiquidoAnual[] = $pa['lucro'] + $ValorTaxaClearSale;
        }

        $lucroLiquidoAnual = array_sum($lucroLiquidoAnual) - $pagamentoFornecedorAnual[0]['total'];

        // MES PASSADO
        $chargebackMesPassado = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor + p.frete) as total FROM pedido p WHERE p.id_situacao_pedido = 4 AND p.id_pedido_status_fornecedor = 2 AND MONTH(p.data) = "' . (date("m") - 1) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $gastoFacebookMesPassado = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 1 AND MONTH(p.data) = "' . (date("m") - 1) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $pagamentoFornecedorMesPassado = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 2 AND MONTH(p.data) = "' . (date("m") - 1) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $clientesCadastradosMesPassado = $this->dao('Core', 'Cliente')->query('SELECT COUNT(p.id) as total FROM cliente p WHERE MONTH(p.date_create) = "' . (date("m") - 1) . '" AND YEAR(p.date_create) = "' . date("Y") . '"');
        $relatorioMesPassado = [];
        $totalPedidosFeitosMesPassado = $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 2 AND MONTH(p.data) = "' . (date("m") - 1) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $freteMesPassado = [];
        $ultimo_dia_mes_passado = date("t", mktime(0, 0, 0, (date("m") - 1), '01', date("Y"))); // Mágica, plim!
        foreach (range(1, $ultimo_dia_mes_passado) as $dia) {
            $dt = date('Y') . '-' . (date("m") - 1) . '-' . $dia;
            $r = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as valor, SUM(p.frete) as frete, DAY(p.data) as dia FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data = "' . $dt . '"');
            if (sizeof($r) != 0 && $r[0]['valor'] != 0) {
                $relatorioMesPassado[$dia] = str_replace(',', '', number_format(($r[0]['valor'] + $r[0]['frete']), 2));
            } else {
                $relatorioMesPassado[$dia] = 0;
            }
        }

        $pedidosMesPassado = $this->dao('Core', 'Pedido')->query('SELECT * FROM pedido p WHERE p.id_situacao_pedido = 2 AND MONTH(p.data) = "' . (date("m") - 1) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $itensPedidoTotalMesPassado = [];
        $lucroLiquidoMesPassado = [];
        foreach ($pedidosMesPassado as $pa) {
            $item = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pa['id']
            ]);

            foreach ($item as $i) {
                $itensPedidoTotalMesPassado[] = $i['quantidade'];
            }

            // VERIFICA O VALOR TOTAL COBRADO
            $valorCobrado = $pa['valor'];
            $ValorTaxaClearSale = 0;
            if (! $pa['frete_gratis']) {
                $valorCobrado = $pa['valor'] + $pa['frete'];
            }

            // VERIFICAR SE O PEDIDO TIVER APROVARDO E SE SUSPEITO, NÃO ABATAR O VALOR DA TAXA NO LUCRO DO PEDIDO
            if ($pa['status_clear_sale'] == 'SUS') {
                $ValorTaxaClearSale = ($valorCobrado / 100) * $taxa_clearSale;
            }

            $freteMesPassado[] = $pa['frete'];
            $lucroLiquidoMesPassado[] = $pa['lucro'] + $ValorTaxaClearSale;
        }

        $lucroLiquidoMesPassado = array_sum($lucroLiquidoMesPassado) - $gastoFacebookMesPassado[0]['total'];

        $diasMesPassado = [];
        foreach (range(1, $ultimo_dia_mes_passado) as $dia) {
            $diasMesPassado[] = $dia;
        }

        // ESSE MES
        $chargebackMesAtual = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor + p.frete) as total FROM pedido p WHERE p.id_situacao_pedido = 4 AND p.id_pedido_status_fornecedor = 2 AND MONTH(p.data) = "' . (date("m")) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $gastoFacebookMesAtual = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 1 AND MONTH(p.data) = "' . (date("m")) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $pagamentoFornecedorMesAtual = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 2 AND MONTH(p.data) = "' . (date("m")) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $clientesCadastradosMesAtual = $this->dao('Core', 'Cliente')->query('SELECT COUNT(p.id) as total FROM cliente p WHERE MONTH(p.date_create) = "' . (date("m")) . '" AND YEAR(p.date_create) = "' . date("Y") . '"');
        $relatorioMesAtual = [];
        $totalPedidosFeitosMesAtual = $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 2 AND MONTH(p.data) = "' . (date("m")) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $freteMesAtual = [];
        $ultimo_dia_mes_atual = date("t", mktime(0, 0, 0, (date("m") - 1), '01', date("Y"))); // Mágica, plim!
        foreach (range(1, $ultimo_dia_mes_atual) as $dia) {
            $dt = date('Y') . '-' . (date("m")) . '-' . $dia;
            $r = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as valor, SUM(p.frete) as frete, DAY(p.data) as dia FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data = "' . $dt . '"');
            if (sizeof($r) != 0 && $r[0]['valor'] != 0) {
                $relatorioMesAtual[$dia] = str_replace(',', '', number_format(($r[0]['valor'] + $r[0]['frete']), 2));
            } else {
                $relatorioMesAtual[$dia] = 0;
            }
        }

        $pedidosMesAtual = $this->dao('Core', 'Pedido')->query('SELECT * FROM pedido p WHERE p.id_situacao_pedido = 2 AND MONTH(p.data) = "' . (date("m")) . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $itensPedidoTotalMesAtual = [];
        $lucroLiquidoMesAtual = [];
        foreach ($pedidosMesAtual as $pa) {
            $item = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pa['id']
            ]);

            // VERIFICA O VALOR TOTAL COBRADO
            $valorCobrado = $pa['valor'];
            $ValorTaxaClearSale = 0;
            if (! $pa['frete_gratis']) {
                $valorCobrado = $pa['valor'] + $pa['frete'];
            }

            // VERIFICAR SE O PEDIDO TIVER APROVARDO E SE SUSPEITO, NÃO ABATAR O VALOR DA TAXA NO LUCRO DO PEDIDO
            if ($pa['status_clear_sale'] == 'SUS') {
                $ValorTaxaClearSale = ($valorCobrado / 100) * $taxa_clearSale;
            }

            foreach ($item as $i) {
                $itensPedidoTotalMesAtual[] = $i['quantidade'];
            }

            $freteMesAtual[] = $pa['frete'];
            $lucroLiquidoMesAtual[] = $pa['lucro'] + $ValorTaxaClearSale;
        }

        $lucroLiquidoMesAtual = array_sum($lucroLiquidoMesAtual) - $gastoFacebookMesAtual[0]['total'];

        $diasMesAtual = [];
        foreach (range(1, $ultimo_dia_mes_atual) as $dia) {
            $diasMesAtual[] = $dia;
        }

        // ULTIMOS 7 DIAS
        $data_ultimos_7_dias = date('Y-m-d', strtotime('-7 days', strtotime(date('d-m-Y'))));
        $chargebackMesUltimos7Dias = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor + p.frete) as total FROM pedido p WHERE p.id_situacao_pedido = 4 AND p.id_pedido_status_fornecedor = 2 AND p.data > "' . $data_ultimos_7_dias . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $gastoFacebookUltimos7Dias = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 1 AND p.data > "' . $data_ultimos_7_dias . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $pagamentoFornecedorUltimos7Dias = $this->dao('Core', 'Lancamento')->query('SELECT SUM(p.valor) as total FROM lancamento p WHERE p.id_tipo_lancamento = 2 AND p.data > "' . $data_ultimos_7_dias . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $clientesCadastradosUltimos7Dias = $this->dao('Core', 'Cliente')->query('SELECT COUNT(p.id) as total FROM cliente p WHERE p.date_create > "' . $data_ultimos_7_dias . '" AND YEAR(p.date_create) = "' . date("Y") . '"');
        $totalPedidosFeitosUltimos7Dias = $this->dao('Core', 'Pedido')->query('SELECT COUNT(p.id) as total FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data > "' . $data_ultimos_7_dias . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $freteUltimos7Dias = [];

        $pedidosUltimos7Dias = $this->dao('Core', 'Pedido')->query('SELECT * FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data > "' . $data_ultimos_7_dias . '" AND YEAR(p.data) = "' . date("Y") . '"');
        $itensPedidoTotalUltimos7Dias = [];
        $lucroLiquidoUltimos7Dias = [];
        foreach ($pedidosUltimos7Dias as $pa) {
            $item = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pa['id']
            ]);

            foreach ($item as $i) {
                $itensPedidoTotalUltimos7Dias[] = $i['quantidade'];
            }

            // VERIFICA O VALOR TOTAL COBRADO
            $valorCobrado = $pa['valor'];
            $ValorTaxaClearSale = 0;
            if (! $pa['frete_gratis']) {
                $valorCobrado = $pa['valor'] + $pa['frete'];
            }

            // VERIFICAR SE O PEDIDO TIVER APROVARDO E SE SUSPEITO, NÃO ABATAR O VALOR DA TAXA NO LUCRO DO PEDIDO
            if ($pa['status_clear_sale'] == 'SUS') {
                $ValorTaxaClearSale = ($valorCobrado / 100) * $taxa_clearSale;
            }

            $freteUltimos7Dias[] = $pa['frete'];
            $lucroLiquidoUltimos7Dias[] = $pa['lucro'] + $ValorTaxaClearSale;
        }

        $lucroLiquidoUltimos7Dias = array_sum($lucroLiquidoUltimos7Dias) - $gastoFacebookUltimos7Dias[0]['total'];

        $diasUltimos7Dias = [];
        $relatorioUltimos7Dias = [];
        foreach (range(1, 7) as $dia) {
            $pedidosDia = $this->dao('Core', 'Pedido')->query('SELECT SUM(p.valor) as valor, SUM(p.frete) as frete, p.data as data FROM pedido p WHERE p.id_situacao_pedido = 2 AND p.data = "' . date('Y-m-d', strtotime('-' . $dia . ' days', strtotime(date('d-m-Y')))) . '" AND YEAR(p.data) = "' . date("Y") . '"');
            if (sizeof($pedidosDia) != 0) {
                $relatorioUltimos7Dias[] = str_replace(',', '', number_format(($pedidosDia[0]['valor'] + $pedidosDia[0]['frete']), 2));
            } else {
                $relatorioUltimos7Dias[] = 0;
            }

            $diasUltimos7Dias[] = date('d/m/Y', strtotime('-' . $dia . ' days', strtotime(date('d-m-Y'))));
        }

        $data = [

            // ANO
            'faturamento_anual' => '[' . implode($relatorioAnual, ',') . ']',
            'total_faturamento_anual' => ValidateUtil::setFormatMoney(array_sum($relatorioAnual)),
            'total_pedidodos_aprovado_anual' => $totalPedidosFeitosAnual[0]['total'],
            'mendia_vendas_menais_anual' => ValidateUtil::setFormatMoney(array_sum($relatorioAnual) / 12),
            'clientes_cadastrados_anual' => $clientesCadastradosAno[0]['total'],
            'gasto_facebook_anual' => ValidateUtil::setFormatMoney($gastoFacebookAno[0]['total']),
            'custo_frete_anual' => ValidateUtil::setFormatMoney(array_sum($freteAnual)),
            'pagamento_fornecedor_anual' => ValidateUtil::setFormatMoney($pagamentoFornecedorAnual[0]['total']),
            'lucro_liquido_anual' => ValidateUtil::setFormatMoney($lucroLiquidoAnual),
            'itens_comprados_anual' => array_sum($itensPedidoTotalAnual),
            'chargebacks_anual' => ValidateUtil::setFormatMoney($chargebackAno[0]['total']),

            // MES PASSADO
            'faturamento_mes_passado' => '[' . implode($relatorioMesPassado, ',') . ']',
            'total_faturamento_mes_passado' => ValidateUtil::setFormatMoney(array_sum($relatorioMesPassado)),
            'total_pedidodos_aprovado_mes_passado' => $totalPedidosFeitosMesPassado[0]['total'],
            'mendia_vendas_menais_mes_passado' => ValidateUtil::setFormatMoney(array_sum($relatorioMesPassado) / 12),
            'clientes_cadastrados_mes_passado' => $clientesCadastradosMesPassado[0]['total'],
            'gasto_facebook_mes_passado' => ValidateUtil::setFormatMoney($gastoFacebookMesPassado[0]['total']),
            'custo_frete_mes_passado' => ValidateUtil::setFormatMoney(array_sum($freteMesPassado)),
            'pagamento_fornecedor_mes_passado' => ValidateUtil::setFormatMoney($pagamentoFornecedorMesPassado[0]['total']),
            'lucro_liquido_mes_passado' => ValidateUtil::setFormatMoney($lucroLiquidoMesPassado),
            'itens_comprados_mes_passado' => array_sum($itensPedidoTotalMesPassado),
            '_dias_mes_passado' => json_encode(array_values($diasMesPassado)),
            'chargebacks_mes_passado' => ValidateUtil::setFormatMoney($chargebackMesPassado[0]['total']),

            // MES ATUAL
            'faturamento_mes_atual' => '[' . implode($relatorioMesAtual, ',') . ']',
            'total_faturamento_mes_atual' => ValidateUtil::setFormatMoney(array_sum($relatorioMesAtual)),
            'total_pedidodos_aprovado_mes_atual' => $totalPedidosFeitosMesAtual[0]['total'],
            'mendia_vendas_menais_mes_atual' => ValidateUtil::setFormatMoney(array_sum($relatorioMesAtual) / 12),
            'clientes_cadastrados_mes_atual' => $clientesCadastradosMesAtual[0]['total'],
            'gasto_facebook_mes_atual' => ValidateUtil::setFormatMoney($gastoFacebookMesAtual[0]['total']),
            'custo_frete_mes_atual' => ValidateUtil::setFormatMoney(array_sum($freteMesAtual)),
            'pagamento_fornecedor_mes_atual' => ValidateUtil::setFormatMoney($pagamentoFornecedorMesAtual[0]['total']),
            'lucro_liquido_mes_atual' => ValidateUtil::setFormatMoney($lucroLiquidoMesAtual),
            'itens_comprados_mes_atual' => array_sum($itensPedidoTotalMesAtual),
            '_dias_mes_atual' => json_encode(array_values($diasMesAtual)),
            'chargebacks_mes_atual' => ValidateUtil::setFormatMoney($chargebackMesAtual[0]['total']),

            // ÚLTIMOS 7 DIAS
            'faturamento_ultimos_7_dias' => '[' . implode($relatorioUltimos7Dias, ',') . ']',
            'total_faturamento_ultimos_7_dias' => ValidateUtil::setFormatMoney(array_sum($relatorioUltimos7Dias)),
            'total_pedidodos_aprovado_ultimos_7_dias' => $totalPedidosFeitosUltimos7Dias[0]['total'],
            'mendia_vendas_menais_ultimos_7_dias' => ValidateUtil::setFormatMoney(array_sum($relatorioUltimos7Dias) / 12),
            'clientes_cadastrados_ultimos_7_dias' => $clientesCadastradosUltimos7Dias[0]['total'],
            'gasto_facebook_ultimos_7_dias' => ValidateUtil::setFormatMoney($gastoFacebookUltimos7Dias[0]['total']),
            'custo_frete_ultimos_7_dias' => ValidateUtil::setFormatMoney(array_sum($freteUltimos7Dias)),
            'pagamento_fornecedor_ultimos_7_dias' => ValidateUtil::setFormatMoney($pagamentoFornecedorUltimos7Dias[0]['total']),
            'lucro_liquido_ultimos_7_dias' => ValidateUtil::setFormatMoney($lucroLiquidoUltimos7Dias),
            'itens_comprados_ultimos_7_dias' => array_sum($itensPedidoTotalUltimos7Dias),
            '_dias_ultimos_7_dias' => json_encode(array_values($diasUltimos7Dias)),
            'chargebacks_ultimos_7_dias' => ValidateUtil::setFormatMoney($chargebackMesUltimos7Dias[0]['total'])
        ];

        $this->renderView('relatorio', $data);
    }

    public function importarAction()
    {
        $this->renderView('importar');
    }

    public function capturar_vendas_upnidAction()
    {
        $email = new Email();

        $keys_customer = [
            'email' => 'email',
            'name' => 'nome',
            'doc' => 'cpf',
            'phone_local_code' => 'ddd',
            'phone_number' => 'telefone'
        ];

        $keys_address = [
            'name' => 'destinatario',
            'address' => 'endereco',
            'address_number' => 'numero',
            'address_district' => 'bairro',
            'address_comp' => 'complemento',
            'address_city' => 'cidade',
            'address_state' => 'uf',
            'address_zip_code' => 'cep'
        ];

        $keys_sale = [
            'transaction' => 'numero_pedido',
            'purchase_date' => 'data',
            'affiliation_commission' => 'valor'
        ];

        $idCliente = NULL;
        $idEndereco = NULL;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data_webhooks = file_get_contents('php://input');
            $sale = explode("&", $data_webhooks);

            $_dt = [];
            foreach ($sale as $sal) {
                $v = explode("=", $sal);
                $_dt[$v[0]] = urldecode(strtr($v[1], "'", '%'));
            }

            $produto = explode(' ', $_dt['prod_name']);

            $produtoSelect = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                [
                    'descricao',
                    'LIKE',
                    $produto[0] . ' ' . $produto[1]
                ]
            ]);

            if (sizeof($produtoSelect) != 0) {

                // ////////////////////////////////////////////////////////////////////////////////////////////////////
                // FORM CLIENT

                $formCliente = [
                    "ativo" => 1,
                    "date_create" => date('Y-m-d')
                ];

                foreach ($keys_customer as $keyc => $valc) {
                    if (! empty($_dt[$keyc])) {
                        $formCliente[$valc] = $_dt[$keyc];
                    }
                }

                $phone = $formCliente['ddd'] . $formCliente['telefone'];
                unset($formCliente['ddd']);
                $formCliente['telefone'] = $phone;
                $formCliente['senha'] = md5('shoptivas');

                $c = $this->dao('Cliente', 'Cliente')->countOcurrence('*', [
                    'cpf',
                    '=',
                    $formCliente['cpf']
                ]);

                // CREATE NEW ACOUNT | TEMPORARY
                if ($c == 0) {
                    $idCliente = $this->dao('Core', 'Cliente')->insert($formCliente);
                } else {
                    // GET CLIENT
                    $cliente = $this->dao('Cliente', 'Cliente')->select([
                        '*'
                    ], array(
                        array(
                            'cpf',
                            '=',
                            $formCliente['cpf']
                        )
                    ));

                    $this->dao('Core', 'Cliente')->update($formCliente, [
                        'id',
                        '=',
                        $cliente[0]['id']
                    ]);

                    $idCliente = $cliente[0]['id'];
                }

                // END FORM CLIENT
                // ////////////////////////////////////////////////////////////////////////////////////////////////////

                // ////////////////////////////////////////////////////////////////////////////////////////////////////
                // FORM ADDRESS
                $formAddress = [
                    "principal" => TRUE
                ];

                foreach ($keys_address as $keya => $vala) {
                    if (! empty($_dt[$keya])) {
                        $formAddress[$vala] = $_dt[$keya];
                    }
                }

                $end = $this->dao('Endereco', 'Endereco')->countOcurrence('*', [
                    'id_cliente',
                    '=',
                    $idCliente
                ]);

                // CREATE NEW ACOUNT | TEMPORARY
                if ($end == 0) {
                    $idEndereco = $this->dao('Core', 'Endereco')->insert($formAddress);
                } else {
                    // GET ADRESS
                    $endereco = $this->dao('Cliente', 'Endereco')->select([
                        '*'
                    ], array(
                        array(
                            'id_cliente',
                            '=',
                            $idCliente
                        )
                    ));

                    $idEndereco = $endereco[0]['id'];
                }

                // END FORM PEDIDO
                // ////////////////////////////////////////////////////////////////////////////////////////////////////

                // ////////////////////////////////////////////////////////////////////////////////////////////////////
                // FORM ADDRESS
                $formPedido = [];

                foreach ($keys_sale as $keyp => $valp) {
                    if (! empty($_dt[$keyp])) {
                        $formPedido[$valp] = $_dt[$keyp];
                    }
                }

                $formPedido['data'] = substr($formPedido['data'], 0, 10);
                $formPedido['id_endereco'] = $idEndereco;
                $formPedido['id_cliente'] = $idCliente;
                $formPedido['id_pedido_status_fornecedor'] = 1;
                $formPedido['link_boleto'] = $_dt['billet_url'];
                $formPedido['codigo_transacao'] = '#' . $_dt['transaction'];

                switch ($_dt['payment_type']) {
                    case 'billet':
                        $formPedido['tipo_pagamento'] = 'Boleto';
                        break;

                    case 'credit_card':
                        $formPedido['tipo_pagamento'] = 'Cartao';
                        break;
                }

                switch ($_dt['status']) {
                    case 'billet_printed':
                        $formPedido['id_situacao_pedido'] = 1;
                        break;

                    case 'approved':
                        $formPedido['id_situacao_pedido'] = 2;
                        break;
                }

                $idPedido = $this->dao('Core', 'Pedido')->insert($formPedido);

                $lucro = floatval($_dt['affiliation_commission']) - floatval($produtoSelect[0]['valor_compra']);

                $form_item = [
                    'id_pedido' => $idPedido,
                    'id_situacao_item_pedido' => 1,
                    'preco' => $produtoSelect[0]['valor_venda'],
                    'quantidade' => 1,
                    'lucro' => $lucro,
                    'id_produto' => $produtoSelect[0]['id']
                ];

                $this->dao('Core', 'ItemPedido')->insert($form_item);

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

                switch ($_dt['status']) {
                    case 'billet_printed':
                        $notificaBoleto = $email->segundaViaBoleto($_dt['first_name'], $_dt['billet_url'], 'R$ ' . $_dt['full_price'], $_dt['email'], 'shoptivas');
                        $email->send($_dt['email'], "Olá " . $_dt['first_name'] . ', não esqueça do seu boleto', $notificaBoleto, '1001');
                        break;

                    case 'approved':
                        $bodyConfirmacaoPedido = $email->confirmacaoPedido($_dt['first_name'], $this->dao('Core', 'Pedido')
                            ->getField('numero_pedido', $idPedido), $produtos, $endereco);

                        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                        $email->send($_dt['email'], "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                        break;
                }

                // END FORM PEDIDO // ////////////////////////////////////////////////////////////////////////////////////////////////////
            }

            $filename = Configuration::PATH_LOG . '/logs_upnid.txt';
            file_put_contents($filename, print_r($sale, true), FILE_APPEND);
        } else {
            $filename = Configuration::PATH_LOG . '/logs_upnid.txt';
            file_put_contents($filename, $sale, FILE_APPEND);
        }
    }

    public function importarRastreamentoAction()
    {
        $this->renderView('importar_codigos');
    }

    public function importarDocumentoAction()
    {
        $idPedido = $_POST['id_pedido'];
        $targetDir = Configuration::PATH_PEDIDO;

        if (! is_dir($targetDir . $idPedido)) {
            mkdir($targetDir . $idPedido);
            $targetDir = $targetDir . $idPedido . '/';
        } else if (is_dir($targetDir . $idPedido)) {
            $targetDir = $targetDir . $idPedido . '/';
        }

        $targetFile = $targetDir . basename($_FILES['file']['name']);

        move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
    }

    public function importarVendasUpnidAction()
    {
        $dir = Configuration::PATH_UPLOADS;
        if (isset($_FILES)) {
            foreach ($_FILES as $file) {
                $filename = basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $dir . '\\' . $filename)) {

                    $File = $dir . '\\' . $filename;

                    $vendas = array();
                    $handle = fopen($File, "r");
                    if (empty($handle) === false) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $vendas[] = $data;
                        }
                        fclose($handle);
                    }

                    unset($vendas[0]);

                    foreach ($vendas as $venda) {
                        $vend = [
                            explode(';', implode(';', $venda))
                        ];

                        foreach ($vend as $v) {

                            $produto = explode(' ', $v[1]);

                            $produtoSelect = $this->dao('Core', 'Produto')->select([
                                '*'
                            ], [
                                'descricao',
                                'LIKE',
                                $produto[0] . ' ' . $produto[1]
                            ]);

                            $idCliente = NULL;
                            $idEndereco = NULL;

                            if (sizeof($produtoSelect) != 0) {

                                $hasPedido = $this->dao('Core', 'Pedido')->countOcurrence('*', [
                                    'numero_pedido',
                                    '=',
                                    trim($v[0])
                                ]);

                                $hassPassword = md5('shopvitas');
                                // $hassPassword = '725e8e6855f8c0e0f23f36ce369d25e3';
                                $formCliente = [
                                    "nome" => trim($v[23]),
                                    "email" => trim($v[24]),
                                    "cpf" => ValidateUtil::cleanInput(trim($v[25])),
                                    'senha' => trim($hassPassword),
                                    "telefone" => ValidateUtil::cleanInput(trim($v[26])),
                                    "ativo" => 1,
                                    "date_create" => date('Y-m-d')
                                ];

                                $c = $this->dao('Cliente', 'Cliente')->countOcurrence('*', [
                                    'email',
                                    '=',
                                    trim($v[24])
                                ]);

                                // CREATE NEW ACOUNT | TEMPORARY
                                if ($c == 0) {
                                    $idCliente = $this->dao('Core', 'Cliente')->insert($formCliente);
                                } else {
                                    // GET CLIENT
                                    $cliente = $this->dao('Cliente', 'Cliente')->select([
                                        '*'
                                    ], array(
                                        array(
                                            'email',
                                            '=',
                                            trim($v[24])
                                        )
                                    ));

                                    $idCliente = $cliente[0]['id'];
                                }

                                $end = $this->dao('Endereco', 'Endereco')->countOcurrence('*', [
                                    'id_cliente',
                                    '=',
                                    $idCliente
                                ]);

                                $normal_cep = str_replace(' ', '', trim(ValidateUtil::cleanString($v[33])));
                                switch (strlen($normal_cep)) {
                                    case 8:
                                        $normal_cep = $normal_cep;
                                        break;
                                    case 7:
                                        $normal_cep = '0' . $normal_cep;
                                        break;
                                    default:
                                        $normal_cep = $normal_cep;
                                        break;
                                }

                                $_cep_1 = substr($normal_cep, 0, 5);
                                $_cep_2 = substr($normal_cep, 5);

                                $cep = $_cep_1 . '-' . $_cep_2;

                                $endereco = [
                                    "destinatario" => trim($v[23]),
                                    "endereco" => trim($v[27]),
                                    "bairro" => trim($v[30]),
                                    "cidade" => trim($v[31]),
                                    "complemento" => trim($v[29]),
                                    "uf" => trim($v[32]),
                                    "cep" => $cep,
                                    "numero" => str_replace('/', '-', trim($v[28])),
                                    "principal" => TRUE,
                                    "id_cliente" => $idCliente
                                ];

                                // CREATE NEW ACOUNT | TEMPORARY
                                if ($end == 0) {
                                    $idEndereco = $this->dao('Core', 'Endereco')->insert($endereco);
                                } else {
                                    // GET ADRESS
                                    $endereco = $this->dao('Cliente', 'Endereco')->select([
                                        '*'
                                    ], array(
                                        array(
                                            'id_cliente',
                                            '=',
                                            $idCliente
                                        )
                                    ));

                                    $idEndereco = $endereco[0]['id'];
                                }

                                // NOT CHANGE
                                if ($hasPedido == 0) {
                                    $data_trans = trim($v[38]); // DATA DE ATUALIZAÇÃO
                                    if ($data_trans == 0 || $data_trans == '0') {
                                        $data_trans = trim($v[37]);
                                    }

                                    if ($this->validateDate(trim($data_trans)) != TRUE || $data_trans == '' || $data_trans == NULL) {
                                        $data_transacao = date('Y-m-d');
                                    } else {
                                        $ano_transacao = substr($data_trans, 6, 4);
                                        $mes_transacao = substr($data_trans, 3, 2);
                                        $dia_transacao = substr($data_trans, 0, 2);
                                        $data_transacao = $ano_transacao . '-' . $mes_transacao . '-' . $dia_transacao;
                                    }

                                    // TIPO PAGAMENTO
                                    $tipo = trim($v[6]);
                                    if ($tipo == 'Cartão de Crédito') {
                                        $tipo = 'Cartao';
                                    } else if ($tipo == 'Boleto Bancário') {
                                        $tipo = 'Boleto';
                                    }

                                    // STATUS PAGAMENTO | FALTA REGRAS AINDA
                                    $status = trim($v[2]);
                                    if ($status == 'Aprovada' || $status == 'Boleto Pago') {
                                        if ($status == 'Boleto Pago') {
                                            $data_transacao = date('Y-m-d');
                                        }

                                        $status = 2;
                                    } else if ($status == 'Recusada') {
                                        $status = 3;
                                    } else if ($status == 'Boleto Gerado') {
                                        // BOLETO GERADO PROVAVELMENTE
                                        $email = new Email();
                                        $notificaBoleto = $email->segundaViaBoleto(trim($v[23]), '', 'R$ ' . trim($v[10]) . ',' . trim($v[11]), trim($v[24]), 'shopvitas');
                                        $email->send(trim($v[24]), "Olá " . trim($v[23]) . ', não esqueça do seu boleto', $notificaBoleto, '1001');
                                        $status = 1;
                                        sleep(2);
                                    }

                                    // VALOR VENDA LIQUIDA
                                    $cent = 0;
                                    if ($v[4] != NULL || $v[4] != '' || $v[4] != 0) {
                                        $cent = ($v[4] / 100);
                                    }

                                    $valor_venda_liquida = str_replace('.', '', $v[3]);
                                    $valor_venda_liquida = str_replace(',', '.', $valor_venda_liquida);
                                    $valor_venda_liquida = floatval($valor_venda_liquida) + $cent;

                                    $lucro = $valor_venda_liquida - $produtoSelect[0]['valor_compra'];

                                    $formPedido = [
                                        "numero_pedido" => trim($v[0]),
                                        "data" => $data_transacao,
                                        "valor" => $valor_venda_liquida,
                                        "frete" => 0,
                                        "lucro" => $lucro,
                                        "codigo_transacao" => '#' . trim($v[0]),
                                        "id_cliente" => $idCliente,
                                        "id_endereco" => $idEndereco,
                                        "id_situacao_pedido" => $status,
                                        "id_pedido_status_fornecedor" => 1,
                                        "tipo_pagamento" => $tipo
                                    ];

                                    $oferta = explode('-', $v[21]);

                                    $_id_tamanho_produto = NULL;
                                    $_id_cor_produto = NULL;
                                    if (isset($oferta[1]) && $oferta[1] != '') {
                                        $oft = trim($oferta[0]);
                                        if ($oft == 'Tamanho') {
                                            $_tamanho = trim($oferta[1]);
                                            $_tm = $this->dao('Core', 'TamanhoProduto')->select([
                                                '*'
                                            ], [
                                                [
                                                    'descricao',
                                                    '=',
                                                    $_tamanho
                                                ],
                                                [
                                                    'id_produto',
                                                    '=',
                                                    $produtoSelect[0]['id']
                                                ]
                                            ]);

                                            $_id_tamanho_produto = $_tm[0]['id'];
                                        } else if ($oft == 'Cor') {
                                            $_cor = trim($oferta[1]);
                                            $_cr = $this->dao('Core', 'CorProduto')->select([
                                                '*'
                                            ], [
                                                [
                                                    'nome',
                                                    '=',
                                                    $_cor
                                                ],
                                                [
                                                    'id_produto',
                                                    '=',
                                                    $produtoSelect[0]['id']
                                                ]
                                            ]);

                                            $_id_cor_produto = $_cr[0]['id'];
                                        }
                                    }

                                    $idPedido = $this->dao('Core', 'Pedido')->insert($formPedido);

                                    $form_item = [
                                        'id_pedido' => $idPedido,
                                        'id_situacao_item_pedido' => 1,
                                        'preco' => $produtoSelect[0]['valor_venda'],
                                        'quantidade' => 1,
                                        'lucro' => $lucro,
                                        'id_produto' => $produtoSelect[0]['id']
                                    ];

                                    // HAS SIZE
                                    if ($_id_tamanho_produto != NULL) {
                                        $form_item['id_tamanho_produto'] = $_id_tamanho_produto;
                                    }

                                    // HAS COLOR
                                    if ($_id_cor_produto != NULL) {
                                        $form_item['id_cor_produto'] = $_id_cor_produto;
                                    }

                                    $this->dao('Core', 'ItemPedido')->insert($form_item);

                                    // 2 - IS APROVED
                                    if ($status == 2 && $idPedido) {

                                        // NOTIFICA CLIENTE SOBRE O PAGAMENTO APROVADO
                                        $email = new Email();

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

                                        // ENDEREÇO CLIENTE
                                        $endereco = $this->dao('Core', 'Endereco')->select([
                                            '*'
                                        ], [
                                            'id',
                                            '=',
                                            $idEndereco
                                        ]);

                                        // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                                        $bodyConfirmacaoPedido = $email->confirmacaoPedido(trim($v[23]), $this->dao('Core', 'Pedido')
                                            ->getField('numero_pedido', $idPedido), $produtos, $endereco);

                                        // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                                        $email->send(trim($v[24]), "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                                        sleep(2);

                                        // NOTIFICA CLIENTE
                                        $email = new Email();
                                        $bodyConfirmacaoPedido = $email->contaCliente(trim($v[23]), trim($v[24]), 'shopvitas');
                                        $email->send(trim($v[24]), "Confirmação de Pedido - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
                                        sleep(2);
                                    }
                                } else if ($hasPedido == 1 || $hasPedido > 1) {

                                    // STATUS PAGAMENTO | FALTA REGRAS AINDA
                                    $status = trim($v[2]);
                                    $id_situacao_pedido = 1;

                                    if ($status == 'Aprovada' || $status == 'Boleto Pago') {
                                        $id_situacao_pedido = 2;
                                    } else if ($status == 'Recusada') {
                                        $id_situacao_pedido = 3;
                                    } else if ($status == 'Reembolsada') {
                                        $id_situacao_pedido = 4;
                                    } else if ($status == 'Boleto Gerado') {
                                        $email = new Email();
                                        $notificaBoleto = $email->segundaViaBoleto(trim($v[23]), '', 'R$ ' . trim($v[10]) . ',' . trim($v[11]), trim($v[24]), 'shopvitas');
                                        $email->send(trim($v[24]), "Olá " . trim($v[23]) . ', não esqueça do seu boleto', $notificaBoleto, '1001');
                                        sleep(2);
                                    } else if ($status == 'Chargeback') {
                                        $id_situacao_pedido = 4;
                                    }

                                    $data_trans = trim($v[38]);
                                    if ($data_trans == 0 || $data_trans == '0') {
                                        $data_trans = trim($v[37]);
                                    }

                                    $ano_transacao = substr($data_trans, 6, 4);
                                    $mes_transacao = substr($data_trans, 3, 2);
                                    $dia_transacao = substr($data_trans, 0, 2);
                                    $data_transacao = $ano_transacao . '-' . $mes_transacao . '-' . $dia_transacao;

                                    if ($status == 'Boleto Pago' || $status == 'Chargeback') {
                                        $ano_transacao = substr($data_trans, 6, 4);
                                        $mes_transacao = substr($data_trans, 3, 2);
                                        $dia_transacao = substr($data_trans, 0, 2);
                                        $data_transacao = $ano_transacao . '-' . $mes_transacao . '-' . $dia_transacao;
                                    }

                                    // VALOR VENDA LIQUIDA
                                    $cent = 0;
                                    if ($v[4] != NULL || $v[4] != '' || $v[4] != 0) {
                                        $cent = ($v[4] / 100);
                                    }

                                    $valor_venda_liquida = str_replace('.', '', $v[3]);
                                    $valor_venda_liquida = str_replace(',', '.', $valor_venda_liquida);
                                    $valor_venda_liquida = floatval($valor_venda_liquida) + $cent;

                                    $formPedido = [
                                        "id_situacao_pedido" => $id_situacao_pedido,
                                        "data" => $data_transacao,
                                        "valor" => $valor_venda_liquida,
                                        "id_cliente" => $idCliente
                                    ];

                                    $this->dao('Core', 'Pedido')->update($formPedido, [
                                        'numero_pedido',
                                        '=',
                                        trim($v[0])
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function importarCodigosDeRastreamentoAction()
    {
        $path = Configuration::PATH_UPLOADS;
        if (isset($_FILES)) {
            foreach ($_FILES as $file) {
                $filename = basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $path . '\\' . $filename)) {

                    $xlsx = \SimpleXLSX::parse($path . '\\' . $filename);

                    $rets = [];
                    foreach ($xlsx->rows() as $row) {
                        $rets[] = $row;
                    }

                    unset($rets[0]);

                    foreach ($rets as $ret) {
                        $numeroPedido = $ret[13];

                        $_cliente = $this->dao('Core', 'Cliente')->select([
                            '*'
                        ], [
                            'nome',
                            '=',
                            $ret[5]
                        ]);

                        $_where_pedido = [];
                        if ($numeroPedido == NULL || $numeroPedido == '') {
                            if (count($_cliente) == 1) {
                                $_where_pedido = [
                                    'id_cliente',
                                    '=',
                                    $_cliente[0]['id']
                                ];
                            } else if (count($_cliente) > 1) {
                                $_ids = [];
                                foreach ($_cliente as $_c) {
                                    $_ids[] = $_c['id'];
                                }

                                $_where_pedido = [
                                    'id_cliente',
                                    'IN',
                                    $_ids
                                ];
                            }
                        } else {
                            $_where_pedido = [
                                'numero_pedido',
                                '=',
                                $numeroPedido
                            ];
                        }

                        $pedido = $this->dao('Core', 'Pedido')->select([
                            '*'
                        ], $_where_pedido);

                        if (sizeof($pedido) != 0 && $ret[1] != '') {
                            $email = new Email();
                            $itens = $this->dao('Core', 'ItemPedido')->select([
                                '*'
                            ], [
                                'id_pedido',
                                '=',
                                $pedido[0]['id']
                            ]);

                            // NOME CLIENTE
                            $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $pedido[0]['id_cliente']);

                            // E-MAIL CLIENTE
                            $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $pedido[0]['id_cliente']);

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

                            // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                            $bodyConfirmacaoPedido = $email->confirmacaoCodigoRastreio($nomeCliente, $ret[1], $produtos, $endereco);

                            // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                            $email->send($emailCliente, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                            sleep(2);

                            // UPDATE
                            $this->dao('Core', 'Pedido')->update([
                                "id_pedido_status_fornecedor" => 2 // Enviado
                            ], [
                                'id',
                                '=',
                                $pedido[0]['id']
                            ]);

                            $this->dao('Core', 'Rastreiamento')->delete([
                                'id_pedido',
                                '=',
                                $pedido[0]['id']
                            ]);

                            $this->dao('Core', 'Rastreiamento')->insert([
                                "codigo" => $ret[1],
                                "postado" => 1,
                                "id_pedido" => $pedido[0]['id']
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function deletarPedidoAction()
    {
        $idPedido = (int) $this->post("id_pedido");
        $this->dao('Core', 'Pedido')->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->dao('Core', 'ItemPedido')->delete([
            'id_pedido',
            '=',
            $idPedido
        ]);

        $this->dao('Core', 'Pedido')->delete([
            'id',
            '=',
            $idPedido
        ]);

        MessageUtil::addMessageSucces("Pedido deletado com sucesso!");
    }

    public function _pedidosAction()
    {
        $whereLista = [];
        $where_total_pedidos = [];
        $where_total_pedidos_pagos = [];
        $where_total_pedidos_chargeback = [];
        $where_total_pedidos_em_analise = [];
        $where_boletos_a_compensar = [];
        $where_lancamento = [];
        $b2w = (int) Request::get('b2w');

        if ($_GET['b2w']) {
            $b2w = (int) $_GET['b2w'];
        }

        $_title = "";

        // Origem dos pedidos
        switch ($b2w) {
            case 0:
                $_title = "Pedidos - Shopvitas";
                break;
            case 1:
                $_title = "Pedidos - B2W";
                break;
        }

        // pegar os pedidos da b2w
        array_push($whereLista, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // pegar os pedidos da b2w
        array_push($where_total_pedidos, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // pegar os pedidos da b2w
        array_push($where_total_pedidos_pagos, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // pegar os pedidos da b2w
        array_push($where_total_pedidos_chargeback, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // pegar os pedidos da b2w
        array_push($where_total_pedidos_em_analise, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // pegar os pedidos da b2w
        array_push($where_boletos_a_compensar, [
            'pedido_b2w',
            '=',
            $b2w
        ]);

        // BOLETOS AGUARDANDO PAGAMENTO
        array_push($where_boletos_a_compensar, [
            'id_situacao_pedido',
            '=',
            1
        ]);

        // BOLETOS AGUARDANDO PAGAMENTO
        array_push($where_boletos_a_compensar, [
            'tipo_pagamento',
            '=',
            'boleto'
        ]);

        array_push($where_total_pedidos_pagos, [
            'id_situacao_pedido',
            '=',
            2
        ]);

        array_push($where_total_pedidos_chargeback, [
            'id_situacao_pedido',
            'IN',
            [
                4,
                5
            ]
        ]);

        array_push($where_total_pedidos_em_analise, [
            'id_situacao_pedido',
            '=',
            6
        ]);

        if ($_GET['codigo_transacao'] && $_GET['codigo_transacao'] != "") {
            array_push($whereLista, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);

            array_push($where_boletos_a_compensar, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);

            array_push($where_total_pedidos_pagos, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);

            array_push($where_total_pedidos_chargeback, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);

            array_push($where_total_pedidos_em_analise, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);

            array_push($where_total_pedidos, [
                'codigo_transacao',
                '=',
                $_GET['codigo_transacao']
            ]);
        } else {
            array_push($whereLista, [
                'codigo_transacao',
                '!=',
                NULL
            ]);

            array_push($where_boletos_a_compensar, [
                'codigo_transacao',
                '!=',
                NULL
            ]);

            array_push($where_total_pedidos_pagos, [
                'codigo_transacao',
                '!=',
                NULL
            ]);

            array_push($where_total_pedidos_chargeback, [
                'codigo_transacao',
                '!=',
                NULL
            ]);

            array_push($where_total_pedidos_em_analise, [
                'codigo_transacao',
                '!=',
                NULL
            ]);

            array_push($where_total_pedidos, [
                'codigo_transacao',
                '!=',
                NULL
            ]);
        }

        if ($_GET['numero_pedido'] && $_GET['numero_pedido'] != "") {
            array_push($whereLista, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);

            array_push($where_boletos_a_compensar, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);

            array_push($where_total_pedidos_pagos, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);

            array_push($where_total_pedidos_chargeback, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);

            array_push($where_total_pedidos_em_analise, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);

            array_push($where_total_pedidos, [
                'numero_pedido',
                '=',
                $_GET['numero_pedido']
            ]);
        }

        // FILTRO POR PRODUTO
        $idsPedidos = [];
        if ($_GET['produto'] && $_GET['produto'] != "") {
            $itensPedidos = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_produto',
                '=',
                $_GET['produto']
            ]);

            foreach ($itensPedidos as $item) {
                $idsPedidos[] = $item['id_pedido'];
            }
        }

        // FILTRO POR CATEGORIA
        $idsPedidos = [];
        if ($_GET['categoria'] && $_GET['categoria'] != "") {
            $produtos = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id_categoria',
                'IN',
                $_GET['categoria']
            ]);

            $idProdutos = [];
            foreach ($produtos as $prod) {
                $idProdutos[] = $prod['id'];
            }

            if (sizeof($idProdutos) > 0) {
                $itensPedidos = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_produto',
                    'IN',
                    $idProdutos
                ]);

                foreach ($itensPedidos as $item) {
                    $idsPedidos[] = $item['id_pedido'];
                }
            }
        }

        // FILTRO POR MARCA
        $idsPedidos = [];
        if ($_GET['marca'] && $_GET['marca'] != "") {
            $produtos = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id_marca',
                'IN',
                $_GET['marca']
            ]);

            $idProdutos = [];
            foreach ($produtos as $prod) {
                $idProdutos[] = $prod['id'];
            }

            if (sizeof($idProdutos) > 0) {
                $itensPedidos = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_produto',
                    'IN',
                    $idProdutos
                ]);

                foreach ($itensPedidos as $item) {
                    $idsPedidos[] = $item['id_pedido'];
                }
            }
        }

        if (sizeof($idsPedidos) != 0) {
            array_push($whereLista, [
                'id',
                'IN',
                $idsPedidos
            ]);

            array_push($where_total_pedidos_pagos, [
                'id',
                'IN',
                $idsPedidos
            ]);

            array_push($where_total_pedidos, [
                'id',
                'IN',
                $idsPedidos
            ]);

            array_push($where_boletos_a_compensar, [
                'id',
                'IN',
                $idsPedidos
            ]);

            array_push($where_total_pedidos_chargeback, [
                'id',
                'IN',
                $idsPedidos
            ]);

            array_push($where_total_pedidos_em_analise, [
                'id',
                'IN',
                $idsPedidos
            ]);
        }

        if ($_GET['situacao'] && $_GET['situacao'] != "") {
            array_push($whereLista, [
                'id_situacao_pedido',
                'IN',
                $_GET['situacao']
            ]);
        }

        if ($_GET['cep'] && $_GET['cep'] != "") {
            $endereco = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                'cep',
                '=',
                $_GET['cep']
            ]);

            $idsEnd = [];
            foreach ($endereco as $en) {
                $idsEnd[] = $en['id'];
            }

            if (sizeof($endereco) != 0) {
                array_push($whereLista, [
                    'id_endereco',
                    'IN',
                    $idsEnd
                ]);

                array_push($where_total_pedidos, [
                    'id_endereco',
                    'IN',
                    $idsEnd
                ]);

                array_push($where_total_pedidos_pagos, [
                    'id_endereco',
                    'IN',
                    $idsEnd
                ]);

                array_push($where_total_pedidos_chargeback, [
                    'id_endereco',
                    'IN',
                    $idsEnd
                ]);

                array_push($where_total_pedidos_em_analise, [
                    'id_endereco',
                    'IN',
                    $idsEnd
                ]);
            }
        }

        if ($_GET['codigo_rastreio'] && $_GET['codigo_rastreio'] != "") {
            $rastreamento = $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                'codigo',
                '=',
                trim($_GET['codigo_rastreio'])
            ]);

            if (sizeof($rastreamento) != 0) {
                array_push($whereLista, [
                    'id',
                    '=',
                    $rastreamento[0]['id_pedido']
                ]);

                array_push($where_total_pedidos, [
                    'id',
                    '=',
                    $rastreamento[0]['id_pedido']
                ]);

                array_push($where_total_pedidos_pagos, [
                    'id',
                    '=',
                    $rastreamento[0]['id_pedido']
                ]);

                array_push($where_total_pedidos_chargeback, [
                    'id',
                    '=',
                    $rastreamento[0]['id_pedido']
                ]);

                array_push($where_total_pedidos_em_analise, [
                    'id',
                    '=',
                    $rastreamento[0]['id_pedido']
                ]);
            }
        }

        if ($_GET['cpf'] && $_GET['cpf'] != "") {
            $cpfs = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'cpf',
                '=',
                ValidateUtil::cleanInput(trim($_GET['cpf']))
            ]);

            $idsClientes = [];
            foreach ($cpfs as $cpf) {
                $idsClientes[] = $cpf['id'];
            }

            if (sizeof($idsClientes) != 0) {
                array_push($whereLista, [
                    'id_cliente',
                    'IN',
                    $idsClientes
                ]);

                array_push($where_total_pedidos, [
                    'id_cliente',
                    'IN',
                    $idsClientes
                ]);

                array_push($where_total_pedidos_pagos, [
                    'id_cliente',
                    'IN',
                    $idsClientes
                ]);

                array_push($where_total_pedidos_chargeback, [
                    'id_cliente',
                    'IN',
                    $idsClientes
                ]);

                array_push($where_total_pedidos_em_analise, [
                    'id_cliente',
                    'IN',
                    $idsClientes
                ]);
            }
        }

        if ($_GET['status_fornecedor'] && $_GET['status_fornecedor'] != "") {
            array_push($whereLista, [
                'id_pedido_status_fornecedor',
                '=',
                $_GET['status_fornecedor']
            ]);
        }

        if ($_GET['status_clear_sale'] && $_GET['status_clear_sale'] != "") {
            array_push($whereLista, [
                'status_clear_sale',
                'IN',
                $_GET['status_clear_sale']
            ]);
        }

        if ($_GET['tipo'] && $_GET['tipo'] != "") {
            array_push($whereLista, [
                'tipo_pagamento',
                '=',
                $_GET['tipo']
            ]);

            array_push($where_total_pedidos, [
                'tipo_pagamento',
                '=',
                $_GET['tipo']
            ]);

            array_push($where_total_pedidos_pagos, [
                'tipo_pagamento',
                '=',
                $_GET['tipo']
            ]);

            array_push($where_total_pedidos_chargeback, [
                'tipo_pagamento',
                '=',
                $_GET['tipo']
            ]);

            array_push($where_total_pedidos_em_analise, [
                'tipo_pagamento',
                '=',
                $_GET['tipo']
            ]);
        }

        // DATA PEDIDO
        if ($_GET['data_inicio'] && $_GET['data_fim'] != "" && $_GET['data_inicio'] == $_GET['data_fim']) {
            array_push($where_boletos_a_compensar, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($where_total_pedidos, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($where_total_pedidos_pagos, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($where_total_pedidos_chargeback, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($where_total_pedidos_em_analise, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($whereLista, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);

            array_push($where_lancamento, [
                'data',
                '=',
                $_GET['data_inicio']
            ]);
        } else if ($_GET['data_inicio'] && $_GET['data_fim'] != "" && $_GET['data_inicio'] !== $_GET['data_fim']) {
            array_push($where_boletos_a_compensar, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($where_total_pedidos, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($where_total_pedidos_pagos, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($where_total_pedidos_chargeback, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($where_total_pedidos_em_analise, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($whereLista, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);

            array_push($where_lancamento, [
                'data',
                'BETWEEN',
                [
                    $_GET['data_inicio'],
                    $_GET['data_fim']
                ]
            ]);
        } else if (! isset($_GET['codigo_transacao']) || ! isset($_GET['numero_pedido'])) {
            array_push($whereLista, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_total_pedidos_pagos, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_total_pedidos_chargeback, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_total_pedidos_em_analise, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_total_pedidos, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_boletos_a_compensar, [
                'data',
                '=',
                date('Y-m-d')
            ]);

            array_push($where_lancamento, [
                'data',
                '=',
                date('Y-m-d')
            ]);
        }

        // VALOR TOTAL PEDIDOS
        $total_valor_pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_total_pedidos);
        $_t_p = [];
        foreach ($total_valor_pedido as $pedido) {
            array_push($_t_p, $pedido['valor']);
        }

        // VALOR TOTAL PEDIDOS PAGOS
        $total_valor_pedido_pagos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_total_pedidos_pagos);

        $_t_p_p = [];
        foreach ($total_valor_pedido_pagos as $pedido) {
            array_push($_t_p_p, ($pedido['valor'] + $pedido['frete']));
        }

        // QUANTIDADE PEDIDOS PAGAOS COM CARTÃO
        $where_quantidade_pedidos_pagos_com_cartao = $where_total_pedidos_pagos;
        array_push($where_quantidade_pedidos_pagos_com_cartao, [
            'tipo_pagamento',
            '=',
            'cartao'
        ]);
        $_qtd_total_cartao_pago = $this->dao('Core', 'Pedido')->countOcurrence('*', $where_quantidade_pedidos_pagos_com_cartao);

        // QUANTIDADE PEDIDOS PAGAOS COM BOLETO
        $where_quantidade_pedidos_pagos_com_boleto = $where_total_pedidos_pagos;
        array_push($where_quantidade_pedidos_pagos_com_boleto, [
            'tipo_pagamento',
            '=',
            'boleto'
        ]);
        $_qtd_total_boleto_pago = $this->dao('Core', 'Pedido')->countOcurrence('*', $where_quantidade_pedidos_pagos_com_boleto);

        $_qtd_total_boleto_a_compensar = $this->dao('Core', 'Pedido')->countOcurrence('*', $where_boletos_a_compensar);

        // VALOR TOTAL PEDIDOS CHARGEBACK
        $total_valor_pedido_chargeback = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_total_pedidos_chargeback);
        $_t_p_c = [];
        foreach ($total_valor_pedido_chargeback as $pedidoChargeBack) {
            array_push($_t_p_c, $pedidoChargeBack['valor']);
        }

        // VALOR TOTAL PEDIDOS EM ANÁLISE
        $total_valor_pedido_em_analise = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_total_pedidos_em_analise);
        $_t_p_e_a = [];
        foreach ($total_valor_pedido_em_analise as $pedidoAnalise) {
            array_push($_t_p_e_a, $pedidoAnalise['valor']);
        }

        // LUCRO
        $lucro = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_total_pedidos_pagos);

        $_lucro = [];
        foreach ($lucro as $pedido) {
            $_lucro[] = $pedido['lucro'];
        }

        $_lucro = array_sum($_lucro);

        // LUCRO COM BOLETOS A COMPENSAR | OU SEJA, AGUARDANDO PAGAMENTO
        $lucro_boleto_a_compensar = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $where_boletos_a_compensar);

        $_b_a_c = [];
        foreach ($lucro_boleto_a_compensar as $pedido) {
            $_b_a_c[] = (($pedido['valor'] + $pedido['frete']));
        }

        if (isset($_GET['get_emails_customers']) && $_GET['get_emails_customers'] != '') {
            $ids = [];
            $pedidos = $this->dao('Core', 'Pedido')->select([
                '*'
            ], $whereLista, [
                'data',
                'ASC'
            ]);

            foreach ($pedidos as $ped) {
                $ids[] = $ped['id'];
            }

            $this->_getAction($ids);
            exit(0);
        }

        if (isset($_GET['export_excel']) && $_GET['export_excel'] != '') {
            $planilha = [];
            $pedidos = $this->dao('Core', 'Pedido')->select([
                '*'
            ], $whereLista, [
                'data',
                'ASC'
            ]);

            $header = [
                'Nº Pedido' => 'string',
                'Valor' => 'string',
                'Produto' => 'string',
                'Tamanho' => 'string',
                'Código de Rastreio' => 'string',
                'SKU' => 'string',
                'Quantidade' => 'string',
                'Custo' => 'string',
                'Frete' => 'string',
                'Cliente' => 'string',
                'Email' => 'string',
                'CPF' => 'string',
                'Endereço' => 'string',
                'Número' => 'string',
                'Cidade' => 'string',
                'Estado' => 'string',
                'País' => 'string',
                'Cep' => 'string',
                'Telefone' => 'string',
                'Cor' => 'string',
                'Data Pedido' => 'string',
                'Links' => 'string',
                'Link Produto' => 'string',
                'Nota Fiscal' => 'string'
            ];

            foreach ($pedidos as $p) {
                // Itens
                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $p['id']
                ]);

                $codigo = '';
                $rastreamento = $this->dao('Core', 'Rastreiamento')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $p['id']
                ]);

                if (sizeof($rastreamento) != 0) {
                    $codigo = $rastreamento[0]['codigo'];
                }

                // Endereco Pedido
                $enderecoPedido = $this->dao('Core', 'Endereco')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $p['id_endereco']
                ]);

                // Produto
                $produto = $this->dao('Core', 'Produto')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $itens[0]['id_produto']
                ]);

                // Cliente
                $cliente = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $p['id_cliente']
                ]);

                // Caminha da nota fiscal de cada pedido
                $server = "https://api.focusnfe.com.br";
                $nota_fiscal = $server . $this->dao('Core', 'NotaFiscal')->getField('caminho_pdf', $p['id_nota_fiscal']);

                if ($produto[0]['sku'] != '') {

                    $adress = '';
                    if ($enderecoPedido[0]['endereco'] != '') {
                        $adress .= $enderecoPedido[0]['endereco'];
                    }

                    if ($enderecoPedido[0]['complemento'] != '') {
                        $adress .= ', ' . $enderecoPedido[0]['complemento'];
                    }

                    if ($enderecoPedido[0]['numero'] != '') {
                        $adress .= ', Numero ' . $enderecoPedido[0]['numero'];
                    }

                    $storeFolder = Configuration::PATH_PEDIDO . $p['id'] . '/';
                    $files = scandir($storeFolder); // 1
                    $imgs = array();
                    $ds = DIRECTORY_SEPARATOR;
                    if (is_dir($storeFolder)) {
                        if (false !== $files) {
                            foreach ($files as $file) {
                                if ('.' != $file && '..' != $file) { // 2
                                    $obj['name'] = $file;
                                    $obj['size'] = filesize($storeFolder . $ds . $file);
                                    $imgs[] = $obj;
                                }
                            }
                        }
                    }

                    $links = '';
                    if (count($imgs) > 0) {
                        foreach ($imgs as $img) {
                            $links .= 'https://' . LINK_LOJA . '/data/uploads/pedido' . $p['id'] . '/' . $img['name'] . " ________________________________________________________________________________________________________________________";
                        }
                    }

                    foreach ($itens as $item) {

                        $produto_item = $this->dao('Core', 'Produto')->select([
                            '*'
                        ], [
                            'id',
                            '=',
                            $item['id_produto']
                        ]);

                        // 'Number' => mb_convert_encoding($enderecoPedido[0]['numero'], 'UTF-16LE', 'UTF-8'),

                        $custo = $produto_item[0]['preco_dolar_fornecedor'];
                        if ($custo == '' || $custo == NULL) {
                            $custo = 'R$ ' . ValidateUtil::setFormatMoney($produto_item[0]['valor_compra'] * $item['quantidade']);
                        } else {
                            $custo = '$ ' . ValidateUtil::setFormatMoney($produto_item[0]['preco_dolar_fornecedor'] * $item['quantidade']);
                        }

                        $link_produto = 'https://www.shopvitas.com.br/produto/' . $produto_item[0]['id'] . '/' . $produto_item[0]['cod_url_produto'];

                        $_tmh = ($item['id_tamanho_produto'] != '') ? $this->dao('Core', 'TamanhoProduto')->getField('descricao', $item['id_tamanho_produto']) : '';
                        $planilha[] = [
                            'Nº Pedido' => $p['numero_pedido'],
                            'Valor' => '',
                            'Produto' => trim($produto_item[0]['descricao']) . ' ' . $_tmh,
                            'Tamanho' => $_tmh,
                            'Código de Rastreio' => $codigo,
                            'SKU' => $produto_item[0]['sku'],
                            'Quantidade' => $item['quantidade'],
                            'Custo' => ($item['id_tamanho_produto'] != '') ? 'R$ ' . ValidateUtil::setFormatMoney($this->dao('Core', 'TamanhoProduto')->getField('custo', $item['id_tamanho_produto']) * $item['quantidade']) : $custo,
                            'Frete' => getFormaEnvioPorCodigo($p['codigo_envio']),
                            'Cliente' => $this->dao('Core', 'Cliente')->getField('nome', $p['id_cliente']),
                            'Email' => $this->dao('Core', 'Cliente')->getField('email', $p['id_cliente']),
                            'CPF' => dao('Core', 'Cliente')->getField('cpf', $p['id_cliente']),
                            'Endereço' => $adress,
                            'Número' => $enderecoPedido[0]['numero'],
                            'Cidade' => $enderecoPedido[0]['cidade'],
                            'Estado' => $enderecoPedido[0]['uf'],
                            'País' => 'Brazil',
                            'Cep' => $enderecoPedido[0]['cep'],
                            'Telephone - Telefone' => $cliente[0]['telefone'],
                            'Cor' => ($item['id_cor_produto'] != '') ? $this->dao('Core', 'CorProduto')->getField('nome', $item['id_cor_produto']) : '',
                            'Data Pedido' => $p['data'],
                            'Links' => $links,
                            'Link Produto' => $link_produto,
                            'Nota Fiscal' => $nota_fiscal
                        ];
                    }
                }
            }

            $this->planilhaXLSX($planilha, $header, 'PEDIDOS - ' . date('d-m-Y'));
        }

        // PEDIDOS
        $_html = PaginationUtil::execute($this->dao('Core', 'Pedido')->select([
            '*'
        ], $whereLista, [
            'data',
            'DESC'
        ]), $_GET, 25);

        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $whereLista, [
            'data',
            'DESC'
        ], PaginationUtil::getInicio(), 25);

        // Comissão B2W
        $cmb2w = array_sum($_t_p_p) / 100;
        $cmb2w = $cmb2w * 16;

        $qtd_pago = $_qtd_total_boleto_pago + $_qtd_total_cartao_pago;
        $percentual_cartao = 0;
        $percentual_boleto = 0;
        if ($qtd_pago != 0) {
            $percentual_cartao = substr(($_qtd_total_cartao_pago / $qtd_pago) * 100, 0, 4);
            $percentual_boleto = substr(($_qtd_total_boleto_pago / $qtd_pago) * 100, 0, 4);
        }

        // Taxa conversão boleto
        $total_boleto = $_qtd_total_boleto_a_compensar + $_qtd_total_boleto_pago;
        $taxa_conversao_boleto = 0;
        if ($total_boleto != 0) {
            $taxa_conversao_boleto = substr(($_qtd_total_boleto_pago / $total_boleto) * 100, 0, 4);
        }

        $data = [
            '_pedidos_total' => sizeof($this->dao('Core', 'Pedido')->select([
                '*'
            ], $whereLista, [
                'data',
                'DESC'
            ])),
            '_total_pedidos' => ValidateUtil::setFormatMoney(array_sum($_t_p)),
            '_count_pedidos_aprovados' => sizeof($_t_p_p),
            '_total_pedidos_pagos' => ValidateUtil::setFormatMoney(array_sum($_t_p_p)),
            '_cancelado' => ValidateUtil::setFormatMoney(array_sum($_t_p_c)),
            '_valor_total_pedidos_em_analise' => ValidateUtil::setFormatMoney(array_sum($_t_p_e_a)),
            '_total_em_analise' => sizeof($_t_p_e_a),
            '_lucro' => ValidateUtil::setFormatMoney($_lucro),
            '_boletos_a_compensar' => ValidateUtil::setFormatMoney(array_sum($_b_a_c)),
            '_quantidade_total_boleto_compensar' => sizeof($_b_a_c),
            '_quantidade_total_boleto_pago' => $_qtd_total_boleto_pago,
            '_quantidade_total_cartao_pago' => $_qtd_total_cartao_pago,
            '_percentual_boleto_pago' => $percentual_boleto,
            '_percentual_cartao_pago' => $percentual_cartao,
            '_taxa_conversao_boleto' => $taxa_conversao_boleto,
            'pedidos' => $pedidos,
            'paginacao' => $_html,
            'produtos' => $this->dao('Core', 'Produto')->select([
                '*'
            ], NULL, [
                'id',
                'DESC'
            ]),
            'categorias' => $this->dao('Core', 'Categoria')->select([
                '*'
            ], NULL, [
                'id',
                'DESC'
            ]),
            'marcas' => $this->dao('Core', 'Marca')->select([
                '*'
            ], NULL, [
                'id',
                'ASC'
            ]),
            'situacoes' => $this->dao('Core', 'SituacaoPedido')->select([
                '*'
            ]),
            'pedido_status_fornecedor' => $this->dao('Core', 'PedidoStatusFornecedor')->select([
                '*'
            ]),
            'where_total_pedidos_pagos' => $where_total_pedidos_pagos,
            'where_boletos_a_compensar' => $where_boletos_a_compensar,
            'where_total_pedidos_chargeback' => $where_total_pedidos_chargeback,
            'where_lancamento' => $where_lancamento,
            '_title_origem' => $_title,
            "_view" => $b2w,
            '_total_pagar_fornecedor' => $this->_custoFornecedor($where_total_pedidos_pagos),
            '_frete' => $this->_frete($where_total_pedidos_pagos),
            '_total_pago_fornecedor' => $this->_pagamentoFornecedorPorPeriodo($where_lancamento),
            '_comissao_b2w' => $cmb2w
        ];

        $this->renderView("index_b2w", $data);
    }

    public function nota_fiscalAction()
    {
        $idNota = Request::get('id_nota_fiscal');
        $notaFiscal = $this->dao('Core', 'NotaFiscal')->select([
            '*'
        ], [
            'id',
            '=',
            $idNota
        ]);

        if (isset($notaFiscal[0]['chave_nfe']) && $notaFiscal[0]['chave_nfe'] != NULL) {
            $arquivo = Configuration::PATH_NOTA . "/" . $notaFiscal[0]['chave_nfe'] . ".pdf";
            if (isset($arquivo) && file_exists($arquivo)) {
                header("Content-Type: " . "application/pdf");
                header("Content-Length: " . filesize($arquivo));
                header("Content-Disposition: attachment; filename=" . basename($arquivo));
                readfile($arquivo); // lê o arquivo
                exit(); // aborta pós-ações
            }

            if (! file_exists($arquivo)) {
                //
            }
        }
    }

    public function declaracao_conteudoAction()
    {
        $id = Request::get('id');
        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $id
        ]);

        $itensPedido = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $pedido[0]['id']
        ]);

        $_cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $pedido[0]['id_cliente']
        ]);

        $_endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id_cliente',
            '=',
            $pedido[0]['id_cliente']
        ]);

        $remetente = new Pessoa([
            'nome' => 'Yatta Importados',
            'doc' => ValidateUtil::setFormatCPF('13431940676'),
            'endereco' => 'Rua Monsenhor de Andrade',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '03009-100'
        ]);

        if (sizeof($_endereco) != 0) {
            $destinatario = new Pessoa();

            $numero = $_endereco[0]['numero'];
            if ($numero != NULL) {
                $numero = ', Número ' . $numero;
            }

            $complemento = $_endereco[0]['complemento'];
            if ($complemento != NULL) {
                $complemento = ',  ' . $complemento;
            }

            $destinatario->setNome($_cliente[0]['nome'])
                ->setDoc(ValidateUtil::setFormatCPF($_cliente[0]['cpf']))
                ->setEndereco($_endereco[0]['endereco'] . $numero . $complemento)
                ->setCidade($_endereco[0]['cidade'])
                ->setEstado($_endereco[0]['uf'])
                ->setCep($_endereco[0]['cep']);
        }

        $_itens = [];
        if (sizeof($itensPedido) != 0) {
            foreach ($itensPedido as $item) {
                $peso = $this->dao('Core', 'Produto')->getField('peso_bruto', $item['id_produto']);
                $_tmh = ($item['id_tamanho_produto'] != '') ? $this->dao('Core', 'TamanhoProduto')->getField('descricao', $item['id_tamanho_produto']) : '';
                $_itens[] = [
                    'descricao' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']) . ' ' . $_tmh,
                    'quantidade' => $item['quantidade'],
                    'peso' => ($peso != NULL) ? $peso / 1000 : 1,
                    'valor' => $item['preco']
                ];
            }

            $itens = new ItemBag($_itens);
        }

        $declaracao = new DeclaracaoConteudo($remetente, $destinatario, $itens);

        echo $declaracao->imprimirHtml();
    }

    public function _pagamentoFornecedorPorPeriodo($where)
    {
        array_push($where, [
            'id_tipo_lancamento',
            '=',
            2
        ]);

        $lancamentos = dao('Core', 'Lancamento')->select([
            '*'
        ], $where);

        $_total_inve = [];
        foreach ($lancamentos as $lan) {
            $_total_inve[] = $lan['valor'];
        }

        return array_sum($_total_inve);
    }

    public function _frete($_where)
    {
        $pedidos = dao('Core', 'Pedido')->select([
            '*'
        ], $_where);

        $_frete = [];

        foreach ($pedidos as $pedido) {
            $_frete[] = $pedido['frete'];
        }

        return array_sum($_frete);
    }

    public function _custoFornecedor($_where)
    {
        $pedidos = dao('Core', 'Pedido')->select([
            '*'
        ], $_where);

        $_custo = [];
        foreach ($pedidos as $pedido) {
            $itens = dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            foreach ($itens as $item) {
                $hasSize = dao('Core', 'TamanhoProduto')->select([
                    '*'
                ], [
                    'id_produto',
                    '=',
                    $item['id_produto']
                ]);

                if (sizeof($hasSize) == 0) {
                    $_custo[] = dao('Core', 'Produto')->getField('valor_compra', $item['id_produto']);
                } else {
                    $tamanho = dao('Core', 'TamanhoProduto')->select([
                        '*'
                    ], [
                        [
                            'id',
                            '=',
                            $item['id_tamanho_produto']
                        ]
                    ]);

                    $_custo[] = $item['quantidade'] * $tamanho[0]['custo'];
                }
            }
        }

        return array_sum($_custo);
    }

    public function _getAction($idsPedidos = NULL)
    {
        $data = [];
        $where = [];
        $name = Request::get('name');

        $catPerfumes = $this->dao('Core', 'Categoria')->select([
            'id'
        ], [
            'descricao',
            'LIKE',
            'Perfumes'
        ]);

        $idsPerfumes = [];
        foreach ($catPerfumes as $id) {
            $idsPerfumes[] = $id['id'];
        }

        if (sizeof($idsPerfumes) != 0) {
            $perfumes = $this->dao('Produto', 'Produto')->select([
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

            $ids = [];
            foreach ($perfumes as $p) {
                $ids[] = $p['id'];
            }

            array_push($where, [
                'id_produto',
                'IN',
                $ids
            ]);
        }

        array_push($where, [
            'id_situacao_pedido',
            'IN',
            [
                2,
                4
            ]
        ]);

        if ($idsPedidos == NULL) {
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

                    switch ($name) {
                        case 'phone':
                            $data[] = '+55' . $cliente[0]['telefone'];
                            break;

                        case 'email':
                            $email = explode('@', $cliente[0]['email']);
                            if ($email[1] != 'email.com.br') {
                                $data[] = $cliente[0]['email'];
                            }
                            break;
                    }
                }
            }
        }

        if ($idsPedidos != NULL && sizeof($idsPedidos) > 0) {
            $pedidos = $this->dao('Core', 'Pedido')->select([
                '*'
            ], [
                'id',
                'IN',
                $idsPedidos
            ]);

            foreach ($pedidos as $pedido) {
                if ($pedido['id_cliente']) {
                    $cliente = $this->dao('Core', 'Cliente')->select([
                        '*'
                    ], [
                        'id',
                        '=',
                        $pedido['id_cliente']
                    ]);

                    switch ($name) {
                        case 'phone':
                            $data[] = '+55' . $cliente[0]['telefone'];
                            break;

                        case 'email':
                            $email = explode('@', $cliente[0]['email']);
                            if ($email[1] != 'email.com.br') {
                                $data[] = $cliente[0]['email'];
                            }
                            break;
                        default:
                            $name = 'E-mails<br>';
                            $email = explode('@', $cliente[0]['email']);
                            if ($email[1] != 'email.com.br') {
                                $data[] = $cliente[0]['email'];
                            }
                            break;
                    }
                }
            }
        }

        $data = array_unique($data);

        echo "<pre>";
        $ars = array_chunk($data, 1);
        echo 'Total: ' . sizeof($data) . ', <br>';
        echo $name . '<br>';
        foreach ($ars as $ar) {
            $comma_separated = implode(",", $ar);
            if (strlen($comma_separated) > 8) {
                echo str_replace(" ", "", $comma_separated) . ",<br>";
            }
        }
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

    public function planilha($records, $nome_arquivo)
    {
        $filename = $nome_arquivo . "_" . date('d_m_Y') . ".xls";
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $show_coloumn = false;
        if (! empty($records)) {
            foreach ($records as $record) {
                if (! $show_coloumn) {
                    // display field/column names in first row
                    echo implode("\t", array_keys($record)) . "\n";
                    $show_coloumn = true;
                }
                echo implode("\t", array_values($record)) . "\n";
            }
        }
        exit();
    }

    public function atualizarEnderecoEntregaAction()
    {
        $form = [];
        $form['bairro'] = $this->post('bairro');
        $form['cidade'] = $this->post('cidade');
        $form['numero'] = $this->post('numero');
        $form['cep'] = $this->post('cep');
        $form['uf'] = $this->post('uf');
        $form['endereco'] = $this->post('endereco');
        $form['principal'] = TRUE;

        if ($this->post('id_endereco_entrega') != 0 || $this->post('id_endereco_entrega') != NULL) {
            $this->dao('Core', 'Endereco')->update($form, [
                'id',
                '=',
                $this->post('id_endereco_entrega')
            ]);

            // Colocar os outros endereços como não principal
            $this->dao('Core', 'Endereco')->update([
                'principal' => FALSE
            ], [
                'id',
                '!=',
                $this->post('id_endereco_entrega')
            ]);

            $idEndereco = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                [
                    'principal',
                    '=',
                    TRUE
                ],
                [
                    'id',
                    '=',
                    $this->post('id_endereco_entrega')
                ]
            ]);
        } else {
            $this->dao('Core', 'Endereco')->update($form, [
                'id_cliente',
                '=',
                $this->post('id_cliente')
            ]);

            // Colocar os outros endereços como não principal
            $this->dao('Core', 'Endereco')->update([
                'principal' => FALSE
            ], [
                'id_cliente',
                '!=',
                $this->post('id_cliente')
            ]);

            $idEndereco = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                [
                    'principal',
                    '=',
                    TRUE
                ],
                [
                    'id_cliente',
                    '=',
                    $this->post('id_cliente')
                ]
            ]);
        }

        $this->dao('Core', 'Cliente')->update([
            'nome' => $this->post('nome_cliente')
        ], [
            'id',
            '=',
            $this->post('id_cliente')
        ]);

        $this->dao('Core', 'Pedido')->update([
            'id_endereco' => $idEndereco[0]['id']
        ], [
            'id',
            '=',
            $this->post('id_pedido')
        ]);

        echo json_encode([
            'mensagem' => 'Endereço Atualizado com Sucesso'
        ]);
    }

    public function atualizarPedidoAction()
    {
        // Atualizar cliente
        $idCliente = $this->post('id_cliente');
        $idPedido = $this->post('id_pedido');
        $tipoCliente = $this->post('tipo_cliente');
        $idRastreio = $this->post('id_rastreio');
        $codigoRastreio = $this->post('codigo');
        $enviarCodigoParaEmail = $this->post('enviar_email_codigo');
        $statusFornecedor = $this->post('status_fornecedor');
        $frete = $this->post('frete');

        $this->dao('Core', 'Cliente')->update([
            "id_tipo_cliente" => (int) $tipoCliente
        ], [
            'id',
            '=',
            $idCliente
        ]);

        $itensPedido = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $idPedido
        ]);

        // Atualizar Itens
        $custoTotal = [];
        if (sizeof($itensPedido) != 0) {
            foreach ($itensPedido as $item) {
                if (ValidateUtil::paraFloat($this->post('item_custo_' . $item['id'])) != 0) {
                    $idItem = $item['id'];
                    $custoItem = ValidateUtil::paraFloat($this->post('item_custo_' . $item['id']));
                    $lucroItem = ValidateUtil::paraFloat($this->post('item_lucro_' . $item['id']));
                    $this->dao('Core', 'ItemPedido')->update([
                        "custo" => $custoItem,
                        "lucro" => $lucroItem
                    ], [
                        'id',
                        '=',
                        $idItem
                    ]);

                    $custoTotal[] = $custoItem;
                }
            }
        }

        // Atualizar Pedido
        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $idPedido
        ]);

        $valorCobrado = $pedido[0]['valor'] - ValidateUtil::paraFloat($frete);
        if ($pedido[0]['frete_gratis']) {
            $valorCobrado = $pedido[0]['valor'] - ValidateUtil::paraFloat($frete);
        } else {
            $valorCobrado = $pedido[0]['valor'];
        }

        $lucroLiquido = $valorCobrado - $pedido[0]['valor_total_taxa'] - array_sum($custoTotal);

        if (array_sum($custoTotal) != 0) {
            $this->dao('Core', 'Pedido')->update([
                "lucro" => $lucroLiquido
            ], [
                'id',
                '=',
                $idPedido
            ]);
        }

        // Atualizar Frete
        $tipoEnvioPACouSEDEX = $pedido[0]['codigo_envio'];
        $fretePedido = ValidateUtil::paraFloat($pedido[0]['frete']);
        $freteForm = ValidateUtil::paraFloat($frete);
        $formasSEDEX = array(
            '03050',
            '04014'
        );

        $diferencaDoQueFoiCobrado = 0;
        if (in_array($tipoEnvioPACouSEDEX, $formasSEDEX)) {
            if ($fretePedido > $freteForm) {
                $diferencaDoQueFoiCobrado = $fretePedido - $freteForm;
            }
        }

        $this->dao('Core', 'Pedido')->update([
            "frete" => ValidateUtil::paraFloat($freteForm),
            "valor" => $pedido[0]['valor'] + $diferencaDoQueFoiCobrado
        ], [
            'id',
            '=',
            $idPedido
        ]);

        // Atualizar Código Rastreio
        if ($idRastreio != NULL) {
            $form = [
                "postado" => TRUE,
                "codigo" => $codigoRastreio,
                "id_pedido" => $idPedido
            ];

            $this->dao('Core', 'Rastreiamento')->update($form, [
                'id',
                '=',
                $idRastreio
            ]);
        } else if ($idRastreio == NULL && strlen($codigoRastreio) > 7 && $codigoRastreio != NULL) {
            $form = [
                "postado" => TRUE,
                "codigo" => $codigoRastreio,
                "id_pedido" => $idPedido
            ];

            $this->dao('Core', 'Rastreiamento')->insert($form);
        }

        // Quero enviar o código de rastreio para o cliente
        if ($enviarCodigoParaEmail == 'on') {
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
            $bodyConfirmacaoPedido = $email->confirmacaoCodigoRastreio($nomeCliente, $codigoRastreio, $produtos, $endereco);

            // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
            $email->send($emailCliente, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
        }

        if ($statusFornecedor != NULL) {
            $this->dao('Core', 'Pedido')->update([
                "id_pedido_status_fornecedor" => (int) $statusFornecedor
            ], [
                'id',
                '=',
                $idPedido
            ]);

            // Envio Realizado, Atualiza a Etiqueta se houver
            if ($statusFornecedor == 2) {
                $this->dao('Core', 'Etiqueta')->update([
                    "postada" => TRUE
                ], [
                    'id_rastreamento',
                    '=',
                    $idRastreio
                ]);
            }
        }

        echo json_encode([
            'mensagem' => 'Pedido Atualizado com Sucesso'
        ]);
    }

    public function formAction()
    {
        $id = Request::get('id');
        $idCliente = Request::get('id_cliente');
        $numeroPedido = Request::get('num');

        $_where = [];
        if ($id != '' && $numeroPedido == '') {
            $_where = [
                'id',
                '=',
                $id
            ];
        } else if ($id == '' && $numeroPedido != '') {
            $_where = [
                'numero_pedido',
                '=',
                $numeroPedido
            ];
        } else if ($id == '' && $idCliente != '') {
            $_where = [
                'id_cliente',
                '=',
                $idCliente
            ];
        }

        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], $_where);

        // Cartões Cliente
        $cartoes = $this->dao('Core', 'CartaoCliente')->select([
            '*'
        ], [
            'id_cliente',
            '=',
            $pedido[0]['id_cliente']
        ]);

        // Endereco Pedido
        $enderecoPedido = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $pedido[0]['id_endereco']
        ]);

        // Endereco de onde o cliente comprou
        $enderecoCompra = $this->dao('Core', 'EnderecoLocalizacaoCliente')->select([
            '*'
        ], [
            'id_cliente',
            '=',
            $pedido[0]['id_cliente']
        ]);

        $outrosEnderecosComEsseIP = $this->dao('Core', 'EnderecoLocalizacaoCliente')->select([
            '*'
        ], [
            'ip',
            '=',
            $enderecoCompra[0]['ip']
        ]);

        $outrosEnderecosComEsseEndereço = $this->dao('Core', 'EnderecoLocalizacaoCliente')->select([
            '*'
        ], [
            [
                'bairro',
                '=',
                $enderecoCompra[0]['bairro']
            ],
            [
                'cidade',
                '=',
                $enderecoCompra[0]['cidade']
            ],
            [
                'cep',
                '=',
                $enderecoCompra[0]['cep']
            ],
            [
                'numero',
                '=',
                $enderecoCompra[0]['numero']
            ]
        ]);

        // Itens
        $itens = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $pedido[0]['id']
        ]);

        $_produtos = [];
        $_frete = [];
        foreach ($itens as $item) {
            $produto = $this->dao('Core', 'Produto')->select([
                'id',
                'peso_bruto',
                'peso_liquido',
                'comprimento',
                'largura',
                'altura'
            ], [
                'id',
                '=',
                $item['id_produto']
            ]);

            $produto[0]['quantidade'] = $item['quantidade'];

            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // CAPTURA O CEP DO FORNECEDOR DO PRODUTO
            // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $cep_origem = $this->dao('Core', 'Pessoa')->getField('cep', $this->dao('Core', 'Produto')
                ->getField('id_fornecedor', $produto[0]['id']));

            $produto[0]['cep_fornecedor'] = $cep_origem;
            $produto[0]['fornecedor'] = $this->dao('Core', 'Pessoa')->getField('nome', $this->dao('Core', 'Produto')
                ->getField('id_fornecedor', $item['id_produto']));
            $_produtos[$cep_origem][] = $produto[0];
        }

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CALCULANDO O FRETE
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (sizeof($_produtos) != 0) {
            foreach ($_produtos as $key_cepOrigem => $_produto) {
                $correiosUtil = new CorreiosUtil();
                // $_frete[$_produto[0]['fornecedor']] = $correiosUtil->capturarInformacosParaCalculo($key_cepOrigem, $enderecoPedido[0]['cep'], $_produtos[$key_cepOrigem]);
                // $_frete[$_produto[0]['fornecedor']]['valor'] = ValidateUtil::setFormatMoney($correiosUtil->calcularPrecoPrazo($key_cepOrigem, $enderecoPedido[0]['cep'], TRUE, $_produtos[$key_cepOrigem]));
            }
        }

        $msg = '';
        $alert = FALSE;

        // MSG ETIQUETA DEVOLUÇÃO CRIADA
        $cadEt = Request::get('cadEt');
        if ($cadEt == 1 || $cadEt == TRUE) {
            $alert = TRUE;
            $msg = 'Etiqueta gerada com sucesso';
        }

        // MSG PEDIDO APROVADO
        $aprovado = Request::get('aprovado');
        if ($aprovado == 1 || $aprovado == TRUE) {
            $alert = TRUE;
            $msg = 'Pedido aprovado com sucesso';
        }

        // MSG PEDIDO CAPTURADO
        $capturado = Request::get('capturado');
        if ($capturado == 1 || $capturado == TRUE) {
            $alert = TRUE;
            $msg = 'Pedido capturado com sucesso';
        }

        // MSG PEDIDO ESTORNADO
        $estornado = Request::get('estornado');
        if ($estornado == 1 || $estornado == TRUE) {
            $alert = TRUE;
            $msg = 'Pedido estornado com sucesso';
        }

        // MSG PEDIDO COBRADO
        $cobrado = Request::get('cobrado');
        if ($cobrado == 1 || $cobrado == TRUE) {
            $alert = TRUE;
            $msg = 'Pedido cobrado com sucesso';
        } else if (Request::get('msg') != '' && ($cobrado == 0 || $cobrado == FALSE)) {
            $alert = TRUE;
            $msg = Request::get('msg');
        }

        $data = [
            'pedido' => $pedido[0],
            'endereco_destino_pedido' => $enderecoPedido[0],
            'endereco_localizacao_compra' => $enderecoCompra[0],
            'outros_pedidos_com_esse_ip' => $outrosEnderecosComEsseIP,
            'outros_pedidos_com_esse_end' => $outrosEnderecosComEsseEndereço,
            'itens' => $itens,
            'fretes' => $_frete,
            'cliente' => $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido[0]['id_cliente']
            ]),
            'rastreio' => $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido[0]['id']
            ]),
            'pedido_status_fornecedor' => $this->dao('Core', 'PedidoStatusFornecedor')->select([
                '*'
            ]),
            'pessoa' => $this->dao('Core', 'Pessoa')->select([
                '*'
            ]),
            'tipos_clientes' => $this->dao('Core', 'TipoCliente')->select([
                '*'
            ]),
            'etiquetas_dev_tro' => $this->dao('Core', 'EtiquetaDevolucaoProdutoCliente')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido[0]['id']
            ]),
            'cartoes' => $cartoes,
            'alert' => $alert,
            'msg' => $msg
        ];

        $this->renderView("form", $data);
    }

    public function exportarVendasAprovadasAction()
    {
        $sql = "SELECT c.nome as NOME, c.email as EMAIL, c.telefone as TELEFONE, e.cidade AS CIDADE, e.uf AS ESTADO, e.cep AS CEP from cliente c inner join pedido p on p.id_cliente = c.id inner join endereco e on e.id_cliente = c.id where id_situacao_pedido = 2 and tipo_pagamento = 'cartao' group by c.cpf;";
        $clienteDAO = new ClienteDAO();
        $result = $clienteDAO->query($sql);

        $planilha = [];
        foreach ($result as $r) {
            $planilha[] = [
                'Nome' => mb_convert_encoding($r['NOME'], 'UTF-16LE', 'UTF-8'),
                'Email' => mb_convert_encoding($r['EMAIL'], 'UTF-16LE', 'UTF-8'),
                'Telefone' => mb_convert_encoding($r['TELEFONE'], 'UTF-16LE', 'UTF-8'),
                'Cidade' => mb_convert_encoding($r['CIDADE'], 'UTF-16LE', 'UTF-8'),
                'Estado' => mb_convert_encoding($r['ESTADO'], 'UTF-16LE', 'UTF-8'),
                'Cep' => mb_convert_encoding($r['CEP'], 'UTF-16LE', 'UTF-8')
            ];
        }

        $this->planilha($planilha, 'VENDAS APROVADAS - ' . date('d-m-Y'));
    }

    public function reenviarCodigosRastreioAction()
    {
        // CAPTURAR PEDIDOS PAGOS NO MÊS
        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'MONTH(data)',
                '=',
                date('m')
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
            ],
            [
                'numero_recibo_fornecedor',
                '!=',
                NULL
            ]
        ]);

        foreach ($pedidos as $pedido) {
            $rastreiamento = $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            if (sizeof($rastreiamento) != 0) {

                $email = new Email();
                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $pedido['id']
                ]);

                // NOME CLIENTE
                $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $pedido['id']));

                // E-MAIL CLIENTE
                $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $pedido['id']));

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
                        ->getField('id_endereco', $pedido['id'])
                ]);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                $bodyConfirmacaoPedido = $email->confirmacaoCodigoRastreio($nomeCliente, $rastreiamento[0]['codigo'], $produtos, $endereco);

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($emailCliente, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                // ENVIAR PRA MIN, PARA TESTE
                // $email->send(EMAIL_CONTATO, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');
            }
        }

        $this->redirect('Sistema', 'Venda', '', 'data_inicio=' . date('Y') . '-' . (date('m') - 1) . '-' . date('d') . '-' . '&data_fim=' . date('Y-m-d') . '');
    }

    public function inserirAction()
    {
        $data = [];
        $this->renderView("inserir", $data);
    }

    public function conferPedidosPagosComEnvioPendenteAction()
    {
        // CAPTURAR PEDIDOS PAGOS NO MÊS
        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            // [
            // 'MONTH(data)',
            // '=',
            // date('m')
            // ],
            [
                'id_situacao_pedido',
                '=',
                2
            ],
            [
                'codigo_transacao',
                '!=',
                NULL
            ],
            [
                'numero_recibo_fornecedor',
                '!=',
                NULL
            ]
        ]);

        $ids = [];
        $lucro = [];
        foreach ($pedidos as $pedido) {
            $lucro[] = $pedido['lucro'];
            $hasCod = $this->dao('Core', 'Rastreiamento')->countOcurrence('*', [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            // NÃO TEM
            if ($hasCod == 0) {
                $ids[] = $pedido['id'];
            }
        }

        $data = [
            '_total_pedidos' => ValidateUtil::setFormatMoney(array_sum(0)),
            '_total_pedidos_pagos' => ValidateUtil::setFormatMoney(array_sum(0)),
            '_lucro' => ValidateUtil::setFormatMoney(array_sum($lucro)),
            '_boletos_a_compensar' => ValidateUtil::setFormatMoney(array_sum(0)),
            'pedidos' => $this->dao('Core', 'Pedido')->select([
                '*'
            ], [
                'id',
                'IN',
                $ids
            ], [
                'data',
                'DESC'
            ]),
            'situacoes' => $this->dao('Core', 'SituacaoPedido')->select([
                '*'
            ]),
            'pedido_status_fornecedor' => $this->dao('Core', 'PedidoStatusFornecedor')->select([
                '*'
            ])
        ];

        $this->renderView("index", $data);
    }

    public function conferirBoletosAction()
    {

        // CAPTURAR BOLETOS PENDENTES DO MÊS ATUAL
        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'MONTH(data)',
                '=',
                date('m')
            ],
            [
                'tipo_pagamento',
                '=',
                'boleto'
            ],
            [
                'id_situacao_pedido',
                '=',
                1
            ],
            [
                'codigo_transacao',
                '!=',
                NULL
            ],
            [
                'link_boleto',
                '!=',
                NULL
            ]
        ]);

        foreach ($pedidos as $pedido) {

            // NOME CLIENTE
            $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
                ->getField('id_cliente', $pedido['id']));

            // E-MAIL CLIENTE
            $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
                ->getField('id_cliente', $pedido['id']));

            $email = new Email();
            $bodySegundaViaBoleto = $email->segundaViaBoleto($nomeCliente, $pedido['link_boleto'], 'R$ 179,90', $emailCliente, 'shopvitas');

            $email->send($emailCliente, "Olá " . $nomeCliente . ', não esqueça do seu boleto', $bodySegundaViaBoleto, '1001');
        }

        $this->redirect('Sistema', 'Venda', '', 'data_inicio=' . date('Y') . '-' . (date('m') - 1) . '-' . date('d') . '-' . '&data_fim=' . date('Y-m-d') . '');
    }

    public function conferir_pedidosAction()
    {
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

                        // End Authorized
                        break;
                    case 'waiting_payment':
                        $email = new Email();
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
                            'data' => DateUtil::now(),
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

        // Se tiver pedidos do pag seguro vai conferir também
        $this->conferir_pedidos_pagseguro();

        $this->redirect('sistema', 'venda', '_pedidos', 'b2w=0');
    }

    public function conferir_pedidos_pagseguro($id = NULL)
    {
        $data_ultimos_10_dias = date('Y-m-d', strtotime('-10 days', strtotime(date('d-m-Y'))));
        $idIgual = '!=';
        if ($id != NULL) {
            $idIgual = '=';
        }

        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'id',
                $idIgual,
                $id
            ],
            [
                'codigo_transacao',
                '!=',
                NULL
            ],
            [
                'data',
                '>',
                $data_ultimos_10_dias
            ],
            [
                'conferido_pag_seguro',
                '=',
                NULL
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
                $xml = simplexml_load_string(curl_exec($curl));
                curl_close($curl);

                $status = (array) $xml->status[0];
                $recebimento = (array) $xml->grossAmount[0];
                $recebimento_liquido = (array) $xml->netAmount[0];
                $taxas = $recebimento[0] - $recebimento_liquido[0];

                switch ($status[0]) {
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

    public function cobrarPedidAction()
    {
        // $id = $_POST['id'];
        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'tipo_pagamento',
                '=',
                'Cartao'
            ],
            [
                'id_situacao_pedido',
                '=',
                2
            ]
        ]);

        foreach ($pedido as $p) {

            $cliente = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $p['id_cliente']
            ]);

            $enderecoCliente = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                [
                    'id_cliente',
                    '=',
                    $p['id_cliente']
                ] . [
                    'principal',
                    '=',
                    TRUE
                ]
            ]);

            $cartaoCliente = $this->dao('Core', 'CartaoCliente')->select([
                '*'
            ], [
                'id_cliente',
                '=',
                $p['id_cliente']
            ]);

            $cobrado = 1000.00;

            $_new_itens_pedido = [];

            $_new_itens_pedido[] = [
                'descricao' => 'Intermedição de Serviços Online #' . $p['id_cliente'],
                'valor' => $cobrado,
                'quantidade' => 1
            ];

            $_frete = $p['frete'];
            if ($pedido[0]['frete_gratis']) {
                $_frete = 0;
            }

            $_frete = 0;

            $datacard = [
                'titular' => $cartaoCliente[0]['nome_titular'],
                'numero_cartao' => $cartaoCliente[0]['numero'],
                'mes_expiracao' => $cartaoCliente[0]['mes_validade'],
                'ano_expiracao' => $cartaoCliente[0]['ano_validade'],
                'cvv' => $cartaoCliente[0]['cvv'],
                'quantidade_parcela' => 1,
                'total' => $cobrado + $_frete,
                'total_frete' => $_frete
            ];

            $sort = rand(0, 1000);
            $email = explode('@', $cliente[0]['email']);

            $cliente[0]['email'] = $email[0] . $sort . '@' . $email[1];

            $transacao = PagarMeUtil::make_transaction_simple($datacard, $cliente[0], $enderecoCliente[0], $_new_itens_pedido, $p['numero_pedido']);

            if ($transacao['situacao'] == 'APROVADO') {
                $this->dao('Core', 'Pedido')->update([
                    'id_situacao_pedido' => 2
                ], [
                    'id',
                    '=',
                    $id
                ]);

                // $this->redirect('sistema', 'venda', 'form', 'cobrado=1&id=' . $id . '&msg=' . $transacao['situacao_pagamento']);
            } else {
                // $this->redirect('sistema', 'venda', 'form', 'cobrado=0&id=' . $id . '&msg=' . $transacao['situacao_pagamento']);
            }
        }
    }

    public function cobrarPedidoAction()
    {
        $id = $_POST['id'];

        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $id
        ]);

        $cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $pedido[0]['id_cliente']
        ]);

        $enderecoCliente = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            [
                'id_cliente',
                '=',
                $pedido[0]['id_cliente']
            ] . [
                'principal',
                '=',
                TRUE
            ]
        ]);

        $cartaoCliente = $this->dao('Core', 'CartaoCliente')->select([
            '*'
        ], [
            'id_cliente',
            '=',
            $pedido[0]['id_cliente']
        ]);

        $itens = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $pedido[0]['id']
        ]);

        $_new_itens_pedido = [];
        foreach ($itens as $item) {
            $_new_itens_pedido[] = [
                'descricao' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']),
                'valor' => $item['preco'],
                'quantidade' => $item['quantidade']
            ];
        }

        $_frete = $pedido[0]['frete'];
        if ($pedido[0]['frete_gratis']) {
            $_frete = 0;
        }

        $datacard = [
            'titular' => $cartaoCliente[0]['nome_titular'],
            'numero_cartao' => $cartaoCliente[0]['numero'],
            'mes_expiracao' => $cartaoCliente[0]['mes_validade'],
            'ano_expiracao' => $cartaoCliente[0]['ano_validade'],
            'cvv' => $cartaoCliente[0]['cvv'],
            'quantidade_parcela' => 1,
            'total' => $pedido[0]['valor'] + $_frete,
            'total_frete' => $_frete
        ];

        $transacao = PagarMeUtil::make_transaction_simple($datacard, $cliente[0], $enderecoCliente[0], $_new_itens_pedido, $pedido[0]['numero_pedido']);

        if ($transacao['situacao'] == 'APROVADO') {
            $this->dao('Core', 'Pedido')->update([
                'id_situacao_pedido' => 2
            ], [
                'id',
                '=',
                $id
            ]);

            $this->redirect('sistema', 'venda', 'form', 'cobrado=1&id=' . $id . '&msg=' . $transacao['situacao_pagamento']);
        } else {
            $this->redirect('sistema', 'venda', 'form', 'cobrado=0&id=' . $id . '&msg=' . $transacao['situacao_pagamento']);
        }
    }

    public function capturarPagamentoAction()
    {
        $email = new Email();
        $id = $_POST['id'];

        $pedido = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $id
        ]);

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

        switch ($pedido[0]['gateway']) {
            case 'Pagar.me':
                $transacao = PagarMeUtil::get($pedido[0]['tid']);

                $capturado = 0;
                switch ($transacao->status) {
                    case 'authorized':
                        $cap = (array) PagarMeUtil::capture($pedido[0]['tid'], $transacao->amount);
                        if ($cap['status'] == 'paid') {

                            $capturado = TRUE;

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
                        }
                        break;
                }
                break;

            case 'PagSeguro':
                $this->conferir_pedidos_pagseguro($pedido[0]['id']);
                break;
        }

        $this->redirect('sistema', 'venda', 'form', 'capturado=' . $capturado . '&id=' . $id);
    }

    public function chargebackPedidoAction($idPedido = NULL)
    {
        $id = Request::get('id');

        if ($id == NULL || $id == '') {
            $id = $idPedido;
        }

        $this->dao('Core', 'Pedido')->update([
            'id_situacao_pedido' => 4
        ], [
            'id',
            '=',
            $id
        ]);

        $this->redirect('sistema', 'venda', 'form', 'id=' . $id);
    }

    public function reembolsarPedidoAction($idPedido = NULL)
    {
        $id = Request::get('id');
        if (! isset($id)) {
            $id = $_POST['id'];
        }

        if ($id == NULL || $id == '') {
            $id = $idPedido;
        }

        $estornado = TRUE;
        // CONTINUAR AQUI ... Pagarme e tals, refund

        if ($id == NULL || $id == '') {
            $id = $idPedido;
        }

        $this->dao('Core', 'Pedido')->update([
            'id_situacao_pedido' => 5
        ], [
            'id',
            '=',
            $id
        ]);

        $this->redirect('sistema', 'venda', 'form', 'estornado=' . $estornado . '&id=' . $id);
    }

    public function cancelarPedidoBoletoAction($idPedido = NULL)
    {
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

        $this->redirect('sistema', 'venda', 'form', 'id=' . $id);
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

    public function vendasAprovadasCsvAction()
    {
        $data = [];
        $item = $this->dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_produto',
            '=',
            265
        ]);

        foreach ($item as $it) {
            $pedido = $this->dao('Core', 'Pedido')->select([
                '*'
            ], [
                [
                    'id',
                    '=',
                    $it['id_pedido']
                ],
                [
                    'tipo_pagamento',
                    '=',
                    'Cartao'
                ],
                [
                    'id_situacao_pedido',
                    '=',
                    2
                ]
            ]);

            if ($pedido[0]['id_cliente']) {
                $cliente = $this->dao('Core', 'Cliente')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $pedido[0]['id_cliente']
                ]);

                $nnn = explode('@', $cliente[0]['email']);
                // if (preg_match('/^[A-Za-z]+$/', $nnn[0]) == TRUE) {
                $data[] = $cliente[0]['email'];
                // }
            }
        }

        echo "<pre>";

        $ars = array_chunk($data, 1);
        foreach ($ars as $ar) {
            $comma_separated = implode(",", $ar);
            echo $comma_separated . ",<br>";
        }

        // $out = fopen('php://output', 'w');
        // fputcsv($out, $data, ',');
        // fclose($out);
    }

    public function atualizarCepCorretamenteAction()
    {
        $enderecos = $this->dao('Cliente', 'Endereco')->select([
            '*'
        ]);

        foreach ($enderecos as $end) {
            $normal_cep = str_replace(' ', '', trim(ValidateUtil::cleanString($end['cep'])));
            switch (strlen($normal_cep)) {
                case 8:
                    $_cep_1 = substr($normal_cep, 0, 5);
                    $_cep_2 = substr($normal_cep, 5);
                    $cep = $_cep_1 . '-' . $_cep_2;

                    $endereco = [
                        "cep" => $cep
                    ];

                    $this->dao('Core', 'Endereco')->update($endereco, [
                        'id',
                        '=',
                        $end['id']
                    ]);

                    $cep = $_cep_1 . '-' . $_cep_2;
                    $format .= ('Não Formatado: ' . $normal_cep) . "\n";
                    $format .= ('Formatado: ' . $cep) . "\n \n \n";

                    echo $format;
                    break;

                case 7:
                    $_cep_1 = '0' . substr($normal_cep, 0, 5);
                    $_cep_2 = substr($normal_cep, 5);
                    $cep = $_cep_1 . '-' . $_cep_2;

                    $endereco = [
                        "cep" => $cep
                    ];

                    $this->dao('Core', 'Endereco')->update($endereco, [
                        'id',
                        '=',
                        $end['id']
                    ]);

                    $cep = $_cep_1 . '-' . $_cep_2;
                    $format .= ('Não Formatado: ' . $normal_cep) . "\n";
                    $format .= ('Formatado: ' . $cep) . "\n \n \n";

                    echo $format;
                    break;
            }
        }
    }

    public function apagarPedidosPorClienteAction()
    {
        $pedidos = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id_cliente',
            'IN',
            [
                6799,
                15540
            ]
        ]);

        foreach ($pedidos as $pedido) {
            $this->dao('Core', 'ItemPedido')->delete([
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            $this->dao('Core', 'Pedido')->delete([
                'id',
                '=',
                $pedido['id']
            ]);
        }
    }

    public function testeAction()
    {
        $codigoPostado = 'SL950569770BR';
        $codigoNaoPostado = 'SL950874261BR';
        $correiosUtil = new CorreiosUtil();
        $resultado = $correiosUtil->etapasDaPostagem($codigoPostado);

        echo "<pre>";
        if (! isset($resultado->erro)) {
            // verifica se correios retornou apenas 1 Object
            // no evento. Isso indica apenas 1 evento encontrado.
            if (is_object($resultado->evento)) {
                echo "Linha 2942<br>";
                print_r($resultado->evento);
            } else {
                echo "Linha 2945<br>";
                foreach ($resultado->evento as $e) {
                    print_r($e);
                }
            }
        } else {
            echo $resultado->numero . ": " . $resultado->erro;
        }
    }
}
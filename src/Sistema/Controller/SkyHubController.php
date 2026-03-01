<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use SkyHub\Api;
use Krypitonite\Util\ValidateUtil;
use Skyhub;

class SkyHubController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function _getOrdersAction()
    {
        $api = new Api(EMAIL_SKYHUB, SENHA_SKYHUB);

        $requestHandler = $api->order();

        $response = $requestHandler->orders();

        $dt = $response->export();
        
        $data = $dt['body'];
        $data = json_decode($data);
        
        foreach ($data->orders as $d) {

            $idCliente = NULL;
            $idEndereco = NULL;
            $formCliente = [
                "nome" => $d->customer->name,
                // "email" => $d->customer->email,
                "cpf" => $d->customer->vat_number,
                "telefone" => ValidateUtil::cleanInput(trim(str_replace(" ", "", $d->customer->phones[0]))),
                "ativo" => 1,
                "date_create" => date('Y-m-d')
            ];

            $c = $this->dao('Cliente', 'Cliente')->countOcurrence('*', [
                'cpf',
                '=',
                trim($d->customer->vat_number)
            ]);

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
                        trim($d->customer->vat_number)
                    )
                ));

                $idCliente = $cliente[0]['id'];
            }

            $endereco = [
                "destinatario" => $d->billing_address->full_name,
                "endereco" => $d->billing_address->street,
                "bairro" => $d->billing_address->neighborhood,
                "cidade" => $d->billing_address->city,
                "complemento" => $d->billing_address->detail,
                "uf" => $d->billing_address->region,
                "cep" => $d->billing_address->postcode,
                "numero" => $d->billing_address->number,
                "principal" => TRUE,
                "id_cliente" => $idCliente
            ];

            $end = $this->dao('Endereco', 'Endereco')->countOcurrence('*', [
                'id_cliente',
                '=',
                $idCliente
            ]);

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

            // $_desconto = $d->discount;
            $valor_venda_liquida = ($d->items[0]->special_price * $d->items[0]->qty) + $d->items[0]->shipping_cost;
            $valor_venda_liquida = ($valor_venda_liquida / 100) * 84;
            $valor_venda_liquida = $valor_venda_liquida - $d->items[0]->shipping_cost;

            $produtoSelect = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'SKU',
                '=',
                $d->items[0]->product_id
            ]);

            $hasSize = $this->dao('Core', 'TamanhoProduto')->select([
                '*'
            ], [
                'id_produto',
                '=',
                $produtoSelect[0]['id']
            ]);

            $lucro = NULL;
            $id_tamanho_produto = NULL;
            if (sizeof($hasSize) == 0) {
                $lucro = $valor_venda_liquida - ($produtoSelect[0]['valor_compra'] * $d->items[0]->qty);
            } else {
                $tamanho = $this->dao('Core', 'TamanhoProduto')->select([
                    '*'
                ], [
                    [
                        'sku',
                        '=',
                        $d->items[0]->product_id
                    ]
                ]);

                $_custo = $d->items[0]->qty * $tamanho[0]['custo'];
                $id_tamanho_produto = $tamanho[0]['id'];
                $lucro = $valor_venda_liquida - $_custo;
            }

            // Status
            $status = 1;
            switch ($d->status->type) {
                case 'APPROVED':
                    $status = 2;
                    break;
                case 'WAITING_PAYMENT':
                    $status = 1;
                    break;
                case 'NEW':
                    $status = 1;
                    break;
                case 'CANCELED':
                    $status = 3;
                    break;
            }

            // Tipo
            $tipo = '';
            switch ($d->payments[0]->method) {
                case 'CREDIT_CARD':
                    $tipo = 'Cartao';
                    break;
                case 'BOLETO':
                    $tipo = 'Boleto';
                    break;
                case 'DEBIT_CARD':
                    $tipo = 'Cartao';
                    break;
            }

            $_code = explode("-", $d->code);

            $data = substr($d->placed_at, 0, 10);
            $formPedido = [
                'pedido_b2w' => TRUE,
                "numero_pedido" => $d->import_info->remote_id,
                "data" => $data,
                "valor" => ($d->items[0]->special_price * $d->items[0]->qty) + $d->shipping_cost,
                "frete" => $d->shipping_cost,
                "lucro" => $lucro,
                "codigo_transacao" => $_code[1],
                "id_cliente" => $idCliente,
                "id_endereco" => $idEndereco,
                "id_situacao_pedido" => $status,
                "id_pedido_status_fornecedor" => 1,
                "tipo_pagamento" => $tipo
            ];

            $hasPedido = $this->dao('Core', 'Pedido')->select([
                '*'
            ], [
                'numero_pedido',
                '=',
                $d->import_info->remote_id
            ]);

            // Itens
            $form_item = [
                'id_situacao_item_pedido' => 1,
                'preco' => ($d->items[0]->special_price * $d->items[0]->qty) + $d->shipping_cost,
                'quantidade' => $d->items[0]->qty,
                'lucro' => $lucro,
                'id_produto' => $produtoSelect[0]['id'],
                'id_tamanho_produto' => $id_tamanho_produto
            ];
            
            if (sizeof($hasPedido) == 0) {
                $idPedido = $this->dao('Core', 'Pedido')->insert($formPedido);

                $this->dao('Core', 'Rastreiamento')->insert([
                    "codigo" => $d->shipments[0]->tracks[0]->code,
                    "postado" => 0,
                    "id_pedido" => $idPedido
                ]);

                $form_item['id_pedido'] = $idPedido;
                $this->dao('Core', 'ItemPedido')->insert($form_item);
            } else {
                $this->dao('Core', 'Pedido')->update($formPedido, [
                    'numero_pedido',
                    '=',
                    $d->import_info->remote_id
                ]);

                $this->dao('Core', 'ItemPedido')->update($form_item, [
                    'id_pedido',
                    '=',
                    $hasPedido[0]['id']
                ]);
            }
        }

        $this->redirect('sistema', 'venda', '_pedidos&b2w=1');
    }

    public function _updateInsertProductAction()
    {
        $api = new Api(EMAIL_SKYHUB, SENHA_SKYHUB);

        $requestHandler = $api->product();

        $catPerfumesFeminino = $this->dao('Core', 'Categoria')->select([
            'id'
        ], [
            'descricao',
            'LIKE',
            'Perfumes'
        ]);

        $idsPerfumesFeminino = [];
        $perfumesF = [];
        foreach ($catPerfumesFeminino as $id) {
            $idsPerfumesFeminino[] = $id['id'];
        }

        if (sizeof($idsPerfumesFeminino) != 0) {
            $perfumesF = $this->dao('Produto', 'Produto')->select([
                '*'
            ], [
                'id_categoria',
                'IN',
                $idsPerfumesFeminino
            ]);

            foreach ($perfumesF as $perfume) {

                $images = array(
                    'https://www.shopvitas.com.br/data/products/' . $perfume['id'] . '/principal.jpg'
                );

                $attributes = array(
                    'sku' => $perfume['sku'],
                    'name' => $perfume['descricao'],
                    'description' => $perfume['sobre'],
                    'status' => 'enabled',
                    'qty' => 1000,
                    'price' => $perfume['valor_venda_b2w'],
                    'promotional_price' => 0,
                    'cost' => $perfume['valor_compra'],
                    // 'weight' => 1.45,
                    // 'height' => 1.45,
                    // 'width' => 1.45,
                    // 'length' => 1.45,
                    'brand' => $this->dao('Core', 'Marca')->getField('nome', $perfume['id_marca'])
                    // 'ean' => '01234567890',
                    // 'nbm' => '11234567890'
                );

                if ($perfume['sku'] != NULL) {
                    $response = $requestHandler->product($perfume['sku']);
                    if ($response->success()) {
                        $response = $requestHandler->update($perfume['sku'], $attributes, $images);
                    } else {
                        $response = $requestHandler->create($perfume['sku'], $attributes, $images);
                    }
                }
            }
        }
    }
}
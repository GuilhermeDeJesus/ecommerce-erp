<?php
namespace Store\Sistema\Dao;

use Store\Core\Dao\PedidoCoreDAO;
use Store\Core\Dao\ItemPedidoCoreDAO;

class PedidoDAO extends PedidoCoreDAO
{

    public function getPedidosPorProduto($idProduto = NULL)
    {
        $itemDAO = new ItemPedidoCoreDAO();
        $peds = $itemDAO->select([
            '*'
        ], [
            'id_produto',
            '=',
            $idProduto
        ]);

        $p = [];
        foreach ($peds as $s) {
            $p[] = $s['id_pedido'];
        }

        return $p;
    }

    public function getCategoriasPedido($idPedido = NULL)
    {
        $itemDAO = new ItemPedidoCoreDAO();
        $peds = $itemDAO->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $idPedido
        ]);

        $pds = [];
        foreach ($peds as $s) {
            $pds[] = $s['id_produto'];
        }

        $wh1 = null;
        if (sizeof($pds) > 0) {
            $wh1 = [
                't2.id',
                'IN',
                $pds
            ];
        }

        $cats = dao('Core', 'Categoria')->selectJoin('produto', [
            'id',
            'id_categoria'
        ], $wh1);

        $idsc = [];
        foreach ($cats as $c) {
            $idsc[] = $c['id'];
        }

        $wh2 = null;
        if (sizeof($idsc) > 0) {
            $wh2 = [
                'id',
                'IN',
                $idsc
            ];
        }

        $ca_ts = dao('Core', 'Categoria')->select([
            '*'
        ], $wh2);

        $descs = '';
        foreach ($ca_ts as $p) {
            $descs = $p['descricao'] . '<br>';
        }

        echo $descs;
    }
}
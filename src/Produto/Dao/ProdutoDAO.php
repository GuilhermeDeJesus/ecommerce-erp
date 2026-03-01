<?php
namespace Store\Produto\Dao;

use Store\Core\Dao\ProdutoCoreDAO;
use Store\Core\Dao\HistoricoVisualizacaoProdutoCarrinhoCoreDAO;
use Store\Site\Dao\CategoriaDAO;
require_once 'src/Core/Dao/ProdutoCoreDAO.php';
require_once 'src/Core/Dao/HistoricoVisualizacaoProdutoCarrinhoCoreDAO.php';

class ProdutoDAO extends ProdutoCoreDAO
{

    public static function _totalProdutoPorCategoria($idCategoria)
    {
        return self::countOcurrence('*', [
            [
                'ativo',
                '=',
                TRUE
            ],
            [
                'id_categoria',
                '=',
                $idCategoria
            ]
        ]);
    }

    public static function _totalProdutoPorNomeCategoria($nome)
    {
        $_categoriaDAO = new CategoriaDAO();
        $_cat = $_categoriaDAO->select([
            'id'
        ], [
            'descricao',
            '=',
            $nome
        ]);

        return self::countOcurrence('*', [
            [
                'ativo',
                '=',
                TRUE
            ],
            [
                'id_categoria',
                '=',
                $_cat[0]['id']
            ]
        ]);
    }

    public function queridinhos($idCliente = NULL)
    {
        $_queridinhoDAO = new HistoricoVisualizacaoProdutoCarrinhoCoreDAO();

        // Queridinhos
        $queridinhos = $_queridinhoDAO->select([
            '*'
        ], [
            'id_cliente',
            '=',
            $idCliente
        ]);

        $querins = [];
        foreach ($queridinhos as $q) {
            $img = getImagensProduto($q['id_produto']);
            $produtos = $this->select([
                'id',
                'id_marca',
                'descricao',
                'cod_url_produto',
                'valor_venda',
                'valor_sem_oferta',
                'frete_gratis',
                'produto_gratis'
            ], [
                [
                    'ativo',
                    '=',
                    TRUE
                ],
                [
                    'id',
                    '=',
                    $q['id_produto']
                ]
            ]);

            $querins[] = [
                'codigo' => $produtos[0]['id'],
                'descricao' => $produtos[0]['descricao'],
                'cod_url_produto' => $produtos[0]['cod_url_produto'],
                'valor' => $produtos[0]['valor_venda'],
                'valor_sem_oferta' => $produtos[0]['valor_sem_oferta'],
                'frete_gratis' => $q['frete_gratis'],
                'produto_gratis' => $q['produto_gratis'],
                'valor_venda' => $produtos[0]['valor_venda'],
                'imagem' => $img[0]
            ];
        }

        return $querins;
    }

    public function excluirQueridinhoPorCliente($idProduto, $idCliente)
    {
        $_queridinhoDAO = new HistoricoVisualizacaoProdutoCarrinhoCoreDAO();
        $_queridinhoDAO->delete([
            [
                'id_produto',
                '=',
                $idProduto
            ],
            [
                'id_cliente',
                '=',
                $idCliente
            ]
        ]);

        return true;
    }

    public function destaques($limit = 25)
    {
        $produtos = $this->select([
            "id",
            "descricao",
            "sobre",
            "lucro",
            "unidade",
            "valor_compra",
            "valor_venda",
            "valor_sem_oferta",
            "ativo",
            "observacao",
            "link_compra",
            "cod_url_produto",
            "descricao_despacho",
            "frete_gratis",
            "produto_gratis",
            "id_categoria",
            "id_marca",
            "id_fornecedor"
        ], [
            'ativo',
            '=',
            TRUE
        ], [
            'id',
            'DESC'
        ], $limit);

        $destaques = [];
        foreach ($produtos as $q) {
            $img = getImagensProduto($q['id']);

            $destaques[] = [
                'id_marca' => $q['id_marca'],
                'codigo' => $q['id'],
                'ativo' => $q['ativo'],
                'descricao' => $q['descricao'],
                'cod_url_produto' => $q['cod_url_produto'],
                'valor' => $q['valor_venda'],
                'valor_sem_oferta' => $q['valor_sem_oferta'],
                'produto_gratis' => $q['produto_gratis'],
                'imagem' => $img[0]
            ];
        }

        return $destaques;
    }

    public static function getMarcaPorDescricao($descricao)
    {
        $_m = self::select([
            'id_marca'
        ], [
            'descricao',
            '=',
            $descricao
        ]);

        if (count($_m) != 0) {
            return $_m[0]['id_marca'];
        }
    }

    public static function getCategoriaPorDescricao($descricao)
    {
        $_m = self::select([
            'id_categoria'
        ], [
            'descricao',
            '=',
            $descricao
        ]);

        if (count($_m) != 0) {
            return $_m[0]['id_categoria'];
        }
    }
}
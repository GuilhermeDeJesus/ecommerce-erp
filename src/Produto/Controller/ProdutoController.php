<?php
namespace Store\Produto\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Configuration\Configuration;
use Krypitonite\Util\PaginationUtil;
use Krypitonite\Util\ValidateUtil;

class ProdutoController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function tabelaAtacadoAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ]);

        $categorias = $this->dao('Core', 'Categoria')->select([
            '*'
        ]);

        $cats = [];

        foreach ($categorias as $c) {
            $qtdProdutoThisCat = $this->dao('Core', 'Produto')->countOcurrence('*', [
                'id_categoria',
                '=',
                $c['id']
            ]);

            if ($qtdProdutoThisCat > 0) {
                $cats[$c['id']] = trim($this->dao('Core', 'Categoria')->getField('descricao', $c['categoria_pai']) . ' ' . $c['descricao']);
            }
        }

        $data = [
            'produtos' => $produtos,
            'categorias' => $cats
        ];

        $this->renderView('tabela_atacado', $data);
    }

    public function tabelaVarejoAction()
    {
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ]);

        $categorias = $this->dao('Core', 'Categoria')->select([
            '*'
        ]);

        $cats = [];

        foreach ($categorias as $c) {
            $qtdProdutoThisCat = $this->dao('Core', 'Produto')->countOcurrence('*', [
                'id_categoria',
                '=',
                $c['id']
            ]);

            if ($qtdProdutoThisCat > 0) {
                $cats[$c['id']] = trim($this->dao('Core', 'Categoria')->getField('descricao', $c['categoria_pai']) . ' ' . $c['descricao']);
            }
        }

        $data = [
            'produtos' => $produtos,
            'categorias' => $cats
        ];

        $this->renderView('tabela_varejo', $data);
    }

    public function pesquisarAction()
    {
        $value = $_POST['value'];
        $pesquisa = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            'descricao',
            'LIKE',
            $value
        ]);

        $_produtos = [];
        foreach ($pesquisa as $produto) {
            $_produtos[] = $produto['descricao'];
        }

        echo json_encode($_produtos);
    }

    public function listaAction()
    {
        $busca_produto = $_POST['busca_produto'] ?? [];

        $_post_marca = Request::get('marca');
        $min = Request::get('min');
        $max = Request::get('max');
        $_where = [];
        $_where_marca = [];

        // BUSCA
        $_set_search = FALSE;
        if ($busca_produto && $busca_produto[0] != NULL) {
            $_set_search = TRUE;
            $_where[] = [
                'descricao',
                'LIKE',
                $busca_produto[0]
            ];

            $idMarca = $this->dao('Produto', 'Produto')->getMarcaPorDescricao(noSeo($busca_produto[0]));
            if ($idMarca != NULL) {
                $_where[] = [
                    'id_marca',
                    '=',
                    $idMarca
                ];

                $_where_marca[] = [
                    'id_marca',
                    '=',
                    $idMarca
                ];
            }

            $idCategoria = $this->dao('Produto', 'Produto')->getCategoriaPorDescricao(noSeo($busca_produto[0]));
            if ($idCategoria != NULL) {
                $_where[] = [
                    'id_categoria',
                    '=',
                    $idCategoria
                ];

                $_where_marca[] = [
                    'id_categoria',
                    '=',
                    $idCategoria
                ];
            }
        }

        $result = $this->_filtroPorCategoriaFilho_CategoriaPai_Produto('', Request::get('father'), Request::get('cat'));

        // FILTRO POR VALOR
        if ($min != NULL && $max != NULL) {
            $_where[] = [
                'valor_venda',
                'BETWEEN',
                [
                    $min,
                    $max
                ]
            ];
        } else if ($min != NULL && $max == NULL) {
            $_where[] = [
                'valor_venda',
                '>=',
                $min
            ];
        } else if ($min == NULL && $max != NULL) {
            $_where[] = [
                'valor_venda',
                '<=',
                $max
            ];
        }

        // FILTRO POR CATEGORIA
        if ($result['ids'][0] != NULL) {
            $_where[] = [
                'id_categoria',
                'IN',
                $result['ids']
            ];

            $_where_marca[] = [
                'id_categoria',
                'IN',
                $result['ids']
            ];
        }

        // FILTRO POR MARCA
        if ($_post_marca != NULL) {
            $_where[] = [
                'id_marca',
                '=',
                $this->dao('Site', 'Marca')->getIdPorNome(noSeo($_post_marca))
            ];
        }

        // SOMENTE PRODUTOS ATIVOS
        $_where[] = [
            'ativo',
            '=',
            TRUE
        ];

        $_produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], $_where);

        // Paginator
        $_paginator = PaginationUtil::execute($_produtos, $_GET, 16);
        $_produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], $_where, NULL, PaginationUtil::getInicio(), 16);

        // Marcas
        $marcas = $this->dao('Core', 'MarcaCategoria')->select([
            '*'
        ], $_where_marca);

        $_data_marcas = [];
        if (sizeof($marcas) != 0) {
            foreach ($marcas as $marca) {
                $_data_marcas[] = [
                    'marca' => $this->dao('Core', 'Marca')->getField('nome', $marca['id_marca']),
                    'filho' => Request::get('cat'),
                    'pai' => Request::get('father')
                ];
            }
        }

        $categoriaPai = (Request::get('father') != '') ? str_replace('-', ' ', Request::get('father')) : '';
        $categoriaFilho = (Request::get('cat') != '') ? str_replace('-', ' ', Request::get('cat')) : '';

        // Marcas sem repetições
        $mcs = [];
        foreach ($_data_marcas as $marca) {
            $mcs[] = $marca['marca'];
        }

        $mcs = array_unique($mcs);

        // Montar menu de categorias
        $data = [
            'paginacao' => $_paginator,
            'categoria_pai' => ucfirst(strtolower($categoriaPai)),
            'categoria_filhos' => $result['tree'][$categoriaPai],
            'categoria_selececionada' => $categoriaFilho,
            'marcas' => $_data_marcas,
            'marcas_unicas' => $mcs,
            'produtos' => $_produtos,
            'marca_selecionada' => $_post_marca,
            'customer_search' => $_set_search
        ];

        $this->renderView("lista", $data);
    }

    public function _filtroPorCategoriaFilho_CategoriaPai_Produto($descricaoProduto, $categoriaPai, $categoriaFilho)
    {
        $categoriaPai = ($categoriaPai != '') ? str_replace('-', ' ', $categoriaPai) : '';
        $categoriaFilho = ($categoriaFilho != '') ? str_replace('-', ' ', $categoriaFilho) : '';

        if ($categoriaFilho == NULL) {
            $categoriaFilho = $categoriaPai;
        }

        $idCategoria = $this->dao('Site', 'Categoria')->getIdPorDescricao($categoriaFilho);

        $tree = [];
        $ids = [
            $idCategoria
        ];

        // Ids
        foreach ($this->dao('Site', 'Categoria')->getSubcategorias($categoriaFilho) as $filhos) {
            if ($filhos['id']) {
                $ids[] = $filhos['id'];
            }
        }

        // Menu
        foreach ($this->dao('Site', 'Categoria')->getSubcategorias($categoriaPai) as $filhos) {
            if ($filhos['id']) {
                $tree[$categoriaPai][] = $filhos['descricao'];
            }
        }

        return [
            'ids' => $ids,
            'tree' => $tree
        ];
    }

    public function Action()
    {
        $this->renderView("produtos");
    }

    public function detalhesAction()
    {
        $codProduto = Request::get('cod');
        $idProduto = Request::get('id');

        $produto = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            [
                'cod_url_produto',
                '=',
                $codProduto
            ],
            [
                'id',
                '=',
                $idProduto
            ]
        ]);

        if (!is_array($produto) || sizeof($produto) === 0) {
            header('Location: /404.phtml', true, 302);
            exit();
        }

        // IMAGS
        $images = [];
        $path = Configuration::PATH_PRODUTO . '/' . $produto[0]['id'];
        $dir = dir($path);
        if ($dir != '') {
            while ($arquivo = $dir->read()) {
                $ar = explode('.', $arquivo);
                if (strlen($arquivo) > 3 && sizeof($ar) > 1) {
                    $images[] = $arquivo;
                }
            }
        }

        $_valor_produto = 'R$ ' . ValidateUtil::setFormatMoney($produto[0]['valor_venda']);
        $_valor_produto_sem_oferta = 'R$ ' . (int) ValidateUtil::setFormatMoney($produto[0]['valor_sem_oferta']) . ',00';
        $_tamanhos = $this->dao('Core', 'TamanhoProduto')->select([
            '*'
        ], [
            'id_produto',
            '=',
            $produto[0]['id']
        ]);

        if (sizeof($_tamanhos) != 0) {
            $_valor_produto = 'R$ ' . ValidateUtil::setFormatMoney($_tamanhos[0]['valor']);

            // VALOR SEM DESCONTO
            $_valor_sem_desconto = $_tamanhos[0]['valor'] + $produto[0]['lucro'];
            $_valor_produto_sem_oferta = 'R$ ' . (int) ValidateUtil::setFormatMoney($_valor_sem_desconto) . ',90';
        }

        $data = [
            'produto' => $produto,
            'images' => $images,
            'tamanho_produto' => $_tamanhos,
            'valor_produto' => $_valor_produto,
            'valor_parcela_sem_juros' => 'R$ ' . ValidateUtil::setFormatMoney(($produto[0]['valor_venda'] / QTD_PARCELAS_SEM_JUROS)),
            'valor_sem_oferta' => $_valor_produto_sem_oferta,
            'comentarios' => $this->dao('Core', 'Comentario')->select([
                '*'
            ], [
                [
                    'ativo',
                    '=',
                    TRUE
                ],
                [
                    'id_produto',
                    '=',
                    $produto[0]['id']
                ]
            ]),
            'cor_produto' => $this->dao('Core', 'CorProduto')->select([
                '*'
            ], [
                'id_produto',
                '=',
                $produto[0]['id']
            ]),
            'produtos_relacionados' => $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                [
                    'ativo',
                    '=',
                    TRUE
                ],
                [
                    'id',
                    '!=',
                    $produto[0]['id']
                ],
                [
                    'id_categoria',
                    '=',
                    $produto[0]['id_categoria']
                ]
            ])
        ];

        $this->renderView("detalhes", $data);
    }

    public function listaUrlProdutosAction()
    {
        $produto = $this->dao('Core', 'Produto')->select([
            'id',
            'cod_url_produto'
        ]);

        foreach ($produto as $pro) {
            $site = "<url><loc>https://'" . LINK_LOJA . "/produto/";
            $site .= $pro['id'] . '/';
            $site .= $pro['cod_url_produto'];

            echo $site . "</loc></url>";
        }
    }

    public function prazos_e_entregasAction()
    {
        $this->renderView("prazos_e_entregas");
    }

    public function trocas_e_devolucaoAction()
    {
        $this->renderView("troca_e_devolucao");
    }

    public function faqAction()
    {
        $this->renderView("faq");
    }
}
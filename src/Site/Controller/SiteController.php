<?php
namespace Store\Site\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Mail\Email;

class SiteController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function mailAction()
    {
        $mail = new Email();

        $mails = [
            [
                'nome' => 'Guilherme de Jesus',
                'email' => 'guilherme.malak@gmail.com'
            ],
            [
                'nome' => 'Lotérica',
                'email' => 'contato@lotericapremiada.com.br'
            ]
        ];

        foreach ($mails as $email) {
            echo $mail->send($email['email'], $email['nome'], $email['nome']);
        }
    }

    public function Action()
    {

        // CAT BOTAS
        $catBotas = $this->dao('Core', 'Categoria')->select([
            'id'
        ], [
            'descricao',
            'IN',
            [
                'Bota',
                'Coturno',
                'Cano Curto'
            ]
        ]);
        $idsBotas = [];
        $botas = [];
        foreach ($catBotas as $id) {
            $idsBotas[] = $id['id'];
        }

        if (sizeof($idsBotas) != 0) {
            $botas = $this->dao('Produto', 'Produto')->select([
                '*'
            ], [
                [
                    'ativo',
                    '=',
                    TRUE
                ],
                [
                    'id_categoria',
                    'IN',
                    $idsBotas
                ]
            ], NULL, 10);
        }

        // CAT SAPATOS
        $catSapatos = $this->dao('Core', 'Categoria')->select([
            'id'
        ], [
            'descricao',
            'IN',
            [
                'Sapato',
                'Scarpin'
            ]
        ]);

        $idsSapatos = [];
        $sapatos = [];
        foreach ($catSapatos as $id) {
            $idsSapatos[] = $id['id'];
        }
        if (sizeof($idsSapatos) != 0) {
            $sapatos = $this->dao('Produto', 'Produto')->select([
                '*'
            ], [
                [
                    'ativo',
                    '=',
                    TRUE
                ],
                [
                    'id_categoria',
                    'IN',
                    $idsSapatos
                ]
            ], NULL, 10);
        }

        // TENDENCIAS
        $tendencias = $this->dao('Produto', 'Produto')->select([
            '*'
        ], [
            'ativo',
            '=',
            TRUE
        ], [
            'id',
            'DESC'
        ], 10);

        $marcas = $this->dao('Core', 'Marca')->select([
            '*'
        ], NULL, [
            'id',
            'DESC'
        ]);

        // PRODUTOS MAIS VENDIDOS
        $produtos = $this->dao('Core', 'Produto')->select([
            '*'
        ], [
            [
                'ativo',
                '=',
                TRUE
            ],
            [
                'id_categoria',
                'IN',
                [
                    18,
                    16,
                    22,
                    26
                ]
            ]
        ], [
            'id',
            'DESC'
        ], 10);

        $produtosVendidos = [];
        foreach ($produtos as $prod) {
            $idProduto = $prod['id'];
            $quantidadeItensDesteProduto = $this->dao('Core', 'Produto')->query('SELECT count(p.id) as total FROM produto pd INNER JOIN item_pedido i ON i.id_produto = pd.id INNER JOIN pedido p ON p.id = i.id_pedido WHERE p.id_situacao_pedido = 2 AND pd.ativo IS TRUE AND pd.id = "' . $idProduto . '"');
            if ($quantidadeItensDesteProduto[0]['total'] > 1) {
                $produtosVendidos[] = [
                    'id' => $prod['id'],
                    'id_marca' => $prod['id_marca'],
                    'descricao' => $prod['descricao'],
                    'valor_venda' => $prod['valor_venda'],
                    'cod_url_produto' => $prod['cod_url_produto'],
                    'valor_sem_oferta' => $prod['valor_sem_oferta'],
                    'quantidade' => $quantidadeItensDesteProduto[0]['total']
                ];
            }
        }

        usort($produtosVendidos, function ($a, $b) {
            return $b['quantidade'] - $a['quantidade'];
        });

        $_categorias = [
            'botas',
            'bolsas',
            'sandalias',
            'coturnos',
            'scarpin'
        ];

        $data = [
            'destaques' => $this->dao('Produto', 'Produto')->destaques(),
            'queridinhos' => (isset($_SESSION['cliente']['id_cliente'])) ? $this->dao('Produto', 'Produto')->queridinhos($_SESSION['cliente']['id_cliente']) : [],
            '_botas' => $botas,
            '_sapatos' => $sapatos,
            '_tendencias' => $tendencias,
            '_mais_vendidos' => $produtosVendidos,
            '_marcas' => $marcas,
            '_categorias' => $_categorias
        ];

        $this->renderView("index", $data);
    }
}
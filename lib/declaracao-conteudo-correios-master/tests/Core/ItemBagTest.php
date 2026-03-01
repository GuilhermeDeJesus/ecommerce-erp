<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Click4Web\DeclaracaoConteudo\Core\ItemBag;
use Click4Web\DeclaracaoConteudo\Entities\Item;

final class ItemBagTest extends TestCase
{
    public function testDefinirItensPorArray()
    {
        $itens = new \Click4Web\DeclaracaoConteudo\Core\ItemBag([
            [
                'descricao' => 'Livro - 8Ps do Marketing Digital',
                'quantidade' => 1,
                'peso' => 0.733,
	            'valor' => 80,
            ],
            [
                'descricao' => 'Livro - Super Apresentações',
                'quantidade' => 1,
                'peso' => 0.397,
	            'valor' => 75,
            ]
        ]);

        $this->assertEquals([
            new Item([
                'descricao' => 'Livro - 8Ps do Marketing Digital',
                'quantidade' => 1,
                'peso' => 0.733,
                'valor' => 80,
            ]),
            new Item([
                'descricao' => 'Livro - Super Apresentações',
                'quantidade' => 1,
                'peso' => 0.397,
                'valor' => 75,
            ])
        ], $itens->getItens(), "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }

    public function testAdicionarItem()
    {
        $itens = new ItemBag();

        $item = new Item([
            'descricao' => 'Livro - 8Ps do Marketing Digital',
            'quantidade' => 1,
            'peso' => 0.733,
            'valor' => 80,
        ]);

        $itens->add($item);

        $this->assertEquals([
            new Item([
                'descricao' => 'Livro - 8Ps do Marketing Digital',
                'quantidade' => 1,
                'peso' => 0.733,
                'valor' => 80,
            ]),
        ], $itens->getItens());
    }

    public function testAdicionarItensPorArrayDeObjetosItem()
    {
        $itens = new \Click4Web\DeclaracaoConteudo\Core\ItemBag([
            new Item([
                'descricao' => 'Livro - Super Apresentações',
                'quantidade' => 1,
                'peso' => 0.397,
                'valor' => 75,
            ])
        ]);

        $this->assertEquals([
            new Item([
                'descricao' => 'Livro - Super Apresentações',
                'quantidade' => 1,
                'peso' => 0.397,
                'valor' => 75,
            ])
        ], $itens->getItens(), "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }
}

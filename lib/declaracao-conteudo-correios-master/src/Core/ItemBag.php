<?php
namespace Click4Web\DeclaracaoConteudo\Core;

use Click4Web\DeclaracaoConteudo\Interfaces\ItemBagInterface;
use Click4Web\DeclaracaoConteudo\Interfaces\ItemInterface;

require_once 'lib/declaracao-conteudo-correios-master/src/Interfaces/ItemBagInterface.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Entities/Item.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Interfaces/ItemInterface.php';

/**
 * Class ItemBag
 *
 * @package    Click4Web\DeclaracaoConteudo
 * @subpackage Core
 * @author     fontebasso <sfdsilva@fontesoft.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
class ItemBag implements ItemBagInterface
{
    private $itens = [];

    public function __construct(array $itens = [], $classItem = '\\Click4Web\\DeclaracaoConteudo\\Entities\\Item')
    {
        foreach ($itens as $item) {
            if ($item instanceof $classItem) {
                $this->itens[] = $item;
            } else {
                $this->itens[] = new $classItem($item);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getItens(): array
    {
        return $this->itens;
    }


    /**
     * @inheritdoc
     */
    public function getQuantidade(): int
    {
        $quantidade = 0;
        foreach ($this->itens as $item) {
            $quantidade += $item->getQuantidade();
        }
        return $quantidade;
    }

    /**
     * @inheritdoc
     */
    public function getValor(): float
    {
        $valor = 0;
        foreach ($this->itens as $item) {
            $valor += $item->getValor();
        }
        return $valor;
    }

    /**
     * @inheritdoc
     */
    public function getPeso(): float
    {
        $peso = 0;
        foreach ($this->itens as $item) {
            $peso += $item->getPeso();
        }
        return $peso;
    }

    /**
     * @inheritdoc
     */
    public function add(ItemInterface $item): ItemBagInterface
    {
        $this->itens[] = $item;
        return $this;
    }
}

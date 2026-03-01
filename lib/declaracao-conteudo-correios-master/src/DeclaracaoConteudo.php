<?php
namespace Click4Web\DeclaracaoConteudo;

use Click4Web\DeclaracaoConteudo\Core\Controller;
use Click4Web\DeclaracaoConteudo\Interfaces\ItemBagInterface;
use Click4Web\DeclaracaoConteudo\Interfaces\PessoaInterface;

require_once 'lib/declaracao-conteudo-correios-master/src/Core/Controller.php';

/**
 * Class DeclaracaoConteudo
 *
 * Declaração de Conteúdo para encomendas enviadas via Correios
 *
 * @package Click4Web\DeclaracaoConteudo
 * @author  fontebasso <sfdsilva@fontesoft.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class DeclaracaoConteudo extends Controller
{
    private $remetente;
    private $destinatario;
    private $itens;

    /**
     * DeclaracaoConteudo constructor.
     *
     * @param PessoaInterface  $remetente
     * @param PessoaInterface  $destinatario
     * @param ItemBagInterface $itens
     */
    public function __construct(
        PessoaInterface $remetente,
        PessoaInterface $destinatario,
        ItemBagInterface $itens
    ) {
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->itens = $itens;
    }

    /**
     * Imprimir Declaração de Conteúdo em HTML
     */
    public function imprimirHtml()
    {
        return $this->view(
            'declaracao-conteudo',
            [
            'remetente' => $this->remetente,
            'destinatario' => $this->destinatario,
            'itens' => $this->itens,
            ]
        );
    }
}

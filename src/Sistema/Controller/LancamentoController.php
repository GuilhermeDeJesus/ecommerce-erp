<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\PaginationUtil;

class LancamentoController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $_html = PaginationUtil::execute($this->dao('Core', 'Lancamento')->select([
            '*'
        ], NULL, [
            'data',
            'DESC'
        ]), $_GET, 100);

        $lancamentos = $this->dao('Core', 'Lancamento')->select([
            '*'
        ], NULL, [
            'data',
            'DESC'
        ], PaginationUtil::getInicio(), 15);

        $data = [
            'lancamento' => $lancamentos,
            'paginacao' => $_html
        ];

        $this->renderView("index", $data);
    }

    public function inserirEditarAction()
    {
        $id = Request::get('id');

        $data = [
            'title' => 'Adicionar',
            'tipo_lancamento' => $this->dao('Core', 'TipoLancamento')->select([
                '*'
            ]),
            'produtos' => $this->dao('Core', 'Produto')->select([
                '*'
            ])
        ];

        if ($id) {
            $data['lancamento'] = $this->dao('Core', 'Lancamento')->select([
                '*'
            ], [
                'id',
                '=',
                $id
            ]);

            $data['title'] = 'Editar';
        }

        $this->renderView("inserir", $data);
    }

    public function cadastrarAction()
    {
        $lancamento = [
            'id' => $_POST['id']
        ];

        if ($_POST['id_tipo_lancamento']) {
            $lancamento['id_tipo_lancamento'] = $_POST['id_tipo_lancamento'];
        }

        if ($_POST['data']) {
            $lancamento['data'] = $_POST['data'];
        }

        if ($_POST['valor']) {
            $lancamento['valor'] = ValidateUtil::paraFloat($_POST['valor']);
        }

        if ($_POST['id_produto'] && $_POST['id_produto'] != "0") {
            $lancamento['id_produto'] = $_POST['id_produto'];
        }

        if ($this->dao('Core', 'Lancamento')->insertORUpdate($lancamento)) {
            $this->renderView("index", [
                'lancamento' => $this->dao('Core', 'Lancamento')
                    ->select([
                    '*'
                ]),
                'error' => FALSE,
                'msg' => 'Lançamento salvo com sucesso'
            ], "Lançamento");
        } else {
            $this->renderView("index", [
                'lancamento' => $this->dao('Core', 'Lancamento')
                    ->select([
                    '*'
                ]),
                'error' => TRUE,
                'msg' => 'Algum error ocorreuo durante o processo'
            ], "Lançamento");
        }
    }
}
<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use SkyHub\Api;

class CategoriaController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [
            'categorias' => $this->dao('Core', 'Categoria')->select([
                '*'
            ])
        ];

        $this->renderView("index", $data);
    }

    public function inserirEditarAction()
    {
        $id = Request::get('id');

        $data = [
            'categorias' => $this->dao('Core', 'Categoria')->select([
                '*'
            ])
        ];

        if ($id) {
            $data['categoria'] = $this->dao('Core', 'Categoria')->select([
                '*'
            ], [
                'id',
                '=',
                $id
            ]);
        }

        $this->renderView("inserir", $data);
    }

    public function cadastrarAction()
    {
        $categoria = [
            'id' => $_POST['id'],
            'descricao' => $_POST['descricao'],
            'icone' => $_POST['icone']
        ];

        if ($_POST['categoria_pai']) {
            $categoria['categoria_pai'] = $_POST['categoria_pai'];
        }

        // ATIVO
        if ($_POST['skyhub'] == 'on') {
            $categoria['skyhub'] = TRUE;

            $api = new Api(EMAIL_SKYHUB, SENHA_SKYHUB);

            $requestHandler = $api->category();

            $requestHandler->create(seo($_POST['descricao']), $_POST['descricao']);
        } else {
            $categoria['skyhub'] = 0;
        }

        if ($_POST['descricao'] != '' && $this->dao('Core', 'Categoria')->insertORUpdate($categoria)) {
            $this->renderView("index", [
                'categorias' => $this->dao('Core', 'Categoria')
                    ->select([
                    '*'
                ]),
                'error' => FALSE,
                'msg' => 'Sucesso'
            ], "Categoria");
        } else {
            $this->renderView("index", [
                'categorias' => $this->dao('Core', 'Categoria')
                    ->select([
                    '*'
                ]),
                'error' => TRUE,
                'msg' => 'Algum error ocorreuo durante o processo'
            ], "Categoria");
        }
    }
}
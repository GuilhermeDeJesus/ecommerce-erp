<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Configuration\Configuration;

class MarcaController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [
            'marcas' => $this->dao('Core', 'Marca')->select([
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
            $data['marca'] = $this->dao('Core', 'Marca')->select([
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
        $marca = [
            'id' => $_POST['id'],
            'nome' => $_POST['nome']
        ];

        if ($_POST['nome'] != '' && $idMarca = $this->dao('Core', 'Marca')->insertORUpdate($marca)) {

            // SALVAR IMAGES DA MARCA DO PRODUTO
            $dir = Configuration::PATH_MARCA . '/' . $idMarca;
            $name_img = $idMarca . '_M.png';

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            if (isset($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    $filename = basename($file['name']);
                    move_uploaded_file($file['tmp_name'], $dir . '/' . $name_img);
                }
            }

            $this->renderView("index", [
                'marcas' => $this->dao('Core', 'Marca')
                    ->select([
                    '*'
                ]),
                'error' => FALSE,
                'msg' => 'Sucesso'
            ], "Marca");
        } else {
            $this->renderView("index", [
                'marcas' => $this->dao('Core', 'Marca')
                    ->select([
                    '*'
                ]),
                'error' => TRUE,
                'msg' => 'Algum error ocorreuo durante o processo'
            ], "Marca");
        }
    }
}
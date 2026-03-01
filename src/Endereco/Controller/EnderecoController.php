<?php
namespace Store\Endereco\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\ValidateUtil;

class EnderecoController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function deletarAction()
    {
        $id_endereco = $_POST['id_endereco'];
        $this->dao('Core', 'Endereco')->delete([
            'id',
            '=',
            $id_endereco
        ]);

        return true;
    }

    public function editarAction()
    {
        $formEnd = [
            'endereco' => $this->post('endereco'),
            'complemento' => $this->post('complemento'),
            'cep' => trim(ValidateUtil::cleanInput($this->post('cep'))),
            'bairro' => $this->post('bairro'),
            'cidade' => $this->post('cidade'),
            'numero' => $this->post('numero'),
            'uf' => $this->post('estado'),
            'data_hora_ultima_alteracao' => date("Y-m-d") . "T" . date("H:i:s")
        ];

        $id_endereco = $this->post('id_endereco');
        $this->dao('Core', 'Endereco')->update($formEnd, [
            'id',
            '=',
            $id_endereco
        ]);

        return true;
    }
}
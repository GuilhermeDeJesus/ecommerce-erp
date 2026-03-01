<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;

class PessoaController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [
            'pessoas' => $this->dao('Core', 'Pessoa')->select([
                '*'
            ])
        ];

        $this->renderView("index", $data);
    }

    public function inserirEditarAction()
    {
        $id = Request::get('id');

        $data = [
            'classes' => $this->dao('Core', 'Classe')->select([
                '*'
            ])
        ];

        if ($id) {
            $data['pessoa'] = $this->dao('Core', 'Pessoa')->select([
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
        $pessoa = [
            'id' => $_POST['id'],
            'nome' => $_POST['nome']
        ];

        // ATIVO
        if ($_POST['ativo'] == 'on') {
            $pessoa['ativo'] = TRUE;
        } else {
            $pessoa['ativo'] = FALSE;
        }

        // TIPO
        $pessoa['tipo'] = $_POST['tipo'];

        // CPF
        $pessoa['cpf'] = $_POST['cpf'];

        // CNPJ
        $pessoa['cnpj'] = $_POST['cnpj'];

        // CEP
        $pessoa['cep'] = $_POST['cep'];

        $pessoa['endereco'] = $_POST['endereco'];

        $pessoa['bairro'] = $_POST['bairro'];

        $pessoa['cidade'] = $_POST['cidade'];

        $pessoa['uf'] = $_POST['uf'];

        $pessoa['numero'] = $_POST['numero'];

        // Site
        $pessoa['site'] = $_POST['site'];

        // CELULAR
        $pessoa['celular'] = $_POST['celular'];

        // TELEFONE
        $pessoa['telefone'] = (int) $_POST['telefone'];

        // DATA NASCIMENTO
        if ($_POST['data_nascimento']) {
            $pessoa['data_nascimento'] = $_POST['data_nascimento'];
        }

        // E-MAIL
        $pessoa['email'] = $_POST['email'];

        // SENHA
        $pessoa['senha'] = md5($_POST['senha']);

        // OBSERVAÇÃO
        $pessoa['observacao'] = $_POST['observacao'];

        // CLASSE
        $pessoa['id_classe'] = $_POST['id_classe'];

        if ($_POST['nome'] != '' && $this->dao('Core', 'Pessoa')->insertORUpdate($pessoa)) {
            $this->renderView("index", [
                'pessoas' => $this->dao('Core', 'Pessoa')
                    ->select([
                    '*'
                ]),
                'error' => FALSE,
                'msg' => 'Sucesso'
            ], "Produto");
        } else {
            $this->renderView("index", [
                'pessoas' => $this->dao('Core', 'Pessoa')
                    ->select([
                    '*'
                ]),
                'error' => TRUE,
                'msg' => 'Algum error ocorreuo durante o processo'
            ], "Produto");
        }
    }
}
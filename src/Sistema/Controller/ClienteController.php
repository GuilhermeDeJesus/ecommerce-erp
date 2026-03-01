<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;

class ClienteController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [];
        $this->renderView("index", $data);
    }

    public function clientesAction()
    {
        $clientes = $this->dao('Core', 'Cliente')->select([
            '*'
        ]);
        
        $data = [
            'clientes' => $clientes
        ];
        
        $this->renderView("index", $data);
    }

    public function inserirAction()
    {
        $data = [];
        $this->renderView("inserir", $data);
    }
}
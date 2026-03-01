<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
require_once 'krypitonite/src/Http/Request.php';

class ComentariosController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $comentarios = $this->dao('Core', 'Comentario')->select([
            '*'
        ], NULL, [
            'id',
            'DESC'
        ]);

        $data = [
            'comentarios' => $comentarios
        ];

        $this->renderView("index", $data);
    }

    public function aprovarAction()
    {
        $this->dao('Core', 'Comentario')->update([
            'ativo' => TRUE
        ], [
            'id',
            '=',
            Request::get('id')
        ]);

        $comentarios = $this->dao('Core', 'Comentario')->select([
            '*'
        ]);

        $data = [
            'comentarios' => $comentarios
        ];

        $this->renderView("index", $data);
    }

    public function desaprovarAction()
    {
        $this->dao('Core', 'Comentario')->update([
            'ativo' => FALSE
        ], [
            'id',
            '=',
            Request::get('id')
        ]);

        $comentarios = $this->dao('Core', 'Comentario')->select([
            '*'
        ]);

        $data = [
            'comentarios' => $comentarios
        ];

        $this->renderView("index", $data);
    }

    public function excluirAction()
    {
        $this->dao('Core', 'Comentario')->delete([
            'id',
            '=',
            Request::get('id')
        ]);

        $comentarios = $this->dao('Core', 'Comentario')->select([
            '*'
        ]);

        $data = [
            'comentarios' => $comentarios
        ];

        $this->renderView("index", $data);
    }
}
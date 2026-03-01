<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;

class LoginController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function Action()
    {
        $this->renderView("login");
    }

    public function logoutAction()
    {
        $true = $this->isLogged($_SESSION);
        if ($true == true) {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            session_unset();
            session_destroy();
        }

        $this->Action();
    }

    public function logarAction()
    {
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $user = $this->dao('Sistema', 'Pessoa')->select([
            "id",
            "nome",
            "email",
            "senha"
        ], [
            [
                "email",
                '=',
                $email
            ],
            [
                "senha",
                '=',
                md5($senha)
            ]
        ]);

        if (count($user) == 1) {
            $_SESSION['id_pessoa'] = $user[0]['id'];
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $usuario = explode(" ", $user[0]['nome']);
            $_SESSION['usuario'] = $usuario[0];
            $_SESSION['nome'] = $user[0]['nome'];
            $this->redirect('sistema', 'painel');
        } else {
            $this->renderView('login', [
                'msg' => 'Error ao realizar login',
                'error' => TRUE
            ]);
        }
    }
}
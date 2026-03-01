<?php
namespace Store\Email\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Mail\Email;
require_once 'krypitonite/src/Mail/Email.php';

class EmailController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function testeAction()
    {
        // $mail = new Email();
        // echo $this->confirmacaoPedidoAction();
        // die();
        // $mail->send("guilherme.malak@gmail.com", "Confirmação de Pedido", $this->confirmacaoPedidoAction(), '1001');
    }
}
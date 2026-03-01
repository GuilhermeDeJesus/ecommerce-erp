<?php
namespace Store\Pagamento\Controller;

use Krypitonite\Controller\AbstractController;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'config/Configuration.php';
use Configuration\Configuration;

class PagamentoUpnidController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function endBoletoAction()
    {
        $data = [];
        $this->renderView('end_checkout_boleto', $data);
    }

    public function endCartaoAction()
    {
        $data = [];
        $this->renderView('end_checkout_cartao', $data);
    }

    public function getPaymentServerAction()
    {
        $response = serialize($_POST);
        $filename = Configuration::PATH_LOG . '/upnid.txt';
        file_put_contents($filename, $response, FILE_APPEND);
    }
}
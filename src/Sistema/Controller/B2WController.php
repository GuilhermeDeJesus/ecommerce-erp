<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Http\Request;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Util\ValidateUtil;
use DateTime;
require_once 'krypitonite/src/Mail/Email.php';
require_once 'krypitonite/src/Util/MessageUtil.php';
use Krypitonite\Mail\Email;
use Krypitonite\Util\MessageUtil;
use Configuration\Configuration;
use Krypitonite\Util\DateUtil;
use Store\Cliente\Dao\ClienteDAO;
use Krypitonite\Util\PaginationUtil;
require_once 'src/Cliente/Dao/ClienteDAO.php';
require_once 'lib/simplexlsx-master/src/SimpleXLSX.php';
require_once 'krypitonite/src/Util/PaginationUtil.php';
include_once ('lib/PHP_XLSXWriter-master/xlsxwriter.class.php');

class B2WController extends AbstractController
{

    public function Action()
    {
        $data = [];
        $this->renderView("index", $data);
    }
}

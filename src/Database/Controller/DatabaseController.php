<?php
namespace Store\Database\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Template\TemplateDao;
use Krypitonite\Template\TemplateModel;
require_once 'krypitonite/src/Template/TemplateDao.php';
require_once 'krypitonite/src/Template/TemplateModel.php';
require_once 'krypitonite/src/Template/Template.php';

class DatabaseController extends AbstractController
{

    public function __construct()
    {}

    public function Action()
    {
        $generateDao = new TemplateDao();
        $generateModel = new TemplateModel();

        $generateDao->generateDao("core");
        $generateModel->generateModel("core");
    }
}
<?php
namespace Store\Comentario\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Mail\Email;
use Configuration\Configuration;
require_once 'krypitonite/src/Mail/Email.php';

class ComentarioController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function addComentAction()
    {
        if (isset($_FILES)) {
            $dir = Configuration::PATH_COMENTARIO . '/' . $_POST['id_produto'];

            if (! is_dir(Configuration::PATH_COMENTARIO)) {
                mkdir(Configuration::PATH_COMENTARIO);
            }

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            foreach ($_FILES as $key => $file) {
                $filename = basename($file['name']);
                move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);

                $formComentario = [
                    "nome" => $_POST['nome'],
                    "email" => $_POST['email'],
                    "texto" => $_POST['texto'],
                    "img" => $file['name'],
                    "date_create" => date('Y-m-d'),
                    "id_produto" => $_POST['id_produto']
                ];

                self::dao('Core', 'Comentario')->insert($formComentario);
            }

            $produto = $this->dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $_POST['id_produto']
            ]);

            // ?m=produto&c=produto&a=detalhes&id=209&cod=manga-flare-social-festa
            $this->redirect('produto', 'produto', 'detalhes', 'id=' . $_POST['id_produto'] . '&cod=' . $produto[0]['cod_url_produto']);
        }
    }
}
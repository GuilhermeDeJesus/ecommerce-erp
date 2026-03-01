<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\ValidateUtil;

class PlataformaController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function Action()
    {
        $data = [
            'configuracoes' => $this->dao('Core', 'ConfiguracoesPlataforma')->select([
                '*'
            ])
        ];

        $this->renderView("index", $data);
    }

    public function atualizarAction()
    {
        $form = [
            "nome_loja" => $_POST['nome_loja'],
            "nome_logo" => $_POST['nome_logo'],
            "nome_logo_mobile" => $_POST['nome_logo_mobile'],
            "url_loja" => $_POST['url_loja'],
            "email_contato_loja" => $_POST['email_contato_loja'],
            "telefone_contato_loja" => $_POST['telefone_contato_loja'],
            "tag_title" => $_POST['tag_title'],
            "tag_description" => $_POST['tag_description'],
            "tag_keywords" => $_POST['tag_keywords'],
            "token_pag_seguro" => $_POST['token_pag_seguro'],
            "email_conta_pag_seguro" => $_POST['email_conta_pag_seguro'],
            "cliente_id_mp" => $_POST['cliente_id_mp'],
            "client_secret_mp" => $_POST['client_secret_mp'],
            "gateway" => $_POST['gateway'],
            "email_envio" => $_POST['email_envio'],
            "cor_loja" => $_POST['cor_loja'],
            "senha_email_envio" => $_POST['senha_email_envio'],
            "parcelar_sem_juros" => $_POST['parcelar_sem_juros'],
            "quantidade_parcelas_sem_juros" => $_POST['quantidade_parcelas_sem_juros'],
            "valor_minimo_para_frete_gratis" => ValidateUtil::paraFloat($_POST['valor_minimo_para_frete_gratis']),
            "taf_d_1_mp" => ValidateUtil::paraFloat($_POST['taf_d_1_mp']),
            "taf_d_14_mp" => ValidateUtil::paraFloat($_POST['taf_d_14_mp']),
            "taf_d_30_mp" => ValidateUtil::paraFloat($_POST['taf_d_30_mp']),
            "cupom" => $_POST['cupom'],
            "percentual_desconto_cupom" => ValidateUtil::paraFloat($_POST['percentual_desconto_cupom'])
        ];

        // Update all pixels of produtcs
        if ($_POST['numero_conta_anuncio_facebook'] != NULL) {
            $form["numero_conta_anuncio_facebook"] = $_POST['numero_conta_anuncio_facebook'];
            $this->dao('Core', 'Produto')->update([
                'pixel' => $_POST['numero_conta_anuncio_facebook']
            ], [
                [
                    'id',
                    '!=',
                    0
                ]
            ]);
        }

        $this->dao('Core', 'ConfiguracoesPlataforma')->delete([
            'id',
            '!=',
            0
        ]);

        $this->dao('Core', 'ConfiguracoesPlataforma')->insert($form);

        $this->redirect('sistema', 'plataforma', '');
    }
}
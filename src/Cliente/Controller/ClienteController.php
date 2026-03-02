<?php
namespace Store\Cliente\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\ValidateUtil;
use Store\Core\Dao\EnderecoCoreDAO;
use Krypitonite\Mail\Email;
use Krypitonite\Http\Request;
use Store\Checkout\Controller\CheckoutController;
use Krypitonite\Util\CarrinhoUtil;
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\GoogleMapsUtil;
require_once 'krypitonite/src/Util/GoogleMapsUtil.php';

class ClienteController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function Action()
    {
        $this->renderView("index");
    }

    public function _clientNoAuthenticationAction()
    {
        $this->renderView("login", [
            'error' => TRUE,
            'msg' => 'Para prosseguir você precisa estar logado na sua conta!'
        ], "Login - " . NOME_LOJA);
    }

    public function newsletterAction()
    {
        if ($_POST['email'] != '') {
            $form = [
                'email' => $_POST['email']
            ];

            $this->dao('Core', 'Newsletter')->insert($form);

            $data = [
                'email' => $_POST['email']
            ];

            $_email = new Email();
            $_email->send($_POST['email'], 'Confirmação de Cadastro na Newsletter ' . NOME_LOJA, $_email->confirmacaoNewsletter($_POST['email']), '1001');

            $this->renderView('newsletter', $data);
        }
    }

    public function confirmar_newsletterAction()
    {
        $_email = Request::get('email');

        $this->dao('Core', 'Newsletter')->update([
            'confirmado' => TRUE
        ], [
            'email',
            '=',
            $_email
        ]);

        $data = [
            'email' => $_email
        ];

        $this->renderView('confirmado_newsletter', $data);
    }

    public function informacoesAction()
    {
        $this->sessionIdCreate();

        /*
         * Redirecionar para a página onde o cliente vai adionar o endereço de entrega
         * Página Evento Iniciar Checkout
         */
        $_total = [];

        foreach (CarrinhoUtil::getItens('_itens') as $_t) {
            $_total[] = $_t['valor'];
        }

        $data = [
            'valor_total_compra' => (double) array_sum($_total)
        ];

        $this->renderView('informacoes', $data);
    }

    private function insertAdressIPLocalGoogleMaps($idClient = NULL, $cep = NULL, $ip = NULL, $howManu = NULL, $updateOnly = FALSE)
    {
        $_ip = $_SERVER["REMOTE_ADDR"];
        // $_ip = '177.107.52.163';
        if ($ip != NULL) {
            $_ip = $ip;
        }

        $query = @unserialize(file_get_contents("http://ip-api.com/php/" . $_ip));

        $_howMany = $this->dao('Core', 'EnderecoLocalizacaoCliente')->countOcurrence("*", [
            'id_cliente',
            '=',
            $idClient
        ]);

        if ($howManu == 'Atualizar') {
            $_howMany = 0;
        }

        if ($query['status'] == 'success' && $_howMany == 0) {
            $_address_maps = GoogleMapsUtil::Get_Address_From_Google_Maps($query['lat'], $query['lon']);

            if (sizeof($_address_maps) != 0) {

                $_cep = str_replace("-", "", trim($_address_maps['cep']));
                if (strlen($_address_maps['cep']) == 5) {
                    $_cep = $_address_maps['cep'] . '000';
                }

                if ($_address_maps['cep'] == NULL) {
                    $_cep = substr($query['zip'], 0, 5) . '000';
                }

                $_bairro = $_address_maps['bairro'];
                if ($_bairro == NULL) {
                    $_bairro = 'Centro';
                }

                $_endereco = trim($_address_maps['endereco']);
                $_numero = $_address_maps['numero'];
                if ($_endereco == NULL || $_endereco == 'Unnamed Road') {
                    $_numero = rand(10, 500);
                    $_endereco = "Rua $_numero Bairro $_bairro";
                }

                $_uf = $_address_maps['uf'];
                if ($_uf == NULL) {
                    $_uf = $query['region'];
                }

                if ($updateOnly == TRUE) {
                    $this->dao('Core', 'EnderecoLocalizacaoCliente')->update([
                        "endereco" => $_endereco,
                        "bairro" => $_bairro,
                        "numero" => $_numero,
                        "cidade" => $_address_maps['cidade'],
                        "uf" => $_uf,
                        "cep" => str_replace("-", "", $_cep)
                    ], [
                        'id_cliente',
                        '=',
                        $idClient
                    ]);
                } else {
                    $this->dao('Core', 'EnderecoLocalizacaoCliente')->insert([
                        "endereco" => $_endereco,
                        "bairro" => $_bairro,
                        "numero" => $_numero,
                        "cidade" => $_address_maps['cidade'],
                        "uf" => $_uf,
                        "cep" => str_replace("-", "", $_cep),
                        "ip" => $_ip,
                        "id_cliente" => $idClient
                    ]);
                }
            }
        }
    }

    public function saveInformationsBeforeChechoutAction()
    {
        // Checa se o cpf é valido
        if (ValidateUtil::ifCPFisValid(ValidateUtil::cleanInput($_POST['cpf'])) == FALSE) {
            $this->renderView("informacoes", [
                'error' => TRUE,
                'msg' => 'Insira um cpf válido'
            ], "Informações | " . NOME_LOJA);
        } else if (! filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $this->renderView("informacoes", [
                'error' => TRUE,
                'msg' => 'Insira um e-mail válido'
            ], "Informações | " . NOME_LOJA);
        } else if (strlen(trim(ValidateUtil::cleanInput($_POST['cep']))) < 8) {
            $this->renderView("informacoes", [
                'error' => TRUE,
                'msg' => 'CEP Inválido'
            ], "Informações | " . NOME_LOJA);
        } else {

            $fbq_correspondecia_avancada = [
                'em' => strtolower(trim($_POST['email'])),
                'fn' => strtolower(trim($_POST['nome'])),
                'ln' => strtolower(trim($_POST['sobrenome'])),
                'country' => 'BR',
                'ct' => strtolower(trim($_POST['cidade'])),
                'ph' => '55' . trim(ValidateUtil::cleanInput($_POST['telefone'])),
                'st' => strtolower(trim($_POST['estado'])),
                'zp' => substr(trim(ValidateUtil::cleanInput($_POST['cep'])), 0, 5)
            ];

            if (isset($_SESSION['data_corresondencia_avancada']) && $_SESSION['data_corresondencia_avancada'] != '') {
                unset($_SESSION['data_corresondencia_avancada']);
                $_SESSION['data_corresondencia_avancada'] = $fbq_correspondecia_avancada;
            } else {
                $_SESSION['data_corresondencia_avancada'] = $fbq_correspondecia_avancada;
            }

            $hassPassword = md5($_POST['senha']);

            $formEndereco = [];
            $formCliente = [
                'nome' => trim($_POST['nome'] . ' ' . $_POST['sobrenome']),
                'email' => trim($_POST['email']),
                'senha' => trim($hassPassword),
                'cpf' => ValidateUtil::cleanInput($_POST['cpf']),
                'data_nascimento' => DateUtil::convertDMYtoYMD($_POST['data_nascimento'], '-'),
                'telefone' => trim(ValidateUtil::cleanInput($_POST['telefone'])),
                'ativo' => TRUE,
                'date_create' => date("Y-m-d"),
                'data_hora_ultima_alteracao' => date("Y-m-d") . "T" . date("H:i:s")
            ];

            if (isset($_POST['sexo']) && $_POST['sexo'] != '') {
                $formCliente['sexo'] = trim($_POST['sexo']);
            }

            $c = $this->dao('Cliente', 'Cliente')->select([
                '*'
            ], [
                'email',
                '=',
                $_POST['email']
            ]);

            // nenhum cliente com esse cpf
            if (count($c) == 0) {
                $formCliente['id_tipo_cliente'] = 1;
                $idCliente = $this->dao('Cliente', 'Cliente')->insert($formCliente);

                // Add Endress
                if ($idCliente) {
                    $formEndereco['destinatario'] = trim($_POST['nome'] . ' ' . $_POST['sobrenome']);
                    $formEndereco['cep'] = trim(ValidateUtil::cleanInput($_POST['cep']));
                    $formEndereco['endereco'] = trim($_POST['endereco']);
                    $formEndereco['bairro'] = trim($_POST['bairro']);
                    $formEndereco['cidade'] = trim($_POST['cidade']);
                    $formEndereco['uf'] = trim($_POST['estado']);
                    $formEndereco['numero'] = trim($_POST['numero']);
                    $formEndereco['id_cliente'] = $idCliente;
                    $formEndereco['principal'] = TRUE;
                    $formEndereco['data_hora_ultima_alteracao'] = date("Y-m-d") . "T" . date("H:i:s");

                    $this->dao('Core', 'Endereco')->insert($formEndereco);

                    $this->insertAdressIPLocalGoogleMaps($idCliente);
                }

                $_SESSION['hash_novo_cliente'] = $idCliente;

                $cliente = $this->dao('Cliente', 'Cliente')->select([
                    '*'
                ], array(
                    array(
                        'id',
                        '=',
                        $idCliente
                    )
                ));

                if (! isset($_SESSION['cliente'])) {
                    $_SESSION['cliente'] = [
                        'id_cliente' => $cliente[0]['id'],
                        'nome' => $cliente[0]['nome'],
                        'email' => $cliente[0]['email'],
                        'senha' => $cliente[0]['senha']
                    ];
                }

                $this->redirect('checkout', 'checkout', 'finalizar');
            } else {

                // Pega a Localização do Cliente
                $this->insertAdressIPLocalGoogleMaps($c[0]['id']);

                // $this->dao('Core', 'Endereco')->delete([
                // [
                // 'id_cliente',
                // '=',
                // $c[0]['id']
                // ]
                // ]);

                // Edit Customer
                $this->dao('Cliente', 'Cliente')->update($formCliente, [
                    'id',
                    '=',
                    $c[0]['id']
                ]);

                // Add Endress
                if ($c[0]['id']) {
                    $formEndereco['destinatario'] = $_POST['nome'] . ' ' . $_POST['sobrenome'];
                    $formEndereco['cep'] = trim(ValidateUtil::cleanInput($_POST['cep']));
                    $formEndereco['endereco'] = $_POST['endereco'];
                    $formEndereco['bairro'] = $_POST['bairro'];
                    $formEndereco['cidade'] = $_POST['cidade'];
                    $formEndereco['uf'] = $_POST['estado'];
                    $formEndereco['numero'] = $_POST['numero'];
                    $formEndereco['id_cliente'] = $c[0]['id'];
                    $formEndereco['principal'] = TRUE;
                    $formEndereco['data_hora_ultima_alteracao'] = date("Y-m-d") . "T" . date("H:i:s");
                    $this->dao('Core', 'Endereco')->insert($formEndereco);
                }

                if (! isset($_SESSION['cliente'])) {
                    $_SESSION['cliente'] = [
                        'id_cliente' => $c[0]['id'],
                        'nome' => $c[0]['nome'],
                        'email' => $c[0]['email'],
                        'senha' => $c[0]['senha']
                    ];
                }

                $this->redirect('checkout', 'checkout', 'finalizar');
            }
        }
    }

    public function addAction()
    {
        // Require Authentication
        $this->hasAuthentication($_SESSION);

        // Validate
        if (strlen(trim(ValidateUtil::cleanInput(trim($_POST['cep'])))) < 8) {

            $cliente = $this->dao('Cliente', 'Cliente')->select([
                '*'
            ], array(
                'id',
                '=',
                $_SESSION['cliente']['id_cliente']
            ));

            $data = [
                'msg' => 'CEP Inválido',
                'error' => TRUE,
                'cliente' => $cliente[0],
                'endereco' => dao('Core', 'Endereco')->select([
                    '*'
                ], [
                    'id_cliente',
                    '=',
                    $_SESSION['cliente']['id_cliente']
                ])
            ];

            $this->renderView('conta', $data);
        } else {

            $form = [];
            $cliente = $this->dao('Cliente', 'Cliente')->select([
                '*'
            ], array(
                'id',
                '=',
                $_SESSION['cliente']['id_cliente']
            ));

            if ($_SESSION['cliente']['id_cliente'] && isset($_POST['destinatario'])) {
                $form['destinatario'] = trim($_POST['destinatario']);
                $form['cep'] = trim(ValidateUtil::cleanInput(trim($_POST['cep'])));
                $form['endereco'] = trim($_POST['endereco']);
                $form['numero'] = trim($_POST['numero']);
                $form['bairro'] = trim($_POST['bairro']);
                $form['cidade'] = trim($_POST['cidade']);
                $form['uf'] = trim($_POST['estado']);
                $form['id_cliente'] = $_SESSION['cliente']['id_cliente'];
                $form['data_hora_ultima_alteracao'] = date("Y-m-d") . "T" . date("H:i:s");

                $hasAdress = $this->dao('Core', 'Endereco')->countOcurrence('*', [
                    [
                        'id_cliente',
                        '=',
                        $_SESSION['cliente']['id_cliente']
                    ],
                    [
                        'principal',
                        '=',
                        TRUE
                    ]
                ]);

                if ($hasAdress == 0) {
                    $form['principal'] = TRUE;
                }

                $idEndereco = $this->dao('Core', 'Endereco')->insert($form);
                if ($idEndereco) {
                    $data = [
                        'msg' => 'Endereço cadastrado com sucesso',
                        'error' => FALSE,
                        'cliente' => $cliente[0],
                        'endereco' => dao('Core', 'Endereco')->select([
                            '*'
                        ], [
                            'id_cliente',
                            '=',
                            $_SESSION['cliente']['id_cliente']
                        ])
                    ];

                    if ($_SESSION['REDIRECT_TO_CHECKOUT']) {
                        $this->redirect('checkout', 'checkout', 'finalizar');
                    } else {
                        $this->renderView('conta', $data);
                    }
                }
            } else {
                $data = [
                    'msg' => 'Erro ao cadastrar endereço',
                    'error' => FALSE,
                    'cliente' => $cliente[0],
                    'endereco' => dao('Core', 'Endereco')->select([
                        '*'
                    ], [
                        'id_cliente',
                        '=',
                        $_SESSION['cliente']['id_cliente']
                    ])
                ];

                $this->renderView('conta', $data);
            }
        }
    }

    public function contaAction()
    {
        // Require Authentication
        $this->hasAuthentication($_SESSION);

        if (!isset($_SESSION['cliente']['id_cliente'])) {
            $this->redirect('cliente', 'cliente', 'informacoes');
            return;
        }

        if (Request::get('redirect') == 'checkout') {
            $_SESSION['REDIRECT_TO_CHECKOUT'] = TRUE;
        }

        $idCliente = $_SESSION['cliente']['id_cliente'];
        $cliente = $this->dao('Cliente', 'Cliente')->select([
            '*'
        ], array(
            'id',
            '=',
            $idCliente
        ));

        if (!is_array($cliente) || sizeof($cliente) === 0) {
            $this->redirect('cliente', 'cliente', 'informacoes');
            return;
        }

        $data = [
            'pedido' => $this->dao('Core', 'Pedido')->select([
                '*'
            ], array(
                'id_cliente',
                '=',
                $idCliente
            )),
            'cliente' => $cliente[0],
            'endereco' => dao('Core', 'Endereco')->select([
                '*'
            ], [
                'id_cliente',
                '=',
                $idCliente
            ]),
            'queridinhos' => $this->dao('Produto', 'Produto')->queridinhos($idCliente)
        ];

        $this->renderView("conta", $data);
    }

    public function logarAction()
    {
        $this->isAdmin(FALSE);
        $cliente = $this->dao('Cliente', 'Cliente')->select([
            '*'
        ], array(
            array(
                'email',
                '=',
                $_POST['email']
            ),
            array(
                'senha',
                '=',
                md5($_POST['senha'])
            )
        ));

        if (count($cliente) == 1) {
            if ($cliente[0]['ativo'] == 0) {
                $this->renderView("login", [
                    'error' => TRUE,
                    'msg' => 'CONTA BLOQUEADA, FAVOR, CONSULTE O ADMINISTRADOR'
                ]);
            } else {
                if (md5($_POST['senha']) == $cliente[0]['senha'] && $cliente[0]['email'] == $_POST['email']) {
                    $nome_cliente = explode(' ', $cliente[0]['nome']);
                    $arr = [
                        'id_cliente' => $cliente[0]['id'],
                        'nome' => $nome_cliente[0],
                        'email' => $cliente[0]['email'],
                        'senha' => $cliente[0]['senha']
                    ];

                    $_SESSION['cliente'] = $arr;

                    $this->sessionIdCreate();

                    $this->redirect('cliente', 'cliente', 'conta');
                } else {
                    $this->renderView("login", [
                        'error' => TRUE,
                        'msg' => 'LOGIN OU SENHA INCORRETA'
                    ], "Login | " . NOME_LOJA);
                }
            }
        } else {
            $this->renderView("login", [
                'error' => TRUE,
                'msg' => 'NENHUM USUÁRIO ENCONTRADO'
            ]);
        }
    }

    public function sessionIdCreate()
    {
        if (! isset($_SESSION['MY_ID_SESSION']) || $_SESSION['MY_ID_SESSION'] == NULL) {
            $_SESSION['MY_ID_SESSION'] = rand(11000000000, 99000000000);
        }
    }

    public function mudaTudoAction()
    {
        $c = $this->dao('Cliente', 'Cliente')->select([
            '*'
        ], [
            'email',
            '!=',
            NULL
        ]);

        foreach ($c as $cli) {
            $muda = $this->dao('Cliente', 'Cliente')->update([
                'email' => $cli['email']
            ], [
                'id',
                '=',
                $cli['id']
            ]);
        }
    }

    public function cadastrarAction()
    {
        $this->sessionIdCreate();
        $hassPassword = md5($_POST['senha']);
        $formCliente = [
            'nome' => $_POST['nome'] . '  ' . $_POST['sobrenome'],
            'email' => $_POST['email'],
            'senha' => $hassPassword,
            'cpf' => ValidateUtil::cleanInput($_POST['cpf']),
            'data_nascimento' => DateUtil::convertDMYtoYMD($_POST['data_nascimento'], '-'),
            'telefone' => ValidateUtil::cleanInput($_POST['telefone']),
            'ativo' => TRUE,
            'date_create' => date("Y-m-d"),
            'data_hora_ultima_alteracao' => date("Y-m-d") . "T" . date("H:i:s")
        ];

        if (isset($_POST['sexo']) && $_POST['sexo'] != '') {
            $formCliente['sexo'] = trim($_POST['sexo']);
        }

        $c = $this->dao('Cliente', 'Cliente')->select([
            '*'
        ], [
            'email',
            '=',
            $_POST['email']
        ]);

        // nenhum cliente com esse cpf
        if (count($c) == 0) {
            $formCliente['id_tipo_cliente'] = 1;
            $idCliente = $this->dao('Cliente', 'Cliente')->insert($formCliente);
            $this->insertAdressIPLocalGoogleMaps($idCliente);

            $_SESSION['hash_novo_cliente'] = $idCliente;

            $cliente = $this->dao('Cliente', 'Cliente')->select([
                '*'
            ], array(
                array(
                    'id',
                    '=',
                    $idCliente
                )
            ));

            // if (! isset($_SESSION['cliente'])) {
            $nome_cliente = explode(' ', $cliente[0]['nome']);

            $arr = [
                'id_cliente' => $cliente[0]['id'],
                'nome' => $nome_cliente[0],
                'email' => $cliente[0]['email'],
                'senha' => $cliente[0]['senha']
            ];

            $_SESSION['cliente'] = $arr;
            // }

            $this->redirect('cliente', 'cliente', 'conta');
        } else if (count($c) == 1) {

            // UPDATE ACCOUNT
            $this->dao('Cliente', 'Cliente')->update($formCliente, [
                'email',
                '=',
                $_POST['email']
            ]);

            $_SESSION['hash_novo_cliente'] = $c[0]['id'];

            $this->insertAdressIPLocalGoogleMaps($c[0]['id']);

            $cliente = $this->dao('Cliente', 'Cliente')->select([
                '*'
            ], array(
                array(
                    'email',
                    '=',
                    $_POST['email']
                )
            ));

            // if (! isset($_SESSION['cliente'])) {
            $nome_cliente = explode(' ', $cliente[0]['nome']);

            $arr = [
                'id_cliente' => $cliente[0]['id'],
                'nome' => $nome_cliente[0],
                'email' => $cliente[0]['email'],
                'senha' => $cliente[0]['senha']
            ];

            $_SESSION['cliente'] = $arr;
            // }

            $this->redirect('cliente', 'cliente', 'conta');
        } else {
            $this->redirect('site', 'site');
        }
    }

    public function sairAction()
    {
        $this->isAdmin(TRUE);
        if (isset($_SESSION['cliente'])) {
            unset($_SESSION['cliente']);
        }

        header('Location: /');
    }

    public function faleConoscoAction()
    {
        $_email = new Email();
        $faleConosco = $this->dao('Core', 'FaleConosco');

        $form_fale = [
            'nome' => $this->post('nome'),
            'email' => $this->post('email'),
            'telefone' => $this->post('telefone'),
            'numero_pedido' => $this->post('numero_pedido'),
            'mensagem' => $this->post('mensagem')
        ];

        if ($_SESSION['cliente']['id_cliente']) {
            $form_fale['id_cliente'] = $_SESSION['cliente']['id_cliente'];
        }

        $faleConosco->insert($form_fale);

        // Avisar por email
        $_email->send($this->post('email'), 'Olá ' . $this->post('nome') . ', Você tem uma nova mensagem', $_email->duvidas($this->post('nome'), $this->post('email')), '1001');
    }
}
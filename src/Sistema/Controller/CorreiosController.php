<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Configuration\Configuration;
use Krypitonite\Http\Request;
use PhpSigep;
use PhpSigep\Config;
use Krypitonite\Util\CorreiosUtil;
use Krypitonite\Mail\Email;
require_once 'vendor/stavarengo/php-sigep-fpdf/PhpSigepFPDF.php';
require_once 'vendor/stavarengo/php-sigep/src/PhpSigep/Bootstrap.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Entities/Pessoa.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Core/ItemBag.php';
require_once 'lib/declaracao-conteudo-correios-master/src/DeclaracaoConteudo.php';

// https://www.youtube.com/watch?v=TtGhgbjjFgs
// chrome-extension://oemmndcbldboiebfnladdacbdfmadadm/http://www.corporativo.correios.com.br/encomendas/sigepweb/doc/Manual_de_Implementacao_do_Web_Service_SIGEP_WEB.pdf
class CorreiosController extends AbstractController
{

    public function __construct()
    {}

    public function minhasEtiquetasAction()
    {
        $cad = Request::get('cad');
        $des = Request::get('des');
        $plp = Request::get('plp');
        $posts = Request::get('posts');

        $msg = '';
        if ($cad) {
            $msg = '<b>' . $plp . ' gerada com sucesso!</b>';
        }

        if ($des) {
            $msg = '<b>As entregas foram desagrupadas com sucesso!</b>';
        }

        if ($posts) {
            $msg = '<b>Postagens Atualizadas com Sucesso!</b>';
        }

        $ultimos_120_dias = date('Y-m-d', strtotime('-120 days', strtotime(date('d-m-Y'))));
        $pedidosAprovados = self::dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'data',
                '>',
                $ultimos_120_dias
            ],
            [
                'id_situacao_pedido',
                '=',
                2 // Aprovado
            ],
            [
                'agrupado_etiqueta',
                '!=',
                TRUE
            ],
            [
                'pedido_b2w',
                '=',
                FALSE
            ]
        ], [
            'data',
            'ASC'
        ]);

        $idPedidosNaoPostados = [];
        foreach ($pedidosAprovados as $p) {
            $temRastreioPostadoEssePedido = self::dao('Core', 'Rastreiamento')->countOcurrence('*', [
                [
                    'id_pedido',
                    '=',
                    $p['id']
                ],
                [
                    'postado',
                    '=',
                    TRUE
                ]
            ]);

            if ($temRastreioPostadoEssePedido == 0) {
                $idPedidosNaoPostados[] = $p['id'];
            }
        }

        $plps = self::dao('Core', 'PreListaPostagem')->select([
            '*'
        ]);

        $etiquetasPostadas = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'postada',
            '=',
            TRUE
        ]);

        $pessoa = self::dao('Core', 'Pessoa')->select([
            '*'
        ]);

        $wh = null;
        if (sizeof($idPedidosNaoPostados) > 0) {
            $wh = [
                'id',
                'IN',
                $idPedidosNaoPostados
            ];
        }

        $data = [
            'pedidos_aprovados' => self::dao('Core', 'Pedido')->select([
                '*'
            ], $wh),
            'plps' => $plps,
            'entregas_encaminhadas' => $etiquetasPostadas,
            'pessoa' => $pessoa,
            'cad' => $cad,
            'des' => $des,
            'msg' => $msg,
            'posts' => $posts
        ];

        $this->renderView('minhas_etiquetas', $data);
    }

    public function desagruparAction()
    {
        $Idplp = Request::get('id_plp');

        $_PLP = self::dao('Core', 'PreListaPostagem')->select([
            '*'
        ], [
            'id',
            '=',
            $Idplp
        ]);

        $etiquetas = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'id_pre_lista_postagem',
            '=',
            $_PLP[0]['id']
        ]);

        foreach ($etiquetas as $etq) {

            // Checa se a etiqueta já foi postada ou não
            $temRastreioPostadoEssePedido = self::dao('Core', 'Rastreiamento')->countOcurrence('*', [
                [
                    'id_pedido',
                    '=',
                    $etq['id_pedido']
                ],
                [
                    'postado',
                    '=',
                    TRUE
                ]
            ]);

            // PERMISSÕES CASO A ETIQUETA NÃO FOR POSTADA
            // DELETA A ETIQUETA
            // DESAGRUPA OS PEDIDOS
            // DELETA O RASTREAMENTO PARA SER GERADO UM NOVO
            if ($temRastreioPostadoEssePedido == 0) {
                $this->dao('Core', 'Pedido')->update([
                    'agrupado_etiqueta' => NULL
                ], [
                    'id',
                    '=',
                    $etq['id_pedido']
                ]);

                self::dao('Core', 'Etiqueta')->update([
                    'id_rastreamento' => NULL,
                    'id_pre_lista_postagem' => NULL
                ], [
                    'id_pedido',
                    '=',
                    $etq['id_pedido']
                ]);

                // Descarta os códigos gerados
                self::dao('Core', 'Rastreiamento')->delete([
                    'id_pedido',
                    '=',
                    $etq['id_pedido']
                ]);

                self::dao('Core', 'Etiqueta')->delete([
                    'id',
                    '=',
                    $etq['id']
                ]);
            }
        }

        $temEtiquetaParaPostar = self::dao('Core', 'Etiqueta')->countOcurrence('*', [
            [
                'id_pre_lista_postagem',
                '=',
                $Idplp
            ],
            [
                'postada',
                '!=',
                TRUE
            ]
        ]);

        // Todas postadas, não tem nenhuma pendente ainda
        if ($temEtiquetaParaPostar == 0) {
            self::dao('Core', 'PreListaPostagem')->update([
                'finalizada' => TRUE
            ], [
                'id',
                '=',
                $Idplp
            ]);
        }

        $this->redirect('sistema', 'correios', 'minhasEtiquetas', 'des=1');
    }

    public function degruparEtiquetaAction()
    {
        $idEtiqueta = $_POST['id_etiqueta'];
        $etiqueta = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'id',
            '=',
            $idEtiqueta
        ]);

        $_SESSION['id_plp_p_deletar'] = $etiqueta[0]['id_pre_lista_postagem'];

        if (sizeof($etiqueta) == 1) {
            $this->dao('Core', 'Pedido')->update([
                'agrupado_etiqueta' => NULL
            ], [
                'id',
                '=',
                $etiqueta[0]['id_pedido']
            ]);

            self::dao('Core', 'Etiqueta')->update([
                'id_rastreamento' => NULL,
                'id_pre_lista_postagem' => NULL
            ], [
                'id_pedido',
                '=',
                $etiqueta[0]['id_pedido']
            ]);

            // Descarta os códigos gerados
            self::dao('Core', 'Rastreiamento')->delete([
                'id_pedido',
                '=',
                $etiqueta[0]['id_pedido']
            ]);

            self::dao('Core', 'Etiqueta')->delete([
                'id',
                '=',
                $etiqueta[0]['id']
            ]);

            if (isset($_SESSION['id_plp_p_deletar']) && $_SESSION['id_plp_p_deletar'] != NULL) {
                $etqs = self::dao('Core', 'Etiqueta')->countOcurrence('*', [
                    [
                        'id_pre_lista_postagem',
                        '=',
                        $_SESSION['id_plp_p_deletar']
                    ]
                ]);

                if ($etqs == 0) {
                    self::dao('Core', 'PreListaPostagem')->update([
                        'finalizada' => TRUE
                    ], [
                        'id',
                        '=',
                        $_SESSION['id_plp_p_deletar']
                    ]);
                }
            }
        }
        echo true;
    }

    public function download_etiquetaAction()
    {
        $arquivo = Configuration::PATH_ETIQUETAS . '/' . $_GET["arquivo"] . '.pdf';
        if (isset($arquivo) && file_exists($arquivo)) {
            switch (strtolower(substr(strrchr(basename($arquivo), "."), 1))) {
                case "pdf":
                    $tipo = "application/pdf";
                    break;
                case "exe":
                    $tipo = "application/octet-stream";
                    break;
                case "zip":
                    $tipo = "application/zip";
                    break;
                case "doc":
                    $tipo = "application/msword";
                    break;
                case "xls":
                    $tipo = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $tipo = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $tipo = "image/gif";
                    break;
                case "png":
                    $tipo = "image/png";
                    break;
                case "jpg":
                    $tipo = "image/jpg";
                    break;
                case "mp3":
                    $tipo = "audio/mpeg";
                    break;
                case "php": // deixar vazio por seurança
                case "htm": // deixar vazio por seurança
                case "html": // deixar vazio por seurança
            }
            header("Content-Type: " . $tipo);
            header("Content-Length: " . filesize($arquivo));
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            readfile($arquivo); // lê o arquivo
            exit(); // aborta pós-ações
        }
    }

    public function gerarPLPAction()
    {
        $numbers_orders = [];
        foreach ($_POST as $pedido) {
            $numbers_orders[] = $pedido;
        }

        $_pedidos_ = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'numero_pedido',
            'IN',
            $numbers_orders
        ]);

        $idPLP = $this->dao('Core', 'PreListaPostagem')->insert([
            "data_geracao" => date('Y-m-d'),
            "hora_geracao" => date('H:i:s'),
            "fechada" => 0
        ]);

        foreach ($_pedidos_ as $pedido) {
            if ($this->dao('Core', 'Etiqueta')->countOcurrence('*', [
                'id_pedido',
                '=',
                $pedido['id']
            ]) == 0) {
                // Gerar lista de postagens
                $this->dao('Core', 'Etiqueta')->insert([
                    "data_geracao" => date('Y-m-d'),
                    "data_validade" => date('Y-m-d', strtotime('+7 days', strtotime(date('d-m-Y')))),
                    "plp_fechada" => 0,
                    "id_pre_lista_postagem" => $idPLP,
                    "id_pedido" => $pedido['id']
                ]);

                // Desagrupar pedidos
                $this->dao('Core', 'Pedido')->update([
                    'agrupado_etiqueta' => TRUE
                ], [
                    'id',
                    '=',
                    $pedido['id']
                ]);
            }
        }

        $this->redirect('sistema', 'correios', 'minhasEtiquetas', 'cad=1&plp=Pré Lista de Postagem');
    }

    public function gerarEtiquetasAction()
    {
        $iDplp = $_POST['id_plp'];
        $autoload = 'vendor/autoload.php';

        if (file_exists($autoload)) {
            require_once $autoload;
        }

        $_PLP = self::dao('Core', 'PreListaPostagem')->select([
            '*'
        ], [
            'id',
            '=',
            $iDplp
        ]);

        $etiquetas = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            [
                'postada',
                '!=',
                TRUE
            ],
            [
                'id_pre_lista_postagem',
                '=',
                $_PLP[0]['id']
            ]
        ]);

        $accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();

        $config = new \PhpSigep\Config();
        $config->setAccessData($accessDataParaAmbienteDeHomologacao);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
        $config->setCacheOptions(array(
            'storageOptions' => array(
                'enabled' => false,
                'ttl' => 10, // "time to live" de 10 segundos
                'cacheDir' => sys_get_temp_dir() // Opcional. Quando não inforado é usado o valor retornado de "sys_get_temp_dir()"
            )
        ));

        \PhpSigep\Bootstrap::start($config);

        unset($_POST['table-pedidos-aprovados_length']);

        $numbers_orders = [];
        if (sizeof($etiquetas) > 0) {
            foreach ($etiquetas as $etq) {
                $numbers_orders[] = $etq['id_pedido'];
            }
        }

        $_pedidos_dao = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            'IN',
            $numbers_orders
        ]);

        // Set Remetente
        $_remetente = $this->dao('Core', 'Pessoa')->select([
            '*'
        ], [
            'id',
            '=',
            $_POST['id_fornecedor']
        ]);

        $nomeRemetente = 'Shopvitas';
        $enderecoRemetente = $_remetente[0]['endereco'];
        $numeroRemetente = $_remetente[0]['numero'];
        $complementoRemetente = $_remetente[0]['complemento'];
        $bairroRemetente = $_remetente[0]['bairro'];
        $cepRemetente = $_remetente[0]['cep'];
        $ufRemetente = $_remetente[0]['uf'];
        $cidadeRemetente = $_remetente[0]['cidade'];
        if ($_remetente[0]['endereco'] == NULL || $_remetente[0]['cep'] == NULL || $_remetente[0]['cidade'] == NULL) {
            lp('Remetente com endereço incompleto');
        }

        $plp = new \PhpSigep\Model\PreListaDePostagem();
        $plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());

        $encomendas = [];
        foreach ($_pedidos_dao as $pedido) {
            $itens = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            $endereco = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido['id_endereco']
            ]);

            // $prds = '';

            // Data produtcs
            $peso = [];
            $altura = [];
            $largura = [];
            $comprimento = [];
            $diametro = [];

            // Itens do pedido
            foreach ($itens as $item) {
                $produto = $this->dao('Core', 'Produto')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $item['id_produto']
                ]);

                $total_peso = 0;
                $total_cm_cubico = 0;

                foreach ($produto as $row) {
                    $row_peso = $row['peso_bruto'] * $row['quantidade'];
                    $row_cm_3 = ($row['altura'] * $row['largura'] * $row['comprimento']) * $row['quantidade']; // CONVERT PARA Cm3 (Volume)

                    $total_peso += ($row_peso / 1000); // CONVERT GRAMAS PARA KILOGRAMAS E SOMA O TOTAL
                    $total_cm_cubico += $row_cm_3;
                }

                $raiz_cubica = round(pow($total_cm_cubico, 1 / 3), 2);

                $comprimento[] = $raiz_cubica < 16 ? 16 : $raiz_cubica;
                $altura[] = $raiz_cubica < 2 ? 2 : $raiz_cubica;
                $largura[] = $raiz_cubica < 11 ? 11 : $raiz_cubica;
                $peso[] = $total_peso < 0.3 ? 0.3 : $total_peso;
                $diametro[] = hypot(floatval($comprimento), floatval($largura));
                // /////// END INFOS PRODUTO

                // $prds .= $produto[0]['descricao'] . "\n";
            }

            $cliente = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido['id_cliente']
            ]);

            $dimensao = new \PhpSigep\Model\Dimensao();
            $dimensao->setAltura(intval(array_sum($altura)));
            $dimensao->setLargura(intval(array_sum($largura)));
            $dimensao->setComprimento(intval(array_sum($comprimento)));
            $dimensao->setDiametro(intval(array_sum($diametro)));
            $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

            $destinatario = new \PhpSigep\Model\Destinatario();
            $destinatario->setNome($cliente[0]['nome']);
            $destinatario->setCidade($endereco[0]['cidade']);
            $destinatario->setBairro($endereco[0]['bairro']);
            $destinatario->setUf($endereco[0]['uf']);
            $destinatario->setLogradouro($endereco[0]['endereco']);
            $destinatario->setNumero($endereco[0]['numero']);
            $destinatario->setComplemento($endereco[0]['complemento']);
            $destinatario->setCep($endereco[0]['cep']);

            $destino = new \PhpSigep\Model\DestinoNacional();
            $destino->setBairro($endereco[0]['bairro']);
            $destino->setCep($endereco[0]['cep']);
            $destino->setCidade($endereco[0]['cidade']);
            $destino->setUf($endereco[0]['uf']);

            if (isset($pedido['id_nota_fiscal']) && $pedido['id_nota_fiscal'] != NULL) {
                $numeroNotaFiscal = $this->dao('Core', 'Cliente')->getField('numero', $pedido['id_nota_fiscal']);
                $destino->setNumeroNotaFiscal($numeroNotaFiscal);
            }

            $destino->setNumeroPedido($pedido['numero_pedido']);

            $sedex_ = \PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_03220;
            // $sedex_ = \PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA;

            $pac_ = \PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_03298;
            // $pac_ = \PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA;

            $valor_declarado_pac = \PhpSigep\Model\ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC;
            $valor_declarado_sedex = \PhpSigep\Model\ServicoAdicional::SERVICE_VALOR_DECLARADO_SEDEX;

            $service_envio = null;
            $servico_declarado = null;
            if ($pedido['codigo_envio'] == '04510' || $pedido['codigo_envio'] == '03085') {
                $service_envio = $pac_;
                $servico_declarado = $valor_declarado_pac;
            } else if ($pedido['codigo_envio'] == '04014' || $pedido['codigo_envio'] == '03050') {
                $service_envio = $sedex_;
                $servico_declarado = $valor_declarado_sedex;
            } else {
                $service_envio = $pac_;
                $servico_declarado = $valor_declarado_pac;
            }

            // Rementente e Destinatário do mesmo estado, vai SEDEX :D
            if ($ufRemetente == $endereco[0]['uf']) {
                $service_envio = $sedex_;
                $servico_declarado = $valor_declarado_sedex;
            }

            // ///////////////////////////////////////////
            // Verificar Código de Rastreio
            // ///////////////////////////////////////////
            $rastreamento = $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            $codigo_rastreio_sem_dv = NULL;
            $codigo_rastreio_com_dv = NULL;
            $idRas = NULL;
            if (sizeof($rastreamento) == 0) {
                $solicitacao = $this->_solicitarEtiqueta($service_envio);
                $get = $solicitacao->getResult();
                $codigo_rastreio_sem_dv = $get[0]->getEtiquetaSemDv();
                $codigo_rastreio_com_dv = $get[0]->getEtiquetaComDv();

                // Cad Rastreio
                $idRas = $this->dao('Core', 'Rastreiamento')->insert([
                    'codigo' => $codigo_rastreio_com_dv,
                    'id_pedido' => $pedido['id'],
                    'codigo_sem_dv' => $get[0]->getEtiquetaSemDv(),
                    'codigo_com_dv' => $get[0]->getEtiquetaComDv()
                ]);
            } else if (sizeof($rastreamento) > 0 && isset($rastreamento[0]['codigo']) && $rastreamento[0]['codigo'] != NULL) {
                $codigo_rastreio_sem_dv = $rastreamento[0]['codigo_sem_dv'];
                $codigo_rastreio_com_dv = $rastreamento[0]['codigo_com_dv'];
                $idRas = $rastreamento[0]['id'];
            }

            // Salva o rastreio na etiqueta
            $this->dao('Core', 'Etiqueta')->update([
                'id_rastreamento' => $idRas
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            $etiqueta = new \PhpSigep\Model\Etiqueta();
            $etiqueta->setEtiquetaComDv($codigo_rastreio_com_dv);
            $etiqueta->setEtiquetaSemDv($codigo_rastreio_sem_dv);
            $etiqueta->setDv($etiqueta->getDv());

            // Adicionar serviço de AVISO DE RECEBIMENTO
            // $servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
            // $servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
            // $servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO);

            // Adicionar VALOR DECLARADO
            $servicoAdicional2 = new \PhpSigep\Model\ServicoAdicional();
            $servicoAdicional2->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
            $servicoAdicional2->setCodigoServicoAdicional($servico_declarado);

            if ($pedido['valor'] == NULL || empty($pedido['valor'])) {
                $servicoAdicional2->setValorDeclarado(0);
            } else {
                $servicoAdicional2->setValorDeclarado($pedido['valor']);
            }

            // Adicionar serviço de MÃO PRÓPRIA
            // $servicoAdicional3 = new \PhpSigep\Model\ServicoAdicional();
            // $servicoAdicional3->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
            // $servicoAdicional3->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_MAO_PROPRIA);

            $encomenda = new \PhpSigep\Model\ObjetoPostal();
            $encomenda->setServicosAdicionais(array(
                // $servicoAdicional,
                $servicoAdicional2
                // $servicoAdicional3
            ));

            if (array_sum($total_peso) == 0) {
                $total_peso = 1;
            }

            $encomenda->setDestinatario($destinatario);
            $encomenda->setDestino($destino);
            $encomenda->setDimensao($dimensao);
            $encomenda->setEtiqueta($etiqueta);
            $encomenda->setPeso($total_peso);
            // $encomenda->setObservacao($prds);
            $encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem($service_envio));
            // *** FIM DOS DADOS DA ENCOMENDA QUE SERÁ DESPACHADA *** //

            // *** DADOS DO REMETENTE *** //
            $remetente = new \PhpSigep\Model\Remetente();
            $remetente->setNome($nomeRemetente);
            $remetente->setLogradouro($enderecoRemetente);
            $remetente->setNumero($numeroRemetente);
            $remetente->setComplemento($complementoRemetente);
            $remetente->setBairro($bairroRemetente);
            $remetente->setCep($cepRemetente);
            $remetente->setUf($ufRemetente);
            $remetente->setCidade($cidadeRemetente);

            $encomendas[] = $encomenda;

            $plp->setRemetente($remetente);

            $this->dao('Core', 'Pedido')->update([
                'agrupado_etiqueta' => TRUE
            ], [
                'id',
                '=',
                $pedido['id']
            ]);
        }

        $plp->setEncomendas($encomendas);

        $real = new \PhpSigep\Services\SoapClient\Real();

        $resultC = $real->fechaPlpVariosServicos($plp);

        $result = $resultC->getResult();

        if ($result == null) {
            echo "Ocorreu um erro ao gerar a PLP com os pedidos selecionados.<br />";
            lp($resultC);
            exit();
        }

        $numero_plp = $result->getIdPlp();

        self::dao('Core', 'PreListaPostagem')->update([
            "numero_plp" => $numero_plp,
            "fechada" => TRUE,
            "id_remetente" => $_remetente[0]['id']
        ], [
            'id',
            '=',
            $iDplp
        ]);

        $pdf = new \PhpSigep\Pdf\CartaoDePostagem2018($plp, time(), 'public/img/logo-etiqueta.png', array());
        $pdf->_volume = '1/1';

        $pdf->render('F', Configuration::PATH_ETIQUETAS . '/' . $numero_plp . '.pdf');

        $this->redirect('sistema', 'correios', 'minhasEtiquetas', 'cad=1&plp=PLP ' . $numero_plp);
    }

    public function _solicitarEtiqueta($service_send = NULL)
    {
        $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
        $usuario = trim((isset($_GET['usuario']) ? $_GET['usuario'] : $accessDataDeHomologacao->getUsuario()));
        $senha = trim((isset($_GET['senha']) ? $_GET['senha'] : $accessDataDeHomologacao->getSenha()));
        $cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();

        $accessData = new \PhpSigep\Model\AccessData();
        $accessData->setUsuario($usuario);
        $accessData->setSenha($senha);
        $accessData->setCnpjEmpresa($cnpjEmpresa);

        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas(1);
        $params->setServicoDePostagem($service_send);
        $params->setAccessData($accessData);

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $config = new Config();
        $config->setAccessData($accessData);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);

        \PhpSigep\Bootstrap::start($config);

        $return = $phpSigep->solicitaEtiquetas($params);

        return $return;
    }

    public function atualizarPostagens()
    {
        $email = new Email();

        $etiquetas = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'postada',
            '!=',
            TRUE
        ]);

        foreach ($etiquetas as $etq) {
            $idPedido = $etq['id_pedido'];
            $rastreio = $this->dao('Core', 'Rastreiamento')->select([
                '*'
            ], [
                [
                    'postado',
                    '!=',
                    TRUE
                ],
                [
                    'id_pedido',
                    '=',
                    $idPedido
                ]
            ]);

            // VERIFICA SE O CÓDIGO INFORMADO JÁ FOI POSTADO PELOS CORREIOS
            if ($this->_checarPostagemCodigoRastreio($rastreio[0]['codigo'])) {

                $this->dao('Core', 'Rastreiamento')->update([
                    'postado' => TRUE
                ], [
                    'id',
                    '=',
                    $etq['id_rastreamento']
                ]);

                $itens = $this->dao('Core', 'ItemPedido')->select([
                    '*'
                ], [
                    'id_pedido',
                    '=',
                    $idPedido
                ]);

                $this->dao('Core', 'Pedido')->update([
                    "id_pedido_status_fornecedor" => 2
                ], [
                    'id',
                    '=',
                    $idPedido
                ]);

                // NOME CLIENTE
                $nomeCliente = $this->dao('Core', 'Cliente')->getField('nome', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $idPedido));

                // E-MAIL CLIENTE
                $emailCliente = $this->dao('Core', 'Cliente')->getField('email', $this->dao('Core', 'Pedido')
                    ->getField('id_cliente', $idPedido));

                // PRODUTOS
                $produtos = [];
                foreach ($itens as $item) {
                    $produtos[] = [
                        'produto' => $this->dao('Core', 'Produto')->getField('descricao', $item['id_produto']),
                        'quantidade' => $item['quantidade'],
                        'preco' => $item['preco']
                    ];
                }

                // ENDEREÇO CLIENTE
                $endereco = $this->dao('Core', 'Endereco')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $this->dao('Core', 'Pedido')
                        ->getField('id_endereco', $idPedido)
                ]);

                // CORPO EMAIL DE CONFIRMAÇÃO DE PEDIDO
                $bodyConfirmacaoPedido = $email->confirmacaoCodigoRastreio($nomeCliente, $rastreio[0]['codigo'], $produtos, $endereco);

                // ENVIAR EMAIL DE CONFIRMAÇÃO DE PEDIDO | SEND
                $email->send($emailCliente, "Código de Rastreiamento - " . NOME_LOJA, $bodyConfirmacaoPedido, '1001');

                $etiquetas = self::dao('Core', 'Etiqueta')->update([
                    'postada' => TRUE
                ], [
                    'id',
                    '=',
                    $etq['id']
                ]);
            }
        }
    }

    private function _checarPostagemCodigoRastreio($codigo = '')
    {
        $post = array(
            'Objetos' => $codigo
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $result = curl_exec($ch);
        curl_close($ch);

        if (strpos($result, 'Objeto postado') !== false) {
            return true;
        } else {
            return false;
        }

        // $correiosUtil = new CorreiosUtil();
        // $resultado = $correiosUtil->etapasDaPostagem($codigo);

        // if (! isset($resultado->erro)) {
        // // verifica se correios retornou apenas 1 Object
        // // no evento. Isso indica apenas 1 evento encontrado.
        // if (is_object($resultado->evento)) {
        // return true;
        // } else {
        // foreach ($resultado->evento as $e) {
        // return true;
        // }
        // }
        // } else {
        // return false;
        // }
    }

    public function cancelar_objetoAction()
    {
        $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
        $usuario = $accessDataDeHomologacao->getUsuario();
        $senha = $accessDataDeHomologacao->getSenha();
        $cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();

        $accessData = new \PhpSigep\Model\AccessData();
        $accessData->setUsuario($usuario);
        $accessData->setSenha($senha);
        $accessData->setCnpjEmpresa($cnpjEmpresa);

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $config = new Config();
        $config->setAccessData($accessData);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);

        \PhpSigep\Bootstrap::start($config);

        $resultC = $phpSigep->bloquearObjeto(Request::get('codigo'), Request::get('plp'), $usuario, $senha);

        $result = $resultC->getResult();

        lp($result);
    }

    public function cancelarObjetoAction()
    {
        $id_etiqueta = Request::get('idEtiqueta');

        $_etiqueta = self::dao('Core', 'Etiqueta')->select([
            '*'
        ], [
            'id',
            '=',
            $id_etiqueta
        ]);

        $_rastreio = self::dao('Core', 'Rastreiamento')->select([
            '*'
        ], [
            'id',
            '=',
            $_etiqueta[0]['id_rastreamento']
        ]);

        $_plp = self::dao('Core', 'PreListaPostagem')->select([
            '*'
        ], [
            'id',
            '=',
            $_etiqueta[0]['id_pre_lista_postagem']
        ]);

        $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
        $usuario = $accessDataDeHomologacao->getUsuario();
        $senha = $accessDataDeHomologacao->getSenha();
        $cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();

        $accessData = new \PhpSigep\Model\AccessData();
        $accessData->setUsuario($usuario);
        $accessData->setSenha($senha);
        $accessData->setCnpjEmpresa($cnpjEmpresa);

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $config = new Config();
        $config->setAccessData($accessData);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);

        \PhpSigep\Bootstrap::start($config);

        $resultC = $phpSigep->bloquearObjeto($_rastreio[0]['codigo'], $_plp[0]['numero_plp'], $usuario, $senha);

        $result = $resultC->getResult();

        if ($result == null) {
            echo json_encode([
                'mensagem' => 'Erro ao cancelar entrega ou já existe uma tentativa de cancelamento registrada.'
            ]);
        } else {
            self::dao('Core', 'Etiqueta')->update([
                'entrega_cancelada' => TRUE
            ], [
                'id',
                '=',
                $_etiqueta[0]['id']
            ]);

            echo json_encode([
                'mensagem' => 'Registro de bloqueio de entrega registrado'
            ]);
        }
    }
}
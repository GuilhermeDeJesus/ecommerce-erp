<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use Configuration\Configuration;
use PhpSigep;
use PhpSigep\Config;
require_once 'vendor/stavarengo/php-sigep-fpdf/PhpSigepFPDF.php';
require_once 'vendor/stavarengo/php-sigep/src/PhpSigep/Bootstrap.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Entities/Pessoa.php';
require_once 'lib/declaracao-conteudo-correios-master/src/Core/ItemBag.php';
require_once 'lib/declaracao-conteudo-correios-master/src/DeclaracaoConteudo.php';

class EtiquetaDevolucaoProdutoClienteController extends AbstractController
{

    public function gerarEtiquetaAction()
    {
        $idPedido = $_POST['id_pedido'];
        $idPessoa = $_POST['id_destinatario'];

        $autoload = 'vendor/autoload.php';

        if (file_exists($autoload)) {
            require_once $autoload;
        }

        if (! class_exists('PhpSigepFPDF')) {
            throw new \RuntimeException('Não encontrei a classe PhpSigepFPDF. Execute "php composer.phar install" ou baixe o projeto ' . 'https://github.com/stavarengo/php-sigep-fpdf manualmente e adicione a classe no seu path.');
        }

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

        $_pedido_ = $this->dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $idPedido
        ]);

        $plp = new \PhpSigep\Model\PreListaDePostagem();
        $plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());

        $encomendas = [];
        foreach ($_pedido_ as $pedido) {
            $itens = $this->dao('Core', 'ItemPedido')->select([
                '*'
            ], [
                'id_pedido',
                '=',
                $pedido['id']
            ]);

            $_destinatario = $this->dao('Core', 'Pessoa')->select([
                '*'
            ], [
                'id',
                '=',
                $idPessoa
            ]);

            $cep_remetente = NULL;

            // Data produtcs
            $peso = [];
            $altura = [];
            $largura = [];
            $comprimento = [];
            $diametro = [];

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
                $diametro[] = hypot($comprimento, $largura);

                // /////// END INFOS PRODUTO

                $fornecedor_remetente = $this->dao('Core', 'Pessoa')->select([
                    '*'
                ], [
                    'id',
                    '=',
                    $produto[0]['id_fornecedor']
                ]);

                $cep_remetente = $fornecedor_remetente[0]['cep'];
            }

            $remetente_cliente = $this->dao('Core', 'Endereco')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido['id_endereco']
            ]);

            $cliente = $this->dao('Core', 'Cliente')->select([
                '*'
            ], [
                'id',
                '=',
                $pedido['id_cliente']
            ]);

            $dimensao = new \PhpSigep\Model\Dimensao();
            $dimensao->setAltura(array_sum($altura));
            $dimensao->setLargura(array_sum($largura));
            $dimensao->setComprimento(array_sum($comprimento));
            $dimensao->setDiametro(array_sum($diametro));
            $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

            $destinatario = new \PhpSigep\Model\Destinatario();
            $destinatario->setNome($_destinatario[0]['nome']);
            $destinatario->setCidade($_destinatario[0]['cidade']);
            $destinatario->setBairro($_destinatario[0]['bairro']);
            $destinatario->setUf($_destinatario[0]['uf']);
            $destinatario->setLogradouro($_destinatario[0]['endereco']);
            $destinatario->setNumero($_destinatario[0]['numero']);
            $destinatario->setComplemento($_destinatario[0]['complemento']);

            $destino = new \PhpSigep\Model\DestinoNacional();
            $destino->setBairro($_destinatario[0]['bairro']);
            $destino->setCep($_destinatario[0]['cep']);
            $destino->setCidade($_destinatario[0]['cidade']);
            $destino->setUf($_destinatario[0]['uf']);
            // $destino->setNumeroNotaFiscal();
            $destino->setNumeroPedido($pedido['codigo_transacao']);

            $solicitacao = $this->_solicitarEtiqueta();
            $get = $solicitacao->getResult();

            $etiqueta = new \PhpSigep\Model\Etiqueta();
            $etiqueta->setEtiquetaComDv($get[0]->getEtiquetaComDv());
            $etiqueta->setEtiquetaSemDv($get[0]->getEtiquetaSemDv());

            $servicoAdicional2 = new \PhpSigep\Model\ServicoAdicional();
            $servicoAdicional2->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
            $servicoAdicional2->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC);

            if ($pedido['valor'] == NULL || empty($pedido['valor'])) {
                $servicoAdicional2->setValorDeclarado(0);
            } else {
                $servicoAdicional2->setValorDeclarado($pedido['valor']);
            }

            $encomenda = new \PhpSigep\Model\ObjetoPostal();
            $encomenda->setServicosAdicionais(array(
                $servicoAdicional2
            ));

            if (array_sum($total_peso) == 0) {
                $total_peso = 1;
            }

            $encomenda->setDestinatario($destinatario);
            $encomenda->setDestino($destino);
            $encomenda->setDimensao($dimensao);
            $encomenda->setEtiqueta($etiqueta);
            $encomenda->setPeso($total_peso);
            $encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_03085));
            // *** FIM DOS DADOS DA ENCOMENDA QUE SERÁ DESPACHADA *** //

            // *** DADOS DO REMETENTE *** //
            $remetente = new \PhpSigep\Model\Remetente();
            $remetente->setNome($cliente[0]['nome']);
            $remetente->setLogradouro($remetente_cliente[0]['endereco']);
            $remetente->setNumero($remetente_cliente[0]['numero']);
            $remetente->setComplemento('');
            $remetente->setBairro($remetente_cliente[0]['bairro']);
            $remetente->setCep($cep_remetente);
            $remetente->setUf($remetente_cliente[0]['uf']);
            $remetente->setCidade($remetente_cliente[0]['cidade']);
            $encomendas[] = $encomenda;

            $plp->setRemetente($remetente);
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

        $cod_ras = $get[0]->getEtiquetaComDv();
        if ($cod_ras == NULL) {
            $cod_ras = $get[0]->getEtiquetaSemDv();
        }

        $this->dao('Core', 'EtiquetaDevolucaoProdutoCliente')->insert([
            "plp" => $numero_plp,
            "codigo_rastreio" => $cod_ras,
            "data_validade" => date('Y-m-d', strtotime('+7 days', strtotime(date('d-m-Y')))),
            "data_emissao" => date('Y-m-d'),
            "status" => 'Aguardando Postagem',
            "id_pedido" => $idPedido,
            "id_destinatario" => $idPessoa
        ]);

        $pdf = new \PhpSigep\Pdf\CartaoDePostagem2018($plp, time(), 'public/img/logo-etiqueta.png', array());

        $pdf->render('F', Configuration::PATH_ETIQUETAS . '/' . $numero_plp . '.pdf');

        $this->redirect('sistema', 'venda', 'form', 'cadEt=1&id=' . $_pedido_[0]['id']);
    }

    public function _solicitarEtiqueta()
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
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_04510);
        $params->setAccessData($accessData);

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $config = new Config();
        $config->setAccessData($accessData);
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);

        \PhpSigep\Bootstrap::start($config);

        $return = $phpSigep->solicitaEtiquetas($params);

        return $return;
    }
}

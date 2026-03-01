<?php
namespace Store\Sistema\Controller;

use Krypitonite\Controller\AbstractController;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use Configuration\Configuration;
use NFePHP\NFe\Make;
use Krypitonite\Http\Request;
require_once 'lib/fpdf_merge/fpdf_merge.php';

class NfController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function indexAction()
    {
        $ultimos_90_dias = date('Y-m-d', strtotime('-90 days', strtotime(date('d-m-Y'))));
        $notas = self::dao('Core', 'Pedido')->select([
            '*'
        ], [
            [
                'data',
                '>',
                $ultimos_90_dias
            ],
            [
                'id_situacao_pedido',
                '=',
                2 // Aprovado
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

        // FUNÇÃO SOLICITAR
        $emitida = Request::get('emitida');
        $erro = Request::get('erro');

        $msg = '';
        $setAlert = FALSE;
        if (isset($emitida) && $emitida == 0 || $emitida == '0' && isset($erro) && $erro == 0 || $erro == '0') {
            $setAlert = TRUE;
            $msg = 'Erro de validação da nota';
        } else if (isset($emitida) && $emitida == 1 || $emitida == '1') {
            $setAlert = TRUE;
            $msg = 'Nota(s) emitida(s) com sucesso';
        }

        $data = [
            'pedidos' => $notas,
            'setAlert' => $setAlert,
            'msg' => $msg
        ];

        $this->renderView('index', $data);
    }

    public function enviarNotaAction()
    {
        $pedido = Request::get('pedido');

        $_pedido = self::dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $pedido
        ]);

        $_itensPedido = self::dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $pedido
        ]);

        $_endereco = self::dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $_pedido[0]['id_endereco']
        ]);

        $_cliente = self::dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_pedido[0]['id_cliente']
        ]);

        $itens = [];
        $i = 0;
        $_total_produto = [];
        foreach ($_itensPedido as $_item) {

            $i ++;

            $produto = self::dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $_item['id_produto']
            ]);

            $quantidade_item = $_item['quantidade'];
            $valor_unitario_comercial = $_item['preco'] / $quantidade_item;
            $valor_unitario_tributavel = $_item['preco'] / $quantidade_item;
            $valor_bruto = number_format($_item['preco'], 2, '.', '');
            $tamanho = ($_item['id_tamanho_produto'] != '') ? $this->dao('Core', 'TamanhoProduto')->getField('descricao', $_item['id_tamanho_produto']) : '';

            $descricao = $produto[0]['descricao'];
            if ($tamanho != '') {
                $descricao .= ' ' . $tamanho;
            }

            $local_destino = "02";
            $cfop = "6102";
            if ($_endereco[0]['uf'] == 'GO') {
                $local_destino = "01";
                $cfop = "5102";
            }

            $itens[] = array(
                "numero_item" => "$i",
                "codigo_produto" => $_item['id_produto'],
                "descricao" => $descricao,
                "cfop" => $cfop,
                "unidade_comercial" => "un",
                "quantidade_comercial" => "$quantidade_item",
                "valor_unitario_comercial" => "$valor_unitario_comercial",
                "valor_unitario_tributavel" => "$valor_unitario_tributavel",
                "unidade_tributavel" => "un",
                "codigo_ncm" => "33030010",
                "quantidade_tributavel" => "$quantidade_item",
                "valor_bruto" => $valor_bruto,
                "icms_situacao_tributaria" => "102",
                "icms_origem" => "0",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07",
                "local_destino" => $local_destino
            );

            $_total_produto[] = $_item['preco'];
        }

        // $server = "https://homologacao.focusnfe.com.br";
        // $login = "xeGbgRsVgnkFDF1SaawI5OX3FFgKhJVZ";

        $server = "https://api.focusnfe.com.br";
        $login = "4FghTfARjKNMsU1JeBeappgzeVFdrED5";

        $password = "";

        $_total_produto = number_format(array_sum($_total_produto), 2, '.', '');
        // $frete = number_format($_pedido[0]['frete'], 2, '.', ' ');
        $valor_total = number_format(($_pedido[0]['valor']), 2, '.', '');

        $nfe = array(
            "natureza_operacao" => "Venda de mercadorias",
            "data_emissao" => date("Y-m-d") . "T" . date("H:i:s"),
            "data_entrada_saida" => date("Y-m-d") . "T" . date("H:i:s"),
            "tipo_documento" => "1",
            "finalidade_emissao" => "1",
            "cnpj_emitente" => "20747907000126",
            "nome_emitente" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
            "nome_fantasia_emitente" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
            "logradouro_emitente" => "Rua 14 Q 34 Girassol",
            "numero_emitente" => "10",
            "bairro_emitente" => "Centro",
            "municipio_emitente" => "Cocalzinho de Góias",
            "uf_emitente" => "GO",
            "cep_emitente" => "72979000",
            "inscricao_estadual_emitente" => "107828243",
            // "nome_destinatario" => "NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL",
            "nome_destinatario" => $_cliente[0]['nome'],
            "cpf_destinatario" => $_cliente[0]['cpf'],
            "telefone_destinatario" => $_cliente[0]['telefone'],
            "logradouro_destinatario" => $_endereco[0]['endereco'],
            "numero_destinatario" => $_endereco[0]['numero'],
            "bairro_destinatario" => $_endereco[0]['bairro'],
            "municipio_destinatario" => $_endereco[0]['cidade'],
            "uf_destinatario" => $_endereco[0]['uf'],
            "pais_destinatario" => "Brasil",
            "cep_destinatario" => $_endereco[0]['cep'],
            "valor_frete" => "0",
            "valor_seguro" => "0",
            "valor_total" => $_total_produto,
            "valor_produtos" => "$_total_produto",
            "modalidade_frete" => "0",
            "items" => $itens
        );

        $ref = rand(11000, 99000);
        $emitida = FALSE;
        $erro = FALSE;

        $typesErros = [
            'erro_validacao',
            'erro_validacao_schema',
            'erro_autorizacao'
        ];

        // NOTA JÁ CADASTRADA NO SISTEMA, ENTÃO VERIFICAR
        if ($_pedido[0]['id_nota_fiscal'] != NULL) {
            $nota = self::dao('Core', 'NotaFiscal')->select([
                '*'
            ], [
                'id',
                '=',
                $_pedido[0]['id_nota_fiscal']
            ]);

            $idNotaFiscal = $nota[0]['id'];
            // $refbase = $nota[0]['ref'];
            $refbase = $ref;

            // A nota não foi autorizada ainda mas está cadastrada no sistema
            if ($nota[0]['status'] == NULL || $nota[0]['status'] != 'autorizado') {
                $nfeb = unserialize($nota[0]['json_nfe']);
                if ($nota[0]['codigo'] == 'erro_validacao' || $nota[0]['status'] == NULL) {
                    $nfeb = $nfe;
                }

                if ($nota[0]['json_nfe'] == NULL) {
                    $nfeb = $nfe;
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $refbase);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfeb));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                curl_close($ch);

                $verifyBeforeResults = json_decode($body);
                if (isset($verifyBeforeResults->codigo) && in_array($verifyBeforeResults->codigo, $typesErros)) {
                    $erro = TRUE;
                    self::dao('Core', 'NotaFiscal')->update([
                        "codigo_erro" => $verifyBeforeResults->codigo,
                        "mensagem_erro" => $verifyBeforeResults->mensagem
                    ], [
                        'id',
                        '=',
                        $idNotaFiscal
                    ]);
                } else {
                    $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                    do {
                        $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                    } while ($results->status == 'processando_autorizacao');

                    if ($results->status == 'autorizado') {
                        self::dao('Core', 'NotaFiscal')->update([
                            "status" => $results->status,
                            "numero" => $results->numero,
                            "serie" => $results->serie,
                            "chave_nfe" => $results->chave_nfe,
                            "caminho_pdf" => $results->caminho_danfe,
                            "caminho_xml" => $results->caminho_xml_nota_fiscal,
                            "mensagem_sefax" => $results->mensagem_sefaz,
                            "ref" => $results->ref,
                            "numero_carta_correcao" => $results->numero_carta_correcao,
                            "status_sefaz" => $results->mensagem_sefaz,
                            "data_emissao" => date("Y-m-d")
                        ], [
                            'id',
                            '=',
                            $idNotaFiscal
                        ]);

                        $emitida = TRUE;
                        $arquivo = $server . $results->caminho_danfe;

                        file_put_contents(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf", file_get_contents($arquivo));

                        $this->_download_nota(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf");
                    }
                }
            }
        } else {

            // Nota ainda não cadastrada e não emitida
            $idNotaFiscal = self::dao('Core', 'NotaFiscal')->insert([
                "json_nfe" => serialize($nfe),
                "ref" => $ref
            ]);

            self::dao('Core', 'Pedido')->update([
                'id_nota_fiscal' => $idNotaFiscal
            ], [
                'id',
                '=',
                $_pedido[0]['id']
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $ref);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
            $body = curl_exec($ch);
            curl_close($ch);

            $verifyBeforeResults = json_decode($body);
            if (isset($verifyBeforeResults->codigo) && in_array($verifyBeforeResults->codigo, $typesErros)) {
                $erro = TRUE;
                self::dao('Core', 'NotaFiscal')->update([
                    "codigo_erro" => $verifyBeforeResults->codigo,
                    "mensagem_erro" => $verifyBeforeResults->mensagem
                ], [
                    'id',
                    '=',
                    $idNotaFiscal
                ]);
            } else {
                $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                do {
                    $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                } while ($results->status == 'processando_autorizacao');

                if ($results->status == 'autorizado') {
                    self::dao('Core', 'NotaFiscal')->update([
                        "status" => $results->status,
                        "numero" => $results->numero,
                        "serie" => $results->serie,
                        "chave_nfe" => $results->chave_nfe,
                        "caminho_pdf" => $results->caminho_danfe,
                        "caminho_xml" => $results->caminho_xml_nota_fiscal,
                        "mensagem_sefax" => $results->mensagem_sefaz,
                        "numero_carta_correcao" => $results->numero_carta_correcao,
                        "status_sefaz" => $results->mensagem_sefaz,
                        "data_emissao" => date("Y-m-d")
                    ], [
                        'id',
                        '=',
                        $idNotaFiscal
                    ]);

                    $emitida = TRUE;
                    $arquivo = $server . $results->caminho_danfe;

                    file_put_contents(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf", file_get_contents($arquivo));

                    $this->_download_nota(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf");
                } else {
                    echo '<pre>';
                    echo 'Linha 317 <br>';
                    print_r($body);
                    die();
                }
            }
        }

        $this->redirect('sistema', 'nf', 'index', 'emitida=' . $emitida . '&erro=' . $erro);
        header("Refresh: 3; url=?m=sistema&c=nf&a=index");

        // URL PARA EXEMPLO DE CONSULTA
        // https://api.focusnfe.com.br/v2/nfe/45544?token=4FghTfARjKNMsU1JeBeappgzeVFdrED5
    }

    // Solucionado
    public function gerarPDFNotaAction()
    {
        $idPedido = Request::get('pedido');

        $_pedido = self::dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $idPedido
        ]);

        $notaFiscal = self::dao('Core', 'NotaFiscal')->select([
            '*'
        ], [
            'id',
            '=',
            $_pedido[0]['id_nota_fiscal']
        ]);

        $arquivo = Configuration::PATH_NOTA . '/' . $notaFiscal[0]["chave_nfe"] . '.pdf';
        if (isset($arquivo) && file_exists($arquivo)) {
            header("Content-Type: " . "application/pdf");
            header("Content-Length: " . filesize($arquivo));
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            readfile($arquivo); // lê o arquivo
            exit(); // aborta pós-ações
        }

        if (! file_exists($arquivo)) {
            $arquivo = "https://api.focusnfe.com.br" . $notaFiscal[0]["caminho_pdf"];

            header("Content-Type: " . "application/pdf");
            header("Content-Length: " . filesize($arquivo));
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            readfile($arquivo); // lê o arquivo
            exit(); // aborta pós-ações
        }

        header("Location: $arquivo");
    }

    public function formularioAction()
    {
        // Mesclar PDFs
        if (isset($_POST['gerar_pdf_selecionados']) && $_POST['gerar_pdf_selecionados'] = 'gerar_pdf_selecionados') {

            $idPedidos = [];
            foreach ($_POST as $vals) {
                $ped = intval($vals);
                if (isset($_POST['_pe_' . $ped])) {
                    $idPedidos[] = $_POST['_pe_' . $ped];
                }
            }

            $_pedido = self::dao('Core', 'Pedido')->select([
                '*'
            ], [
                'id',
                'IN',
                $idPedidos
            ]);

            $pathsPdfs = [];
            foreach ($_pedido as $pedido) {
                if ($pedido['id_nota_fiscal'] != NULL) {
                    $notaFiscal = self::dao('Core', 'NotaFiscal')->select([
                        '*'
                    ], [
                        'id',
                        '=',
                        $pedido['id_nota_fiscal']
                    ]);

                    if ($notaFiscal[0]['status'] == 'autorizado' && $notaFiscal[0]['caminho_pdf'] != NULL) {
                        $pathsPdfs[] = Configuration::PATH_NOTA . '/' . $notaFiscal[0]["chave_nfe"] . '.pdf';
                    }
                }
            }

            $pdf = new \FPDF_Merge();
            foreach ($pathsPdfs as $file) {
                if (file_exists($file) && is_readable($file)) {
                    $pdf->add($file);
                }
            }

            if (sizeof($pathsPdfs) != 0) {
                $number = rand(1000, 9500);
                $arquivo = Configuration::PATH_NOTA . '/' . 'Notas_Shopvitas_' . $number . '.pdf';

                file_put_contents($arquivo, file_get_contents($pdf->output()));

                if (isset($arquivo) && file_exists($arquivo)) {
                    header("Content-Type: " . "application/pdf");
                    header("Content-Length: " . filesize($arquivo));
                    header("Content-Disposition: attachment; filename=" . basename($arquivo));
                    readfile($arquivo); // lê o arquivo
                    exit(); // aborta pós-ações
                }
            }
        } else if (isset($_POST['gerar_notas_selecionadas']) && $_POST['gerar_notas_selecionadas'] = 'gerar_notas_selecionados') {
            foreach ($_POST as $vals) {
                $ped = intval($vals);
                if (isset($_POST['_pe_' . $ped])) {
                    $idPedidos = $_POST['_pe_' . $ped];
                    $this->_enviarNota($idPedidos);
                }
            }

            $this->redirect('sistema', 'nf', 'index');
            header("Refresh: 3; url=?m=sistema&c=nf&a=index");
        }
    }

    private function _enviarNota($pedido = NULL)
    {
        $_pedido = self::dao('Core', 'Pedido')->select([
            '*'
        ], [
            'id',
            '=',
            $pedido
        ]);

        $_itensPedido = self::dao('Core', 'ItemPedido')->select([
            '*'
        ], [
            'id_pedido',
            '=',
            $pedido
        ]);

        $_endereco = self::dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $_pedido[0]['id_endereco']
        ]);

        $_cliente = self::dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_pedido[0]['id_cliente']
        ]);

        $itens = [];
        $i = 0;
        $_total_produto = [];
        foreach ($_itensPedido as $_item) {

            $i ++;

            $produto = self::dao('Core', 'Produto')->select([
                '*'
            ], [
                'id',
                '=',
                $_item['id_produto']
            ]);

            $quantidade_item = $_item['quantidade'];
            $valor_unitario_comercial = $_item['preco'] / $quantidade_item;
            $valor_unitario_tributavel = $_item['preco'] / $quantidade_item;
            $valor_bruto = number_format($_item['preco'], 2, '.', '');
            $tamanho = ($_item['id_tamanho_produto'] != '') ? $this->dao('Core', 'TamanhoProduto')->getField('descricao', $_item['id_tamanho_produto']) : '';

            $descricao = $produto[0]['descricao'];
            if ($tamanho != '') {
                $descricao .= ' ' . $tamanho;
            }

            $local_destino = "02";
            $cfop = "6102";
            if ($_endereco[0]['uf'] == 'GO') {
                $local_destino = "01";
                $cfop = "5102";
            }

            $itens[] = array(
                "numero_item" => "$i",
                "codigo_produto" => $_item['id_produto'],
                "descricao" => $descricao,
                "cfop" => $cfop,
                "unidade_comercial" => "un",
                "quantidade_comercial" => "$quantidade_item",
                "valor_unitario_comercial" => "$valor_unitario_comercial",
                "valor_unitario_tributavel" => "$valor_unitario_tributavel",
                "unidade_tributavel" => "un",
                "codigo_ncm" => "33030010",
                "quantidade_tributavel" => "$quantidade_item",
                "valor_bruto" => $valor_bruto,
                "icms_situacao_tributaria" => "102",
                "icms_origem" => "0",
                "pis_situacao_tributaria" => "07",
                "cofins_situacao_tributaria" => "07",
                "local_destino" => $local_destino
            );

            $_total_produto[] = $_item['preco'];
        }

        // $server = "https://homologacao.focusnfe.com.br";
        // $login = "xeGbgRsVgnkFDF1SaawI5OX3FFgKhJVZ";

        $server = "https://api.focusnfe.com.br";
        $login = "4FghTfARjKNMsU1JeBeappgzeVFdrED5";

        $password = "";

        $_total_produto = number_format(array_sum($_total_produto), 2, '.', '');
        // $frete = number_format($_pedido[0]['frete'], 2, '.', ' ');
        $valor_total = number_format(($_pedido[0]['valor']), 2, '.', '');

        $typesErros = [
            'erro_validacao',
            'erro_validacao_schema',
            'erro_autorizacao'
        ];

        $nfe = array(
            "natureza_operacao" => "Venda de mercadorias",
            "data_emissao" => date("Y-m-d") . "T" . date("H:i:s"),
            "data_entrada_saida" => date("Y-m-d") . "T" . date("H:i:s"),
            "tipo_documento" => "1",
            "finalidade_emissao" => "1",
            "cnpj_emitente" => "20747907000126",
            "nome_emitente" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
            "nome_fantasia_emitente" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
            "logradouro_emitente" => "Rua 14 Q 34 Girassol",
            "numero_emitente" => "10",
            "bairro_emitente" => "Centro",
            "municipio_emitente" => "Cocalzinho de Góias",
            "uf_emitente" => "GO",
            "cep_emitente" => "72979000",
            "inscricao_estadual_emitente" => "107828243",
            // "nome_destinatario" => "NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL",
            "nome_destinatario" => $_cliente[0]['nome'],
            "cpf_destinatario" => $_cliente[0]['cpf'],
            "telefone_destinatario" => $_cliente[0]['telefone'],
            "logradouro_destinatario" => $_endereco[0]['endereco'],
            "numero_destinatario" => $_endereco[0]['numero'],
            "bairro_destinatario" => $_endereco[0]['bairro'],
            "municipio_destinatario" => $_endereco[0]['cidade'],
            "uf_destinatario" => $_endereco[0]['uf'],
            "pais_destinatario" => "Brasil",
            "cep_destinatario" => $_endereco[0]['cep'],
            "valor_frete" => "0",
            "valor_seguro" => "0",
            "valor_total" => $_total_produto,
            "valor_produtos" => "$_total_produto",
            "modalidade_frete" => "0",
            "items" => $itens
        );

        $ref = rand(11000, 99000);

        // NOTA JÁ CADASTRADA NO SISTEMA, ENTÃO VERIFICAR
        if ($_pedido[0]['id_nota_fiscal'] != NULL) {
            $nota = self::dao('Core', 'NotaFiscal')->select([
                '*'
            ], [
                'id',
                '=',
                $_pedido[0]['id_nota_fiscal']
            ]);

            $idNotaFiscal = $nota[0]['id'];
            // $refbase = $nota[0]['ref'];
            $refbase = $ref;

            // A nota não foi autorizada ainda mas está cadastrada no sistema
            if ($nota[0]['status'] == NULL || $nota[0]['status'] != 'autorizado') {
                $nfeb = unserialize($nota[0]['json_nfe']);
                if ($nota[0]['codigo'] == 'erro_validacao') {
                    $nfeb = $nfe;
                }

                if ($nota[0]['json_nfe'] == NULL) {
                    $nfeb = $nfe;
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $refbase);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfeb));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                curl_close($ch);

                $verifyBeforeResults = json_decode($body);
                if (isset($verifyBeforeResults->codigo) && in_array($verifyBeforeResults->codigo, $typesErros)) {
                    self::dao('Core', 'NotaFiscal')->update([
                        "codigo_erro" => $verifyBeforeResults->codigo,
                        "mensagem_erro" => $verifyBeforeResults->mensagem
                    ], [
                        'id',
                        '=',
                        $idNotaFiscal
                    ]);
                } else {
                    $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                    do {
                        $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                    } while ($results->status == 'processando_autorizacao');

                    if ($results->status == 'autorizado') {
                        self::dao('Core', 'NotaFiscal')->update([
                            "status" => $results->status,
                            "numero" => $results->numero,
                            "serie" => $results->serie,
                            "chave_nfe" => $results->chave_nfe,
                            "caminho_pdf" => $results->caminho_danfe,
                            "caminho_xml" => $results->caminho_xml_nota_fiscal,
                            "mensagem_sefax" => $results->mensagem_sefaz,
                            "ref" => $results->ref,
                            "numero_carta_correcao" => $results->numero_carta_correcao,
                            "status_sefaz" => $results->mensagem_sefaz,
                            "data_emissao" => date("Y-m-d")
                        ], [
                            'id',
                            '=',
                            $idNotaFiscal
                        ]);

                        $arquivo = $server . $results->caminho_danfe;

                        file_put_contents(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf", file_get_contents($arquivo));
                    } else {
                        echo '<pre>';
                        echo 'Linha 250 <br>';
                        print_r($body);
                        die();
                    }
                }
            }
        } else {

            // Nota ainda não cadastrada e não emitida
            $idNotaFiscal = self::dao('Core', 'NotaFiscal')->insert([
                "json_nfe" => serialize($nfe),
                "ref" => $ref
            ]);

            self::dao('Core', 'Pedido')->update([
                'id_nota_fiscal' => $idNotaFiscal
            ], [
                'id',
                '=',
                $_pedido[0]['id']
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $ref);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
            $body = curl_exec($ch);
            curl_close($ch);

            $verifyBeforeResults = json_decode($body);
            if (isset($verifyBeforeResults->codigo) && in_array($verifyBeforeResults->codigo, $typesErros)) {
                self::dao('Core', 'NotaFiscal')->update([
                    "codigo_erro" => $verifyBeforeResults->codigo,
                    "mensagem_erro" => $verifyBeforeResults->mensagem
                ], [
                    'id',
                    '=',
                    $idNotaFiscal
                ]);
            } else {
                $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                do {
                    $results = json_decode(file_get_contents($server . '/v2/nfe/' . $ref . '?token=' . $login));
                } while ($results->status == 'processando_autorizacao');

                if ($results->status == 'autorizado') {
                    self::dao('Core', 'NotaFiscal')->update([
                        "status" => $results->status,
                        "numero" => $results->numero,
                        "serie" => $results->serie,
                        "chave_nfe" => $results->chave_nfe,
                        "caminho_pdf" => $results->caminho_danfe,
                        "caminho_xml" => $results->caminho_xml_nota_fiscal,
                        "mensagem_sefax" => $results->mensagem_sefaz,
                        "numero_carta_correcao" => $results->numero_carta_correcao,
                        "status_sefaz" => $results->mensagem_sefaz,
                        "data_emissao" => date("Y-m-d")
                    ], [
                        'id',
                        '=',
                        $idNotaFiscal
                    ]);

                    $arquivo = $server . $results->caminho_danfe;

                    file_put_contents(Configuration::PATH_NOTA . "/" . $results->chave_nfe . ".pdf", file_get_contents($arquivo));
                } else {
                    echo '<pre>';
                    echo 'Linha 317 <br>';
                    print_r($body);
                    die();
                }
            }
        }

        $this->redirect('sistema', 'nf', 'index', 'emitida=' . TRUE . '&erro=' . FALSE);
        header("Refresh: 3; url=?m=sistema&c=nf&a=index&emitida=1&erro=0");
    }

    public function cancelarNotaAction()
    {
        $idNotaFiscal = $_POST['id_nota_fiscal'];
        $justificativa_cancelamento = $_POST['justificativa_cancelamento'];

        $notaFiscal = self::dao('Core', 'NotaFiscal')->select([
            '*'
        ], [
            'id',
            '=',
            $idNotaFiscal
        ]);

        // $server = "https://homologacao.focusnfe.com.br";
        // $login = "xeGbgRsVgnkFDF1SaawI5OX3FFgKhJVZ";

        $server = "https://api.focusnfe.com.br";
        $login = "4FghTfARjKNMsU1JeBeappgzeVFdrED5";

        $ref = $notaFiscal[0]['ref'];
        $password = "";
        $justificativa = array(
            "justificativa" => $justificativa_cancelamento
        );

        // Inicia o processo de envio das informações usando o cURL.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/" . $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($justificativa));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        $body = curl_exec($ch);
        curl_close($ch);

        $results = json_decode($body);

        switch ($results->status) {
            case 'erro_cancelamento':
                self::dao('Core', 'NotaFiscal')->update([
                    "mensagem_sefax" => $results->mensagem_sefaz,
                    "status_sefaz" => $results->status_sefaz
                ], [
                    'id',
                    '=',
                    $idNotaFiscal
                ]);
                break;
            case 'cancelado':
                self::dao('Core', 'NotaFiscal')->update([
                    "status" => $results->status,
                    "mensagem_sefax" => $results->mensagem_sefaz,
                    "status_sefaz" => $results->status_sefaz
                ], [
                    'id',
                    '=',
                    $idNotaFiscal
                ]);
                break;
        }

        echo json_encode($results);
    }

    public function _download_nota($arquivo = NULL)
    {
        if (isset($arquivo) && file_exists($arquivo)) {
            header("Content-Type: " . "application/pdf");
            header("Content-Length: " . filesize($arquivo));
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            readfile($arquivo); // lê o arquivo
            exit(); // aborta pós-ações
        }

        if (! file_exists($arquivo)) {
            //
        }
    }

    // public function testAction()
    // {
    // $arr = [
    // "atualizacao" => date("Y-m-d h:i:s"),
    // "tpAmb" => 2,
    // "razaosocial" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
    // "cnpj" => "20747907000126",
    // "siglaUF" => "GO",
    // "schemes" => "PL_009_V4",
    // "versao" => '4.00',
    // "tokenIBPT" => "AAAAAAA",
    // "CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
    // "CSCid" => "000001",
    // "proxyConf" => [
    // "proxyIp" => "",
    // "proxyPort" => "",
    // "proxyUser" => "",
    // "proxyPass" => ""
    // ]
    // ];
    // // monta o config.json
    // $configJson = json_encode($arr);

    // // carrega o conteudo do certificado.
    // $content = file_get_contents(Configuration::FILE_CERTIFICADO_DIGITAL_A1_GJS);

    // $password = '1234';

    // try {
    // $tools = new Tools($configJson, Certificate::readPfx($content, $password));

    // $tools->model('55');

    // // sempre que ativar a contingência pela primeira vez essa informação deverá ser
    // // gravada na base de dados ou em um arquivo para uso posterior, até que a mesma seja
    // // desativada pelo usuário, essa informação não é persistida automaticamente e depende
    // // de ser gravada pelo ERP
    // // NOTA: esse retorno da função é um JSON
    // // $contingencia = $tools->contingency->activate('SP', 'Teste apenas');

    // // e se necessário carregada novamente quando a classe for instanciada,
    // // obtendo a string da contingência em json e passando para a classe
    // // $tools->contingency->load($contingencia);

    // // Se não for passada a sigla do estado, o status será obtido com o modo de
    // // contingência, se este estiver ativo ou seja SVCRS ou SVCAN, usando a sigla
    // // contida no config.json
    // $response = $tools->sefazStatus();

    // // Se for passada a sigla do estado, o status será buscado diretamente
    // // no autorizador indcado pela sigla do estado, dessa forma ignorando
    // // a contingência
    // // $response = $tools->sefazStatus('SP');

    // header('Content-type: text/xml; charset=UTF-8');
    // echo $response;
    // } catch (\Exception $e) {
    // // aqui você trata possiveis exceptions
    // echo $e->getMessage();
    // }
    // }

    // public function testEmissaoAction()
    // {
    // $arr = [
    // "atualizacao" => date("Y-m-d h:i:s"),
    // "tpAmb" => 2,
    // "razaosocial" => "GJS EMPREENDEDORISMO DIGITAL LTDA",
    // "cnpj" => "20747907000126",
    // "siglaUF" => "SP",
    // "schemes" => "PL_009_V4",
    // "versao" => '4.00',
    // "tokenIBPT" => "AAAAAAA",
    // "CSC" => "GPB0JBWLUR6HWFTVEAS6RJ69GPCROFPBBB8G",
    // "CSCid" => "000001",
    // "proxyConf" => [
    // "proxyIp" => "",
    // "proxyPort" => "",
    // "proxyUser" => "",
    // "proxyPass" => ""
    // ]
    // ];

    // $pfxcontent = file_get_contents(Configuration::FILE_CERTIFICADO_DIGITAL_A1_GJS);

    // $configJson = json_encode($arr);

    // $password = '1234';

    // $tools = new Tools($configJson, Certificate::readPfx($pfxcontent, $password));
    // // $tools->disableCertValidation(true); //tem que desabilitar
    // $tools->model('65');

    // try {

    // $make = new Make();

    // // infNFe OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->Id = '';
    // $std->versao = '4.00';
    // $infNFe = $make->taginfNFe($std);

    // // ide OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->cUF = 14;
    // $std->cNF = '03701267';
    // $std->natOp = 'VENDA CONSUMIDOR';
    // $std->mod = 65;
    // $std->serie = 1;
    // $std->nNF = 100;
    // $std->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP');
    // $std->dhSaiEnt = null;
    // $std->tpNF = 1;
    // $std->idDest = 1;
    // $std->cMunFG = 1400100;
    // $std->tpImp = 1;
    // $std->tpEmis = 1;
    // $std->cDV = 2;
    // $std->tpAmb = 2;
    // $std->finNFe = 1;
    // $std->indFinal = 1;
    // $std->indPres = 1;
    // $std->procEmi = 3;
    // $std->verProc = '4.13';
    // $std->dhCont = null;
    // $std->xJust = null;
    // $ide = $make->tagIde($std);

    // // emit OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->xNome = 'SUA RAZAO SOCIAL LTDA';
    // $std->xFant = 'RAZAO';
    // $std->IE = '111111111';
    // $std->IEST = null;
    // // $std->IM = '95095870';
    // $std->CNAE = '4642701';
    // $std->CRT = 1;
    // $std->CNPJ = '99999999999999';
    // // $std->CPF = '12345678901'; //NÃO PASSE TAGS QUE NÃO EXISTEM NO CASO
    // $emit = $make->tagemit($std);

    // // enderEmit OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->xLgr = 'Avenida Getúlio Vargas';
    // $std->nro = '5022';
    // $std->xCpl = 'LOJA 42';
    // $std->xBairro = 'CENTRO';
    // $std->cMun = 1400100;
    // $std->xMun = 'BOA VISTA';
    // $std->UF = 'RR';
    // $std->CEP = '69301030';
    // $std->cPais = 1058;
    // $std->xPais = 'Brasil';
    // $std->fone = '55555555';
    // $ret = $make->tagenderemit($std);

    // // dest OPCIONAL
    // $std = new \stdClass();
    // $std->xNome = 'Eu Ltda';
    // $std->CNPJ = '01234123456789';
    // // $std->CPF = '12345678901';
    // // $std->idEstrangeiro = 'AB1234';
    // $std->indIEDest = 9;
    // // $std->IE = '';
    // // $std->ISUF = '12345679';
    // // $std->IM = 'XYZ6543212';
    // $std->email = 'seila@seila.com.br';
    // $dest = $make->tagdest($std);

    // // enderDest OPCIONAL
    // $std = new \stdClass();
    // $std->xLgr = 'Avenida Sebastião Diniz';
    // $std->nro = '458';
    // $std->xCpl = null;
    // $std->xBairro = 'CENTRO';
    // $std->cMun = 1400100;
    // $std->xMun = 'Boa Vista';
    // $std->UF = 'RR';
    // $std->CEP = '69301088';
    // $std->cPais = 1058;
    // $std->xPais = 'Brasil';
    // $std->fone = '1111111111';
    // $ret = $make->tagenderdest($std);

    // // prod OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->item = 1;
    // $std->cProd = '1111';
    // $std->cEAN = "SEM GTIN";
    // $std->xProd = 'CAMISETA REGATA GG';
    // $std->NCM = 61052000;
    // // $std->cBenef = 'ab222222';
    // $std->EXTIPI = '';
    // $std->CFOP = 5101;
    // $std->uCom = 'UNID';
    // $std->qCom = 1;
    // $std->vUnCom = 100.00;
    // $std->vProd = 100.00;
    // $std->cEANTrib = "SEM GTIN"; // '6361425485451';
    // $std->uTrib = 'UNID';
    // $std->qTrib = 1;
    // $std->vUnTrib = 100.00;
    // // $std->vFrete = 0.00;
    // // $std->vSeg = 0;
    // // $std->vDesc = 0;
    // // $std->vOutro = 0;
    // $std->indTot = 1;
    // // $std->xPed = '12345';
    // // $std->nItemPed = 1;
    // // $std->nFCI = '12345678-1234-1234-1234-123456789012';
    // $prod = $make->tagprod($std);

    // $tag = new \stdClass();
    // $tag->item = 1;
    // $tag->infAdProd = 'DE POLIESTER 100%';
    // $make->taginfAdProd($tag);

    // // Imposto
    // $std = new \stdClass();
    // $std->item = 1; // item da NFe
    // $std->vTotTrib = 25.00;
    // $make->tagimposto($std);

    // $std = new \stdClass();
    // $std->item = 1; // item da NFe
    // $std->orig = 0;
    // $std->CSOSN = '102';
    // $std->pCredSN = 0.00;
    // $std->vCredICMSSN = 0.00;
    // $std->modBCST = null;
    // $std->pMVAST = null;
    // $std->pRedBCST = null;
    // $std->vBCST = null;
    // $std->pICMSST = null;
    // $std->vICMSST = null;
    // $std->vBCFCPST = null; // incluso no layout 4.00
    // $std->pFCPST = null; // incluso no layout 4.00
    // $std->vFCPST = null; // incluso no layout 4.00
    // $std->vBCSTRet = null;
    // $std->pST = null;
    // $std->vICMSSTRet = null;
    // $std->vBCFCPSTRet = null; // incluso no layout 4.00
    // $std->pFCPSTRet = null; // incluso no layout 4.00
    // $std->vFCPSTRet = null; // incluso no layout 4.00
    // $std->modBC = null;
    // $std->vBC = null;
    // $std->pRedBC = null;
    // $std->pICMS = null;
    // $std->vICMS = null;
    // $std->pRedBCEfet = null;
    // $std->vBCEfet = null;
    // $std->pICMSEfet = null;
    // $std->vICMSEfet = null;
    // $std->vICMSSubstituto = null;
    // $make->tagICMSSN($std);

    // // PIS
    // $std = new \stdClass();
    // $std->item = 1; // item da NFe
    // $std->CST = '99';
    // // $std->vBC = 1200;
    // // $std->pPIS = 0;
    // $std->vPIS = 0.00;
    // $std->qBCProd = 0;
    // $std->vAliqProd = 0;
    // $pis = $make->tagPIS($std);

    // // COFINS
    // $std = new \stdClass();
    // $std->item = 1; // item da NFe
    // $std->CST = '99';
    // $std->vBC = null;
    // $std->pCOFINS = null;
    // $std->vCOFINS = 0.00;
    // $std->qBCProd = 0;
    // $std->vAliqProd = 0;
    // $make->tagCOFINS($std);

    // // icmstot OBRIGATÓRIA
    // $std = new \stdClass();
    // // $std->vBC = 100;
    // // $std->vICMS = 0;
    // // $std->vICMSDeson = 0;
    // // $std->vFCPUFDest = 0;
    // // $std->vICMSUFDest = 0;
    // // $std->vICMSUFRemet = 0;
    // // $std->vFCP = 0;
    // // $std->vBCST = 0;
    // // $std->vST = 0;
    // // $std->vFCPST = 0;
    // // $std->vFCPSTRet = 0.23;
    // // $std->vProd = 2000;
    // // $std->vFrete = 100;
    // // $std->vSeg = null;
    // // $std->vDesc = null;
    // // $std->vII = 12;
    // // $std->vIPI = 23;
    // // $std->vIPIDevol = 9;
    // // $std->vPIS = 6;
    // // $std->vCOFINS = 25;
    // // $std->vOutro = null;
    // // $std->vNF = 2345.83;
    // // $std->vTotTrib = 798.12;
    // $icmstot = $make->tagicmstot($std);

    // // transp OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->modFrete = 0;
    // $transp = $make->tagtransp($std);

    // // pag OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->vTroco = 0;
    // $pag = $make->tagpag($std);

    // // detPag OBRIGATÓRIA
    // $std = new \stdClass();
    // $std->indPag = 1;
    // $std->tPag = '01';
    // $std->vPag = 100.00;
    // $detpag = $make->tagdetpag($std);

    // // infadic
    // $std = new \stdClass();
    // $std->infAdFisco = '';
    // $std->infCpl = '';
    // $info = $make->taginfadic($std);

    // $std = new \stdClass();
    // $std->CNPJ = '99999999999999'; // CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
    // $std->xContato = 'Fulano de Tal'; // Nome da pessoa a ser contatada
    // $std->email = 'fulano@soft.com.br'; // E-mail da pessoa jurídica a ser contatada
    // $std->fone = '1155551122'; // Telefone da pessoa jurídica/física a ser contatada
    // // $std->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
    // // $std->idCSRT = '01'; //Identificador do CSRT
    // $make->taginfRespTec($std);

    // $make->monta();
    // $xml = $make->getXML();

    // $xml = $tools->signNFe($xml);

    // // $tools->sefazDownload($make->getChave());

    // header('Content-Type: application/xml; charset=utf-8');
    // echo $xml;
    // } catch (\Exception $e) {
    // echo $e->getMessage();
    // }
    // }
}
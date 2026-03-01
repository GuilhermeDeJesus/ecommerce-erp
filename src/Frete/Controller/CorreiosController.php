<?php
namespace Store\Frete\Controller;

use Krypitonite\Controller\AbstractController;
use Krypitonite\Util\SoapUtil;
use Krypitonite\Http\Request;
use Krypitonite\Util\ValidateUtil;

class CorreiosController extends AbstractController
{

    private $_servicos = [
        // '04014' => 'SEDEX',
        // '04510' => 'PAC'
        '03050' => 'SEDEX',
        '03085' => 'PAC'
    ];

    private $service = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';

    private $_DOC_PDF_SERVICE = 'http://www.correios.com.br/a-a-z/pdf/calculador-remoto-de-precos-e-prazos/manual-de-implementacao-do-calculo-remoto-de-precos-e-prazos';

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function header_log($data)
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $line = $caller['line'];
        $file = array_pop(explode('/', $caller['file']));
        header('log_' . $file . '_' . $caller['line'] . ': ' . json_encode($data));
    }

    // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // CALCULA O FRETE POR PRODUTO
    // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function calcularPrecoPrazoAction()
    {
        $produto = $this->dao('Produto', 'Produto')->select([
            '*'
        ], [
            'id',
            '=',
            $this->post('produto')
        ]);

        // CEP DESTINO
        $cepDestino = str_replace('-', '', $this->post('cep_destino'));
        $cepDestino = str_replace('.', '', $cepDestino);

        // CEP ORIGEM
        $cepOrigem = str_replace('-', '', $this->dao('Core', 'Pessoa')->getField('cep', $this->dao('Core', 'Produto')
            ->getField('id_fornecedor', $produto[0]['id'])));
        $cepOrigem = str_replace('.', '', $cepOrigem);

        $total_peso = 0;
        $total_cm_cubico = 0;

        foreach ($produto as $row) {
            $row_peso = $row['peso_bruto'];
            $row_cm_3 = ($row['altura'] * $row['largura'] * $row['comprimento']); // CONVERT PARA Cm3 (Volume)

            $total_peso += ($row_peso / 1000); // CONVERT GRAMAS PARA KILOGRAMAS E SOMA O TOTAL
            $total_cm_cubico += $row_cm_3;
        }

        $raiz_cubica = round(pow($total_cm_cubico, 1 / 3), 2);

        $comprimento = $raiz_cubica < 16 ? 16 : $raiz_cubica;
        $altura = $raiz_cubica < 2 ? 2 : $raiz_cubica;
        $largura = $raiz_cubica < 11 ? 11 : $raiz_cubica;
        $peso = $total_peso < 0.3 ? 0.3 : $total_peso;
        // $diametro = hypot($comprimento, $largura);

        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////

        // https://cws.correios.com.br/
        // chrome-extension://oemmndcbldboiebfnladdacbdfmadadm/https://www.correios.com.br/atendimento/developers/arquivos/manual-para-integracao-correios-api

        // Calcular Prazo
        $url_prazo = 'https://api.correios.com.br/prazo/v1/nacional';
        $token = 'eyJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzA3Nzk1OTMsImlzcyI6InRva2VuLXNlcnZpY2UiLCJleHAiOjE3MzA4NjU5OTMsImp0aSI6Ijc1MzQ5N2NiLTEwYjMtNGFkMC1hY2JlLWVlNzU4NmFkZmU4ZiIsImFtYmllbnRlIjoiUFJPRFVDQU8iLCJwZmwiOiJQSiIsImlwIjoiNDUuMjM1LjU2LjEzMCwgMTkyLjE2OC4xLjEzMiIsImNhdCI6IkJ6MCIsImNvbnRyYXRvIjp7Im51bWVybyI6Ijk5MTI0ODY5MDIiLCJkciI6MTYsImFwaSI6WzI3LDM0LDM1LDQxLDc2LDc4LDg3LDU2Niw1ODYsNTg3LDYyMSw2MjNdfSwiaWQiOiJndWlsaGVybWVtYWxhayIsImNucGoiOiIyMDc0NzkwNzAwMDEyNiJ9.tE0y8jT6itVkhY78orloQzequ7dNDCDclZAoPT0nnDNykaWLxlkg3rxX7O4KVKiyQvmYpEb5-FsR81LpBl07U6OdB5w3zt8xcmH5Tmm5yCk5nZBCK_KQZZ550IKek6PydTiiOh2UHwH2nhHpkmY9_0-2qsMmNSHDZ1GTebOga4--j4p7SfP2kXHdViCa4o5REmdkPFtMzczoi003QaMKUyCfySSJaKcuZHWOhO9kojbZ_D_BUKK6jdYY_YNMDPQiaM35Y2wA5xFq1ewi2G7fbfmmS51eCKLOxwjZVWgArSQHCCMbNH9kJ1I2G3xD37-s727zJOrJ8upPt0WZs7lyWA';

        $data_prazo = [
            "idLote" => "1",
            "parametrosPrazo" => [
                [
                    "cepDestino" => $cepDestino,
                    "cepOrigem" => $cepOrigem,
                    "coProduto" => "04162",
                    "nuRequisicao" => "1",
                    "dtEvento" => date('d/m/Y')
                ]
            ]
        ];

        $options = [
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token",
                    'ignore_errors' => true
                ],
                'method' => 'POST',
                'content' => json_encode($data_prazo)
            ]
        ];

        $context_prazo = stream_context_create($options);
        $prazo = json_decode(file_get_contents($url_prazo, false, $context_prazo));

        // Calcular Preço
        $data_preco = [
            "idLote" => "1",
            "parametrosProduto" => [
                [
                    "coProduto" => "04162",
                    "nuRequisicao" => "1",
                    "cepOrigem" => $cepOrigem,
                    "psObjeto" => $peso,
                    "tpObjeto" => "2",
                    "comprimento" => $comprimento,
                    "largura" => $largura,
                    "altura" => $altura,
                    "servicosAdicionais" => [
                        [
                            "coServAdicional" => "019"
                        ]
                        // [
                        // "coServAdicional" => "001"
                        // ]
                    ],
                    "vlDeclarado" => "100",
                    "dtEvento" => date('d/m/Y'),
                    "cepDestino" => $cepDestino
                ]
            ]
        ];

        $jsonDataPreco = json_encode($data_preco);
        $options_preco = [
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => $jsonDataPreco
            ]
        ];

        $context_preco = stream_context_create($options_preco);
        $preco = json_decode(file_get_contents('https://api.correios.com.br/preco/v1/nacional', false, $context_preco));

        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////

        $_resultado = [];
        if (isset($prazo[0]->prazoEntrega) && isset($preco[0]->pcBaseGeral)) {
            $_valor = 'R$ ' . $preco[0]->pcBaseGeral;
            if (floatval($preco[0]->pcBaseGeral) >= VALOR_MINIMO_PARA_FRETE_GRATIS) {
                $_valor = 'Oba! Frete Grátis';
            }

            $_resultado[] = [
                'entrega_pac' => '<b>Pac</b>',
                'prazo_pac' => $prazo[0]->prazoEntrega . ' dias úteis após a postagem',
                'frete_pac' => 'Pac - ' . $_valor
            ];
        } else {
            $_resultado[] = [
                'entrega_pac' => '',
                'prazo_pac' => '',
                'frete_pac' => ''
            ];
        }

        if (count($_resultado) != 0) {
            echo json_encode($_resultado);
        }
    }

    public function minhasEtiquetasAction()
    {
        $this->renderView('minhas_etiquetas');
    }

    public function rastreiarPedidoAction()
    {
        $codigo = $_POST['codigo'];
        header("location: https://correiosrastrear.com/?tracking_field=" . $codigo);
    }
}
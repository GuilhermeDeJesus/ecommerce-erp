<?php
namespace Krypitonite\Util;

require_once 'krypitonite/src/Log/Log.php';

class CorreiosUtil
{

    public $_servicos = [
        '03050' => 'SEDEX À VISTA',
        '03085' => 'PAC À VISTA'
    ];

    public $service = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';

    // Para ter acesso a este serviço, o cliente deverá:

    // Solicitar junto à área comercial da ECT a disponibilidade do serviço juntamente com o certificado de segurança emitido pela autoridade responsável;
    // Receber, da área comercial, a autorização para execução do aplicativo, juntamente com uma identificação de usuário e senha de conexão;
    // Contato: sro@correios.com.br

    // Em desenvolvimento
    // MANUAL
    // https://www.correios.com.br/enviar-e-receber/precisa-de-ajuda/manual_rastreamentoobjetosws.pdf
    public function etapasDaPostagem($rastreio)
    {
        // http://sooho.com.br/resources/Manual_RastreamentoObjetosWS.pdf
        // @var string - URL dos correios para obter dados
        $__wsdl = "http://webservice.correios.com.br/service/rastro/Rastro.wsdl";

        // @var array - a ser usado com parametro para 1 objeto
        $_buscaEventos = array(
            'usuario' => 'ECT',
            'senha' => 'SRO',
            'tipo' => 'L',
            'resultado' => 'U',
            'lingua' => '101'
        );

        $_buscaEventos['objetos'] = $rastreio;

        // criando objeto soap a partir da URL
        $client = SoapUtil::execute($__wsdl);
        $r = $client->buscaEventosLista($_buscaEventos);

        return $r->return->objeto;
    }

    public function capturarInformacosParaCalculo($cepOrigem, $cepDestino, $infosProduto = array())
    {
        $total_peso = 0;
        $total_cm_cubico = 0;

        foreach ($infosProduto as $row) {
            $row_peso = $row['peso_bruto'] * $row['quantidade'];
            $row_cm_3 = ($row['altura'] * $row['largura'] * $row['comprimento']) * $row['quantidade']; // CONVERT PARA Cm3 (Volume)

            $total_peso += ($row_peso / 1000); // CONVERT GRAMAS PARA KILOGRAMAS E SOMA O TOTAL
            $total_cm_cubico += $row_cm_3;
        }

        $raiz_cubica = round(pow($total_cm_cubico, 1 / 3), 2);

        $comprimento = $raiz_cubica < 16 ? 16 : $raiz_cubica;
        $altura = $raiz_cubica < 2 ? 2 : $raiz_cubica;
        $largura = $raiz_cubica < 11 ? 11 : $raiz_cubica;
        $peso = $total_peso < 0.3 ? 0.3 : $total_peso;
        $diametro = hypot($comprimento, $largura);

        return [
            "CepOrigem" => $cepOrigem,
            "CepDestino" => $cepDestino,
            'Peso' => $peso,
            'Comprimento' => $comprimento,
            'Altura' => $altura,
            'Largura' => $largura,
            'Diametro' => $diametro
        ];
    }

    public function calcularPrecoPrazo($cepOrigem, $cepDestino, $returnValor = FALSE, $infosProduto = array(), $modalidadeEnvio = NULL)
    {
        $total_peso = 0;
        $total_cm_cubico = 0;

        foreach ($infosProduto as $row) {
            $row_peso = $row['peso_bruto'] * $row['quantidade'];
            $row_cm_3 = ($row['altura'] * $row['largura'] * $row['comprimento']) * $row['quantidade']; // CONVERT PARA Cm3 (Volume)

            $total_peso += ($row_peso / 1000); // CONVERT GRAMAS PARA KILOGRAMAS E SOMA O TOTAL
            $total_cm_cubico += $row_cm_3;
        }

        $raiz_cubica = round(pow($total_cm_cubico, 1 / 3), 2);

        $comprimento = $raiz_cubica < 16 ? 16 : $raiz_cubica;
        $altura = $raiz_cubica < 2 ? 2 : $raiz_cubica;
        $largura = $raiz_cubica < 11 ? 11 : $raiz_cubica;
        $peso = $total_peso < 0.3 ? 0.3 : $total_peso;
        // $diametro = hypot($comprimento, $largura);

        // PAC
        // $modalidadePadrao = "03085";
        // if ($modalidadeEnvio != NULL) {
        // $modalidadePadrao = $modalidadeEnvio;
        // }

        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////

        // https://cws.correios.com.br/
        // chrome-extension://oemmndcbldboiebfnladdacbdfmadadm/https://www.correios.com.br/atendimento/developers/arquivos/manual-para-integracao-correios-api

        $token = 'eyJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzA3Nzk1OTMsImlzcyI6InRva2VuLXNlcnZpY2UiLCJleHAiOjE3MzA4NjU5OTMsImp0aSI6Ijc1MzQ5N2NiLTEwYjMtNGFkMC1hY2JlLWVlNzU4NmFkZmU4ZiIsImFtYmllbnRlIjoiUFJPRFVDQU8iLCJwZmwiOiJQSiIsImlwIjoiNDUuMjM1LjU2LjEzMCwgMTkyLjE2OC4xLjEzMiIsImNhdCI6IkJ6MCIsImNvbnRyYXRvIjp7Im51bWVybyI6Ijk5MTI0ODY5MDIiLCJkciI6MTYsImFwaSI6WzI3LDM0LDM1LDQxLDc2LDc4LDg3LDU2Niw1ODYsNTg3LDYyMSw2MjNdfSwiaWQiOiJndWlsaGVybWVtYWxhayIsImNucGoiOiIyMDc0NzkwNzAwMDEyNiJ9.tE0y8jT6itVkhY78orloQzequ7dNDCDclZAoPT0nnDNykaWLxlkg3rxX7O4KVKiyQvmYpEb5-FsR81LpBl07U6OdB5w3zt8xcmH5Tmm5yCk5nZBCK_KQZZ550IKek6PydTiiOh2UHwH2nhHpkmY9_0-2qsMmNSHDZ1GTebOga4--j4p7SfP2kXHdViCa4o5REmdkPFtMzczoi003QaMKUyCfySSJaKcuZHWOhO9kojbZ_D_BUKK6jdYY_YNMDPQiaM35Y2wA5xFq1ewi2G7fbfmmS51eCKLOxwjZVWgArSQHCCMbNH9kJ1I2G3xD37-s727zJOrJ8upPt0WZs7lyWA';

        // Calcular Prazo
        $prazo = json_decode(file_get_contents('https://api.correios.com.br/prazo/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosPrazo" => [
                        [
                            "cepDestino" => $cepDestino,
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "coProduto" => "04162",
                            "nuRequisicao" => "1",
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // Calcular Preço
        $preco = json_decode(file_get_contents('https://api.correios.com.br/preco/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosProduto" => [
                        [
                            "coProduto" => "04162",
                            "nuRequisicao" => "1",
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "cepDestino" => $cepDestino,
                            "psObjeto" => 1,
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
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////

        $_result = [];
        if (isset($prazo[0]->prazoEntrega) && isset($preco[0]->pcBaseGeral)) {
            $_result[] = [
                'entrega' => '<b>Pac</b>',
                'prazo' => 'Pac - ' . $prazo[0]->prazoEntrega . '  dias úteis após a postagem',
                'frete' => floatval($preco[0]->pcBaseGeral)
            ];
        }

        if (count($_result) != 0 && $returnValor == FALSE) {
            return $_result[0];
        } else if ($returnValor == TRUE) {
            return $_result[0]['frete'];
        }
    }

    public function getValorPrazoPAC_e_SEDEX($cepOrigem, $cepDestino, $returnValor = FALSE, $infosProduto = array())
    {
        $total_peso = 0;
        $total_cm_cubico = 0;

        foreach ($infosProduto as $row) {
            $row_peso = $row['peso_bruto'] * $row['quantidade'];
            $row_cm_3 = ($row['altura'] * $row['largura'] * $row['comprimento']) * $row['quantidade']; // CONVERT PARA Cm3 (Volume)

            $total_peso += ($row_peso / 1000); // CONVERT GRAMAS PARA KILOGRAMAS E SOMA O TOTAL
            $total_cm_cubico += $row_cm_3;
        }

        $raiz_cubica = round(pow($total_cm_cubico, 1 / 3), 2);

        $comprimento = $raiz_cubica < 16 ? 16 : $raiz_cubica;
        $altura = $raiz_cubica < 2 ? 2 : $raiz_cubica;
        $largura = $raiz_cubica < 11 ? 11 : $raiz_cubica;
        $peso = $total_peso < 0.3 ? 0.3 : $total_peso;
        // $diametro = hypot($comprimento, $largura);

        $token = 'eyJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzA3Nzk1OTMsImlzcyI6InRva2VuLXNlcnZpY2UiLCJleHAiOjE3MzA4NjU5OTMsImp0aSI6Ijc1MzQ5N2NiLTEwYjMtNGFkMC1hY2JlLWVlNzU4NmFkZmU4ZiIsImFtYmllbnRlIjoiUFJPRFVDQU8iLCJwZmwiOiJQSiIsImlwIjoiNDUuMjM1LjU2LjEzMCwgMTkyLjE2OC4xLjEzMiIsImNhdCI6IkJ6MCIsImNvbnRyYXRvIjp7Im51bWVybyI6Ijk5MTI0ODY5MDIiLCJkciI6MTYsImFwaSI6WzI3LDM0LDM1LDQxLDc2LDc4LDg3LDU2Niw1ODYsNTg3LDYyMSw2MjNdfSwiaWQiOiJndWlsaGVybWVtYWxhayIsImNucGoiOiIyMDc0NzkwNzAwMDEyNiJ9.tE0y8jT6itVkhY78orloQzequ7dNDCDclZAoPT0nnDNykaWLxlkg3rxX7O4KVKiyQvmYpEb5-FsR81LpBl07U6OdB5w3zt8xcmH5Tmm5yCk5nZBCK_KQZZ550IKek6PydTiiOh2UHwH2nhHpkmY9_0-2qsMmNSHDZ1GTebOga4--j4p7SfP2kXHdViCa4o5REmdkPFtMzczoi003QaMKUyCfySSJaKcuZHWOhO9kojbZ_D_BUKK6jdYY_YNMDPQiaM35Y2wA5xFq1ewi2G7fbfmmS51eCKLOxwjZVWgArSQHCCMbNH9kJ1I2G3xD37-s727zJOrJ8upPt0WZs7lyWA';

        // Calcular Prazo
        $prazo_PAC = json_decode(file_get_contents('https://api.correios.com.br/prazo/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosPrazo" => [
                        [
                            "cepDestino" => $cepDestino,
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "coProduto" => "04162",
                            "nuRequisicao" => "1",
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // Calcular Preço
        $preco_PAC = json_decode(file_get_contents('https://api.correios.com.br/preco/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosProduto" => [
                        [
                            "coProduto" => "04162",
                            "nuRequisicao" => "1",
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "cepDestino" => $cepDestino,
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
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // Calcular Prazo
        $prazo_SEDEX = json_decode(file_get_contents('https://api.correios.com.br/prazo/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosPrazo" => [
                        [
                            "cepDestino" => $cepDestino,
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "coProduto" => "04014",
                            "nuRequisicao" => "1",
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // Calcular Preço
        $preco_SEDEX = json_decode(file_get_contents('https://api.correios.com.br/preco/v1/nacional', false, stream_context_create([
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Accept: application/json",
                    "Authorization: Bearer $token"
                ],
                'method' => 'POST',
                'content' => json_encode([
                    "idLote" => "1",
                    "parametrosProduto" => [
                        [
                            "coProduto" => "04790",
                            "nuRequisicao" => "1",
                            "cepOrigem" => str_replace('.', '', $cepOrigem),
                            "cepDestino" => $cepDestino,
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
                            "dtEvento" => date('d/m/Y')
                        ]
                    ]
                ])
            ]
        ])));

        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////
        // ///////////////////////////////////////////////////////////////////////////////////////////

        $_result = [];
        if (isset($prazo_PAC[0]->prazoEntrega) && isset($preco_PAC[0]->pcBaseGeral)) {
            $_result['PAC'] = [
                'prazo' => 'Pac - ' . $prazo_PAC[0]->prazoEntrega . '  dias úteis após a postagem',
                'frete' => floatval($preco_PAC[0]->pcBaseGeral)
            ];
        }

        if (isset($prazo_SEDEX[0]->prazoEntrega) && isset($preco_SEDEX[0]->pcBaseGeral)) {
            $_result['SEDEX'] = [
                'prazo' => 'Sedex - ' . $prazo_SEDEX[0]->prazoEntrega . '  dias úteis após a postagem',
                'frete' => floatval($preco_SEDEX[0]->pcBaseGeral)
            ];
        }

        return $_result;
    }
}
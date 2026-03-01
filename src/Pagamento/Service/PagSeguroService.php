<?php
namespace Store\Pagamento\Service;

use Krypitonite\Util\DateUtil;

class PagSeguroService
{

    public function __construct()
    {}

    public function iniciaPagamentoAction()
    {
        $data['token'] = '9B222B1D484D47CEBC86B591BC93287D';

        $emailPagseguro = "guilherme.malak@gmail.com";

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/sessions';

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);
        $idSessao = $xml->id;
        echo $idSessao;
        exit();
        // return $codigoRedirecionamento;
    }

    public function efetuaPagamentoCartaoAction()
    {
        $_endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $this->post('endereco')
        ]);

        $_endereco = $_endereco[0];
        $_cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_endereco['id_cliente']
        ]);

        $_cliente = $_cliente[0];

        $data['token'] = '9B222B1D484D47CEBC86B591BC93287D';
        $data['paymentMode'] = 'default';
        $data['senderHash'] = $this->post('hashPagSeguro'); // Identificador do usuário
        $data['creditCardToken'] = $this->post('tokenPagamentoCartao'); // gerado via javascript
        $data['paymentMethod'] = 'creditCard';
        $data['receiverEmail'] = 'guilherme.malak@gmail.com';
        $data['senderName'] = $this->post('name'); // nome do cliente completo

        $data['senderAreaCode'] = '61'; // DDD
        $data['senderPhone'] = '998493256'; // Telefone do cliente
        $data['senderEmail'] = 'contato@vocesemprebela.com.br';
        $data['senderCPF'] = $_cliente['cpf'];
        $data['installmentQuantity'] = '1';
        // $data['noInterestInstallmentQuantity'] = '1';
        $data['installmentValue'] = '100.15'; // valor da parcela
        $data['creditCardHolderName'] = $this->post('name'); // nome do titular
        $data['creditCardHolderCPF'] = $_cliente['cpf'];
        $data['creditCardHolderBirthDate'] = DateUtil::getDateDMY($_cliente['data_nascimento']);
        $data['creditCardHolderAreaCode'] = '61';

        $data['creditCardHolderPhone'] = '998493256';

        $data['billingAddressStreet'] = $_endereco['numero'];

        $data['billingAddressNumber'] = $_endereco['numero'];

        $data['billingAddressDistrict'] = $_endereco['bairro'];
        $data['billingAddressPostalCode'] = $_endereco['cep'];

        $data['billingAddressCity'] = $_endereco['cidade'];

        $data['billingAddressState'] = $_endereco['uf'];

        $data['billingAddressCountry'] = 'Brasil';
        $data['currency'] = 'BRL';

        $data['itemId1'] = '01';
        $data['itemQuantity1'] = '1';
        $data['itemDescription1'] = 'Batom Vult';
        $data['reference'] = $_cliente['id']; // referencia qualquer do produto
        $data['shippingAddressRequired'] = 'false';
        $data['itemAmount1'] = '100.15';

        // $_SERVER['REMOTE_ADDR']
        $emailPagseguro = 'guilherme.malak@gmail.com';

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; // URL de teste

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);

        $retornoCartao = [
            'code' => (array) $xml->code[0],
            'date' => (array) $xml->date[0],
            'bandeira' => $this->post('bandeira_cartao')
        ];

        echo json_encode($retornoCartao);
    }

    public function efetuaPagamentoBoleto($endereco = NULL, $hash = NULL, $itens = NULL)
    {
        $_endereco = $this->dao('Core', 'Endereco')->select([
            '*'
        ], [
            'id',
            '=',
            $endereco
        ]);

        $_endereco = $_endereco[0];
        $_cliente = $this->dao('Core', 'Cliente')->select([
            '*'
        ], [
            'id',
            '=',
            $_endereco['id_cliente']
        ]);

        $_cliente = $_cliente[0];

        $data['token'] = '9B222B1D484D47CEBC86B591BC93287D';
        $data['paymentMode'] = 'default';
        $data['senderHash'] = $hash;
        $data['paymentMethod'] = 'boleto';
        $data['receiverEmail'] = 'guilherme.malak@gmail.com';
        $data['senderName'] = $_cliente['nome'];
        $data['senderAreaCode'] = '61';
        $data['senderPhone'] = '98493256';
        $data['senderEmail'] = 'contato@vocesemprebela.com.br';
        $data['senderCPF'] = $_cliente['cpf'];

        $data['currency'] = 'BRL';

        // Itens
        $data['itemId1'] = '01';
        $data['itemQuantity1'] = '1';
        $data['itemDescription1'] = 'Batom Vult';
        $data['reference'] = $_cliente['id']; // referencia qualquer do produto
        $data['shippingAddressRequired'] = 'false';
        $data['itemAmount1'] = '12.99';

        // $_SERVER['REMOTE_ADDR']
        $emailPagseguro = "guilherme.malak@gmail.com";

        $data = http_build_query($data);
        $url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; // URL de teste

        $curl = curl_init();

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
        );

        curl_setopt($curl, CURLOPT_URL, $url . "?email=" . $emailPagseguro);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $xml = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($xml);

        $boletoLink = (array) $xml->paymentLink[0];

        $retornoBoleto = [
            'code' => (array) $xml->code[0],
            'date' => (array) $xml->date[0],
            'paymentLink' => $boletoLink
        ];

        echo json_encode($retornoBoleto);
    }
}
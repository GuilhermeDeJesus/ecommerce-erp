<?php
session_start();
include("includes/config.php");

require_once("composer/vendor/mercadopago/sdk/lib/mercadopago.php");
$mp = new MP($accessToken); //AQUI USAR O ACCESS TOKEN (https://www.mercadopago.com/mlb/account/credentials) 


//////// ABAIXO OS DADOS ESTÃO PREENCHIDOS JÁ
/////// FAÇA UMA CONSULTA EM BANCO DE DADOS PARA PREENCHER AUTOMATICAMENTE
////// OU CRIE ALGUM FORMULÁRIO PARA A PESSOA PREENCHER OS CAMPOS
$codigo = $_SESSION['usuario'];
$valor = 10.00;
$descricao = "Boleto Registrado na CIP conforme Febraban";
$URL_retorno = "http://www.minhaloja.com.br/webhooks";
$email_comprador = "comprador@teste.com";
$nome = "João";
$sobrenome = "Silva";
$cpf = "19119119100";
$ddd = "11";
$telefone = "3290-1100";


$endereco = "Rua Teste da Silva";
$numero = "123";
$bairro = "Jardins";
$cidade = "São Paulo";
$cep = "014206-000";
$uf = "SP";


$payment_preference = array(
    //"date_of_expiration"=> "2019-08-20T23:59:59.000-04:00", //CAMPO OPCIONAL ONDE SE DEFINE O PRAZO DESEJADO PARA O PAGAMENTO DO BOLETO (DATA VENCIMENTO), APÓS ESTE PRAZO O PAGAMENTO NÃO SERÁ MAIS ACEITO, O PRAZO NÃO PODE SER SUPERIOR A 29 DIAS A PARTIR DA DATA DE CRIAÇÃO DO PAGAMENTO, NÃO INFORMANDO ESTE CAMPO VALE O PADRÃO DE 3 DIAS
    "transaction_amount"=> $valor, //VALOR TOTAL A SER PAGO PELO COMPRADOR
    "external_reference"=> $codigo, //NUMERO DO PEDIDO DE SEU SITE PARA FUTURA CONCILIAÇÃO FINANCEIRA
    "description"=> $descricao, //DESCRIÇÃO DO CARRINHO OU ITEM VENDIDO
    "notification_url"=> $URL_retorno, //ENDEREÇO EM SEU SISTEMA POR ONDE DESEJA RECEBER AS NOTIFICAÇÕES DE STATUS: httpsSEJA RECEBER AS NOTIFICAÇÕES DE STATUS: https://www.mercadopago.com.br/developers/pt/guides/notifications/webhooks/://www.mercadopago.com.br/developers/pt/guides/notifications/webhooks/
    "payment_method_id"=> "bolbradesco", //MEIO DE PAGAMENTO ESCOLHIDO
    "payer"=> array( //DADOS ESSENCIAIS PARA REGISTRO DO BOLETO
        "email"=> $email_comprador, //EMAIL DO COMPRADOR
        "first_name"=> $nome, //PRIMEIRO NOME DO COMPRADOR
        "last_name"=> $sobrenome, //SOBRENOME DO COMPRADOR, OPCIONAL SE FOR PESSOA JURIDICA
        "identification"=> array( //DADOS DE IDENTIFICAÇÃO DO COMPRADOR
                "type"=>"CPF", //TIPO DE DOCUMENTO, CPF OU CNPJ CASO BRASIL
                "number"=>$cpf //NUMERAÇÃO DO DOCUMENTO INFORMADO
        ),
        "address"=>  array( //ENDEREÇO DO COMPRADOR
                "zip_code"=> $cep, //CEP DO COMPRADOR
                "street_name"=> $endereco, //RUA DO COMPRADOR
                "street_number"=> $numero, //NÚMERO DO COMPRADOR
                "neighborhood"=> $bairro, //BAIRRO DO COMPRADOR
                "city"=> $cidade, //CIDADE DO COMPRADOR
                "federal_unit"=> $uf //UNIDADE FEDERATIVA RESUMIDA EM SIGLA DO COMPRADOR
        )
    ),
    "additional_info"=>  array( //DADOS ESSENCIAIS PARA ANÁLISE ANTI-FRAUDE
        "items"=> array(array( //PARA CADA ITEM QUE ESTÁ SENDO VENDIDO É CRIADO UM ARRAY DENTRO DESTE ARRAY PAI COM AS INFORMAÇÕES DESCRITAS ABAIXO
            
                "id"=> "1234", //CÓDIGO IDENTIFICADOR DO SEU PRODUTO
                "title"=> "Aqui coloca os itens do carrinho", //TÍTULO DO ITEM
                "description"=> "Produto Teste novo", //DESCRIÇÃO DO ITEM
                "picture_url"=> "https://google.com.br/images?image.jpg", //IMAGEM DO ITEM
                "category_id"=> "others", //CATEGORIA A QUAL O ITEM PERTENCE, LISTAGEM DISPONÍVEL EM: https://api.mercadopago.com/item_categories
                "quantity"=> 1, //QUANTIDADE A QUAL ESTA SENDO COMPRADO ESTE ITEM
                "unit_price"=> 10.00 //VALOR UNITARIO DO ITEM INDEPENDENTE DO QUANTO ESTÁ SENDO COBRADO
            )
        ),
        "payer"=>  array( //INFORMAÇÕES PESSOAIS DO COMPRADOR
            "first_name"=> $nome, //NOME DO COMPRADOR
            "last_name"=> $sobrenome, //SOBRENOME DO COMPRADOR
            "registration_date"=> "2014-06-28T16:53:03.176-04:00", //DATA EM QUE O COMPRADOR FOI CADASTRADO COMO CLIENTE
            "phone"=>  array( //CONTATO TELEFÔNICO DO COMPRADOR
                "area_code"=> $ddd, //DDD DO TELEFONE DO COMPRADOR
                "number"=> $telefone //NÚMERO DO TELEFONE DO COMPRADOR
            ),
            "address"=>  array( //ENDEREÇO DO COMPRADOR
                "zip_code"=> $cep, //CEP DO COMPRADOR
                "street_name"=> $endereco, //NOME DA RUA DO COMPRADOR
                "street_number"=> $numero //NUMERO DA CASA DO COMPRADOR
            )
        ),
        "shipments"=>  array( //INFORMAÇÕES DO LOCAL ONDE O ITEM SERÁ ENTREGUE
            "receiver_address"=>  array(
                "zip_code"=> $cep, //CEP DA ENTREGA
                "street_name"=> $endereco, //RUA DA ENTREGA
                "street_number"=> $numero, //NUMERO DA ENTREGA
                "floor"=> "1", //ANDAR DA ENTREGA
                "apartment"=> "14" //APARTAMENTO DA ENTREGA
            )
        )
    )
  );

  
$response_payment = $mp->post("/v1/payments/", $payment_preference);

//REMOVA O COMENTÁRIO DAS 3 LINHAS ABAIXO PARA VISUALIZAR O RETORNO COMPLETO DE CADA CAMPO DO ARRAY
/*echo "<pre>";
print_r($response_payment);
echo "</pre>";
*/

echo "<div style='width: 700px; height: 800px; margin: 0 auto; text-align: center;'>";
echo "<a href=" . $response_payment["response"]["transaction_details"]["external_resource_url"] . ">Clique aqui para baixar o boleto</a><br/><br/>";
echo "<iframe style='width: 700px; height: 800px;' src='". $response_payment["response"]["transaction_details"]["external_resource_url"] . "' >";
echo "</div>";
?>


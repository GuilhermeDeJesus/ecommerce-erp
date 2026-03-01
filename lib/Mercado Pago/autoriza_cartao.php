<?php include("includes/head.php"); ?>
<?php include("includes/config.php"); ?>


<div class="container">
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        require_once ("composer/vendor/mercadopago/sdk/lib/mercadopago.php");
        $mp = new MP($accessToken); // AQUI USAR O ACCESS TOKEN (https://www.mercadopago.com/mlb/account/credentials)

        // ////////////// MUITO IMPORTANTE: O MODO PRODUÇÃO SÓ FUNCIONA COM SSL ATIVO NO SITE (HTTPS)/////////////////////

        // Produto
        $id = $_POST['id'];
        $produto = $_POST['produto'];
        $qtde = $_POST['qtde'];
        $total = $_POST['total'];
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];

        $id = rand(1, 99999);

        $item = array(
            "id" => $id,
            "title" => $produto,
            "quantity" => $qtde,
            "unit_price" => $total
        );

        // ARRAY PAGTO
        if (isset($_POST['token'])) {

            $payment_preference = array(
                "token" => $_POST['token'],
                "installments" => (int) $_POST['parcelas_quantidade'], // QUANTIDADE DE PARCELAS ESCOLHIDAS PELO COMPRADOR.
                "transaction_amount" => round((float) $total, 2), // VALOR TOTAL A SER PAGO PELO COMPRADOR.
                                                                   // "coupon_amount" => valor do desconto,
                "external_reference" => $id, // NUMERO DO PEDIDO DE SEU SITE PARA FUTURA CONCILIAÇÃO FINANCEIRA.
                "binary_mode" => false, // SE DEFINIDO true DESLIGA PROCESSO DE ANÁLISE MANUAL DE RISCO, PODE REDUZIR APROVAÇÃO DAS VENDAS SE NÃO CALIBRADO PREVIAMENTE.
                "description" => "MINHA LOJA - PEDIDO-" . $id, // DESCRIÇÃO DO CARRINHO OU ITEM VENDIDO.
                "payment_method_id" => $_POST['paymentMethodId'], // MEIO DE PAGAMENTO ESCOLHIDO.
                "statement_descriptor" => "NOME LOJA", // ESTE CAMPO IRÁ NA APARECER NA FATURA DO CARTÃO DO CLIENTE, LIMITADO A 10 CARACTERES.
                "notification_url" => "http://www.SEUSITE.com.br/retorno.php", // ENDEREÇO EM SEU SISTEMA POR ONDE DESEJA RECEBER AS NOTIFICAÇÕES DE STATUS: https://www.mercadopago.com.br/developers/pt/solutions/payments/custom-checkout/webhooks/

                "payer" => array(
                    "email" => $_POST['email'] // E-MAIL DO COMPRADOR
                ),

                "additional_info" => array( // DADOS ESSENCIAIS PARA ANÁLISE ANTI-FRAUDE
                    "ip_address" => $_SERVER['REMOTE_ADDR'],

                    // PARA CADA ITEM QUE ESTÁ SENDO VENDIDO É CRIADO UM ARRAY DENTRO DESTE ARRAY PAI COM AS INFORMAÇÕES DESCRITAS ABAIXO
                    "items" => array(
                        $item
                    ),

                    "payer" => array( // INFORMAÇÕES PESSOAIS DO COMPRADOR
                        "first_name" => $_POST['nome'], // NOME DO COMPRADOR
                        "last_name" => $_POST['sobrenome'], // SOBRENOME DO COMPRADOR
                                                             // "registration_date" => "2014-06-28T16:53:03.176-04:00", //DATA EM QUE O COMPRADOR FOI CADASTRADO COMO CLIENTE
                        "phone" => array( // Telefone do Comprador
                            "area_code" => $_POST['ddd'], // DDD
                            "number" => $_POST['telefone'] // NÚMERO
                        )
                    )
                    /*
                 * "shipments" => array( //INFORMAÇÕES DO LOCAL ONDE O ITEM SERÁ ENTREGUE
                 * "receiver_address" => array(
                 * "zip_code" => "", //CEP
                 * "street_name" => "", //Logradouro
                 * "street_number" => "", //Número
                 * "floor" => "", //Andar
                 * "apartment" => "" //Apto
                 * ),
                 * ),
                 */
                )
            );

            $response_payment = $mp->post("/v1/payments/", $payment_preference);

            if ($response_payment['response']['status'] == 'approved') {

                echo "<div class='mx-auto mt-4 mb-4' style='width: 580px;'>";
                echo "<div class='card'>";
                echo "<h4 class='card-header bg-success text-white'>Sucesso!</h4>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>Obrigado!</h5>";
                echo "<p>Seu pedido foi recebido com sucesso!</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='mx-auto mt-4 mb-4' style='width: 580px;'>";
                echo "<div class='card'>";
                echo "<h4 class='card-header bg-danger text-white'>";
                echo "Ooops! Não foi possível prosseguir :/<br>";
                echo "</h4>";
                echo "<div class='card-body'>";
                echo "<p>Houve uma falha: " . $response_payment['response']['status_detail'] . "</p>";
                echo "</div>";
                echo "</div>";
            }

            // IMPRESSÃO DO RETORNO DA API SOBRE A REQUISIÇÃO DE PAGAMENTO FEITA
            // REMOVA AS BARRAS DE COMENTÁRIO DAS 3 LINHAS ABAIXO PARA VISUALIZAR O ARRAY COMPLETO E TUDO QUE ESTÁ SENDO RETORNADO
            /*
             * echo "<pre>";
             * print_r($response_payment);
             * echo "</pre>";
             */
        } else {
            echo "<div class='alert alert-danger mx-auto' role='alert' style='width: 580px;'>";
            echo "Ooops! Houve uma falha. (token)";
            echo "</div>";
        }
    }
    ?>
</div>
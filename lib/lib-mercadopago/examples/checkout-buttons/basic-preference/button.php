<?php
require_once "../../../lib/mercadopago.php";

$mp = new MP("6601206125001748", "gjpKH8d5BWfuD5DiI38z6av7UeQO1igp");

$preference_data = array(
    "items" => array(
        array(
            "title" => "Compra de Créditos",
            "currency_id" => "BRL",
            "category_id" => "Category",
            "quantity" => 1,
            "unit_price" => 10.2
        )
    )
);

$preference = $mp->create_preference($preference_data);
?>

<!doctype html>
<html>
    <head>
        <title>MercadoPago SDK - Create Preference and Show Checkout Example</title>
    </head>
    <body>
       	<a href="<?=$preference["response"]["init_point"]; ?>" name="MP-Checkout" class="orange-ar-m-sq-arall">Pay</a>
        <script type="text/javascript" src="//resources.mlstatic.com/mptools/render.js"></script>
    </body>
</html>

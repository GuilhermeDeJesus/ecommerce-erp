<!doctype html>
<html>
<head>
	<title>Checkout Transparente MercadoPago</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <title>jQuery UI Tabs - Default functionality</title>
	  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	  <link rel="stylesheet" href="/resources/demos/style.css">
	  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
	<script>
		$(function() {
			$( "#tabs" ).tabs();
		});
	</script>
	
</head>
<body>
	<div id="tabs">
		<ul>
			<li><a href="#aba-1">Cartão de Crédito</a></li>
			<li><a href="#aba-2">Boleto</a></li>
		</ul>
	<div id="aba-1">
		<? include ('cartao.php');?>
	</div>
	<div id="aba-2">
		<a href="boleto.php"><button type="submit" class="btn btn-success ">Pagar com Boleto</button></a>
	</div>
</div>
</body>
</head>
</html>
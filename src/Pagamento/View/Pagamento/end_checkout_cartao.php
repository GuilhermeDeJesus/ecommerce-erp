<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=TAG_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="public/css/cred-card.css " />
<?php
$status = $data['status'];
?>
<?php if($status == 3 || $status == 2){ ?>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '<?=$_SESSION['pixel_produto'];?>');
  fbq('track', 'Purchase', {
    value: <?=$data['total'];?>,
    currency: 'BRL',
  });
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=$_SESSION['pixel_produto'];?>&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
<?php } ?>
<style type="text/css">
</style>
</head>
<body>
	<div id="credit-card">
		<main data-reactroot="">
		<section class="status">
			<div class="content">
				<div class="spinner">
					<div class="bounce1"></div>
					<div class="bounce2"></div>
					<div class="bounce3"></div>
				</div>
			</div>
		</section>
		<header>
			<img style="margin-left: 0px; padding: 5px; width: 40%; margin-bottom: 15px;"
				src="public/img/<?=NOME_LOGO;?>" title=""
    				alt="">
			<?php 
			if($status == 3){ ?>
			<h1>Obrigado pelo seu pagamento, aguarde a confirmação do seu pedido por email.</h1>
			<br> <br>
			<h1>Agradecemos a preferência e a confiança depositada. Fazemos
				sempre o nosso melhor para que nossa relação seja a mais duradoura.</h1>
			<br> <br>
			<h1>Dentro de 3 dias úteis vamos entrar em contato via E-mail para te
				encaminharmos o código de rastreamento, não se preocupe que seu
				pedido chegará no prazo estabelecido pelo nosso checkout.</h1>
			<br> <br>
			<h2 style="font-size: 12px;">Acompanhe seu pedido clicando <a href="#">Aqui</a></h2>
			<?php } ?>
			
			<?php if($status == 7){ ?>
			<span><h1>Pagamento não autorizado!</h1></span>
			<br> <br>
			<h1>Isso pode ocorrer porque sua operadora de cartão não aprovou sua compra ou você inseriu algum dado incorretamente.</h1>
			<br> <br>
			<h2 style="font-size: 12px;">Tente novamente clicando <a href="/?m=checkout&c=checkout&a=finalizar">Aqui</a></h2>
			<?php } ?>
			
			<?php if($status == 1 || $status == 2){ ?>
			<span><h1>(<?=$status;?>) - Pagamento em Análise!</h1></span>
			<br> <br>
			<h1>Estamos processando seu pagamento, em instantes você recebrá um e-mail confirmando o status do seu pedido!</h1>
			<br> <br>
			<h2 style="font-size: 12px;">Acompanhe seu pedido clicando <a href="#">Aqui</a></h2>
			<?php } ?>
			
			<br> <br>
			<br> <br>
			<h1>Atenciosamente,</h1>
			<br> <br>
			<h1>Equipe <?=NOME_LOJA;?></h1>
		</header>
		<div class="master-alert fade-in mobile" style="display: none;">
			<p></p>
		</div>
		<footer> </footer>
		<div class="master-alert fade-in desktop" style="display: none;">
			<p></p>
		</div>
		<p class="out-contact">Contacte-nos através
			<?=EMAIL_CONTATO;?></p>
		</main>
	</div>
</body>
</html>
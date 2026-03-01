<html>
<head>
<title>Obrigado pelo seu Pagamento</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords"
	content="<?=TAG_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="public/css/cred-card.css " />
<link rel="stylesheet" type="text/css" href="public/css/final_mp.css " />
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
  fbq('init', '<?=$data['_pixel']?>');
  fbq('track', 'Purchase', {
    value: <?=$data['float_valor'];?>,
    currency: 'BRL',
  });
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=$data['_pixel']?>&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
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
			<img style="margin-left: -25px; margin-bottom: 15px;"
				src="public/img/<?=NOME_LOGO;?>" width="180" height="70" title="" alt=""><span
				class="country-flag"><img width="25px" src="public/img/BR.png"></span><small
				class="reference"> Número de referência : <span><?=$data['numero_pedido'];?></span>
			</small><span><h1>Agradecemos a sua preferência!</h1></span>
		</header>
		<div class="master-alert fade-in mobile" style="display: none;">
			<p></p>
		</div>
		<section class="checkout-wrapper">
			<div class="left transparent checkout ">
				<ul>
					<li>Tipo Pagamento <small> <?=$data['tipo_pagamento'];?> </small>
					</li>
					<li>Valor <small> <?=$data['valor'];?> </small>
					</li>
					<li>Situação <small> <?=$data['status'];?> </small></li>
					<li class="total"><small>PAGAMENTO TOTAL R$ <?=$data['valor'];?></small>
					</li>
				</ul>
			</div>
		</section>
		<footer>
			<a class="btn color-default"
				href="https://<?=LINK_LOJA;?>/minha-conta" target="new"><span
				class="lock-icon"></span> VOLTAR AO SITE</a>
		</footer>
		<div class="master-alert fade-in desktop" style="display: none;">
			<p></p>
		</div>
		<p class="out-contact">Contacte-nos através
			<?=EMAIL_CONTATO;?></p>
		</main>
	</div>
</body>
</html>
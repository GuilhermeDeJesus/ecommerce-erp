<html>
<head>
<title>Obrigado</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=TAG_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="public/css/cred-card.css " />
<link rel="stylesheet" type="text/css" href="public/css/final_mp.css " />
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  <?php if($data['_pixel'] != ''){ ?>
  fbq('init', '<?=$data['_pixel'];?>', {
		em: '<?=$data['_correspondencia_fbk']['em'];?>',
		fn: '<?=$data['_correspondencia_fbk']['fn'];?>',	
		ln: '<?=$data['_correspondencia_fbk']['ln'];?>',
		country: '<?=$data['_correspondencia_fbk']['country'];?>',	
		ct: '<?=$data['_correspondencia_fbk']['ct'];?>',
		ph: '<?=$data['_correspondencia_fbk']['ph'];?>',	
		st: '<?=$data['_correspondencia_fbk']['st'];?>',	
		zp: '<?=$data['_correspondencia_fbk']['zp'];?>'
  });
  
  fbq('track', 'Purchase', {
	     value: <?=$data['_total'];?>,
	     currency: 'BRL'
  });
  <?php } ?>
</script>
  <?php if($data['_pixel'] != ''){ ?>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=$data['_pixel'];?>&ev=Purchase&noscript=1" />
</noscript>
  <?php } ?>
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
					src="public/img/<?=NOME_LOGO;?>" width="150" height="60" title=""
					alt=""><span class="country-flag"><img width="25px"
					src="public/img/BR.png"></span><small class="reference"> Número de
					referência : <span><?=$data['numero_pedido'];?></span>
				</small><span><h1>Agradecemos a sua preferência!</h1></span>
			</header>
			<div class="master-alert fade-in mobile" style="display: none;">
				<p></p>
			</div>
			<section class="checkout-wrapper">
				<div class="left transparent checkout ">
					<ul>
						<li>Tipo Pagamento <small><b> <?=$data['tipo_pagamento'];?> </b> </small>
						</li>
						<li>Valor <small class="reference"> <?=$data['valor'];?> </small>
						</li>
						<li>Situação <small class="reference"> <?=$data['status'];?> </small></li>
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
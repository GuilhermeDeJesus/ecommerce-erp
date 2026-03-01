<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=TAG_KEYWORDS;?>" />
<link rel="stylesheet" type="text/css" href="public/css/cred-card.css " />
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
  fbq('init', '<?=PIXEL_FACEBOOK?>');
  fbq('track', 'Purchase', {
    value: 0,
    currency: 'BRL',
  });
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=PIXEL_FACEBOOK?>&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
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
			<img style="margin-left: 0px; padding: 5px; margin-bottom: 15px;"
				src="public/img/<?=NOME_LOGO;?>" width="150" height="60" title=""
				alt=""><span class="country-flag"><img width="25px"
				src="public/img/BR.png"></span> </small><span><h1>Obrigado pelo seu
					pagamento, aguarde a confirmação do seu pedido por email.</h1></span>
			<br> <br>
			<h1>Agradecemos a preferência e a confiança depositada. Fazemos
				sempre o nosso melhor para que nossa relação seja a mais duradoura.</h1>
			<br> <br>
			<h1>Dentro de 3 dias úteis vamos entrar em contato via E-mail para te
				encaminharmos o código de rastreamento, não se preocupe que seu
				pedido chegará no prazo estabelecido pelo nosso checkout.</h1>
			<br> <br>
			<h2 style="font-size: 12px;">Acompanhe seu pedido em nosso site
				utilizando o seguinte usuário:</h2>
			<br> <br>
			<ul style="font-size: 12px;">
				<li>E-mail: seu e-mail</li>
				<li>Senha: eusoubela@56</li>
			</ul>
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
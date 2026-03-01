<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords"
	content="<?=TAG_KEYWORDS;?>" />
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
    value: 164.49,
    currency: 'BRL',
  });
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=<?=PIXEL_FACEBOOK?>&ev=PageView&noscript=1"
/></noscript>
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
				src="public/img/<?=NOME_LOGO;?>" width="150" height="60" title="" alt=""><span
				class="country-flag"><img width="25px" src="public/img/BR.png"></span>
			</small><span><h1>
					A sua compra foi reservada com sucesso, mas por apenas 3 dias. <br>Pague
					o boleto no tempo determinado e receba seu produto o mais rápido
					possível.
				</h1></span>
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
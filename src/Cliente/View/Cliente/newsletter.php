<!DOCTYPE html>
<html lang="pt-br">
<head>
<title>Newsletter | <?=NOME_LOJA;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="/">
<meta name="description" content="<?=TAG_DESCRIPTION;?>" />
<meta name="keywords" content="<?=TAG_KEYWORDS;?>" />
	<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<link rel="canonical" href="<?php echo $actual_link;?>" />
<meta name="robots" content="index, follow" />
<meta name="rating" content="general" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="HandheldFriendly" content="True" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style"
	content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="<?=NOME_LOJA;?>">
<meta name="mobile-web-app-capable" content="yes">

<meta property="og:type" content="product.group">
<meta property="og:description" content="<?=TAG_DESCRIPTION;?>">
<meta property="og:locale" content="pt_BR">
<meta property="og:title"
	content="<?=NOME_LOJA;?> | Moda Feminina, Vestidos, Saias, Bolsas e tudo
	mais!">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<meta property="og:url" content="https://<?=LINK_LOJA;?>">
<?php require_once 'src/Site/View/Site/css.php';?>
</head>
<body>
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<div class="services-breadcrumb">
		<div class="agile_inner_breadcrumb">
			<div class="container">
				<ul class="w3_short">
					<li><a href="/">Página Inicial</a> <i class="fas fa-angle-right"></i></li>
					<li>Agradecemos o seu cadastro :)</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="ads-grid">
		<div class="container">
			<div class="agileinfo-ads-display col-md-12">
				<div>
					<div class="content page col-xs-12 col-sm-12 col-md-9 col-lg-9 "
						style="color: #94788B;">
						<br>
						<div
							style="margin: 0px; padding: 0cm 0cm 11pt; border-width: medium medium 1.5pt; border-style: none none dotted; border-color: currentcolor currentcolor #cccccc; border-image: initial; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial; color: #555555; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; font-size: 14px;">
							<p dir="auto"
								style="margin: 0px; line-height: 20px; padding: 0cm; border: medium none currentcolor; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial;">
								<span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(102, 102, 102);"><span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-size: 15pt;">CADASTRO
										NEWSLETTER</span></span>
							</p>
						</div>
						<p dir="auto"
							style="margin: 0cm 0cm 17.25pt 43.8pt; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial; color: #555555;">
							<br> <span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(102, 102, 102);"><span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-weight: 600;"><span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-size: 11.5pt;"><span
										style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;">
											Prezado Sr(a) ,.</span></span></span>
						
						</p>
						<p dir="auto"
							style="margin: 0cm 0cm 10.25pt 43.8pt; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial; color: #555555;">
							<span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(102, 102, 102);"><span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-weight: 600;"><span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-size: 11.5pt;"><span
										style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;">
											Obrigado por cadastrar seu e-mail <?=$data['email'];?>
											para receber newsletter.</span></span></span>
						
						</p>
						<p dir="auto"
							style="margin: 0cm 0cm 17.25pt 43.8pt; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial; color: #555555;">
							<br> <span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(102, 102, 102);"><span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-weight: 600;"><span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-size: 11.5pt;"><span
										style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;">Contudo
											é necessário sua confirmação através do e-mail que você
											receberá em instantes. Basta clicar no link da mensagem e seu
											cadastro será confirmado. </span></span></span>
						
						</p>
						<p dir="auto"
							style="margin: 0cm 0cm 10.25pt 43.8pt; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: none; background-position: 0% 0%; background-size: initial; background-repeat: repeat; background-attachment: scroll; background-origin: initial; background-clip: initial; color: #555555;">
							<span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(102, 102, 102);"><span
								style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-weight: 600;"><span
									style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; font-size: 11.5pt;"><span
										style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;">
											A <?=NOME_LOJA;?> agradece.</span></span></span>
						
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
</body>
</html>
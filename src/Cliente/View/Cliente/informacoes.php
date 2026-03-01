<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?=TAG_DESCRIPTION;?>" />
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
<meta property="og:title" content="">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<meta name="theme-color" content="#ffffff">
<title>Checkout | <?=NOME_LOJA;?></title>
<link href="public/css/bootstrap.css" rel="stylesheet" type="text/css"
	media="all" />
<?php require_once 'src/Site/View/Site/css.php';?>
<link rel="stylesheet" type="text/css" href="public/css/carrinho.css " />
<link rel="stylesheet" type="text/css" href="public/css/payments.css " />
<link rel="stylesheet" type="text/css" href="public/css/card.css " />
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  <?php if($_SESSION['pixel_produto'] != ''){ ?>
  fbq('init', '<?=$_SESSION['pixel_produto'];?>');
  fbq('track', 'InitiateCheckout', {
		value: <?=$data['valor_total_compra'];?>,
	    currency: 'BRL'
  });
  <?php } ?>
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=PIXEL_FACEBOOK?>&ev=AddPaymentInfo&noscript=1" />
</noscript>
</head>
<body>
	<img src="public/img/loading3.gif" alt="<?=NOME_LOJA;?>" id="load-img"
		style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 15%; height: 30%;">
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<div class="cart_header display-none">
		<div class="wrapper">
			<div class="clearfix">
				<div class="cart_logo fl">
					<a href="/"> <img src="public/img/<?=NOME_LOGO;?>"
						class="logo-cart" title="<?=NOME_LOJA;?>" alt="<?=NOME_LOJA;?>">
					</a>
				</div>
				<div class="top_nav_user_carrinho">
					<img src="public/img/user.png"
						style="width: 50px; height: 50px; margin-left: 15px; margin-top: 15px;">
            		<?php if(isset($_SESSION['cliente']['nome'])) {?>
        				<span class="ola-user"><i class="ola">Olá</i>, <span
						class="nome-cliente"><?=$_SESSION['cliente']['nome'];?>!</span></span>
					<a class="minha-conta" class="minha-conta" href="minha-conta"><span>Minha
							Conta</span></a>
            		<?php }else{ ?>
                 		<span class="ola-user"><a href="#"
						data-toggle="modal" data-target="#myModal1"> Faça seu Login</a></span>
					<a class="minha-conta" class="minha-conta" href="#"
						data-toggle="modal" data-target="#myModal2"><span>ou Cadastre-se</span></a>
            		<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="services-breadcrumb">
		<div class="agile_inner_breadcrumb">
			<div class="container">
				<ul class="w3_short">
					<li><a href="/">Página Inicial <i class="fas fa-angle-right"></i></a></li>
					<li><a href="current-checkout">Adicionar endereço de entrega</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="privacy">
		<div class="container">
			<div class="new-adress imageLink">
				<div class="modal fade" id="alerta-cad-end" tabindex="-1" role="dialog">
            		<div class="modal-dialog">
            			<div class="modal-content">
            				<div class="modal-header">
            					<button type="button" class="close" data-dismiss="modal"
            						aria-hidden="true">×</button>
            					<h4 class="modal-title">Alerta</h4>
            				</div>
            				<div class="modal-body">
            					<span id="alert-cad"></span>
            				</div>
            				<div class="modal-footer">
            					<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            				</div>
            			</div>
            		</div>
            	</div>
				<div id="form-end">
					<legend class="cor-secundaria"> 1 - Dados Pessoais</legend>
					<?php if(isset($data['error']) && $data['error'] == true){ ?>
        			<div class="alert alert-warning" role="alert">
        				<?=$data['msg'];?>
        			</div>
        			<?php } ?>
					<form method="post"
						action="?m=cliente&c=cliente&a=saveInformationsBeforeChechout">
						<label for="cep">Digite seus dados pessoais abaixo para iniciar a
							sua compra.</label><br> <br>
						<div class="form-group">
							<input type="email" class="form-control input-end" id="email"
								name="email" aria-describedby="email" placeholder="E-mail"
								required="">
						</div>
						<div class="form-group">
							<input type="password" class="form-control input-end" id="senha"
								name="senha" aria-describedby="senha"
								placeholder="Senha para acompanhar o seu pedido" required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="nome"
								name="nome" aria-describedby="emailHelp" placeholder="Nome"
								required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="sobrenome"
								name="sobrenome" aria-describedby="emailHelp"
								placeholder="Sobrenome" required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="Telefone"
								name="telefone" aria-describedby="emailHelp"
								placeholder="Telefone" required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end cpf" id="cpf_cliente"
								name="cpf" aria-describedby="emailHelp"
								placeholder="CPF" required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="data_nascimento2"
								name="data_nascimento" aria-describedby="emailHelp"
								placeholder="Data de Nascimento" required="">
						</div>
						<div class="form-group">
							<select class="form-control input-end" name="sexo">
								<option  class="input-end" value="">Sexo</option>
								<option  class="input-end" value="F">Feminino</option>
								<option  class="input-end" value="M">Masculino</option>
								<option  class="input-end" value="">Não Informado</option>
							</select>
						</div>
						<legend class="cor-secundaria"> 2 - Endereço de Entrega</legend>
						<label for="cep">Digite os dados para onde vamos enviar o seu
							pedido.</label><br> <br>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="endereco"
								name="endereco" aria-describedby="emailHelp"
								placeholder="Endereço" required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="numero"
								name="numero" aria-describedby="emailHelp" placeholder="Número"
								required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="cidade"
								name="cidade" aria-describedby="emailHelp" placeholder="Cidade"
								required="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="bairro"
								name="bairro" aria-describedby="emailHelp" placeholder="Bairro"
								required="">
						</div>
						<div class="form-group">
							<select class="form-control input-end" name="estado">
								<?php foreach (estadosBrasileiros() as $indice => $estado){?>
									<option  class="input-end" value="<?=$indice;?>"><?=$estado;?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<input type="text" class="form-control input-end" id="cep"
								name="cep" aria-describedby="emailHelp" placeholder="Cep"
								required="">
						</div>
						<button type="submit"
							class="btn btn-danger btn-add-endereco button-comprar" onclick="return validateFormInformacoes();">CONTINUAR</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
	<script type="text/javascript">
	function valida_cpf(cpf){
	    cpf = cpf.replace(/\D/g, '');
	    if(cpf.toString().length != 11 || /^(\d)\1{10}$/.test(cpf)) return false;
	    var result = true;
	    [9,10].forEach(function(j){
	        var soma = 0, r;
	        cpf.split(/(?=)/).splice(0,j).forEach(function(e, i){
	            soma += parseInt(e) * ((j+2)-(i+1));
	        });
	        r = soma % 11;
	        r = (r <2)?0:11-r;
	        if(r != cpf.substring(j, j+1)) result = false;
	    });

	    return result;
	}

	function FormataStringData(data) {
		  var dia  = data.split("/")[0];
		  var mes  = data.split("/")[1];
		  var ano  = data.split("/")[2];
		  return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
	}
	
	function validateFormInformacoes() {
		var dt = FormataStringData(document.getElementById("data_nascimento2").value);
	    var dataNascimento = new Date(dt);

	    var _dataAtual = new Date();
	    var _dataAtualMenos18Anos = new Date(_dataAtual.getFullYear() - 18,
	    		_dataAtual.getMonth(),
	    		_dataAtual.getDate());

	    if (dataNascimento == 'Invalid Date') {
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Data de Nascimento Inválida!</p>');
	    }else if(dataNascimento >= _dataAtual){
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Data de Nascimento não permitida para cadastro!</p>');
	    }else if(dataNascimento >= _dataAtualMenos18Anos){
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Data de Nascimento não permitida para cadastro!</p>');
	    }
	    
		if (document.getElementById("cep").value.length < 10) {
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Insira um cep válido!</p>');
	    }

		if (document.getElementById("Telefone").value.length < 14) {
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Insira um telfone válido!</p>');
	    }

		var _cpf_cliente = valida_cpf(document.getElementById("cpf_cliente").value);
	    if(!_cpf_cliente){
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>Insira um cpf válido!</p>');
	    }
    }
	</script>
</body>
</html>
<?php
use Krypitonite\Util\ValidateUtil;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<link rel="manifest" href="/manifest.json">
<title>Carrinho de Compras | <?=NOME_LOJA;?></title>
<?php require_once 'src/Site/View/Site/css.php';?>
<link rel="stylesheet" type="text/css" href="public/css/carrinho.css " />
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
  fbq('track', 'AddToCart', {
	     value: <?=$data['_total_float'];?>,
	     currency: 'BRL'
  });
  <?php } ?>
</script>
  <?php if($_SESSION['pixel_produto'] != ''){ ?>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=$_SESSION['pixel_produto'];?>&ev=AddToCart&noscript=1" />
</noscript>
  <?php } ?>
</head>
<body>
	<img src="public/img/loading3.gif" alt="<?=NOME_LOJA;?>" id="load-img"
		style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
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
	<div class="modal fade" id="myModal1" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Acessar conta</h4>
				</div>
				<div class="modal-body modal-body-sub_agile">
					<div class="main-mailposi">
						<span class="fa fa-envelope-o" aria-hidden="true"></span>
					</div>
					<div class="modal_body_left modal_body_left1">
						<p>
							Não tem uma conta? <a href="#" data-toggle="modal"
								data-target="#myModal2">Inscreva-se agora</a>
						</p>
						<form action="?m=cliente&c=cliente&a=logar" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" placeholder="E-mail" name="email"
									required="required">
							</div>
							<div class="styled-input">
								<input type="password" placeholder="Senha" name="senha"
									required="required">
							</div>
							<input type="submit" value="Prosseguir">
						</form>
						<a href="#"><h6>Esqueceu a senha?</h6></a>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Cadastre-se</h4>
				</div>
				<div class="modal-body modal-body-sub_agile">
					<div class="main-mailposi">
						<span class="fa fa-envelope-o" aria-hidden="true"></span>
					</div>
					<div class="modal_body_left modal_body_left1">
						<p>Crie uma conta caso ainda não possua cadastro.</p>
						<form action="?m=cliente&c=cliente&a=cadastrar" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" placeholder="Nome completo" name="nome"
									required="">
							</div>
							<div class="styled-input">
								<input type="email" placeholder="E-mail" name="email"
									required="">
							</div>
							<div class="styled-input">
								<input type="text" placeholder="CPF" name="cpf" class="cpf"
									required="">
							</div>
							<div class="styled-input">
								<input type="text" placeholder="Telefone/Celular"
									name="telefone" class="telefone" required="">
							</div>
							<div class="styled-input">
								<input type="password" placeholder="Crie uma senha" name="senha"
									id="password1" required="">
							</div>
							<div class="styled-input">
								<input type="password" placeholder="Confirmar senha"
									name="Confirmar Senha" id="password2" required="">
							</div>
							<input type="submit" value="Cadastrar">
						</form>
						<p>
							<a href="#"></a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="services-breadcrumb">
		<div class="agile_inner_breadcrumb">
			<div class="container">
				<ul class="w3_short">
					<li><a href="/">Página Inicial <i class="fas fa-angle-right"></i></a></li>
					<li><a href="meu-carrinho">Carrinho</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="privacy">
		<form method="post"
			action="/?m=checkout&c=checkout&a=current_checkout">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<div class="checkout-right">
							<h3 class="tittle-w3l carrinho">Meu carrinho</h3>
							<div id="cart-container" class="cart_container">
								<div id="summary-container">
									<div class="cart_prowrap">
										<table class="table" id="cart-itens">
											<thead class="thead-dark">
												<tr>
													<th scope="col" id="no-border-th">produto</th>
													<th scope="col" id="no-border-th">qtd.</th>
													<th scope="col" id="no-border-th">descrição</th>
													<th scope="col" id="no-border-th">preço</th>
													<th scope="col" id="no-border-th">remover</th>
												</tr>
											</thead>
											<tbody>
            									<?php if(sizeof($data['_itens']) != 0){?>
            									<?php foreach ($data['_itens'] as $key => $value) { ?>
            									<tr style="border: 1px solid #e5e5e5;">
													<td class="invert-image"><a
														href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>">
															<img
															src="data/products/<?=$value['codigo'];?>/principal.jpg"
															alt="<?=$value['descricao'];?>" class="img-responsive">
													</a></td>
													<td><input type="hidden" name="codigo[]"
														value="<?=$value['codigo'];?>"> <select
														class="qtd-product" id="qtd-product"
														onchange="changeQtd(this.value, '<?=$value['codigo'];?>', <?=$value['valor_unitario'];?>);"
														name="qtd[]">
															<option><?=$value['quantidade'];?></option>
													<?php foreach (range(1, 20) as $v) { ?>
													<?php if($value['quantidade'] != $v){ ?>
														<option><?=$v?></option>
													<?php } ?>	
													<?php }?>
												</select></td>
													<td class="invert"><?=$value['descricao'];?></td>
													<td class="td-price">R$ <span
														id="<?=$value['codigo'];?>-price"><?=ValidateUtil::setFormatMoney($value['valor']);?></span></td>
													<td class="invert">
														<div class="rem">
															<a
																href="/?m=checkout&c=checkout&a=cesta&remover=<?=($key);?>"><i
																class="fa fa-trash" aria-hidden="true"></i></a>
														</div>
													</td>
												</tr>
            									<?php }?>
            									<?php }else {?>		
            									<tr>
													<td colspan="5">Seu Carrinho de compras está vazio</td>
												</tr>						
            									<?php }?>
                    						</tbody>
										</table>
									</div>
								</div>
								<div class="cart_checkoutwrap clearfix">
									<table class="TableUI-o6rohr-0 kJZaBa">
										<tr>
										
										
										<tr>
											<td width="150"><span class="span-frete">Calcular frete e
													prazo</span></td>
										</tr>
										<tr>
											<td width="100"><input name="cep_destino"
												placeholder="Digite seu CEP"
												class="form-control input-frete" id="cep_destino"></td>
											<td><button id="calcular" class="change" type="button">OK</button></td>
											<td class="no-mobile"><a href="/"
												class="cart_btn_gray large fl">Continuar Comprando</a></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="checkout-left">
							<div class="basket-summaryAndComboVip__wrapper">
								<div class="basket-summary">
									<h3 class="summary-title">Resumo do pedido</h3>
									<ul class="summary-details">
										<li class="summary-detail"><span> Subtotal (<?=$data['_qtd'];?>) </span><span
											id="sub-total">
											R$ <?=$data['_total'];?> </span></li>
										<li class="summary-detail -freightNotSelected"><span>Frete</span><span
											id="total-frete">-</span></li>
										<li class="summary-detail"><span> <b>total</b>
										</span><b><span id="_total_cart">
											R$ <?=$data['_total'];?> </span></b></li>
									</ul>
									<?php if(isset($_SESSION['CUPOM_VALIDADO']) && $_SESSION['CUPOM_VALIDADO'] == true){ ?>
									<table class="TableUI-o6rohr-0 kJZaBa">
										<tr>
											<td><input name="meu_cupom" placeholder="Meu cupom"
												value="<?=$_SESSION['CUPOM_CLIENTE'];?>" disabled="disabled"
												class="form-control input-cupom" id="meu_cupom"></td>
											<td><button id="aplicar_cupom" class="button-cupom"
													disabled="disabled" type="button">APLICAR</button></td>
										</tr>
									</table>
									<?php }else{ ?>
									<table class="TableUI-o6rohr-0 kJZaBa">
										<tr>
											<td><input name="meu_cupom" placeholder="Meu cupom"
												class="form-control input-cupom" id="meu_cupom"></td>
											<td><button id="aplicar_cupom" class="button-cupom"
													type="button">APLICAR</button></td>
										</tr>
									</table>
									<?php } ?>
									<span style="<?=($_SESSION['CUPOM_VALIDADO'] == true) ? '' : 'display: none;';?>" id="remover-cupom-span"><a
										href="#" id="remover_cupom"
										style="margin-top: 10px; display: inline-block; padding: 6px;">Remover
											cupom.</a></span> <span class="" id="badge-cupom"
										style="margin-top: 10px; display: none;"><span
										id="badge-spam-cupom"></span></span>
									<div class="fr">
										<div class="cart_btnwrap">
											<button name="checkout" class="button-comprar" title=""
												type="submit" type="submit" style="float: right;">
												<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
												FINALIZAR COMPRA
											</button>
										</div>
										<div class="cart_checkbox">
											<label style="font-size: 13px;" for="policy"> <input
												id="policy" name="cbPolicy" class="cb" type="checkbox"
												checked="checked">Eu com concordo com os Termos e Politicas
												da <?=LINK_LOJA;?>.
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
	<script>
    	Array.prototype.sum = function(selector) {
    	    if (typeof selector !== 'function') {
    	        selector = function(item) {
    	            return item;
    	        }
    	    }
    	    var sum = 0;
    	    for (var i = 0; i < this.length; i++) {
    	        sum += parseFloat(selector(this[i]));
    	    }
    	    return sum;
    	};
	
		function changeQtd(qtd, id_valor, realValor){
			document.getElementById("load-img").style.display = "inline-block";
	 		$('body').css("opacity", "0.5");
			
			setTimeout(function(){ 
				var _total = 0;
				var price = document.getElementById(id_valor+'-price').innerText;
				price = price.replace(',', '.');
				price = price.replace(/[^0-9,.]*/, '');
				price = parseFloat(price);

				var newPrice = realValor * parseInt(qtd);
				document.getElementById(id_valor+'-price').innerHTML = numberToReal(newPrice);

				var table = document.getElementById('cart-itens');
			    for (var r = 0, n = table.rows.length; r < n; r++) {
				    prices = table.rows[r].cells[2].innerText;
				    prices = prices.replace(',', '.');
				    prices = prices.replace('R$', '');
				    prices = parseFloat(prices);
				    if(!isNaN(prices)){
				    	_total += prices;
				    }
			    }

			    var frete = document.getElementById('total-frete').innerText;
			    frete = frete.replace(',', '.');
	            frete = frete.replace('R$', '');
	            frete = parseFloat(frete);

	            var _frete = 0;
	            if(!isNaN(frete)){
	            	_frete = frete;
	            }
			    
			    $.ajax({
    				type : 'POST',
    			 	beforeSend: function(){},
    				dataType : "text",
    				async : false,
    				url : "/?m=checkout&c=checkout&a=calculateTotalProducts",
    				data : {
     					"id_produto" : id_valor,
     					"quantidade" : qtd
    				},					  
    				success: function(response){
        				jjson = JSON.parse(response);
        			    document.getElementById('sub-total').innerHTML = 'R$ ' + numberToReal(jjson.value_total_cart);
        			    document.getElementById('_total_cart').innerHTML = 'R$ ' + numberToReal((jjson.value_total_cart) + _frete);
        			    document.getElementById("load-img").style.display = "none";
            	 		$('body').css("opacity", "1");
    				},
    			});
     		}, 1000);
		}

		function numberToReal(numero) {
		    var numero = numero.toFixed(2).split('.');
		    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
		    return numero.join(',');
		}
		
		$(document).ready(function () {
	        $("#calcular").on('click', function(){
	        	$('#load-img').show();
	        	$('#load-img').css('display', 'inline-block');
    	 		$('body').css("opacity", "0.5");
	     		setTimeout(function(){ 
	     			$.ajax({
	    				type : 'POST',
	    			 	beforeSend: function(){},
	    				dataType : "text",
	    				async : false,
	    				url : "/?m=checkout&c=checkout&a=calcularPrecoPrazoCart",
	    				data : {
	     					"cep" : document.getElementById('cep_destino').value,
	    				},					  
	    				success: function(response){
	    					$('#load-img').css("display", "none");
	    					$('#load-img').hide();
	                        $('body').css("opacity", "1");

							var _resultFrete = JSON.parse(response);
	                        document.getElementById('total-frete').innerHTML = 'R$ ' + numberToReal(_resultFrete.frete_total);

	                        var _sub_total_ = document.getElementById('sub-total').innerHTML;
	                        _sub_total_ = _sub_total_.replace('.', '');
	                        _sub_total_ = _sub_total_.replace(',', '.');
	                        _sub_total_ = _sub_total_.replace('R$', '');
	                        _sub_total_ = parseFloat(_sub_total_);
	                        
	                        _total_ = _sub_total_ + _resultFrete.frete_total;
	                        document.getElementById('_total_cart').innerHTML = 'R$ ' + numberToReal(_total_);
	    				  },
	    			});
	     		}, 100);
	        });

	        $("#remover_cupom").on('click', function(){
	        	$('#load-img').show();
	        	$('#load-img').css('display', 'inline-block');
    	 		$('body').css("opacity", "0.5");
    	 		
	        	setTimeout(function(){ 
	     			$.ajax({
	    				type : 'POST',
	    			 	beforeSend: function(){},
	    				dataType : "text",
	    				async : false,
	    				url : "/?m=checkout&c=checkout&a=removeCupom",
	    				success: function(response){
		    				res = JSON.parse(response);
							if(res.success){		    		
							    $('#aplicar_cupom').prop('disabled', false);
							    $('#meu_cupom').prop('disabled', false);

		    				    document.getElementById('meu_cupom').value = '';
		                        document.getElementById('sub-total').innerHTML = 'R$ ' + res.valor_total_sem_cupom;
		                        document.getElementById('_total_cart').innerHTML = 'R$ ' + res.valor_total_sem_cupom;
		    					$('#remover-cupom-span').css("display", "none");
		                        $('#badge-cupom').css("display", "none");
    	    					$('#load-img').css("display", "none");
    	    					$('#load-img').hide();
    	                        $('body').css("opacity", "1");
	    				  	}
	    				}
	    			});
	     		}, 1000);
	        });

	        $("#aplicar_cupom").on('click', function(){
	        	$('#load-img').show();
	        	$('#load-img').css('display', 'inline-block');
    	 		$('body').css("opacity", "0.5");

    	 		setTimeout(function(){ 
	     			$.ajax({
	    				type : 'POST',
	    			 	beforeSend: function(){},
	    				dataType : "text",
	    				async : false,
	    				url : "/?m=checkout&c=checkout&a=checkCupom",
	    				data : {
	     					"meu_cupom" : document.getElementById('meu_cupom').value,
	    				},					  
	    				success: function(response){
		    				result = JSON.parse(response);
		    				if(result.cupom_valido == true){
		    					$('#badge-cupom').css("display", "inline-block");
		    					$('#badge-cupom').css("padding", "6px");
		    					$('#badge-cupom').css("background", "#9ACD32");
		    					$('#badge-cupom').css("color", "#FFF");
								var percent = 100 - result.percentual_desconto;
		                        var _sub_total_ = document.getElementById('sub-total').innerHTML;
		                        _sub_total_ = _sub_total_.replace('.', '');
		                        _sub_total_ = _sub_total_.replace(',', '.');
		                        _sub_total_ = _sub_total_.replace('R$', '');
		                        _sub_total_ = parseFloat(_sub_total_);
		                        _sub_total_ = (_sub_total_ / 100) * percent;

		    					$('#sub-total').html('R$ ' + numberToReal(_sub_total_));

		    				    var frete = document.getElementById('total-frete').innerText;
		    				    frete = frete.replace(',', '.');
		    		            frete = frete.replace('R$', '');
		    		            frete = parseFloat(frete);

		    		            var _frete = 0;
		    		            if(!isNaN(frete)){
		    		            	_frete = frete;
		    		            }

		    					$('#_total_cart').html('R$ ' + numberToReal(_sub_total_ + _frete));

		    					$('#remover-cupom-span').css("display", "inline-block");
		    					$('#badge-spam-cupom').html(result.message);
		    				    $('#aplicar_cupom').prop('disabled', true);
		    				    $('#meu_cupom').prop('disabled', true);
		    					
		    				}else if(result.cupom_valido == false){
		    					$('#badge-cupom').css("display", "inline-block");
		    					$('#badge-cupom').css("padding", "6px");
		    					$('#badge-cupom').css("background", "#FF6347");
		    					$('#badge-cupom').css("color", "#FFF");
		    					$('#badge-spam-cupom').html(result.message);
		    				}
		    				
	    					$('#load-img').css("display", "none");
	    					$('#load-img').hide();
	                        $('body').css("opacity", "1");
	    				  },
	    			});
	     		}, 1000);
		    });
		});
	</script>
</body>
</html>
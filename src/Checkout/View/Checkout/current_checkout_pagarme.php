<?php
use Krypitonite\Util\ValidateUtil;
use Store\Pagamento\Controller\PagamentoMPController;
require_once ('src/Pagamento/Controller/PagamentoMPController.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description"
	content="<?=TAG_DESCRIPTION;?>" />
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
  fbq('init', '<?=$_SESSION['pixel_produto'];?>', {
		em: '<?=$data['_correspondencia_fbk']['em'];?>',
		fn: '<?=$data['_correspondencia_fbk']['fn'];?>',	
		ln: '<?=$data['_correspondencia_fbk']['ln'];?>',
		country: '<?=$data['_correspondencia_fbk']['country'];?>',	
		ct: '<?=$data['_correspondencia_fbk']['ct'];?>',
		ph: '<?=$data['_correspondencia_fbk']['ph'];?>',	
		st: '<?=$data['_correspondencia_fbk']['st'];?>',	
		zp: '<?=$data['_correspondencia_fbk']['zp'];?>'
  });
  <?php } ?>
</script>
  <?php if($_SESSION['pixel_produto'] != ''){ ?>
<noscript>
<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?=$_SESSION['pixel_produto'];?>&ev=AddPaymentInfo&noscript=1" /></noscript>
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
					<a href="/"> <img src="public/img/logo6.png" class="logo-cart"
						title="<?=NOME_LOJA;?>" alt="<?=NOME_LOJA;?>">
					</a>
				</div>
				<div class="top_nav_user_carrinho">
					<img src="public/img/user.png"
						style="width: 50px; height: 50px; margin-left: 15px; margin-top: 15px;">
            		<?php if(isset($_SESSION['cliente']['nome'])) {?>
        				<span class="ola-user"><i class="ola">Olá</i>, <span
						class="nome-cliente"><?=$_SESSION['cliente']['nome'];?>!</span></span>
					<a class="minha-conta" class="minha-conta"
						href="minha-conta"><span>Minha Conta</span></a>
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
					<li><a href="current-checkout">Checkout</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="privacy">
		<div class="container" id="final-car">
			<div class="checkout-right-checkout">
				<div id="cart-container" class="cart_container">
					<br>
					<h5>Finalizar compra</h5>
					<br>
					<div id="summary-container">
						<div class="cart_prowrap">
							<table class="table">
								<tbody>
									<tr style="border-top: 1px solid #e5e5e5;">
										<td><span class="check">(<?=$data['_qtd'];?><a href="#"
												data-toggle="modal" data-target="#itens">) <b>Ver produto(s)</b></a><br>Frete para <?=$data['_endereco_principal']['cidade'];?>/<?=$data['_endereco_principal']['uf'];?> (CEP: <?=$data['_endereco_principal']['cep'];?>)</span></td>
										<td><span class="check">R$ <?=$data['_total'];?><br><?=ValidateUtil::setTextGratisNoZero($data['_frete']);?>
											</span></td>
									</tr>
									<?php if($_SESSION['CUPOM_VALIDADO'] == TRUE){ ?>
									<tr style="border-top: 1px solid #e5e5e5;">
										<td><span class="check"><b>meu cupom</b> </span></td>
										<td><span class="check" style="color: #FF8C00; font-weight: bold;"><?=$_SESSION['CUPOM_CLIENTE'];?> </span></td>
									</tr>
									<?php } ?>
									<tr style="border-top: 1px solid #e5e5e5;">
										<td><span class="check"><b>subtotal</b> </span></td>
										<td><span class="check">R$  <?=$data['_total'];?> </span></td>
									</tr>
									<tr style="border-top: 1px solid #e5e5e5;">
										<td><span class="check"><b>total a pagar</b> </span></td>
										<td><span class="check"><b id="_total_cart"><?=$data['_total_a_pagar'];?></b></span></td>
									</tr>
									<tr style="border-top: 1px solid #e5e5e5;">
										<td><span class="check"><b>total a pagar no boleto</b> </span></td>
										<td><span class="check"><b id="_total_cart">R$ <?=$data['_total_a_pagar_no_boleto'];?></b></span></td>
									</tr>
									<tr></tr>
									<?php 
									
									$valor_pac = $data['_detalhes_entrega']['PAC']['valor'];
									$valor_sedex = $data['_detalhes_entrega']['SEDEX']['valor'];
									
									$prazo_pac = $data['_detalhes_entrega']['PAC']['prazo'];
									$prazo_sedex = $data['_detalhes_entrega']['SEDEX']['prazo'];
									
									?>
									<?php if($valor_pac != 0){ ?>
									<tr class="<?=($data['_valor_frete_sem_promocao'] == $valor_pac) ? 'radio-checked' : 'radio-no-checked' ?>">
										<td><div style="padding: 5%;"><input type="radio" name="tranporte" value="03085" <?=($data['_valor_frete_sem_promocao'] == $valor_pac) ? 'checked="checked"' : '' ?>><span class="check"><img src="public/img/fe_pac-loja.jpg" class="<?=($data['_valor_frete_sem_promocao'] == $valor_pac) ? 'transporte-checked' : 'transporte-no-checked' ?>"/> </span></div></td>
										<td><span class="check">PAC<br><b><?=($data['_total_float'] > VALOR_MINIMO_PARA_FRETE_GRATIS) ? 'Grátis' : ValidateUtil::setTextGratisNoZero(ValidateUtil::setFormatMoney($valor_pac));?></b><br><?=$prazo_pac;?></span></td>
									</tr>
									<?php } ?>
									<tr class="<?=($data['_valor_frete_sem_promocao'] == $valor_sedex) ? 'radio-checked' : 'radio-no-checked' ?>">
										<td><div style="padding: 5%;"><input type="radio" name="tranporte" value="03050" <?=($data['_valor_frete_sem_promocao'] == $valor_sedex) ? 'checked="checked"' : '' ?>><span class="check"><img src="public/img/fe_sedex-loja.jpg" class="<?=($data['_valor_frete_sem_promocao'] == $valor_sedex) ? 'transporte-checked' : 'transporte-no-checked' ?>"/> </span></div></td>
										<td><span class="check">SEDEX<br><b>R$ <?=ValidateUtil::setFormatMoney($valor_sedex);?></b><br><?=$prazo_sedex;?></span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="checkout-left-checkout">
				<div class="basket-summaryAndComboVip__wrapper">
					<div class="basket-summary">
						<h5>Endereço de entrega</h5>
						<hr style="width: 50%;" />
						<ul class="endereco-list">
							<li><b><?=$data['_endereco_principal']['destinatario'];?></b></li>
							<li><?=$data['_endereco_principal']['endereco'];?></li>
							<li><?=$data['_endereco_principal']['cidade'];?>/<?=$data['_endereco_principal']['bairro'];?> - <?=$data['_endereco_principal']['uf'];?></li>
							<li>CEP <?=$data['_endereco_principal']['cep'];?></li>
						</ul>
						<hr style="width: 50%;" />
						<a href="#" class="a-alterar-endereco" data-toggle="modal"
							data-target="#mudarEndereco">Alterar / Cadastrar</a>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="container">
			<div class="w3-container">
				<?php if(!$data['_endereco_principal']['cep']){ ?>
        		<span class="check">VOCÊ AINDA NÃO SELECIONOU SEU ENDEREÇO</span><br>
        		<?php }else{ ?>
				<h4>Formas de pagamento</h4>
				<p>Todas as transações são seguras e criptografadas</p>
				<br>
				<?php 
				$gateway = GATEWAY;
				if($gateway == 'pagarme'){ ?>
				<div class="w3-row">
					<a href="javascript:void(0)" onclick="openOpcaoPag(event, 'Cartao');">
						<div
							class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">
							<img alt="<?=NOME_LOJA;?>" src="public/img/icon-creditcard.png"
								style="width: 8%; display: none;">Cartão de crédito
						</div>
					</a> 
					<a href="javascript:void(0)"
						onclick="openOpcaoPag(event, 'Boleto');">
						<div
							class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">
							<img alt="<?=NOME_LOJA;?>" src="public/img/icon-boleto.png"
								style="width: 13.5%; display: none;">Boleto Bancário
						</div>
					</a>
					<a href="javascript:void(0)"
						onclick="openOpcaoPag(event, 'Pix');">
						<div
							class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding">
							<img alt="<?=NOME_LOJA;?>" src="public/img/icon-boleto.png"
								style="width: 13.5%; display: none;">PIX
						</div>
					</a>
				</div>
				<div id="Cartao" class="w3-container cartao"
					style="background-color: #FFF;">
					<div class="payments">
						<div class="col-md-6" id="div-cartao">
							<form action="#" method="post" id="pagar-cartao-rede">
								<div class="row-fluid">
									<div class="col-md-12">
											<input type="hidden" name="_endereco" value="<?=$data['_endereco_principal']['id'];?>"> 
											<input type="hidden" name="_bandeira_cartao" id="bandeira_cartao"> 
										<div class="form-group hide">
											<label>CPF do Titular do Cartão</label> 
											<input type="text" name="_cpf"  id="cpf" value="<?=$data['_cpf_cliente'];?>" class="form-control input-card cpf" disabled="disabled"/>
										</div>
										<div class="form-group">
											<label>Titular </label> 
											<input type="text" name="_name" class="form-control input-card" id="titular"/>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Número do Cartão </label> 
											<input type="text" name="_number_card" class="form-control input-card" id="NumeroCartao"/>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Parcelas </label> 
											<input type="hidden" id="hasParcela">
											<select class="form-control input-card"  id="parcela"  name="_parcela">
												<option value="01">01</option>
    											<option value="02">02</option>
    											<option value="03">03</option>
    											<option value="04">04</option>
    											<option value="05">05</option>
    											<option value="06">06</option>
    											<option value="07">07</option>
    											<option value="08">08</option>
    											<option value="09">09</option>
    											<option value="10">10</option>
    											<option value="11">11</option>
    											<option value="12">12</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Mês</label> 
											<select class="form-control input-card"  id="mes_validade"  name="_expiry_month">
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Ano</label>
											<select class="form-control input-card"  id="ano_validate"  name="_expiry_year">
												<option value="2019">2019</option>
    											<option value="2020">2020</option>
    											<option value="2021">2021</option>
    											<option value="2022">2022</option>
    											<option value="2023">2023</option>
    											<option value="2024">2024</option>
    											<option value="2025">2025</option>
    											<option value="2026">2026</option>
    											<option value="2027">2027</option>
    											<option value="2028">2028</option>
    											<option value="2029">2029</option>
    											<option value="2030">2030</option>
											</select>	
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>CVV </label> 
											<input type="text" class="form-control input-card"  id="cvv"  name="_cvv"/>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<p class="informacoes-bandeira">
												<span class="bandeira"></span>
											</p>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<button type="button" class="btn button-comprar" onclick="payByRede();"
												id="pagarCartaoComRede" >
											<i class="fa fa-credit-card" aria-hidden="true"></i>
												Finalizar compra
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-6">
							<div id="status-compra" style="display: none;">
								<center>
									<span id="situacao-pagamento"></span>
								</center>
								<br>
								<center>
									<span id="parcela-pagamento"></span>
								</center>
								<br>
								<center>
									<span>Forma de Pagamento: <span id="forma-pagamento"></span></span>
								</center>
							</div>
						</div>
					</div>
				</div>
				<div id="Boleto" class="w3-container cartao"
					style="display: none; background-color: #FFF;">
					<div class="payments">
						<form action="#" method="post" id="pagar-boleto">
							<div class="col-md-7">
								<div class="row-fluid">
									<div class="col-md-12">
										<input type="hidden" name="_endereco"
											value="<?=$data['_endereco_principal']['id'];?>"> <input
											type="hidden" name="sessionId" id="sessionId_boleto"> <input
											type="hidden" name="hash" id="hash"> <input type="hidden"
											name="tipo_pagamento" value="boleto">
									</div>
									<div class="col-md-12">
										<p>Clique no botão "GERAR BOLETO" para confirmar seu pedido e
											imprimir seu boleto.</p>

										<p>Imprima o boleto e pague na agência bancária de sua
											preferência ou através dos serviços de Internet Banking.</p>

										<p>Acompanhe o prazo de validade do seu boleto, ele não será
											enviado pelos Correios.</p>
										<div class="form-group">
            								<label>CPF DE COBRANÇA</label> <input type="text" name="cpf_boleto" id="cpf_boleto" 
            									value="<?=$data['_cpf_cliente'];?>"
            									class="form-control input-card cpf" />
            							</div>
										<div class="form-group">
											<button type="button" class="btn button-comprar"
												id="pagarBoleto">
												<i class="fa fa-barcode"></i> GERAR BOLETO
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div id="status-compra-boleto" style="display: none;">
									<br>
									<center>
										<h4>Clique no link abaixo para abrir o boleto!</h4>
									</center>
									<br>
									<center>
										<span>CÓDIGO DE BARRAS<br> <span id="codigo_boleto"></span></span>
										<i class="fa fa-copy" onclick="copiarCodigoBoleto()"></i>
									</center>
									<br>
									<center>
										<span>Forma de Pagamento: <b>Boleto Bancário</b> </span>
										<div id="link-boleto"></div>
									</center>
								</div>
							</div>
						</form>
					</div>
				</div>	
				<div id="Pix" class="w3-container cartao"
					style="display: none; background-color: #FFF;">
					<div class="payments">
						<form action="#" method="post" id="pagar-pix">
							<div class="col-md-7">
								<div class="row-fluid">
									<div class="col-md-12">
										<input type="hidden" name="_endereco"
											value="<?=$data['_endereco_principal']['id'];?>"> <input
											type="hidden" name="sessionId" id="sessionId_boleto"> <input
											type="hidden" name="hash" id="hash"> <input type="hidden"
											name="tipo_pagamento" value="pix">
									</div>
									<div class="col-md-12">
										<p>Para aprovação do seu pedido após o pagamento via PIX, mande o comprovante para o nosso whatsapp ou e-mail.</p>

										<ul>
											<li>E-mail: contato@shopvitas.com.br</li>
											<li>WhatsApp: <a href="https://api.whatsapp.com/send?phone=556196187206&text=Olá, fiz um pagamento via PIX e estou enviando o comprovante, pode aprovar por favor ?" target="_blank"><img style="width: 25px;" src="public/img/whatsapp.png" /></a></li>
										</ul>
										<div class="form-group hide">
            								<label>CHAVE PIX (CNPJ)</label> <input type="text"
            									value="20.747.907/0001-26"
            									class="form-control input-card" />
            							</div>
            							<div class="form-group">
											<button type="button" class="btn button-comprar"
												id="pagarPix">
												<i class="fa fa-barcode"></i> Finalizar Pedido
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div id="status-compra-pix" style="display: none;">
									<br>
									<center>
										<h4>Cópie o PIX abaixo para realizar o pagamento!</h4>
									</center>
									<br>
									<center>
										<span>CHAVE PIX (CNPJ):<br> <span id="chave_pix"></span></span>
										<i class="fa fa-copy" onclick="copiarPix()"></i>
									</center>
									<br>
									<center>
										<span>Forma de Pagamento: <b>Pix</b> </span>
										<div id="link-boleto"></div>
									</center>
								</div>
							</div>
						</form>
					</div>
				</div>				
				<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="modal fade" id="itens" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body modal-body-sub_agile">
					<div class="main-mailposi">
						<span class="fa fa-envelope-o" aria-hidden="true"></span>
						<h4>Itens</h4>
					</div>
					<div class="modal_body_left modal_body_left1">
						<br>
						<table class="table" id="cart-itens">
							<thead class="thead-dark">
								<tr>
									<th scope="col" id="no-border-th">produto</th>
									<th scope="col" id="no-border-th">qtd.</th>
									<th scope="col" id="no-border-th">descrição</th>
									<th scope="col" id="no-border-th">preço</th>
								</tr>
							</thead>
							<tbody>
									<?php foreach ($data['_itens'] as $key => $value) { ?>
									<tr style="border: 1px solid #e5e5e5;">
									<td class="invert" style="width: 100px;"><a
										href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>">
											<img
											src="data/products/<?=$value['codigo'];?>/<? $value['imagem']; ?>principal.jpg"
											alt="<?=NOME_LOJA;?>" style="width: 80px;" class="">
									</a></td>
									<td><h6><?=$value['quantidade'];?></h6></td>
									<td class="invert"><h6><?=$value['descricao'];?></h6></td>
									<td class="td-price"><h6 style="text-transform: uppercase;">
											R$ <span id="<?=$value['codigo'];?>-price"><?=ValidateUtil::setFormatMoney($value['valor']);?></span>
										</h6></td>
								</tr>
									<?php }?>								
        						</tbody>
						</table>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mudarEndereco" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body modal-body-sub_agile">
					<div class="main-mailposi">
						<span class="fa fa-envelope-o" aria-hidden="true"></span>
						<h4>alterar endereço de entrega</h4>
					</div>
					<div class="modal_body_left modal_body_left1">
						<br>
						<?php if(sizeof($data['_enderecos_cliente']) == 0){ ?>
                		<span class="check">NENHUM ENDEREÇO CADASTRADO</span><br><br>
                		<a class="cad-endereco" href="minha-conta#parentHorizontalTab3"><i class="fas fa-map-marker-alt"></i> <b>CLIQUE AQUI PARA CADASTRAR</b></a>
                		<?php }else{ ?>
						<form action="?m=checkout&c=checkout&a=alterarEnderecoEntrega"
							method="post">
    							<?php foreach ($data['_enderecos_cliente'] as $e) {?>
    							<div class="escolher-endereco">
								<input type="radio" name="endereco_principal"
									value="<?=$e['id'];?>" class="form-control radio-endereco">
								<ul class="endereco-ecolha">
									<li><b><?=$e['destinatario'];?></b><br><?=$e['endereco'];?><br><?=$e['cidade'];?>/<?=$e['bairro'];?> - <?=$e['uf'];?><br>CEP <?=$e['cep'];?></li>
								</ul>
							</div>
    							<?php } ?>
    							<button class="button-comprar" type="submit">SALVAR</button>
						</form>
						<?php } ?>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="alerta-cartao-c" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">×</button>
					<h4 class="modal-title">Alerta</h4>
				</div>
				<div class="modal-body">
					<span id="alert-cartao"></span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
	
	<?php if($gateway == 'pagarme'){ ?>
	<script type="text/javascript" src="public/js/pagarme.js"></script>
	<?php } ?>
	<script type="text/javascript">
    	function openOpcaoPag(evt, cityName) {
    		  var i, x, tablinks;
    		  x = document.getElementsByClassName("cartao");
    		  for (i = 0; i < x.length; i++) {
    		     x[i].style.display = "none";
    		  }
    		  tablinks = document.getElementsByClassName("tablink");
    		  for (i = 0; i < x.length; i++) {
    		     tablinks[i].className = tablinks[i].className.replace(" w3-border-orange", "");
    		  }
    		  document.getElementById(cityName).style.display = "block";
    		  evt.currentTarget.firstElementChild.className += " w3-border-orange";
		}

		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}

		function payByRede(){
			$('#load-img').css('display', 'inline-block');
			$('body').css("opacity", "0.5");
		}
		
		function copiarCodigoBoleto() {
          var aux = document.createElement("input");
          aux.setAttribute("value", document.getElementById("codigo_boleto").innerText);
          document.body.appendChild(aux);
          aux.select();
          document.execCommand("copy");
          document.body.removeChild(aux);
          alert("Código copiado com sucesso!");
        }
        
        function copiarPix() {
          var auxx = document.createElement("input");
          auxx.setAttribute("value", document.getElementById("chave_pix").innerText);
          document.body.appendChild(auxx);
          auxx.select();
          document.execCommand("copy");
          document.body.removeChild(auxx);
          alert("Pix copiado com sucesso!");
        }

	</script>
	<script>
        (function (a, b, c, d, e, f, g) {
        a['CsdpObject'] = e; a[e] = a[e] || function () {
        (a[e].q = a[e].q || []).push(arguments)
        }, a[e].l = 1 * new Date(); f = b.createElement(c),
        g = b.getElementsByTagName(c)[0]; f.async = 1; f.src = d; g.parentNode.insertBefore(f, g)
        })(window, document, 'script', '//device.clearsale.com.br/p/fp.js', 'csdp');
        csdp('app', 'wxcftq91ssei846pcv6h');
        csdp('sessionid', '<?=$_SESSION['MY_ID_SESSION'];?>');
	</script>
</body>
</html>
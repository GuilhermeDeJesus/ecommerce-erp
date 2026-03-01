<?php
use Krypitonite\Util\ValidateUtil;
use Configuration\Configuration;
use Krypitonite\Util\DateUtil;

$totalestoque = [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no, minimal-ui" />
<meta charset="UTF-8" />
<base href="/">
<meta name="description"
	content="<?=$data['produto'][0]['descricao'];?>" />
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

<meta property="og:description"
	content="<?=$data['produto'][0]['descricao'];?> | <?=NOME_LOJA;?>">
<meta property="product:availability" content="in stock">
<meta property="product:condition" content="new">
<meta property="product:price:amount"
	content="<?=$data['produto'][0]['valor_venda'];?>" />
<meta property="product:price:currency" content="BRL" />
<meta property="product:retailer_item_id"
	content="<?=$data['produto'][0]['id'];?>">

<meta property="og:locale" content="pt_BR">
<meta property="og:title"
	content="<?=$data['produto'][0]['descricao'];?> | <?=NOME_LOJA;?>">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<meta property="og:url"
	content="https://<?=LINK_LOJA;?>/produto/<?=$data['produto'][0]['id'];?>/<?=$data['produto'][0]['cod_url_produto'];?>">

<title><?=$data['produto'][0]['descricao'];?> | <?=NOME_LOJA;?></title>
<?php require_once 'src/Site/View/Site/css.php';?>
<link rel="stylesheet" href="public/css/chosen.css">
<link rel="stylesheet" href="public/css/ImageSelect.css">
<link rel="stylesheet" href="public/css/detalhes_produto.css">
<script src="public/js/chosen.jquery.js"></script>
<script src="public/js/ImageSelect.jquery.js"></script>
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '<?=$data['produto'][0]['pixel'];?>');
  fbq('track', 'ViewContent');
</script>
<noscript>
	<img height="1" width="1" style="display: none"
		src="https://www.facebook.com/tr?id=<?=$data['produto'][0]['pixel'];?>&ev=ViewContent&noscript=1" />
</noscript>
</head>
<body>
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<?php if ($data['produto'][0]['frete_gratis'] && $data['produto'][0]['descricao_cabecalho'] != '') { ?>
	<div style="position: sticky;">
    	<a href="#" class="announcement-bar announcement-bar--link  hide mobile">
    		<p class="announcement-bar__message"><?=$data['produto'][0]['descricao_cabecalho'];?></p>
    	</a>
	</div>
	<?php }else if($data['produto'][0]['valor_venda'] > VALOR_MINIMO_PARA_FRETE_GRATIS){ ?>
	<div style="position: sticky;">
    	<a href="#" class="announcement-bar announcement-bar--link hide mobile">
    		<p class="announcement-bar__message"><i class="fa fa-truck"></i> <span style="font-size: 12px;">FRETE GRÁTIS PARA TODO BRASIL</span></p>
    	</a>
	</div>
	<?php } ?>
	<div class="content">
		<div class="services-breadcrumb">
			<div class="agile_inner_breadcrumb">
				<div class="container">
					<ul class="w3_short">
						<li><a href="/">Página Inicial <i class="fas fa-angle-right"></i></a></li>
						<li><?=dao('Core', 'Categoria')->getField('descricao', $data['produto'][0]['id_categoria']);?> <i
							class="fas fa-angle-right"></i></li>
						<li><b><?=$data['produto'][0]['descricao'];?></b></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="banner-bootom-w3-agileits">
			<div class="container">
				<div class="col-md-7 single-right-left ">
					<div class="grid images_3_of_2">
						<div class="flexslider">
							<ul class="slides">
    							<?php foreach ($data['images'] as $i) { 
    							    if($i != '.DS_Store' && $i != '.ptp-sync-folder'){
    							    ?>
        							<li
									data-thumb="data/products/<?=$data['produto'][0]['id'];?>/<?=$i;?>">
									<div class="thumb-image">
										<img
											src="data/products/<?=$data['produto'][0]['id'];?>/<?=$i;?>"
											data-imagezoom="true" class="img-responsive" alt="">
									</div>
								</li>
    							<?php } ?>
    							<?php } ?>
    						</ul>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-md-5 single-right-left simpleCart_shelfItem">
					<span class="title-marca" class="no-a"><a class="no-a"  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $data['produto'][0]['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $data['produto'][0]['id_marca']);?></a></span><br>
					<h1 style="font-size: 30px" class="title-product"><?=$data['produto'][0]['descricao'];?></h1>
					<?php if($data['produto'][0]['observacao'] !=''){ ?>
						<p class="short_description_product"><?=$data['produto'][0]['observacao'];?></p>
					<?php } ?>
					<?php if(sizeof($data['comentarios']) != 0){ ?>
					<div class="rating1">
						<span class="starRating"> <input id="rating5" type="radio"
							name="rating" value="5"> <label for="rating5">5</label> <input
							id="rating4" type="radio" name="rating" value="4"> <label
							for="rating4">4</label> <input id="rating3" type="radio"
							name="rating" value="3" checked=""> <label for="rating3">3</label>
							<input id="rating2" type="radio" name="rating" value="2"> <label
							for="rating2">2</label> <input id="rating1" type="radio"
							name="rating" value="1"> <label for="rating1">1</label>
						</span> <span>(<?=sizeof($data['comentarios']);?>)</span>
					</div>
					<?php }else{ ?>
					<div class="rating1" style="display: none;">
						<span class="starRating"> <input id="rating5" type="radio"
							name="rating" value="5"> <label for="rating5">5</label> <input
							id="rating4" type="radio" name="rating" value="4"> <label
							for="rating4">4</label> <input id="rating3" type="radio"
							name="rating" value="3" checked=""> <label for="rating3">3</label>
							<input id="rating2" type="radio" name="rating" value="2"> <label
							for="rating2">2</label> <input id="rating1" type="radio"
							name="rating" value="1"> <label for="rating1">1</label>
						</span>
					</div>
					<?php }?>
    				<?php
                    if ($data['produto'][0]['produto_gratis']) {
                        $data['produto'][0]['valor_venda'] = 0;
                        $data['produto'][0]['valor_sem_oferta'] = 0;
                    }
                    
                    ?>
                    <img src="public/img/ofertadia.png"
						style="margin-top: -10px; width: 100%; display: none;">
					<p style="text-align: left;">
						<?php 
						$desc = (ValidateUtil::paraFloat($data['valor_produto']) / 100);
						$desc = intval($desc * 130) + 0.90;
						$desc = 'R$ '. ValidateUtil::setFormatMoney($desc);
						?>
						<del id="desconto"><?=$desc;?></del>
						<?php if(PARCELAR_SEM_JUROS == 1){?>
						<span class="item_price" id="item_price" style="text-align: left;"><?=$data['valor_produto'];?> </span><br>
						<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=$data['valor_parcela_sem_juros'];?> Sem Juros</span>
						<?php }else if(PARCELAR_SEM_JUROS == 0){?>
						<span class="item_price" id="item_price" style="text-align: left;"><?=$data['valor_produto'];?></span><br>
						<span class="price_ticket" id="price_ticket"><b>R$ <?=descontoBoleto($data['produto'][0]['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?></b> no boleto</span>
						<?php } ?>
					</p>
					<?php if($data['produto'][0]['descricao_despacho'] != ''){ ?>	
					<p style="text-align: left; font-size: 16px; font-weight: 600; display: none;">
						<?=($data['produto'][0]['descricao_despacho']) ? '<span>'.$data['produto'][0]['descricao_despacho'].'</span>' : '';?>
					</p>
					<?php } ?>
					<p id="ultimas_unidades">Restam apenas <?=rand(5, 30);?> unidades</p>
					<p id="temporizador" style="display: none;"></p>
					<p style="font-size: 11px;">Ref <?=$data['produto'][0]['sku'];?></p>
					<div class="single-infoagile">
						<ul>
    						<?=($data['produto'][0]['frete_gratis'] || $data['produto'][0]['valor_venda'] > VALOR_MINIMO_PARA_FRETE_GRATIS) ? '<li><label class="frete-gratis" style="color: #FF8C00;"><i class="fa fa-truck"></i> Frete Grátis</label></li>' : '';?>
    						<?=($data['produto'][0]['prazo_entrega']) ? '<li><label class="frete-gratis">'.$data['produto'][0]['prazo_entrega'].'</label></li>' : '';?>
    						<?php if(sizeof($data['tamanho_produto']) !=0 || sizeof($data['cor_produto']) !=0) { ?>
    						<li>
								<div id="escolher-cor">
								<?php if ($data['produto'][0]['link_compra_upnid'] == NULL){ ?>
    								<?php if(sizeof($data['cor_produto']) !=0) {?><span
    										class="escolher-span">COR:</span> <br> <select name="cor_pro"
    										id="select-cores" class="form-control input-cores my-select"
    										onchange="selecionarCor(this, this);">
    										<option value="<?=$data['cor_produto'][0]['id'];?>" id="<?=$data['cor_produto'][0]['link_venda'];?>" onselect="selecionarCor(<?=$data['cor_produto'][0]['id'];?>, '<?=$data['cor_produto'][0]['link_venda'];?>');"
    											<?php if(file_exists(Configuration::PATH_APPICATION. '/' .$data['cor_produto'][0]['url_img'])){ echo 'data-img-src="'.$data['cor_produto'][0]['url_img'].'"'; } ?>
    											selected><?=$data['cor_produto'][0]['nome'];?></option>
            								<?php foreach ($data['cor_produto'] as $kc => $cor) { ?>
            								<?php if($kc != 0) { ?>
            								<option
    											<?php if(file_exists(Configuration::PATH_APPICATION. '/' .$cor['url_img'])){ echo 'data-img-src="'.$cor['url_img'].'"'; } ?>
    											value="<?=$cor['id'];?>"
    											onselect="selecionarCor(<?=$cor['id'];?>, '<?=$cor['link_venda'];?>');"><?=$cor['nome'];?></option>
                							<?php }?>
                							<?php }?>
        							</select> 
        							<?php }?>
								<?php }else{ ?>
    								<?php if(sizeof($data['cor_produto']) !=0) {?><span
    										class="escolher-span">COR:</span> <br> <select name="cor_pro"
    										id="select-cores" class="form-control input-cores my-select"
    										onchange="selecionarCor(this, this);">
    										<option value="<?=$data['cor_produto'][0]['link_venda'];?>" id="<?=$data['cor_produto'][0]['link_venda'];?>" onselect="selecionarCor(<?=$data['cor_produto'][0]['id'];?>, '<?=$data['cor_produto'][0]['link_venda'];?>');"
    											<?php if(file_exists(Configuration::PATH_APPICATION. '/' .$data['cor_produto'][0]['url_img'])){ echo 'data-img-src="'.$data['cor_produto'][0]['url_img'].'"'; } ?>
    											selected><?=$data['cor_produto'][0]['nome'];?></option>
            								<?php foreach ($data['cor_produto'] as $kc => $cor) { ?>
            								<?php if($kc != 0) { ?>
            								<option
    											<?php if(file_exists(Configuration::PATH_APPICATION. '/' .$cor['url_img'])){ echo 'data-img-src="'.$cor['url_img'].'"'; } ?>
    											value="<?=$cor['link_venda'];?>"
    											onselect="selecionarCor(<?=$cor['id'];?>, '<?=$cor['link_venda'];?>');"><?=$cor['nome'];?></option>
                							<?php }?>
                							<?php }?>
        							</select> 
        							<?php }?>
								<?php } ?>
    							
    							</div><br><br>
								<div id="escolher-tamanho">
    							<?php if(sizeof($data['tamanho_produto']) !=0) { ?><span
										class="escolher-span" style="font-size: 14px; margin-top: 12px;">TAMANHO:</span> <br> <select
										name="tamanho_pro" style="display: none;"
										class="form-control input-tamanhos"
										onclick="selecionarTamanho(this);">
            							<?php foreach ($data['tamanho_produto'] as $tamanho) { ?>
            								<option value="<?=$tamanho['id'];?>"><?=$tamanho['descricao'];?></option>
            							<?php }?>
    								</select> 
    								<?php foreach ($data['tamanho_produto'] as $tamanho) {
    								    $totalestoque[] = $tamanho['estoque'];
        								
    								    if($tamanho['estoque'] > 0){ ?>
        								<button type="button"  class="btn button-tamanho"  value="<?=$tamanho['id'];?>"
    										onclick="selecionarTamanho2(<?=$tamanho['id'];?>, <?=$tamanho['valor'];?>, <?=($data['produto'][0]['lucro'] != NULL) ? $data['produto'][0]['lucro'] : 0;?>, null);">
    										<?=$tamanho['descricao'];?>
    									</button>
										<?php }else if($tamanho['estoque'] == 0){ ?>
										<button type="button"  class="btn button-tamanho-sem-estoque" value="<?=$tamanho['id'];?>">
    										<?=$tamanho['descricao'];?> Sem estoque
    									</button>
										<?php } ?>
										
    								<?php } ?>
    							<?php } ?>
    							</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					<?php if($data['produto'][0]['link_compra_upnid'] == NULL && GATEWAY == 'pagseguro'){ ?>
					<div class="" style="display: none;">
						<div class="sub-some child-momu">
							<ul>
								<li><img src="public/img/pagseguro.png"
									style="width: 15%;" alt="<?=NOME_LOJA;?>"></li>
							</ul>
						</div>
					</div>
					<?php } ?>
					<div>
						<div
							class="top_brand_home_details item_add single-item hvr-outline-out">
							<?php if ($data['produto'][0]['link_compra_upnid'] == NULL){ ?>
							<form action="?m=checkout&c=checkout&a=cart" method="post"
								name="formProduto"
								<?php if(sizeof($data['tamanho_produto']) != 0){ ?>
								onsubmit="return validarCompra()" <?php } ?> id="form-produto">
								<input type="hidden" name="cod_produto" value="<?=$data['produto'][0]['id'];?>" /> 
								<input type="hidden" id="cor" name="cor" value="<?=$data['cor_produto'][0]['id'];?>" />
								<input type="hidden" id="tamanho" name="tamanho" value="" />

									<?php if($data['produto'][0]['ativo'] && $data['produto'][0]['link_compra_upnid'] == NULL){ ?>
        							<?php if(array_sum($totalestoque) == 0){ ?>
                    				<!-- <button type="button" class="btn-produto-indisponivel">PRODUTO
										INDISPONÍVEL</button>  -->
									<button type="submit" class="button-comprar">
    									<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
    									COMPRAR
									</button>	
                    				<?php }else { ?>
        							<button type="submit" class="button-comprar">
    									<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
    									COMPRAR
									</button>
									<?php } ?>
								<p class="selo-compra-segura"><i class="fa fa-lock"></i> Compra 100% segura</p>
								
								<!-- LINK INÚTIL, MAS FAVOR, MANTER -->
								<a href="#" id="link_upnid"></a>   
								
								<?php }else { ?>
								<button type="button" class="btn-produto-indisponivel">PRODUTO
									INDISPONÍVEL</button>
								<?php } ?>	    								
    						</form>
    						<?php } else if($data['produto'][0]['ativo'] && $data['produto'][0]['link_compra_upnid'] != NULL){ ?>
    							<?php if(sizeof($data['tamanho_produto']) !=0){ ?>
    							<a href="<?=$data['tamanho_produto'][0]['link_venda'];?>" id="link_upnid">
    								<button class="button-comprar">
    									<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
    									COMPRAR
    								</button>
								</a>    							
    							<?php }else{ ?>
    							<?php if(isset($data['error']) && $data['error'] == true){ ?>
                        			<div class="alert alert-warning" role="alert">
                        				<?=$data['msg'];?>
                        			</div>
                    			<?php } ?>
    							<form action="?m=checkout&c=checkout&a=validarCompraSimples" method="post">
    								<input type="hidden" name="link_upnid" id="link_upnid" value="" />
    								<input type="hidden" name="id_produto" value="<?=$data['produto'][0]['id'];?>" />
        							<?php if($data['produto'][0]['cupom_desconto']){ ?>
        							<div>
            							<br>
                    						<ul><li ><div style="background: #FF8C00; padding: 10px; font-size: 14px; text-align: center; color: #FFF; width: 200px; font-weight: 600;">CUPOM DE DESCONTO</div></li><li><input type="text" placeholder="" name="cupom" style="background-color: #fff; text-transform: uppercase; border-radius: 0px; ;border: 1px solid #FF8C00; font-weight: 300; color: #000; text-align: center; padding: 15px 10px; height: 25px; width: 200px; flex: 1 1 auto;"></li>
                    					</ul>
                    				</div>
                    				<?php } 
                    				?> 
                    				
    								<button class="button-comprar" type="submit">
    									<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
    									COMPRAR
    								</button>
								</form>
								<div>
                					<br>
                					<h5 style="text-align: center;">
                						<span class="item_price" id="item_price">COMPRAS NO CARTÃO SÃO PROCESSADAS
                							E ENVIADAS MAIS RAPIDAMENTE. <i class="fa fa-credit-card"
                							aria-hidden="true"></i>
                						</span>
                					</h5>
                				</div>
    							<?php } ?>
    						<?php } ?>
				  			<div>
				  				<br><br>
                  				<strong><img
                  					src="https://cdn.shopify.com/s/files/1/1980/0295/files/Badges_large_e0a5755b-ec43-4ac6-a85e-c5dbc6afb609_large.png?v=1545848930"
                  					alt=""
                  					style="display: block; margin-left: auto; margin-right: auto; width: 330px; display: none;"></strong>
                  			</div>
						</div>
					</div>
    				<?php if(!$data['produto'][0]['frete_gratis']){ ?>
    				<div class="frete-dv">
						<br> <span class="span-frete">Calcule Frete:</span>
						<form action="#" id="form-calcula-entrega" method="POST">
							<table class="TableUI-o6rohr-0 kJZaBa">
								<tr>
									<td width="300"><input type="hidden" name="produto"
										value="<?=$data['produto'][0]['id'];?>" /> <input
										name="cep_destino" placeholder="Ex: 00000-000"
										id="frete-input" class="form-control input-frete2"></td>
									<td><button id="calcular" class="change" type="button">OK</button></td>
								</tr>
							</table>
						</form>
						<div class="load-img">
							<img src="public/img/loading3.gif" alt="<?=NOME_LOJA;?>"
								style="width: 50px; height: 50px; margin-top: 10px;"> <span
								style="font-size: 13px; color: #666;">Calculando frete e prazo</span>
						</div>
						<table class="TableUI-o6rohr-0 kJZaBa" id="result-frete">
							<thead class="THead-sc-6kkk7q-0 cGBmyV" style="display: none;">
								<tr class="Tr-sc-6kkk7q-3 bwaahg">
									<th class="Th-sc-6kkk7q-4 hFLQFO" type="head"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz">FRETE</span></th>
									<th class="Th-sc-6kkk7q-4 hFLQFO" type="head"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz">ENTREGA</span></th>
								</tr>
							</thead>
							<tbody class="TBody-sc-6kkk7q-2 gkpKTw">
								<tr class="Tr-sc-6kkk7q-3 bwaahg" id="tr-pac">
									<td class="Td-sc-6kkk7q-5 cIBiSj"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz" id="frete_pac"></span></td>
									<td class="Td-sc-6kkk7q-5 cIBiSj"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz" id="prazo_pac"></span></td>
								</tr>
								<tr class="Tr-sc-6kkk7q-3 bwaahg" id="tr-sedex">
									<td class="Td-sc-6kkk7q-5 cIBiSj"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz" id="frete_sedex"></span></td>
									<td class="Td-sc-6kkk7q-5 cIBiSj"><span
										class="TextUI-sc-1hrwx40-0 hDvhAz" id="prazo_sedex"></span></td>
								</tr>
							</tbody>
						</table>
						<?php 
						$mostrar = TRUE;
						if(sizeof($data['tamanho_produto']) !=0 && $mostrar == TRUE){ ?>
						<br>
						<h5 style="color: #d33665;">MEDIDAS APROXIMADAS POR TAMANHO:</h5>
						<table class="table" style="width: 70%;">
							<tr>
								<th style="width: 40px;">PP</th>
								<th style="width: 40px;">P</th>
								<th style="width: 40px;">M</th>
								<th style="width: 40px;">G</th>
								<th style="width: 40px;">GG</th>
							</tr>
							<tbody>
								<tr>
									<th>34</th>
									<td>36-38</td>
									<td>40-42</td>
									<td>42-44</td>
									<td>46-50</td>
								</tr>
							</tbody>
						</table>
						<a href="#" class="btn btn-primary noboxbor" data-toggle="modal"
							style="background: #d33665; color: #FFF;"
							data-target="#medidasTamanhos">Descubra o seu tamanho</a>
						<div class="modal fade" id="medidasTamanhos" tabindex="-1"
							role="dialog" aria-labelledby=""
							aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 style="color: #d33665;">MEDIDAS APROXIMADAS POR TAMANHO:</h5>
										<button type="button" class="close" data-dismiss="modal"
											aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<table class="table">
											<thead>
												<tr>
													<th class="sft-ms-country type2">tamanho</th>
													<th class="sft-ms-sizes type2">PP</th>
													<th class="sft-ms-sizes type2" colspan="2">P</th>
													<th class="sft-ms-sizes type2" colspan="2">M</th>
													<th class="sft-ms-sizes type2" colspan="2">G</th>
													<th class="sft-ms-sizes type2" colspan="2">GG</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">tamanho BR</td>
													<td class="sft-ms-sizes" data-th="PP">34</td>
													<td class="sft-ms-sizes" data-th="P">36</td>
													<td class="sft-ms-sizes" data-th="P">38</td>
													<td class="sft-ms-sizes" data-th="M">40</td>
													<td class="sft-ms-sizes" data-th="M">42</td>
													<td class="sft-ms-sizes" data-th="G">44</td>
													<td class="sft-ms-sizes" data-th="G">46</td>
													<td class="sft-ms-sizes" data-th="GG">48</td>
													<td class="sft-ms-sizes" data-th="GG">50</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">busto BR</td>
													<td class="sft-ms-sizes" data-th="PP">76cm</td>
													<td class="sft-ms-sizes" data-th="P">82cm</td>
													<td class="sft-ms-sizes" data-th="P">86cm</td>
													<td class="sft-ms-sizes" data-th="M">90cm</td>
													<td class="sft-ms-sizes" data-th="M">94cm</td>
													<td class="sft-ms-sizes" data-th="G">98cm</td>
													<td class="sft-ms-sizes" data-th="G">102cm</td>
													<td class="sft-ms-sizes" data-th="GG">106cm</td>
													<td class="sft-ms-sizes" data-th="GG">110cm</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">cintura BR</td>
													<td class="sft-ms-sizes" data-th="PP">58cm</td>
													<td class="sft-ms-sizes" data-th="P">62cm</td>
													<td class="sft-ms-sizes" data-th="P">66cm</td>
													<td class="sft-ms-sizes" data-th="M">70cm</td>
													<td class="sft-ms-sizes" data-th="M">74cm</td>
													<td class="sft-ms-sizes" data-th="G">78cm</td>
													<td class="sft-ms-sizes" data-th="G">82cm</td>
													<td class="sft-ms-sizes" data-th="GG">86cm</td>
													<td class="sft-ms-sizes" data-th="GG">90cm</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">quadril BR</td>
													<td class="sft-ms-sizes" data-th="PP">84cm</td>
													<td class="sft-ms-sizes" data-th="P">86cm</td>
													<td class="sft-ms-sizes" data-th="P">90cm</td>
													<td class="sft-ms-sizes" data-th="M">94cm</td>
													<td class="sft-ms-sizes" data-th="M">98cm</td>
													<td class="sft-ms-sizes" data-th="G">102cm</td>
													<td class="sft-ms-sizes" data-th="G">106cm</td>
													<td class="sft-ms-sizes" data-th="GG">110cm</td>
													<td class="sft-ms-sizes" data-th="GG">114cm</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">Tamanho EUA</td>
													<td class="sft-ms-sizes" data-th="PP">XS</td>
													<td class="sft-ms-sizes" colspan="2" data-th="P">S</td>
													<td class="sft-ms-sizes" colspan="2" data-th="M">M</td>
													<td class="sft-ms-sizes" colspan="2" data-th="G">L</td>
													<td class="sft-ms-sizes" colspan="2" data-th="GG">XL</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">tamanho EUA</td>
													<td class="sft-ms-sizes" data-th="PP">2</td>
													<td class="sft-ms-sizes" data-th="P">4</td>
													<td class="sft-ms-sizes" data-th="P">6</td>
													<td class="sft-ms-sizes" data-th="M">8</td>
													<td class="sft-ms-sizes" data-th="M">10</td>
													<td class="sft-ms-sizes" data-th="G">12</td>
													<td class="sft-ms-sizes" data-th="G">14</td>
													<td class="sft-ms-sizes" data-th="GG">16</td>
													<td class="sft-ms-sizes" data-th="GG">18</td>
												</tr>
												<tr>
													<td class="sft-ms-country" data-th="TAMANHO">calça EUA</td>
													<td class="sft-ms-sizes" data-th="PP">23</td>
													<td class="sft-ms-sizes" data-th="P">24</td>
													<td class="sft-ms-sizes" data-th="P">26</td>
													<td class="sft-ms-sizes" data-th="M">28</td>
													<td class="sft-ms-sizes" data-th="M">29/30</td>
													<td class="sft-ms-sizes" data-th="G">31/32</td>
													<td class="sft-ms-sizes" data-th="G">33</td>
													<td class="sft-ms-sizes" data-th="GG">34</td>
													<td class="sft-ms-sizes" data-th="GG">36</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary"
											data-dismiss="modal">Fechar</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
    				<?php } ?>
    			</div>
				<div class="clearfix"></div>
    			<?php if($data['produto'][0]['sobre'] != ''){?>
    			<br>
				<div class="sobre">
					<div class="tab">
						<button class="tablinks"
							onclick="openDetalhes(event, 'Descricao')"><span class="productDetalhes">Descrição</span></button>
					</div>
					<div id="Descricao" class="tabcontent">
        			<?php
                    $text = trim($data['produto'][0]['sobre']);
                    $texts = array(
                        "",
                    );
            
                    $replace = "<p>";
                    $newstr = str_replace('style=', 'txt=', str_replace($texts, $replace, $text));
                    // echo '<span class="sobre-product">'.trim($newstr).'</span>';
                    echo $newstr;
                    ?>	
                    </div>
				</div>
				<br>
				<br>
                <?php } ?>
    		</div>
		</div>
		<div class="sobre-class"></div>
		<!-- COMENTARIO -->
		<div class="container">
			<button class="btn" id="btn-end" style="outline: none; box-shadow: none;">
				<img class="img-add-end hide" alt="<?=NOME_LOJA;?>"
					src="public/img/plus-512.png">Seja a primeira pessoa a avaliar este produto!
			</button>
			<br> <br>
			<div class="new-adress imageLink">
				<div id="form-end">
					<form method="post" action="?m=comentario&c=comentario&a=addComent"
						enctype="multipart/form-data">
						<div class="form-group">
							<input type="hidden" value="<?=$data['produto'][0]['id'];?>"
								name="id_produto"> <label for="destinatario">Seu Nome</label> <input
								type="text" class="form-control input-end" id="nome" name="nome"
								aria-describedby="emailHelp" placeholder="Seu Nome" required="">
						</div>
						<div class="form-group">
							<label for="cep">Seu E-mail</label> <input type="text"
								class="form-control input-end" id="email" name="email"
								aria-describedby="emailHelp" placeholder="Seu E-mail"
								required="">
						</div>
						<div class="form-group">
							<label for="cep">Comentário</label>
							<textarea class="form-control input-end" id="texto" name="texto"
								required=""></textarea>
						</div>
						<div class="form-group">
							<label for="cep">Foto</label> <input type="file" style=""
								class="form-control input-end" id="foto" name="foto"
								aria-describedby="emailHelp" required="">
						</div>
						<button type="submit" class="btn btn-danger btn-add-endereco">SALVAR
						</button>
					</form>
				</div>
			</div>
			<div class="row">
    		<?php foreach ($data['comentarios'] as $comentario) { ?>
    		<div class="col-sm-3 col-md-3">
					<div class="thumbnail">
						<img data-toggle="modal"
							data-target="#coment-<?=$comentario['id'];?>"
							class="media-object img-coment"
							src="data/comentarios/<?=$comentario['id_produto'] ?>/<?=$comentario['img'] ?>"
							alt="<?=$comentario['nome'];?>">
						<div class="caption">
							<h3
								style="font-size: 14px; font-weight: 600; color: #000; text-align: center;"><?=$comentario['nome'];?></h3>
							<div class="alireview-national-info">
								<center><span class="ali-flag-slc br"><img class="ali-flag-slc"
									src="public/img/brazil.png" align="middle"></span></center>
							</div>
							<p id="comentario"><?=$comentario['texto'];?></p>
							<span class="alireview-icon-like"></span> <span
								class="alireview-icon-unlike"></span>
							<p id="data-coment"><?=DateUtil::getDateDMY($comentario['date_create']);?></p>
							<br>
						</div>
					</div>
				</div>
				<div class="modal fade" id="coment-<?=$comentario['id'];?>"
					tabindex="-1" role="dialog"
					aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<ul class="media-list">
									<li class="media">
										<div class="media-left">
											<a href="#"> <img class="media-object"
												style="border-radius: 7px; width: 300px;"
												src="data/comentarios/<?=$comentario['id_produto'] ?>/<?=$comentario['img'] ?>"
												alt="<?=$comentario['nome'];?>">
											</a>
										</div>
										<div class="media-body">
											<h3
												style="font-size: 15px; font-weight: 600; color: #000; text-align: center;"><?=$comentario['nome'];?></h3>
											<div class="alireview-national-info">
												<span class="ali-flag-slc br"><img class="ali-flag-slc"
													src="public/img/brazil.png"></span>
											</div>
											<p id="comentario"><?=$comentario['texto'];?></p>
											<span class="alireview-icon-like"></span> <span
												class="alireview-icon-unlike"></span>
											<p id="data-coment"><?=DateUtil::getDateDMY($comentario['date_create']);?></p>
											<br>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
            <?php } ?>
            </div>
		</div>
		<!-- COMENTARIO -->
    	<?php if(sizeof($data['produtos_relacionados']) != 0){ ?>
    	<div class="featured-section" id="projects">
			<div class="container">
				<div class="content-bottom-in">
					<h3 class="heading-tittle">Produtos relacionados</h3>
					<ul id="flexiselDemo1">
    					<?php
                        foreach ($data['produtos_relacionados'] as $prds) {
                            $imgs = getImagensProduto($prds['id']);
                            ?>
        					<li>
							<div class="w3l-specilamk">
								<div class="speioffer-agile">
									<a
										href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>">
										<img src="data/products/<?=$prds['id'];?>/principal.jpg"
										alt="<?=$prds['descricao'];?>">
									</a>
								</div>
								<div class="product-name-w3l">
									<h3 style="margin-top: 160px;">
										<a class="asodjas" href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"><?=$prds['descricao'];?></a>
									</h3>
									<div class="w3l-pricehkj">
										<h6>R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></h6><span style="padding-top: 7px;">ou <?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda'] / QTD_PARCELAS_SEM_JUROS);?></span>
									</div>
									<div
										class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
										<a href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"><button type="button" class="button-comprar-default">
											<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
												dar uma olhadinha
										</button></a>
									</div>
								</div>
							</div>
						</li>
    					<?php } ?>
    				</ul>
				</div>
			</div>
		</div>
    	<?php } ?>
	</div>
	<div style="display: none;">
		<a
			href="https://api.whatsapp.com/send?phone=5561999237374&text=Queria mais informações sobre o produto"
			target="_blank"><img class="whatsapp" src="public/img/whatsapp.png" /></a>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
	<script>
    var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
    
    var x = setInterval(function() {
    
      var now = new Date().getTime();
        
      var distance = countDownDate - now;
        
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      document.getElementById("temporizador").innerHTML = "<div id='hours'>00 Hours</div>"
      + "<div id='hours'>" + minutes + " Min </div>" + "<div id='hours'>" + seconds + " Seconds</div>";
        
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("temporizador").innerHTML = "EXPIRED";
      }
    }, 1000);
    </script>
	<script>
	$(document).ready(function(){
	 	$("#form-end").hide();
	    $("#btn-end").click(function(){
	        $("#form-end").toggle();
	    });
	});
	
    var acc = document.getElementsByClassName("accordion");
    var i;
    
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight){
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        } 
      });
    }
    
	function validarCompra(){
		var x = document.forms["formProduto"]["tamanho"].value;
    	if (x == "") {
        	alert('Selecione um tamanho para este produto');
        	return false;
        }
	}
	
    function openDetalhes(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
	<script>
    	$(document).ready(function () {
    		$(".my-select").chosen();
    		$('.load-img').css('display', 'none');
			$('.load-img').hide();
    		$('#result-frete').css('display', 'none');
    	    $("#calcular").on('click', function(){
    	    	$('.load-img').show();
    	    	$('.load-img').css('display', 'inline-block');
    	 		setTimeout(function(){ 
    	 			$.ajax({
        				type : 'POST',
        			 	beforeSend: function(){},
        				dataType : "text",
        				async : false,
        				url : "?m=frete&c=correios&a=calcularPrecoPrazo",
        				data : {
         					"data" : JSON.stringify($('#form-calcula-entrega').serializeArray()),
        				},					  
        				success: function(data){
        					$('.load-img').css("display", "none");
    						$('.load-img').hide();
                            $('#result-frete').css('display', 'inline-block');
                            var responseJson = JSON.parse(data);

							if(responseJson[0].prazo_pac == '' || responseJson[0].prazo_pac == null){
	                            $('#tr-pac').css('display', 'none');
							}
                            document.getElementById('prazo_pac').innerHTML = responseJson[0].prazo_pac;
                            document.getElementById('frete_pac').innerHTML = responseJson[0].frete_pac;

                            document.getElementById('prazo_sedex').innerHTML = responseJson[1].prazo_sedex;
                            document.getElementById('frete_sedex').innerHTML = responseJson[1].frete_sedex;
        				  },
        			});
    	 		}, 100);
    	    });
    	});

		function selecionarCor(cor, link_venda){
			var _link_venda = (cor.value || cor.options[cor.selectedIndex].value); 
			document.getElementById("link_upnid").value = _link_venda;
			document.getElementById("cor").value = _link_venda;
		}

		function selecionarTamanho(tamanho){
			var _tamanho = (tamanho.value || tamanho.options[tamanho.selectedIndex].value); 
			document.getElementById("tamanho").value = _tamanho;
		}

		function numberToReal(numero) {
		    var numero = numero.toFixed(2).split('.');
		    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('');
		    return numero.join(',');
		}

		function selecionarTamanho2(idTamanho, preco, lucro = 0, link_venda = null){
			var _desconto = (preco / 100) * 130;
			document.getElementById("tamanho").value = idTamanho;
			document.getElementById("desconto").innerHTML = 'R$ ' + parseInt(numberToReal(_desconto)) + ',90';
			document.getElementById("item_price").innerHTML = 'R$ ' + numberToReal(preco);
			
			var priceTicket = 'R$ ' + numberToReal((preco / 100) * (100 - 10));
			document.getElementById("price_ticket").innerHTML = priceTicket + ' no boleto';
			
			if(link_venda != ""){
				document.getElementById("link_upnid").href = link_venda; 
			}
		}

	</script>
</body>
</html>
<?php
use Krypitonite\Util\ValidateUtil;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<meta charset="UTF-8">

<?php if($_SERVER['REQUEST_URI']=='/categoria/perfumes-masculinos'){?>
<title>Perfumes Importados Masculinos – Comprar Perfume para Homem | Shopvitas</title>
<meta name="description" content="Compre linha exclusiva de perfumes masculinos importados na Shopvitas! Compre perfumes masculinos das melhores marcas. 100% original. Frete grátis a partir de R$ 150." />

<?php }elseif($_SERVER['REQUEST_URI'] =='/categoria/perfume-unissex'){ ?>
<title>Perfume Unissex Importados – Melhores Preços | Shopvitas</title>
<meta name="description" content="Compre linha exclusiva de perfumes importados unissex na Shopvitas! Compre perfumes unissex das principais marcas. 100% original. Frete grátis a partir de R $ 150." />

<?php }elseif($_SERVER['REQUEST_URI'] =='/categoria/kits-masculinos'){ ?>
<title>Kits de Perfumes Masculinos Importados – Melhores Preços | Shopvitas</title>
<meta name="description" content="Compre uma linha exclusiva de kits de perfumes masculinos importados na Shopvitas! Compre kits de perfumes masculinos das principais marcas. 100% original. Frete grátis a partir de R$ 150." />

<?php }elseif($_SERVER['REQUEST_URI'] =='/categoria/perfumes-femininos'){ ?>
<title>Perfumes Importados Femininos – Comprar Perfume Feminino | Shopvitas</title>
<meta name="description" content="Compre linha exclusiva de perfumes femininos importados na Shopvitas! Compre perfumes femininos das melhores marcas. 100% original. Frete grátis a partir de R$150." />

<?php }else{?>
<title><?=(noSeo($data['categoria_selececionada'] != NULL)) ? ucwords(noSeo($data['categoria_selececionada']))." | ". noSeo(noSeo($data['categoria_pai'])) : ''; ?> <?=NOME_LOJA;?></title>
<meta name="description" content="<?=TAG_DESCRIPTION;?> ✓ Entrega Rápida. ✓ Melhor Preço | <?=noSeo($data['categoria_selececionada']);?>." />
<?php }?>
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no, minimal-ui" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8" />
<base href="/">

<meta name="keywords"
	content="<?=TAG_KEYWORDS;?>" />
<link rel="canonical" href="<?php echo $actual_link;?>" />
<?php if($actual_link=='https://www.shopvitas.com.br/marca/antonio-bandeiras/perfumes/perfumes-femininos' || $actual_link=='https://www.shopvitas.com.br/marca/antonio-bandeiras/perfumes/perfumes-masculinos' || $actual_link=='https://www.shopvitas.com.br/categoria/perfumes/kits-masculinos' ){?>
<meta name="robots" content="noindex, nofollow" />
<?php }elseif($actual_link=="https://www.shopvitas.com.br/marca/arsenal/perfumes" || $actual_link=="https://www.shopvitas.com.br/marca/azzaro/corpo" || $actual_link=="https://www.shopvitas.com.br/marca/azzaro/corpo--banho"){?>
<meta name="robots" content="noindex, nofollow" />
<?php }else{?>
<meta name="robots" content="index, follow" />
<?php }?>
<meta name="rating" content="general" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="HandheldFriendly" content="True" />

<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style"
	content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="<?=NOME_LOJA;?>">
<meta name="mobile-web-app-capable" content="yes">

<meta property="og:type" content="product.group">
<meta property="og:description"
	content="<?=TAG_DESCRIPTION;?> ✓ Entrega Rápida. ✓ Melhor Preço | <?=noSeo($data['categoria_selececionada']);?>."> 
<meta  
	property="og:locale" content="pt_BR">
<meta property="og:title"
	content="<?=$data['categoria_pai'];?> | <?=NOME_LOJA;?>">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<meta property="og:url" content="https://<?=LINK_LOJA;?>/">

<meta name="insight-app-sec-validation"
	content="2c70914d-09ae-46a9-92e8-e7f92f9e3d24">
<?php require_once 'src/Site/View/Site/css.php';?>
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
  <?php if($data['customer_search'] == FALSE){ ?>
  fbq('track', 'PageView');
  <?php }else if($data['customer_search'] == TRUE){ ?>
  fbq('track', 'Search');
  <?php } ?>
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=<?=PIXEL_FACEBOOK?>&ev=PageView&noscript=1"
/></noscript>
</head>
<body>
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<div class="_ofertas no-mobile">
		<a href="prazos-e-entregas"><img style="width: 100%;"
			alt="Ofertas <?=NOME_LOJA;?>"
			src="public/img/ssssaaa.png"></a>
	</div>
	<div class="services-breadcrumb">
		<div class="agile_inner_breadcrumb">
			<div class="container">
				<ul class="w3_short">
					<li><a href="#">Página Inicial <i class="fas fa-angle-right"></i><?=noSeo($data['categoria_pai']);?></a></li>
					<li><?=noSeo($data['categoria_selececionada']);?></li>
					<li><?=($data['marca_selececionada'] != NULL) ? "</a><i class='fas fa-angle-right'></i>". noSeo($data['marca_selececionada']) : ''; ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="_ofertas no-mobile hide">
		<a href="prazos-e-entregas"><img class="img-ofertas"
			alt="Ofertas <?=NOME_LOJA;?>"
			src="public/img/ofertas_voce_sempre_bela.jpeg"></a>
	</div>
	<div class="ads-grid">
		<div class="container">
			<div class="side-bar col-md-2 no-mobile">
				<div class="left-side">
					<?php if($data['categoria_pai'] && sizeof($data['categoria_filhos']) != 0){ ?>
					<h5><b>Categorias</b></h5>
					<ul class="ul-categoria">
						<li><span class="span"><a
								href="categoria/<?=seo($data['categoria_pai']);?>" class="no-a"><?=noSeo($data['categoria_pai']);?></a></span>
							<ul class="filhos">
    							<?php
                                    if ($data['categoria_filhos'] && sizeof($data['categoria_filhos']) != 0) {
                                        foreach ($data['categoria_filhos'] as $cat) {
                                            $link_categoria = "";
                                            if ($cat != NULL) {
                                                $link_categoria .= "/" . seo($cat);
                                            }
                                            ?>
        						    <li><a
									href="categoria/<?=seo($data['categoria_pai']);?><?=$link_categoria;?>"><span
										class="span"><?=noSeo($cat);?></span><span
										class="total_produto">(<?=dao('Produto', 'Produto')->_totalProdutoPorNomeCategoria(noSeo($cat));?>)</span></a></li>
        							<?php } ?>
        						<?php } ?>
							</ul></li>
					</ul>
					<hr />
					<?php } ?>
					<?php if(sizeof($data['marcas_unicas']) != 0){ ?>
					<h5><b>Marcas</b></h5>
					<ul class="ul-categoria">
						<li>
							<ul class="filhos">
    							<?php foreach ($data['marcas_unicas'] as $marca) { ?>
	    						<?php
                                    if (dao('Core', 'Produto')->countOcurrence('*', [
                                        'id_marca',
                                        '=',
                                        dao('Site', 'Marca')->getIdPorNome($marca)
                                    ]) != 0) {
                                        $link_marca = "marca/";
                                        $link_marca .= seo($marca);
                        
                                        if ($data['categoria_pai']) {
                                            $link_marca .= "/" . seo($data['categoria_pai']);
                                        }
                        
                                        if ($data['categoria_selececionada']) {
                                            $link_marca .= "/" . seo($data['categoria_selececionada']);
                                        }
                                        ?>
        						    <li><a href="<?=$link_marca;?>"><span class="span"><?=noSeo($marca);?></span><span
										class="total_produto"></span></a></li>
        							<?php } ?>	
    							<?php } ?>						
							</ul>
						</li>
					</ul>
					<?php } ?>
				</div>
			</div>
			<div class="agileinfo-ads-display col-md-10 w3l-rightpro">
				<div class="filtros">
					<div class="marcas">
    					<?php foreach ($data['marcas_unicas'] as $marca) { ?>
    						<?php
                                if (dao('Core', 'Produto')->countOcurrence('*', [
                                    'id_marca',
                                    '=',
                                    dao('Site', 'Marca')->getIdPorNome($marca)
                                ]) != 0) {
                                    $link = "marca/";
                                    $link .= seo($marca);
                    
                                    if ($data['categoria_pai']) {
                                        $link .= "/" . seo($data['categoria_pai']);
                                    }
                    
                                    if ($data['categoria_selececionada']) {
                                        $link .= "/" . seo($data['categoria_selececionada']);
                                    }
                    
                                    ?>
        				    	<button class="no-btn">
							<div class="f-marca">
								<a class="no-a" href="<?=$link;?>"><?=noSeo($marca);?></a>
							</div>
						</button>
        					<?php } ?>
    					<?php } ?>
    				</div>
					<div class="preco">
						<span class="txt-preco">Preço</span>
						<form
							action="?m=produto&c=produto&a=lista&cat=<?=seo($data['marca_selececionada']);?>&father=<?=seo($data['categoria_pai']);?>"
							method="post">
							<input name="min" class="min-preco" type="text" placeholder="min">
							-<input name="max" class="max-preco dez-left" type="text"
								placeholder="max">
							<span class="total-resultados">(<?=sizeof($data['produtos'])?>) <?=sizeof($data['produtos']) == 1 ? 'Resultado' : 'Resultados'; ?></span>
							<button class="btn-filtro" type="submit">OK</button>
						</form>
					</div>
				</div>
				<div class="wrapper">
					<div class="row">
						<?php
                        if (sizeof($data['produtos']) != 0) {
                            foreach ($data['produtos'] as $produto) {
                                $imgs = getImagensProduto($produto['id']);
                                ?>
                                <!-- VERSION MOBILE -->
                                <div class="col-xs-3 product-men hide mobile">
            						<div class="men-pro-item simpleCart_shelfItem">
            							<div class="men-thumb-item">
            								<div>
            									<table>
            									<tr>
            									<td width="150">
            									<a
            										href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><img
            										class="per-80 img-pro"  id="img-produto-mobile"
            										src="data/products/<?=$produto['id'];?>/principal.jpg"
            										alt="<?=$produto['descricao'];?>"></a>
            										</td><td>
            										<div class="item-produto">
                        								<h4>
                        									<a
                        										href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><?=ucwords($produto['descricao']);?></a>
                        								</h4>
                        									<?php if($produto['frete_gratis']){ ?>
                        										<span class="color-marca">Frete Grátis</span>
                        									<?php } ?>
                        									<?php if($produto['produto_gratis']){ ?>
                        									<?php $produto['valor_venda'] = 0; ?>
                        										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
                        									<?php } ?>
                        									<div class="info-product-price">
                        										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
                        									<br><?php } ?>
                        										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']);?></span><br>
                        										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
                        								</div>
                        								<div style="display: none;"
                        									class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
                        										<?php if($produto['ativo']){ ?>
                        										<a
                        										href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><button
                        											type="button" name="" class="button-comprar-default">
                        											<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                        											comprar
                        										</button></a>
                        										<?php }else { ?>
                        										<button type="button" class="btn-produto-indisponivel">
                        										produto indisponível</button>
                        										<?php } ?>
                    									</div>
                    								</div>
                    								</td>
                    								</tr>
                								</table>
            								</div>
            								<div class="men-cart-pro">
            									<div class="inner-men-cart-pro">
            										<a
            											href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"
            											class="link-product-add-cart">VER MAIS</a>
            									</div>
            								</div>
            							</div>
            							<div class="item-info-product" style="display: none;">
            								<h4>
            									<a
            										href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
            										<span class="color-marca">Frete Grátis</span> - 
            									<?php } ?><?=$produto['descricao'];?></a>
            								</h4>
            									<?php if($produto['produto_gratis']){ ?>
            									<?php $produto['valor'] = 0; ?>
            										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
            									<?php } ?>
            									<div class="info-product-price">
            										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
            									<br><?php } ?>
            										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor']);?></span>
            									</div>
            								<div
            									class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
            										<?php if($produto['ativo']){ ?>
            										<a
            										href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><button
            											type="button" name="" class="button-comprar-default">
            											<i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
            											comprar
            										</button></a>
            										<?php }else { ?>
            										<button type="button" class="btn-produto-indisponivel">
            										produto indisponível</button>
            										<?php } ?>
            									</div>
            							</div>
            						</div>
            					</div>
                                <!-- END VERSION MOBILE -->
        						<div class="col-xs-4 product-men no-mobile" id="product-men">
        							<div class="men-pro-item simpleCart_shelfItem">
        								<div class="men-thumb-item">
        									<div>
        										<a
        											href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><img
        											class="per-80 img-destaque"
        											src="data/products/<?=$produto['id'];?>/principal.jpg"
        											alt="<?=$produto['descricao'];?>"></a>
        									</div>
        									<div class="men-cart-pro">
        										<div class="inner-men-cart-pro">
        											<a
        												href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"
        												class="link-product-add-cart">VER MAIS</a>
        										</div>
        									</div>
        								</div>
        								<div class="item-info-product">
        									<h4>
        										<a
        											href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
        										<span class="color-marca">Frete Grátis</span> - 
        									<?php } ?><?=$produto['descricao'];?></a>
        									</h4>
        									<?php if($produto['produto_gratis']){ ?>
        									<?php $produto['valor_venda'] = 0; ?>
        										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
        									<?php } ?>	
        									<div class="info-product-price">
        										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
        										<br><?php } ?>
        										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']);?></span><br>
        										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
        									</div>
        									<div
        										class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
        										<?php if($produto['ativo']){ ?>
        											<a
        												href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>">
        												<button type="button" class="button-comprar-default"><i
        												class="fa fa-cart-arrow-down" aria-hidden="true"></i> comprar</button></a>
        										<?php }else { ?>
        										<button type="button" class="btn-produto-indisponivel">
        											produto indisponível</button>
        										<?php } ?>										
        									</div>
        								</div>
        							</div>
        						</div>
							<?php } ?>
						<?php }else{ ?>
							<br>
						<h4>Ops! Nenhum resultado encontrado.</h4>
						<?php }?>
						<div class="clearfix"></div>
					</div>
					<?php if(sizeof($data['produtos']) != 0){ ?>
					<div class="paginacao">
						<?=$data['paginacao'];?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
		<?php if($actual_link=='https://www.shopvitas.com.br/?m=produto&c=produto&a=lista&father=perfumes-femininos'){ ?>
	    <div class="container hide">
	        <div class="row">
	            <div class="col-md-12">
	                <h1><span style="font-weight: 400;">Adquira </span><span style="font-weight: 400;">perfumes femininos</span><span style="font-weight: 400;"> de marcas renomadas&nbsp;</span></h1>
                    <p><span style="font-weight: 400;">Comprar perfume online pode n&atilde;o parecer a tarefa mais f&aacute;cil diante da quantidade de produtos que existem online. No entanto, algumas lojas facilitam muito a escolha dos clientes, atrav&eacute;s de descri&ccedil;&otilde;es detalhadas dos perfumes, e de um suporte eficiente.</span></p>
                    <p><span style="font-weight: 400;">Esse &eacute; o caso do nosso site, atrav&eacute;s do qual voc&ecirc; encontrar&aacute; o </span><span style="font-weight: 400;">perfume importado feminino</span><span style="font-weight: 400;"> da marca que deseje. Assim voc&ecirc; ter&aacute; o aroma perfeito para combinar com os seus looks e para fazer sucesso em qualquer ocasi&atilde;o.</span></p>
                    <p><span style="font-weight: 400;">Um perfume &eacute; sempre uma &oacute;tima escolha, pois as fragr&acirc;ncias s&atilde;o capazes de trazer &agrave; mem&oacute;ria lembran&ccedil;as e de criar la&ccedil;os entre as pessoas. Al&eacute;m disso, </span><span style="font-weight: 400;">perfumes femininos importados</span><span style="font-weight: 400;"> podem ser fundamentais para melhorar a autoestima de algu&eacute;m. Isso faz com que esses itens sejam t&atilde;o populares entre as pessoas.</span><span style="font-weight: 400;">&nbsp;</span></p>
                    <h2><span style="font-weight: 400;">Como buscar por </span><span style="font-weight: 400;">perfumes importados originais femininos</span><span style="font-weight: 400;">?&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></h2>
                    <p><span style="font-weight: 400;">Na Internet &eacute; poss&iacute;vel encontrar in&uacute;meras lojas que vendem perfumes, mas poucas delas oferecem a garantia de originalidade que nossos produtos t&ecirc;m. Os perfumes que vendemos s&atilde;o 100% originais, para que assim voc&ecirc; tenha a certeza de estar adquirindo a fragr&acirc;ncia da marca que deseja com seguran&ccedil;a.</span></p>
                    <p><span style="font-weight: 400;">Somente um produto original ser&aacute; capaz de proporcionar a voc&ecirc; a fragr&acirc;ncia que deseja, seja para exalar um aroma que evoque sedu&ccedil;&atilde;o, romantismo, ou apenas para utilizar no seu dia a dia. Um </span><span style="font-weight: 400;">perfume importado para mulher</span><span style="font-weight: 400;"> &eacute; capaz de fazer com que os outros olhem para voc&ecirc; exatamente da maneira como voc&ecirc; quer ser vista.</span></p>
                    <p><span style="font-weight: 400;">Al&eacute;m disso, um perfume original da marca vai garantir a qualidade do produto. Dessa forma, voc&ecirc; ter&aacute; a certeza de que o aroma ter&aacute; uma excelente fixa&ccedil;&atilde;o, e que ficar&aacute; em voc&ecirc; durante muitas horas do seu dia.</span></p>
                    <h2><span style="font-weight: 400;">Qual &eacute; a melhor loja para </span><span style="font-weight: 400;">comprar perfume feminino</span><span style="font-weight: 400;">?</span></h2>
                    <p><span style="font-weight: 400;">Em nossa loja voc&ecirc; encontrar&aacute; uma variada sele&ccedil;&atilde;o de fragr&acirc;ncias de diversas marcas. Al&eacute;m disso, &eacute; poss&iacute;vel encontrar em nosso site </span><span style="font-weight: 400;">perfumes importados femininos baratos</span><span style="font-weight: 400;">, para que assim voc&ecirc; n&atilde;o tenha que gastar muito dinheiro para ter o aroma perfeito.</span></p>
                    <p><span style="font-weight: 400;">Outras vantagens de adquirir um perfume em nossa loja s&atilde;o as seguintes:</span></p>
                    <ul style="margin-bottom:10px;">
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Frete gr&aacute;tis para compras acima de R$ 150,00</span></li>
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Suporte de qualidade caso voc&ecirc; tenha alguma d&uacute;vida</span></li>
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Compra 100% segura</span></li>
                    </ul>
                    <p><span style="font-weight: 400;">Seja para dar de presente para algu&eacute;m, ou para voc&ecirc; mesmo, escolher um perfume &eacute; sempre uma boa op&ccedil;&atilde;o, pois esses itens t&ecirc;m o poder de transformar uma pessoa, j&aacute; que ela pode demonstrar o que deseja ser atrav&eacute;s de uma fragr&acirc;ncia.</span></p>
                    <p><span style="font-weight: 400;">Comprar atrav&eacute;s da nossa loja traz muitos benef&iacute;cios para voc&ecirc;. Adquira agora mesmo o perfume que deseja atrav&eacute;s do nosso site, e impressione a todos com o seu novo aroma.</span></p>
	           </div>
	        </div>
	    </div>
		<?php } ?>
		<?php if($actual_link=='https://www.shopvitas.com.br/?m=produto&c=produto&a=lista&father=perfumes-masculinos'){?>
	    <div class="container hide">
	        <div class="row">
	            <div class="col-md-12">
	                <h1><span style="font-weight: 400;">Adquira </span><span style="font-weight: 400;">perfumes femininos</span><span style="font-weight: 400;"> de marcas renomadas&nbsp;</span></h1>
                    <p><span style="font-weight: 400;">Comprar perfume online pode n&atilde;o parecer a tarefa mais f&aacute;cil diante da quantidade de produtos que existem online. No entanto, algumas lojas facilitam muito a escolha dos clientes, atrav&eacute;s de descri&ccedil;&otilde;es detalhadas dos perfumes, e de um suporte eficiente.</span></p>
                    <p><span style="font-weight: 400;">Esse &eacute; o caso do nosso site, atrav&eacute;s do qual voc&ecirc; encontrar&aacute; o </span><span style="font-weight: 400;">perfume importado feminino</span><span style="font-weight: 400;"> da marca que deseje. Assim voc&ecirc; ter&aacute; o aroma perfeito para combinar com os seus looks e para fazer sucesso em qualquer ocasi&atilde;o.</span></p>
                    <p><span style="font-weight: 400;">Um perfume &eacute; sempre uma &oacute;tima escolha, pois as fragr&acirc;ncias s&atilde;o capazes de trazer &agrave; mem&oacute;ria lembran&ccedil;as e de criar la&ccedil;os entre as pessoas. Al&eacute;m disso, </span><span style="font-weight: 400;">perfumes femininos importados</span><span style="font-weight: 400;"> podem ser fundamentais para melhorar a autoestima de algu&eacute;m. Isso faz com que esses itens sejam t&atilde;o populares entre as pessoas.</span><span style="font-weight: 400;">&nbsp;</span></p>
                    <h2><span style="font-weight: 400;">Como buscar por </span><span style="font-weight: 400;">perfumes importados originais femininos</span><span style="font-weight: 400;">?&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></h2>
                    <p><span style="font-weight: 400;">Na Internet &eacute; poss&iacute;vel encontrar in&uacute;meras lojas que vendem perfumes, mas poucas delas oferecem a garantia de originalidade que nossos produtos t&ecirc;m. Os perfumes que vendemos s&atilde;o 100% originais, para que assim voc&ecirc; tenha a certeza de estar adquirindo a fragr&acirc;ncia da marca que deseja com seguran&ccedil;a.</span></p>
                    <p><span style="font-weight: 400;">Somente um produto original ser&aacute; capaz de proporcionar a voc&ecirc; a fragr&acirc;ncia que deseja, seja para exalar um aroma que evoque sedu&ccedil;&atilde;o, romantismo, ou apenas para utilizar no seu dia a dia. Um </span><span style="font-weight: 400;">perfume importado para mulher</span><span style="font-weight: 400;"> &eacute; capaz de fazer com que os outros olhem para voc&ecirc; exatamente da maneira como voc&ecirc; quer ser vista.</span></p>
                    <p><span style="font-weight: 400;">Al&eacute;m disso, um perfume original da marca vai garantir a qualidade do produto. Dessa forma, voc&ecirc; ter&aacute; a certeza de que o aroma ter&aacute; uma excelente fixa&ccedil;&atilde;o, e que ficar&aacute; em voc&ecirc; durante muitas horas do seu dia.</span></p>
                    <h2><span style="font-weight: 400;">Qual &eacute; a melhor loja para </span><span style="font-weight: 400;">comprar perfume feminino</span><span style="font-weight: 400;">?</span></h2>
                    <p><span style="font-weight: 400;">Em nossa loja voc&ecirc; encontrar&aacute; uma variada sele&ccedil;&atilde;o de fragr&acirc;ncias de diversas marcas. Al&eacute;m disso, &eacute; poss&iacute;vel encontrar em nosso site </span><span style="font-weight: 400;">perfumes importados femininos baratos</span><span style="font-weight: 400;">, para que assim voc&ecirc; n&atilde;o tenha que gastar muito dinheiro para ter o aroma perfeito.</span></p>
                    <p><span style="font-weight: 400;">Outras vantagens de adquirir um perfume em nossa loja s&atilde;o as seguintes:</span></p>
                    <ul style="margin-bottom:10px;">
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Frete gr&aacute;tis para compras acima de R$ 150,00</span></li>
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Suporte de qualidade caso voc&ecirc; tenha alguma d&uacute;vida</span></li>
                    <li style="font-weight: 400;"><span style="font-weight: 400;">-Compra 100% segura</span></li>
                    </ul>
                    <p><span style="font-weight: 400;">Seja para dar de presente para algu&eacute;m, ou para voc&ecirc; mesmo, escolher um perfume &eacute; sempre uma boa op&ccedil;&atilde;o, pois esses itens t&ecirc;m o poder de transformar uma pessoa, j&aacute; que ela pode demonstrar o que deseja ser atrav&eacute;s de uma fragr&acirc;ncia.</span></p>
                    <p><span style="font-weight: 400;">Comprar atrav&eacute;s da nossa loja traz muitos benef&iacute;cios para voc&ecirc;. Adquira agora mesmo o perfume que deseja atrav&eacute;s do nosso site, e impressione a todos com o seu novo aroma.</span></p>
	           </div>
	        </div>
	    </div>
	<?php } ?>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>	
</body>
</html>
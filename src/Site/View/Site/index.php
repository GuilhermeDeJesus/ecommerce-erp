<?php
use Krypitonite\Util\ValidateUtil;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php if($_SERVER["REQUEST_URI"]=="/"){?>
    <title>Loja de Calçados Feminino | Shopvitas</title>
    <meta name="description" content="Shopvitas oferece uma grande variedade de perfumes importados a   
    preços de atacado! Compre perfumes femininos, masculinos e unissex de 
    suas marcas favoritas. 100% original. Frete grátis a partir de R $ 150.
    " />
    <?php }else{?>
<title><?=NOME_LOJA;?> | <?=TAG_DESCRIPTION;?></title>
<meta name="description" content="<?=TAG_DESCRIPTION;?>" />
<?php }?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="/">
<meta name="facebook-domain-verification" content="3ckuftt6adkq6d548w7eogm24jz7ib" />
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
<meta property="og:description"
	content="<?=NOME_LOJA;?> | <?=TAG_DESCRIPTION;?>">
<meta property="og:locale" content="pt_BR">
<meta property="og:title"
	content="<?=NOME_LOJA;?> | <?=TAG_DESCRIPTION;?>">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<meta property="og:url" content="https://<?=LINK_LOJA;?>">
<?php require_once 'src/Site/View/Site/css.php';?>
<link rel="stylesheet" type="text/css" href="public/css/slick.min.css">
<link rel="stylesheet" href="public/css/swiper.min.css">
<link rel="stylesheet" type="text/css"
	href="public/css/slick-theme.min.css">
<script type="application/ld+json">
{
"@context": "https://schema.org",
"@id": "https://www.shopvitas.com.br/#organization",
"@type": "Organization",
"name": "Shopvitas",
"url": "https://www.shopvitas.com.br/",
"logo": "https://www.shopvitas.com.br/public/img/logo_shopvitas.png",
"description": "A maior loja de calçados feminino do Brasil. ✈️Enviamos para todo o Brasil. 💯 Produtos 100% Originais. ⭐️Atacado de Varejo.",
"email": "contato@shopvitas.com.br",
"contactPoint":
{
"@type": "ContactPoint",
"telephone": "+5561996187206",
"contactType": "customer support"
},
"sameAs":
[
"https://www.facebook.com/shopvitas.br/",
"https://www.instagram.com/shopvitas.br/"
]
}
</script>
</head>
<body>
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<div class="_ofertas no-mobile hide">
		<a href="prazos-e-entregas"><img style="width: 100%;"
			alt="Ofertas <?=NOME_LOJA;?>" src="public/img/ssssaaa.png"></a>
	</div>
	<div class="swiper-container hide mobile">
        <div class="swiper-wrapper">
          <div class="swiper-slide"><img src="public/img/banners/banner-2.png" alt="Coturno Feminino" class="img-banner-mobile"></div>
          <div class="swiper-slide"><img src="public/img/banners/banner-1.png" alt="Calçados Feminino" class="img-banner-mobile"></div>
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next" style="color: #ccc;"></div>
        <div class="swiper-button-prev" style="color: #ccc;"></div>
    </div>
	<div id="myCarousel" class="carousel slide no-mobile"
		data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
			<li data-target="#myCarousel" data-slide-to="3"></li>
		</ol>
		<div class="carousel-inner"
			style="background: #FFF; border: 0px solid #000; margin: auto; width: 100%; left: 0; right: 0;">
			<div class="item active">
				<div class="item">
					<img src="public/img/banners/banner-2.png" alt="Calçados Feminino"
						id="img-banner">
				</div>
			</div>
			<div class="item">
				<div class="item">
					<img src="public/img/banners/banner-1.png" alt="Calçados Feminino"
						id="img-banner">
				</div>
			</div>
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span> <span
				class="sr-only">Próxima</span>
			</a> <a class="right carousel-control" href="#myCarousel"
				data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span>
				<span class="sr-only">Anterior</span>
			</a>
		</div>
	</div>
	<div class="_ofertas no-mobile hide">
		<a href="prazos-e-entregas"><img class="img-ofertas"
			alt="Ofertas <?=NOME_LOJA;?>"
			src="public/img/ofertas_voce_sempre_bela.jpeg"></a>
	</div>
	<div class="ads-grid">
		<div class="container">
			<div class="agileinfo-ads-display col-md-12">
			    <!-- CATEGORIAS DESKTOP -->
    			<div class="wrapper no-mobile">
    				<div class="sliders main">
    					<div class="slider slider-nav">
						<?php
                        foreach ($data['_categorias'] as $categoria) {
                            if (file_exists('data/categorias/'.$categoria.'.png')) {
                            ?>
                               <div class="boxMobile">
    								<a href="/categoria/<?=seo($categoria)?>"> <img class="slide-categoria" src="data/categorias/<?=$categoria;?>.png" border="0"
    									alt="<?=$categoria;?>">
    								</a> 
    								<br>
    								<span style="text-align: center; margin-left: 40%;"><?=ucfirst($categoria);?></span>
    							</div>
        						<?php } ?>  
        					<?php } ?>  
                    	</div>
    				</div>
    			</div>
    			<br><br>
    			<h3 class="heading-tittle mobile">Marcas procuradas</h3>
    			<br><br>
			    <!-- MARCAS DESKTOP -->
    			<div class="wrapper no-mobile">
    				<div class="sliders main">
    					<div class="slider slider-nav">
						<?php
                        foreach ($data['_marcas'] as $marca) {
                            if (file_exists('data/marcas/' . $marca['id'] . '/'.$marca['id'].'_M.png')) {
                            ?>
                               <div class="boxMobile">
    								<a href="/marca/<?=seo($marca['nome'])?>"> <img class="slide-marca" src="data/marcas/<?=$marca['id'];?>/<?=$marca['id'];?>_M.png" border="0"
    									alt="<?=$marca['nome'];?>">
    								</a> 
    								<br>
    							</div>
        						<?php } ?>  
        					<?php } ?>  
                    	</div>
    				</div>
    			</div>
    			<br>
    			<br>
    			<!-- CATEGORIAS MOBILE -->
    			<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider>
                    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m uk-grid">
                    	<?php
                            foreach ($data['_marcas'] as $marca) {
                                if (file_exists('data/marcas/' . $marca['id'] . '/'.$marca['id'].'_M.png')) { ?>
                                <li>
                                    <div class="uk-panel">
                                        <a href="/marca/<?=seo($marca['nome'])?>"
    									class="img-destaque"><img style="width: 70%; height: 70%;" src="data/marcas/<?=$marca['id'];?>/<?=$marca['id'];?>_M.png" alt=""></a>
                                        <div class="uk-position-center uk-panel"></div>
                                    </div>
                                </li>
                        	<?php } ?>  
        				<?php } ?>  
                    </ul>
                    <a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                    <a style="color: #000; font-weight: bold;" class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
            	</div>
            	<br>
            	<br>
            	 <!-- CATEGORIAS MOBILE -->
            	<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider>
                    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m uk-grid">
                    	<?php
                            foreach ($data['_categorias'] as $categoria) {
                                if (file_exists('data/categorias/'.strtolower($categoria).'.png')) { ?>
                                <li>
                                    <div class="uk-panel">
                                        <a href="/categoria/<?=seo($categoria)?>"
    									class="img-destaque"><img style="width: 70%; height: 70%;" src="data/categorias/<?=strtolower($categoria);?>.png" alt=""></a>
                                        <div class="uk-position-center uk-panel"></div>
                                    </div>
                                </li>
                        	<?php } ?>  
        				<?php } ?>  
                    </ul>
                    <a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                    <a style="color: #000; font-weight: bold;" class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
            	</div>
    			<!-- MAIS VENDIDOS VERSÃO MOBILE -->
    			<br>
    			<?php if(sizeof($data['_mais_vendidos']) != 0){ ?>
    			<h3 class="heading-tittle hide mobile">Os mais procurados</h3>
    			<br>
				<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider="sets: true">
                	<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m">
                		<?php  foreach ($data['_mais_vendidos'] as $produto) { ?>
                		<li>
                			<a href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><img src="data/products/<?=$produto['id'];?>/principal.jpg" alt=""></a>
                			<div class="uk-position-center uk-panel"></div>
                			<div class="item-produto">
                				<p class="name-brand"><a class=""  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $produto['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $produto['id_marca']);?></a></p>
								<br>
								<span class='description-index'>
									<a href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
										<span class="color-marca">Frete Grátis</span> - 
									<?php } ?><?=$produto['descricao'];?></a>
								</span>
									<?php if($produto['produto_gratis']){ ?>
									<?php $produto['valor'] = 0; ?>
										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
									<?php } ?>
									<div class="info-product-price">
										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
									<br><?php } ?>
										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']);?></span><br>
										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
                		</li>
                		<?php } ?>
                	</ul>
                	<a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-previous uk-slider-item="previous"></a> <a style="color: #000; font-weight: bold;"
                		class="uk-position-center-right uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>
                <!-- Os mais Vendidos Versão DESKTOP -->
				<div class="wrapper no-mobile">
					<br>
					<h3 class="heading-tittle">Os mais vendidos</h3>
					<h3 class="line"></h3>
					<div class="sliders main">
						<br>
						<div class="slider slider-nav">
                        <?php
                        foreach ($data['_mais_vendidos'] as $prds) {
                            if (file_exists('data/products/' . $prds['id'] . '/principal.jpg')) {
                            ?>
                            <div class="boxMobile">
								<a
									href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"
									class="img-destaque"> <img
									src="data/products/<?=$prds['id'];?>/principal.jpg"
									class="per-80 img-destaque" border="0"
									alt="<?=$prds['descricao'];?>">
								</a> <br>
								<div class="boxProduct">
									<h2 class="boxProductText"><?=$prds['descricao'];?></h2>
									<del class="desc">R$ <?=ValidateUtil::setFormatMoney($prds['valor_sem_oferta']);?></del>
									<span class="boxProductPrice">R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></span><br>
									<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($prds['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
							<?php } ?>  
    					<?php } ?>  
                      </div>
					</div>
				</div>
				<br>
				<?php } ?>
    			<!-- TENDÊNCIAS VERSÃO DESKTOP -->
				<div class="wrapper no-mobile">
					<br>
					<h3 class="heading-tittle">Últimos lançamentos</h3>
					<h3 class="line"></h3>
					<div class="sliders main">
						<br>
						<div class="slider slider-nav">
                        <?php
                        foreach ($data['_tendencias'] as $prds) {
                            if (file_exists('data/products/' . $prds['id'] . '/principal.jpg')) {
                            ?>
                            <div class="boxMobile">
								<a
									href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"
									class="img-destaque"> <img
									src="data/products/<?=$prds['id'];?>/principal.jpg"
									class="per-80 img-destaque" border="0"
									alt="<?=$prds['descricao'];?>">
								</a> <br>
								<div class="boxProduct">
									<h2 class="boxProductText"><?=$prds['descricao'];?></h2>
									<del class="desc">R$ <?=ValidateUtil::setFormatMoney($prds['valor_sem_oferta']);?></del>
									<span class="boxProductPrice">R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></span><br>
									<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($prds['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
							<?php } ?>  
    					<?php } ?>  
                      </div>
					</div>
				</div>
				<br>
				<?php
                $_botas = $data['_botas'];
                if (sizeof($_botas) > 0) { ?>
                <h3 class="heading-tittle hide mobile">Coleção Inverno <?=date('Y'); ?></h3>
                <br>
                <!-- VERSÃO MOBILE -->
				<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider="sets: true">
                	<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m">
                		<?php  foreach ($_botas as $produto) { ?>
                		<li>
                			<a href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><img src="data/products/<?=$produto['id'];?>/principal.jpg" alt=""></a>
                			<div class="uk-position-center uk-panel"></div>
                			<div class="item-produto">
                			    <p class="name-brand"><a class=""  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $produto['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $produto['id_marca']);?></a></p>
								<br>
								<span class='description-index'>
									<a
										href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
										<span class="color-marca">Frete Grátis</span> - 
									<?php } ?><?=$produto['descricao'];?></a>
								</span>
									<?php if($produto['produto_gratis']){ ?>
									<?php $produto['valor'] = 0; ?>
										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
									<?php } ?>
									<div class="info-product-price">
										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
									<br><?php } ?>
										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']);?></span><br>
										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
                		</li>
                		<?php } ?>
                	</ul>
                	<a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-previous uk-slider-item="previous"></a> <a style="color: #000; font-weight: bold;"
                		class="uk-position-center-right uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>
                <!-- VERSÃO DESKTOP -->				
				<div class="wrapper no-mobile">
					<br>
					<h3 class="heading-tittle">Coleção Inverno <?=date('Y'); ?></h3>
					<h3 class="line"></h3>
					<div class="sliders main">
						<br>
						<div class="slider slider-nav">
                            <?php
                            foreach ($data['_botas'] as $prds) {
                                if (file_exists('data/products/' . $prds['id'] . '/principal.jpg')) {
                                ?>
                                <div class="boxMobile">
								<a
									href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"
									class="img-destaque"> <img
									src="data/products/<?=$prds['id'];?>/principal.jpg"
									class="per-80 img-destaque" border="0"
									alt="<?=$prds['descricao'];?>">
								</a> <br>
								<div class="boxProduct">
									<h2 class="boxProductText"><?=$prds['descricao'];?></h2>
									<del class="desc">R$ <?=ValidateUtil::setFormatMoney($prds['valor_sem_oferta']);?></del>
									<span class="boxProductPrice">R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></span><br>
									<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($prds['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
    							<?php } ?>  
        					<?php } ?>  
                          </div>
					</div>
				</div>
				<?php } ?>
				<br>
				<?php
                $_sapatos = $data['_sapatos'];
                if (sizeof($_sapatos) > 0) { ?>
                <!-- VERSÃO MOBILE -->
                <h3 class="heading-tittle hide mobile">Sapatos</h3>
                <br>
				<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider="sets: true">
                	<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m">
                		<?php  foreach ($_sapatos as $produto) { ?>
                		<li>
                			<a href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><img src="data/products/<?=$produto['id'];?>/principal.jpg" alt=""></a>
                			<div class="uk-position-center uk-panel"></div>
                			<div class="item-produto">
                			    <p class="name-brand"><a class=""  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $produto['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $produto['id_marca']);?></a></p>
								<br>
								<span class='description-index'>
									<a
										href="produto/<?=$produto['id'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
										<span class="color-marca">Frete Grátis</span> - 
									<?php } ?><?=$produto['descricao'];?></a>
								</span>
									<?php if($produto['produto_gratis']){ ?>
									<?php $produto['valor'] = 0; ?>
										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
									<?php } ?>
									<div class="info-product-price">
										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
									<br><?php } ?>
										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']);?></span><br>
										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
                		</li>
                		<?php } ?>
                	</ul>
                	<a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-previous uk-slider-item="previous"></a> <a style="color: #000; font-weight: bold;"
                		class="uk-position-center-right uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>		
                <!-- VERSÃO DESKTOP -->		
				<div class="wrapper no-mobile">
					<br>
					<h3 class="heading-tittle">Sapatos</h3>
					<h3 class="line"></h3>
					<div class="sliders main">
						<br>
						<div class="slider slider-nav">
                            <?php
                            foreach ($data['_sapatos'] as $prds) {
                                if (file_exists('data/products/' . $prds['id'] . '/principal.jpg')) {
                                ?>
                                <div class="boxMobile">
								<a
									href="produto/<?=$prds['id'];?>/<?=$prds['cod_url_produto'];?>"
									class="img-destaque"> <img
									src="data/products/<?=$prds['id'];?>/principal.jpg"
									class="per-80 img-destaque" border="0"
									alt="<?=$prds['descricao'];?>">
								</a> <br>
								<div class="boxProduct">
									<h2 class="boxProductText"><?=$prds['descricao'];?></h2>
									<del class="desc">R$ <?=ValidateUtil::setFormatMoney($prds['valor_sem_oferta']);?></del>
									<span class="boxProductPrice">R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></span><br>
									<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($prds['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
    							<?php } ?>  
        					<?php } ?>  
                          </div>
					</div>
				</div>
				<?php } ?>
				<?php $destaques = $data['destaques']; ?>
				<br>
				<h3 class="heading-tittle hide mobile">Destaques</h3>
				<br>
				<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider="sets: true">
                	<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m">
                		<?php  foreach ($destaques as $produto) { ?>
                		<li>
                			<a href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><img src="data/products/<?=$produto['codigo'];?>/principal.jpg" alt=""></a>
                			<div class="uk-position-center uk-panel"></div>
                			<div class="item-produto">
                			    <p class="name-brand"><a class=""  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $produto['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $produto['id_marca']);?></a></p>
								<br>
								<span class='description-index'>
									<a
										href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
										<span class="color-marca">Frete Grátis</span> - 
									<?php } ?><?=$produto['descricao'];?></a>
								</span>
									<?php if($produto['produto_gratis']){ ?>
									<?php $produto['valor'] = 0; ?>
										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
									<?php } ?>
									<div class="info-product-price">
										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
									<br><?php } ?>
										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor']);?></span><br>
										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
                		</li>
                		<?php } ?>
                	</ul>
                	<a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-previous uk-slider-item="previous"></a> <a style="color: #000; font-weight: bold;"
                		class="uk-position-center-right uk-position-small uk-hidden-hover"
                		href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>
				<?php
                if (sizeof($destaques) > 0) { ?>
					<div class="product-sec1 hide no-mobile">
						<br>
						<?php  foreach ($destaques as $produto) { ?>
                        <hr />
						<div class="col-xs-3 product-men">
						<div class="men-pro-item simpleCart_shelfItem">
							<div class="men-thumb-item">
								<div>
									<table>
									<tr>
									<td width="150">
									<a
										href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><img
										class="per-80 img-pro"  id="img-produto-mobile"
										src="data/products/<?=$produto['codigo'];?>/principal.jpg"
										alt="<?=$produto['descricao'];?>"></a>
										</td><td>
										<div class="item-produto">
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
            										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
            										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor']);?></span><br>
													<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
            								</div>
            								<div style="display: none;"
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
										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor']);?></span><br>
										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
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
					<?php } ?>
					<div class="clearfix"></div>
					</div>
					<?php } ?>
					<?php
                    $queridinhos = $data['queridinhos'];
                    if (sizeof($queridinhos) > 0) { ?>
                    <h3 class="heading-tittle hide mobile">Você andou vendo</h3>
    				<div class="uk-position-relative uk-visible-toggle uk-light hide mobile" tabindex="-1" uk-slider="sets: true">
                    	<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m">
                    		<?php  foreach ($queridinhos as $produto) { ?>
                    		<li>
                				<a href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><img src="data/products/<?=$produto['id'];?>/principal.jpg" alt=""></a>
                    			<div class="uk-position-center uk-panel"></div>
                    			<div class="item-produto">
                    			    <p class="name-brand"><a class=""  href="marca/<?=seo(dao('Core', 'Marca')->getField('nome', $produto['id_marca']));?>"><?=dao('Core', 'Marca')->getField('nome', $produto['id_marca']);?></a></p>
    								<br>
    								<span class='description-index'>
    									<a
    										href="produto/<?=$produto['codigo'];?>/<?=$produto['cod_url_produto'];?>"><?php if($produto['frete_gratis']){ ?>
    										<span class="color-marca">Frete Grátis</span> - 
    									<?php } ?><?=$produto['descricao'];?></a>
    								</span>
    									<?php if($produto['produto_gratis']){ ?>
    									<?php $produto['valor'] = 0; ?>
    										<h5 class="h5-frete-gratis">Pague Somente o Frete</h5>
    									<?php } ?>
    									<div class="info-product-price">
    										<?php if(!$produto['produto_gratis']){ ?><del>R$ <?=ValidateUtil::setFormatMoney($produto['valor_sem_oferta']);?></del>
    									<br><?php } ?>
    										<span class="parcelas" id="parcelas" style="text-align: left;">a partir de</span>
    										<span class="item_price">R$ <?=ValidateUtil::setFormatMoney($produto['valor']);?></span><br>
    										<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($produto['valor']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($produto['valor'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
    								</div>
    							</div>
                    		</li>
                    		<?php } ?>
                    	</ul>
                    	<a style="color: #000; font-weight: bold;" class="uk-position-center-left uk-position-small uk-hidden-hover"
                    		href="#" uk-slidenav-previous uk-slider-item="previous"></a> <a style="color: #000; font-weight: bold;"
                    		class="uk-position-center-right uk-position-small uk-hidden-hover"
                    		href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div>
					<div class="wrapper no-mobile">
					<br>
					<h3 class="heading-tittle">Você andou vendo</h3>
					<h3 class="line"></h3>
					<div class="sliders main">
						<br>
						<div class="slider slider-nav">
                            <?php
                            foreach ($data['queridinhos'] as $prds) {
                                if (file_exists('data/products/' . $prds['codigo'] . '/principal.jpg')) {
                                ?>
                                <div class="boxMobile">
								<a
									href="produto/<?=$prds['codigo'];?>/<?=$prds['cod_url_produto'];?>"
									class="img-destaque"> <img
									src="data/products/<?=$prds['codigo'];?>/principal.jpg"
									class="per-80 img-destaque" border="0"
									alt="<?=$prds['descricao'];?>">
								</a> <br>
								<div class="boxProduct">
									<h2 class="boxProductText"><?=$prds['descricao'];?></h2>
									<del class="desc">R$ <?=ValidateUtil::setFormatMoney($prds['valor_sem_oferta']);?></del>
									<span class="boxProductPrice">R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']);?></span><br>
									<span class="parcelas" id="parcelas" style="text-align: left;"><?=QTD_PARCELAS_SEM_JUROS;?>x de R$ <?=ValidateUtil::setFormatMoney($prds['valor_venda']/QTD_PARCELAS_SEM_JUROS);?> ou R$ <?=descontoBoleto($prds['valor_venda'], PERCENTUAL_DESCONTO_BOLETO, TRUE);?> no boleto</span>
								</div>
							</div>
    							<?php } ?>  
        					<?php } ?>  
                          </div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if($_SERVER["REQUEST_URI"]=='/'){?>
	    <div class="container hide">
	        <div class="row">
	            <div class="col-md-12">
	                <h1><span style="font-weight: 400;">Adquira </span><span style="font-weight: 400;">perfumes importados originais</span></h1>
                    <p><span style="font-weight: 400;">O mundo dos perfumes &eacute; vasto, e cada um desses itens &eacute; considerado uma obra de arte, diante de todo o trabalho envolvido para obter a fragr&acirc;ncia perfeita. A d&uacute;vida que muitas pessoas ainda t&ecirc;m &eacute; sobre como escolher o perfume ideal atrav&eacute;s da Internet.</span></p>
                    <p><span style="font-weight: 400;">Primeiramente &eacute; importante buscar por uma </span><span style="font-weight: 400;">loja de perfumes importados</span><span style="font-weight: 400;"> que seja refer&ecirc;ncia no mercado. Temos uma vasta experi&ecirc;ncia e conosco voc&ecirc; ter&aacute; a garantia de estar obtendo perfumes 100% originais.</span></p>
                    <p><span style="font-weight: 400;">Al&eacute;m disso, fazemos a </span><span style="font-weight: 400;">venda de perfumes importados</span><span style="font-weight: 400;"> para todo o Brasil. Dessa forma, todos podem ter acesso &agrave;s maravilhas oferecidas pelo mundo da perfumaria.</span></p>
                    <h2><span style="font-weight: 400;">Vale a pena buscar por </span><span style="font-weight: 400;">perfumes online</span><span style="font-weight: 400;">?</span></h2>
                    <p><span style="font-weight: 400;">A Internet &eacute; um importante meio para fazer compras atualmente, e por isso vale sim a pena buscar por esses produtos online.&nbsp; Abaixo listamos algumas das vantagens de comprar perfumes atrav&eacute;s da Internet:</span></p>
                    <ul style="margin-bottom:10px">
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- Voc&ecirc; recebe o produto no conforto do seu lar</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- &Eacute; poss&iacute;vel achar pre&ccedil;os melhores comprando</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- Voc&ecirc; encontrar&aacute; a descri&ccedil;&atilde;o detalhada de cada produto, para que assim n&atilde;o restem d&uacute;vidas sobre qual &eacute; o perfume ideal para voc&ecirc;</span></li>
                    </ul>
                    <p><span style="font-weight: 400;">Por isso, as </span><span style="font-weight: 400;">perfumarias online</span><span style="font-weight: 400;"> t&ecirc;m feito cada vez mais sucesso, j&aacute; que as pessoas t&ecirc;m percebido cada vez mais o conforto que representa a compra online de perfumes. Lojas que oferecem frete gr&aacute;tis tamb&eacute;m s&atilde;o vantajosas, j&aacute; que com isso voc&ecirc; n&atilde;o ter&aacute; que arcar com o custo de entrega.</span></p>
                    <h2><span style="font-weight: 400;">Onde </span><span style="font-weight: 400;">comprar perfume importado</span><span style="font-weight: 400;">?</span></h2>
                    <p><span style="font-weight: 400;">Por conta dos diversos sites que vendem perfumes na Internet, &eacute; importante estar atento para adquirir o seu produto em um lugar que ofere&ccedil;a seguran&ccedil;a e servi&ccedil;o &aacute;gil. Em nosso </span><span style="font-weight: 400;">site de perfumes importados</span><span style="font-weight: 400;">, al&eacute;m destes benef&iacute;cios, voc&ecirc; ter&aacute; acesso a uma enorme gama de fragr&acirc;ncias, al&eacute;m de produtos para o corpo, como hidratantes e desodorantes.</span></p>
                    <p><span style="font-weight: 400;">Tamb&eacute;m &eacute; importante buscar por uma loja que ofere&ccedil;a bons pre&ccedil;os nas fragr&acirc;ncias. Por isso, caso voc&ecirc; esteja em busca de </span><span style="font-weight: 400;">perfume importado barato</span><span style="font-weight: 400;"> e com garantia de originalidade, o nosso e-commerce &eacute; a escolha ideal para a sua aquisi&ccedil;&atilde;o.</span></p>
                    <p><span style="font-weight: 400;">Obter informa&ccedil;&otilde;es sobre a pol&iacute;tica de entregas tamb&eacute;m &eacute; importante. Em nossa loja, ela &eacute; a seguinte:</span></p>
                    <ul style="margin-bottom:10px">
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- Depois que o seu pedido for aprovado, o prazo para preparo e postagem &eacute; de 72 horas &uacute;teis</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- O prazo de entrega depender&aacute; do site dos correios e ser&aacute; calculado em dias &uacute;teis. Todas as entregas s&atilde;o realizadas durante a semana, de segunda a sexta-feira, em hor&aacute;rio comercial</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- O c&oacute;digo de rastreamento pode demorar at&eacute; 72 horas para ser atualizado no site dos Correios</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- Os correios realizam duas ou tr&ecirc;s tentativas de entrega ou podem solicitar a retirada em ag&ecirc;ncia, caso haja alguma restri&ccedil;&atilde;o</span></li>
                    	<li style="font-weight: 400;"><span style="font-weight: 400;">- O frete &eacute; gratuito para compras acima de R$ 150,00 para o envio via PAC. Caso o cliente opte por Sedex, o frete ser&aacute; cobrado normalmente</span></li>
                    </ul>
                    <p><span style="font-weight: 400;">Al&eacute;m disso, no endere&ccedil;o fornecido para a entrega, dever&aacute; estar presente uma pessoa maior de 18 anos com o documento de identifica&ccedil;&atilde;o em m&atilde;os.</span></p>
                    <p><span style="font-weight: 400;">O perfume que uma pessoa usa diz muito sobre ela, e por isso &eacute; t&atilde;o importante a escolha de uma loja online de confian&ccedil;a e que ofere&ccedil;a perfumes 100% originais. Fa&ccedil;a suas compras agora mesmo em nosso site e obtenha o aroma ideal para a sua pele.</span></p>
    			</div>
	        </div>
	    </div>
	<?php } ?>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>
	<script src="public/js/swiper.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
  	</script>
	<script type="text/javascript" src="public/js/slick.min.js"></script>
	<script type="text/javascript">
    $('.slider-for').slick({
    	   slidesToShow: 1,
    	   slidesToScroll: 1,
    	   arrows: false,
    	   fade: true,
    	   asNavFor: '.slider-nav'
    	 });
    	 $('.slider-nav').slick({
    	   slidesToShow: 4,
    	   slidesToScroll: 1,
    	   asNavFor: '.slider-for',
    	   dots: true,
    	   focusOnSelect: true
    	 });

    	 $('a[data-slide]').click(function(e) {
    	   e.preventDefault();
    	   var slideno = $(this).data('slide');
    	   $('.slider-nav').slick('slickGoTo', slideno - 1);
    	 });
    </script>
</body>
</html>
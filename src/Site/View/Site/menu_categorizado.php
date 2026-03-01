<?php
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\CarrinhoUtil;
?>
<div class="mobile-menu">
	<div id="mySidenav" class="sidenav">
		<span class="i-close-menu" id="#"><i onclick="closeNav();"
			class="fas fa-window-close"></i></span>
		<div>
			<a href="/"><img class="logo-mobile" src="public/img/<?=NOME_LOGO;?>"></a><br>
			<br>
		</div>
		<br>
		<hr>
		<div class="div-logado">
    		<?php if(isset($_SESSION['cliente']['nome'])) {?>
				<span class="ola-user"><i class="ola">Olá</i>, <span
				class="nome-cliente"><?=$_SESSION['cliente']['nome'];?></span></span>
			<a style="padding: 0px;" href="minha-conta"><span
				class="minha-conta-mobile">Minha Conta</span></a>
    		<?php }else{ ?>
				<a href="#" data-toggle="modal" data-target="#myModal1"><span
				class="faca-login">Faça login</span></a> <a style="padding: 0px;"
				href="minha-conta" data-toggle="modal" data-target="#myModal2"><span
				class="minha-conta-mobile">ou Cadastre-se</span></a>
    		<?php } ?>
		</div>
		<br>
		<hr>
		<ul class="nav navbar-nav menu__list">
			<li><a href="/" class="dropdown-toggle nav-stylehead" role="button"
				aria-haspopup="true" aria-expanded="false"><i class="fas fa-home"></i>
			</a></li>
			<li class="dropdown hide"><a href="#"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Cabelo <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-4 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/cabelo" class="no-a">Produtos para Cabelo</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Cabelo') as $cat) { ?>
							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a href="categoria/cabelo/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="col-sm-4 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/cabelo" class="no-a">Tipos de Cabelo</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Tipos de Cabelo') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a href="categoria/cabelo/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/vestidos"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Vestidos <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/vestidos" class="no-a">Vestidos</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Vestidos') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a href="categoria/vestidos/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/conjuntos"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Conjuntos <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/conjuntos" class="no-a">Conjuntos</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Conjuntos') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a
									href="categoria/conjuntos/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/saias-e-calcas"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Saias e
					Calças<i class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/saias-e-calcas" class="no-a">Saias e Calças</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Saias e Calcas') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a
									href="categoria/saias-e-calcas/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/blusas"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Blusas<i class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/blusas" class="no-a">Blusas</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Blusas') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a
									href="categoria/blusas/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/bolsas"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Bolsas <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/bolsas" class="no-a">Bolsas</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Bolsas') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a href="categoria/bolsas/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/body"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Casacos e
					Moletons <i class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/casacos-e-moletons" class="no-a">Casacos e
									Moletons</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Casacos e Moletons') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a
									href="categoria/casacos-e-moletons/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/plus-size"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Plus Size <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/plus-size" class="no-a">Plus Size</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Bolsas') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a
									href="categoria/plus-size/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="categoria/outros">Outros <i
					class="fas fa-angle-down right"></i>
			</a></li>				
			<li class="dropdown hide"><a href="#"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Perfumes <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/perfumes" class="no-a">Perfumes</a>
							</h5>
							<ul class="multi-column-dropdown">
							<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Perfumes') as $cat) { ?>
    							<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    							<li><a href="categoria/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> (<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</a></li>
    							<?php }?>
							<?php }?>
						</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<li class="dropdown"><a href="#"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false">Marcas <i
					class="fas fa-angle-down right"></i>
			</a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-6 multi-gd-img">
							<ul class="multi-column-dropdown">
    							<?php foreach (dao('Core', 'Marca')->select(['*'], NULL, NULL, 25) as $marca) { ?>
    							<li><a href="marca/<?=seo($marca['nome']);?>"><i
										class="fas fa-angle-right"></i> <?=$marca['nome'];?> </a></li>
    							<?php }?>							
							</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
		</ul>
	</div>
</div>
<?php
// VALOR TOTAL CARRINHO
$_total = [];
if (sizeof(CarrinhoUtil::getItens('_itens')) != 0) {
    foreach (CarrinhoUtil::getItens('_itens') as $_t) {
        $_total[] = $_t['valor'];
    }
}
?>
<div class="topm wrap">
	<div class="menu-top-mobile">
		<span id="menu-mob" style="font-size: 25px; cursor: pointer" id="#"><i
			onclick="openNav();" class="fas fa-bars"></i></span> <input
			type="text" class="pesquisa-mobile" name="busca_produto[]"
			placeholder="" />
		<div class="ins-mobile">
			<div>
				<span class="itens"><span class="iten-count"><?=sizeof(CarrinhoUtil::getItens('_itens'));?></span>
					Iten(s)</span>
			</div>
			<div>
				<span class="total-carrinho">R$ <?=ValidateUtil::setFormatMoney(array_sum($_total));?></span>
			</div>
		</div>
		<a href="/"><img style="width: 33%; margin-top: -10px; margin-left: 1%;" src="public/img/<?=NOME_LOGO_MOBILE;?>"></a>
		<span id="cd-cart-trigger"
			style="font-size: 25px; cursor: pointer; float: right; padding-right: 5px;"><i
			class="fa fa-shopping-cart fa-fw" aria-hidden="true"></i></span>
	</div>
	<div class="contentwrap mobile"></div>
</div>
<div id="cd-cart">
	<div id="close-cart">
		<a href="#" id="cd-cart-close">FECHAR</a><br> <br>
	</div>
	<h2>
		<i class="fa fa-cart-arrow-down" aria-hidden="true"></i> Carrinho
	</h2>
	<div id="summary-container">
		<div class="cart_prowrap">
			<table class="table" id="cart-itens">
				<thead>
					<tr>
						<th scope="col" id="no-border-th">produto</th>
						<th scope="col" id="no-border-th">descrição</th>
						<th scope="col" id="no-border-th">preço</th>
					</tr>
				</thead>
				<tbody>
				<?php
                if (sizeof(CarrinhoUtil::getItens('_itens')) != 0) {
                    $_total = [];
                    foreach (CarrinhoUtil::getItens('_itens') as $key => $value) {
                        $_total[] = $value['valor'];
                        ?>
					<tr style="border: 1px solid #e5e5e5;">
						<td class="invert-image"><a
							href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>">
								<img
								src="data/products/<?=$value['codigo'];?>/<?=$value['imagem'];?>"
								alt="<?=$value['descricao'];?>" class="img-responsive">
						</a></td>
						<td><h5 id="td-cart"><?=$value['descricao'];?></h5></td>
						<td><h5 id="td-cart">R$ <?=ValidateUtil::setFormatMoney($value['valor']);?></h5></td>
					</tr>
					<?php
                    }
                    ?>
    				<?php
                    } else {
                        ?>		
    				<tr>
						<td colspan="5">Seu Carrinho de compras está vazio</td>
					</tr>						
				<?php
                }
                ?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="cd-cart-total">
		<span>Total: R$ <?=ValidateUtil::setFormatMoney(array_sum($_total));?></span>
		<button class="button-comprar">
			<a href="meu-carrinho"><i class="fa fa-cart-arrow-down"
				aria-hidden="true"></i> CONTINUAR</a>
		</button>
	</div>
</div>
<div class="no-mobile">
	<div class="tops">
		<div id="buttons">
        	<?php
            if (! isset($_SESSION['cliente']['nome'])) {
                ?>	
        	<ul>
				<li><a href="#" data-toggle="modal" data-target="#rastreiar"> <span
						class="fa fa-truck" aria-hidden="true"></span> Rastreie seu pedido
				</a></li>
				<li><span class="fa fa-phone" aria-hidden="true"></span> Whatsapp:
					<?=TELEFONE_CONTATO;?></li>
				<li><i class="fab fa-facebook-square"></i> Facebook</li>
			</ul>
    		<?php
            } else {
                ?>
    		<ul>
				<li><a href="#" data-toggle="modal" data-target="#rastreiar"> <span
						class="fa fa-truck" aria-hidden="true"></span> Rastreie seu pedido
				</a></li>
				<li><span class="fa fa-phone" aria-hidden="true"></span> Whatsapp:
					<?=TELEFONE_CONTATO;?></li>
				<li><a href="?m=cliente&c=cliente&a=sair"> <span
						class="fa fa-unlock-alt" aria-hidden="true"></span> Sair
				</a></li>
			</ul>			
        	<?php
            }
            ?>
        </div>
	</div>
	<div class="header-bot">
		<div class="header-bot_inner_wthreeinfo_header_mid">
			<div class="col-md-4">
				<div class="logo-dv">
					<h2>
						<a href="/"><img class="logo-5" src="public/img/<?=NOME_LOGO;?>"
							alt="<?=$value['descricao'];?>"> </a>
					</h2>
				</div>
			</div>
			<div class="col-md-4">
				<div class="class-search">
					<form action="?m=produto&c=produto&a=lista" method="post"
						autocomplete="off">
						<div class="row">
							<div class="col-md-12">
								<div class="inner-addon right-addon autocomplete">
									<input type="text" placeholder="Digite o que você procura"
										class="form-control" id="search-input" name="busca_produto[]" />
									<button class="btn btn-pesquisar" type="submit">
										<i class="glyphicon glyphicon-search"></i>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-4 header">
				<div class="top_nav_user">
					<img src="public/img/user.png"
						style="width: 50px; height: 50px; margin-left: 15px;">
            		<?php if(isset($_SESSION['cliente']['nome'])) {?>
        				<span class="ola-user"><i class="ola">Olá</i>, <span
						class="nome-cliente"><?=$_SESSION['cliente']['nome'];?></span></span>
					<a class="minha-conta" class="minha-conta" href="minha-conta"><span>Minha
							Conta</span></a>
            		<?php }else{ ?>
                 		<span class="ola-user"><a href="#"
						data-toggle="modal" data-target="#myModal1"> Faça seu Login</a></span>
					<a class="minha-conta" class="minha-conta" href="#"
						data-toggle="modal" data-target="#myModal2"><span>ou Cadastre-se</span></a>
            		<?php } ?>
				</div>
				<div class="top_nav_user">
					<form action="#" method="post" class="last">
						<input type="hidden" name="cmd" value="_cart"> <input
							type="hidden" name="display" value="1"> <a href="#"
							id="cd-cart-trigger-2"><button class="w3view-cart" type="button"
								name="#" value="">
								<img src="public/img/carrinho.png"
									style="width: 30px; height: 30px; margin-left: -5px;"> <span
									class="carinho-span"></span>
							</button></a>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="ban-top">
		<div class="container">
			<div class="top_nav_left">
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed"
								data-toggle="collapse"
								data-target="#bs-example-navbar-collapse-1"
								aria-expanded="false"></button>
						</div>
						<div class="collapse navbar-collapse menu--shylock"
							id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav menu__list">
								<li><a href="/" class="dropdown-toggle nav-stylehead"
									role="button" aria-haspopup="true" aria-expanded="false"><i
										class="fas fa-home"></i> </a></li>
								<li class="dropdown" onmouseover="abrirMenu('moda');"
									onmouseout="fecharMenu('moda');"><a href="categoria/vestidos"
									class="dropdown-toggle nav-stylehead" data-toggle="dropdown"
									role="button" aria-haspopup="true" aria-expanded="false">VESTIDOS
										<span class="caret"></span>
								</a>
									<ul class="dropdown-menu multi-column columns-2" id="moda">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-6 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="categoria/vestidos" class="no-a">Vestidos</a>
												</h5>
												<ul class="multi-column-dropdown">
												<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Vestidos') as $cat) { ?>
    												<?php // if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    												<li><a
														href="categoria/vestidos/<?=seo($cat['descricao']);?>"><i
															class="fas fa-angle-right"></i> <?=$cat['descricao'];?></a></li>
    												<?php }?>
												<?php // }?>
											</ul>
											</div>
											<div class="col-sm-6 multi-gd-img">
												<a href="categoria/vestidos" class="img-destaque"> <img
													src="public/img/vestido.jpg" class="" style="width: 100%;"
													border="0" alt="Body">
												</a>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
								<li class="dropdown" onmouseover="abrirMenu('conjuntos');"
									onmouseout="fecharMenu('conjuntos');"><a
									href="categoria/conjuntos"
									class="dropdown-toggle nav-stylehead" data-toggle="dropdown"
									role="button" aria-haspopup="true" aria-expanded="false">CONJUNTOS
										<span class="caret"></span>
								</a>
									<ul class="dropdown-menu multi-column columns-2" id="conjuntos">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-6 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="categoria/conjuntos" class="no-a">Conjuntos</a>
												</h5>
												<ul class="multi-column-dropdown">
												<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Conjuntos') as $cat) { ?>
    												<?php // if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    												<li><a
														href="categoria/conjuntos/<?=seo($cat['descricao']);?>"><i
															class="fas fa-angle-right"></i> <?=$cat['descricao'];?></a></li>
    												<?php }?>
												<?php // }?>
											</ul>
											</div>
											<div class="col-sm-6 multi-gd-img">
												<a href="categoria/body" class="img-destaque"> <img
													src="public/img/conjunto.jpg" border="0" alt="Body">
												</a>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
								<li class="dropdown"><a
									href="categoria/saias-e-calcas">SAIAS E CALÇAS</a>
								</li>
								<li class="dropdown" onmouseover="abrirMenu('blusa');"
									onmouseout="fecharMenu('blusa');"><a href="categoria/vestidos"
									class="dropdown-toggle nav-stylehead" data-toggle="dropdown"
									role="button" aria-haspopup="true" aria-expanded="false">BLUSAS
										<span class="caret"></span>
								</a>
									<ul class="dropdown-menu multi-column columns-2" id="blusa">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-6 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="categoria/blusas" class="no-a">Blusas</a>
												</h5>
												<ul class="multi-column-dropdown">
												<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Blusas') as $cat) { ?>
    												<?php // if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    												<li><a
														href="categoria/blusas/<?=seo($cat['descricao']);?>"><i
															class="fas fa-angle-right"></i> <?=$cat['descricao'];?></a></li>
    												<?php }?>
												<?php // }?>
											</ul>
											</div>
											<div class="col-sm-6 multi-gd-img">
												<a href="categoria/blusas" class="img-destaque"> <img
													src="public/img/blusas.jpg" class="" style="width: 100%;"
													border="0" alt="Blusas">
												</a>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
								<li class="dropdown" onmouseover="abrirMenu('casacos');"
									onmouseout="fecharMenu('casacos');"><a
									href="categoria/casacos-moletons">CASACOS E MOLETONS</a></li>
								<li class="dropdown" onmouseover="abrirMenu('bolsas');"
									onmouseout="fecharMenu('bolsas');"><a href="categoria/bolsas">BOLSAS</a></li>
								<li class="dropdown hide" onmouseover="abrirMenu('plus');"
									onmouseout="fecharMenu('plus');"><a href="categoria/plus-size">PLUS SIZE</a>
								</li>
								<li class="dropdown hide" onmouseover="abrirMenu('perfumes');"
									onmouseout="fecharMenu('perfumes');"><a href="#"
									class="dropdown-toggle nav-stylehead" data-toggle="dropdown"
									role="button" aria-haspopup="true" aria-expanded="false">PERFUMES
										<span class="caret"></span>
								</a>
									<ul class="dropdown-menu multi-column columns-1" id="perfumes">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-12 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="categoria/" class="no-a">Perfumes</a>
												</h5>
												<ul class="multi-column-dropdown">
												<?php foreach (dao('Site', 'Categoria')->getSubcategorias('Perfumes') as $cat) { ?>
    												<?php if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    												<li><a
											 			href="categoria/<?=seo($cat['descricao']);?>"><i
															class="fas fa-angle-right"></i> <?=$cat['descricao'];?></a></li>
    												<?php }?>
												<?php }?>
											</ul>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
								<li class="dropdown" onmouseover="abrirMenu('outos');"
									onmouseout="fecharMenu('outros');"><a href="categoria/outros">ACESSÓRIOS</a>
								</li>	
								<li class="dropdown hide" onmouseover="abrirMenu('marcas');"
									onmouseout="fecharMenu('marcas');"><a href="#"
									class="dropdown-toggle nav-stylehead" data-toggle="dropdown"
									role="button" aria-haspopup="true" aria-expanded="false">Marcas
										<span class="caret"></span>
								</a>
									<ul class="dropdown-menu multi-column columns-1" id="marcas">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-12 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="#" class="no-a">Marcas</a>
												</h5>
												<ul class="multi-column-dropdown">
                        							<?php foreach (dao('Core', 'Marca')->select(['*'], NULL, NULL, 25) as $marca) { ?>
                        							<li><a
														href="marca/<?=seo($marca['nome']);?>"><i
															class="fas fa-angle-right"></i> <?=$marca['nome'];?> </a></li>
                        							<?php }?>
                        							<li><a href="#">Muito mais ;D</a></li>
												</ul>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
							</ul>
						</div>
					</div>
				</nav>
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
								required="required">
						</div>
						<div class="styled-input">
							<input type="email" placeholder="E-mail" name="email" required="">
						</div>
						<div class="styled-input">
							<input type="text" placeholder="CPF" name="cpf" class="cpf"
								required="required">
						</div>
						<div class="styled-input">
							<input type="text" placeholder="Telefone/Celular" name="telefone"
								class="telefone" required="required">
						</div>
						<div class="styled-input">
							<input type="password" placeholder="Crie uma senha" name="senha"
								id="password1" required="required">
						</div>
						<div class="styled-input">
							<input type="password" placeholder="Confirmar senha"
								name="Confirmar Senha" id="password2" required="required">
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
<div class="modal fade" id="rastreiar" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>Rastreiar meu pedido</h4>
			</div>
			<div class="modal-body modal-body-sub_agile">
				<div class="main-mailposi">
					<span class="fa fa-envelope-o" aria-hidden="true"></span>
				</div>
				<div class="modal_body_left modal_body_left1">
					<p>Digite aqui seu códido de rastreio.</p>
					<form action="?m=frete&c=correios&a=rastreiarPedido" method="post"
						id="rastreiar-pedido-form" target="new">
						<div class="styled-input agile-styled-input-top">
							<input type="text" placeholder="CÓDIGO" name="codigo"
								required="required" id="input-rastreiar">
						</div>
						<input type="submit" class="btn-rastreiar" id="btn-rastreiar"
							value="BUSCAR">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
autocomplete(document.getElementById("search-input"));
</script>

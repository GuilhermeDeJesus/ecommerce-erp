
<?php
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\CarrinhoUtil;
$menus_pai = dao('Site', 'Categoria')->select([
    '*'
], [
    'categoria_pai',
    '=',
    NULL
]);
?>
<div class="mobile-menu">
	<div id="mySidenav" class="sidenav">
		<span class="i-close-menu" id="#"><i onclick="closeNav();"
			class="fas fa-window-close"></i></span>
		<div
			style="width: 100%; background: #eaeaea; margin-top: -4px; color: #000; text-transform: uppercase;">
			<a href="#" data-toggle="modal" data-target="#myModal1"><span
				class="faca-login noboxbor"><i class="glyphicon glyphicon-user"></i> Meus
					Pedidos</span></a>
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
			<?php foreach ($menus_pai as $father) { ?>
			<li class="dropdown"><a href="#"
				class="dropdown-toggle nav-stylehead m-mb" data-toggle="dropdown"
				role="button" aria-haspopup="true" aria-expanded="false"><?=$father['descricao'];?> <i
					class="fas fa-angle-down right"></i> </a>
				<ul class="dropdown-menu multi-column columns-3 submenu-mobile">
					<div class="agile_inner_drop_nav_info">
						<div class="col-sm-3 multi-gd-img">
							<h5 class="subcategoria-menu">
								<a href="categoria/<?=seo($father['descricao']);?>" class="no-a"><?=$father['descricao'];?></a>
							</h5>
							<ul class="multi-column-dropdown">
								<?php foreach (dao('Site', 'Categoria')->getSubcategorias($father['descricao']) as $cat) { ?>
    								<?php // if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
    								<li><a
									href="categoria/<?=seo($father['descricao']);?>/<?=seo($cat['descricao']);?>"><i
										class="fas fa-angle-right"></i> <?=$cat['descricao'];?> <span
										class="hide">(<?=dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao']));?>)</span></a></li>
        							<?php // } ?>
    							<?php }?>
    							</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</ul></li>
			<?php } ?>
			<li class="hide"><a href="/prazos-e-entregas"
				class="dropdown-toggle nav-stylehead" role="button"
				aria-haspopup="true" aria-expanded="false">Envio e Prazo de Entrega
			</a></li>
		</ul>
	</div>
</div>
<?php
// VALOR TOTAL CARRINHO
$_total = [];
if (is_array(CarrinhoUtil::getItens('_itens')) && sizeof(CarrinhoUtil::getItens('_itens')) != 0) {
    foreach (CarrinhoUtil::getItens('_itens') as $_t) {
        $_total[] = $_t['valor'];
    }
}
?>
<div class="topm wrap">
	<div class="tops_boleto_mobile">
		<span class="span-tikect_mobile">PAGUE NO BOLETO E GANHE <?=PERCENTUAL_DESCONTO_BOLETO;?>% OFF EM PRODUTOS ACIMA DE R$ 100,00</span>
	</div>
	<div class="menu-top-mobile">
		<form style="width: 70%; position: absolute;"
			action="?m=produto&c=produto&a=lista" method="post"
			autocomplete="off">
			<button class="btn btn-pesquisar"
				style="background: none; margin-top: -0px; padding-bottom: 0px; outline: none;"
				type="button">
				<span id="menu-mob"
					style="font-size: 25px; margin-bottom: -50px !important; cursor: pointer;"
					id="#"> <i onclick="openNav();" class="fas fa-bars"></i>
				</span>
			</button>
			<input placeholder="" type="text" class="pesquisa-mobile"
				name="busca_produto[]" placeholder="" />
			<button class="btn btn-pesquisar"
				style="background: none; margin-top: -0px; padding-bottom: 0px; outline: none;"
				type="submit">
				<i class="glyphicon glyphicon-search"></i>
			</button>
		</form>
		<div class="ins-mobile">
			<div>
				<?php if(sizeof($_total) > 0){ ?><span class="itens"><span class="iten-count"><?php if(CarrinhoUtil::getItens('_itens') != null){ echo sizeof(CarrinhoUtil::getItens('_itens'));};?></span><?php } ?>
					Iten(s)</span>
			</div>
			<div>
				<span class="total-carrinho">R$ <?=ValidateUtil::setFormatMoney(array_sum($_total));?></span>
			</div>
		</div>
		<a href="/"><img
			style="width: 33%; margin-top: -10px; margin-left: 1%; display: none;"
			src="public/img/<?=NOME_LOGO_MOBILE;?>"></a> <span
			id="cd-cart-trigger"
			style="font-size: 25px; cursor: pointer; float: right; padding-right: 5px; margin-top: 5px;"><i
			class="fa fa-shopping-cart fa-fw" aria-hidden="true"></i></span>
	</div>
	<div class="contentwrap mobile"></div>
</div>
<div id="cd-cart">
	<div id="close-cart">
		<span id="cd-cart-close">X</span><br> <br>
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
                if ( CarrinhoUtil::getItens('_itens') != null && sizeof(CarrinhoUtil::getItens('_itens')) != 0) {
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
						<td colspan="5">Seu carrinho de compras está vazio</td>
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
		<a href="meu-carrinho"><button class="button-comprar">
				<i class="fa fa-cart-arrow-down" aria-hidden="true"></i> CONTINUAR
			</button></a>
	</div>
</div>
<div class="no-mobile">
	<div class="tops_boleto">
		<span class="span-tikect">PAGUE NO BOLETO E GANHE <?=PERCENTUAL_DESCONTO_BOLETO;?>% OFF EM PRODUTOS ACIMA DE R$ 100,00</span>
	</div>
	<div class="tops">
		<div id="regra-frete-ul">
			<ul>
				<li><?php if(VALOR_MINIMO_PARA_FRETE_GRATIS < 1000){ ?><span class="fas fa-shipping-fast" aria-hidden="true" style="color: <?=COR_LOJA;?>;"> FRETE GRÁTIS nas compras a partir de R$ <?=ValidateUtil::setFormatMoney(VALOR_MINIMO_PARA_FRETE_GRATIS);?></span><?php }else {?> Horário de funcionamento: Segunda a Sexta das 09h às 18hrs
				<?php }?></li>
			</ul>
		</div>
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
							alt="<?=NOME_LOJA;?>"> </a>
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
										class="fas fa-home"></i> Início</a></li>
								
								<?php foreach ($menus_pai as $father) { ?>
    								<li class="dropdown"
									onmouseover="abrirMenu('<?=strtolower($father['descricao']);?>');"
									onmouseout="fecharMenu('<?=strtolower($father['descricao']);?>');"><a
									href="#" class="dropdown-toggle nav-stylehead"
									data-toggle="dropdown" role="button" aria-haspopup="true"
									aria-expanded="false"><?=$father['descricao'];?>
    										<span class="caret"></span> </a>
									<ul class="dropdown-menu multi-column columns-1"
										id="<?=strtolower($father['descricao']);?>">
										<div class="agile_inner_drop_nav_info">
											<div class="col-sm-12 multi-gd-img">
												<h5 class="subcategoria-menu">
													<a href="categoria/<?=seo($father['descricao']);?>"
														class="no-a"><?=$father['descricao'];?></a>
												</h5>
												<ul class="multi-column-dropdown">
    												<?php foreach (dao('Site', 'Categoria')->getSubcategorias($father['descricao']) as $cat) { ?>
        												<?php //  if(dao('Produto', 'Produto')->_totalProdutoPorCategoria(dao('Site', 'Categoria')->getIdPorDescricao($cat['descricao'])) != 0){ ?>
        												<li><a
														href="categoria/<?=seo($father['descricao']);?>/<?=seo($cat['descricao']);?>"><i
															class="fas fa-angle-right"></i> <?=$cat['descricao'];?></a></li>
        												<?php // } ?>
    												<?php }?>
    											</ul>
											</div>
											<div class="clearfix"></div>
										</div>
									</ul></li>
								<?php }?>
								<li class="dropdown"><a href="/prazos-e-entregas">Prazos e Entregas</a></li>
								<li class="dropdown hide"><a href="#" data-toggle="modal"
									data-target="#contato">Contato</a></li>
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
					<span id="alert-cad-cliente" style="display: none;"></span>
					<form action="?m=cliente&c=cliente&a=cadastrar" method="post">
						<div class="styled-input agile-styled-input-top">
							<input type="text" placeholder="Nome" name="nome"
								required="required">
						</div>
						<div class="styled-input agile-styled-input-top">
							<input type="text" placeholder="Sobrenome" name="sobrenome"
								required="required">
						</div>
						<div class="styled-input">
							<input type="email" placeholder="E-mail" name="email" required="">
						</div>
						<div class="styled-input">
							<input type="text" placeholder="CPF" name="cpf" class="cpf"
								id="cpf" required="required">
						</div>
						<div class="styled-input">
							<input type="text" placeholder="Data de Nascimento"
								name="data_nascimento" class="data_nascimento"
								id="data_nascimento" required="required">
						</div>
						<div class="styled-input agile-styled-input-top">
							<select class="" name="sexo">
								<option class="input-end" value="">Sexo</option>
								<option class="input-end" value="F">Feminino</option>
								<option class="input-end" value="M">Masculino</option>
								<option class="input-end" value="">Não Informado</option>
							</select>
						</div>
						<div class="styled-input">
							<input type="text" placeholder="Telefone/Celular" name="telefone"
								id="telefone" class="telefone" required="required">
						</div>
						<div class="styled-input">
							<input type="password" placeholder="Crie uma senha" name="senha"
								id="password1" required="required">
						</div>
						<div class="styled-input">
							<input type="password" placeholder="Confirmar senha"
								name="Confirmar Senha" id="password2" required="required">
						</div>
						<input type="submit" value="Cadastrar"
							onclick="return validateFormCadMenu();">
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
<div class="mobile" style="height: 65px;">
	<h2 class="">
		<a href="/"><img
			style="width: 33%; margin-top: 20px; margin-left: auto; margin-right: auto; display: block;"
			src="public/img/<?=NOME_LOGO;?>"></a>
	</h2>
</div>
<div>
<a href="https://api.whatsapp.com/send?phone=55<?=str_replace('' , '', trim(TELEFONE_CONTATO));?>&text=Olá, pode me ajudar ?" target="_blank"><img class="whatsapp" src="public/img/whatsapp.png" /></a>
</div>
<!--inicio Integrazap-->
<script async
	src="https://integrazap.com.br/gadget-v.1/wapp-flutuante.js?x=1eae20707f74779e5cce72ef7856b235&y=86336209850&z=shopvitas.com.br"></script>
<!--fim Integrazap-->
<script>
autocomplete(document.getElementById("search-input"));

function cpf(cpf){
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

function validateFormCadMenu() {
	var _cpf = cpf(document.getElementById("cpf").value);
	
	if (document.getElementById("telefone").value.length < 12) {
        event.preventDefault();
		$('#alert-cad-cliente').css('display', 'table');
		$('#alert-cad').html('<p>Insira um telefone válido!</p>');
    }

    if(!_cpf){
        event.preventDefault();
		$('#alert-cad-cliente').css('display', 'table');
		$('#alert-cad-cliente').html('<p>Insira um cpf válido!</p>');
    }

	var dt = FormataStringData(document.getElementById("data_nascimento").value);
    var dataNascimento = new Date(dt);


    var _dataAtual = new Date();
    var _dataAtualMenos18Anos = new Date(_dataAtual.getFullYear() - 18,
    		_dataAtual.getMonth(),
    		_dataAtual.getDate());
	
    if (dataNascimento == 'Invalid Date') {
        event.preventDefault();
		$('#alert-cad-cliente').css('display', 'table');
		$('#alert-cad-cliente').html('<p>Data de Nascimento Inválida!</p>');
    }else if(dataNascimento >= _dataAtual){
        event.preventDefault();
		$('#alert-cad-cliente').css('display', 'table');
		$('#alert-cad-cliente').html('<p>Data de Nascimento não permitida para cadastro!</p>');
    }else if(dataNascimento >= _dataAtualMenos18Anos){
        event.preventDefault();
		$('#alert-cad-cliente').css('display', 'table');
		$('#alert-cad-cliente').html('<p>Data de Nascimento não permitida para cadastro!</p>');
    }
}
</script>

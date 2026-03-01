<div id="newsletter-rodape">
	<div class="container">
		<div class="row">
			<section class="newsletter col-md-9 col-sm-12 col-xs-12"
				data-tray-tst="newsletter_box">
				<div class="col-sm-5 col-xs-12">
					<h2 class="newsletter-title">
						<strong>CADASTRE O SEU E-MAIL</strong><span>e receba vantagens
							diretamente no seu e-mail</span>
					</h2>
				</div>
				<div class="col-sm-7 col-xs-12">
					<form action="?m=cliente&c=cliente&a=newsletter" method="post"
						name="newsletter" data-tray-tst="newsletter_form">
						<input name="email" type="email" placeholder="Digite seu e-mail"
							data-tray-tst="newsletter_email" required="">
						<button data-tray-tst="newsletter_cadastrar">Cadastrar</button>
					</form>
				</div>
			</section>
			<div style="display: none;"
				class="col-md-3 col-xs-12 hidden-sm visible-block-xs social-list">
				<ul class="social-list flex ">
					<li><a href="https://www.facebook.com/primacial" target="_blank"><i
							class="fa fa-facebook"></i></a></li>
					<li><a href="https://www.instagram.com/primacialoficial"
						target="_blank"><i class="fa fa-instagram"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<footer>
	<div class="container">
		<hr>
		<div class="footer-info w3-agileits-info">
			<div class="col-sm-5 address-right">
				<div class="col-xs-6 footer-grids">
					<h3>Categorias</h3>
					<ul>
    					<?php
                             $_categorias = dao('Core', 'Categoria')->select([
                             '*'
                             ]);
                             foreach ($_categorias as $categoria) {
                            ?>
						<li><h2 class="categoria-footer">
								<a href="categoria/<?=seo($categoria['descricao']);?>"><?=$categoria['descricao'];?></a>
							</h2></li>
					<?php  } ?>
					</ul>
				</div>
				<div class="col-xs-6 footer-grids">
					<h3>Contato</h3>
					<ul>

						<li><a href=mailto:<?=EMAIL_CONTATO;?>><?=EMAIL_CONTATO;?></a> </li>		
						<li><img src="public/img/whatsapp.png" style="width: 9%;">
						<?=TELEFONE_CONTATO;?></li>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-sm-5 address-right">
				<div class="col-xs-6 footer-grids">
					<h3>Links Rápidos</h3>
					<ul>
						<li><a href="#" data-toggle="modal" data-target="#contato">Fale
								Conosco</a></li>
						<li><a href="prazos-e-entregas">Prazos e Entregas</a></li>
						<li><a href="troca-e-devolucao">Troca e Devolução</a></li>
						<li><a href="faq-perguntas-frequentes">FAQ - Perguntas Frequentes</a></li>
					</ul>
				</div>

				<div class="col-xs-6 footer-grids">
					<h3>Entrega</h3>
					<img src="public/img/correios.png" style="width: 40%;"><br> <br>
					<h3>Segurança</h3>
					<img src="public/img/rapidssll.png" style="width: 55%;">
				</div>
			</div>
			<div class="col-sm-2 footer-grids  w3l-socialmk">
				<h3>Siga-nos no</h3>
				<div class="social">
					<ul>
						<li><a class="icon " target="new"
							href="https://www.facebook.com/gkshows/">
								<img src="public/img/icon-facebook.jpg"
								style="width: 30px; height: 30px;">
						</a></li>
						<!-- <li><a class="icon " href="#"> <img
								src="public/img/icon-twiter.png"
								style="width: 30px; height: 30px;">
						</a></li> -->
						<li><a class="icon " href="https://www.instagram.com/gkshoes/"><img
								src="public/img/instagram.jpg"
								style="width: 30px; height: 30px;"> </a></li>
					</ul>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<hr>
		<div class="agile-sometext">
			<div class="sub-some">
				<h4><?=NOME_LOJA;?></h4>
				<br>
				<p style="display: none;">
					<b><?=NOME_LOJA;?> Ltda. 20.747.907/0001-26</b>
				</p>
			</div>
			<div class="sub-some">
				<h5>Compre online com as melhores ofertas</h5><br>
				<p>O valor mínimo para FRETE GRÁTIS pode variar entre os estados e
					você poderá verificar o frete no carrinho ao inserir seu CEP.</p>

				<p>Copyright © <?=date('Y');?>  <?=LINK_LOJA;?> Todos os direitos
					reservados.</p>
			</div>
			<div class="sub-some">
				<h4>Nossas Marcas</h4>
				<br>
				<ul>
				<?php
                $_marcas = dao('Core', 'Marca')->select([
                    '*'
                ]);
                foreach ($_marcas as $marca) {
                    ?>
					<li><a href="marca/<?=seo($marca['nome']);?>"><?=$marca['nome'];?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="sub-some child-momu" style="display: none;">
				<h4>Pagamento</h4>
				<ul>
				<?php 
    				$gateway = GATEWAY;
    				if($gateway == 'mercadopago'){?>
					<li><img src="public/img/todas-mercadopago-010419.png" style="width: 25%; padding: 15px;"
						alt="<?=NOME_LOJA;?>"></li>
					<?php }else if($gateway == 'pagseguro'){?>
					<li><img src="public/img/pagseguro.png" style="width: 20%; padding: 15px;"
						alt="<?=NOME_LOJA;?>"></li>
					<?php }else if($gateway == 'upnid'){?>
					<li><img src="public/img/upnid.png" style="width: 60%; padding: 15px;"
						alt="<?=NOME_LOJA;?>"></li>
					<?php }?>
				</ul>
			</div>
		</div>
	</div>
</footer>
<div class="copy-right">
	<div class="container">
		<p>© <?=date('Y');?> <?=NOME_LOJA;?>. Todos os direitos reservados. <?=date('Y')?></p>
	</div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id='contato'
	role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>Fale Conosco</h4>
			</div>
			<div class="modal-body modal-body-sub_agile">
				<div class="main-mailposi">
					<span class="fa fa-envelope-o" aria-hidden="true"></span>
				</div>
				<div class="modal_body_left modal_body_left1">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-warning" id="msg-fale" role="alert"
								style="display: none; width: 100%;">
								<span id="text-fale"></span>
							</div>
						</div>
						<form action="#" role="form" id="fale-conosco" novalidate>
							<div class="col-md-6">
								<div class="control-group">
									<span class="table-contato-th">Nome</span>
									<div class="controls">
										<input id="nome" maxlength="100" name="nome" type="text"
											required="required">
									</div>
								</div>
								<div class="control-group">
									<span class="table-contato-th">E-mail</span>
									<div class="controls">
										<input id="email" maxlength="128" name="email" type="text"
											required="required">
									</div>
								</div>
								<div class="control-group">
									<span class="table-contato-th">Telefone</span>
									<div class="controls">
										<input class="input-telefone" id="telefone" name="telefone"
											type="text" maxlength="15" required="required">
									</div>
								</div>
								<div class="control-group">
									<span class="table-contato-th">Nº do pedido</span>
									<div class="controls">
										<input id="numero_pedido" name="numero_pedido" type="text"
											required="required">
									</div>
								</div>
								<div class="control-group">
									<span class="table-contato-th">Mensagem</span>
									<div class="controls">
										<textarea cols="40" id="mensagem"
											style="border: 1px solid #e5e5e5; width: 95%;"
											name="mensagem" rows="6"></textarea>
									</div>
								</div>
								<div class="control-group">
									<input id="id_hostname" name="hostname" type="hidden">
									<div class="controls">
										<button type="button" class="btn-excluir-endereco"
											id="btn-fale-conosco">Enviar</button>
									</div>
								</div>
							</div>
						</form>
						<div class="col-md-6">
							<div>
								<br>
								<table class="table">
									<tbody>
										<tr>
											<th scope="row"><span class="table-contato-th">Razão Social</span></th>
											<td><span class="table-contato-td"><?=NOME_LOJA;?> LTDA</span></td>
										</tr>
										<tr style="display: none;">
											<th scope="row"><span class="table-contato-th">CNPJ</span></th>
											<td style="display: none;"><span class="table-contato-td">20.747.907/0001-26</span></td>
										</tr>
										<tr>
											<th scope="row"><span class="table-contato-th">E-mail</span></th>
											<td><span class="table-contato-td"><?=EMAIL_CONTATO;?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
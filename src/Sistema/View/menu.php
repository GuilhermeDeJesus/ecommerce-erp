<div class="col-md-3 left_col">
	<div class="left_col scroll-view">
		<div class="profile">
			<div class="profile_pic">
				<img src="public/img/icons/user.png" alt="..."
					class="img-circle profile_img">
			</div>
			<div class="profile_info">
				<span>Bem Vindo,</span>
				<h2><?=$_SESSION['usuario'];?></h2>
				<br /> <br />
			</div>
		</div>
		<br>
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			<div class="menu_section">
				<ul class="nav side-menu">
					<li><a><i class="fa fa-home"></i> Início <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=painel">Dashboard</a></li>
						</ul></li>
					<li><a><i class="fa fa-shopping-cart"></i> Vendas <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=venda&a=_pedidos&	b2w=0">Todos os pedidos</a></li>
							<li><a href="?m=sistema&c=venda&a=_pedidos&	b2w=1">Pedidos B2W</a></li>
							<li><a href="?m=sistema&c=venda&a=importar">Importar Pedidos</a></li>
							<li><a href="?m=sistema&c=venda&a=importarRastreamento">Importar
									Rastreamento</a></li>
							<li><a href="?m=sistema&c=correios&a=minhasEtiquetas">Minhas Etiquetas</a></li>	
							<li><a href="?m=sistema&c=nf&a=index">Notas Fiscais</a></li>		
							<li><a href="?m=sistema&c=venda&a=relatorio">Relatórios</a></li>							
						</ul></li>
					<li><a><i class="fa fa-desktop"></i> Produtos <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=produto">Todos os produtos</a></li>
							<li><a href="?m=sistema&c=produto&a=inserirEditar">Cadastrar Novo Produto</a></li>
							<li><a href="?m=sistema&c=produto&a=tabela">Tabela de Preços</a></li>
							<li><a href="?m=sistema&c=categoria">Categorias</a></li>
							<li><a href="?m=sistema&c=marca">Marcas</a></li>
							<li><a href="?m=sistema&c=produto&a=metricas">Gráficos</a></li>
						</ul></li>
					<li><a><i class="fa fa-money"></i> Lançamentos <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=lancamento">Todos os lançamentos</a></li>
						</ul></li>						
					<li><a><i class="fa fa-user"></i> Clientes <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=cliente&a=clientes">Todos os clientes</a></li>
							<li><a href="?m=sistema&c=comentarios">Comentários</a></li>
						</ul></li>
					<li><a><i class="fa fa-users"></i> Colaboradores <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=pessoa">Todos os colaboradores</a></li>
						</ul></li>
					<li><a><i class="fa fa-cog"></i> Conta <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="?m=sistema&c=plataforma">Configurações</a></li>
						</ul></li>					
				</ul>
			</div>
			<div class="menu_section" style="display: none;">
				<h3>Live On</h3>
				<ul class="nav side-menu">
					<li><a><i class="fa fa-bug"></i> Additional Pages <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="e_commerce.html">E-commerce</a></li>
							<li><a href="projects.html">Projects</a></li>
							<li><a href="project_detail.html">Project Detail</a></li>
							<li><a href="contacts.html">Contacts</a></li>
							<li><a href="profile.html">Profile</a></li>
						</ul></li>
					<li><a><i class="fa fa-windows"></i> Extras <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="page_403.html">403 Error</a></li>
							<li><a href="page_404.html">404 Error</a></li>
							<li><a href="page_500.html">500 Error</a></li>
							<li><a href="plain_page.html">Plain Page</a></li>
							<li><a href="login.html">Login Page</a></li>
							<li><a href="pricing_tables.html">Pricing Tables</a></li>
						</ul></li>
					<li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span
							class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="#level1_1">Level One</a>
							
							<li><a>Level One<span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li class="sub_menu"><a href="level2.html">Level Two</a></li>
									<li><a href="#level2_1">Level Two</a></li>
									<li><a href="#level2_2">Level Two</a></li>
								</ul></li>
							<li><a href="#level1_2">Level One</a></li>
						</ul></li>
					<li><a href="javascript:void(0)"><i class="fa fa-laptop"></i>
							Landing Page <span class="label label-success pull-right">Coming
								Soon</span></a></li>
				</ul>
			</div>
		</div>
		<div class="sidebar-footer hidden-small">
			<a href="?m=sistema&c=plataforma" data-toggle="tooltip"
				data-placement="top" title="Configurações"><span
				class="glyphicon glyphicon-cog" aria-hidden="true"></span> </a> <a
				data-toggle="tooltip" data-placement="top" title="FullScreen"> <span
				class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
			</a> <a data-toggle="tooltip" data-placement="top" title="Lock"> <span
				class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
			</a> <a data-toggle="tooltip" data-placement="top" title="Sair"
				href="?m=sistema&c=login&a=logout"> <span
				class="glyphicon glyphicon-off" aria-hidden="true"></span>
			</a>
		</div>
	</div>
</div>
<!-- top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="" role="navigation">
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>

			<ul class="nav navbar-nav navbar-right">
				<li class=""><a href="javascript:;"
					class="user-profile dropdown-toggle" data-toggle="dropdown"
					aria-expanded="false"><?=$_SESSION['nome'];?> <span class=" fa fa-angle-down"></span>
				</a>
					<ul class="dropdown-menu dropdown-usermenu pull-right">
						<li><a href="javascript:;"> Perfil</a></li>
						<li><a href="javascript:;"> <span class="badge bg-red pull-right">50%</span>
								<span>Configurações</span>
						</a></li>
						<li><a href="javascript:;">Ajuda</a></li>
						<li><a href="?m=sistema&c=login&a=logout"><i
								class="fa fa-sign-out pull-right"></i> Sair</a></li>
					</ul></li>

				<li role="presentation" class="dropdown"><a href="javascript:;"
					class="dropdown-toggle info-number" data-toggle="dropdown"
					aria-expanded="false"> <i class="fa fa-envelope-o"></i> <span
						class="badge bg-green">6</span>
				</a>
					<ul id="menu1" class="dropdown-menu list-unstyled msg_list"
						role="menu">
						<li><a> <span class="image"><img src="public/admin/images/img.jpg"
									alt="Profile Image" /></span> <span> <span>John Smith</span> <span
									class="time">3 mins ago</span>
							</span> <span class="message"> Film festivals used to be
									do-or-die moments for movie makers. They were where... </span>
						</a></li>
						<li><a> <span class="image"><img src="public/admin/images/img.jpg"
									alt="Profile Image" /></span> <span> <span>John Smith</span> <span
									class="time">3 mins ago</span>
							</span> <span class="message"> Film festivals used to be
									do-or-die moments for movie makers. They were where... </span>
						</a></li>
						<li><a> <span class="image"><img src="public/admin/images/img.jpg"
									alt="Profile Image" /></span> <span> <span>John Smith</span> <span
									class="time">3 mins ago</span>
							</span> <span class="message"> Film festivals used to be
									do-or-die moments for movie makers. They were where... </span>
						</a></li>
						<li><a> <span class="image"><img src="public/admin/images/img.jpg"
									alt="Profile Image" /></span> <span> <span>John Smith</span> <span
									class="time">3 mins ago</span>
							</span> <span class="message"> Film festivals used to be
									do-or-die moments for movie makers. They were where... </span>
						</a></li>
						<li>
							<div class="text-center">
								<a> <strong>See All Alerts</strong> <i class="fa fa-angle-right"></i>
								</a>
							</div>
						</li>
					</ul></li>
			</ul>
		</nav>
	</div>
</div>

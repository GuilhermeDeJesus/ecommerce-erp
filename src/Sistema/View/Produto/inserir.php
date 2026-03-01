<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Adicionar/Editar produto</title>

<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/google-code-prettify/bin/prettify.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/select2/dist/css/select2.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/switchery/dist/switchery.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/starrr/dist/starrr.css"
	rel="stylesheet">
<link href="public/admin/vendors/dropzone/dist/min/dropzone.min.css"
	rel="stylesheet">
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">

<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>

</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
		<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3><?=($data['produto'][0]['id'] != '') ? 'Editar' : 'Adicionar'; ?> produto</h3>
						</div>
						<div class="title_right">
							<div
								class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Pesquisar">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button">OK</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Informações Gerais</h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown"><a href="#" class="dropdown-toggle"
											data-toggle="dropdown" role="button" aria-expanded="false"><i
												class="fa fa-wrench"></i></a>
											<ul class="dropdown-menu" role="menu">
												<li><a href="#">Settings 1</a></li>
												<li><a href="#">Settings 2</a></li>
											</ul></li>
										<li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
											<li role="presentation" class="active"><a
												href="#tab_content1" id="home-tab" role="tab"
												data-toggle="tab" aria-expanded="true">Dados Cadastrais</a></li>
											<li role="presentation" class=""><a href="#tab_content2"
												role="tab" id="profile-tab" data-toggle="tab"
												aria-expanded="false">Imagens</a></li>
											<li role="presentation" class=""><a href="#tab_content3"
												role="tab" id="profile-tab" data-toggle="tab"
												aria-expanded="false">Cores</a></li>
											<li role="presentation" class=""><a href="#tab_content4"
												role="tab" id="profile-tab" data-toggle="tab"
												aria-expanded="false">Tamanhos</a></li>
											<li role="presentation" class=""><a href="#tab_content5"
												role="tab" id="profile-tab" data-toggle="tab"
												aria-expanded="false">Uploads</a></li>
											<li role="presentation" class=""><a href="#tab_content6"
												role="tab" id="profile-tab" data-toggle="tab"
												aria-expanded="false">Duplicar Produto</a></li>
										</ul>
										<form action="?m=sistema&c=produto&a=cadastrar" method="post"
											enctype="multipart/form-data"
											class="form-horizontal form-label-left">
											<div id="myTabContent" class="tab-content">
												<br>
												<div role="tabpanel" class="tab-pane fade active in"
													id="tab_content1" aria-labelledby="home-tab">
													<input type="hidden" name="id"
														value="<?= (isset($data['produto'][0]['id'])) ? $data['produto'][0]['id'] : '' ?>" />
													<div class="row">
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Descrição</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="descricao"
																	value="<?= (isset($data['produto'][0]['descricao'])) ? $data['produto'][0]['descricao'] : '' ?>"
																	class="form-control" placeholder="Descrição">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Redução
																IVA ST</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="reducao_iva_st"
																	value="<?= (isset($data['produto'][0]['reducao_iva_st'])) ? $data['produto'][0]['reducao_iva_st'] : '' ?>"
																	class="form-control" placeholder="Redução IVA ST">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group"
															style="display: none;">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">%
																Lucro</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="lucro"
																	value="<?= (isset($data['produto'][0]['lucro'])) ? $data['produto'][0]['lucro'] : '' ?>"
																	class="form-control" placeholder="% Lucro">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">R$
																Lucro</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="lucro_reais"
																	value="<?= (isset($data['produto'][0]['lucro_reais'])) ? $data['produto'][0]['lucro_reais'] : '' ?>"
																	class="form-control" placeholder="R$ Lucro">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Estoque</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="unidade"
																	value="<?= (isset($data['produto'][0]['unidade'])) ? $data['produto'][0]['unidade'] : '' ?>"
																	class="form-control"
																	placeholder="Quantidade de Produto disponível">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Custo</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="valor_compra"
																	value="<?= (isset($data['produto'][0]['valor_compra'])) ? $data['produto'][0]['valor_compra'] : '' ?>"
																	class="form-control"
																	placeholder="Valor Compra / Custo do Parceiro">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Valor
																Venda</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="valor_venda"
																	value="<?= (isset($data['produto'][0]['valor_venda'])) ? $data['produto'][0]['valor_venda'] : '' ?>"
																	class="form-control" placeholder="Valor Venda">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Valor
																Venda (B2W)</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="valor_venda_b2w"
																	value="<?= (isset($data['produto'][0]['valor_venda_b2w'])) ? $data['produto'][0]['valor_venda_b2w'] : '' ?>"
																	class="form-control" placeholder="Valor Venda na B2W">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Valor
																Sem Oferta</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="valor_sem_oferta"
																	value="<?= (isset($data['produto'][0]['valor_sem_oferta'])) ? $data['produto'][0]['valor_sem_oferta'] : '' ?>"
																	class="form-control" placeholder="Valor sem o desconto">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">SKU</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="sku"
																	value="<?= (isset($data['produto'][0]['sku'])) ? $data['produto'][0]['sku'] : '' ?>"
																	class="form-control" placeholder="SKU">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">EAN</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="ean"
																	value="<?= (isset($data['produto'][0]['ean'])) ? $data['produto'][0]['ean'] : '' ?>"
																	class="form-control" placeholder="EAN">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Píxel
																Facebook</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="pixel"
																	value="<?= (isset($data['produto'][0]['pixel'])) ? $data['produto'][0]['pixel'] : '' ?>"
																	class="form-control" placeholder="Píxel Facebook">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome
																Produto</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="nome_produto"
																	value="<?= (isset($data['produto'][0]['nome_produto'])) ? $data['produto'][0]['nome_produto'] : '' ?>"
																	class="form-control"
																	placeholder="Nome Produto para Envio de Mensagens no WhatSapp">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Preço
																em dolar a pagar para o fornecedor</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="preco_dolar_fornecedor"
																	value="<?= (isset($data['produto'][0]['preco_dolar_fornecedor'])) ? $data['produto'][0]['preco_dolar_fornecedor'] : '' ?>"
																	class="form-control"
																	placeholder="Preço em dolar a pagar para o fornecedor">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">NCM</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="ncm"
																	value="<?= (isset($data['produto'][0]['ncm'])) ? $data['produto'][0]['ncm'] : '' ?>"
																	class="form-control" placeholder="NCM">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Cupom</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cupom_desconto"
																	value="<?= (isset($data['produto'][0]['cupom_desconto'])) ? $data['produto'][0]['cupom_desconto'] : '' ?>"
																	class="form-control" placeholder="Cupom de Desconto">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																do Cupom</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="link_compra_upnid_cupom"
																	value="<?= (isset($data['produto'][0]['link_compra_upnid_cupom'])) ? $data['produto'][0]['link_compra_upnid_cupom'] : '' ?>"
																	class="form-control"
																	placeholder="Link Cupom de Desconto">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																Produto Aliexpress</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="link_compra"
																	value="<?= (isset($data['produto'][0]['link_compra'])) ? $data['produto'][0]['link_compra'] : '' ?>"
																	class="form-control"
																	placeholder="Link Produto no Site do Fornecedor">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																de Venda Direta</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="link_compra_upnid"
																	value="<?= (isset($data['produto'][0]['link_compra_upnid'])) ? $data['produto'][0]['link_compra_upnid'] : '' ?>"
																	class="form-control"
																	placeholder="Link de Venda Direta (Quando o cliente clicar em Comprar Agora)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																de Venda na Upnid para Boleto</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="link_compra_upnid_boleto"
																	value="<?= (isset($data['produto'][0]['link_compra_upnid_boleto'])) ? $data['produto'][0]['link_compra_upnid_boleto'] : '' ?>"
																	class="form-control"
																	placeholder="Link de Venda na Upnid para Boleto">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																de Venda no Mercado Pago</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="link_compra_mercado_pago"
																	value="<?= (isset($data['produto'][0]['link_compra_mercado_pago'])) ? $data['produto'][0]['link_compra_mercado_pago'] : '' ?>"
																	class="form-control"
																	placeholder="Link de Venda no Mercado Pago">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Marca</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select class="form-control" name="id_marca">
																	<option
																		value="<?= (isset($data['produto'][0]['id_marca'])) ? $data['produto'][0]['id_marca'] : '' ?>"><?= (isset($data['produto'][0]['id_marca'])) ? dao('Core', 'Marca')->getField('nome', $data['produto'][0]['id_marca']) : '' ?></option>
                                        							<?php foreach ($data['marcas'] as $pval => $pkey) { ?>
                                        							<option
																		value="<?=$pkey['id']?>"><?=$pkey['nome']?></option>
                                        							<?php } ?>
                                        						</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Não
																tem marca ? Digite Aqui!</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cad_marca"
																	value="<?= (isset($data['produto'][0]['cad_marca'])) ? $data['cad_marca'][0]['cad_marca'] : '' ?>"
																	class="form-control"
																	placeholder="Só preencha este campo caso não tenha a marca na lista acima">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Categoria</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select class="form-control" name="id_categoria"
																	required="required">
																	<option
																		value="<?= (isset($data['produto'][0]['id_categoria'])) ? $data['produto'][0]['id_categoria'] : '' ?>"><?= (isset($data['produto'][0]['id_categoria'])) ? dao('Core', 'Categoria')->getField('descricao', $data['produto'][0]['id_categoria']) : '' ?></option>
                                        							<?php foreach ($data['categorias'] as $pval => $pkey) { ?>
                                        							<option
																		value="<?=$pkey['id']?>"><?=dao('Site', 'Categoria')->getCategoriaFather($pkey['categoria_pai']).$pkey['descricao']?></option>
                                        							<?php } ?>
                                        						</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Fornecedor</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select class="form-control" name="id_fornecedor"
																	required="required">
																	<option
																		value="<?= (isset($data['produto'][0]['id_fornecedor'])) ? $data['produto'][0]['id_fornecedor'] : '' ?>"><?= (isset($data['produto'][0]['id_fornecedor'])) ? dao('Core', 'Pessoa')->getField('nome', $data['produto'][0]['id_fornecedor']) : '' ?></option>
                                        							<?php foreach ($data['fornecedores'] as $pval => $pkey) { ?>
                                        							<option
																		value="<?=$pkey['id']?>"><?=$pkey['nome']?></option>
                                        							<?php } ?>
                                        						</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group"
															style="display: none;">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Código
																de Barras</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="codigo_de_barras"
																	value="<?= (isset($data['produto'][0]['codigo_de_barras'])) ? $data['produto'][0]['codigo_de_barras'] : '' ?>"
																	class="form-control"
																	placeholder="0000000000000000000000">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group"
															style="display: none;">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Descrição
																do Despacho</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="descricao_despacho"
																	value="<?= (isset($data['produto'][0]['descricao_despacho'])) ? $data['produto'][0]['descricao_despacho'] : '' ?>"
																	class="form-control"
																	placeholder="Ex.: Parcele em até 12X de R$ 20,17 Sem Juros">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Prazo
																de Entrega</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="prazo_entrega"
																	value="<?= (isset($data['produto'][0]['prazo_entrega'])) ? $data['produto'][0]['prazo_entrega'] : '' ?>"
																	class="form-control"
																	placeholder="Ex.: Tempo de entrega estimado 14-30 dias">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Descrição
																do Cabeçalho</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="descricao_cabecalho"
																	value="<?= (isset($data['produto'][0]['descricao_cabecalho'])) ? $data['produto'][0]['descricao_cabecalho'] : '' ?>"
																	class="form-control"
																	placeholder="Descrição do cabeçalho">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="">
																	<label> <input type="checkbox" class="js-switch"
																		name="ativo" checked /> Ativo
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="">
																	<label> <input type="checkbox" class="js-switch"
																		name="frete_gratis" /> Frete Grátis
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="">
																	<label> <input type="checkbox" class="js-switch"
																		name="produto_gratis" /> Oferta Produto Grátis
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="">
																	<label> <input type="checkbox" class="js-switch"
																		name="skyhub" /> Inserir/Atualizar Produto na SkyHub
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Comprimento</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="comprimento"
																	value="<?= (isset($data['produto'][0]['comprimento'])) ? $data['produto'][0]['comprimento'] : '' ?>"
																	class="form-control" placeholder="Comprimento em cm">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Largura</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="largura"
																	value="<?= (isset($data['produto'][0]['largura'])) ? $data['produto'][0]['largura'] : '' ?>"
																	class="form-control" placeholder="Largura em cm">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Altura</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="altura"
																	value="<?= (isset($data['produto'][0]['altura'])) ? $data['produto'][0]['altura'] : '' ?>"
																	class="form-control" placeholder="Altura cm">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Peso
																Bruto</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="peso_bruto"
																	value="<?= (isset($data['produto'][0]['peso_bruto'])) ? $data['produto'][0]['peso_bruto'] : '' ?>"
																	class="form-control" placeholder="Peso Bruto | Gramas">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Peso
																Líquido</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="peso_liquido"
																	value="<?= (isset($data['produto'][0]['peso_liquido'])) ? $data['produto'][0]['peso_liquido'] : '' ?>"
																	class="form-control"
																	placeholder="Peso Líquido | Gramas">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Sobre</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="btn-toolbar editor"
																	data-role="editor-toolbar" data-target="#editor">
																	<div class="btn-group">
																		<a class="btn dropdown-toggle" data-toggle="dropdown"
																			title="Font"><i class="fa fa-font"></i><b
																			class="caret"></b></a>
																		<ul class="dropdown-menu">
																		</ul>
																	</div>
																	<div class="btn-group">
																		<a class="btn dropdown-toggle" data-toggle="dropdown"
																			title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b
																			class="caret"></b></a>
																		<ul class="dropdown-menu">
																			<li><a data-edit="fontSize 5">
																					<p style="font-size: 17px">Huge</p>
																			</a></li>
																			<li><a data-edit="fontSize 3">
																					<p style="font-size: 14px">Normal</p>
																			</a></li>
																			<li><a data-edit="fontSize 1">
																					<p style="font-size: 11px">Small</p>
																			</a></li>
																		</ul>
																	</div>

																	<div class="btn-group">
																		<a class="btn" data-edit="bold"
																			title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
																		<a class="btn" data-edit="italic"
																			title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
																		<a class="btn" data-edit="strikethrough"
																			title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
																		<a class="btn" data-edit="underline"
																			title="Underline (Ctrl/Cmd+U)"><i
																			class="fa fa-underline"></i></a>
																	</div>

																	<div class="btn-group">
																		<a class="btn" data-edit="insertunorderedlist"
																			title="Bullet list"><i class="fa fa-list-ul"></i></a>
																		<a class="btn" data-edit="insertorderedlist"
																			title="Number list"><i class="fa fa-list-ol"></i></a>
																		<a class="btn" data-edit="outdent"
																			title="Reduce indent (Shift+Tab)"><i
																			class="fa fa-dedent"></i></a> <a class="btn"
																			data-edit="indent" title="Indent (Tab)"><i
																			class="fa fa-indent"></i></a>
																	</div>

																	<div class="btn-group">
																		<a class="btn" data-edit="justifyleft"
																			title="Align Left (Ctrl/Cmd+L)"><i
																			class="fa fa-align-left"></i></a> <a class="btn"
																			data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i
																			class="fa fa-align-center"></i></a> <a class="btn"
																			data-edit="justifyright"
																			title="Align Right (Ctrl/Cmd+R)"><i
																			class="fa fa-align-right"></i></a> <a class="btn"
																			data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i
																			class="fa fa-align-justify"></i></a>
																	</div>

																	<div class="btn-group">
																		<a class="btn dropdown-toggle" data-toggle="dropdown"
																			title="Hyperlink"><i class="fa fa-link"></i></a>
																		<div class="dropdown-menu input-append">
																			<input class="span2" placeholder="URL" type="text"
																				data-edit="createLink" />
																			<button class="btn" type="button">Add</button>
																		</div>
																		<a class="btn" data-edit="unlink"
																			title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
																	</div>

																	<div class="btn-group">
																		<a class="btn"
																			title="Insert picture (or just drag & drop)"
																			id="pictureBtn"><i class="fa fa-picture-o"></i></a> <input
																			type="file" data-role="magic-overlay"
																			data-target="#pictureBtn" data-edit="insertImage" />
																	</div>

																	<div class="btn-group">
																		<a class="btn" data-edit="undo"
																			title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
																		<a class="btn" data-edit="redo"
																			title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
																	</div>
																</div>

																<div id="editor" class="editor-wrapper"></div>
																<textarea name="descr" id="sobre" style="display: none;"></textarea>
																<div class="form-group">
																	<label
																		class="control-label col-md-3 col-sm-3 col-xs-12">Cópie
																		todo texto aqui!</label>
																	<div class="col-md-9 col-sm-9 col-xs-12">
																		<textarea class="resizable_textarea form-control"
																			name="sobre"
																			placeholder="Cópie o texto sobre o produto aqui...."><?= (isset($data['produto'][0]['sobre'])) ? $data['produto'][0]['sobre'] : '' ?></textarea>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
																<button type="submit" class="btn btn-success">Salvar</button>
															</div>
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content2"
													aria-labelledby="profile-tab">
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															1</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_1">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															2</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_2">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															3</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_3">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															4</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_4">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															5</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_5">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															6</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_6">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															7</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_7">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															8</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_8">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															9</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_9">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															10</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_10">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															11</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_11">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															12</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_12">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															13</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_13">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															14</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_14">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															15</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_15">
															</div>
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content3"
													aria-labelledby="profile-tab">
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">1º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_1"><br> <input type="text" name="cor_1"
																value="" class="form-control" placeholder="1º Cor"><br>
															<input type="text" name="link_venda_cor_1" value=""
																class="form-control" placeholder="Link Venda 1">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">2º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_2"><br> <input type="text" name="cor_2"
																value="" class="form-control" placeholder="2º Cor"><br>
															<input type="text" name="link_venda_cor_2" value=""
																class="form-control" placeholder="Link Venda 2">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">3º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_3"><br> <input type="text" name="cor_3"
																value="" class="form-control" placeholder="3º Cor"><br>
															<input type="text" name="link_venda_cor_3" value=""
																class="form-control" placeholder="Link Venda 3">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">4º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_4"><br> <input type="text" name="cor_4"
																value="" class="form-control" placeholder="4º Cor"><br>
															<input type="text" name="link_venda_cor_4" value=""
																class="form-control" placeholder="Link Venda 4">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">5º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_5"><br> <input type="text" name="cor_5"
																value="" class="form-control" placeholder="5º Cor"><br>
															<input type="text" name="link_venda_cor_5" value=""
																class="form-control" placeholder="Link Venda 5">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">6º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_6"><br> <input type="text" name="cor_6"
																value="" class="form-control" placeholder="6º Cor"><br>
															<input type="text" name="link_venda_cor_6" value=""
																class="form-control" placeholder="Link Venda 6">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">7º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_7"><br> <input type="text" name="cor_7"
																value="" class="form-control" placeholder="7º Cor"><br>
															<input type="text" name="link_venda_cor_7" value=""
																class="form-control" placeholder="Link Venda 7">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">8º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_8"><br> <input type="text" name="cor_8"
																value="" class="form-control" placeholder="8º Cor"><br>
															<input type="text" name="link_venda_cor_8" value=""
																class="form-control" placeholder="Link Venda 8">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">9º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_9"><br> <input type="text" name="cor_9"
																value="" class="form-control" placeholder="9º Cor"><br>
															<input type="text" name="link_venda_cor_9" value=""
																class="form-control" placeholder="Link Venda 9">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">10º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_10"><br> <input type="text" name="cor_10"
																value="" class="form-control" placeholder="10º Cor"><br>
															<input type="text" name="link_venda_cor_10" value=""
																class="form-control" placeholder="Link Venda 10">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">11º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_11"><br> <input type="text" name="cor_11"
																value="" class="form-control" placeholder="11º Cor"><br>
															<input type="text" name="link_venda_cor_11" value=""
																class="form-control" placeholder="Link Venda 11">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">12º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_12"><br> <input type="text" name="cor_12"
																value="" class="form-control" placeholder="12º Cor"><br>
															<input type="text" name="link_venda_cor_12" value=""
																class="form-control" placeholder="Link Venda 12">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">13º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_13"><br> <input type="text" name="cor_13"
																value="" class="form-control" placeholder="13º Cor"><br>
															<input type="text" name="link_venda_cor_13" value=""
																class="form-control" placeholder="Link Venda 13">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">14º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_14"><br> <input type="text" name="cor_14"
																value="" class="form-control" placeholder="14º Cor"><br>
															<input type="text" name="link_venda_cor_14" value=""
																class="form-control" placeholder="Link Venda 14">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">15º
															Cor</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="file" class="form-control-file"
																name="cors_15"><br> <input type="text" name="cor_15"
																value="" class="form-control" placeholder="15º Cor"><br>
															<input type="text" name="link_venda_cor_15" value=""
																class="form-control" placeholder="Link Venda 15">
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content4"
													aria-labelledby="profile-tab">
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															1</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_1"
																value="<?= (isset($data['tamanhos'][0]['descricao'])) ? $data['tamanhos'][0]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"> <br> <input
																type="text" name="valor_tamanho_1"
																value="<?= (isset($data['tamanhos'][0]['valor'])) ? $data['tamanhos'][0]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"> <br> <input
																type="text" name="custo_tamanho_1"
																value="<?= (isset($data['tamanhos'][0]['custo'])) ? $data['tamanhos'][0]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_1"
																value="<?= (isset($data['tamanhos'][0]['link_venda'])) ? $data['tamanhos'][0]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_1"
																value="<?= (isset($data['tamanhos'][0]['sku'])) ? $data['tamanhos'][0]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_1"
																value="<?= (isset($data['tamanhos'][0]['estoque'])) ? $data['tamanhos'][0]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															2</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_2"
																value="<?= (isset($data['tamanhos'][1]['descricao'])) ? $data['tamanhos'][1]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"><br> <input
																type="text" name="valor_tamanho_2"
																value="<?= (isset($data['tamanhos'][1]['valor'])) ? $data['tamanhos'][1]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"><br> <input
																type="text" name="custo_tamanho_2"
																value="<?= (isset($data['tamanhos'][1]['custo'])) ? $data['tamanhos'][1]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_2"
																value="<?= (isset($data['tamanhos'][1]['link_venda'])) ? $data['tamanhos'][1]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_2"
																value="<?= (isset($data['tamanhos'][1]['sku'])) ? $data['tamanhos'][1]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_2"
																value="<?= (isset($data['tamanhos'][1]['estoque'])) ? $data['tamanhos'][1]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															3</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_3"
																value="<?= (isset($data['tamanhos'][2]['descricao'])) ? $data['tamanhos'][2]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"><br> <input
																type="text" name="valor_tamanho_3"
																value="<?= (isset($data['tamanhos'][2]['valor'])) ? $data['tamanhos'][2]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"><br> <input
																type="text" name="custo_tamanho_3"
																value="<?= (isset($data['tamanhos'][2]['custo'])) ? $data['tamanhos'][2]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_3"
																value="<?= (isset($data['tamanhos'][2]['link_venda'])) ? $data['tamanhos'][2]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_3"
																value="<?= (isset($data['tamanhos'][2]['sku'])) ? $data['tamanhos'][2]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_3"
																value="<?= (isset($data['tamanhos'][2]['estoque'])) ? $data['tamanhos'][2]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															4</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_4"
																value="<?= (isset($data['tamanhos'][3]['descricao'])) ? $data['tamanhos'][3]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"><br> <input
																type="text" name="valor_tamanho_4"
																value="<?= (isset($data['tamanhos'][3]['valor'])) ? $data['tamanhos'][3]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"><br> <input
																type="text" name="custo_tamanho_4"
																value="<?= (isset($data['tamanhos'][3]['custo'])) ? $data['tamanhos'][3]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_4"
																value="<?= (isset($data['tamanhos'][3]['link_venda'])) ? $data['tamanhos'][3]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_4"
																value="<?= (isset($data['tamanhos'][3]['sku'])) ? $data['tamanhos'][3]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_4"
																value="<?= (isset($data['tamanhos'][3]['estoque'])) ? $data['tamanhos'][3]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															5</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_5"
																value="<?= (isset($data['tamanhos'][4]['descricao'])) ? $data['tamanhos'][4]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"><br> <input
																type="text" name="valor_tamanho_5"
																value="<?= (isset($data['tamanhos'][4]['valor'])) ? $data['tamanhos'][4]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"> <br> <input
																type="text" name="custo_tamanho_4"
																value="<?= (isset($data['tamanhos'][4]['custo'])) ? $data['tamanhos'][4]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_5"
																value="<?= (isset($data['tamanhos'][4]['link_venda'])) ? $data['tamanhos'][4]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_5"
																value="<?= (isset($data['tamanhos'][4]['sku'])) ? $data['tamanhos'][4]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_5"
																value="<?= (isset($data['tamanhos'][4]['estoque'])) ? $data['tamanhos'][4]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Tamanho
															6</label>
														<div class="col-md-9 col-sm-9 col-xs-12"
															style="border: 1pX solid #CCC; padding: 30px; border-radius: 5px;">
															<input type="text" name="tamanho_6"
																value="<?= (isset($data['tamanhos'][5]['descricao'])) ? $data['tamanhos'][5]['descricao'] : '' ?>"
																class="form-control" placeholder="Tamanho"><br> <input
																type="text" name="valor_tamanho_6"
																value="<?= (isset($data['tamanhos'][5]['valor'])) ? $data['tamanhos'][5]['valor'] : '' ?>"
																class="form-control" placeholder="Valor"> <br> <input
																type="text" name="custo_tamanho_6"
																value="<?= (isset($data['tamanhos'][5]['custo'])) ? $data['tamanhos'][5]['custo'] : '' ?>"
																class="form-control" placeholder="Custo"> <br> <input
																type="text" name="link_venda_6"
																value="<?= (isset($data['tamanhos'][5]['link_venda'])) ? $data['tamanhos'][5]['link_venda'] : '' ?>"
																class="form-control" placeholder="Link Venda"><br> <input
																type="text" name="sku_6"
																value="<?= (isset($data['tamanhos'][5]['sku'])) ? $data['tamanhos'][5]['sku'] : '' ?>"
																class="form-control" placeholder="SKU"> <br> <input
																type="text" name="estoque_6"
																value="<?= (isset($data['tamanhos'][5]['estoque'])) ? $data['tamanhos'][5]['estoque'] : '' ?>"
																class="form-control" placeholder="Estoque">
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content5"
													aria-labelledby="profile-tab">
													<?php
                                                    $imgs = "data/products/" . $data['produto'][0]['id'] . "/";
                                                    $files = scandir($imgs);
                                                    $idImg = 0;
                                                    foreach ($files as $file) {
                                                        if (strlen($file) > 4) {
                                                            $idImg ++;
                                                            ?>
													<div class="col-md-55" id="div-img-<?=$idImg?>">
														<div class="">
															<div class="image view view-first">
																<img style="width: 100%; display: block;"
																	src="data/products/<?=$data['produto'][0]['id'];?>/<?=$file;?>"
																	alt="image" />
																<div class="mask">
																	<div class="tools tools-bottom">
																		<a
																			href="?m=sistema&c=produto&a=download_imagem&produto=<?=$data['produto'][0]['id'];?>&&imagem=<?=$file;?>"><i
																			class="fa fa-download"></i></a> <a href="#"
																			id="deletar_img_<?=$idImg;?>"><i class="fa fa-times"></i></a>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<script>
        											$(document).ready(function () {
        												$('#deletar_img_<?=$idImg;?>').click(function(e) {
        													$('#load-img-modal').css('display', 'inline-block');
        													$('body').css("opacity", "0.5");
        													                                                        	    	
															var idProduto = <?=$data['produto'][0]['id'];?>;
															var imagem = '<?=$file;?>';

															setTimeout(function(){ 
																$.ajax({
		                                            				type : 'POST',
		                                            				dataType : "text",
                                                    				async : false,
                                                    				url : "?m=sistema&c=produto&a=deletarImagem",
                                                    				data : {
                                                    					idProduto: idProduto,
                                                       					imagem: imagem
                                                    				},					  
                                                    				success: function(response){
                                                        				if(response){
                        													$('#load-img-modal').css('display', 'none');
                        													$('#div-img-<?=$idImg?>').css('display', 'none');
                        													$('body').css("opacity", "1");
                                                        				}else{
                        													$('#load-img-modal').css('display', 'none');
                        													$('body').css("opacity", "1");
                                                        				}
                                                    				},
                                                    			});
		                                        	 		}, 700);
                                                        });
        											 });
                                                    </script>
													<?php } ?>
													<?php } ?>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content6"
													aria-labelledby="profile-tab">
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<div class="col-md-9 col-sm-9 col-xs-12">
															<input type="text" name="descricao_dup" value=""
																class="form-control" placeholder="Descrição"><br>
															<button type="submit" name="duplicar" value="1" class="btn btn-success">Duplicar</button>
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer>
				<div class="pull-right">
				<?=NOME_LOJA;?> - Todos os direitos reservados <a
						href="https://<?=LINK_LOJA;?>"><?=NOME_LOJA;?></a>
				</div>
				<div class="clearfix"></div>
			</footer>
		</div>
	</div>
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="public/admin/vendors/fastclick/lib/fastclick.js"></script>
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<script
		src="public/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
	<script src="public/admin/vendors/iCheck/icheck.min.js"></script>
	<script src="public/admin/js/moment/moment.min.js"></script>
	<script src="public/admin/js/datepicker/daterangepicker.js"></script>
	<script
		src="public/admin/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
	<script src="public/admin/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
	<script src="public/admin/vendors/google-code-prettify/src/prettify.js"></script>
	<script
		src="public/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
	<script src="public/admin/vendors/switchery/dist/switchery.min.js"></script>
	<script src="public/admin/vendors/select2/dist/js/select2.full.min.js"></script>
	<script src="public/admin/vendors/parsleyjs/dist/parsley.min.js"></script>
	<script src="public/admin/vendors/autosize/dist/autosize.min.js"></script>
	<script
		src="public/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
	<script src="public/admin/vendors/starrr/dist/starrr.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>
	<script src="public/admin/vendors/dropzone/dist/min/dropzone.min.js"></script>
	<script
		src="public/admin/vendors/jquery.inputmask/dist/inputmask/jquery.maskMoney.min.js"></script>
	<script>
		$("input[name='valor_venda']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_compra']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_sem_oferta']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='lucro']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
// 		$("input[name='peso_bruto']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
// 		$("input[name='peso_liquido']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='reducao_iva_st']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

		$("input[name='valor_tamanho_1']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_tamanho_2']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_tamanho_3']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_tamanho_4']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_tamanho_5']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_tamanho_6']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

		$("input[name='custo_tamanho_1']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='custo_tamanho_2']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='custo_tamanho_3']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='custo_tamanho_4']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='custo_tamanho_5']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='custo_tamanho_6']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    </script>

	<script>
      $(document).ready(function() {
		$('input[name=lucro]').keyup(function() { 
			var valLucro = parseFloat($('input[name=lucro]').val());
			var valorCompra = parseFloat($('input[name=valor_compra]').val());
			var lucro = (valorCompra/100) * valLucro;
			var valorVenda = lucro + valorCompra;
			$('input[name=valor_venda]').val(valorVenda);
			$('input[name=valor_sem_oferta]').val(valorVenda + lucro + lucro);
		});

		$('input[name=lucro_reais]').keyup(function() { 
			var valLucroReais = parseFloat($('input[name=lucro_reais]').val());
			var valorCompra = parseFloat($('input[name=valor_compra]').val());
			var ValorVenda =  valLucroReais + valorCompra;
			var VENDA_COM_COMISSAO_MARK = (ValorVenda/100) * 116;
			var VALOR_COMISSAO = (VENDA_COM_COMISSAO_MARK/100) * 16;
			ValorVendaB2W = ValorVenda + VALOR_COMISSAO;

			 // No vitas vou colocar 3% de desconto
			var valorVendaVitas = (ValorVendaB2W / 100) * 97;
			// No "de" "por" apenas, vou colocar 35% de desconto, nesse caso, vai somar 35%
			var valorVendaVitasSemOferta = (ValorVenda / 100) * 135; 

			// SHOPVITAS
			$('input[name=valor_venda]').val(valorVendaVitas);
			$('input[name=valor_sem_oferta]').val(valorVendaVitasSemOferta);

			// B2W
			$('input[name=valor_venda_b2w]').val(ValorVendaB2W);
		});
    	  
          
        $('#birthday').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
	<!-- /bootstrap-daterangepicker -->

	<!-- bootstrap-wysiwyg -->
	<script>
      $(document).ready(function() {
        function initToolbarBootstrapBindings() {
          var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
              'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
              'Times New Roman', 'Verdana'
            ],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
          $.each(fonts, function(idx, fontName) {
            fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
          });
          $('a[title]').tooltip({
            container: 'body'
          });
          $('.dropdown-menu input').click(function() {
              return false;
            })
            .change(function() {
              $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
            })
            .keydown('esc', function() {
              this.value = '';
              $(this).change();
            });

          $('[data-role=magic-overlay]').each(function() {
            var overlay = $(this),
              target = $(overlay.data('target'));
            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
          });

          if ("onwebkitspeechchange" in document.createElement("input")) {
            var editorOffset = $('#editor').offset();

            $('.voiceBtn').css('position', 'absolute').offset({
              top: editorOffset.top,
              left: editorOffset.left + $('#editor').innerWidth() - 35
            });
          } else {
            $('.voiceBtn').hide();
          }
        }

        function showErrorAlert(reason, detail) {
          var msg = '';
          if (reason === 'unsupported-file-type') {
            msg = "Unsupported format " + detail;
          } else {
            console.log("error uploading file", reason, detail);
          }
          $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        }

        initToolbarBootstrapBindings();

        $('#editor').wysiwyg({
          fileUploadError: showErrorAlert
        });

        window.prettyPrint;
        prettyPrint();
      });
    </script>
	<!-- /bootstrap-wysiwyg -->

	<!-- Select2 -->
	<script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "With Max Selection limit 4",
          allowClear: true
        });
      });
    </script>
	<!-- /Select2 -->

	<!-- jQuery Tags Input -->
	<script>
      function onAddTag(tag) {
        alert("Added a tag: " + tag);
      }

      function onRemoveTag(tag) {
        alert("Removed a tag: " + tag);
      }

      function onChangeTag(input, tag) {
        alert("Changed a tag: " + tag);
      }

      $(document).ready(function() {
        $('#tags_1').tagsInput({
          width: 'auto'
        });
      });
    </script>
	<!-- /jQuery Tags Input -->

	<!-- Parsley -->
	<script>
      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form .btn').on('click', function() {
          $('#demo-form').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });

      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form2 .btn').on('click', function() {
          $('#demo-form2').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form2').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}
    </script>
	<!-- /Parsley -->

	<!-- Autosize -->
	<script>
      $(document).ready(function() {
        autosize($('.resizable_textarea'));
      });
    </script>
	<!-- /Autosize -->

	<!-- jQuery autocomplete -->
	<script>
      $(document).ready(function() {
        var countries = { AD:"Andorra",A2:"Andorra Test",AE:"United Arab Emirates",AF:"Afghanistan",AG:"Antigua and Barbuda",AI:"Anguilla",AL:"Albania",AM:"Armenia",AN:"Netherlands Antilles",AO:"Angola",AQ:"Antarctica",AR:"Argentina",AS:"American Samoa",AT:"Austria",AU:"Australia",AW:"Aruba",AX:"Åland Islands",AZ:"Azerbaijan",BA:"Bosnia and Herzegovina",BB:"Barbados",BD:"Bangladesh",BE:"Belgium",BF:"Burkina Faso",BG:"Bulgaria",BH:"Bahrain",BI:"Burundi",BJ:"Benin",BL:"Saint Barthélemy",BM:"Bermuda",BN:"Brunei",BO:"Bolivia",BQ:"British Antarctic Territory",BR:"Brazil",BS:"Bahamas",BT:"Bhutan",BV:"Bouvet Island",BW:"Botswana",BY:"Belarus",BZ:"Belize",CA:"Canada",CC:"Cocos [Keeling] Islands",CD:"Congo - Kinshasa",CF:"Central African Republic",CG:"Congo - Brazzaville",CH:"Switzerland",CI:"Côte d’Ivoire",CK:"Cook Islands",CL:"Chile",CM:"Cameroon",CN:"China",CO:"Colombia",CR:"Costa Rica",CS:"Serbia and Montenegro",CT:"Canton and Enderbury Islands",CU:"Cuba",CV:"Cape Verde",CX:"Christmas Island",CY:"Cyprus",CZ:"Czech Republic",DD:"East Germany",DE:"Germany",DJ:"Djibouti",DK:"Denmark",DM:"Dominica",DO:"Dominican Republic",DZ:"Algeria",EC:"Ecuador",EE:"Estonia",EG:"Egypt",EH:"Western Sahara",ER:"Eritrea",ES:"Spain",ET:"Ethiopia",FI:"Finland",FJ:"Fiji",FK:"Falkland Islands",FM:"Micronesia",FO:"Faroe Islands",FQ:"French Southern and Antarctic Territories",FR:"France",FX:"Metropolitan France",GA:"Gabon",GB:"United Kingdom",GD:"Grenada",GE:"Georgia",GF:"French Guiana",GG:"Guernsey",GH:"Ghana",GI:"Gibraltar",GL:"Greenland",GM:"Gambia",GN:"Guinea",GP:"Guadeloupe",GQ:"Equatorial Guinea",GR:"Greece",GS:"South Georgia and the South Sandwich Islands",GT:"Guatemala",GU:"Guam",GW:"Guinea-Bissau",GY:"Guyana",HK:"Hong Kong SAR China",HM:"Heard Island and McDonald Islands",HN:"Honduras",HR:"Croatia",HT:"Haiti",HU:"Hungary",ID:"Indonesia",IE:"Ireland",IL:"Israel",IM:"Isle of Man",IN:"India",IO:"British Indian Ocean Territory",IQ:"Iraq",IR:"Iran",IS:"Iceland",IT:"Italy",JE:"Jersey",JM:"Jamaica",JO:"Jordan",JP:"Japan",JT:"Johnston Island",KE:"Kenya",KG:"Kyrgyzstan",KH:"Cambodia",KI:"Kiribati",KM:"Comoros",KN:"Saint Kitts and Nevis",KP:"North Korea",KR:"South Korea",KW:"Kuwait",KY:"Cayman Islands",KZ:"Kazakhstan",LA:"Laos",LB:"Lebanon",LC:"Saint Lucia",LI:"Liechtenstein",LK:"Sri Lanka",LR:"Liberia",LS:"Lesotho",LT:"Lithuania",LU:"Luxembourg",LV:"Latvia",LY:"Libya",MA:"Morocco",MC:"Monaco",MD:"Moldova",ME:"Montenegro",MF:"Saint Martin",MG:"Madagascar",MH:"Marshall Islands",MI:"Midway Islands",MK:"Macedonia",ML:"Mali",MM:"Myanmar [Burma]",MN:"Mongolia",MO:"Macau SAR China",MP:"Northern Mariana Islands",MQ:"Martinique",MR:"Mauritania",MS:"Montserrat",MT:"Malta",MU:"Mauritius",MV:"Maldives",MW:"Malawi",MX:"Mexico",MY:"Malaysia",MZ:"Mozambique",NA:"Namibia",NC:"New Caledonia",NE:"Niger",NF:"Norfolk Island",NG:"Nigeria",NI:"Nicaragua",NL:"Netherlands",NO:"Norway",NP:"Nepal",NQ:"Dronning Maud Land",NR:"Nauru",NT:"Neutral Zone",NU:"Niue",NZ:"New Zealand",OM:"Oman",PA:"Panama",PC:"Pacific Islands Trust Territory",PE:"Peru",PF:"French Polynesia",PG:"Papua New Guinea",PH:"Philippines",PK:"Pakistan",PL:"Poland",PM:"Saint Pierre and Miquelon",PN:"Pitcairn Islands",PR:"Puerto Rico",PS:"Palestinian Territories",PT:"Portugal",PU:"U.S. Miscellaneous Pacific Islands",PW:"Palau",PY:"Paraguay",PZ:"Panama Canal Zone",QA:"Qatar",RE:"Réunion",RO:"Romania",RS:"Serbia",RU:"Russia",RW:"Rwanda",SA:"Saudi Arabia",SB:"Solomon Islands",SC:"Seychelles",SD:"Sudan",SE:"Sweden",SG:"Singapore",SH:"Saint Helena",SI:"Slovenia",SJ:"Svalbard and Jan Mayen",SK:"Slovakia",SL:"Sierra Leone",SM:"San Marino",SN:"Senegal",SO:"Somalia",SR:"Suriname",ST:"São Tomé and Príncipe",SU:"Union of Soviet Socialist Republics",SV:"El Salvador",SY:"Syria",SZ:"Swaziland",TC:"Turks and Caicos Islands",TD:"Chad",TF:"French Southern Territories",TG:"Togo",TH:"Thailand",TJ:"Tajikistan",TK:"Tokelau",TL:"Timor-Leste",TM:"Turkmenistan",TN:"Tunisia",TO:"Tonga",TR:"Turkey",TT:"Trinidad and Tobago",TV:"Tuvalu",TW:"Taiwan",TZ:"Tanzania",UA:"Ukraine",UG:"Uganda",UM:"U.S. Minor Outlying Islands",US:"United States",UY:"Uruguay",UZ:"Uzbekistan",VA:"Vatican City",VC:"Saint Vincent and the Grenadines",VD:"North Vietnam",VE:"Venezuela",VG:"British Virgin Islands",VI:"U.S. Virgin Islands",VN:"Vietnam",VU:"Vanuatu",WF:"Wallis and Futuna",WK:"Wake Island",WS:"Samoa",YD:"People's Democratic Republic of Yemen",YE:"Yemen",YT:"Mayotte",ZA:"South Africa",ZM:"Zambia",ZW:"Zimbabwe",ZZ:"Unknown or Invalid Region" };

        var countriesArray = $.map(countries, function(value, key) {
          return {
            value: value,
            data: key
          };
        });

        // initialize autocomplete with custom appendTo
        $('#autocomplete-custom-append').autocomplete({
          lookup: countriesArray,
          appendTo: '#autocomplete-container'
        });
      });
    </script>
	<!-- /jQuery autocomplete -->

	<!-- Starrr -->
	<script>
      $(document).ready(function() {
        $(".stars").starrr();

        $('.stars-existing').starrr({
          rating: 4
        });

        $('.stars').on('starrr:change', function (e, value) {
          $('.stars-count').html(value);
        });

        $('.stars-existing').on('starrr:change', function (e, value) {
          $('.stars-count-existing').html(value);
        });
      });
    </script>
	<!-- /Starrr -->
</body>
</html>
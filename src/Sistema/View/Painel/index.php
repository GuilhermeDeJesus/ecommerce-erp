<?php
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\ValidateUtil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Painel</title>
<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"
	rel="stylesheet">
<link href="public/admin/css/maps/jquery-jvectormap-2.0.3.css"
	rel="stylesheet" />
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
<link href="public/admin/vendors/select2/dist/css/select2.min.css"
	rel="stylesheet">
<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="row tile_count">
					<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
						<span class="count_top"><i class="fa fa-money"></i> Total Vendido</span>
						<div class="count">R$ <?=$data['pedidos_pago_valor'];?></div>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
						<span class="count_top"><i class="fa fa-sellsy"></i> Total</span>
						<div class="count"><?=$data['pedidos_total'];?></div>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
						<span class="count_top"><i class="fa fa-check-circle"></i>
							Aprovados</span>
						<div class="count"><?=$data['pedidos_pago_total'];?></div>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
						<span class="count_top"><i class="fa fa-user"></i> Clientes </span>
						<div class="count green"><?=$data['qtd_cliente'];?></div>
					</div>
					<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
						<span class="count_top"><i class="fa fa-desktop"></i> Produtos </span>
						<div class="count"><?=$data['produto_disponivel'];?></div>
					</div>
				</div>
				<div class="row">
					<div class="no-mobile col-md-12 col-sm-12 col-xs-12"
						style="margin-bottom: 10px;">
						<button class="btn btn-success pull-left" data-toggle='modal'
							data-target='#add-codigo'>
							 <i class="fa fa-filter" aria-hidden="true"></i>
						</button>
						<!-- DADOS FRETE-->
						<div class="modal fade" id="add-codigo" tabindex="-1"
							role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>"
										id="load-img-modal"
										style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
									<div class="">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="x_panel">
													<div class="x_title">
														<h2>
															Filtro <small></small>
														</h2>
														<div class="clearfix"></div>
													</div>
													<div class="x_content">
														<div id='modal-global'
															class='modal fade bd-example-modal-sm' tabindex='-1'
															role='dialog' aria-labelledby='mySmallModalLabel'
															aria-hidden='true'>
															<div class='modal-dialog modal-sm'>
																<div class='modal-conten'>
																	<div
																		style='width: 400px; height: 60px; border-radius: 5px; text-align: center; background-color: #696969;'>
																		<h5 style='color: #FFF; margin-top: 100px;'>
																			<br> <span id="text-pedido-atualizar"
																				style="margin-left: 10px; font-weight: bold;"></span>
																		</h5>
																	</div>
																</div>
															</div>
														</div>
														<form action="?m=sistema&c=painel&a=filter_dash" method="get"
															class="form-horizontal form-label-left">
															<input type="hidden" name="m" value="sistema"> <input
                                								type="hidden" name="c" value="painel"> <input type="hidden"
                                								name="a" value="filter_dash">
															<div class="form-group">
																<label for="middle-name"
																	class="control-label col-md-3 col-sm-3 col-xs-12">Status </label>
																<div class="col-md-8 col-sm-8 col-xs-12">
																	<div id="reportrange" class="pull-left"
                                        								style="border: 0px !important; margin-top: 5px !important; width: 100%;">
                                        								<select class="select2_multiple_produtos form-control"
                                        									name="situacao[]" multiple="multiple"
                                        									style="border: 1px !important; margin-top: -5px !important; width: 100%;">
                                        									<option value="">-- Todos --</option>
                                        									<?php foreach ($data['_situacoes'] as $pd) { ?>
                                        										<option value="<?=$pd['id'];?>"><?=$pd['situacao'];?></option>
                                        									<?php } ?>
                                        								</select>
                                        							</div>
																</div>
															</div>                                								
															<div class="form-group">
																<label for="middle-name"
																	class="control-label col-md-3 col-sm-3 col-xs-12">Produto </label>
																<div class="col-md-8 col-sm-8 col-xs-12">
																	<div id="reportrange" class="pull-left"
                                        								style="border: 0px !important; margin-top: 5px !important; width: 100%;">
                                        								<select class="select2_multiple_produtos form-control"
                                        									name="produto[]" multiple="multiple"
                                        									style="border: 1px !important; margin-top: -5px !important; width: 100%;">
                                        									<option value="">-- Todos --</option>
                                        									<?php foreach ($data['_produtos'] as $pd) { ?>
                                        										<option value="<?=$pd['id'];?>"><?=$pd['descricao'];?></option>
                                        									<?php } ?>
                                        							</select>
                                        							</div>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3 col-sm-3 col-xs-12"
																	for="last-name">Categoria <span
																	class="required"></span>
																</label>
																<div class="col-md-8 col-sm-8 col-xs-12">
																	<select class="select2_multiple_categorias form-control"
                                        									name="categoria[]" multiple="multiple"
                                        									style="border: 0px !important; margin-top: 5px !important;  width: 100%;">
                                        									<option value="">-- Todos --</option>
                                        									<?php foreach ($data['_categorias'] as $pd) { ?>
                                        										<option value="<?=$pd['id'];?>"><?=$pd['descricao'];?></option>
                                        									<?php } ?>
                                        							</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3 col-sm-3 col-xs-12"
																	for="last-name">Dispositivo <span
																	class="required"></span>
																</label>
																<div class="col-md-8 col-sm-8 col-xs-12">
																	<select class="select2_multiple_categorias form-control"
                                        									name="dispositivo[]" multiple="multiple"
                                        									style="border: 0px !important; margin-top: 5px !important;  width: 100%;">
                                        									<option value="">-- Todos --</option>
                                        									<?php foreach (["Desktop","iPhone","Android"] as $d) { ?>
                                        										<option value="<?=$d;?>"><?=$d;?></option>
                                        									<?php } ?>
                                        							</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3 col-sm-3 col-xs-12"
																	for="last-name">Plataforma <span
																	class="required"></span>
																</label>
																<div class="col-md-8 col-sm-8 col-xs-12">
																	<select class="select2_multiple_categorias form-control"
                                        									name="plataforma[]" multiple="multiple"
                                        									style="border: 0px !important; margin-top: 5px !important;  width: 100%;">
                                        									<option value="">-- Todos --</option>
                                        									<?php foreach (["Instagram","Facebook","Google Chrome"] as $p) { ?>
                                        										<option value="<?=$p;?>"><?=$p;?></option>
                                        									<?php } ?>
                                        							</select>
																</div>
															</div>
															<div class="form-group">
																<label for="middle-name"
																	class="control-label col-md-3 col-sm-3 col-xs-12">De </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<label> <input type="date" name="de" style="border: 1px solid #ccc; margin-top: 5px; padding: 5px;">
																	</label>
																</div>
															</div>
															<div class="form-group">
																<label for="middle-name"
																	class="control-label col-md-3 col-sm-3 col-xs-12">Ate </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<label> <input type="date" name="ate" style="border: 1px solid #ccc; margin-top: 5px; padding: 5px;">
																	</label>
																</div>
															</div>
															<div class="form-group">
																<label for="middle-name"
																	class="control-label col-md-3 col-sm-3 col-xs-12">Previsão de Faturamento </label>
																<div class="col-md-6 col-sm-6 col-xs-12">
																	<div id="reportrange" class="pull-left"
                                        								style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; float: right; margin-left: 2px; margin-top: 0px; display: block;">
                                        								<input type="text"
                                        									name="meta_faturamento" style="border: 0px;">
                                        							</div>
																</div>
															</div>
															<div class="ln_solid"></div>
															<div class="form-group">
																<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
																	<button style="float: right;" type="button"
																		class="btn btn-secondary" data-dismiss="modal">Fechar</button>
																	<button style="float: right;" type="submit"
																		class="btn btn-success" id="btn-salvar-pedido">Filtrar</button>
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
						</div>
					</div>
					<div class="col-md-8 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Faturamento</h2>
								<div class="col-md-6"></div>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div id="faturamento" style="height: 300px;"></div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Detalhamento por região</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div id="vendas_por_estados" style="height: 300px;"></div>
							</div>
						</div>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12" style="">
						<div class="x_panel tile fixed_height_320">
							<div class="x_title">
								<h2>Faixa etária</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>18/24</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['18_24']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['18_24']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['18_24']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>25/34</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['25_34']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['25_34']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['25_34']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>35/44</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['35_44']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['35_44']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['35_44']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>45/54</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['45_54']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['45_54']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['45_54']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>55/64</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['55_64']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['55_64']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['55_64']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="widget_summary">
									<div class="w_left w_25">
										<span><b>65/74</b></span>
									</div>
									<div class="w_center w_55">
										<div class="progress">
											<div class="progress-bar bg-green" role="progressbar"
												aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
												style="width: <?=(sizeof($data['_faixa_etaria']['65_74']) / $data['_total_pedidos_ultimos_90_dias']) * 100 + 10;?>%;">
												<span><?=round((sizeof($data['_faixa_etaria']['65_74']) / $data['_total_pedidos_ultimos_90_dias']) * 100);?>%</span>
											</div>
										</div>
									</div>
									<div class="w_right w_20">
										<span style="font-size: 11px; font-weight: bold;">R$ <?=ValidateUtil::setFormatMoney(array_sum($data['_faixa_etaria']['55_64']));?></span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12" style="">
						<div class="x_panel tile fixed_height_320 overflow_hidden">
							<div class="x_title">
								<h2><?=$data['_titulo_resumo'];?></h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<table class="" style="width: 100%">
									<tr>
										<td>
											<table class="tile_info">
												<tr>
													<td>
														<p>
															<i class="fa fa-square green"></i>Aprovados
														</p>
													</td>
													<td><?=$data['_total_hoje']['aprovado'][0]['total']?></td>
												</tr>
												<tr>
													<td>
														<p>
															<i class="fa fa-square orange"></i>Análise
														</p>
													</td>
													<td><?=$data['_total_hoje']['analise'][0]['total']?></td>
												</tr>
												<tr>
													<td>
														<p>
															<i class="fa fa-square yellow"></i>Pendentes
														</p>
													</td>
													<td><?=$data['_total_hoje']['boletoGerado'][0]['total']?></td>
												</tr>
												<tr>
													<td>
														<p>
															<i class="fa fa-square blue"></i>Chargebacks
														</p>
													</td>
													<td><?=$data['_total_hoje']['chargeback'][0]['total']?></td>
												</tr>
												<tr>
													<td>
														<p>
															<i class="fa fa-square red"></i>Recusados
														</p>
													</td>
													<td><?=$data['_total_hoje']['recusado'][0]['total']?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Detalhamento por sexo</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div id="vendas_por_sexo" style="height: 237px;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Detalhamento por dispositivo</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div id="vendas_por_dispositivo" style="height: 237px;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Detalhamento por mídia social</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div id="vendas_por_midia" style="height: 237px;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2><?=$data['_resumo_geral']; ?></h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<ul class="list-group">
									<li class="list-group-item active" style="font-weight: bold;">
										<span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Faturamento'][0]['_vendas_total_aprovadas']);?></span>
										<i class="fa fa-money" aria-hidden="true"></i> Faturamento
									</li>

									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge"><?=$data['_vendas']['Pedidos Quantidade'][0]['_pedidos'];?></span>
										<i class="fa fa-check-square" aria-hidden="true"></i> Pedidos</li>

									<li class="list-group-item list-group-item-success"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Cartão Aprovado Valor'][0]['_cartoes_aprovados_valor']);?> </span>
										<a class="badge"
										href="?m=sistema&c=venda&a=&numero_pedido=&tipo=cartao&data_inicio=<?=$_GET['de'];?>&data_fim=<?=$_GET['ate'];?>&situacao=2&status_fornecedor=&produto=<?=$_GET['produto'];?>"><?=$data['_vendas']['Cartão Aprovado Quantidade'][0]['_cartoes_aprovados_quantidade'];?></a>
										<i class="fa fa-credit-card-alt" aria-hidden="true"></i>
										Cartão Aprovado</li>

									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge"><?=$data['_PERCENTUAL_CARTAO'];?>% </span>
										<i class="fa fa-percent" aria-hidden="true"></i> Cartão</li>
										
									<li class="list-group-item list-group-item-danger"
										style="font-weight: bold;"><span class="badge"><?=$data['_PERCENTUAL_CARTAO_RECUSADO'];?>% </span>
										<a class="badge"><?=$data['_vendas']['Cartão Recusado Quantidade'][0]['_cartoes_recusados_quantidade'];?></a>
										<i class="fa fa-percent" aria-hidden="true"></i> Cartão Recusado</li>										

									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Boleto Pendente Valor'][0]['_valor_boletos_pendentes']);?> </span>
										<a class="badge"
										href="?m=sistema&c=venda&a=&numero_pedido=&tipo=&data_inicio=<?=$_GET['de'];?>&data_fim=<?=$_GET['ate'];?>&situacao=1&status_fornecedor=&produto=<?=$_GET['produto'];?>"><?=$data['_vendas']['Boleto Pendente Quantidade'][0]['_quantidade_boletos_pendentes'];?></a>
										<i class="fa fa-barcode" aria-hidden="true"></i> Boleto
										Pendente</li>

									<li class="list-group-item list-group-item-success"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Boleto Pago Valor'][0]['_valor_boletos_pagos']);?></span>
										<a class="badge"
										href="?m=sistema&c=venda&a=&numero_pedido=&tipo=boleto&data_inicio=<?=$_GET['de'];?>&data_fim=<?=$_GET['ate'];?>&situacao=2&status_fornecedor=&produto=<?=$_GET['produto'];?>"><?=$data['_vendas']['Boleto Pago Quantidade'][0]['_quantidade_boletos_pagos'];?></a>
										<i class="fa fa-barcode" aria-hidden="true"></i> Boleto Pago</li>
									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge"><?=$data['_PERCENTUAL_CONVERSAO_BOLETO'];?>% </span>
										<i class="fa fa-percent" aria-hidden="true"></i> Conversão de
										Boleto</li>

									<li class="list-group-item list-group-item-danger"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Prejuízos (Chargeback/Reembolso) Valor'][0]['_prejus_valor']);?></span>
										<span class="badge"><?=$data['_vendas']['Prejuízos (Chargeback/Reembolso) Quantidade'][0]['_prejus_quantidade'];?></span>
										<i class="fa fa-frown-o" aria-hidden="true"></i> Chargeback/Reembolso</li>

									<li class="list-group-item list-group-item-danger"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Prejuízos Prod Env (Chargeback/Reembolso) Valor'][0]['_prejus_valor']);?></span>
										<span class="badge"><?=$data['_vendas']['Prejuízos Prod Env (Chargeback/Reembolso) Quantidade'][0]['_prejus_quantidade'];?></span>
										<i class="fa fa-frown-o" aria-hidden="true"></i> Chargeback
										(Entregues)</li>

									<li class="list-group-item list-group-item-success"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Lucro Líquido'][0]['_lucro'] - $data['_vendas']['Facebook Ads'][0]['_valor']);?> </span>
										<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Lucro
										Líquido</li>

									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Lucro Desejado'][0]['_lucro_desejado'] - $data['_vendas']['Facebook Ads'][0]['_valor']);?> </span>
										<i class="fa fa-frown-o" aria-hidden="true"></i> Lucro
										Desejado</li>

									<li class="list-group-item">
										<p class="list-group-item-text">O Lucro desejado basea-se no
											lucro líquido somado com o lucro dos boletos não pagos.</p>
									</li>

									<li class="list-group-item"
										style="background: #4267b2; color: #FFF; font-weight: bold;"
										style="font-weight: bold;"><span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_vendas']['Facebook Ads'][0]['_valor']);?> </span>
										<i class="fa fa-facebook-official" aria-hidden="true"></i>
										Facebook Ads</li>
									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge"><?=$data['_ROI'];?> </span>
										<i class="fa fa-rocket" aria-hidden="true"></i> ROI</li>
									<li class="list-group-item list-group-item-info"
										style="font-weight: bold;"><span class="badge"><?=$data['_ROAS'];?> </span>
										<i class="fa fa-rocket" aria-hidden="true"></i> ROAS</li>	
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php

                        function metaFaturamentoLiquido($valor_meta, $faturamento, $lucro_liquido, $qtd_cartao_aprovado, $qtd_boleto_aprovado, $investimento)
                        {
                            $meta_faturamento = $valor_meta;
                    
                            $qtd_total_cartao_aprovado = $qtd_cartao_aprovado;
                            $qtd_total_boleto_pago = $qtd_boleto_aprovado;
                            $qtd_total_vendas_pagas = $qtd_total_cartao_aprovado + $qtd_total_boleto_pago;
                            if($qtd_total_vendas_pagas == 0){
                                $qtd_total_vendas_pagas = 1;
                            }
                    
                            $ticket_medio_do_produto = 1;
                            $ticket_medio_do_produto = $faturamento / $qtd_total_vendas_pagas;
                            
                            if($ticket_medio_do_produto == 0){
                                $ticket_medio_do_produto = 1;
                            }
                    
                            $quantidade_vendas_mensais = $meta_faturamento / $ticket_medio_do_produto;
                            $quantidade_vendas_semanais = ($meta_faturamento / $ticket_medio_do_produto) / 7;
                            $quantidade_vendas_diarias = ($meta_faturamento / $ticket_medio_do_produto) / 30;
                    
                            // CUSTO MÉDIO POR RESULTADO APROVADO
                            $CPCMA = $investimento / $qtd_total_vendas_pagas;
                    
                            $quantidade_investimento_mensais = $CPCMA * $quantidade_vendas_mensais;
                            $quantidade_investimento_semanais = $CPCMA * $quantidade_vendas_semanais;
                            $quantidade_investimento_diarias = $CPCMA * $quantidade_vendas_diarias;
                    
                            // FATURAMENTO REAL MÉDIO
                            $faturamento_real_medio_mensal = $ticket_medio_do_produto * $quantidade_vendas_mensais;
                            $faturamento_real_medio_semanal = $ticket_medio_do_produto * $quantidade_vendas_semanais;
                            $faturamento_real_medio_diarias = $ticket_medio_do_produto * $quantidade_vendas_diarias;
                    
                            $lucro_medio_liquido_por_produto = $lucro_liquido / $qtd_total_vendas_pagas;
                            $lucro_medio_mensal = $lucro_medio_liquido_por_produto * $quantidade_vendas_mensais;
                            $lucro_medio_semanal = $lucro_medio_liquido_por_produto * $quantidade_vendas_semanais;
                            $lucro_medio_diario = $lucro_medio_liquido_por_produto * $quantidade_vendas_diarias;
                    
                            return [
                                'ticket_medio_do_produto' => $ticket_medio_do_produto,
                                'quantidade_vendas_mensais' => $quantidade_vendas_mensais,
                                'quantidade_vendas_semanais' => $quantidade_vendas_semanais,
                                'quantidade_vendas_diarias' => $quantidade_vendas_diarias,
                                'quantidade_investimento_mensais' => $quantidade_investimento_mensais,
                                'quantidade_investimento_semanais' => $quantidade_investimento_semanais,
                                'quantidade_investimento_diarias' => $quantidade_investimento_diarias,
                                'faturamento_real_medio_mensal' => $faturamento_real_medio_mensal,
                                'faturamento_real_medio_semanal' => $faturamento_real_medio_semanal,
                                'faturamento_real_medio_diarias' => $faturamento_real_medio_diarias,
                                'lucro_medio_mensal' => $lucro_medio_mensal - $quantidade_investimento_mensais,
                                'lucro_medio_semanal' => $lucro_medio_semanal - $quantidade_investimento_semanais,
                                'lucro_medio_diario' => $lucro_medio_diario - $quantidade_investimento_diarias
                            ];
                        }
                    
                        $meta = $data['_valor_meta_faturamento'];
                        $faturamento = $data['_vendas']['Faturamento'][0]['_vendas_total_aprovadas'];
                        $lucro_liquido = $data['_vendas']['Lucro Líquido'][0]['_lucro'];
                        $qtd_total_cartao_aprovado = $data['_vendas']['Cartão Aprovado Quantidade'][0]['_cartoes_aprovados_quantidade'];
                        $qtd_total_boleto_pago = $data['_vendas']['Boleto Pago Quantidade'][0]['_quantidade_boletos_pagos'];
                        $facebook_ads = $data['_vendas']['Facebook Ads'][0]['_valor'];
                    
                        $previsao = metaFaturamentoLiquido($meta, $faturamento, $lucro_liquido, $qtd_total_cartao_aprovado, $qtd_total_boleto_pago, $facebook_ads);
                    
                        ?>
						<div class="x_panel">
							<div class="x_title">
								<h2>Previsão de faturamento real</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<ul class="list-group">
									<li class="list-group-item active" style="font-weight: bold;">
										<span class="badge">R$ <?=ValidateUtil::setFormatMoney($data['_valor_meta_faturamento']);?></span>
										<i class="fa fa-money" aria-hidden="true"></i> Faturamento
									</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['ticket_medio_do_produto']);?></span>
										Ticket Médio</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['quantidade_investimento_mensais']);?></span>
										Investimento Mensal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['quantidade_investimento_semanais']);?></span>
										Investimento Semanal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['quantidade_investimento_diarias']);?></span>
										Investimento Diário</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge"><?=round($previsao['quantidade_vendas_mensais']);?></span>
										Quantidade de Vendas Mensal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge"><?=round($previsao['quantidade_vendas_semanais']);?></span>
										Quantidade de Vendas Semanal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge"><?=round($previsao['quantidade_vendas_diarias']);?></span>
										Quantidade de Vendas Diária</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['faturamento_real_medio_mensal']);?></span>
										Faturamento Mensal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['faturamento_real_medio_semanal']);?></span>
										Faturamento Semanal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['faturamento_real_medio_diarias']);?></span>
										Faturamento Diário</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['lucro_medio_mensal']);?></span>
										Lucro Líquido Mensal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['lucro_medio_semanal']);?></span>
										Lucro Líquido Semanal</li>
									<li class="list-group-item" style="font-weight: bold;"><span
										class="badge">R$ <?=ValidateUtil::setFormatMoney($previsao['lucro_medio_diario']);?></span>
										Lucro Líquido Diário</li>
								</ul>
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
	<script src="public/admin/vendors/echarts/dist/echarts.min.js"></script>
	<script src="public/admin/vendors/echarts/map/js/world.js"></script>

	<!-- NProgress -->
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<!-- Chart.js -->
	<script src="public/admin/vendors/Chart.js/dist/Chart.min.js"></script>
	<!-- jQuery Sparklines -->
	<script
		src="public/admin/vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
	<!-- morris.js -->
	<script src="public/admin/vendors/raphael/raphael.min.js"></script>
	<script src="public/admin/vendors/morris.js/morris.min.js"></script>
	<!-- gauge.js -->
	<script src="public/admin/vendors/gauge.js/dist/gauge.min.js"></script>
	<script
		src="public/admin/vendors/jquery.inputmask/dist/inputmask/jquery.maskMoney.min.js"></script>
	<!-- bootstrap-progressbar -->
	<script
		src="public/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
	<!-- Skycons -->
	<script src="public/admin/vendors/skycons/skycons.js"></script>
	<!-- Flot -->
	<script src="public/admin/vendors/Flot/jquery.flot.js"></script>
	<script src="public/admin/vendors/Flot/jquery.flot.pie.js"></script>
	<script src="public/admin/vendors/Flot/jquery.flot.time.js"></script>
	<script src="public/admin/vendors/Flot/jquery.flot.stack.js"></script>
	<script src="public/admin/vendors/Flot/jquery.flot.resize.js"></script>
	<!-- Flot plugins -->
	<script src="public/admin/js/flot/jquery.flot.orderBars.js"></script>
	<script src="public/admin/js/flot/date.js"></script>
	<script src="public/admin/js/flot/jquery.flot.spline.js"></script>
	<script src="public/admin/js/flot/curvedLines.js"></script>
	<!-- bootstrap-daterangepicker -->
	<script src="public/admin/js/moment/moment.min.js"></script>
	<script src="public/admin/js/datepicker/daterangepicker.js"></script>

	<script src="public/admin/vendors/select2/dist/js/select2.full.min.js"></script>

	<script src="public/admin/build/js/custom.min.js"></script>
	<script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Selecione o produto",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple_produtos").select2({
          maximumSelectionLength: 100,
          placeholder: "",
          allowClear: true
        });
        $(".select2_multiple_categorias").select2({
            maximumSelectionLength: 100,
            placeholder: "",
            allowClear: true
          });
          
        $("input[name='meta_faturamento']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
          
      });
    </script>
	<script>
		var theme = {
			color: ['#C13584', '#3b5998', '#1a73e8', '#1ABB9C', '#00BFFF', '#4682B4', 'fuchsia', 'gray', '#1ABB9C', 
				'lime', 'maroon', 'navy', 'olive', 'orange', 'purple', 'red', 
				'silver', 'teal', 'yellow'],
            title: {
                itemGap: 8,
                textStyle: {
                    fontWeight: 'normal',
                    color: '#408829'
                }
            },

            dataRange: {
                color: ['#1f610a', '#97b58d']
            },

            toolbox: {
                color: ['#408829', '#408829', '#408829', '#408829']
            },

            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.5)',
                axisPointer: {
                    type: 'line',
                    lineStyle: {
                        color: '#408829',
                        type: 'dashed'
                    },
                    crossStyle: {
                        color: '#408829'
                    },
                    shadowStyle: {
                        color: 'rgba(200,200,200,0.3)'
                    }
                }
            },

            dataZoom: {
                dataBackgroundColor: '#eee',
                fillerColor: 'rgba(64,136,41,0.2)',
                handleColor: '#408829'
            },
            grid: {
                borderWidth: 0
            },

            categoryAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },

            valueAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitArea: {
                    show: true,
                    areaStyle: {
                        color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },
            timeline: {
                lineStyle: {
                    color: '#408829'
                },
                controlStyle: {
                    normal: {color: '#408829'},
                    emphasis: {color: '#408829'}
                }
            },

            k: {
                itemStyle: {
                    normal: {
                        color: '#68a54a',
                        color0: '#a9cba2',
                        lineStyle: {
                            width: 1,
                            color: '#408829',
                            color0: '#86b379'
                        }
                    }
                }
            },
            map: {
                itemStyle: {
                    normal: {
                        areaStyle: {
                            color: '#ddd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    },
                    emphasis: {
                        areaStyle: {
                            color: '#99d2dd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    }
                }
            },
            force: {
                itemStyle: {
                    normal: {
                        linkStyle: {
                            strokeColor: '#408829'
                        }
                    }
                }
            },
            chord: {
                padding: 4,
                itemStyle: {
                    normal: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    },
                    emphasis: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    }
                }
            },
            gauge: {
                startAngle: 225,
                endAngle: -45,
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                        width: 8
                    }
                },
                axisTick: {
                    splitNumber: 10,
                    length: 12,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: 'auto'
                    }
                },
                splitLine: {
                    length: 18,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                pointer: {
                    length: '90%',
                    color: 'auto'
                },
                title: {
                    textStyle: {
                        color: '#333'
                    }
                },
                detail: {
                    textStyle: {
                        color: 'auto'
                    }
                }
            },
            textStyle: {
                fontFamily: 'Arial, Verdana, sans-serif'
            }
        };
        
    	function numberToReal(numero) {
    	    var numero = numero.toFixed(2).split('.');
    	    numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
    	    return numero.join(',');
    	}

    	// GRÁFICO DE VENDAS POR DIA NO MES ATUAL
    	var echartBar = echarts.init(document.getElementById('faturamento'), theme);
    	echartBar.setOption({
            title: {
              text: '',
              // subtext: '<?=DateUtil::monthLiteral(date('m'));?>'
            },
            tooltip: {
              trigger: 'axis'
            },
            legend: {
              data: ['sales', 'purchases']
            },
            toolbox: {
              show: true
            },
            calculable: true,
            xAxis: [{
              scale: true,
              type: 'category',
              data: <?=$data['_dias_mes']; ?>
            }],
            yAxis: [{
                scale: false,
                type: 'value',
                axisLabel: {
                	formatter: 'R$ {value}'
                }  
            }],
            series: [{
              name: 'Aprovados',
              type: 'bar',
              tooltip: {
              trigger: 'item',
              formatter: function(params) {
                  	return params.name +'<br> ' + numberToReal(params.value);
              }
              },
              data: <?=$data['_faturamento']; ?>,
            }]
          });

        // GRÁFICO DE VENDAS POR ESTADO MES ATUAL
        var echartDonut = echarts.init(document.getElementById('vendas_por_estados'), theme);
            
            echartDonut.setOption({
              title: {
                text: '',
                subtext: '<?=date('Y');?>'
              },
              tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
              },
              calculable: true,
              legend: {
                x: 'center',
                y: 'bottom'
              },
              toolbox: {
                show: true,
                feature: {
                  magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                      funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'center',
                        max: 1548
                      }
                    }
                  },
                  restore: {
                    show: true,
                    title: "Atualizar"
                  },
                  saveAsImage: {
                    show: true,
                    title: "Salvar"
                  }
                }
              },
              series: [{
                name: 'Total',
                type: 'pie',
                radius: ['25%', '50%'],
                itemStyle: {
                  normal: {
                    label: {
                      show: true
                    },
                    labelLine: {
                      show: true
                    }
                  },
                  emphasis: {
                    label: {
                      show: false,
                      position: 'center',
                      textStyle: {
                        fontSize: '10',
                        fontWeight: 'normal'
                      }
                    }
                  }
                },
                data: <?=json_encode($data['_vendas_por_estado']); ?>
              }]
            });

            // GRÁFICO DE VENDAS POR SEXO
            var echartSexo = echarts.init(document.getElementById('vendas_por_sexo'), theme);
                
            	echartSexo.setOption({
                  title: {
                    text: '',
                    subtext: '<?=date('Y');?>'
                  },
                  tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                  },
                  calculable: true,
                  legend: {
                    x: 'center',
                    y: 'bottom'
                  },
                  toolbox: {
                    show: true,
                    feature: {
                      magicType: {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                          funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'center',
                            max: 1548
                          }
                        }
                      },
                      restore: {
                        show: true,
                        title: "Atualizar"
                      },
                      saveAsImage: {
                        show: true,
                        title: "Salvar"
                      }
                    }
                  },
                  series: [{
                    name: 'Total',
                    type: 'pie',
                    radius: ['25%', '50%'],
                    itemStyle: {
                      normal: {
                        label: {
                          show: true
                        },
                        labelLine: {
                          show: true
                        }
                      },
                      emphasis: {
                        label: {
                          show: false,
                          position: 'center',
                          textStyle: {
                            fontSize: '10',
                            fontWeight: 'normal'
                          }
                        }
                      }
                    },
                    data: <?=json_encode($data['_vendas_por_sexo']); ?>
                  }]
                });   

            	// GRÁFICO DE VENDAS POR DISPOSITIVO
                var echartDis = echarts.init(document.getElementById('vendas_por_dispositivo'), theme);
                	echartDis.setOption({
                      title: {
                        text: '',
                        subtext: '<?=date('Y');?>'
                      },
                      tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                      },
                      calculable: true,
                      legend: {
                        x: 'center',
                        y: 'bottom'
                      },
                      toolbox: {
                        show: true,
                        feature: {
                          magicType: {
                            show: true,
                            type: ['pie', 'funnel'],
                            option: {
                              funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'center',
                                max: 1548
                              }
                            }
                          },
                          restore: {
                            show: true,
                            title: "Atualizar"
                          },
                          saveAsImage: {
                            show: true,
                            title: "Salvar"
                          }
                        }
                      },
                      series: [{
                        name: 'Total',
                        type: 'pie',
                        radius: ['25%', '50%'],
                        itemStyle: {
                          normal: {
                            label: {
                              show: true
                            },
                            labelLine: {
                              show: true
                            }
                          },
                          emphasis: {
                            label: {
                              show: false,
                              position: 'center',
                              textStyle: {
                                fontSize: '10',
                                fontWeight: 'normal'
                              }
                            }
                          }
                        },
                        data: <?=json_encode($data['_vendas_por_dispositivo']); ?>
                      }]
                    });      
                    
				// GRÁFICO DE VENDAS POR MIDIA
                var echartDis = echarts.init(document.getElementById('vendas_por_midia'), theme);
                	echartDis.setOption({
                      title: {
                        text: '',
                        subtext: '<?=date('Y');?>'
                      },
                      tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                      },
                      calculable: true,
                      legend: {
                        x: 'center',
                        y: 'bottom'
                      },
                      toolbox: {
                        show: true,
                        feature: {
                          magicType: {
                            show: true,
                            type: ['pie', 'funnel'],
                            option: {
                              funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'center',
                                max: 1548
                              }
                            }
                          },
                          restore: {
                            show: true,
                            title: "Atualizar"
                          },
                          saveAsImage: {
                            show: true,
                            title: "Salvar"
                          }
                        }
                      },
                      series: [{
                        name: 'Total',
                        type: 'pie',
                        radius: ['25%', '50%'],
                        itemStyle: {
                          normal: {
                            label: {
                              show: true
                            },
                            labelLine: {
                              show: true
                            }
                          },
                          emphasis: {
                            label: {
                              show: false,
                              position: 'center',
                              textStyle: {
                                fontSize: '10',
                                fontWeight: 'normal'
                              }
                            }
                          }
                        },
                        data: <?=json_encode($data['_vendas_por_midia']); ?>
                      }]
                    });                                     
    </script>
</body>
</html>
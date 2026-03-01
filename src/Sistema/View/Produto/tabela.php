<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Tabela</title>

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
	<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img"
		style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Tabela de Preços</h2>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<div id="nn-wizard_verticle"
										class="nn-form_wizard wizard_verticle">
										<ul class="list-unstyled wizard_steps" style="display: none;">
											<?php
                                            $i = 0;
                                            foreach ($data['produtos'] as $p) {
                                                $i ++;
                                                ?>
											<li><a href="#step-<?=$i.$i;?>"> <span class="step_no"><?=$i;?></span></a></li>
											<?php } ?>
										</ul>
    										<table class="table" id="table-produtos">
    											<thead>
    												<tr>
    													<th scope="col">Produto</th>
    												</tr>
    											</thead>
    										<?php
                                            $t = 0;
                                            foreach ($data['produtos'] as $p) {
                                                $tamanhosProduto = dao('Core', 'TamanhoProduto')->select([
                                                    '*'
                                                ], [
                                                    [
                                                        'id_produto',
                                                        '=',
                                                        $p['id']
                                                    ]
                                                ]);
                                                $t ++;
                                                ?>
    										<tr>
												<td>
													<div id="step-<?=$i.$i;?>">
														<form class="form-horizontal form-label-left"
															id="form-produto-<?=$p['id'];?>">
															<br> <span class="section"><?=$p['descricao'];?></span> <input
																type="hidden" name="id_produto" value="<?=$p['id'];?>">
															<div class="inbox-body">
																<div class="products">
																	<ul>
																		<li><a target="new"
																			href="data/products/<?=$p['id'];?>/principal.jpg"
																			class="atch-thumb"> <img
																				src="data/products/<?=$p['id'];?>/principal.jpg"
																				alt="img" /></a></li>
																	</ul>
																</div>
															</div>
															<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200"><label
																			class="control-label"
																			for="first-name">Lucro R$</label></td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text"
																					required="required" value="<?=$p['lucro_reais'];?>"
																					name="lucro_reais_<?=$p['id'];?>" id="lucro_reais_<?=$p['id'];?>"
																					class="form-control col-md-7 col-xs-12">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
															<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200"><label
																			class="control-label"
																			for="first-name">Lucro %</label></td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text"
																					required="required" value="<?=$p['lucro'];?>"
																					name="lucro_percentual_<?=$p['id'];?>" id="lucro_percentual_<?=$p['id'];?>"
																					class="form-control col-md-7 col-xs-12">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
															<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200"><label
																			class="control-label col-md-3 col-sm-3"
																			for="last-name"> Custo </label></td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text" id="custo_produto_<?=$p['id'];?>"
																					name="custo_produto_<?=$p['id'];?>" required="required"
																					value="<?=$p['valor_compra'];?>"
																					class="form-control col-md-7 col-xs-12">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
															<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200"><label
																			class="control-label col-md-3 col-sm-3"
																			for="last-name"> Venda </label></td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text"
																					name="venda_produto_<?=$p['id'];?>" required="required"
																					value="<?=$p['valor_venda'];?>" id="venda_produto_<?=$p['id'];?>"
																					class="form-control col-md-7 col-xs-12">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
            												<?php if(sizeof($tamanhosProduto) > 1){ ?>
            												<?php foreach ($tamanhosProduto as $tp) { ?>
            												<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200"><label class="control-label"
																			for="last-name"> <?=$tp['descricao'];?> Custo
																		</label>
																		</td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text"
																					name="custo_tamanho_<?=$tp['id'];?>"
																					required="required" value="<?=$tp['custo'];?>" id="custo_tamanho_<?=$tp['id'];?>"
																					class="form-control col-md-7 col-xs-12 custo_tamanho">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
															<div class="form-group" style="padding: 10px;">
																<table>
																	<tr>
																		<td width="200">
																			<label class="control-label" for="last-name"> <?=$tp['descricao'];?> Venda</label>
																		</td>
																		<td>
																			<div class="col-md-6 col-sm-6">
																				<input type="text"
																					name="venda_tamanho_<?=$tp['id'];?>" id="venda_tamanho_<?=$tp['id'];?>"
																					required="required" value="<?=$tp['valor'];?>"
																					class="form-control col-md-7 col-xs-12 venda_tamanho">
																			</div>
																		</td>
																	</tr>
																</table>
															</div>
															<br>
            												<?php } ?>
            												<?php } ?>
            												<div class="form-group" style="float: right;">
																<div class="col-md-6 col-sm-6">
																	<button type="button" class="btn btn-success"
																		id="atualizar_produto_<?=$p['id'];?>">Atualizar</button>
																</div>
															</div>
														</form>
													</div>
													<div id='success-edit'
														class='modal fade bd-example-modal-sm' tabindex='-1'
														role='dialog' aria-labelledby='mySmallModalLabel'
														aria-hidden='true'>
														<div class='modal-dialog modal-sm'>
															<div class='modal-conten'>
																<div
																	style='width: 400px; height: 60px; border-radius: 5px; text-align: center; background-color: #696969;'>
																	<h5 style='color: #FFF; margin-top: 100px;'>
																		<br>Produto editado com sucesso</h5>
																</div>
															</div>
														</div>
													</div> <script>
                									$(document).ready(function () {
                								  		$("input[name='lucro_reais_<?=$p['id'];?>']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                								  		$("input[name='custo_produto_<?=$p['id'];?>']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                								  		$("input[name='venda_produto_<?=$p['id'];?>']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                								  		
                								  		<?php foreach ($tamanhosProduto as $tp) { ?>
                								  		$("input[name='custo_tamanho_<?=$tp['id'];?>']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                								  		$("input[name='venda_tamanho_<?=$tp['id'];?>']").maskMoney({prefix:' ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                								  		<?php } ?>

                										$('#atualizar_produto_<?=$p['id'];?>').click(function(e) {
                											$('#load-img').css('display', 'inline-block');
                											$('body').css("opacity", "0.5");
                											                                                        	    	
                											setTimeout(function(){ 
                												$.ajax({
                                                    				type : 'POST',
                                                    				dataType : "text",
                                                    				async : false,
                                                    				url : "?m=sistema&c=produto&a=atualizarPrecoProduto",
                                                    				data : {
                                                    					"data" : JSON.stringify($("#form-produto-<?=$p['id'];?>").serializeArray())
                                                    				},					  
                                                    				success: function(data){
                                                        				json = JSON.parse(data);
                    													$('#load-img').css('display', 'none');
                    													$('body').css("opacity", "1");
                
                    													if(json){
                        													$('#success-edit').modal('show');
                        													setInterval(function() {
                        														$('#success-edit').modal('hide');
                        														if (setRefresh == true) {
                        															location.reload();
                        														}
                        													}, 1800);
                    													}
                                                    				},
                                                    			});
                                                	 		}, 100);
                                                        });

                										$('#lucro_reais_<?=$p['id'];?>').keyup(function() {
                    										 
                											var valLucroReais = parseFloat($('#lucro_reais_<?=$p['id'];?>').val());
                											var valCustoReais = parseFloat($('#custo_produto_<?=$p['id'];?>').val());
                											var valVendaReais = parseFloat($('#venda_produto_<?=$p['id'];?>').val());

                											var valVend = valCustoReais + valLucroReais;
                											
                											$('#venda_produto_<?=$p['id'];?>').val(valVend);
                											
                											<?php foreach ($tamanhosProduto as $tp) { ?>
                											var tamnhoCusto = parseFloat($('#custo_tamanho_<?=$tp['id'];?>').val());

                											var valVendaTamanho = tamnhoCusto + valLucroReais;
                											$('#venda_tamanho_<?=$tp['id'];?>').val(valVendaTamanho);
                											
                											<?php } ?>
                										});


                										$('#lucro_percentual_<?=$p['id'];?>').keyup(function() {
                   										 
                											var valLucroPercentual = parseFloat($('#lucro_percentual_<?=$p['id'];?>').val());
                											var valCustoReais = parseFloat($('#custo_produto_<?=$p['id'];?>').val());
                											var valVendaReais = parseFloat($('#venda_produto_<?=$p['id'];?>').val());

                											var valVend = (valCustoReais / 100) * valLucroPercentual;
                											
                											$('#venda_produto_<?=$p['id'];?>').val(valVend + valCustoReais);
                											
                											<?php foreach ($tamanhosProduto as $tp) { ?>
                											var tamnhoCusto = parseFloat($('#custo_tamanho_<?=$tp['id'];?>').val());

                											var valVendaTamanho = (tamnhoCusto / 100) * valLucroPercentual;

                											$('#venda_tamanho_<?=$tp['id'];?>').val(valVendaTamanho + tamnhoCusto);
                											
                											<?php } ?>
                										});
                									 });
                                                    </script>
												</td>
											</tr>
										<?php } ?>
										</table>
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
    <script
    	src="public/admin/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <script
    	src="public/admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script
    	src="public/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script
    	src="public/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script
    	src="public/admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script
    	src="public/admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script
    	src="public/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script>
      $(document).ready(function() {
        $('#table-produtos').dataTable();
      });
    </script>
</body>
</html>
<?php
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\DateUtil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Minhas Notas</title>
<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
	<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img"
		style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Notas Fiscais de Saída</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12"></div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php if(isset($data['setAlert']) && $data['setAlert'] == TRUE){ ?>
							<div class="alert alert-secondary alert-dismissible fade in" style="background: #eee;"
								role="alert">
								<button type="button" class="close" data-dismiss="alert"
									aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<?=$data['msg'];?>
							</div>
							<?php } ?>
							<div class="x_panel">
								<div class="x_content">
									<form method="post" action="?m=sistema&c=nf&a=formulario">
										<div>
											<div class="x_panel">
												<div class="x_content">
													<button class="btn btn-default" name="gerar_notas_selecionadas" type="submit" value="gerar_notas_selecionados" onclick="return hideLoad();">
														<i class="fa fa-play"></i> Gerar NFC-e dos pedidos
														selecionados
													</button>

													<button class="btn btn-default" name="gerar_pdf_selecionados" type="submit" value="gerar_pdf_selecionados" onclick="">
														<i class="fa fa-file-pdf-o"></i> Gerar PDF DANFE dos
														pedidos selecionados
													</button>
												</div>
											</div>
										</div>
										<div id="exTab3" class="container">
											<br>
											<table class="table table-striped bulk_action" id="notas">
												<thead>
													<tr class="headings">
														<th></th>
														<th scope="col">Número</th>
														<th scope="col">Data</th>
														<th scope="col">Cliente</th>
														<th scope="col">Situação</th>
														<th scope="col">Valor (R$)</th>
														<th scope="col">Ações</th>
													</tr>
												</thead>
												<tbody>
    											<?php foreach ($data['pedidos'] as $p) {?>
    												<?php
                                                    $idNota = $p['id_nota_fiscal'];
                                                    $nota = dao('Core', 'NotaFiscal')->select(['*'], ['id', '=', $idNota]);
                                                    $status = $nota[0]['status'];
                                                    $codigo_erro = $nota[0]['codigo_erro'];
                                                    $mensagem_erro = $nota[0]['mensagem_erro'];
                                    
                                                    ?>
    												<tr class="even pointer">
														<td class="a-center"><input type="checkbox" class="flat" name="_pe_<?=$p['id']?>" value="<?=$p['id']?>"></td>
														<td width="200"><a href="?m=sistema&c=venda&a=form&num=<?=$p['numero_pedido'];?>"><?=$p['numero_pedido'];?></a></td>
														<td><?=DateUtil::getDateDMY($p['data']);?></td>
														<td><?=dao('Core', 'Cliente')->getField('nome', $p['id_cliente']);?></td>
														<td><?=ucfirst($status); ?></td>
														<td width="100">R$ <?=ValidateUtil::setFormatMoney($p['valor']);?></td>
														<td>
														    <?php if($status != 'autorizado' && $status != 'cancelado'){ ?>
															<a onclick=""
															href="/?m=sistema&c=nf&a=enviarNota&pedido=<?=$p['id'];?>"
															class="btn btn-primary"><i
																class="fa fa-play"></i> Gerar NFC-e</a>
															<?php } ?>
															
															<?php if($status == 'autorizado'){ ?>
															<a
															href="/?m=sistema&c=nf&a=gerarPDFNota&pedido=<?=$p['id'];?>"
															class="btn btn-info"><i
																class="fa fa-file-pdf-o"></i> Gerar PDF DANFE</a>
															<?php } ?>
															<?php if($status == 'autorizado'){ ?>
															<button type="button" class="btn btn-danger"
																data-toggle="modal" data-target="#cancelarNota_<?=$idNota;?>"
																data-whatever="@mdo">
																<i class="fa fa-close"></i> Cancelar NF-e
															</button>
															<div class="modal fade" id="cancelarNota_<?=$idNota;?>" tabindex="-1"
																role="dialog" aria-labelledby="exampleModalLabel"
																aria-hidden="true">
																<div class="modal-dialog" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h3 class="modal-title" id="exampleModalLabel">Nota
																				Fiscal eletrônica</h3>
																			<button type="button" class="close"
																				data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		<div class="modal-body">
																			<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img-modal-<?=$idNota;?>" style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
																			<div class="" id="msg-cancelamento-<?=$idNota;?>" role="alert" style="display: none; width: 100%; background: #eee; color: #333; border: 0px; padding: 25px; border-radius: 7px;">
                                                                				<span id="text-cancelamento-<?=$idNota;?>" style="margin-left: 10px; font-weight: bold;"></span>
                                                                			</div>
                                                                			<Br>
																			<div>
																				<label for="message-text" class="col-form-label">Digite
																					a justificativa (mínimo 15 caracteres):</label><br>
																				<input type="hidden" id="id_nota_fiscal_<?=$idNota;?>" name="id_nota_fiscal_<?=$idNota;?>" value="<?=$idNota;?>">
																				<textarea class="form-control" id="justificativa_cancelamento_<?=$idNota;?>" name="justificativa_cancelamento_<?=$idNota;?>"
																					cols="40"></textarea>
																			</div>
																			<br> <br>
																			<div class="">
																				<button type="button" id="cancelar_nota_btn_<?=$idNota;?>" class="btn btn-success" name="cancelar_nota" value="cancelar_nota" onclick="">Cancelar
																					Nota</button>
																			</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-secondary"
																				data-dismiss="modal">Fechar</button>

																		</div>
																	</div>
																</div>
															</div>
															<script>
                											$(document).ready(function () {
                												$('#cancelar_nota_btn_<?=$idNota;?>').click(function(e) {
                													$('#load-img-modal-<?=$idNota;?>').css('display', 'inline-block');
                													$('body').css("opacity", "0.5");
                													                                                        	    	
																	var jus = $("#justificativa_cancelamento_<?=$idNota;?>").val();
																	var idNota = $("#id_nota_fiscal_<?=$idNota;?>").val();

																	setTimeout(function(){ 
																		$.ajax({
				                                            				type : 'POST',
				                                            				dataType : "text",
	                                                        				async : false,
	                                                        				url : "?m=sistema&c=nf&a=cancelarNota",
	                                                        				data : {
	                                                         					id_nota_fiscal : idNota,
	                                                         					justificativa_cancelamento : jus
	                                                        				},					  
	                                                        				success: function(data){
	                                                            				json = JSON.parse(data);
	                                                            				if(json.status == "erro_cancelamento" || json.status == "cancelado"){
	                                                                                $('#msg-cancelamento-<?=$idNota;?>').css("display", "table");
	                                                                                document.getElementById('text-cancelamento-<?=$idNota;?>').innerHTML = json.mensagem_sefaz;
	                                                            				}else if(json.codigo == "requisicao_invalida" || json.codigo == "pending_operation"){
	                                                                                $('#msg-cancelamento-<?=$idNota;?>').css("display", "table");
	                                                                                document.getElementById('text-cancelamento-<?=$idNota;?>').innerHTML = json.mensagem;
	                                                            				}else{
	                                                                                $('#msg-cancelamento-<?=$idNota;?>').css("display", "table");
	                                                                                document.getElementById('text-cancelamento-<?=$idNota;?>').innerHTML = json.mensagem_sefaz;
	                                                            				}
	                                                            				
	                        													$('#load-img-modal-<?=$idNota;?>').css('display', 'none');
	                        													$('body').css("opacity", "1");
	                                                        				},
	                                                        			});
				                                        	 		}, 100);
                                                                });
                											 });
                                                            </script>	
															<?php } ?>
															<?php if($codigo_erro != NULL){ ?>
															<button type="button" class="btn btn-danger"
																data-toggle="modal" data-target="#verErro"
																data-whatever="@mdo">
																<i class="fa fa-close"></i> Ver Erro NF-e
															</button>
															<div class="modal fade" id="verErro" tabindex="-1"
																role="dialog" aria-labelledby="exampleModalLabel"
																aria-hidden="true">
																<div class="modal-dialog" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h3 class="modal-title" id="exampleModalLabel">Nota
																				Fiscal eletrônica com erro de validação</h3>
																			<button type="button" class="close"
																				data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		<div class="modal-body">
																			<br>
																			<?php 
																			print_r($mensagem_erro);
																			?>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-secondary"
																				data-dismiss="modal">Fechar</button>

																		</div>
																	</div>
																</div>
															</div>
															<?php } ?>
														</td>
													</tr>
    												<?php }?>
    											</tbody>
											</table>
										</div>
									</form>
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
		</div>
	</div>
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="public/admin/vendors/fastclick/lib/fastclick.js"></script>
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<script src="public/admin/vendors/iCheck/icheck.min.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

	<script>
      $(document).ready(function() {
      	$('#notas').dataTable();

        $(".alert-secondary").fadeTo(2000, 500).slideUp(500, function(){
			$(".alert-secondary").slideUp(500);
		});
		    
      });

      function showLoad(){
			$('#load-img').css('display', 'none');
			$('body').css("opacity", "1");
	  }

      function hideLoad(){
			$('#load-img').css('display', 'inline-block');
			$('body').css("opacity", "0.5");
	  }
    </script>
</body>
</html>
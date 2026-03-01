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

<title>Minhas etiquetas</title>
<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>

<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css"
	rel="stylesheet">
<link
	href="public/admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css"
	rel="stylesheet">

<link href="public/admin/build/css/custom.min.css" rel="stylesheet">

<link rel='stylesheet' href='public/css/paginacao.css' type='text/css' />

<style>
.form-group input[type="checkbox"] {
	display: none;
}

.form-group input[type="checkbox"]+.btn-group>label span {
	width: 20px;
}

.form-group input[type="checkbox"]+.btn-group>label span:first-child {
	display: none;
}

.form-group input[type="checkbox"]+.btn-group>label span:last-child {
	display: inline-block;
}

.form-group input[type="checkbox"]:checked+.btn-group>label span:first-child
	{
	display: inline-block;
}

.form-group input[type="checkbox"]:checked+.btn-group>label span:last-child
	{
	display: none;
}
</style>
</head>

<body class="nav-md">
	<div class="container body">
		<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img"
			style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
		
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Minhas etiquetas</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div id="div-msg" style="display: none; width: 100%;" class="alert alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"
							aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<span id="msg"></span>
					</div>					
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12"></div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php if(isset($data['cad']) && $data['cad'] == true || isset($data['des']) && $data['des'] == true || isset($data['posts']) && $data['posts'] == true){ ?>
							<div class="alert alert-success alert-dismissible fade in"
								role="alert">
								<button type="button" class="close" data-dismiss="alert"
									aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<?=$data['msg'];?>
							</div>
							<?php } ?>
							<div class="x_panel">
								<div class="x_title" style="display: none;">
									<br>
    								<div>
    									<div class="x_panel">
    										<div class="x_content">
    											<a href="?m=sistema&c=correios&a=atualizarPostagens"><button class="btn btn-default" name="gerar_notas_selecionadas" type="submit" value="gerar_selecionados" onclick="">
    												<i class="fa fa-play"></i> Atualizar Postagens
    											</button></a>
    										</div>
    									</div>
    								</div>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<div id="exTab3" class="container">
										<ul class="nav nav-pills">
											<li class="active"><a href="#1b" data-toggle="tab"><b>Pedidos</b></a></li>
											<li><a href="#2b" data-toggle="tab"><b>Gerar Etiquetas</b></a></li>
											<li><a href="#3b" data-toggle="tab"><b>Objetos Encaminhados</b></a></li>
										</ul>
										<div class="tab-content clearfix">
											<div class="tab-pane active" id="1b">
												<div class="row">
													<div class="col-md-12">
														<form action="?m=sistema&c=correios&a=gerarPLP" method="post" >
															<br>
															<table class="table table-striped"
																id="table-pedidos-aprovados">
																<thead>
																	<tr>
																		<th scope="col">Pedido</th>
																		<th scope="col">Categoria</th>
																		<th scope="col">Inclusão</th>
																		<th scope="col">Cliente</th>
																		<th scope="col">Valor</th>
																		<th scope="col"></th>
																	</tr>
																</thead>
            													<?php foreach ($data['pedidos_aprovados'] as $pedido) { ?>
            														<tr>
																		<th scope="row" style="color: green;"><a href="?m=sistema&c=venda&a=form&num=<?=$pedido['numero_pedido'];?>"><?=$pedido['numero_pedido'];?><?=(strlen($pedido['status_clear_sale']) > 1) ? ' - '.getStatusClear($pedido['status_clear_sale']) : ' - '.str_replace('ta', 'tã', ucfirst($pedido['tipo_pagamento']));?></a></th>
																		<td><?=dao('Sistema', 'Pedido')->getCategoriasPedido($pedido['id']);?></td>
																		<td><?=DateUtil::getDateDMY($pedido['data']);?></td>
																		<td><?=dao('Core', 'Cliente')->getField('nome', $pedido['id_cliente']);?></td>
																		<td>R$ <?=ValidateUtil::setFormatMoney($pedido['valor']);?></td>
																		<td>
																			<div class="form-group">
																				<input type="checkbox"
																					name="p-<?=$pedido['numero_pedido'];?>"
																					value="<?=$pedido['numero_pedido'];?>"
																					id="p-<?=$pedido['numero_pedido'];?>"
																					autocomplete="off" />
																				<div class="btn-group">
																					<label for="p-<?=$pedido['numero_pedido'];?>"
																						class="btn btn-primary"> <span
																						class="glyphicon glyphicon-ok"></span> <span> </span>
																					</label>
																				</div>
																			</div>
																		</td>
																	</tr>
            														<?php } ?>
																</table>
															<button type="submit" class="btn btn-primary">Gerar PLP</button>
														</form>
														<div class="col-md-6">
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="2b">
												<br>
												<table class="table table-striped"
													id="table-etiquetas">
													<thead>
														<tr>
															<th scope="col">PLP</th>
															<th scope="col">Entregas</th>
															<th scope="col">Data</th>
															<th scope="col">Expiração</th>
															<th scope="col">Ações</th>
														</tr>
													</thead>
													<?php foreach ($data['plps'] as $plp) { ?>
													<?php 
													$etiquetasDaPLP = dao('Core', 'Etiqueta')->select(['*'], ['id_pre_lista_postagem', '=', $plp['id']]);
													if(count($etiquetasDaPLP) != 0){
													?>
														<tr>
															<td scope="row" ><?=($plp['numero_plp'] != NULL) ? $plp['numero_plp'] : '<b>Aguardando Fechamento</b>';?></td>
															<td>        																		
															<?php
															foreach ($etiquetasDaPLP as $etiqueta) {
															    $numero_pedido = dao('Core', 'Pedido')->getField('numero_pedido', $etiqueta['id_pedido']);
															    $nome_cliente = dao('Core', 'Cliente')->getField('nome', dao('Core', 'Pedido')->getField('id_cliente', $etiqueta['id_pedido']));
															    $background = '#FFD700';
															    $color = '#000';
															    $text = 'Aguardando postagem - '.$numero_pedido." - ".$nome_cliente;
															    $idEtiqueta = $etiqueta['id'];
															    
															    if($etiqueta['postada']){
															        $background = 'green';
															        $color = '#FFF';
															        $text = 'Objeto encaminhado - '.$numero_pedido." - ".$nome_cliente;
															    }
															    
															    $dv =
															    "<div id='etq-".$idEtiqueta."' style='box-shadow: 0 3px 3px 0 rgba(0,0,0,.3); border-radius: 12px; display: block; padding: 4px 5px 3px; color: #FFF; background: ".$background."; padding: 7px !important; margin-left: 10px; margin-top: 5px !important;'>".
															    '<a style="color: '.$color.';" href="?m=sistema&c=venda&a=form&num='.$numero_pedido.'">'.$text."</a>";
															    
// 															    if(!$etiqueta['postada']){
															         $dv .= '<button type="button" onclick="return _desagruparEtiqueta('.$idEtiqueta.');" style="background: none; border: 0px;"><i class="fa fa-close" aria-hidden="true"></i> </button>';
// 															    }
															    
															    $dv .= '</div>';
															    
															    echo $dv;
															
     														    } ?>
        													</td>
															<td><?=DateUtil::getDateDMY($plp['data_geracao']);?><?=($plp['hora_geracao'] != '') ? ' às <b>'.$plp['hora_geracao'].'</b>' : '';?></td>
															<td><?=DateUtil::getDateDMY($plp['data_validade']);?></td>
															<td>
																<?php if($plp['numero_plp'] != NULL){ ?>
																<a href="?m=sistema&c=correios&a=download_etiqueta&arquivo=<?=$plp['numero_plp'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> baixar</button></a>
																<?php } ?>
																
																<button type="button" data-toggle="modal" data-target="#desgrup-<?=$plp['id'];?>" class="btn btn-warning"><i class="fa fa-close" aria-hidden="true"></i> </button>
																<div class="modal fade" id="desgrup-<?=$plp['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    																<div class="modal-dialog" role="document">
    																	<div class="modal-content">
    																		<div class="modal-header">
    																			<h4 class="modal-title" id="exampleModalLabel"><b>Desagrupar entregas</b></h4>
    																			<button type="button" class="close"
    																				data-dismiss="modal" aria-label="Close">
    																				<span aria-hidden="true">&times;</span>
    																			</button>
    																		</div>
    																		<div class="modal-body"><b>Deseja desagrupar as seguintes entregas ? </b><br><br>
    																		<?php
    																		foreach ($etiquetasDaPLP as $etiqueta) {
    																		  $numero_pedido = dao('Core', 'Pedido')->getField('numero_pedido', $etiqueta['id_pedido']);
    																		  $nome_cliente = dao('Core', 'Cliente')->getField('nome', dao('Core', 'Pedido')->getField('id_cliente', $etiqueta['id_pedido']));
    																		  $background = '#FFD700';
    																		  $color = '#000';
    																		  $text = 'Aguardando postagem - '.$numero_pedido." - ".$nome_cliente;
    																		  $idEtiqueta = $etiqueta['id'];
    																		
    																		  if($etiqueta['postada']){
    																		      $background = 'green';
    																		      $color = '#FFF';
    																		      $text = 'Objeto encaminhado - '.$numero_pedido." - ".$nome_cliente;
    																		}
    																		
    																		$dv3 = 
    																	         "<div style='box-shadow: 0 3px 3px 0 rgba(0,0,0,.3); border-radius: 12px; display: block; padding: 4px 5px 3px; color: #FFF; background: ".$background."; padding: 7px !important; margin-left: 10px; margin-top: 5px !important;'>".
        																		 '<a style="color: '.$color.';" href="?m=sistema&c=venda&a=form&num='.$numero_pedido.'">'.$text."</a></div>";
    																		echo $dv3;
    																		
    																		}
    																		?> 
    																		<br><br>
    																		<b>Ao confirmar, essas entregas serão removidas do agrupamento atual e estarão novamente disponíveis para serem reagrupadas.</b>
    																		</div>
    																		<div class="modal-footer">
    																			<button type="button" class="btn btn-secondary"
    																				data-dismiss="modal">Cancelar</button>
    																			<a href="?m=sistema&c=correios&a=desagrupar&id_plp=<?=$plp['id'];?>"><button type="button" class="btn btn-primary">Sim, desagrupar as entregas</button></a>
    																		</div>
    																	</div>
    																</div>
    															</div>
    															
    															<?php if($plp['numero_plp'] == NULL){ ?>
    															<button type="button" data-toggle="modal" data-target="#fecharplp-<?=$plp['id'];?>" class="btn btn-primary"><i class="fa fa-play" aria-hidden="true"></i> Gerar Etiquetas</button>
																<div class="modal fade" id="fecharplp-<?=$plp['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    																<div class="modal-dialog" role="document">
    																	<div class="modal-content">
    																		<div class="modal-header">
    																			<h4 class="modal-title" id="exampleModalLabel"><b>Gerar etiquetas (PLP: <?=($plp['numero_plp'] != NULL) ? $plp['numero_plp'] : '<b>Aguardando Fechamento</b>';?>)</b></h4>
    																			<button type="button" class="close"
    																				data-dismiss="modal" aria-label="Close">
    																				<span aria-hidden="true">&times;</span>
    																			</button>
    																		</div>
    																		<div class="modal-body">
    																		<form action="?m=sistema&c=correios&a=gerarEtiquetas" method="post">
    																			<input type="hidden" value="<?=$plp['id'];?>" name="id_plp">
        																		<label for="fullname">	Remetente:</label>
    																			<select name="id_fornecedor" required="required"
    																				class="form-control">
    																				<?php foreach ($data['pessoa'] as $n) {?>
    																				<option value="<?=$n['id'];?>"><?=$n['nome'];?></option>
    																				<?php } ?>
    																			</select>
    																			<br>
        																		<b>Pedidos </b><br><br>
        																		<?php
        																		foreach ($etiquetasDaPLP as $etiqueta) {
        																		    $numero_pedido = dao('Core', 'Pedido')->getField('numero_pedido', $etiqueta['id_pedido']);
        																		    $nome_cliente = dao('Core', 'Cliente')->getField('nome', dao('Core', 'Pedido')->getField('id_cliente', $etiqueta['id_pedido']));
        																		    $background = '#FFD700';
        																		    $color = '#000';
        																		    $text = 'Aguardando postagem - '.$numero_pedido." - ".$nome_cliente;
        																		    $idEtiqueta = $etiqueta['id'];
        																		    
        																		    if($etiqueta['postada']){
        																		        $background = 'green';
        																		        $color = '#FFF';
        																		        $text = 'Objeto encaminhado - '.$numero_pedido." - ".$nome_cliente;
        																		    }
        																		    
        																		    $dv3 =
        																		    "<div style='box-shadow: 0 3px 3px 0 rgba(0,0,0,.3); border-radius: 12px; display: block; padding: 4px 5px 3px; color: #FFF; background: ".$background."; padding: 7px !important; margin-left: 10px; margin-top: 5px !important;'>".
        																		    '<a style="color: '.$color.';" href="?m=sistema&c=venda&a=form&num='.$numero_pedido.'">'.$text."</a></div>";
        																		    echo $dv3;
        																		    
        																		}
        																		?>
        																		<br><br>
    																		    <br><br>
    																		    <a href=""><button type="submit" class="btn btn-primary">Gerar Etiquetas</button></a>
    																		</form>
    																		</div>
    																		<div class="modal-footer">
    																			<button type="button" class="btn btn-secondary"
    																				data-dismiss="modal">Cancelar</button>
    																		</div>
    																	</div>
    																</div>
    															</div>
    															<?php } ?>
															</td>
														</tr>
														<?php } ?>
														<?php } ?>
													</table>
											</div>
											<div class="tab-pane" id="3b">
												<br>
												<table class="table table-striped"
													id="entregas-encaminhadas">
													<thead>
														<tr>
															<th scope="col">PLP</th>
															<th scope="col">Objeto</th>
															<th scope="col">Data</th>
															<th scope="col">Ações</th>
														</tr>
													</thead>
													<?php foreach ($data['entregas_encaminhadas'] as $etq) { ?>
    													<?php 
    													
    													$_idRemetente = dao('Core', 'PreListaPostagem')->getField('id_remetente', $etq['id_pre_lista_postagem']);
    													$_plp = dao('Core', 'PreListaPostagem')->getField('numero_plp', $etq['id_pre_lista_postagem']);
    													$objeto = dao('Core', 'Rastreiamento')->getField('codigo', $etq['id_rastreamento']);
    													$numeroPedido = dao('Core', 'Pedido')->getField('numero_pedido', $etq['id_pedido']);
    													$idCliente = dao('Core', 'Pedido')->getField('id_cliente', $etq['id_pedido']);
    													$nomeCliente = dao('Core', 'Cliente')->getField('nome', $idCliente);
    													$remetente = trim(str_replace("Fornecedor", "", dao('Core', 'Pessoa')->getField('nome', $_idRemetente)));
    													
    													$background = '#FFD700';
    													$color = '#000';
    													$text = 'Aguardando postagem - '.$numeroPedido." - ".$nomeCliente;
    													
    													if($etq['postada']){
    													    $background = '#228B22';
    													    $color = '#FFF';
    													    $text = 'Objeto encaminhado - '.$numeroPedido." - ".$nomeCliente;
    													}
    													
    													$entrega_suspensa = FALSE;
    													if($etq['entrega_cancelada']){
    													    $background = '#D2691E';
    													    $color = '#FFF';
    													    $text = 'Entrega suspensa - '.$numeroPedido." - ".$nomeCliente;
    													    $entrega_suspensa = TRUE;
    													}
    													
    													$entga =  "<div style='box-shadow: 0 3px 3px 0 rgba(0,0,0,.3); border-radius: 12px; display: block; padding: 4px 5px 3px; color: #FFF; background: ".$background."; padding: 7px !important; margin-left: 0px; margin-top: 5px !important;'>".
    																	'<a style="color: '.$color.';" href="?m=sistema&c=venda&a=form&num='.$numeroPedido.'">'.$text."</a></div>";
    													?>
														<tr>
															<td><b>#<?=$_plp;?></b></td>
															<td><b><?=$objeto;?></b><br>Remetente: <?=$remetente;?><br><?=$entga;?></td>
															<td><?=DateUtil::getDateDMY($etq['data_geracao']);?></td>
															<td>
																<?php if(!$entrega_suspensa){ ?>    															
																<button type="button" data-toggle="modal" data-target="#cancelar-<?=$etq['id'];?>" class="btn btn-warning"><i class="fa fa-close" aria-hidden="true"></i> </button>
																<div class="modal fade" id="cancelar-<?=$etq['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    																<div class="modal-dialog" role="document">
    																	<div class="modal-content">
    																		<div class="modal-header">
    																			<h4 class="modal-title" id="exampleModalLabel"><b>Cancelar Objeto (<?=$objeto;?>)</b></h4>
    																			<button type="button" class="close"
    																				data-dismiss="modal" aria-label="Close">
    																				<span aria-hidden="true">&times;</span>
    																			</button>
    																		</div>
    																		<div class="modal-body">
    																		<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img-modal-<?=$etq['id'];?>" style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
    																		<div class="" id="msg-cancelamento-<?=$etq['id'];?>" role="alert" style="display: none; width: 100%; background: #eee; color: #333; border: 0px; padding: 25px; border-radius: 7px;">
                                                                				<span id="text-cancelamento-<?=$etq['id'];?>" style="margin-left: 10px; font-weight: bold;"></span>
                                                                			</div>
                                                                			<br>
    																		<b>Deseja cancelar a seguinte entrega ? </b><br><br>
    																		
    																		Cliente: <b><?=$nomeCliente;?></b><br>
    																		Rastreio: <b><?=$objeto;?></b><br>
    																		Pedido: <b><?=$numeroPedido;?></b>
    																		<br><br>
    																		<b>Ao confirmar, o envio desta entrega será cancelado e devolvida ao remetente.</b>
    																		
    																		<input type="hidden" id="id_etiqueta_<?=$etq['id'];?>" name="id_etiqueta_<?=$etq['id'];?>" value="<?=$etq['id'];?>">
    																		</div>
    																		<div class="modal-footer">
    																			<button type="button" class="btn btn-secondary"
    																				data-dismiss="modal">Fechar</button>
    																			<button type="button" id="cancelar_objeto_btn_<?=$etq['id'];?>" class="btn btn-success" name="cancelar_nota" value="cancelar_nota" onclick="">Sim, cancelar entrega</button>	
    																		</div>
    																	</div>
    																</div>
    															</div>
    															<script>
                    											$(document).ready(function () {
                    												$('#cancelar_objeto_btn_<?=$etq['id'];?>').click(function(e) {
                    													$('#load-img-modal-<?=$etq['id'];?>').css('display', 'inline-block');
                    													$('body').css("opacity", "0.5");
                    													                                                        	    	
    																	var idEtiqueta = $("#id_etiqueta_<?=$etq['id'];?>").val();
    
    																	setTimeout(function(){ 
    																		$.ajax({
    				                                            				type : 'POST',
    				                                            				dataType : "text",
    	                                                        				async : false,
    	                                                        				url : "?m=sistema&c=correios&a=cancelarObjeto",
    	                                                        				data : {
    	                                                        					idEtiqueta: idEtiqueta
    	                                                        				},					  
    	                                                        				success: function(data){
    	                                                            				json = JSON.parse(data);
    	                                                                            $('#msg-cancelamento-<?=$etq['id'];?>').css("display", "table");
    	                                                                            document.getElementById('text-cancelamento-<?=$etq['id'];?>').innerHTML = json.mensagem;
    	                                                            				
    	                        													$('#load-img-modal-<?=$etq['id'];?>').css('display', 'none');
    	                        													$('body').css("opacity", "1");
    	                                                        				},
    	                                                        			});
    				                                        	 		}, 100);
                                                                    });
                    											 });
                                                                </script>
                                                                <?php } ?>
															</td>
														</tr>
														<?php } ?>
													</table>
											</div>
											<script
												src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
											<script
												src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
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
		</div>
	</div>
	<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="public/admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="public/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script src="public/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="public/admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
	<script src="public/admin/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script src="public/admin/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="public/admin/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="public/admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
	<script src="public/admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
	<script src="public/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="public/admin/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
	<script src="public/admin/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>
	
	<script>
    $(document).ready(function() {
    	$('#table-pedidos-aprovados').dataTable();
        $('#table-etiquetas').dataTable();
        $('#entregas-encaminhadas').dataTable();
	});

    $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
	    $(".alert-success").slideUp(500);
	});

    function showLoad(){
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
    }
    
    function hideLoad(){
    	$('#load-img').css('display', 'inline-block');
    	$('body').css("opacity", "0.5");
    }

    function sleep(milliseconds) {
	  const date = Date.now();
	  let currentDate = null;
	  do {
	    currentDate = Date.now();
	  } while (currentDate - date < milliseconds);
	}

    function _desagruparEtiqueta(idEtiqueta) {
    	hideLoad();
    	$.ajax({
			type : 'POST',
			dataType : "text",
			async : false,
			url : "/?m=sistema&c=correios&a=degruparEtiqueta",
			data : {
				"id_etiqueta" : idEtiqueta,
			},
			success: function (response) {
				if(response){
					$('#div-msg').css('display', 'inline-block');
					$('#div-msg').css('background', 'rgba(38, 185, 154, 0.88)');
					$('#div-msg').css('color', '#FFF');
		    	 	$("#etq-"+idEtiqueta).hide();
					document.getElementById('msg').innerHTML = '<b>Etiqueta #'+idEtiqueta+' desagrupada com sucesso!</b>';
				    $("#div-msg").fadeTo(2000, 500).slideUp(500, function(){
			 	    	$("#div-msg").slideUp(500);
			 		});
								
				}else if(!response){
					$('#div-msg').css('display', 'inline-block');
					$('#div-msg').css('background', '#FFD700');
					$('#div-msg').css('color', '#000');
					
					document.getElementById('msg').innerHTML = '<b>Erro ao desagrupar etiqueta '+idEtiqueta+'</b>';
				}
			}
		});

    	setInterval(function() {
    		showLoad();
		}, 1800);
    }
    </script>
</body>
</html>
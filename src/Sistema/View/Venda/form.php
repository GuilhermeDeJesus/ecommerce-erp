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
<title>#<?=$data['pedido']['numero_pedido'];?> | <?=dao('Core', 'Cliente')->getField('nome', $data['pedido']['id_cliente']);?></title>
<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/switchery/dist/switchery.min.css"
	rel="stylesheet">	
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
<link href="public/admin/vendors/dropzone/dist/min/dropzone.min.css"
	rel="stylesheet">
<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
<script src="public/admin/vendors/jquery.inputmask/dist/inputmask/jquery.maskMoney.min.js"></script>
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>
								Pedido <small>#<?=$data['pedido']['numero_pedido'];?></small>
							</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12">
							<?php if(isset($data['alert']) && $data['alert'] == true){ ?>
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
								<div class="x_title">
									<h2>
										Transação: <b style="color: #26B99A;">#<?=$data['pedido']['codigo_transacao'];?></b>
									</h2>
									<h2 style="margin-left: 10px;">
										Despacho: <b style="color: #26B99A;"><?=dao('Core', 'PedidoStatusFornecedor')->getField('status', $data['pedido']['id_pedido_status_fornecedor']);?></b>
									</h2>
									<?php if($data['pedido']['numero_recibo_fornecedor'] != ''){?>
									<h2 style="margin-left: 10px;">
										Número Recibo: <b style="color: #26B99A;"><?=$data['pedido']['numero_recibo_fornecedor'];?></b>
									</h2>
									<?php } ?>
									<ul class="nav navbar-right panel_toolbox">
										<li
											><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="modal" data-target="#debugfraude">
												<i class="fa fa-bug"></i>
											</a>
											<!-- DEBUG -->
                                            <div class="modal fade" id="debugfraude" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Debug Análise Clearsale</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                  <p>sessionid: <?=$data['pedido']['session_id']; ?></p>
                                                  <?php 
                                                  echo '<pre>';
                                                  print_r(unserialize($data['pedido']['debug_order_clearsale']));
                                                  echo '</pre>';
                                                  ?>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
										<li>
											<a class="close-link">
												<i class="fa fa-close"></i>
											</a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<section class="content invoice">
										<div class="row">
											<div class="col-xs-12 invoice-header">
												<h1>
													<i class="fa fa-user"></i> <?=dao('Core', 'Cliente')->getField('nome', $data['pedido']['id_cliente']);?><small
														class="pull-right">Data: <?=DateUtil::dateLiteral($data['pedido']['data']);?><?=($data['pedido']['hora'] != '') ? ' às '.$data['pedido']['hora'] : '';?></small>
												</h1>
											</div>
										</div>
										<div class="row invoice-info">
											<div class="col-sm-4 invoice-col" style="display: none;">
												De
												<address>
													<strong>Iron Admin, Inc.</strong> <br>795 Freedom Ave,
													Suite 600 <br>New York, CA 94107 <br>Phone: 1 (804)
													123-9876 <br>Email: ironadmin.com
												</address>
											</div>
											<?php
                                            $_cliente = explode(' ', dao('Core', 'Cliente')->getField('nome', $data['pedido']['id_cliente']));
                                            $_cpf = dao('Core', 'Cliente')->getField('cpf', $data['pedido']['id_cliente']);
                                            $_dataNascimento = dao('Core', 'Cliente')->getField('data_nascimento', $data['pedido']['id_cliente']);
                                            $_idade = DateUtil::calculateTimeDifferenceRaw($_dataNascimento, date('Y-m-d'));
                                            ?>
											<div class="col-sm-4 invoice-col">
												<br>
												<address>
    												<?php 
    												$tipoCliente = dao('Core', 'TipoCliente')->getField('tipo', $data['cliente'][0]['id_tipo_cliente']);
    												$SiglaTipoCliente = dao('Core', 'TipoCliente')->getField('sigla', $data['cliente'][0]['id_tipo_cliente']);
    												
    												if($tipoCliente != NULL){ ?>
    												<?php if($SiglaTipoCliente == 'NV'){ ?>
    												<span class="label label-info"><?=$tipoCliente;?></span>
    												<?php } ?>
    												<?php if($SiglaTipoCliente == 'FD'){ ?>
    												<span class="label label-danger"><?=$tipoCliente;?></span>
    												<?php } ?>
    												<?php if($SiglaTipoCliente == 'RC'){ ?>
    												<span class="label label-success"><?=$tipoCliente;?></span>
    												<?php } ?>
    												<?php } ?>
    												<br><br>
													CPF: <strong><?=ValidateUtil::setFormatCPF($_cpf);?></strong><br>
													Data Nascimento: <strong><?=DateUtil::getDateDMY($_dataNascimento);?> ( <?=$_idade['years'];?> anos )</strong><br>
													Destinatário: <strong><?=$data['endereco_destino_pedido']['destinatario'];?></strong>
													<br>Endereço: <b><?=$data['endereco_destino_pedido']['endereco'];?></b>
													<br>Bairro: <b><?=$data['endereco_destino_pedido']['bairro'];?></b><br>Cidade:
													<b><?=$data['endereco_destino_pedido']['cidade'];?></b><br>Número:
													<b><?=$data['endereco_destino_pedido']['numero'];?></b><br>Complemento:
													<b><?=$data['endereco_destino_pedido']['complemento']; ?></b><br>
													CEP: <b><?=ValidateUtil::setFormatCEP($data['endereco_destino_pedido']['cep']);?></b><br>
													UF: <b><?=$data['endereco_destino_pedido']['uf'];?></b><br>Telefone: <?=dao('Core', 'Cliente')->getField('telefone', $data['pedido']['id_cliente']);?> <br>Email:
													<?=dao('Core', 'Cliente')->getField('email', $data['pedido']['id_cliente']);?><br>
													Código de Rastreio: <?=$data['rastreio'][0]['codigo'];?><?php if($data['rastreio'][0]['codigo'] != ''){ ?> <b><a
														target="new"
														href="https://api.whatsapp.com/send?phone=55<?=dao('Core', 'Cliente')->getField('telefone', $data['pedido']['id_cliente']);?>&text=Ol%C3%A1%20<?=$_cliente[0];?>,%20%0A%0ATudo%20Joia%20? %E2%98%BA%0A%0ASomos%20da%20Equipe%20<?=NOME_LOJA?>%20e%20informamos%20que%20seu%20pedido%20já%20foi%20despachado.%20%20%0A%0ASeu%20código%20de%20rastreamento%20é%20<?=$data['rastreio'][0]['codigo'];?>&source=&data="">Enviar
															por WhatsApp</a></b><?php } ?><br>
													Código Transação: <?=$data['pedido'][0]['codigo_transacao']?> <b><a
														target="new"
														href="https://trade.aliexpress.com/order_detail.htm?spm=a2g0s.9042311.0.0.14464c4dXUoLOb&orderId=<?=$data['pedido']['numero_recibo_fornecedor'];?>">Ver
															detalhes</a></b><br> Contato WhatsApp: <b><a target="new"
														href="https://api.whatsapp.com/send?phone=55<?=dao('Core', 'Cliente')->getField('telefone', $data['pedido']['id_cliente']);?>&text=Ol%C3%A1%20<?=$_cliente[0];?>,%20%0A%0ATudo%20Joia%20? %E2%98%BA%0A%0ASomos%20da%20Equipe%20<?=NOME_LOJA?>%20e%20estamos%20aqui%20para%20te%20ajudar%20caso%20tenha%20alguma%20d%C3%BAvida%20sobre%20seu%20pedido%20de%20uma%20Chapinha%20Dupla%20Profissional.%20%0A%0AVoc%C3%AA%20conseguiu%20realizar%20a%20compra%20sem%20problemas?🥰&source=&data="">Abrir
															WhatsApp</a></b><br>
														Declaração de Conteúdo: <b><a target="new"  title="Declaração de Conteúdo"  href="?m=sistema&c=venda&a=declaracao_conteudo&id=<?=$data['pedido']['id'];?>">Abrir</a>
														</b><br>
														Nota Fiscal: <? if($data['pedido']['id_nota_fiscal'] != NULL) { ?> <b><a target="new"  title="Nota Fiscal"  href="?m=sistema&c=venda&a=nota_fiscal&id_nota_fiscal=<?=$data['pedido']['id_nota_fiscal'];?>">Abrir</a>
														</b><?php } ?><br>
														Confirmar Compra: <b><a target="new"
														href="https://api.whatsapp.com/send?phone=55<?=dao('Core', 'Cliente')->getField('telefone', $data['pedido']['id_cliente']);?>&text=Ol%C3%A1%20<?=$_cliente[0];?>%2C%20sou%20a%20J%C3%A9ssica%20da%20loja%20Shopvitas%2C%20Tudo%20bem%20%3F%20%20%20%E2%98%BA%0A%0ARecebemos%20seu%20pedido%20em%20nossa%20loja%20e%20estamos%20aqui%20para%20confirmar%20a%20sua%20compra%20feita%20em%20nossa%20loja.%20Precisamos%20que%20confirme%20a%20sua%20identidade%20com%20um%20documento%20com%20foto%20para%20que%20possamos%20enviar%20o%20seu%20pedido%2C%20pois%20o%20mesmo%20foi%20para%20an%C3%A1lise%20manual%20para%20verificar%20se%20não%20se%20trata%20de%20uma%20tentativa%20de%20fraude.">Abrir WhatSapp</a></b>
														<br>
														<span class="label label-warning" data-toggle='modal' data-target='#atualizar-end'>Atualizar Endereço</span>
        												<div class="modal fade" id="atualizar-end" tabindex="-1"
        													role="dialog" aria-labelledby="myModalLabel"
        													aria-hidden="true">
        													<div class="modal-dialog modal-lg">
        														<div class="modal-content">
        															<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img-modal" style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
        															<div class="">
        															    <div class="row">
                                                                          <div class="col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="x_panel">
                                                                              <div class="x_title">
                                                                                <h2>Endereço de entrega <small></small></h2>
                                                                                <div class="clearfix"></div>
                                                                              </div>
                                                                              <div class="x_content">
                    															<div id='modal-global2' class='modal fade bd-example-modal-sm' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel' aria-hidden='true'>
                                                                                 <div class='modal-dialog modal-sm'>
                                                                                    <div class='modal-conten'>
                                                                                        <div style='width: 400px; 
                                                                                                    height:60px; 
                                                                                                    border-radius:5px; 
                                                                                                    text-align: center; 
                                                                                                    background-color: #696969;'>
                                                                                            <h5 style='color: #FFF; margin-top: 100px;'><br>            																
                                                                                            <span id="text-endereco-atualizar" style="margin-left: 10px; font-weight: bold;"></span>
                                                                                            </h5>
                                                                                    	</div>
                                                                                  	</div>
                                                                                  </div>
                                                                                </div>
                                                                                <form id="form-atualizar-endereco" data-parsley-validate class="form-horizontal form-label-left">
                                                                                  <input type="hidden" name="id_pedido" value="<?=$data['pedido']['id'];?>">
                                                                                  <input type="hidden" name="id_endereco_entrega" value="<?=$data['endereco_destino_pedido']['id'];?>">
                                                                                  <input type="hidden" name="id_cliente" value="<?=$data['cliente'][0]['id'];?>">
        																		  <div class="card" style="border: 1px solid #ccc !important;  border-radius: 5px; padding-left: 10px; background: #FFF; margin-bottom: 20px;">
                                                                                      <div class="card-body">
                                                                                            <h5 class="card-title" style="magin-left: 10px; font-weight: bold !important;"> Cliente</h5>
                                                                                      </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Endereço <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="nome_cliente"
        																					value="<?=$data['cliente'][0]['nome'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
        																		  <div class="card" style="border: 1px solid #ccc !important;  border-radius: 5px; padding-left: 10px; background: #FFF; margin-bottom: 20px;">
                                                                                      <div class="card-body">
                                                                                            <h5 class="card-title" style="magin-left: 10px; font-weight: bold !important;"> Nome</h5>
                                                                                      </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Endereço <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="endereco"
        																					value="<?=$data['endereco_destino_pedido']['endereco'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Bairro <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="bairro"
        																					value="<?=$data['endereco_destino_pedido']['bairro'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Cidade <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="cidade"
        																					value="<?=$data['endereco_destino_pedido']['cidade'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Número <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="numero"
        																					value="<?=$data['endereco_destino_pedido']['numero'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">CEP <span class="required"></span>
                                                                                    </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                   		<input type="text"
        																					name="cep"
        																					value="<?=$data['endereco_destino_pedido']['cep'];?>"
        																					class="form-control col-md-7 col-xs-12">
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Estado </label>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                    	<select name="uf"
        																					class="form-control col-md-7 col-xs-12">
        																					<option value="<?=$data['endereco_destino_pedido']['uf'];?>"><?=$data['endereco_destino_pedido']['uf'];?></option>
        																					<?php foreach (estadosBrasileiros() as $indice => $estado){?>
        																					<option value="<?=$indice;?>"><?=$indice;?> - <?=$estado;?></option>
        																					<?php } ?>
        																				</select>
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="ln_solid"></div>
                                                                                  <div class="form-group">
                                                                                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                                                                      <button style="float: right;" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                                                      <button style="float: right;" type="button" class="btn btn-success" id="btn-salvar-endereco">Salvar</button>
                                                                                    </div>
                                                                                  </div>
                                                                                </form>
                                                                                <script>
                                    											$(document).ready(function () {
                                    												$('#btn-salvar-endereco').click(function(e) {
                                    													$('#load-img-modal').css('display', 'inline-block');
                                    													$('body').css("opacity", "0.5");
                                    													                                                        	    	
                    																	setTimeout(function(){ 
                    																		$.ajax({
                    				                                            				type : 'POST',
                    				                                            				dataType : "text",
                    	                                                        				async : false,
                    	                                                        				url : "?m=sistema&c=venda&a=atualizarEnderecoEntrega",
                    	                                                        				data : {
                    	                                                        					"data" : JSON.stringify($('#form-atualizar-endereco').serializeArray()),
                    	                                                        				},					  
                    	                                                        				success: function(data){
                    	                                                            				json = JSON.parse(data);
                    	                                                                            $('#msg').css("display", "table");
                    	                                                                            document.getElementById('text-endereco-atualizar').innerHTML = json.mensagem;
                    	                                                            				
                    	                        													$('#load-img-modal').css('display', 'none');
                    	                        													$('body').css("opacity", "1");
        //             	                        													$('#add-codigo').modal('hide');
                    	                        													$('#modal-global2').modal('show');
                    	                        													setInterval(function() {
                    	                        														$('#modal-global2').modal('hide');
                    	                        														if (setRefresh == true) {
                    	                        															location.reload();
                    	                        														}
                    	                        													}, 1800);
                    	                                                        				},
                    	                                                        			});
                    				                                        	 		}, 100);
                                                                                    });
                                    											 });
                                                                                </script>
                                                                              </div>
                                                                            </div>
                                                                          </div>
                                                                        </div>
        															</div>
        														</div>
        													</div>
														</div>
												</address>
											</div>
											<div class="col-sm-4 invoice-col">
												<?php if($data['endereco_localizacao_compra']['endereco'] != NULL){ ?>
												<br>
												<b>Geolocalização</b> <br>
												<br>Endereço: <b><?=$data['endereco_localizacao_compra']['endereco'];?></b>
												<br>Bairro: <b><?=$data['endereco_localizacao_compra']['bairro'];?></b><br>Cidade:
												<b><?=$data['endereco_localizacao_compra']['cidade'];?></b><br>Número:
												<b><?=$data['endereco_localizacao_compra']['numero'];?></b><br>
												CEP: <b><?=ValidateUtil::setFormatCEP($data['endereco_localizacao_compra']['cep']);?></b><br>
												UF: <b><?=$data['endereco_localizacao_compra']['uf'];?></b><br>
												Dispositivo: <b><?=$data['pedido']['dispositivo'];?></b><br>
												IP: <b><?=$data['endereco_localizacao_compra']['ip'];?></b> <i class="fa fa-eye" data-toggle="modal" data-target="#ips"></i><br>
												<!-- Modal -->
                                                <div class="modal fade" id="ips" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Ips Relacionados</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <table class="table">
                                                          <thead class="thead-dark">
                                                            <tr>
                                                              <th scope="col">Cliente</th>
                                                              <th scope="col">Endereço</th>
                                                              <th scope="col">Bairro</th>
                                                              <th scope="col">Cidade</th>
                                                              <th scope="col">CEP</th>
                                                              <th scope="col">UF</th>
                                                              <th scope="col">IP</th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                          	<?php foreach ($data['outros_pedidos_com_esse_ip'] as $ips) { ?>
                                                            <tr>
                                                              <td><a href="?m=sistema&c=venda&a=form&id_cliente=<?=$ips['id_cliente'];?>"><?=dao('Core', 'Cliente')->getField('nome', $ips['id_cliente']);?></a></td>
                                                              <td><?=$ips['endereco'];?></td>
                                                              <td><?=$ips['bairro'];?></td>
                                                              <td><?=$ips['cidade'];?></td>
                                                              <td><?=$ips['cep'];?></td>
                                                              <td><?=$ips['uf'];?></td>
                                                              <td><?=$ips['ip'];?></td>
                                                            </tr>
                                                            <?php } ?>
                                                            <?php foreach ($data['outros_pedidos_com_esse_end'] as $ips) { ?>
                                                            <tr>
                                                              <td><a href="?m=sistema&c=venda&a=form&id_cliente=<?=$ips['id_cliente'];?>"><?=dao('Core', 'Cliente')->getField('nome', $ips['id_cliente']);?></a></td>
                                                              <td><?=$ips['endereco'];?></td>
                                                              <td><?=$ips['bairro'];?></td>
                                                              <td><?=$ips['cidade'];?></td>
                                                              <td><?=$ips['cep'];?></td>
                                                              <td><?=$ips['uf'];?></td>
                                                              <td><?=$ips['ip'];?></td>
                                                            </tr>
                                                            <?php } ?>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
												<?php } ?>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 table">
												<table class="table table-responsive">
													<thead>
														<tr>
															<th>Produto</th>
															<th style="width: 50%">Descrição</th>
															<th>Situação</th>
															<th>Qtd</th>
															<th>Tamanho</th>
															<th>Cor</th>
															<th>Fornecedor</th>
															<th>Subtotal</th>
															<th style="display: none;">Fazer Pedido</th>
														</tr>
													</thead>
													<tbody>
														<?php
                                                        foreach ($data['itens'] as $item) {
                                                            $img = getImagensProduto($item['id_produto']);
                                                            ?>
														<tr>
															<td><div style="display: inline;">
																	<img style="width: 65px; float: left;"
																		src="data/products/<?=$item['id_produto'];?>/principal.jpg"
																		alt=" " class="img-responsive">
																</div></td>
															<td>
																<div style="display: inline;">
																	<br> <a
																		href="?m=sistema&c=produto&a=inserirEditar&id=<?=$item['id_produto'];?>"><?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?></a>
																</div>
															</td>
															<td><?=dao('Core', 'SituacaoItemPedido')->getField('situacao', $item['id_situacao_item_pedido']);?></td>
															<td><?=$item['quantidade'];?></td>
															<td><?=($item['id_tamanho_produto'] != NULL) ? dao('Core', 'TamanhoProduto')->getField('descricao', $item['id_tamanho_produto']) : 'N/A';?></td>
															<td><?=($item['id_cor_produto'] != NULL) ? dao('Core', 'CorProduto')->getField('nome', $item['id_cor_produto']) : 'N/A';?></td>
															<td><?=dao('Core', 'Pessoa')->getField('nome', dao('Core', 'Produto')->getField('id_fornecedor', $item['id_produto']));?></td>
															<td>R$ <?=ValidateUtil::setFormatMoney($item['preco']);?></td>
															<td style="display: none;"><a target="new"
																href="<?=dao('Core', 'Produto')->getField('link_compra', $item['id_produto']);?>">Comprar</a>
															</td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 col-xs-6">
												<p class="lead">
													<?php
                                                    $tipo = $data['pedido']['tipo_pagamento'];
                                                    $link = $data['pedido']['link_boleto'];
                                                    $codigobarras = $data['pedido']['link_boleto'];;
                                                    
                                                    if ($data['pedido']['tipo_pagamento'] == 'cartao') {
                                                        $tipo = 'Cartão de Crédito';
                                                    }
                                                    
                                                    if ($data['pedido']['tipo_pagamento'] == 'boleto') {
                                                        $tipo = 'Boleto';
                                                    }
                                                    ?>
													Pagamento: <b><?=$tipo;?></b>
												</p>
												<?php
												$sts = '';
												if(strlen($data['pedido']['status_clear_sale']) > 2){
												    $sts = '- '.$data['pedido']['status_clear_sale'];
												}
												?>
												<p class="text-muted well well-sm no-shadow"
													style="margin-top: 10px;">
													Situação: <b><?=dao('Core', 'SituacaoPedido')->getField('situacao', $data['pedido']['id_situacao_pedido']);?> <?=$sts;?></b>
												</p>
												<?php if($link != NULL){ ?>
												<button style="border-color: #a1a1a1;" class="btn btn-default"><a style="color: #a1a1a1; font-weight: bold;" href="<?=$link;?>" target="new">Ver Boleto</a></button>
												<?php } ?>
											</div>
											<div class="col-md-6 col-xs-6">
												<p class="lead">Preço</p>
												<div class="table-responsive">
													<table class="table">
														<tbody>
															<tr>
																<th style="width: 50%">Subtotal:</th>
																<td>R$ <?=ValidateUtil::setFormatMoney(($data['pedido']['valor']));?></td>
															</tr>
															<tr>
																<th>Frete:</th>
																<td>R$ <?=ValidateUtil::setFormatMoney($data['pedido']['frete']);?><?=($data['pedido']['frete_gratis'] != 0) ? ' - Frete Grátis' : '';?> - <img src="public/img/fe_<?=strtolower(getFormaEnvioPorCodigo($data['pedido']['codigo_envio']));?>-loja.jpg"></td>
															</tr>
															<tr>
																<th>Lucro:</th>
																<td>R$ <?=ValidateUtil::setFormatMoney($data['pedido']['lucro']);?></td>
															</tr>
															<tr>
																<th>Total:</th>
																<td>R$ <?=ValidateUtil::setFormatMoney($data['pedido']['valor']  + $data['pedido']['frete']);?></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="row no-print">
											<div class="col-xs-12">
											
												<?php if($data['pedido']['id_situacao_pedido'] == 6 || $data['pedido']['id_situacao_pedido'] == 3){ ?>
    												<button class="btn btn-default" data-toggle="modal" data-target="#capturarModal" style="border-color: #a1a1a1;">
    													<a style="color: #a1a1a1; font-weight: bold;"
    														href="#"><i class="fa fa-money"></i> CAPTURAR</a>
    												</button>
    												<!-- Capturar Pagamento -->
                                                    <div class="modal fade" id="capturarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Tem certeza que deseja capturar essa transação? </p>
                                                            <form action="?m=sistema&c=venda&a=capturarPagamento" method="post">
                                                            	<input type="hidden" name="id" value="<?=$data['pedido']['id'];?>">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Cancelar</button>
                                                            	<button type="submit" class="btn btn-default" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;" >Confirmar</button>
                                                            </form>
                                                          </div>
                                                          <div class="modal-footer">
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                <?php } ?>
                                                				
                                                <?php if($data['pedido']['id_situacao_pedido'] != 2){ ?>											
    												<button class="btn btn-default" data-toggle="modal" data-target="#aprovarModal" style="border-color: #a1a1a1;">
    													<a style="color: #a1a1a1; font-weight: bold;"
    														href="#"><i class="fa fa-money"></i> APROVAR</a>
    												</button>
    												<!-- Aprovar Pagamento -->
                                                    <div class="modal fade" id="aprovarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Tem certeza que deseja aprovar essa transação? </p>
                                                            <form action="?m=sistema&c=venda&a=aprovarPagamento" method="post">
                                                            	<input type="hidden" name="id" value="<?=$data['pedido']['id'];?>">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Cancelar</button>
                                                            	<button type="submit" class="btn btn-default" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Confirmar</button>
                                                            </form>
                                                          </div>
                                                          <div class="modal-footer">
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                <?php } ?>
												
												<button class="btn btn-default" style="border-color: #a1a1a1;">
													<a style="color: #a1a1a1; font-weight: bold;"
														href="?m=sistema&c=venda&a=cancelarPedido&id=<?=$data['pedido']['id'];?>"><i class="fa fa-close"></i> CANCELAR</a>
												</button>
												
												<button class="btn btn-default" style="border-color: #a1a1a1;">
													<a style="color: #a1a1a1; font-weight: bold;"
														href="?m=sistema&c=venda&a=cancelarPedidoBoleto&id=<?=$data['pedido']['id'];?>"><i class="fa fa-close"></i> CANCELAR BOLETO</a>
												</button>
												
												<?php if($data['pedido']['id_situacao_pedido'] != 4){ ?>
												<button class="btn btn-default" style="border-color: #a1a1a1;">
													<a style="color: #a1a1a1; font-weight: bold;"
														href="?m=sistema&c=venda&a=chargebackPedido&id=<?=$data['pedido']['id'];?>"><i class="fa fa-money"></i> CHARGEBACK</a>
												</button>
												<?php } ?>
												
												<?php // if($data['pedido']['id_situacao_pedido'] == 4){ ?>											
    												<button class="btn btn-default" data-toggle="modal" data-target="#cobrarModal" style="border-color: #a1a1a1;">
    													<a style="color: #a1a1a1; font-weight: bold;"
    														href="#"><i class="fa fa-money"></i> FAZER COBRANÇA</a>
    												</button>
    												<!-- Cobrar Pagamento -->
                                                    <div class="modal fade" id="cobrarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Tem certeza que deseja fazer a cobrança deste pedido? </p>
                                                            <form action="?m=sistema&c=venda&a=cobrarPedido2" method="post">
                                                            	<input type="hidden" name="id" value="<?=$data['pedido']['id'];?>">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Cancelar</button>
                                                            	<button type="submit" class="btn btn-default" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Confirmar</button>
                                                            </form>
                                                          </div>
                                                          <div class="modal-footer">
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                <?php // } ?>
												
												<?php if($data['pedido']['id_situacao_pedido'] == 2 && $data['pedido']['gateway'] == 'Pagar.me'){ ?>											
    												<button class="btn btn-default" data-toggle="modal" data-target="#estornarModal" style="border-color: #a1a1a1;">
    													<a style="color: #a1a1a1; font-weight: bold;"
    														href="#"><i class="fa fa-money"></i> ESTORNAR</a>
    												</button>
    												<!-- Aprovar Pagamento -->
                                                    <div class="modal fade" id="estornarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Tem certeza que deseja estornar essa transação? </p>
                                                            <form action="?m=sistema&c=venda&a=reembolsarPedido" method="post">
                                                            	<input type="hidden" name="id" value="<?=$data['pedido']['id'];?>">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Cancelar</button>
                                                            	<button type="submit" class="btn btn-default" style="color: #a1a1a1; font-weight: bold; border-color: #a1a1a1;">Confirmar</button>
                                                            </form>
                                                          </div>
                                                          <div class="modal-footer">
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                <?php } ?>
												
												<button class="btn btn-default" style="display: none;" onclick="window.print();">
													<i class="fa fa-print"></i> Imprimir
												</button>
												<button class="btn btn-success pull-right" style="display: none;"
													data-toggle='modal' data-target='#dados-frete'>
													<i class="fa fa-credit-card"></i> VER DADOS DE FRETE
												</button>
												<!-- DADOS FRETE-->
												<div class="modal fade" id="dados-frete" tabindex="-1"
													role="dialog" aria-labelledby="myModalLabel"
													aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<span
																	style="font-size: 16px; color: #73879C; font-weight: 600;">Calculo
																	de Frete: Melhor Envio</span>
															</div>
															<div id="msg"></div>
															<div class="">
																<br>
																	<?php foreach ($data['fretes'] as $fornecedor => $frete) { ?>
																	<span style="margin-left: 40px;"> Fornecedor: <b><?=$fornecedor;?></b></span>
																<ul class="caixa-info" style="margin-left: 30px;">
																	<li><span> Cep Origem: <b><?=$frete['CepOrigem'];?></b></span></li>
																	<li><span> Cep Destino: <b><?=$frete['CepDestino'];?></b></span></li>
																	<li><span> Peso: <b><?=$frete['Peso'];?> Kg</b></span></li>
																	<li><span> Comprimento: <b><?=$frete['Comprimento'];?> cm</b></span></li>
																	<li><span> Altura: <b><?=$frete['Altura'];?> cm</b></span></li>
																	<li><span> Largura: <b><?=$frete['Largura'];?> cm</b></span></li>
																	<li><span> Diametro: <b><?=$frete['Diametro'];?> cm</b></span></li>
																	<li><span> Valor: <b>R$ <?=$frete['valor'];?></b></span></li>
																</ul>
																<hr>
																	<?php } ?>
																	<br>
															</div>
														</div>
													</div>
												</div>
												<button class="btn btn-success pull-right"
													data-toggle='modal' data-target='#troca-dev'><i class="fa fa-ticket"></i> Troca ou Devolução</button>
												<div class="modal fade" id="troca-dev" tabindex="-1"
													role="dialog" aria-labelledby="myModalLabel"
													aria-hidden="true">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<div class="modal-header">
																<span
																	style="font-size: 16px; color: #73879C; font-weight: 600;">Troca ou Devolução</span>
															</div>
															<div id="msg"></div>
															<div class="modal-body">
															<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                                               <div class="panel">
                                                                    <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                      <h4 class="panel-title">Etiquetas</h4>
                                                                    </a>
                                                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                                      <div class="panel-body">
                                                                        <table class="table table-bordered">
                                                                          <thead>
                                                                            <tr>
                                                                              <th>PLP</th>
                                                                              <th>Código Rastreio</th>
                                                                              <th>Status</th>
                                                                              <th>#</th>
                                                                            </tr>
                                                                          </thead>
                                                                          <tbody>
                                                                          <?php foreach ($data['etiquetas_dev_tro'] as $et){ ?>
                                                                            <tr>
                                                                              <th scope="row"><?=$et['plp'];?></th>
                                                                              <td><?=$et['codigo_rastreio'];?></td>
                                                                              <td><?=$et['status'];?></td>
                                                                              <td>
                                                                              	<a href="?m=sistema&c=correios&a=download_etiqueta&arquivo=<?=$et['plp'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> baixar</button></a>
                                                                              </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                          </tbody>
                                                                        </table>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                  <div class="panel">
                                                                    <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                      <h4 class="panel-title">Formulário - Gerar Etiqueta</h4>
                                                                    </a>
                                                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                                      <div class="panel-body">
                                                                        <form
        																	action="?m=sistema&c=EtiquetaDevolucaoProdutoCliente&a=gerarEtiqueta"
        																	method="post" class="form-horizontal form-label-left">
        																	<div class="row">
        																		<div class="col-md-12 col-sm-12 col-xs-12 form-group">
        																			<div class="col-md-12 col-sm-9 col-xs-12">
        																				<input type="hidden" name="id_pedido" value="<?=$data['pedido']['id'];?>">
        																				<div style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                            													Remetente: <strong><?=$data['endereco_destino_pedido']['destinatario'];?></strong>
                                            													<br>Endereço: <b><?=$data['endereco_destino_pedido']['endereco'];?></b>
                                            													<br>Bairro: <b><?=$data['endereco_destino_pedido']['bairro'];?></b><br>Cidade:
                                            													<b><?=$data['endereco_destino_pedido']['cidade'];?></b><br>Número:
                                            													<b><?=$data['endereco_destino_pedido']['numero'];?></b><br>Complemento:
                                            													<b><?=$data['endereco_destino_pedido']['complemento']; ?></b><br>
                                            													CEP: <b><?=$data['endereco_destino_pedido']['cep'];?></b><br>
                                            													UF: <b><?=$data['endereco_destino_pedido']['uf'];?></b>
                                            											</div>
        																				<label
        																					for="fullname">	Destinatário Recebedor:</label>
        																				<select name="id_destinatario"
        																					class="form-control">
        																					<?php foreach ($data['pessoa'] as $n) {?>
        																					<option value="<?=$n['id'];?>"><?=$n['nome'];?></option>
        																					<?php } ?>
        																				</select>
        																				<br>
        																				<button type="submit" class="btn btn-success">GERAR</button>
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
												</div>
												<button class="btn btn-success pull-right" data-toggle='modal' data-target='#add-codigo'><i class="fa fa-ellipsis-v"></i> Atualizar Pedido</button>
												<!-- DADOS FRETE-->
												<div class="modal fade" id="add-codigo" tabindex="-1"
													role="dialog" aria-labelledby="myModalLabel"
													aria-hidden="true">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<img src="public/img/loading2.gif" alt="<?=NOME_LOJA;?>" id="load-img-modal" style="position: fixed; z-index: 999; overflow: show; margin: auto; top: 0; left: 0; bottom: 0; right: 0; background-color: none; display: none; width: 50px; height: 50px;">
															<div class="">
															    <div class="row">
                                                                  <div class="col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="x_panel">
                                                                      <div class="x_title">
                                                                        <h2>Informações do Pedido <small></small></h2>
                                                                        <div class="clearfix"></div>
                                                                      </div>
                                                                      <div class="x_content">
            															<div id='modal-global' class='modal fade bd-example-modal-sm' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel' aria-hidden='true'>
                                                                         <div class='modal-dialog modal-sm'>
                                                                            <div class='modal-conten'>
                                                                                <div style='width: 400px; 
                                                                                            height:60px; 
                                                                                            border-radius:5px; 
                                                                                            text-align: center; 
                                                                                            background-color: #696969;'>
                                                                                    <h5 style='color: #FFF; margin-top: 100px;'><br>            																
                                                                                    <span id="text-pedido-atualizar" style="margin-left: 10px; font-weight: bold;"></span>
                                                                                    </h5>
                                                                            	</div>
                                                                          	</div>
                                                                          </div>
                                                                        </div>
                                                                        <form id="form-atualizar-pedido" data-parsley-validate class="form-horizontal form-label-left">
                                                                          <input type="hidden" name="id_cliente" value="<?=$data['cliente'][0]['id'];?>">
                                                                          <input type="hidden" name="id_rastreio" value="<?=$data['rastreio'][0]['id'];?>">
																		  <input type="hidden" value="<?=$data['pedido']['id'];?>" name="id_pedido" />
																		  <div class="card" style="border: 1px solid #ccc !important;  border-radius: 5px; padding-left: 10px; background: #FFF; margin-bottom: 20px;">
                                                                              <div class="card-body">
                                                                                    <h5 class="card-title" style="magin-left: 10px; font-weight: bold !important;"> Cliente</h5>
                                                                              </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo Cliente </label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                            	<select name="tipo_cliente"
																					class="form-control col-md-7 col-xs-12">
																					<?php 
																					$tipoCliente = dao('Core', 'TipoCliente')->getField('tipo', $data['cliente'][0]['id_tipo_cliente']);
																					?>
																					<option value="<?=$data['cliente'][0]['id_tipo_cliente'];?>"><?=$tipoCliente;?></option>
																					<?php foreach ($data['tipos_clientes'] as $n) {?>
																					<?php if($n['tipo'] != $tipoCliente){ ?>
																					<option value="<?=$n['id'];?>"><?=$n['tipo'];?></option>
																					<?php } ?>
																					<?php } ?>
																				</select>
                                                                            </div>
                                                                          </div>
																		  <div class="card" style="border: 1px solid #ccc !important;  border-radius: 5px; padding-left: 10px; background: #FFF; margin-bottom: 20px;">
                                                                              <div class="card-body">
                                                                                    <h5 class="card-title" style="magin-left: 10px; font-weight: bold !important;"> Pedido</h5>
                                                                              </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Código de Rastreio <span class="required"></span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                           		<input type="text"
																					name="codigo"
																					value="<?=$data['rastreio'][0]['codigo'];?>"
																					class="form-control col-md-7 col-xs-12"
																					placeholder="Adicionar código de Rastreiamento">
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Situação Envio </label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                            	<select name="status_fornecedor"
																					class="form-control col-md-7 col-xs-12">
																					<option value=""></option>
																					<?php foreach ($data['pedido_status_fornecedor'] as $n) {?>
																					<option value="<?=$n['id'];?>"><?=$n['status'];?></option>
																					<?php } ?>
																				</select>
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                          	  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Enviar Código Rastreio </label>
                                                                              <div class="col-md-6 col-sm-6 col-xs-12">
            																	<label> <input type="checkbox" class="js-switch"
            																		name="enviar_email_codigo" />
            																	</label>
                    														  </div>
                														  </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Total Cobrado </label>
                                                                            <?php 
                                                                            $frete = $data['pedido']['frete'];
                                                                            if($data['pedido']['frete_gratis']){
                                                                                $valorCobrado = $data['pedido']['valor'];
                                                                            }else{
                                                                                $valorCobrado = $data['pedido']['valor'] + $frete;
                                                                            }
                                                                            ?>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                           		<input type="text"
																					name="valor"
																					value="<?=$valorCobrado;?>"
																					class="form-control col-md-7 col-xs-12">
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Lucro </label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                           		<input type="text"
																					name="lucro"
																					value="<?=$data['pedido']['lucro'];?>"
																					class="form-control col-md-7 col-xs-12">
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Frete</label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                           		<input type="text" <?=($data['pedido']['frete_gratis'] != 0) ? '' : '';?> 
																					name="frete"
																					value="<?=$data['pedido']['frete'];?>"
																					class="form-control col-md-7 col-xs-12">
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Taxas </label>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                           		<input type="text" disabled="disabled"
																					name="codigo"
																					value="<?=$data['pedido']['valor_total_taxa'];?>"
																					class="form-control col-md-7 col-xs-12">
                                                                            </div>
                                                                          </div>
                                                                          <div class="form-group">
                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="card" style="border: 1px solid #ccc !important;  border-radius: 5px; padding-left: 10px; background: #FFF; margin-bottom: 20px;">
                                                                              <div class="card-body">
                                                                                    <h5 class="card-title" style="magin-left: 10px; font-weight: bold !important;"> Itens</h5>
                                                                              </div>
                                                                            </div>                                                                            
                                                                            <table class="table">
                                                                              <thead>
                                                                                <tr>
                                                                                  <th scope="col">Produto</th>
                                                                                  <th scope="col">Custo</th>
                                                                                  <th scope="col">Lucro</th>
                                                                                  <th scope="col">Venda</th>
                                                                                </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                              	<?php foreach ($data['itens'] as $item){ ?>
                                                                                <tr>
                                                                                  <th scope="row"><?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?></th>
                                                                                  <td><input style="border: 1px solid #ccc;" type="text" value="<?=$item['custo'];?>" id="item_custo_<?=$item['id'];?>" name="item_custo_<?=$item['id'];?>"></td>
                                                                                  <td><input style="border: 1px solid #ccc;" type="text" value="<?=$item['lucro'];?>" id="item_lucro_<?=$item['id'];?>" name="item_lucro_<?=$item['id'];?>"></td>
                                                                                  <td><input style="border: 1px solid #ccc;" type="text" value="<?=$item['preco'];?>" id="item_preco_<?=$item['id'];?>" name="item_preco_<?=$item['id'];?>" disabled="disabled"></td>
                                                                                </tr>
                                                                                <script>
                                                                                $(document).ready(function () {
                                                                        			$("input[name='item_custo_<?=$item['id'];?>']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                                                                            		$("input[name='item_preco_<?=$item['id'];?>']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
                                                                            		$("input[name='item_lucro_<?=$item['id'];?>']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

                                                                            		$('#item_custo_<?=$item['id'];?>').keyup(function() {
                                            											var valLucroReais = parseFloat($('#item_lucro_<?=$item['id'];?>').val());
                                            											var valCustoReais = parseFloat($('#item_custo_<?=$item['id'];?>').val());
                                            											var valPrecoReais = parseFloat($('#item_preco_<?=$item['id'];?>').val());
                                            											
                                            											var valLucro = valPrecoReais - valCustoReais;
                                            											$('#item_lucro_<?=$item['id'];?>').val( Math.round(valLucro));
                                            										});
                                                                                });
                                                                                </script>
                                                                                <?php } ?>
                                                                              </tbody>
                                                                            </table>                                                                            
                                                                            </div>
                                                                          </div>
                                                                          <div class="ln_solid"></div>
                                                                          <div class="form-group">
                                                                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                                                              <button style="float: right;" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                                              <button style="float: right;" type="button" class="btn btn-success" id="btn-salvar-pedido">Salvar</button>
                                                                            </div>
                                                                          </div>
                                                                        </form>
                                                                        <script>
                            											$(document).ready(function () {
                            												$('#btn-salvar-pedido').click(function(e) {
                            													$('#load-img-modal').css('display', 'inline-block');
                            													$('body').css("opacity", "0.5");
                            													                                                        	    	
            																	setTimeout(function(){ 
            																		$.ajax({
            				                                            				type : 'POST',
            				                                            				dataType : "text",
            	                                                        				async : false,
            	                                                        				url : "?m=sistema&c=venda&a=atualizarPedido",
            	                                                        				data : {
            	                                                        					"data" : JSON.stringify($('#form-atualizar-pedido').serializeArray()),
            	                                                        				},					  
            	                                                        				success: function(data){
            	                                                            				json = JSON.parse(data);
            	                                                                            $('#msg').css("display", "table");
            	                                                                            document.getElementById('text-pedido-atualizar').innerHTML = json.mensagem;
            	                                                            				
            	                        													$('#load-img-modal').css('display', 'none');
            	                        													$('body').css("opacity", "1");
//             	                        													$('#add-codigo').modal('hide');
            	                        													$('#modal-global').modal('show');
            	                        													setInterval(function() {
            	                        														$('#modal-global').modal('hide');
            	                        														if (setRefresh == true) {
            	                        															location.reload();
            	                        														}
            	                        													}, 1800);
            	                                                        				},
            	                                                        			});
            				                                        	 		}, 100);
                                                                            });
                            											 });
                                                                        </script>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Anexar Documentos</h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown"><a href="#" class="dropdown-toggle"
											data-toggle="dropdown" role="button" aria-expanded="false"><i
												class="fa fa-wrench"></i></a>
										<li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<section class="content invoice">
										<div class="row">
											<div class="col-md-6 col-xs-6">
												<p>Arraste o(s) arquivo(s) para o quadro abaixo.</p>
												<form action="?m=sistema&c=venda&a=importarDocumento"
													class="dropzone">
													<input type="hidden" name="id_pedido"
														value="<?=$data['pedido']['id'];?>" />
												</form>
											</div>
											<div class="col-md-6 col-xs-6">
												<div class="col-sm-9">
													<div class="inbox-body">
														<div class="attachment">
															<ul>
															<?php
                                                            $storeFolder = Configuration\Configuration::PATH_PEDIDO . $data['pedido']['id'] . '/';
                                                            $files = scandir($storeFolder); // 1
                                                            $imgs = array();
                                                            $ds = DIRECTORY_SEPARATOR;
                                                            if (is_dir($storeFolder)) {
                                                                if (false !== $files) {
                                                                    foreach ($files as $file) {
                                                                        if ('.' != $file && '..' != $file) { // 2
                                                                            $obj['name'] = $file;
                                                                            $obj['size'] = filesize($storeFolder . $ds . $file);
                                                                            $imgs[] = $obj;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            if(count($imgs) > 0){
                                                                $video = ['mp4'];
                                                                $imagem = ['jpg', 'png', 'jpeg', 'gif'];
                                                                foreach ($imgs as $img) {
                                                                    $doc = explode('.', $img['name']);
                                                                    if(in_array($doc[1], $imagem)){ ?>
    																<li><a target="new" href="data/uploads/pedido<?=$data['pedido']['id'];?>/<?=$img['name'];?>" class="atch-thumb"> <img
    																		src="data/uploads/pedido<?=$data['pedido']['id'];?>/<?=$img['name'];?>" alt="img" />
    																</a>
    																	<div class="links">
    																		<a target="new" href="data/uploads/pedido<?=$data['pedido']['id'];?>/<?=$img['name'];?>"><i class="fa fa-search" aria-hidden="true"></i></a>
    																	</div></li>
																		<?php } ?>
																	<?php } 
                                                                foreach ($imgs as $img) {
                                                                    $doc = explode('.', $img['name']);
                                                                    if(in_array($doc[1], $video)){ ?>
                                                                    	<li>
																			<iframe class="elementor-video-iframe lazy-loaded"
                                    											allowfullscreen="false" data-lazy-type="iframe"
                                    											data-src="data/uploads/pedido<?=$data['pedido']['id'];?>/<?=$img['name'];?>"
                                    											src="data/uploads/pedido<?=$data['pedido']['id'];?>/<?=$img['name'];?>"><i class="fa fa-search" aria-hidden="true"></i></iframe>
                                										</li>
																		<?php } ?>
																	<?php } ?>
																<?php } ?>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Outros Pedidos</h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown"><a href="#" class="dropdown-toggle"
											data-toggle="dropdown" role="button" aria-expanded="false"><i
												class="fa fa-wrench"></i></a>
										<li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
								<?php 
								$outrosPedidosDesteCliente = dao('Core', 'Pedido')->select(['*'], [['codigo_transacao', '!=', NULL], ['id_cliente', '=', $data['pedido']['id_cliente']]]);
								?>
								  <div class="panel-body">
                                    <table
									class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="width: 100px;">Ações</th>
											<th>Nº Pedido</th>
											<th>Data</th>
											<th>Total</th>
											<th>Lucro</th>
											<th>Frete</th>
											<th>Tipo</th>
											<th>Pagamento</th>
											<th>Boleto</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($outrosPedidosDesteCliente as $pedido) {
									    $_cliente = explode(' ', dao('Core', 'Cliente')->getField('nome', $pedido['id_cliente']));
									    $_telefone = dao('Core', 'Cliente')->getField('telefone', $pedido['id_cliente']);
									    $_codigo_rastreio = dao('Core', 'Rastreiamento')->getField('codigo', $pedido['id']);
									    
									    $_hora = $pedido['hora'];
									    $tipo = $pedido['tipo_pagamento'];
									    if($pedido['tipo_pagamento'] == 'cartao'){
									        $tipo = 'Cartão de Crédito';
									    }
									    
									    // Nome do Produto
									    $itemPedido = dao('Core', 'ItemPedido')->select(['*'], ['id_pedido', '=', $pedido['id']]);
									    $nome_produto = '';
									    if(sizeof($itemPedido) != 0){
									        $nome_produto = dao('Core', 'Produto')->getField('descricao', $itemPedido[0]['id_produto']);
									    }
									    
									    $statusClearSale = ($pedido['status_clear_sale']) ? ' - '.$pedido['status_clear_sale'].'' : '';
									    $pagamento_situacao = dao('Core', 'SituacaoPedido')->getField('situacao', $pedido['id_situacao_pedido']);
									    if($pagamento_situacao == 'Pendente'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #EEDD82; border-color: #EEDD82; ">'.$pagamento_situacao.'</button>';
									    }else if($pagamento_situacao == 'Pago'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #3CB371; border-color: #3CB371;">'.$pagamento_situacao.'</button>';
									    }else if($pagamento_situacao == 'Cancelado'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #FF6347; border-color: #FF6347;">'.$pagamento_situacao.'</button>';
									    }else if($pagamento_situacao == 'Chargeback'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #FF6347; border-color: #FF6347;">'.$pagamento_situacao.'</button>';
									    }else if($pagamento_situacao == 'Reembolsado'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #FF6347; border-color: #FF6347;">'.$pagamento_situacao.'</button>';
									    }else if($pagamento_situacao == 'Em Análise'){
									        $pagamento_situacao = '<button type="button" class="btn btn-success btn-xs" style="background: #CD853F; border-color: #CD853F;">'.$pagamento_situacao.'</button>';
									    }
									    
									    $status_fornecedor = dao('Core', 'PedidoStatusFornecedor')->getField('status', $pedido['id_pedido_status_fornecedor']);
									    if($status_fornecedor == 'Pendente'){
									        $status_fornecedor = '<button type="button" class="btn btn-success btn-xs" style="background: #CD853F; border-color: #CD853F;">'.$status_fornecedor.'</button>';
									    }else if($status_fornecedor == 'Realizado'){
									        $status_fornecedor = '<button type="button" class="btn btn-success btn-xs" style="background: #3CB371; border-color: #3CB371;">'.$status_fornecedor.'</button>';
									    }else if($status_fornecedor == 'Entregue ao destinatário'){
									        $status_fornecedor = '<button type="button" class="btn btn-success btn-xs" style="background: #3CB371; border-color: #3CB371;">'.$status_fornecedor.'</button>';
									    }else if($status_fornecedor == 'Devolvido ao remetente'){
									        $status_fornecedor = '<button type="button" class="btn btn-success btn-xs" style="background: #E9967A; border-color: #E9967A;">'.$status_fornecedor.'</button>';
									    }else if($status_fornecedor == 'Entregue com defeito'){
									        $status_fornecedor = '<button type="button" class="btn btn-success btn-xs" style="background: #E9967A; border-color: #E9967A;">'.$status_fornecedor.'</button>';
									    }
									    
									    $contato_whatsapp_email = $pedido['contato_watsapp_email_feito'];
									    if($contato_whatsapp_email == '0' || $contato_whatsapp_email == 0){
									        $contato_whatsapp_email = '<div style="background: #FF6347; border-radius: 8px; text-align: center; padding: 2px; color: #FFF;">Não</div>';
									    }else if($contato_whatsapp_email == '1' || $contato_whatsapp_email == 1){
									        $contato_whatsapp_email = '<div style="background: #3CB371; border-radius: 8px; text-align: center; padding: 2px; color: #FFF;">Sim</div>';
									    }
									    ?>
										<tr>
											<td>
												<button type="button"
													class="btn btn-default btn-xs dropdown-toggle"
													data-method="getData" data-option="" data-target="#putData">
													<a href="?m=sistema&c=venda&a=form&id=<?=$pedido['id'];?>"><i
														class="glyphicon glyphicon-edit"></i></a>
												</button>
											</td>
											<td><?=$pedido['numero_pedido'];?><p><b>#<?=$pedido['codigo_transacao'];?></b></p></td>
											<td style="display: none;"><?=dao('Core', 'Cliente')->getField('nome', $pedido['id_cliente']);?><span style="color: #3CB371; display: none;"><?=$nome_produto;?></span></td>
											<td><?=DateUtil::getDateDMY($pedido['data']);?><?=($_hora != '') ? ' às <b>'.$_hora.'</b>' : '';?><?=($pedido['response_code_gateway'] != NULL) ? '<br>'. getDescricaoTransacao($pedido['response_code_gateway'], $statusClearSale) : ''; ?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney(($pedido['valor'] + $pedido['frete']));?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney($pedido['lucro']);?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney($pedido['frete']);?></td>
											<td><?=ucfirst($tipo);?></td>
											<td><?=$pagamento_situacao;?></td>
											<td><b><a target="new" href="https://api.whatsapp.com/send?phone=55<?=$_telefone;?>&text=Ol%C3%A1%20<?=$_cliente[0];?>,%20sou%20a%20Jéssica%20da%20loja%20<?=NOME_LOJA?>,%20Tudo%20bem%20?%20%0A%0ARecebemos%20seu%20pedido%20de%20uma%20<?=$nome_produto;?>%20e%20ficamos%20no%20aguardo%20da%20confirmação%20do%20pagamento%20do%20boleto%20para%20o%20envio%20do%20mesmo%20ok?%20Dúvidas,%20estou%20à%20disposição.%20Jéssica%20Souza.&source=&data="><button class='btn btn-primary'
													style='color: red; cursor: pointer; border: 0px; font-size: 0.9rem; background: #3CB371;'>
													<img src="public/img/icons/send.png">
												</button></a></b>
                                            </td>
                                            <td style="display: none;"><?=$contato_whatsapp_email;?></td>
											<td style="display: none;">
												<button class='btn btn-primary' data-toggle='modal'
													style='color: red; cursor: pointer; border: 0px; font-size: 0.9rem; background: #FF6347;'
													data-target='#deletar-pedido-<?=$pedido['id'];?>'>
													<img src="public/img/icons/delete.png" style="width: 70%;">
												</button>
												<div class="modal fade"
													id="deletar-pedido-<?=$pedido['id'];?>" tabindex="-1"
													role="dialog" aria-labelledby="myModalLabel"
													aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<span style="font-size: 17px;">Tem certeza que deseja
																	excluir o pedido <b><?=$pedido['id'];?></b>?
																</span>
															</div>
															<div id="msg"></div>
															<div class="modal-body">
																<ul>
																	<li><p style="font-size: 14px;">Ao excluir este pedido,
																			todos os registros atrelado a este pedido serão
																			deletados</p></li>
																</ul>
																<form role="form"
																	id="form-pedido-delete-<?=$pedido['id'];?>" novalidate>
																	<input value="<?=$pedido['id'];?>" type="hidden"
																		name="id_pedido" />
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default"
																			data-dismiss="modal">Não</button>
																		<button type="button" class="btn btn-primary"
																			id="deletar-pedido-b-<?=$pedido['id'];?>">Sim</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</div> 
												<script src="public/js/vcsempbela.js"></script> 
												<script>
                                                    $('#deletar-pedido-b-<?=$pedido['id'];?>').click(function(e) {
                                                    	e.preventDefault();
                                                    	$('#deletar-pedido-<?=$pedido['id'];?>').modal('hide');
                                                    	post('#form-pedido-delete-<?=$pedido['id'];?>', {module: "sistema", controller: "venda", action: "deletarPedido"}, true);
                                                    });
                                                </script>
											</td>
										</tr>
										<?php } ?>
										</tbody>
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
	<script src="public/admin/build/js/custom.min.js"></script>
	<script src="public/admin/vendors/dropzone/dist/min/dropzone.min.js"></script>
	<script src="public/admin/vendors/switchery/dist/switchery.min.js"></script>
	<script>
		$("input[name='preco']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: true});
		$("input[name='lucro']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: true});
		$("input[name='valor']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: true});
		$("input[name='frete']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: true});
    </script>		
	<script type="text/javascript">
	$(document).ready(function() {
		$(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
		    $(".alert-success").slideUp(500);
		});
	});
	</script>
</body>
</html>
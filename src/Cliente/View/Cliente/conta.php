<?php
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\DateUtil;
?>
<!DOCTYPE html>
<html lang='pt-BR' xml:lang='pt-BR'>
<head>
<title>Minha Conta | <?=NOME_LOJA;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="/"><meta name="description" content="<?=TAG_DESCRIPTION;?>" />
<meta name="robots" content="index, follow" />
<meta name="rating" content="general" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no, minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="HandheldFriendly" content="True" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="<?=NOME_LOJA;?>">
<meta name="mobile-web-app-capable" content="yes">

<meta property="og:type" content="product.group">
<meta property="og:description" content="<?=TAG_DESCRIPTION;?>"> 

<meta property="og:locale" content="pt_BR">
<meta property="og:title" content="">
<meta property="og:site_name" content="<?=NOME_LOJA;?>">
<?php require_once 'src/Site/View/Site/css.php';?>
</head>
<body>
	<?php require_once 'src/Site/View/Site/menu.php';?>
	<div class="privacy">
		<div class="container">
			<div class="checkout-right">
				<div id="parentHorizontalTab">
					<?php if(isset($data['error']) && $data['error'] == false){ ?>
					<div class="alert alert-success" role="alert"><?=$data['msg'];?></div>
					<?php } ?>
					<?php if(isset($data['error']) && $data['error'] == true){ ?>
					<div class="alert alert-warning" role="alert"><?=$data['msg'];?></div>
					<?php }?>
					<br>
					<ul class="resp-tabs-list hor_1">
						<li>Minha Conta</li>
						<li>Compras</li>
						<li>Endereços</li>
					</ul>
					<div class="resp-tabs-container hor_1">
						<div>
							<div class="vertical_post check_box_agile">
								<div class="pagina-conta  tema-pequeno ">
									<div class="abas-conteudo borda-alpha">
										<div class="caixa-dados">
											<div class="row-fluid">
												<div class="span6">
													<fieldset>
														<legend class="cor-secundaria"> Dados Cadastrais </legend>
														<ul class="caixa-info">
															<li><span> <b class="cor-secundaria">Nome: </b><?=$data['cliente']['nome'];?> - <b class="cor-secundaria">CPF: </b><?=$data['cliente']['cpf'];?></span></li>
															<li style="display: none;"><span> <b class="cor-secundaria">Sexo: </b><?=ValidateUtil::getSex($data['cliente']['sexo']);?></span></li>
															<li><span> <b class="cor-secundaria">Data Nascimento: </b><?=DateUtil::getDateDMY($data['cliente']['data_nascimento']);?></span></li>
															<li><span> <b class="cor-secundaria">E-mail: </b><?=$data['cliente']['email'];?></span></li>
															<li><span> <b class="cor-secundaria">Telefone: </b><?=$data['cliente']['telefone'];?></span></li>
														</ul>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div>
    						<div class="row">
    							<div class="col-md-8">
    								<?php  if(sizeof($data['pedido']) == 0){ ?>
    								<br>
    								<h4>Ops! Você ainda não comprou nada! :D</h4>
    								<?php } ?>
        							<div class="creditly-wrapper wthree, w3_agileits_wrapper">
        								<br>
        								<?php foreach ($data['pedido'] as $pedido) { ?>
        								<?php
                                        $enderecoEntrega = dao('Core', 'Endereco')->select([
                                            '*'
                                        ], [
                                            'id',
                                            '=',
                                            $pedido['id_endereco']
                                        ]);
                                        
                                        $codigo = dao('Core', 'Rastreiamento')->select([
                                            '*'
                                        ], [
                                            [
                                            'postado',
                                            '=',
                                            TRUE
                                            ],
                                            [
                                            'id_pedido',
                                            '=',
                                            $pedido['id']
                                            ]
                                        ]);
                                        
                                        
                                        $itemPedido = dao('Core', 'ItemPedido')->select([
                                            '*'
                                        ], [
                                            'id_pedido',
                                            '=',
                                            $pedido['id']
                                        ]);
                                        
                                        ?>
        								<div class="abas-conteudo borda-alpha">
        									<button class="accordion">
        										Pedido: <span class="cor-principal"><b><?=$pedido['numero_pedido'];?></b></span>
        										<div style="margin-left: 30px; display: inline;">
        											Valor Total: <span class="cor-principal"><b>R$ <?=ValidateUtil::setFormatMoney($itemPedido[0]['preco']);?></b></span>
        										</div>
        										<?php if($codigo[0]['codigo'] != NULL && $codigo[0]['codigo'] != ''){ ?>
            										<div style="margin-left: 30px; display: inline;">
            											Código Rastreiamento: <span class="cor-principal"><b><?=$codigo[0]['codigo'];?></b></span>
            										</div>
        										<?php } ?>
        									</button>
        									<div class="panel">
        										<div class="caixa-dados">
        											<div class="row-fluid">
        												<div class="span6">
        													<fieldset>
        														<legend class="cor-secundaria"> Dados do pedido </legend>
        														<ul class="caixa-info">
        															<li><b class="cor-secundaria">Situação:</b><span
        																class="situacao-<?=seo(dao('Core', 'SituacaoPedido')->getField('situacao', $pedido['id_situacao_pedido']));?>"> <?=dao('Core', 'SituacaoPedido')->getField('situacao', $pedido['id_situacao_pedido']);?></span></li>
        															<li><b class="cor-secundaria">Data do Pedido:</b><span
        																class="cor-principal"> <?=DateUtil::dateLiteral($pedido['data']);?></span></li>
        															<li><b class="cor-secundaria">Valor:</b><span
        																class="cor-principal"> R$ <?=ValidateUtil::setFormatMoney($itemPedido[0]['preco']);?></span></li>
        														</ul>
        													</fieldset>
        												</div>
        												<div class="span6">
        													<fieldset>
        														<legend class="cor-secundaria"> Endereço </legend>
        														<ul class="caixa-info">
        															<li><b class="cor-secundaria">Destinatário:</b><span
        																class="cor-principal"> <?=$enderecoEntrega[0]['destinatario'];?></span></li>
																	<li><b class="cor-secundaria">Cidade:</b> <span
        																class="cor-principal"> <?=$enderecoEntrega[0]['cidade'];?>/<?=$enderecoEntrega[0]['uf'];?> </span></li>
        															<li><b class="cor-secundaria">Endereço:</b> <span
        																class="cor-principal"> <?=$enderecoEntrega[0]['endereco'];?> <?=$enderecoEntrega[0]['complemento'];?></span></li>
        															<li><b class="cor-secundaria">CEP:</b><span
        																class="cor-principal"> <?=$enderecoEntrega[0]['cep'];?></span></li>
        														</ul>
        													</fieldset>
        												</div>
        											</div>
        										</div>
        										<div class="caixa-dados">
        											<div>
        												<legend class="cor-secundaria"> Itens </legend>
        												<div class="caixa-sombreada">
        												<?php
        												    $i = 1;
                                                            foreach (dao('Core', 'ItemPedido')->select([
                                                                '*'
                                                            ], [
                                                                'id_pedido',
                                                                '=',
                                                                $pedido['id']
                                                            ]) as $item) {
                                                                $imgs = getImagensProduto($item['id_produto']);
                                                                ?>
                                                           <hr> 
                                                            <br>    
        													<div class="acc-delivery-header">
        														<span class="entrega">Entrega <?=$i++;?> - </span> <span class="status-pedido"><?=dao('Core', 'SituacaoItemPedido')->getField('situacao', $item['id_situacao_item_pedido']);?></span>
        													</div>
        													<br>
        													<ul class="acc-delivery-list">
        														<li class="acc-order-item-cont acc-order-item-cont-0"><div
        																class="acc-order-product">
        																<figure>
        																	<img
        																		src="data/products/<?=$item['id_produto'];?>/principal.jpg"
        																		alt="<?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?>" class="img-responsive"
        																		style="width: 75px;">
        																</figure>
        																<div class="acc-order-product-truncate">
        																	<span class="acc-order-product-info"
        																		alt="<?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?>"
        																		title="<?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?>">
        																		<?=dao('Core', 'Produto')->getField('descricao', $item['id_produto']);?></span>
        																	<span class="cor-pedido"><?=dao('Core', 'CorProduto')->getField('nome', $item['id_cor_produto']);?></span>
        																	<span class="tamanho-pedido"><?=dao('Core', 'TamanhoProduto')->getField('descricao', $item['id_tamanho_produto']);?></span>
        																	<p class="acc-order-product-info">
        																		<strong><?=$item['quantidade'];?> unidade - R$ <?=ValidateUtil::setFormatMoney($item['preco']);?></strong>
        																	</p>
        																</div>
        															</div>
        														</li>
        													</ul>
        													<?php }?>
        													<br>
        												</div>
        											</div>
        										</div>
        									</div>
        								</div>
        								<?php }?>
        							</div>
    							</div>
    							<?php if(sizeof($data['queridinhos']) != 0){ ?>
    							<div class="col-md-4">
    								<br>
    								<h4>Acho que você vai gostar ;D</h4>
									<div id="summary-container">
                                		<div class="cart_prowrap">
                                			<table class="table" id="cart-itens">
                                				<tbody>
                            					<?php
                            					    foreach ($data['queridinhos'] as $key => $value) { ?>
                                    					<tr>
                                    						<td class="invert-image" style="border-bottom: 1px solid #e5e5e5;">
                                    							<a href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>">
                                    								<img src="data/products/<?=$value['codigo'];?>/principal.jpg" alt="<?=$value['descricao'];?>" class="img-responsive">
                                    							</a>
                                    						</td>
                                    						<td style="border-bottom: 1px solid #e5e5e5;">
                                								<a href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>"><h5  id="td-cart"><span class="descricao-queridinho"><?=$value['descricao'];?></span></h5></a><br>
                                								<a href="produto/<?=$value['codigo'];?>/<?=$value['cod_url_produto'];?>"><span class="a-partir-de">a partir de:</span>
                                								<h5  id="td-cart"><span class="valor-queridinho">R$ <?=ValidateUtil::setFormatMoney($value['valor']);?></span></h5></a>
                                    						</td>
                                    					</tr>
                                					<?php }?>
                                				</tbody>
                                			</table>
                                		</div>
                                	</div>
    							</div>
    							<?php } ?>
    						</div>
						</div>
						<div>
							<div class="vertical_post check_box_agile">
								<div class="pagina-conta  tema-pequeno ">
									<div class="abas-conteudo borda-alpha">
										<div class="caixa-dados">
											<legend class="cor-secundaria"> Endereços </legend>
											<div class="alert alert-warning" id="msg-endereco" role="alert" style="display: none; width: 100%;">
                                				<span id="text-endereco"></span>
                                			</div>
											<?php foreach ($data['endereco'] as $end) {  ?>
											<div class="dv-endereco">
												<div class="row-fluid">
													<div class="span6">
														<fieldset>
															<ul class="caixa-info">
																<li><br> <br></li>
																<li><span> Destinatário: <b><?=$end['destinatario'];?></b></span></li>
																<li><span> Endereço: <?=$end['endereco'];?>, <?=$end['numero'];?> - <?=$end['bairro'];?></span></li>
																<?php if($end['complemento'] != ''){ ?><li><span> Complemento: <?=$end['complemento'];?></span></li><?php } ?>
																<li><span> CEP: <?=$end['cep'];?> - <?=$end['cidade'];?></span></li>
																<li><span> Cidade: <?=$end['cidade'];?>/<?=$end['uf'];?></span></li>
															</ul>
														</fieldset>
													</div>
												</div>
												<div id="acoes">
													<button class="trash-end" data-toggle='modal' data-target='#deletar-endereco-<?=$end['id'];?>'>
														<img src="public/img/icon-trash.png" alt="<?=NOME_LOJA;?>"
															style="width: 18px; height: 18px;">
													</button>
													<!-- MODAL TRASH -->
													<div class="modal fade" id="deletar-endereco-<?=$end['id'];?>"
														tabindex="-1" role="dialog"
														aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<span style="font-size: 16px; color: #DAA520; font-weight: 600;">excluir endereço</span>
																</div>
																<div id="msg"></div>
																<div class="">
																	<ul class="caixa-info">
																			<li><span>Você tem certeza que deseja excluir o endereço <b><?=$end['destinatario'];?></b>?</span> </li>
            																<li><span> Endereço: <?=$end['endereco'];?>, <?=$end['numero'];?> - <?=$end['bairro'];?></span></li>
            																<li><span> CEP: <?=$end['cep'];?> - <?=$end['cidade'];?></span></li>
            																<li><span> Cidade: <?=$end['cidade'];?>/<?=$end['uf'];?></span></li>																		
																	</ul>
																	<form role="form" id="form-deletar-endereco-<?=$end['id'];?>" novalidate>
																		<input value="<?=$end['id'];?>" type="hidden" name="id_endereco" />
																		<div class="modal-footer">
																			<button type="button" class="btn-excluir-endereco"
																				data-dismiss="modal">Não, cancelar</button>
																			<button type="button" class="btn-excluir-endereco" id="deletar-endereco-<?=$end['id'];?>">Sim, excluir</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div>													
													<!-- END MODAL TRASH -->
													<button class="edit-end">
														<img src="public/img/icon-edit.png" alt="<?=NOME_LOJA;?>" data-toggle='modal' data-target='#editar-endereco-<?=$end['id'];?>'
															style="width: 18px; height: 18px;">
													</button>
													<!-- MODAL EDIT -->
													<div class="modal fade" id="editar-endereco-<?=$end['id'];?>"
														tabindex="-1" role="dialog"
														aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<span style="font-size: 16px; color: #DAA520; font-weight: 600;">Editar Endereço</span>
																</div>
																<div id="msg"></div>
																<div class="">
																	<br>
																	<form role="form" id="form-editar-endereco-<?=$end['id'];?>" method="post" novalidate>
																		<input value="<?=$end['id'];?>" type="hidden" name="id_endereco" />
																		<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="endereco" value="<?=$end['endereco'];?>"
                                                								name="endereco" aria-describedby="emailHelp"
                                                								placeholder="Endereço" required="">
                                                						</div>
                        												<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="numero"
                                                								name="numero" aria-describedby="emailHelp" placeholder="Número" value="<?=$end['numero'];?>"
                                                								required="">
                                                						</div>
                                                						<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="complemento"
                                                								name="complemento" aria-describedby="emailHelp" placeholder="Complemento" value="<?=$end['complemento'];?>"
                                                								required="">
                                                						</div>
                                                						<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="cidade"
                                                								name="cidade" aria-describedby="emailHelp" placeholder="Cidade" value="<?=$end['cidade'];?>"
                                                								required="">
                                                						</div>
                                                						<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="bairro"
                                                								name="bairro" aria-describedby="emailHelp" placeholder="Bairro" value="<?=$end['bairro'];?>"
                                                								required="">
                                                						</div>
                                                						<div class="form-group pdd">
                                                							<select class="form-control input-end" name="estado">
                                            									<option value="<?=$end['uf'];?>"><?=$end['uf'];?></option>
                                                								<?php foreach (estadosBrasileiros() as $indice => $estado){?>
                                                									<option value="<?=$indice;?>"><?=$estado;?></option>
                                                								<?php } ?>
                                                							</select>
                                                						</div>
                                                						<div class="form-group pdd">
                                                							<input type="text" class="form-control input-end" id="cep2"
                                                								name="cep" aria-describedby="emailHelp" placeholder="Cep" value="<?=$end['cep'];?>"
                                                								required="">
                                                						</div>
																		<div class="modal-footer">
																			<button type="button" class="btn-editar-endereco"
																				data-dismiss="modal">Não, cancelar</button>
																			<button type="button" class="btn-editar-endereco" id="btn-editar-endereco-<?=$end['id'];?>">Sim, editar</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div>													
													<!-- END MODAL EDIT -->
												</div>
											</div>
											<script>
											$(document).ready(function () {
												$('.load-img').css('display', 'none');
												$('.load-img').hide();

												// EXCLUIR ENDEREÇO
												 $('#deletar-endereco-<?=$end['id'];?>').click(function(e) {
                                        	    	$('.load-img').show();
                                        	    	$('.load-img').css('display', 'inline-block');
                                        	 		$('body').css("opacity", "0.5");
                                        	 		
                                                	e.preventDefault();
                                                	$('#deletar-endereco-<?=$end['id'];?>').modal('hide');
                                        	 		setTimeout(function(){ 
                                        	 			$.ajax({
                                            				type : 'POST',
                                            			 	beforeSend: function(){},
                                            				dataType : "text",
                                            				async : false,
                                            				url : "?m=endereco&c=endereco&a=deletar",
                                            				data : {
                                             					"id_endereco" : <?=$end['id'];?>,
                                            				},					  
                                            				success: function(data){
                                            					$('.load-img').css("display", "none");
                                        						$('.load-img').hide();
                                                                $('body').css("opacity", "1");
                                                                $('#msg-endereco').css("display", "table");
                                                                document.getElementById('text-endereco').innerHTML = "Endereço excluído com sucesso";
                                            				},
                                            			});
                                        	 		}, 100);                                                	
                                                });

												 $('#btn-editar-endereco-<?=$end['id'];?>').click(function(e) {
                                        	    	$('.load-img').show();
                                        	    	$('.load-img').css('display', 'inline-block');
                                        	 		$('body').css("opacity", "0.5");
                                        	 		
                                                	e.preventDefault();
                                                	$('#editar-endereco-<?=$end['id'];?>').modal('hide');
                                        	 		setTimeout(function(){ 
                                        	 			$.ajax({
                                            				type : 'POST',
                                            			 	beforeSend: function(){},
                                            				dataType : "text",
                                            				async : false,
                                            				url : "?m=endereco&c=endereco&a=editar",
                                            				data : {
                                             					"data" : JSON.stringify($('#form-editar-endereco-<?=$end['id'];?>').serializeArray()),
                                            				},					  
                                            				success: function(data){
                                            					$('.load-img').css("display", "none");
                                        						$('.load-img').hide();
                                                                $('body').css("opacity", "1");
                                                                $('#msg-endereco').css("display", "table");
                                                                document.getElementById('text-endereco').innerHTML = "Endereço editado com sucesso";
                                            				},
                                            			});
                                        	 		}, 100);                                                	
                                                });
											 });
                                            </script>												
											<?php } ?>
											<div class="new-adress imageLink">
												<button class="btn" id="btn-end">
													<img class="img-add-end hide" alt="<?=NOME_LOJA;?>" src="public/img/plus-512.png">Novo
													endereço
												</button>
												<br>
												<br>
												<div class="modal fade" id="alerta-cad-end" tabindex="-1" role="dialog">
                                            		<div class="modal-dialog">
                                            			<div class="modal-content">
                                            				<div class="modal-header">
                                            					<button type="button" class="close" data-dismiss="modal"
                                            						aria-hidden="true">×</button>
                                            					<h4 class="modal-title">Alerta</h4>
                                            				</div>
                                            				<div class="modal-body">
                                            					<span id="alert-cad"></span>
                                            				</div>
                                            				<div class="modal-footer">
                                            					<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                                            				</div>
                                            			</div>
                                            		</div>
                                            	</div>
												<div id="form-end">
													<legend class="cor-secundaria"> Adicionar endereço de
														entrega </legend>
													<form method="post" action="?m=cliente&c=cliente&a=add">
														<label for="cep">* Campos obrigatórios</label><br> <br>
														<div class="form-group">
															<label for="destinatario">Nome do destinatário*</label> <input
																type="text" class="form-control input-end"
																id="destinatario" name="destinatario"
																aria-describedby="emailHelp"
																placeholder="Nome do destinatário" required="">
														</div>
														<div class="form-group">
															<label for="cep">Cep*</label> <input type="text"
																class="form-control input-end" id="cep" name="cep"
																aria-describedby="emailHelp" placeholder="Cep" size="10"
																required="">
														</div>
														<div class="form-group">
															<label for="cep">Endereço*</label> <input type="text"
																class="form-control input-end" id="endereco"
																name="endereco" aria-describedby="emailHelp"
																placeholder="Endereço" required="">
														</div>
														<div class="form-group">
															<label for="numero">Número*</label> <input type="text"
																class="form-control input-end" id="numero" name="numero"
																aria-describedby="emailHelp" placeholder="Número"
																required="">
														</div>
														<div class="form-group">
															<label for="bairro">Bairro*</label> <input type="text"
																class="form-control input-end" id="bairro" name="bairro"
																aria-describedby="emailHelp" placeholder="Bairro"
																required="">
														</div>
														<div class="form-group">
															<label for="cidade">Cidade*</label> <input type="text"
																class="form-control input-end" id="cidade" name="cidade"
																aria-describedby="emailHelp" placeholder="Cidade"
																required="">
														</div>
														<div class="form-group">
															<label for="bairro">Estado*</label> <select
																class="form-control input-end" name="estado">
																<?php foreach (estadosBrasileiros() as $indice => $estado){?>
																	<option value="<?=$indice;?>"><?=$estado;?></option>
																<?php } ?>
															</select>
														</div>
														<button type="submit" class="btn btn-danger btn-add-endereco" onclick="return validateFormCadEnd();">Adicionar
															endereço
														</button>
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
			</div>
		</div>
	</div>
	<?php require_once 'src/Site/View/Site/footer.php';?>
	<?php require_once 'src/Site/View/Site/js.php';?>	
	<script>
	$(document).ready(function(){
	 	$("#form-end").hide();
	    $("#btn-end").click(function(){
	        $("#form-end").toggle();
	    });
	});

	function validateFormCadEnd() {
		if (document.getElementById("cep").value.length < 10) {
	        event.preventDefault();
			$('#alerta-cad-end').modal("show");
			$('#alert-cad').html('<p>CEP Inválido!</p>');
	    }
    }
	
    var acc = document.getElementsByClassName("accordion");
    var i;
    
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight){
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        } 
      });
    }
    </script>
	<script>
		$(document).ready(function () {
			$('#parentHorizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion
				width: 'auto', //auto or any width like 600px
				fit: true, // 100% fit in a container
				tabidentify: 'hor_1', // The tab groups identifier
				activate: function (event) { // Callback function if tab is switched
					var $tab = $(this);
					var $info = $('#nested-tabInfo');
					var $name = $('span', $info);
					$name.text($tab.text());
					$info.show();
				}
			});
		});

		$(function () {
			var creditly = Creditly.initialize(
				'.creditly-wrapper .expiration-month-and-year',
				'.creditly-wrapper .credit-card-number',
				'.creditly-wrapper .security-code',
				'.creditly-wrapper .card-type');

			$(".creditly-card-form .submit").click(function (e) {
				e.preventDefault();
				var output = creditly.validate();
				if (output) {
					// Your validated credit card output
					console.log(output);
				}
			});
		});
	</script>
</body>
</html>
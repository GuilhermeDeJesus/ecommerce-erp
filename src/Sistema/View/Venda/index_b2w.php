<?php
use Krypitonite\Util\DateUtil;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Http\Request;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pedidos</title>
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
<link href="public/admin/vendors/select2/dist/css/select2.min.css"
	rel="stylesheet">	
<link
	href="public/admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css"
	rel="stylesheet">
<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
<link rel='stylesheet' href='public/css/paginacao.css' type='text/css' />
</head>
<div id="msg"></div>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3><?=$data['_title_origem'];?></h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
                          <div class="x_title">
                            <h2>Filtro</h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                          	<div class="row">
                                <form class="form-horizontal" method="get">
								  <input type="hidden" name="m" value="sistema" /> 
								  <input type="hidden" name="c" value="venda" /> 
								  <input type="hidden" name="a" value="_pedidos" />
								  <input type="hidden" name="b2w" value="<?=Request::get('b2w');?>" />
								  
                                  <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                  	<label for="fullname">Nº Pedido:</label>
                                  	<input type="text" name="numero_pedido" class="form-control">
            					  </div>
            					  
            					  <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                  	<label for="fullname">Nº Transação:</label>
                                  	<input type="text" name="codigo_transacao" class="form-control">
            					  </div>
            					  
            					  <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                  	<label for="fullname">Código Rastreio:</label>
                                  	<input type="text" name="codigo_rastreio" class="form-control">
            					  </div>            					  
            					  
            					  <div class="col-md-3 col-sm-12 col-xs-12 form-group">
 									<label for="email">Meio:</label>
                                  	<select class="form-control" name="tipo">
                                  		<option value="">--Todos--</option>
    									<option value="cartao">Cartão de Crédito</option>
    									<option value="boleto">Boleto</option>
    							  	</select>
            					  </div>
            					  
            					  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
 									<label for="email">Status:</label>
        							  <select name="situacao[]" class="form-control select2_multiple" multiple="multiple">
        								<option value="">--Todos--</option>
        								<?php foreach ($data['situacoes'] as $s) { ?>
        									<option value="<?=$s['id'];?>"><?=$s['situacao'];?></option>
        								<?php } ?>
        							  </select>
            					  </div>
            					  
            					  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
            					  	<label>Marca</label> 
            					  	<select name="marca[]"
    									class="form-control select2_multiple" multiple="multiple">
    									<option value="">--Todos--</option>
    									<?php foreach ($data['marcas'] as $s) { ?>
    										<option value="<?=$s['id'];?>"><?=$s['nome'];?></option>
    									<?php } ?>
    								</select>
								  </div>
            					  
            					  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
            					  	<label>Envio</label> 
            					  	<select name="status_fornecedor"
    									class="form-control">
    									<option value="">--Todos--</option>
    									<?php foreach ($data['pedido_status_fornecedor'] as $s) { ?>
    										<option value="<?=$s['id'];?>"><?=$s['status'];?></option>
    									<?php } ?>
    								</select>
								  </div>
								  
								  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
            					  	<label>Categoria</label> 
            					  	<select name="categoria[]"
    									class="form-control select2_multiple" multiple="multiple">
    									<option value="">--Todos--</option>
    									<?php foreach ($data['categorias'] as $s) { ?>
    										<option value="<?=$s['id'];?>"><?=$s['descricao'];?></option>
    									<?php } ?>
    								</select>
								  </div>
								  
								  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
            					  	<label>ClearSale</label> 
            					  	<select name="status_clear_sale[]"
    									class="form-control select2_multiple" multiple="multiple">
    									<option value="">--Todos--</option>
    									<option value="APA">Aprocação Automática</option>
    									<option value="APM">Aprovação Manual</option>
    									<option value="RPM">Reprovado Sem Suspeita</option>
    									<option value="AMA">Análise manual</option>
    									<option value="NVO">Novo</option>
    									<option value="SUS">Suspensão Manual</option>
    									<option value="CAN">Cancelado pelo Cliente</option>
    									<option value="FRD">Fraude Confirmada</option>
    									<option value="RPA">Reprovação Automática</option>
    									<option value="RPP">Reprovação Por Política</option>
    									<option value="APP">Aprovação Por Política</option>
    								</select>
								  </div>
								  
								  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                  	<label for="fullname">Cep:</label>
                                  	<input type="text" name="cep" class="form-control">
            					  </div>
                                 
    							  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
    							  	<label for="email">De:</label>
                                  	<input type="date" name="data_inicio" id="reservation" class="form-control" value="" />
                                  </div>
                                  
    							  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
    							  	<label for="email">Até:</label>
                                  	<input type="date" name="data_fim" id="reservation" class="form-control" value="" />
                                  </div>
                                  
                                  <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                  	<label for="fullname">CPF:</label>
                                  	<input type="text" name="cpf" class="form-control">
            					  </div>
                                  
                                  <div class="col-md-12">
									<div class="control-group">
										<div class="controls">
											<div class="input-prepend input-group">
												<br>
												<button type="submit" class="btn btn-success"
													style="margin-top: 5px;"><i class="fa fa-search"></i></button>
												<button type="submit" name="export_excel" value="export_excel" class="btn btn-success"
													style="margin-top: 5px;"><i class="fa fa-download"></i></button>
												<button type="button"   class="btn btn-success" data-toggle="modal" data-target="#sinc"
													style="margin-top: 5px;"><i class="fa fa-reply"></i></button>
												<button type="submit" name="get_emails_customers" value="get_emails_customers" class="btn btn-success"
													style="margin-top: 5px;"><i class="fa fa-group"></i></button>													
												<div class="modal fade" id="sinc" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title" id="exampleModalLabel"><b>Atenção</b></h4>
																<button type="button" class="close"
																	data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">Você confirma a sincronização de pedidos na Skyhub? <br> 
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary"
																	data-dismiss="modal">Cancelar</button>
																<a href="?m=sistema&c=skyHub&a=_getOrders"><button type="button" class="btn btn-primary">Sincronizar</button></a>
															</div>
														</div>
													</div>
												</div>
												<a href="?m=sistema&c=venda&a=conferir_pedidos"><button type="button" class="btn btn-success"
													style="margin-top: 5px;"> <i class="fa fa-external-link-square"></i> Atualizar Pedidos</button></a>
											</div>
										</div>
									</div>
								 </div>
                                </form>
        					</div>
                          </div>
                        </div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>
									Métricas <small></small>
								</h2>
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
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count"><?=($data['_count_pedidos_aprovados'] != NULL) ? $data['_count_pedidos_aprovados'] : 0 ;?></div>
										<h3><a class="" data-toggle="collapse" href="#total-pedidos" role="button" aria-expanded="false" aria-controls="total-pago">Aprovados</a></h3>
										<div class="row">
                                          <div class="col">
                                            <div class="collapse multi-collapse" id="total-pedidos">
                                              <div class="card card-body">
                                                <table class="table table-dark" style="margin-left: 15px; margin-right: 15px;">
                                                  <tbody>
                                                  	<?php 
                                                      	function Investimento($where, $idProduto = 0){
                                                      	    
                                                      	    if(sizeof($where) == 0){ 
                                                                    array_push($where, [
                                                              	         'data',
                                                              	         '=',
                                                              	         date('Y-m-d')
                                                      	            ]);
                                                      	    }
                                                      	    
                                                      	    array_push($where, [
                                                      	        'id_tipo_lancamento',
                                                      	        '=',
                                                      	        1
                                                      	    ]);
                                                      	    
                                                      	    if($idProduto != 0){
                                                      	        array_push($where, [
                                                      	            'id_produto',
                                                      	            '=',
                                                      	            $idProduto
                                                      	        ]);
                                                      	    }
                                                      	    
                                                      	    $lancamentos = dao('Core', 'Lancamento')->select(['*'], $where);
                                                      	    
                                                      	    $_total_inve = [];
                                                      	    foreach ($lancamentos as $lan) {
                                                  	              $_total_inve[] = $lan['valor'];
                                                      	    }
                                                      	    
                                                      	    return array_sum($_total_inve);
                                                      	}
                                                      	
                                                      	function DebitoFornecedor(){
                                                      	    
                                                      	    $where = [];
                                                      	    array_push($where, [
                                                      	        'id_tipo_lancamento',
                                                      	        '=',
                                                      	        4
                                                      	    ]);
                                                      	    
                                                      	    $lancamentos = dao('Core', 'Lancamento')->select(['*'], $where);
                                                      	    
                                                      	    $_total_inve = [];
                                                      	    foreach ($lancamentos as $lan) {
                                                      	        $_total_inve[] = $lan['valor'];
                                                      	    }
                                                      	    
                                                      	    return array_sum($_total_inve);
                                                      	}
                                                      	
                                                      	function taxaSaque($where){
                                                      	    
                                                      	    if(sizeof($where) == 0){ 
                                                                    array_push($where, [
                                                      	         'data',
                                                      	         '=',
                                                      	         date('Y-m-d')
                                                      	        ]);
                                                      	    }
                                                      	    
                                                      	    array_push($where, [
                                                      	        'id_tipo_lancamento',
                                                      	        '=',
                                                      	        3
                                                      	    ]);
                                                      	    
                                                      	    $lancamentos = dao('Core', 'Lancamento')->select(['*'], $where);
                                                      	    
                                                      	    $_total_inve = [];
                                                      	    foreach ($lancamentos as $lan) {
                                                      	        $_total_inve[] = $lan['valor'];
                                                      	    }
                                                      	    
                                                      	    return array_sum($_total_inve);
                                                      	}
                                                      	
                                                      	?>
                                                   		<tr>
                                                          <td>
                                                          		  <p style="color: #4267b2; font-weight: bold;">Facebook Ads: R$ <?=ValidateUtil::setFormatMoney(Investimento($data['where_lancamento']));?></p>
                                                          		  <p style="color: tomato; font-weight: bold;">Taxa Saque D+1: R$ <?=ValidateUtil::setFormatMoney(taxaSaque($data['where_lancamento']));?></p>
                                                        		  <p style="color: tomato; font-weight: bold;">Débito Fornecedor: R$ <?=ValidateUtil::setFormatMoney(DebitoFornecedor());?></p>
                                                          		  <p style="color: #6495ED; font-weight: bold;">Pag. Fornecedor: R$ <?=ValidateUtil::setFormatMoney($data['_total_pago_fornecedor']);?></p>
                                                          </td>
                                                        </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
									</div>
								</div>
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count">R$ <?=$data['_total_pedidos_pagos'];?></div>
										<h3><a class="" data-toggle="collapse" href="#total-pago" role="button" aria-expanded="false" aria-controls="total-pago">Faturamento</a></h3>
										<div class="row">
                                          <div class="col">
                                            <div class="collapse multi-collapse" id="total-pago">
                                              <div class="card card-body">
                                                <table class="table table-dark" style="margin-left: 15px; margin-right: 15px;">
                                                  <tbody>
                                                   		<tr>
                                                          <td>
                                                          	<p style="color: #6495ED; font-weight: bold;">Custo para Envio: R$ <?=ValidateUtil::setFormatMoney($data['_total_pagar_fornecedor']);?></p>
                                                          	<p style="color: #6495ED; font-weight: bold;">Frete: R$ <?=ValidateUtil::setFormatMoney($data['_frete']);?></p>
                                                          	<p style="color: #6495ED; font-weight: bold;">Comissão B2W: R$ <?=ValidateUtil::setFormatMoney($data['_comissao_b2w']);?></p>
                                                          	<p style="color: #6495ED; font-weight: bold;">Cartão Pago: <?=$data['_quantidade_total_cartao_pago'];?> / <?=$data['_percentual_cartao_pago'];?>%</p>
                                                          	<p style="color: #6495ED; font-weight: bold;">Boleto Pago: <?=$data['_quantidade_total_boleto_pago'];?> / <?=$data['_percentual_boleto_pago'];?>%</p>
                                                          	<p style="color: #6495ED; font-weight: bold;">Taxa conversão boleto: <?=$data['_taxa_conversao_boleto'];?>%</p>
                                                          </td>
                                                        </tr>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
									</div>
								</div>
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count">R$ <?=$data['_lucro'];?></div>
										<h3><span style="font-size: 13px; color: #3CB371;"><?=($data['_lucro'] != 0) ? substr(($data['_lucro'] / ($data['_total_pedidos_pagos'] != 0) ? $data['_total_pedidos_pagos'] : 1) * 100, 0, 2).'%' : '0%';?></span> <a class="" data-toggle="collapse" href="#lucro-em-cada-produto" role="button" aria-expanded="false" aria-controls="lucro-em-cada-produto">Lucro Líquido</a> </h3>
                                        <div class="row">
                                          <div class="col">
                                            <div class="collapse multi-collapse" id="lucro-em-cada-produto">
                                              <div class="card card-body">
                                                <table class="table table-dark" style="margin-left: 15px; margin-right: 15px;">
                                                  <tbody>
                                                    <?php 
                                                      function lucroPorProduto($idProduto, $_where, $return, $tipo = NULL){
                                                          $where = $_where;
                                                          $idsPedidos = [];
                                                          $itensPedidos = dao('Core', 'ItemPedido')->select([
                                                              '*'
                                                          ], [
                                                              'id_produto',
                                                              '=',
                                                              $idProduto
                                                          ]);
                                                          
                                                          foreach ($itensPedidos as $item) {
                                                              $idsPedidos[] = $item['id_pedido'];
                                                          }
                                                          
                                                          if(sizeof($idsPedidos) > 0){
                                                            array_push($where, [
                                                                'id',
                                                                'IN',
                                                                  $idsPedidos
                                                            ]);
                                                          }
                                                          
                                                          if($tipo != NULL){
                                                              array_push($where, [
                                                                  'tipo_pagamento',
                                                                  '=',
                                                                  $tipo
                                                              ]);
                                                          }
                                                          
                                                          // LUCRO
                                                          $lucro = dao('Core', 'Pedido')->select(['*'], $where);
                                                          
                                                          $_lucro = [];
                                                          $_total_pago = [];
                                                          foreach ($lucro as $pedido) {
                                                              $_total_pago[] = $pedido['valor'];
                                                              // ITENS DO PEDIDO
                                                              
                                                              $_lucro[] = $pedido['lucro'];
                                                              
                                                              // $itens = dao('Core', 'ItemPedido')->select(['*'], ['id_pedido','=',$pedido['id']]);
                                                              // foreach ($itens as $item) {
                                                              // $_lucro[] = $item['lucro'];
                                                              // }
                                                          }

                                                          switch ($return) {
                                                              case 'lucro':
                                                                    return array_sum($_lucro);
                                                                    break;
                                                              case 'total':
                                                                  return array_sum($_total_pago);
                                                                  break;
                                                              case 'countable':
                                                                  return count($_total_pago);
                                                                  break;
                                                          }
                                                          
                                                      }
                                                      
                                                      function metaFaturamento($cat, $valor_meta, $faturamento, $produto, $_data){
                                                          
                                                          // 250.000,00 // FATURAMENTO BRUTO
                                                          $meta_faturamento = $valor_meta;
                                                          
                                                          $qtd_total_cartao_aprovado = lucroPorProduto($produto['id'], $_data['where_total_pedidos_pagos'], 'countable', 'Cartao');
                                                          $qtd_total_boleto_pago = lucroPorProduto($produto['id'], $_data['where_total_pedidos_pagos'], 'countable', 'Boleto');
                                                          $qtd_total_vendas_pagas = $qtd_total_cartao_aprovado + $qtd_total_boleto_pago;
                                                          if($qtd_total_vendas_pagas == 0){
                                                              $qtd_total_vendas_pagas = 1;
                                                          }
                                                          
//                                                           $facebook_ads = Investimento($_data['where_lancamento'], $produto['id']);
                                                          $facebook_ads = Investimento($_data['where_lancamento'], $produto['id']);
                                                          
                                                          
                                                          $ticket_medio_do_produto = 1;
                                                          $ticket_medio_do_produto = $faturamento / $qtd_total_vendas_pagas;
                                                          
                                                          $quantidade_vendas_mensais = $meta_faturamento / $ticket_medio_do_produto;
                                                          $quantidade_vendas_semanais = ($meta_faturamento / $ticket_medio_do_produto) / 7;
                                                          $quantidade_vendas_diarias = ($meta_faturamento / $ticket_medio_do_produto) / 30;
                                                          
                                                          // CUSTO MÉDIO POR RESULTADO APROVADO
                                                          $CPCMA = $facebook_ads / $qtd_total_vendas_pagas;
                                                          
                                                          $quantidade_investimento_mensais = $CPCMA * $quantidade_vendas_mensais;
                                                          $quantidade_investimento_semanais = $CPCMA * $quantidade_vendas_semanais;
                                                          $quantidade_investimento_diarias = $CPCMA * $quantidade_vendas_diarias;
                                                          
                                                          // FATURAMENTO REAL MÉDIO
                                                          $faturamento_real_medio_mensal = $ticket_medio_do_produto * $quantidade_vendas_mensais;
                                                          $faturamento_real_medio_semanal = $ticket_medio_do_produto * $quantidade_vendas_semanais;
                                                          $faturamento_real_medio_diarias = $ticket_medio_do_produto * $quantidade_vendas_diarias;
                                                          
                                                          $lucro_liquido = lucroPorProduto($produto['id'], $_data['where_total_pedidos_pagos'], 'lucro');
                                                          
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
                                                      
                                                     foreach (dao('Core', 'Produto')->select(['*']) as $p) { ?>
                                                     <?php
                                                         $total_pedidos = (lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable') + lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'countable'));
                                                         $total_venda = (lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total') + lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'total'));
                                                         $total_pago = lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total');
                                                         $percentual_das_vendas_pagas_em_relacao_aos_boletos = 0;
                                                         if($total_venda != 0){
                                                            $percentual_das_vendas_pagas_em_relacao_aos_boletos = ($total_pago / $total_venda) * 100;
                                                            $percentual_das_vendas_pagas_em_relacao_aos_boletos = substr($percentual_das_vendas_pagas_em_relacao_aos_boletos, 0, 5);
                                                         }
                                                         
                                                         $qtd_total_vendas = (lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable', 'Cartao') + lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'countable', 'Boleto') + lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable', 'Boleto'));
                                                         $qtd_total_cartao_aprovado = lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable', 'Cartao');
                                                         $qtd_total_boleto_nao_pago = lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'countable', 'Boleto');
                                                         $qtd_total_boleto_pago = lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable', 'Boleto');
                                                         
                                                         $percentual_cartao = 0;
                                                         $percentual_boleto = 0;
                                                         if($qtd_total_vendas != 0){
                                                            $percentual_cartao = round(($qtd_total_cartao_aprovado * 100) / $qtd_total_vendas);
                                                            $percentual_boleto = round((($qtd_total_boleto_nao_pago + $qtd_total_boleto_pago) * 100) / $qtd_total_vendas);
                                                         }
                                                         
                                                         $taxa_aprovacao_boleto = 0;
                                                         if(($qtd_total_boleto_nao_pago + $qtd_total_boleto_pago) != 0){
                                                            $taxa_aprovacao_boleto = round(($qtd_total_boleto_pago * 100) / ($qtd_total_boleto_nao_pago + $qtd_total_boleto_pago));
                                                         }
                                                         
                                                         // FATURAMENTO BASEADO NAS VENDAS CONCRETIZADAS, OU SEJA, VENDA APROVADA DE CARTÃO E BOLETO
                                                         $faturamento_liquido = lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total');
                                                         if($faturamento_liquido == 0){
                                                             $faturamento_liquido = 1;
                                                         }
                                                         
                                                         $faturamento_bruto = $qtd_total_vendas * $p['valor_venda'];
                                                         
                                                         $facebook_ads = Investimento($data['where_lancamento'], $p['id']);
                                                         // $facebook_ads = Investimento($data['where_lancamento']);
                                                         
                                                         $ROI = 0;
                                                         if($facebook_ads != 0){
                                                         $ROI = ($faturamento_bruto - $facebook_ads) / $facebook_ads;
                                                         $ROI = floatval($ROI);
                                                         $ROI = floatval(substr($ROI, 0, 4));
                                                         }
                                                         
                                                         // CARTAO APROVADO + BOLETO PAGO + BOLETO PENDENTE
                                                         $qtd_pedidos_pagos_e_pendentes = $qtd_total_cartao_aprovado + $qtd_total_boleto_pago + $qtd_total_boleto_nao_pago;
                                                         
                                                         // CUSTO POR RESULTADO (COMPRA) (BASEADO NOS BOLOES PAGOS, PENDENTES E CARTÕES APROVADOS)
                                                         $CPR = 0;
                                                         if($qtd_pedidos_pagos_e_pendentes != 0){
                                                            $CPR = $facebook_ads / $qtd_pedidos_pagos_e_pendentes;
                                                         }
                                                         
                                                         $qtd_pedidos_pagos = $qtd_total_cartao_aprovado + $qtd_total_boleto_pago; // CARTAO APROVADO + BOLETO PAGO
                                                         
                                                         // CUSTO MÉDIO POR RESULTADO APROVADO
                                                         $CPCMA = 0;
                                                         if($qtd_pedidos_pagos != 0){
                                                         $CPCMA = $facebook_ads / $qtd_pedidos_pagos;
                                                         }
                                                         
                                                         // Lucro Líquido
                                                         $lucro_liquido = ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'lucro'));
                                                         $lucro_desejado = (lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'lucro') + lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'lucro'));
                                                         
                                                         // PERCENTUAL DE LUCRO | CONSIDERANDO AS VENDAS PAGAS
                                                         $percentual_de_lucro = lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'lucro') - $facebook_ads;
                                                         $percentual_de_lucro = ($percentual_de_lucro / $faturamento_liquido) * 100;
                                                         $percentual_de_lucro = substr($percentual_de_lucro, 0, 6);
                                                         
                                                         $meta_liquida_duzentos_cinquenta = metaFaturamento('L', 250000, $faturamento_liquido, $p, $data);
                                                         $meta_liquida_quinhentos = metaFaturamento('L', 500000, $faturamento_liquido, $p, $data);
                                                         $meta_liquida_milhao = metaFaturamento('L',1000000, $faturamento_liquido, $p, $data);
                                                         
                                                         
                                                         if($total_pedidos != 0){ 
                                                         ?>
                                                        <tr>
                                                          <td><span style="cursor: pointer;" title="<?=$p['descricao'];?>" data-toggle="modal" data-target="#analisar_resultados-<?=$p['id'];?>">(<?=(lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'countable') + lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'countable'))?>) <?=substr($p['descricao'], 0, 20);?>...</span><br>
															<div class="modal fade" id="analisar_resultados-<?=$p['id'];?>"  tabindex="-1" role="dialog"
																	aria-labelledby="exampleModalCenterTitle"
																	aria-hidden="true">
																	<div class="modal-dialog modal-dialog-centered"
																		role="document">
																		<div class="modal-content">
																			<div class="modal-header">
																				<h5 class="modal-title"
																					id="exampleModalLongTitle">
																					<span title="<?=$p['descricao'];?>" style="font-weight: bold;"
																						data-toggle="modal"
																						data-target="#analisar_resultados"><?=$p['descricao'];?></span>
																				</h5>
																			</div>
																			<div class="modal-body">
    																			<div class="panel panel-default">
                                                                                  <div class="panel-body">
                                                                                    <span style="padding-bottom: 10px; font-weight: bold;">Vendas</span>
                                                                                    <ul class="list-group">
                                                                                     <li class="list-group-item active" style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($faturamento_liquido);?></span>
                                                                                        Faturamento
                                                                                      </li>
                                                                                      <li class="list-group-item list-group-item-info" style="font-weight: bold;">
                                                                                        <span class="badge"><?=$qtd_total_vendas;?></span>
                                                                                        Compras
                                                                                      </li>
                                                                                      <li class="list-group-item list-group-item-success" style="font-weight: bold;">
                                                                                      	<span class="badge">R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total', 'Cartao'));?> </span>
                                                                                         <a class="badge" href="?m=sistema&c=venda&a=&numero_pedido=&tipo=cartao&data_inicio=<?=$_GET['data_inicio'];?>&data_fim=<?=$_GET['data_fim'];?>&situacao=2&status_fornecedor=&produto=<?=$p['id'];?>"><?=$qtd_total_cartao_aprovado;?></a>
                                                                                        Cartão Aprovado
                                                                                      </li>
                                                                                      <li class="list-group-item list-group-item-info" style="font-weight: bold;">
    																					<span class="badge">R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'total', 'Boleto'));?> </span>
                                                                                        <a class="badge" href="?m=sistema&c=venda&a=&numero_pedido=&tipo=&data_inicio=<?=$_GET['data_inicio'];?>&data_fim=<?=$_GET['data_fim'];?>&situacao=1&status_fornecedor=&produto=<?=$p['id'];?>"><?=$qtd_total_boleto_nao_pago;?></a>
                                                                                        Boleto Pendente
                                                                                      </li>
                                                                                     <li class="list-group-item list-group-item-success" style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total', 'Boleto'));?></span>
                                                                                        <a class="badge" href="?m=sistema&c=venda&a=&numero_pedido=&tipo=boleto&data_inicio=<?=$_GET['data_inicio'];?>&data_fim=<?=$_GET['data_fim'];?>&situacao=2&status_fornecedor=&produto=<?=$p['id'];?>"><?=$qtd_total_boleto_pago;?></a>
                                                                                        Boleto Pago
                                                                                      </li>
                                                                                      <li class="list-group-item list-group-item-danger" style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_chargeback'], 'total'));?></span>
                                                                                        <span class="badge"><?=lucroPorProduto($p['id'], $data['where_total_pedidos_chargeback'], 'countable');?></span>
                                                                                        Prejuízos (Chargeback/Reembolso)
                                                                                      </li>
                                                                                       <li class="list-group-item list-group-item-success" style="font-weight: bold;">
                                                                                      	<span class="badge">R$ <?=$lucro_liquido;?> </span>
                                                                                        Lucro Líquido
                                                                                      </li>
                                                                                      <li class="list-group-item list-group-item-info" style="font-weight: bold;">
                                                                                      	<span class="badge">R$ <?=ValidateUtil::setFormatMoney($lucro_desejado);?> </span>
                                                                                        Lucro Desejado
                                                                                      </li>
                                                                                      <li class="list-group-item" >
                                                                                      	<p class="list-group-item-text">O Lucro desejado basea-se no lucro líquido somado com o lucro dos boletos não pagos.</p>
                                                                                      </li>
                                                                                      <li class="list-group-item active" style="font-weight: bold; display: none;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($faturamento_bruto);?></span>
                                                                                        Faturamento Bruto
                                                                                      </li>
                                                                                    </ul>
                                                                                  </div>
                                                                                </div>
    																			<div class="panel panel-default">
                                                                                  <div class="panel-body">
                                                                                    <span style="padding-bottom: 10px; font-weight: bold;">Marketing</span>
                                                                                    <ul class="list-group">
                                                                                      <li class="list-group-item" style="background: #4267b2; color: #FFF; font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(Investimento($data['where_lancamento'], $p['id']));?></span>
                                                                                        Facebook Ads
                                                                                      </li>
                                                                                    </ul>
                                                                                  </div>
                                                                                </div>
                                                                                <div class="panel panel-default">
                                                                                  <div class="panel-body">
                                                                                    <span style="padding-bottom: 10px; font-weight: bold;">Desempenho</span>
                                                                                    <ul class="list-group">
                                                                                       <li class="list-group-item" style="font-weight: bold;" >
                                                                                        <span class="badge"><?=$percentual_boleto;?>%</span>
                                                                                       Percentual de Boleto
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=$percentual_cartao;?>%</span>
                                                                                       Percentual de Cartão
                                                                                      </li>
                                                                                       <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=$percentual_de_lucro;?>%</span>
                                                                                       Percentual de Lucro Liquido
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=$taxa_aprovacao_boleto;?>%</span>
                                                                                       Taxa de Aprovação de Boleto
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=$ROI;?> / <?=($ROI*100);?>%</span>
                                                                                       Retorno sobre Investimento
                                                                                      </li>
                                                                                      <li class="list-group-item" >
                                                                                      	<p class="list-group-item-text">Para cada real investido, o facebook ads trouxe R$ <?=ValidateUtil::setFormatMoney($ROI);?> de retorno.</p>
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($CPR);?></span>
                                                                                       Custo por Resultado
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($CPCMA);?></span>
                                                                                       Custo Médio por Compra Recebida
                                                                                      </li>
                                                                                    </ul>
                                                                                  </div>
                                                                                </div>
                                                                                <div class="panel panel-default">
                                                                                  <div class="panel-body">
                                                                                    <span style="padding-bottom: 10px; font-weight: bold;">Meta de Faturamento</span>
                                                                                    <ul class="list-group">
                                                                                       <li class="list-group-item active" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(250000);?></span>
                                                                                       Meta
                                                                                      </li>
                                                                                       <li class="list-group-item" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['ticket_medio_do_produto']);?></span>
                                                                                       Ticket Médio
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['quantidade_investimento_mensais']);?></span>
                                                                                       Investimento Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['quantidade_investimento_semanais']);?></span>
                                                                                       Investimento Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['quantidade_investimento_diarias']);?></span>
                                                                                       Investimento Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_duzentos_cinquenta['quantidade_vendas_mensais']);?></span>
                                                                                       Quantidade de Vendas Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_duzentos_cinquenta['quantidade_vendas_semanais']);?></span>
                                                                                       Quantidade de Vendas Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_duzentos_cinquenta['quantidade_vendas_diarias']);?></span>
                                                                                       Quantidade de Vendas Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['faturamento_real_medio_mensal']);?></span>
                                                                                       Faturamento Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['faturamento_real_medio_semanal']);?></span>
                                                                                       Faturamento Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['faturamento_real_medio_diarias']);?></span>
                                                                                       Faturamento Líquido Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['lucro_medio_mensal']);?></span>
                                                                                       Lucro Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['lucro_medio_semanal']);?></span>
                                                                                       Lucro Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_duzentos_cinquenta['lucro_medio_diario']);?></span>
                                                                                       Lucro Líquido Diário
                                                                                      </li>
                                                                                    </ul>
                                                                                    
                                                                                    <ul class="list-group">
                                                                                       <li class="list-group-item active" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(500000);?></span>
                                                                                       Meta
                                                                                      </li>
                                                                                       <li class="list-group-item" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['ticket_medio_do_produto']);?></span>
                                                                                       Ticket Médio
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['quantidade_investimento_mensais']);?></span>
                                                                                       Investimento Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['quantidade_investimento_semanais']);?></span>
                                                                                       Investimento Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['quantidade_investimento_diarias']);?></span>
                                                                                       Investimento Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_quinhentos['quantidade_vendas_mensais']);?></span>
                                                                                       Quantidade de Vendas Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_quinhentos['quantidade_vendas_semanais']);?></span>
                                                                                       Quantidade de Vendas Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_quinhentos['quantidade_vendas_diarias']);?></span>
                                                                                       Quantidade de Vendas Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['faturamento_real_medio_mensal']);?></span>
                                                                                       Faturamento Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['faturamento_real_medio_semanal']);?></span>
                                                                                       Faturamento Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['faturamento_real_medio_diarias']);?></span>
                                                                                       Faturamento Líquido Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['lucro_medio_mensal']);?></span>
                                                                                       Lucro Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['lucro_medio_semanal']);?></span>
                                                                                       Lucro Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_quinhentos['lucro_medio_diario']);?></span>
                                                                                       Lucro Líquido Diário
                                                                                      </li>
                                                                                    </ul>
                                                                                    
                                                                                    <ul class="list-group">
                                                                                       <li class="list-group-item active" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney(1000000);?></span>
                                                                                       Meta
                                                                                      </li>
                                                                                       <li class="list-group-item" style="font-weight: bold;" >
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['ticket_medio_do_produto']);?></span>
                                                                                       Ticket Médio
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['quantidade_investimento_mensais']);?></span>
                                                                                       Quantidade de Investimento Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['quantidade_investimento_semanais']);?></span>
                                                                                       Quantidade de Investimento Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['quantidade_investimento_diarias']);?></span>
                                                                                       Quantidade de Investimento Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_milhao['quantidade_vendas_mensais']);?></span>
                                                                                       Quantidade de Vendas Mensais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_milhao['quantidade_vendas_semanais']);?></span>
                                                                                       Quantidade de Vendas Semanais
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge"><?=round($meta_liquida_milhao['quantidade_vendas_diarias']);?></span>
                                                                                       Quantidade de Vendas Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['faturamento_real_medio_mensal']);?></span>
                                                                                       Faturamento Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['faturamento_real_medio_semanal']);?></span>
                                                                                       Faturamento Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['faturamento_real_medio_diarias']);?></span>
                                                                                       Faturamento Líquido Diário
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['lucro_medio_mensal']);?></span>
                                                                                       Lucro Líquido Mensal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['lucro_medio_semanal']);?></span>
                                                                                       Lucro Líquido Semanal
                                                                                      </li>
                                                                                      <li class="list-group-item"  style="font-weight: bold;">
                                                                                        <span class="badge">R$ <?=ValidateUtil::setFormatMoney($meta_liquida_milhao['lucro_medio_diario']);?></span>
                                                                                       Lucro Líquido Diário
                                                                                      </li>
                                                                                    </ul>
                                                                                  </div>
                                                                                </div>
																			</div>
																			<div class="modal-footer">
																				<button type="button" class="btn btn-secondary"
																					data-dismiss="modal">Fechar</button>
																			</div>
																		</div>
																	</div>
																</div>
                                                              <p style="color: green; font-weight: bold;">Vendas: R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_pagos'], 'total'));?> </p>
                                                              <p style="color: green; font-weight: bold; display: none;">Lucro Líquido - R$ <?=$lucro_liquido;?></p>
                                                              <p style="color: #4267b2; font-weight: bold; display: none;">Facebook Ads - R$ <?=ValidateUtil::setFormatMoney(Investimento($data['where_lancamento']));?></p>
                                                              <p style="color: #6495ED; font-weight: bold; display: none;">Líquido Pendente - R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_boletos_a_compensar'], 'lucro'));?></p>
                                                              <p style="color: tomato; font-weight: bold; display: none;">Chargeback - R$ <?=ValidateUtil::setFormatMoney(lucroPorProduto($p['id'], $data['where_total_pedidos_chargeback'], 'total'));?></p>
                                                          </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php } ?>
                                                  </tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
									</div>
								</div>
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count">R$ <?=$data['_boletos_a_compensar'];?></div>
										<h3><?=$data['_quantidade_total_boleto_compensar'];?> Ag. Pagamento</h3>
									</div>
								</div>
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count">R$ <?=$data['_valor_total_pedidos_em_analise'];?></div>
										<h3><?=$data['_total_em_analise'];?> Em Análise</h3>
									</div>
								</div>								
								<div
									class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="tile-stats">
										<div class="count">R$ <?=$data['_cancelado'];?></div>
										<h3>Chargeback</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>
									Pedidos <small>Todos </small>
								</h2>
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
								<table
									class="table table-striped table-bordered dt-responsive nowrap"
									cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="width: 100px;">Ações</th>
											<th>Cliente</th>
											<th>Nº Pedido</th>
											<th>Data</th>
											<th>Total</th>
											<th>Lucro</th>
											<th>Frete</th>
											<th>Tipo</th>
											<th>Pagamento</th>
											<?php if(Request::get('b2w') == 0){ ?><th>Envio</th><?php } ?>
											<th>Boleto</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($data['pedidos'] as $pedido) {
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
									    
									    $statusClearSale = ($pedido['status_clear_sale']) ? ' - '.getStatusClear($pedido['status_clear_sale']).'' : '';
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
											<td><button type="button"
													class="btn btn-default btn-xs dropdown-toggle"
													data-method="getData" data-option="" data-target="#putData">
													<a href="?m=sistema&c=venda&a=form&id=<?=$pedido['id'];?>"><i
														class="glyphicon glyphicon-edit"></i></a>
												</button>
												<button type="button" 
													class="btn btn-default btn-xs dropdown-toggle"
													data-method="getData" data-option="" data-target="#putData">
													<a target="new"  title="Declaração de Conteúdo"  href="?m=sistema&c=venda&a=declaracao_conteudo&id=<?=$pedido['id'];?>">DC</a>
												</button>	
											</td>
											<td><?=dao('Core', 'Cliente')->getField('nome', $pedido['id_cliente']);?></td>
											<td><?=$pedido['numero_pedido'];?><p><b>#<?=$pedido['codigo_transacao'];?></b></p></td>
											<td style="display: none;"><?=dao('Core', 'Cliente')->getField('nome', $pedido['id_cliente']);?><span style="color: #3CB371; display: none;"><?=$nome_produto;?></span></td>
											<td><?=DateUtil::getDateDMY($pedido['data']);?><?=($_hora != '') ? ' às <b>'.$_hora.'</b>' : '';?><?=($pedido['response_code_gateway'] != NULL) ? '<br>'. getDescricaoTransacao($pedido['response_code_gateway'], $statusClearSale) : ''; ?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney(($pedido['valor'] + $pedido['frete']));?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney($pedido['lucro']);?></td>
											<td>R$ <?=ValidateUtil::setFormatMoney($pedido['frete']);?></td>
											<td><?=ucfirst($tipo);?></td>
											<td><?=$pagamento_situacao;?></td>
											<?php if(Request::get('b2w') == 0){ ?><td><?=$status_fornecedor;?></td><?php }?>
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
								<div id="navegacao">
                                	<?=$data['paginacao'];?>
                            	</div>
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
					href="https://<?=LINK_LOJA;?>"></a>
			</div>
			<div class="clearfix"></div>
		</footer>
	</div>
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="public/admin/vendors/fastclick/lib/fastclick.js"></script>
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<script
		src="public/admin/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script
		src="public/admin/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
	<script src="public/admin/vendors/select2/dist/js/select2.full.min.js"></script>
		
	<script
		src="public/admin/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
	<script src="public/admin//vendors/jszip/dist/jszip.min.js"></script>
	<script src="public/admin/vendors/pdfmake/build/pdfmake.min.js"></script>
	<script src="public/admin/vendors/pdfmake/build/vfs_fonts.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>
	<script src="public/admin/js/datepicker/daterangepicker.js"></script>
	<script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Selecione o produto",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 100,
          placeholder: "",
          allowClear: true
        });

        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();
        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        var table = $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        TableManageButtons.init();
      });
    </script>
</body>
</html>
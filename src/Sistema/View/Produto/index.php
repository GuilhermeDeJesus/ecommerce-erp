<?php
use Krypitonite\Util\ValidateUtil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Produtos</title>

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
							<h3>Produto</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<a href="?m=sistema&c=produto&a=inserirEditar"><button
									type="button" class="btn btn btn-success">Adicionar produto</button>
							</a>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php if(isset($data['error']) && $data['error'] == true){ ?>
							<div class="alert alert-warning alert-dismissible fade in"
								role="alert">
								<button type="button" class="close" data-dismiss="alert"
									aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								<?=$data['msg'];?>
							</div>
							<?php } ?>
							<?php if(isset($data['error']) && $data['error'] == false){ ?>
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
										Todos
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
									<div class="alert alert-warning" id="msg-produto" role="alert"
										style="display: none; width: 100%;">
										<span id="text-produto"></span>
									</div>
									<table id="datatable-responsive"
										class="table table-striped table-bordered dt-responsive nowrap"
										cellspacing="0" width="100%">
										<thead>
											<tr>
												<th style="width: 80px;">Ações</th>
												<th></th>
												<th>Descrição</th>
												<th>Valor Compra</th>
												<th>Valor Venda</th>
												<th>Categoria</th>
												<th>Fornecedor</th>
												<th>Ativo</th>
												<th>SKU</th>
												<th>Frete</th>
												<th>Vendas</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data['produtos'] as $m){ ?>
											<tr>
												<td>
    												<button type="button"
														class="btn btn-default btn-xs dropdown-toggle"
														data-method="getData" data-option=""
														data-target="#putData">
														<a
															href="?m=sistema&c=produto&a=inserirEditar&id=<?=$m['id'];?>"><i
															class="glyphicon glyphicon-edit"></i></a>
													</button>
													<button type="button" style=""
														class="btn btn-default btn-xs dropdown-toggle"
														data-toggle='modal'
														data-target='#deletar-produto-<?=$m['id'];?>'>
														<i class="glyphicon glyphicon-trash"></i>
													</button>
													<div class="modal fade" id="deletar-produto-<?=$m['id'];?>"
														tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
														aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<span
																		style="font-size: 16px; color: #666; font-weight: 600;">excluir
																		produto</span>
																</div>
																<div id="msg"></div>
																<div class="">
																	<ul class="caixa-info">
																		<li><span>Você tem certeza que deseja excluir este
																				produto <b><?=$m['descricao'];?></b>?
																		</span></li>
																	</ul>
																	<form role="form"
																		id="form-deletar-endereco-<?=$m['id'];?>" novalidate>
																		<input value="<?=$m['id'];?>" type="hidden"
																			name="id_endereco" />
																		<div class="modal-footer">
																			<button type="button" class="btn-excluir-produto"
																				data-dismiss="modal">Não, cancelar</button>
																			<button type="button" class="btn-excluir-endereco"
																				id="deletar-produto-<?=$m['id'];?>">Sim, excluir</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div> <script>
        											$(document).ready(function () {
        												$('.load-img').css('display', 'none');
        												$('.load-img').hide();
        												 $('#deletar-produto-<?=$m['id'];?>').click(function(e) {
        	                                        	    	$('.load-img').show();
        	                                        	    	$('.load-img').css('display', 'inline-block');
        	                                        	 		$('body').css("opacity", "0.5");
        	                                        	 		
        	                                                	e.preventDefault();
        	                                                	$('#deletar-produto-<?=$m['id'];?>').modal('hide');
        	                                        	 		setTimeout(function(){ 
        	                                        	 			$.ajax({
        	                                            				type : 'POST',
        	                                            			 	beforeSend: function(){},
        	                                            				dataType : "text",
        	                                            				async : false,
        	                                            				url : "?m=sistema&c=produto&a=deletar",
        	                                            				data : {
        	                                             					"id_produto" : <?=$m['id'];?>,
        	                                            				},					  
        	                                            				success: function(data){
        	                                            					$('.load-img').css("display", "none");
        	                                        						$('.load-img').hide();
        	                                                                $('body').css("opacity", "1");
        	                                                                $('#msg-produto').css("display", "table");
        	                                                                document.getElementById('text-produto').innerHTML = "Produto excluído com sucesso";
        	                                            				},
        	                                            			});
        	                                        	 		}, 100);                                                	
        	                                                });
        											 });
                                                    </script></td>
                                                <td><div class="inbox-body">
														<div class="products"><ul><li><a target="new" href="data/products/<?=$m['id'];?>/principal.jpg" class="atch-thumb"> <img
    																		src="data/products/<?=$m['id'];?>/principal.jpg" alt="img" /></a></li></ul></div></div></td>
												<td><?=$m['descricao'];?></td>
												<td>R$ <?=ValidateUtil::setFormatMoney($m['valor_compra']);?></td>
												<td>R$ <?=ValidateUtil::setFormatMoney($m['valor_venda']);?></td>
												<td><?=dao('Core', 'Categoria')->getField('descricao', $m['id_categoria']);?></td>
												<td><?=dao('Core', 'Pessoa')->getField('nome', $m['id_fornecedor']);?></td>
												<td><?=($m['ativo']) ? 'Sim' : 'Não';?></td>
												<td><?=($m['sku']);?></td>
												<td><?=($m['frete_gratis']) ? 'Grátis' : 'Pago';?></td>
												<td><a target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=2&tipo=Cartao"
													style="color: green;"><i class="fa fa-dollar"
														style="color: green;"></i> Cartão - Aprovado </a> | <a
													target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=2&tipo=Boleto"
													style="color: green;"><i class="fa fa-dollar"
														style="color: green;"></i> Boleto - Aprovado </a> | <a
													target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=3&tipo=Cartao"
													style="color: #F08080;"><i class="fa fa-dollar"
														style="color: #F08080;"></i> Cartão - Recusado</a> | <a
													target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=1&tipo=Boleto"
													style="color: #6495ED;"><i class="fa fa-dollar"
														style="color: #6495ED;"></i> Boleto - Não Pago</a> | <a
													target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=4&tipo=Cartao"
													style="color: #6495ED;"><i class="fa fa-dollar"
														style="color: #6495ED;"></i> Chargebacks</a> | <a
													target="new"
													href="?m=sistema&c=produto&a=_getEmailsPorStatusDeVenda&id=<?=$m['id'];?>&situacao=5"
													style="color: #6495ED;"><i class="fa fa-dollar"
														style="color: #6495ED;"></i> Reembolsados</a></td>
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
			<footer>
				<div class="pull-right">
				<?=NOME_LOJA;?> - Todos os direitos reservados <a
						href="https://<?=LINK_LOJA;?>"></a>
				</div>
				<div class="clearfix"></div>
			</footer>
		</div>
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
	<script
		src="public/admin/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
	<script src="public/admin//vendors/jszip/dist/jszip.min.js"></script>
	<script src="public/admin/vendors/pdfmake/build/pdfmake.min.js"></script>
	<script src="public/admin/vendors/pdfmake/build/vfs_fonts.js"></script>

	<script src="public/admin/build/js/custom.min.js"></script>

	<script>
      $(document).ready(function() {
    	$(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
    		$(".alert-success").slideUp(500);
    	});
    	
    	$(".alert-warning").fadeTo(2000, 500).slideUp(500, function(){
    		$(".alert-warning").slideUp(500);
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
	<!-- /Datatables -->
</body>
</html>
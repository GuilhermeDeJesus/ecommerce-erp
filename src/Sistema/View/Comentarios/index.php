<?php
use Krypitonite\Util\DateUtil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Comentários</title>

<!-- Bootstrap -->
<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<!-- Font Awesome -->
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<!-- iCheck -->
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<!-- Datatables -->
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
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Comentários</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>
										Listagem de<small>Comentários dos Produtos</small>
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
									<table id="datatable-responsive"
										class="table table-striped table-bordered dt-responsive nowrap"
										cellspacing="0" width="100%">
										<thead>
											<tr>
												<th width="200">Nome</th>
												<th width="200">Texto</th>
												<th>Ativo</th>
												<th>Imagem</th>
												<th>Ações</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data['comentarios'] as $c){ ?>
											<?php 
											$ativo = '';
											if($c['ativo'] == 0){
											    $ativo = '<div style="background: #EEDD82; width: 50px; border-radius: 8px; text-align: center; padding: 2px; color: #FFF;">Não</div>';
											}else{
											    $ativo = '<div style="background: #3CB371; width: 50px; border-radius: 8px; text-align: center; padding: 2px; color: #FFF;">Sim</div>';
											}
											?>
											<tr>
												<td><?=$c['nome'];?><br>Data: <?=DateUtil::getDateDMY($c['date_create']);?></td>
												<td><?=substr($c['texto'], 0, 80);?> ... 
													<button type="button" title="<?=$c['texto'];?>"
														class="btn btn-default btn-xs dropdown-toggle"
														data-method="getData" data-option data-target="#putData">Comentário completo
													</button></td>
												<td><?=$ativo;?></td>
												<td><div style="display: inline;">
														<img style="width: 65px; float: left;"
															src="data/comentarios/<?=$c['id_produto'];?>/<?=$c['img'];?>"
															alt="" class="img-responsive">
													</div></td>
												<td><button type="button" title="Aprovar"
														class="btn btn-default btn-xs dropdown-toggle"
														data-method="getData" data-option data-target="#putData">
														<a href="?m=sistema&c=comentarios&a=aprovar&id=<?=$c['id']?>"><i
															class="glyphicon glyphicon-ok"></i> Aprovar</a>
													</button>
													<button type="button" title="Desaprovar comentário"
														class="btn btn-default btn-xs dropdown-toggle"
														data-method="getData" data-option data-target="#putData">
														<a href="?m=sistema&c=comentarios&a=desaprovar&id=<?=$c['id']?>"><i
															class="fa fa-close"></i> Desaprovar</a>
													</button>
													<button type="button" title="Excluir comentário :D"
														class="btn btn-default btn-xs dropdown-toggle"
														data-method="getData" data-option data-target="#putData">
														<a href="?m=sistema&c=comentarios&a=excluir&id=<?=$c['id']?>"><i
															class="fa fa-close"></i> Excluir</a>
													</button>	
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

			<footer>
				<div class="pull-right">
				<?=NOME_LOJA;?> - Todos os direitos reservados <a
						href="https://<?=LINK_LOJA;?>"><?=NOME_LOJA;?></a>
				</div>
				<div class="clearfix"></div>
			</footer>
		</div>
	</div>
	<!-- jQuery -->
	<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- FastClick -->
	<script src="public/admin/vendors/fastclick/lib/fastclick.js"></script>
	<!-- NProgress -->
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<!-- Datatables -->
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
<?php
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Util\DateUtil;
?>
<link
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css"
	rel="stylesheet" id="bootstrap-css">
<script
	src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
$(document).ready(function () {

	$('.star').on('click', function () {
      $(this).toggleClass('star-checked');
    });

    $('.ckbox label').on('click', function () {
      $(this).parents('tr').toggleClass('selected');
    });

    $('.btn-filter').on('click', function () {
      var $target = $(this).data('target');
      if ($target != 'all') {
        $('.table tr').css('display', 'none');
        $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
      } else {
        $('.table tr').css('display', 'none').fadeIn('slow');
      }
    });

 });
</script>
<!------ Include the above in your HEAD tag ---------->
<style>
/*    --------------------------------------------------
	:: General
	-------------------------------------------------- */
body {
	font-family: 'Open Sans', sans-serif;
	color: #353535;
}

.content h1 {
	text-align: center;
}

.content .content-footer p {
	color: #6d6d6d;
	font-size: 12px;
	text-align: center;
}

.content .content-footer p a {
	color: inherit;
	font-weight: bold;
}

/*	--------------------------------------------------
	:: Table Filter
	-------------------------------------------------- */
.panel {
	border: 1px solid #ddd;
	background-color: #fcfcfc;
}

.panel .btn-group {
	margin: 15px 0 30px;
}

.panel .btn-group .btn {
	transition: background-color .3s ease;
}

.table-filter {
	background-color: #fff;
	border-bottom: 1px solid #eee;
}

.table-filter tbody tr:hover {
	cursor: pointer;
	background-color: #eee;
}

.table-filter tbody tr td {
	padding: 10px;
	vertical-align: middle;
	border-top-color: #eee;
}

.table-filter tbody tr.selected td {
	background-color: #eee;
}

.table-filter tr td:first-child {
	width: 38px;
}

.table-filter tr td:nth-child(2) {
	width: 35px;
}

.ckbox {
	position: relative;
}

.ckbox input[type="checkbox"] {
	opacity: 0;
}

.ckbox label {
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.ckbox label:before {
	content: '';
	top: 1px;
	left: 0;
	width: 18px;
	height: 18px;
	display: block;
	position: absolute;
	border-radius: 2px;
	border: 1px solid #bbb;
	background-color: #fff;
}

.ckbox input[type="checkbox"]:checked+label:before {
	border-color: #2BBCDE;
	background-color: #2BBCDE;
}

.ckbox input[type="checkbox"]:checked+label:after {
	top: 3px;
	left: 3.5px;
	content: '\e013';
	color: #fff;
	font-size: 11px;
	font-family: 'Glyphicons Halflings';
	position: absolute;
}

.table-filter .star {
	color: #ccc;
	text-align: center;
	display: block;
}

.table-filter .star.star-checked {
	color: #F0AD4E;
}

.table-filter .star:hover {
	color: #ccc;
}

.table-filter .star.star-checked:hover {
	color: #F0AD4E;
}

.table-filter .media-photo {
	width: 350px;
	margin-right: 20px;
}

.table-filter .media-body {
	display: block;
	/* Had to use this style to force the div to expand (wasn't necessary with my bootstrap version 3.3.6) */
}

.table-filter .media-meta {
	font-size: 11px;
	color: #999;
}

.table-filter .media .title {
	color: #2BBCDE;
	font-size: 15px;
	font-weight: bold;
	line-height: normal;
	margin: 0;
}

.table-filter .media .title span {
	font-size: .95em;
	margin-right: 20px;
}

.table-filter .media .title span.pagado {
	color: #5cb85c;
}

.table-filter .media .title span.pendiente {
	color: #f0ad4e;
}

.table-filter .media .title span.cancelado {
	color: #d9534f;
}

.table-filter .media .summary {
	font-size: 14px;
}
</style>

<div class="container">
	<div class="row">
		<section class="content">
			<h1><?=NOME_LOJA;?> Atacado</h1>
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="pull-right">
							<div class="btn-group">
								<?php foreach ($data['categorias'] as $cat) { ?>
								<button type="button" class="btn btn-success btn-filter" style="margin-right: 5px; margin-top: 5px; border-radius: 5px; font-size: 16px;"
									data-target=<?=seo($cat);?>><?=$cat;?></button>
								<?php } ?>
								<button type="button" class="btn btn-default btn-filter" style="margin-right: 5px; margin-top: 5px; border-radius: 5px; font-size: 16px;"
									data-target="all">Todos</button>
							</div>
						</div>
						<div class="table-container">
							<table class="table table-filter">
								<tbody>
									<?php 
								    foreach ($data['produtos'] as $p) { 
								        
								    $percentual_lucro = 30;    
								    $preco = ($p['valor_compra'] / 100) * (100 + $percentual_lucro);    
								    $preco = intval($preco);
								    $preco = floatval($preco + 0.90);
								        
								    ?>
									<tr data-status="<?=seo($data['categorias'][$p['id_categoria']]);?>">
										<td>
											<div class="media">
												<a href="https://www.shopvitas.com.br/produto/<?=$p['id'];?>/<?=seo($p['descricao']);?>" class="pull-left" target="new"> <img
													src="data/products/<?=$p['id'];?>/principal.jpg"
													class="media-photo">
												</a>
												<div class="media-body">
													<span class="media-meta pull-right">Atualizada <?=DateUtil::dateLiteral(date('Y-m-d'));?>, <?=date('Y');?></span><br>
													<h4 class="title">
														<?=$data['categorias'][$p['id_categoria']];?> <span class="pull-right pagado">R$ <?=ValidateUtil::setFormatMoney($preco);?> / Unidade</span>
													</h4>
													<p class="summary"><?=$p['sobre']; ?></p>
												</div>
											</div>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="content-footer">
					<p>
						<?=NOME_LOJA;?> © - <?=date('Y')?>
					</p>
				</div>
			</div>
		</section>

	</div>
</div>
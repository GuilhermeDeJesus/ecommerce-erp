<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Configurações de Conta</title>
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
</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
		<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Configurações</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Conta</h2>
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
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
											<li role="presentation" class="active"><a
												href="#tab_content1" id="home-tab" role="tab"
												data-toggle="tab" aria-expanded="true">Configurações da
													Plataforma</a></li>
										</ul>
										<div id="myTabContent" class="tab-content">
											<br>
											<div role="tabpanel" class="tab-pane fade active in"
												id="tab_content1" aria-labelledby="home-tab">
												<form action="?m=sistema&c=plataforma&a=atualizar"
													method="post" class="form-horizontal form-label-left">
													<div class="row">
														<input type="hidden" name="id"
															value="<?= (isset($data['categoria'][0]['id'])) ? $data['categoria'][0]['id'] : '' ?>" />
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Píxel
																Facebook</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="numero_conta_anuncio_facebook"
																	value="<?= (isset($data['configuracoes'][0]['numero_conta_anuncio_facebook'])) ? $data['configuracoes'][0]['numero_conta_anuncio_facebook'] : '' ?>"
																	class="form-control"
																	placeholder="Píxel do Facebook | Esta configuração adiciona o píxel a todos os produtos cadastrados">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Token
																PagSeguro</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="token_pag_seguro"
																	value="<?= (isset($data['configuracoes'][0]['token_pag_seguro'])) ? $data['configuracoes'][0]['token_pag_seguro'] : '' ?>"
																	class="form-control" placeholder="Token do PagSeguro">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail
																PagSeguro</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="email_conta_pag_seguro"
																	value="<?= (isset($data['configuracoes'][0]['email_conta_pag_seguro'])) ? $data['configuracoes'][0]['email_conta_pag_seguro'] : '' ?>"
																	class="form-control" placeholder="E-mail do PagSeguro">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Cliente
																ID MP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cliente_id_mp"
																	value="<?= (isset($data['configuracoes'][0]['cliente_id_mp'])) ? $data['configuracoes'][0]['cliente_id_mp'] : '' ?>"
																	class="form-control" placeholder="Cliente ID MP">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Secret
																ID MP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="client_secret_mp"
																	value="<?= (isset($data['configuracoes'][0]['client_secret_mp'])) ? $data['configuracoes'][0]['client_secret_mp'] : '' ?>"
																	class="form-control" placeholder="Secret ID MP">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Rarif.
																D+01 MP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="taf_d_1_mp"
																	value="<?= (isset($data['configuracoes'][0]['taf_d_1_mp'])) ? $data['configuracoes'][0]['taf_d_1_mp'] : '' ?>"
																	class="form-control" placeholder="Ex.: 4.99">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Rarif.
																D+14 MP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="taf_d_14_mp"
																	value="<?= (isset($data['configuracoes'][0]['taf_d_1_mp'])) ? $data['configuracoes'][0]['taf_d_14_mp'] : '' ?>"
																	class="form-control" placeholder="Ex.: 4.49">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Rarif.
																D+30 MP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="taf_d_30_mp"
																	value="<?= (isset($data['configuracoes'][0]['taf_d_30_mp'])) ? $data['configuracoes'][0]['taf_d_30_mp'] : '' ?>"
																	class="form-control" placeholder="Ex.: 3.99">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Oferecer
																Parcelamento Sem Juros</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select name="parcelar_sem_juros" class="form-control">
																	<?php
                                                                    $parcelar = [
                                                                        '1' => 'Sim',
                                                                        '0' => 'Não'
                                                                    ];
                                                                    $parcelar2 = [
                                                                        '1' => 'Sim',
                                                                        '0' => 'Não'
                                                                    ];
                                                                    unset($parcelar[$data['configuracoes'][0]['parcelar_sem_juros']]);
                                                                    ?>
																	<option
																		value="<?=$data['configuracoes'][0]['parcelar_sem_juros'];?>"><?=$parcelar2[$data['configuracoes'][0]['parcelar_sem_juros']];?></option>
																	<?php foreach ($parcelar as $park => $parv) {?>
																	<option value="<?=$park;?>"><?=$parv;?></option>
																	<?php }?>
																</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Quantidade
																de Parcelas Sem Juros</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="quantidade_parcelas_sem_juros"
																	value="<?= (isset($data['configuracoes'][0]['quantidade_parcelas_sem_juros'])) ? $data['configuracoes'][0]['quantidade_parcelas_sem_juros'] : '' ?>"
																	class="form-control"
																	placeholder="Quantidade de Parcelas Sem Juros">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Gateway
																de Pagamento</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select name="gateway" class="form-control">
																	<?php
                                                                    $pags = [
                                                                        'upnid' => 'Upnid',
                                                                        'mercadopago' => 'Mercado Pago',
                                                                        'pagseguro' => 'Pag Seguro',
                                                                        'rede' => 'Rede',
                                                                        'gerencianet' => 'Gerencianet',
                                                                        'pagarme' => 'Pagar.me'
                                                                    ];
                                                                    $pags2 = [
                                                                        'upnid' => 'Upnid',
                                                                        'mercadopago' => 'Mercado Pago',
                                                                        'pagseguro' => 'Pag Seguro',
                                                                        'rede' => 'Rede',
                                                                        'gerencianet' => 'Gerencianet',
                                                                        'pagarme' => 'Pagar.me'
                                                                    ];
                                                                    unset($pags[$data['configuracoes'][0]['gateway']]);
                                                                    ?>
																	<option
																		value="<?=$data['configuracoes'][0]['gateway'];?>"><?=$pags2[$data['configuracoes'][0]['gateway']];?></option>
																	<?php foreach ($pags as $pagk => $pagv) {?>
																	<option value="<?=$pagk;?>"><?=$pagv;?></option>
																	<?php }?>
																</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail
																SMTP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="email_envio"
																	value="<?= (isset($data['configuracoes'][0]['email_envio'])) ? $data['configuracoes'][0]['email_envio'] : '' ?>"
																	class="form-control"
																	placeholder="Digite o E-mail para envio da loja">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Senha
																E-mail SMTP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="senha_email_envio"
																	value="<?= (isset($data['configuracoes'][0]['senha_email_envio'])) ? $data['configuracoes'][0]['senha_email_envio'] : '' ?>"
																	class="form-control"
																	placeholder="Digite a Senha do E-mail para envio da loja">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome
																da Loja</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="nome_loja"
																	value="<?= (isset($data['configuracoes'][0]['nome_loja'])) ? $data['configuracoes'][0]['nome_loja'] : '' ?>"
																	class="form-control" placeholder="Nome da Loja Virtual">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Link
																da Loja</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="url_loja"
																	value="<?= (isset($data['configuracoes'][0]['url_loja'])) ? $data['configuracoes'][0]['url_loja'] : '' ?>"
																	class="form-control" placeholder="Link da Loja Virtual">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail
																para Contato da Loja</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="email_contato_loja"
																	value="<?= (isset($data['configuracoes'][0]['email_contato_loja'])) ? $data['configuracoes'][0]['email_contato_loja'] : '' ?>"
																	class="form-control"
																	placeholder="E-mail para Contato da Loja (loja@loja.com.br)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Telefone
																para Contato da Loja</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="telefone_contato_loja"
																	value="<?= (isset($data['configuracoes'][0]['telefone_contato_loja'])) ? $data['configuracoes'][0]['telefone_contato_loja'] : '' ?>"
																	class="form-control"
																	placeholder="Telefone para Contato da Loja (00 0000-0000)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Tag
																Descripion</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="tag_description"
																	value="<?= (isset($data['configuracoes'][0]['tag_description'])) ? $data['configuracoes'][0]['tag_description'] : '' ?>"
																	class="form-control" placeholder="Tag Description">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Tag
																Keywords</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="tag_keywords"
																	value="<?= (isset($data['configuracoes'][0]['tag_keywords'])) ? $data['configuracoes'][0]['tag_keywords'] : '' ?>"
																	class="form-control"
																	placeholder="Palavras Chaves da Loja (Separado por Vírgula)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Tag
																Cor Loja</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cor_loja"
																	value="<?= (isset($data['configuracoes'][0]['cor_loja'])) ? $data['configuracoes'][0]['cor_loja'] : '' ?>"
																	class="form-control" placeholder="Ex.: #000000">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome
																Logo</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="nome_logo"
																	value="<?= (isset($data['configuracoes'][0]['nome_logo'])) ? $data['configuracoes'][0]['nome_logo'] : '' ?>"
																	class="form-control"
																	placeholder="Nome da Logo Exemplo: (exemplo_01.png | Padrão 450 x 180)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome
																Logo Mobile</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="nome_logo_mobile"
																	value="<?= (isset($data['configuracoes'][0]['nome_logo_mobile'])) ? $data['configuracoes'][0]['nome_logo_mobile'] : '' ?>"
																	class="form-control"
																	placeholder="Nome da Logo Exemplo: (exemplo_01.png | Padrão 175 x 50)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Valor
																Mínimo para Frete Grátis</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="valor_minimo_para_frete_gratis"
																	value="<?= (isset($data['configuracoes'][0]['valor_minimo_para_frete_gratis'])) ? $data['configuracoes'][0]['valor_minimo_para_frete_gratis'] : '' ?>"
																	class="form-control" placeholder="R$ 350,00">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Percentual
																Desconto</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="percentual_desconto_cupom"
																	value="<?= (isset($data['configuracoes'][0]['percentual_desconto_cupom'])) ? $data['configuracoes'][0]['percentual_desconto_cupom'] : '' ?>"
																	class="form-control" placeholder="5">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Cupom
																de Desconto</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cupom"
																	value="<?= (isset($data['configuracoes'][0]['cupom'])) ? $data['configuracoes'][0]['cupom'] : '' ?>"
																	class="form-control" placeholder="MEUCUPOM">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
																<button type="submit" class="btn btn-primary">FECHAR</button>
																<button type="submit" class="btn btn-success">ATUALIZAR</button>
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
	<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
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
	<script>
      $(document).ready(function() {
        $('#birthday').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });

		$("input[name='valor_minimo_para_frete_gratis']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
      });
    </script>
	<!-- /bootstrap-daterangepicker -->

	<!-- bootstrap-wysiwyg -->
	<script>
      $(document).ready(function() {
        function initToolbarBootstrapBindings() {
          var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
              'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
              'Times New Roman', 'Verdana'
            ],
            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
          $.each(fonts, function(idx, fontName) {
            fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
          });
          $('a[title]').tooltip({
            container: 'body'
          });
          $('.dropdown-menu input').click(function() {
              return false;
            })
            .change(function() {
              $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
            })
            .keydown('esc', function() {
              this.value = '';
              $(this).change();
            });

          $('[data-role=magic-overlay]').each(function() {
            var overlay = $(this),
              target = $(overlay.data('target'));
            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
          });

          if ("onwebkitspeechchange" in document.createElement("input")) {
            var editorOffset = $('#editor').offset();

            $('.voiceBtn').css('position', 'absolute').offset({
              top: editorOffset.top,
              left: editorOffset.left + $('#editor').innerWidth() - 35
            });
          } else {
            $('.voiceBtn').hide();
          }
        }

        function showErrorAlert(reason, detail) {
          var msg = '';
          if (reason === 'unsupported-file-type') {
            msg = "Unsupported format " + detail;
          } else {
            console.log("error uploading file", reason, detail);
          }
          $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        }

        initToolbarBootstrapBindings();

        $('#editor').wysiwyg({
          fileUploadError: showErrorAlert
        });

        window.prettyPrint;
        prettyPrint();
      });
    </script>
	<!-- /bootstrap-wysiwyg -->

	<!-- Select2 -->
	<script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Select a state",
          allowClear: true
        });
        $(".select2_group").select2({});
        $(".select2_multiple").select2({
          maximumSelectionLength: 4,
          placeholder: "With Max Selection limit 4",
          allowClear: true
        });
      });
    </script>
	<!-- /Select2 -->

	<!-- jQuery Tags Input -->
	<script>
      function onAddTag(tag) {
        alert("Added a tag: " + tag);
      }

      function onRemoveTag(tag) {
        alert("Removed a tag: " + tag);
      }

      function onChangeTag(input, tag) {
        alert("Changed a tag: " + tag);
      }

      $(document).ready(function() {
        $('#tags_1').tagsInput({
          width: 'auto'
        });
      });
    </script>
	<!-- /jQuery Tags Input -->

	<!-- Parsley -->
	<script>
      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form .btn').on('click', function() {
          $('#demo-form').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });

      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#demo-form2 .btn').on('click', function() {
          $('#demo-form2').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#demo-form2').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}
    </script>
	<!-- /Parsley -->

	<!-- Autosize -->
	<script>
      $(document).ready(function() {
        autosize($('.resizable_textarea'));
      });
    </script>
	<!-- /Autosize -->

	<!-- jQuery autocomplete -->
	<script>
      $(document).ready(function() {
        var countries = { AD:"Andorra",A2:"Andorra Test",AE:"United Arab Emirates",AF:"Afghanistan",AG:"Antigua and Barbuda",AI:"Anguilla",AL:"Albania",AM:"Armenia",AN:"Netherlands Antilles",AO:"Angola",AQ:"Antarctica",AR:"Argentina",AS:"American Samoa",AT:"Austria",AU:"Australia",AW:"Aruba",AX:"Åland Islands",AZ:"Azerbaijan",BA:"Bosnia and Herzegovina",BB:"Barbados",BD:"Bangladesh",BE:"Belgium",BF:"Burkina Faso",BG:"Bulgaria",BH:"Bahrain",BI:"Burundi",BJ:"Benin",BL:"Saint Barthélemy",BM:"Bermuda",BN:"Brunei",BO:"Bolivia",BQ:"British Antarctic Territory",BR:"Brazil",BS:"Bahamas",BT:"Bhutan",BV:"Bouvet Island",BW:"Botswana",BY:"Belarus",BZ:"Belize",CA:"Canada",CC:"Cocos [Keeling] Islands",CD:"Congo - Kinshasa",CF:"Central African Republic",CG:"Congo - Brazzaville",CH:"Switzerland",CI:"Côte d’Ivoire",CK:"Cook Islands",CL:"Chile",CM:"Cameroon",CN:"China",CO:"Colombia",CR:"Costa Rica",CS:"Serbia and Montenegro",CT:"Canton and Enderbury Islands",CU:"Cuba",CV:"Cape Verde",CX:"Christmas Island",CY:"Cyprus",CZ:"Czech Republic",DD:"East Germany",DE:"Germany",DJ:"Djibouti",DK:"Denmark",DM:"Dominica",DO:"Dominican Republic",DZ:"Algeria",EC:"Ecuador",EE:"Estonia",EG:"Egypt",EH:"Western Sahara",ER:"Eritrea",ES:"Spain",ET:"Ethiopia",FI:"Finland",FJ:"Fiji",FK:"Falkland Islands",FM:"Micronesia",FO:"Faroe Islands",FQ:"French Southern and Antarctic Territories",FR:"France",FX:"Metropolitan France",GA:"Gabon",GB:"United Kingdom",GD:"Grenada",GE:"Georgia",GF:"French Guiana",GG:"Guernsey",GH:"Ghana",GI:"Gibraltar",GL:"Greenland",GM:"Gambia",GN:"Guinea",GP:"Guadeloupe",GQ:"Equatorial Guinea",GR:"Greece",GS:"South Georgia and the South Sandwich Islands",GT:"Guatemala",GU:"Guam",GW:"Guinea-Bissau",GY:"Guyana",HK:"Hong Kong SAR China",HM:"Heard Island and McDonald Islands",HN:"Honduras",HR:"Croatia",HT:"Haiti",HU:"Hungary",ID:"Indonesia",IE:"Ireland",IL:"Israel",IM:"Isle of Man",IN:"India",IO:"British Indian Ocean Territory",IQ:"Iraq",IR:"Iran",IS:"Iceland",IT:"Italy",JE:"Jersey",JM:"Jamaica",JO:"Jordan",JP:"Japan",JT:"Johnston Island",KE:"Kenya",KG:"Kyrgyzstan",KH:"Cambodia",KI:"Kiribati",KM:"Comoros",KN:"Saint Kitts and Nevis",KP:"North Korea",KR:"South Korea",KW:"Kuwait",KY:"Cayman Islands",KZ:"Kazakhstan",LA:"Laos",LB:"Lebanon",LC:"Saint Lucia",LI:"Liechtenstein",LK:"Sri Lanka",LR:"Liberia",LS:"Lesotho",LT:"Lithuania",LU:"Luxembourg",LV:"Latvia",LY:"Libya",MA:"Morocco",MC:"Monaco",MD:"Moldova",ME:"Montenegro",MF:"Saint Martin",MG:"Madagascar",MH:"Marshall Islands",MI:"Midway Islands",MK:"Macedonia",ML:"Mali",MM:"Myanmar [Burma]",MN:"Mongolia",MO:"Macau SAR China",MP:"Northern Mariana Islands",MQ:"Martinique",MR:"Mauritania",MS:"Montserrat",MT:"Malta",MU:"Mauritius",MV:"Maldives",MW:"Malawi",MX:"Mexico",MY:"Malaysia",MZ:"Mozambique",NA:"Namibia",NC:"New Caledonia",NE:"Niger",NF:"Norfolk Island",NG:"Nigeria",NI:"Nicaragua",NL:"Netherlands",NO:"Norway",NP:"Nepal",NQ:"Dronning Maud Land",NR:"Nauru",NT:"Neutral Zone",NU:"Niue",NZ:"New Zealand",OM:"Oman",PA:"Panama",PC:"Pacific Islands Trust Territory",PE:"Peru",PF:"French Polynesia",PG:"Papua New Guinea",PH:"Philippines",PK:"Pakistan",PL:"Poland",PM:"Saint Pierre and Miquelon",PN:"Pitcairn Islands",PR:"Puerto Rico",PS:"Palestinian Territories",PT:"Portugal",PU:"U.S. Miscellaneous Pacific Islands",PW:"Palau",PY:"Paraguay",PZ:"Panama Canal Zone",QA:"Qatar",RE:"Réunion",RO:"Romania",RS:"Serbia",RU:"Russia",RW:"Rwanda",SA:"Saudi Arabia",SB:"Solomon Islands",SC:"Seychelles",SD:"Sudan",SE:"Sweden",SG:"Singapore",SH:"Saint Helena",SI:"Slovenia",SJ:"Svalbard and Jan Mayen",SK:"Slovakia",SL:"Sierra Leone",SM:"San Marino",SN:"Senegal",SO:"Somalia",SR:"Suriname",ST:"São Tomé and Príncipe",SU:"Union of Soviet Socialist Republics",SV:"El Salvador",SY:"Syria",SZ:"Swaziland",TC:"Turks and Caicos Islands",TD:"Chad",TF:"French Southern Territories",TG:"Togo",TH:"Thailand",TJ:"Tajikistan",TK:"Tokelau",TL:"Timor-Leste",TM:"Turkmenistan",TN:"Tunisia",TO:"Tonga",TR:"Turkey",TT:"Trinidad and Tobago",TV:"Tuvalu",TW:"Taiwan",TZ:"Tanzania",UA:"Ukraine",UG:"Uganda",UM:"U.S. Minor Outlying Islands",US:"United States",UY:"Uruguay",UZ:"Uzbekistan",VA:"Vatican City",VC:"Saint Vincent and the Grenadines",VD:"North Vietnam",VE:"Venezuela",VG:"British Virgin Islands",VI:"U.S. Virgin Islands",VN:"Vietnam",VU:"Vanuatu",WF:"Wallis and Futuna",WK:"Wake Island",WS:"Samoa",YD:"People's Democratic Republic of Yemen",YE:"Yemen",YT:"Mayotte",ZA:"South Africa",ZM:"Zambia",ZW:"Zimbabwe",ZZ:"Unknown or Invalid Region" };

        var countriesArray = $.map(countries, function(value, key) {
          return {
            value: value,
            data: key
          };
        });

        // initialize autocomplete with custom appendTo
        $('#autocomplete-custom-append').autocomplete({
          lookup: countriesArray,
          appendTo: '#autocomplete-container'
        });
      });
    </script>
	<!-- /jQuery autocomplete -->

	<!-- Starrr -->
	<script>
      $(document).ready(function() {
        $(".stars").starrr();

        $('.stars-existing').starrr({
          rating: 4
        });

        $('.stars').on('starrr:change', function (e, value) {
          $('.stars-count').html(value);
        });

        $('.stars-existing').on('starrr:change', function (e, value) {
          $('.stars-count-existing').html(value);
        });
      });
    </script>
	<!-- /Starrr -->
</body>
</html>
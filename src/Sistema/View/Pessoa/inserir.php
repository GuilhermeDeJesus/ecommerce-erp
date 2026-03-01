<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Adicionar Colaborador</title>

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
							<h3>Adicionar colaborador</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2>Informações Gerais</h2>
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
												data-toggle="tab" aria-expanded="true">Dados Cadastrais</a></li>
										</ul>
										<form action="?m=sistema&c=pessoa&a=cadastrar" method="post"
											enctype="multipart/form-data"
											class="form-horizontal form-label-left">
											<div id="myTabContent" class="tab-content">
												<br>
												<div role="tabpanel" class="tab-pane fade active in"
													id="tab_content1" aria-labelledby="home-tab">
													<input type="hidden" name="id"
														value="<?= (isset($data['pessoa'][0]['id'])) ? $data['pessoa'][0]['id'] : '' ?>" />
													<div class="row">
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Classe</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select class="form-control" name="id_classe"
																	required="required">
                                        							<?php foreach ($data['classes'] as $pval => $pkey) { ?>
                                        							<option
																		value="<?=$pkey['id']?>"><?=$pkey['nome']?></option>
                                        							<?php } ?>
                                        						</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<select class="form-control" name="tipo"
																	required="required">
																	<option value="PF">Pessoa Física</option>
																	<option value="PJ">Pessoa Jurídica</option>
																</select>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Nome</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="nome"
																	value="<?= (isset($data['pessoa'][0]['nome'])) ? $data['pessoa'][0]['nome'] : '' ?>"
																	class="form-control" placeholder="Nome Fantasia">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">CPF</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cpf"
																	value="<?= (isset($data['pessoa'][0]['cpf'])) ? $data['pessoa'][0]['cpf'] : '' ?>"
																	class="form-control" placeholder="CPF">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">CNPJ</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cnpj"
																	value="<?= (isset($data['pessoa'][0]['cnpj'])) ? $data['pessoa'][0]['cnpj'] : '' ?>"
																	class="form-control" placeholder="CNPJ">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">CEP</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cep"
																	value="<?= (isset($data['pessoa'][0]['cep'])) ? $data['pessoa'][0]['cep'] : '' ?>"
																	class="form-control"
																	placeholder="CEP (CEP de origem do frete)">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Endereço</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="endereco"
																	value="<?= (isset($data['pessoa'][0]['endereco'])) ? $data['pessoa'][0]['endereco'] : '' ?>"
																	class="form-control"
																	placeholder="">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Cidade</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="cidade"
																	value="<?= (isset($data['pessoa'][0]['cidade'])) ? $data['pessoa'][0]['cidade'] : '' ?>"
																	class="form-control"
																	placeholder="">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Bairro</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="bairro"
																	value="<?= (isset($data['pessoa'][0]['bairro'])) ? $data['pessoa'][0]['bairro'] : '' ?>"
																	class="form-control"
																	placeholder="">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">UF</label>
															<div class="col-md-1 col-sm-1 col-xs-12">
																<input type="text" name="uf"
																	value="<?= (isset($data['pessoa'][0]['uf'])) ? $data['pessoa'][0]['uf'] : '' ?>"
																	class="form-control"
																	placeholder="">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Número</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="numero"
																	value="<?= (isset($data['pessoa'][0]['numero'])) ? $data['pessoa'][0]['numero'] : '' ?>"
																	class="form-control"
																	placeholder="">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Site</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="site"
																	value="<?= (isset($data['pessoa'][0]['site'])) ? $data['pessoa'][0]['site'] : '' ?>"
																	class="form-control" placeholder="Site">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Celular</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="celular"
																	value="<?= (isset($data['pessoa'][0]['celular'])) ? $data['pessoa'][0]['celular'] : '' ?>"
																	class="form-control" placeholder="(00) 0000-0000">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Telefone</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="text" name="telefone"
																	value="<?= (isset($data['pessoa'][0]['telefone'])) ? $data['pessoa'][0]['telefone'] : '' ?>"
																	class="form-control" placeholder="(00) 0000-0000">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Data
																Nascimento</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="date" name="data_nascimento"
																	value="<?= (isset($data['pessoa'][0]['data_nascimento'])) ? $data['pessoa'][0]['data_nascimento'] : '' ?>"
																	class="form-control" placeholder="Data Nascimento">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="email" name="email"
																	value="<?= (isset($data['pessoa'][0]['email'])) ? $data['pessoa'][0]['email'] : '' ?>"
																	class="form-control" placeholder="E-mail">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Senha</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<input type="password" name="senha"
																	value="<?= (isset($data['pessoa'][0]['senha'])) ? $data['pessoa'][0]['senha'] : '' ?>"
																	class="form-control" placeholder="Senha para login">
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12">Observação</label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<textarea class="form-control" name="observacao"
																	rows="5" required="required"><?= (isset($data['pessoa'][0]['observacao'])) ? $data['pessoa'][0]['observacao'] : '' ?>
                                        						</textarea>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
															<div class="col-md-9 col-sm-9 col-xs-12">
																<div class="">
																	<label> <input type="checkbox" class="js-switch"
																		name="ativo" checked /> Ativo
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-8 col-sm-12 col-xs-12 form-group">
															<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
																<button type="submit" class="btn btn-primary">FECHAR</button>
																<button type="submit" class="btn btn-success">CONFIRMAR</button>
															</div>
														</div>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="tab_content2"
													aria-labelledby="profile-tab">
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															1</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_1">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															2</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_2">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															3</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_3">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															4</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_4">
															</div>
														</div>
													</div>
													<div class="col-md-8 col-sm-12 col-xs-12 form-group">
														<label class="control-label col-md-3 col-sm-3 col-xs-12">Arquivo
															5</label>
														<div class="col-md-9 col-sm-9 col-xs-12">
															<div class="">
																<input type="file" class="form-control-file"
																	name="arquivo_5">
															</div>
														</div>
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
	<!-- bootstrap-progressbar -->
	<script
		src="public/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
	<!-- iCheck -->
	<script src="public/admin/vendors/iCheck/icheck.min.js"></script>
	<!-- bootstrap-daterangepicker -->
	<script src="public/admin/js/moment/moment.min.js"></script>
	<script src="public/admin/js/datepicker/daterangepicker.js"></script>
	<!-- bootstrap-wysiwyg -->
	<script
		src="public/admin/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
	<script src="public/admin/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
	<script src="public/admin/vendors/google-code-prettify/src/prettify.js"></script>
	<!-- jQuery Tags Input -->
	<script
		src="public/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
	<!-- Switchery -->
	<script src="public/admin/vendors/switchery/dist/switchery.min.js"></script>
	<!-- Select2 -->
	<script src="public/admin/vendors/select2/dist/js/select2.full.min.js"></script>
	<!-- Parsley -->
	<script src="public/admin/vendors/parsleyjs/dist/parsley.min.js"></script>
	<!-- Autosize -->
	<script src="public/admin/vendors/autosize/dist/autosize.min.js"></script>
	<!-- jQuery autocomplete -->
	<script
		src="public/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
	<!-- starrr -->
	<script src="public/admin/vendors/starrr/dist/starrr.js"></script>
	<!-- Custom Theme Scripts -->
	<script src="public/admin/build/js/custom.min.js"></script>
	<script src="public/admin/vendors/dropzone/dist/min/dropzone.min.js"></script>
	<script
		src="public/admin/vendors/jquery.inputmask/dist/inputmask/jquery.maskMoney.min.js"></script>
	<script>
		$("input[name='valor_venda']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='valor_compra']").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='lucro']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='peso_bruto']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='peso_liquido']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		$("input[name='reducao_iva_st']").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    </script>

	<!-- bootstrap-daterangepicker -->
	<script>
      $(document).ready(function() {
        $('#birthday').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
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
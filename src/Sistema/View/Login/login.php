<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login</title>

<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="https://colorlib.com/polygon/gentelella/css/animate.min.css"
	rel="stylesheet">
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
	<div>
		<a class="hiddenanchor" id="signup"></a> <a class="hiddenanchor"
			id="signin"></a>
		<div class="login_wrapper">
			<div class="animate form login_form">
				<section class="login_content">
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
					<form action="?m=sistema&c=login&a=logar" method="post">
						<h1>Login</h1>
						<div>
							<input type="text" name="email" class="form-control"
								placeholder="E-mail" required="required" />
						</div>
						<div>
							<input type="password" name="senha" class="form-control"
								placeholder="Senha" required="required" />
						</div>
						<div>
							<button type="submit" class="btn btn-success">Prosseguir</button>
							<a class="reset_pass" href="#">Esqueceu sua senha?</a>
						</div>

						<div class="clearfix"></div>

						<div class="separator">
							<div class="clearfix"></div>
							<br />
							<div>
								<h1>
									<?=NOME_LOJA;?>
								</h1>
								<p>© <?=date('Y');?> Todos os direitos reservados</p>
							</div>
						</div>
					</form>
				</section>
			</div>
		</div>
	</div>
</body>
</html>
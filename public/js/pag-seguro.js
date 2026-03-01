var _sessionId = null;

function sessaoPagSeguro() {
	$.ajax({
		url : '?m=pagamento&c=pagamento&a=iniciaPagamento',
		type : 'post',
		dataTyp : 'json',
		async : false,
		timeout : 20000,
		success : function(data) {
			$("#sessionId").val(data);
			$("#sessionId_boleto").val(data);
			_sessionId = data;
			PagSeguroDirectPayment.setSessionId(data);
		}
	});
}

//Pagar com Boleto Bancário
$("#pagarBoleto").click(function() {
	$('#load-img').css('display', 'inline-block');
	$('body').css("opacity", "0.5");
	
	if($('#cpf_boleto').val() !== '' && $("#cpf_boleto").val().length < 11){
		
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>CPF INVÁLIDO!</p>');
		
	}else if($("#cpf_boleto").val() != '') {
		identificador = PagSeguroDirectPayment.getSenderHash();
		$("#hash").val(identificador);
		
		setTimeout(function() {
			var response = $.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=checkout&c=checkout&a=finalizarPedido",
				data : {
					"data" : JSON.stringify($('#pagar-boleto').serializeArray()),
				},
				success : function(response) {
					var json = JSON.parse(response);
					
					fbq('track', 'Purchase', {
						value: json.total,
						currency: 'BRL'
					});
					
					$('#codigo_boleto').html(json.code);
					
					$('#link-boleto').html('<br><a href="'+ json.paymentLink+ '" style="color: #FF8C00;" target="new"><b>ABRIR BOLETO</b></a>');

					$('#status-compra-boleto').css('display', 'table');
					$('#load-img').css('display', 'none');
					$('body').css("opacity", "1");
					$('#final-car').css('display', 'none');
				},
			});
		}, 100);		
	}else{
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha o campo CPF de Cobrança!</p>');
	}
});

// Pagar com cartão de Crédito
$("#pagarCartao").click(function() {
	$('#load-img').css('display', 'inline-block');
	$('body').css("opacity", "0.5");
    
    if($('#NumeroCartao').val() !== '' && $("#NumeroCartao").val().length < 19){
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
	}else if($('#cvv').val() !== '' && $("#cvv").val().length < 3){
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
	}else if($('#NumeroCartao').val() !== '' && $('#mes_validade').val() !== '' && $('#ano_validate').val() !== '' && $('#cvv').val() !== '' && $("#titular").val() != '') {
		identificador = PagSeguroDirectPayment.getSenderHash();
		$("#hashPagSeguro").val(identificador);
		
		numCartao = $("#NumeroCartao").val();
		cvvCartao = $("#cvv").val();
		expiracaoMes = $("#mes_validade").val();
		expiracaoAno = $("#ano_validate").val();
		bandeira = $("#bandeira_cartao").val();
		
	    $('#pagarCartao').prop('disabled', true);
		
		var param = {
			cardNumber : numCartao.split('-').join(''),
			brand : bandeira,
			cvv : cvvCartao,
			expirationMonth : expiracaoMes,
			expirationYear : expiracaoAno,
			success : function(response) {
				$("#tokenPagamentoCartao").val(response['card']['token']);
				
				setTimeout(function() {
					$.ajax({
						type : 'POST',
						dataType : "text",
						async : false,
						url : "?m=checkout&c=checkout&a=finalizarPedido",
						data : {
							"data" : JSON.stringify($('#pagar-cartao').serializeArray()),
						},
						success : function(response) {
							var json = JSON.parse(response);

							if(json.success){
								setTimeout(function() {
									$.ajax({
										type : 'POST',
										dataType : "text",
										async : false,
										url : "?m=pagamento&c=pagamento&a=result",
										data : {
											"pedido" : json.pedido,
										},
										success : function(response) {
											var jsonResult = JSON.parse(response);
											$('#load-img').css('display', 'none');
											$('body').css("opacity", "1");
											
											if(jsonResult.status == 7 || jsonResult.status == "7"){
												$('#pgs-reprovado').css('display', 'table');
												$('#pgs-aprovado').css('display', 'none');
												$('#pgs-aguardando').css('display', 'none');
											}
											
											if(jsonResult.status == 3  || jsonResult.status == "3"){
												$('#pgs-aprovado').css('display', 'table');
												$('#pgs-reprovado').css('display', 'none');
												$('#pgs-aguardando').css('display', 'none');
												
												fbq('track', 'Purchase', {
													value: jsonResult.total,
													currency: 'BRL'
												});
											}
											
											if(jsonResult.status == 1  || jsonResult.status == "1" || jsonResult.status == 2  || jsonResult.status == "2"){
												$('#pgs-aguardando').css('display', 'table');
												$('#pgs-reprovado').css('display', 'none');
												$('#pgs-aprovado').css('display', 'none');
												
												fbq('track', 'Purchase', {
													value: jsonResult.total,
													currency: 'BRL'
												});
											}
										}
									});
								}, 7000);
							}else{
							    $('#pagarCartao').prop('disabled', false);
								(json.code != null) ? $('#codigo').html(json.code) : null;
								$('#situacao-pagamento').html(json.situacao_pagamento);
								$('#parcela-pagamento').html(json.parcela);
								$('#forma-pagamento')
								.append(
										'<br><img style="margin-left: -10px;" src="public/img/'
												+ json.bandeira
												+ '.png" width="34" height="34" alt="Você Sempre Bela" title="Você Sempre Bela" />');

								$('#status-compra').css('display', 'table');
								$('#load-img').css('display', 'none');
								$('body').css("opacity", "1");
								
								if(json.error == false){
									$('#final-car').css('display', 'none');
									$('#div-cartao').css('display', 'none');
									
									// Clean inputs
									$("#NumeroCartao").val();
									$("#cvv").val('');
									$("#mes_validade").val('');
									$("#ano_validate").val('');
									$("#titular").val('');
								}
							}
						},
					});
				}, 100);
			},
		};

		PagSeguroDirectPayment.createCardToken(param);
		
	}else{
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
	}
});

var carregarBandeira = function(cardBin) {
	cardBin = cardBin.split(' ').join('');
	PagSeguroDirectPayment
			.getBrand({
				cardBin : cardBin,
				success : function(response) {
					$brand = response.brand;
					brandLoaded = true;
					card = cardBin.toString().substring(0, 6);
					if ($('.bandeira').html() == '') {
						$("#bandeira_cartao").val($brand.name);
						$('.bandeira')
								.append(
										'<br><img style="margin-left: -10px;" src="public/img/'
												+ $brand.name
												+ '.png" width="34" height="34" alt="Você Sempre Bela" title="Você Sempre Bela" />');
					}
				},
				error : function(response) {
					console.log(response);
				}
			});
};

function numberToReal(numero) {
    var numero = numero.toFixed(2).split('.');
    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

//Generate token card crédit
$("#NumeroCartao").keyup(function() {
	var totalPagar = document.getElementById("_total_cart").innerHTML.replace('R$', '').replace('.', '');
	totalPagar = parseFloat(totalPagar.replace(',', '.'));
	numCartao = $("#NumeroCartao").val();
	carregarBandeira(numCartao.split('-').join(''));
	if($("#NumeroCartao").val().length > 10 && $("#parcela option").length == 0){
		PagSeguroDirectPayment.getInstallments({
			amount : totalPagar,
//			maxInstallmentNoInterest : 6,
			brand: $("#bandeira_cartao").val(),
			success : function(response) {
				var _parcelamento = response;
				var _parcelas = _parcelamento.installments[$("#bandeira_cartao").val()];
				var options = "";
				for (var key in _parcelas) {   
					if (_parcelas.hasOwnProperty(key)) {
						if(key < 6){
							options += "<option value='" + _parcelas[key].quantity+"x"+_parcelas[key].installmentAmount + "'>" + _parcelas[key].quantity + "x de R$ " + numberToReal(_parcelas[key].installmentAmount) + "</option>";
						}
					}
				}
								
				document.getElementById("parcela").innerHTML = options;
			},
			error : function(response) {
				console.log(response);
			}
		});
	}
});

// Generate token card crédit
$("#cvv").keyup(function() {
	numCartao = $("#NumeroCartao").val();
	cvvCartao = $("#cvv").val();
	expiracaoMes = $("#mes_validade").val();
	expiracaoAno = $("#ano_validate").val();

	var param = {
		cardNumber : numCartao.split('-').join(''),
		brand : $("#bandeira_cartao").val(),
		cvv : cvvCartao,
		expirationMonth : expiracaoMes,
		expirationYear : expiracaoAno,
		success : function(response) {
			$("#tokenPagamentoCartao").val(response['card']['token']);
		},
	};

	PagSeguroDirectPayment.createCardToken(param);
});

function openOpcaoPag(evt, cityName) {
	  var i, x, tablinks;
	  x = document.getElementsByClassName("cartao");
	  for (i = 0; i < x.length; i++) {
	     x[i].style.display = "none";
	  }
	  tablinks = document.getElementsByClassName("tablink");
	  for (i = 0; i < x.length; i++) {
	     tablinks[i].className = tablinks[i].className.replace(" w3-border-orange", "");
	  }
	  document.getElementById(cityName).style.display = "block";
	  evt.currentTarget.firstElementChild.className += " w3-border-orange";
	}

	addEventListener("load", function () {
		setTimeout(hideURLbar, 0);
	}, false);

	function hideURLbar() {
		window.scrollTo(0, 1);
}
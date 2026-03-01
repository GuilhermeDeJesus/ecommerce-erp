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

function _realizarCobranca() {
	
	$('#load-img').css('display', 'inline-block');
	$('body').css("opacity", "0.5");
    
    if($('#cardNumber').val() !== '' && $("#cardNumber").val().length < 16){
    	
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
		
	}else if($('#securityCode').val() !== '' && $("#securityCode").val().length < 3){
		
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
		
	}else if($('#cardNumber').val() !== '' && $('#cardExpirationMonth').val() !== '' && $('#cardExpirationYear').val() !== '' && $('#securityCode').val() !== '' && $("#cardholderName").val() != '') {
		
		numCartao = $("#cardNumber").val();
		cvvCartao = $("#securityCode").val();
		expiracaoMes = $("#cardExpirationMonth").val();
		expiracaoAno = $("#cardExpirationYear").val();
		
	    $('#pagarCartao').prop('disabled', true);
		
	    setTimeout(function() {
			$.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=pagamento&c=pagamentoMP&a=checkoutTransparente",
				data : {
					"data" : JSON.stringify($('#pagar-cartao').serializeArray()),
				},
				success : function(response) {
					var json = JSON.parse(response);

					if(json.success){
						
						$('#pgs-aprovado').css('display', 'table');
						$('#pgs-reprovado').css('display', 'none');
						$('#pgs-aguardando').css('display', 'none');
						
						$('#load-img').css('display', 'none');
						$('body').css("opacity", "1");
					    $('#pagarCartao').prop('disabled', true);
						$('#div-cartao').css('display', 'none');

						fbq('track', 'Purchase', {
							value: jsonResult.total,
							currency: 'BRL'
						});
						
					}else{
						
						$('#pgs-aprovado').css('display', 'none');
						$('#pgs-reprovado').css('display', 'table');
						$('#pgs-aguardando').css('display', 'none');
						$('#load-img').css('display', 'none');
						$('body').css("opacity", "1");
					    $('#pagarCartao').prop('disabled', false);
					    $('#situacao-pagamento-recusado').html(json.situacao_pagamento);


						if(json.error == false){
							$('#final-car').css('display', 'none');
							
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

	}else{
		
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
	}
}

function numberToReal(numero) {
    var numero = numero.toFixed(2).split('.');
    numero[0] = numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

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
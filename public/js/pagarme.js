// Pagar com cartão de Crédito
$("#pagarCartaoComRede").click(function() {
	setTimeout(function() {
		if($('#NumeroCartao').val() !== '' && $("#NumeroCartao").val().length < 19){
			$('#load-img').css('display', 'none');
			$('body').css("opacity", "1");
			$('#alerta-cartao-c').modal("show");
			$('#alert-cartao').append('<p>Número do cartão inválido!</p>');
		}else if($('#cvv').val() !== '' && $("#cvv").val().length < 3){
			$('#load-img').css('display', 'none');
			$('body').css("opacity", "1");
			$('#alerta-cartao-c').modal("show");
			$('#alert-cartao').append('<p>CVV inválido!</p>');
		}else if($('#NumeroCartao').val() !== '' && $('#mes_validade').val() !== '' && $('#ano_validate').val() !== '' && $('#cvv').val() !== '' && $("#titular").val() != '') {
			
			numCartao = $("#NumeroCartao").val();
			cvvCartao = $("#cvv").val();
			expiracaoMes = $("#mes_validade").val();
			expiracaoAno = $("#ano_validate").val();
			bandeira = $("#bandeira_cartao").val();
			
			$.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=pagamento&c=pagamentoPagarme&a=pay_cred_card",
				data : {
					"data" : JSON.stringify($('#pagar-cartao-rede').serializeArray()),
				},
				success : function(response) {
					
					var json = JSON.parse(response);
					$('#codigo').html(json.code);
					$('#situacao-pagamento').html(json.situacao_pagamento);
					$('#parcela-pagamento').html(json.parcela);
					$('#forma-pagamento').html(json.forma_pagamento);

					$('#status-compra').css('display', 'table');
					$('#load-img').css('display', 'none');
					$('body').css("opacity", "1");
					
					if(json.success){
						$('#div-cartao').css('display', 'none');
						  fbq('track', 'Purchase', {
							     value: json.total,
							     currency: 'BRL'
						  });
						  
						// Clean inputs
						$("#NumeroCartao").val('');
						$("#cvv").val('');
						$("#mes_validade").val('');
						$("#ano_validate").val('');
						$("#titular").val('');						  
					}
				},
			});
		}else{
			$('#load-img').css('display', 'none');
			$('body').css("opacity", "1");
			$('#alerta-cartao-c').modal("show");
			$('#alert-cartao').html('<p>Preencha os campos corretamente!</p>');
		}
		
	}, 2000);		
});

$("#pagarBoleto").click(function() {
	$('#load-img').css('display', 'inline-block');
	$('body').css("opacity", "0.5");
	
	if($('#cpf_boleto').val() !== '' && $("#cpf_boleto").val().length < 11){
		
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>CPF INVÁLIDO!</p>');
		
	}else if($("#cpf_boleto").val() != '') {

		setTimeout(function() {
			var response = $.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=pagamento&c=pagamentoPagarme&a=gerar_boleto",
				data : {
					"data" : JSON.stringify($('#pagar-boleto').serializeArray()),
				},
				success : function(response) {
					var json = JSON.parse(response);
					$('#codigo_boleto').html(json.code);
					
					$('#link-boleto')
					.html(
							'<br><a href="'+ json.paymentLink+ '" style="color: #FF8C00;" target="new"><b>ABRIR BOLETO</b></a>');

					$('#status-compra-boleto').css('display', 'table');
					$('#load-img').css('display', 'none');
					$('body').css("opacity", "1");
					$('#final-car').css('display', 'none');
					
					fbq('track', 'Purchase', {
						value: json.total,
						currency: 'BRL'
					});
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

$("#pagarPix").click(function() {
	
	$('#load-img').css('display', 'inline-block');
	$('body').css("opacity", "0.5");
	
	if($('#cpf_boleto').val() !== '' && $("#cpf_boleto").val().length < 11){
		
		$('#load-img').css('display', 'none');
		$('body').css("opacity", "1");
		$('#alerta-cartao-c').modal("show");
		$('#alert-cartao').html('<p>CPF INVÁLIDO!</p>');
		
	}else if($("#cpf_boleto").val() != '') {

		setTimeout(function() {
			var response = $.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=pagamento&c=pagamentoPagarme&a=gerar_pix",
				data : {
					"data" : JSON.stringify($('#pagar-pix').serializeArray()),
				},
				success : function(response) {
					var json = JSON.parse(response);
					$('#chave_pix').html(json.pix);
					
					$('#status-compra-pix').css('display', 'table');
					$('#load-img').css('display', 'none');
					$('body').css("opacity", "1");
					$('#final-car').css('display', 'none');
					
					fbq('track', 'Purchase', {
						value: json.total,
						currency: 'BRL'
					});
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
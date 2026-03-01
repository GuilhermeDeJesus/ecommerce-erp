// Pagar com cartão de Crédito
$("#pagarCartaoComRede").click(function() {
	setTimeout(function() {
		if($('#cpf').val() !== '' && $("#cpf").val().length < 11){
			
			$('#load-img').css('display', 'none');
			$('body').css("opacity", "1");
			$('#alerta-cartao-c').modal("show");
			$('#alert-cartao').append('<p>CPF INVÁLIDO!</p>');
			
		}else if($('#cpf').val() !== '' && $('#NumeroCartao').val() !== '' && $('#mes_validade').val() !== '' && $('#ano_validate').val() !== '' && $('#cvv').val() !== '' && $("#titular").val() != '') {
			numCartao = $("#NumeroCartao").val();
			cvvCartao = $("#cvv").val();
			expiracaoMes = $("#mes_validade").val();
			expiracaoAno = $("#ano_validate").val();
			bandeira = $("#bandeira_cartao").val();
			
			$.ajax({
				type : 'POST',
				dataType : "text",
				async : false,
				url : "?m=pagamento&c=pagamentoRede&a=pay_cred_card_rede",
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
					
					// Clean inputs
					$("#NumeroCartao").val('');
					$("#cvv").val('');
					$("#mes_validade").val('');
					$("#ano_validate").val('');
					$("#titular").val('');
					
					if(json.success){
						$('#div-cartao').css('display', 'none');
						  fbq('track', 'Purchase', {
							     value: json.total,
							     currency: 'BRL'
						  });
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
function autocomplete(inp) {
	var currentFocus;
	inp
			.addEventListener(
					"input",
					function(e) {
						var a, b, i, val = this.value;
						closeAllLists();
						if (!val) {
							return false;
						}
						var arr = [];
						$.ajax({
							type : 'POST',
							beforeSend : function() {
							},
							dataType : "text",
							async : false,
							url : "?m=produto&c=produto&a=pesquisar",
							data : {
								"value" : val
							},
							success : function(data) {
								arr = JSON.parse(data);
							},
						});

						currentFocus = -1;
						a = document.createElement("DIV");
						a.setAttribute("id", this.id + "autocomplete-list");
						a.setAttribute("class", "autocomplete-items");
						// Aqui ele completa o que o usuário esta digitando na
						// div
						this.parentNode.appendChild(a);
						for (i = 0; i < arr.length; i++) {
							if (arr[i].substr(0, val.length).toUpperCase() == val
									.toUpperCase()) {
								b = document.createElement("DIV");
								b.innerHTML = arr[i].substr(0, val.length);
								b.innerHTML += arr[i].substr(val.length);
								b.innerHTML += "<input type='hidden' value='"
										+ arr[i] + "' name='busca_produto[]'>";
								b
										.addEventListener(
												"click",
												function(e) {
													inp.value = this
															.getElementsByTagName("input")[0].value;
													closeAllLists();
												});
								a.appendChild(b);
							}
						}
					});
	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x)
			x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
			currentFocus++;
			addActive(x);
		} else if (e.keyCode == 38) { // up
			currentFocus--;
			addActive(x);
		} else if (e.keyCode == 13) {
			e.preventDefault();
			if (currentFocus > -1) {
				if (x)
					x[currentFocus].click();
			}
		}
	});
	function addActive(x) {
		if (!x)
			return false;
		removeActive(x);
		if (currentFocus >= x.length)
			currentFocus = 0;
		if (currentFocus < 0)
			currentFocus = (x.length - 1);
		x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
		for (var i = 0; i < x.length; i++) {
			x[i].classList.remove("autocomplete-active");
		}
	}
	function closeAllLists(elmnt) {
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			}
		}
	}
	document.addEventListener("click", function(e) {
		closeAllLists(e.target);
	});
}

function openNav() {
	document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
	document.getElementById("mySidenav").style.width = "0";
}

window.onload = function () {
	document.getElementById("password1").onchange = validatePassword;
	document.getElementById("password2").onchange = validatePassword;
}

function validatePassword() {
	var pass2 = document.getElementById("password2").value;
	var pass1 = document.getElementById("password1").value;
	if (pass1 != pass2)
		document.getElementById("password2").setCustomValidity("As senhas não conferem");
	else
		document.getElementById("password2").setCustomValidity('');
}

$(document).ready(function () {
	 $('.load-img').css('display', 'none');
	 $('.load-img').hide();
	 $('#btn-fale-conosco').click(function(e) {
	    	$('.load-img').show();
	    	$('.load-img').css('display', 'inline-block');
	 		$('body').css("opacity", "0.5");
	 		
        	e.preventDefault();
	 		setTimeout(function(){ 
	 			if($('#nome').val() != '' && $('#email').val() && $('#mensagem').val() != ''){
	 				$.ajax({
	    				type : 'POST',
	    			 	beforeSend: function(){},
	    				dataType : "text",
	    				async : false,
	    				url : "?m=cliente&c=cliente&a=faleConosco",
	    				data : {
	    					"data" : JSON.stringify($("#fale-conosco").serializeArray()),
	    				},					  
	    				success: function(data){
	    					$('#nome').val('');
	    					$('#email').val('');
	    					$('#telefone').val('');
	    					$('#mensagem').val('');
	    					$('#numero_pedido').val('');
	    					
	    					$('.load-img').css("display", "none");
							$('.load-img').hide();
	                        $('body').css("opacity", "1");
	                        $('#msg-fale').css("display", "table");
	                        document.getElementById('text-fale').innerHTML = "Mensagem Enviado com Sucesso";
	    				},
	    			});
	 			}else{
	 				$('.load-img').css("display", "none");
					$('.load-img').hide();
                    $('body').css("opacity", "1");
	 				$('#msg-fale').css("display", "table");
                    document.getElementById('text-fale').innerHTML = "Preencha o formulário corretamente";
	 			}
	 		}, 100);                                                	
        });
	 
		$('.flexslider').flexslider({
			animation: "slide",
			controlNav: "thumbnails"
		});
		
		$("#flexiselDemo1").flexisel({
			visibleItems: 3,
			animationSpeed: 1000,
			autoPlay: false,
			autoPlaySpeed: 3000,
			pauseOnHover: true,
			enableResponsiveBreakpoints: true,
			responsiveBreakpoints: {
				portrait: {
					changePoint: 480,
					visibleItems: 1
				},
				landscape: {
					changePoint: 640,
					visibleItems: 2
				},
				tablet: {
					changePoint: 768,
					visibleItems: 2
				}
			}
		});
	 
		$('.popup-with-zoom-anim').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
		});
		
		$(".scroll").click(function (event) {
			event.preventDefault();

			$('html,body').animate({
				scrollTop: $(this.hash).offset().top
			}, 1000);
		});
		
		$().UItoTop({
			easingType: 'easeOutQuart'
		});
		
		$("#slider-range").slider({
			range: true,
			min: 0,
			max: 9000,
			values: [50, 6000],
			slide: function (event, ui) {
				$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			}
		});
		$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
		
		$('#frete-input').mask('99.999-999');
		$('#cep_destino').mask('99.999-999');
		$('#cep').mask('99.999-999');
		$('#cep2').mask('99.999-999');
		$('#ano_validade').mask('0000');
		$('#cvv').mask('000');
		$('.cpf').mask('000.000.000-00');
		$('.telefone').mask('00 00000-0000');
		$('#Telefone').mask('(00) 00000-0000');
		$('#data_nascimento').mask('00/00/0000');
		$('.data_nascimento').mask('00/00/0000');
		$('#data_nascimento2').mask('00/00/0000');
		$('.data_nascimento2').mask('00/00/0000');
        $("#NumeroCartao").mask("9999-9999-9999-9999");  
 });

function abrirMenu(id){
	document.getElementsByClassName('content').style = 'opacity: 0.5';
	$("#"+id).toggle();
}

function fecharMenu(id){
	$("#"+id).toggle();
}
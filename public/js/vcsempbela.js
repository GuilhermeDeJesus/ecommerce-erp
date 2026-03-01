	/**
	 * JS DEFAULT
	 */
	function post(form, url, setMessage, setRefresh) {
		var response = $
				.ajax({
					type : 'POST',
					dataType : "text",
					async : false,
					url : "?m=" + url.module + "&c=" + url.controller + "&a="
							+ url.action,
					data : {
						"data" : JSON.stringify($(form).serializeArray()),
					}
				});
	
		// Last one second clean all inputs form
		setInterval(function() {
			Array.prototype.slice.call(document.getElementsByTagName('input'))
					.forEach(function(el) {
						// el.value = '';
					});
	
			// clean checkbox
			var eleNodelist = document.getElementsByTagName("input");
			for (i = 0; i < eleNodelist.length; i++) {
				if (eleNodelist[i].type == 'checkbox')
					if (status == null) {
						eleNodelist[i].checked = !eleNodelist[i].checked;
					} else
						eleNodelist[i].checked = status;
			}
	
		}, 1000);
	
		if (setMessage == true) {
			document.getElementById('msg').innerHTML = response.responseText;
			$('#modal-global').modal('show');
			setInterval(function() {
				$('#modal-global').modal('hide');
				if (setRefresh == true) {
					location.reload();
				}
			}, 1800);
		}
	
		return response.responseText;
	}
	
	//
	// READ RAMAL SYSTEM
	//
	function ruunerVerificandoLigacoes(ramal){
		var file = new XMLHttpRequest();
		file.open("GET", ramal, false);
		file.onreadystatechange = function ()
	    {
	        if(file.readyState === 4)
	        {
	            if(file.status === 200 || file.status == 0)
	            {
	            	response = file.responseText;
        			var timeout = setTimeout(function() {
        			if(typeof(response) == 'object' || response == '' || response == null && Number.isInteger(response) == false){
        				// NADA
        			}else if(typeof(response) != 'object' || response != '' || response != null){
        				var obj = window.open("?m=atendimento&c=atendimento&a=setClienteView&cpf="+response,'popup',"height=900,width=1100,status=yes,toolbar=no,menubar=no,location=no");
        				if(typeof(obj) == 'object'){
        					$.ajax({
            					type : 'POST',
            					dataType : "text",
            					async : false,
            					url : "?m=Atendimento&c=Atendimento&a=cleanRamal"
            				});        					
        				}
        			}
        			ruunerVerificandoLigacoes(ramal);
        			}, 400);
	            }
	        }
	    }
		file.send(null);
	}
	
	function delet(id, url) {
		var response = $
				.ajax({
					type : 'POST',
					dataType : "text",
					async : false,
					url : "/?m=" + url.module + "&c=" + url.controller + "&a="
							+ url.action,
					data : id
				});
	
		document.getElementById('msg').innerHTML = response.responseText;
		$('#modal-global').modal('show');
		setInterval(function() {
			$('#modal-global').modal('hide');
			location.reload();
		}, 1800);
	
		return response;
	}
	
	function openCity(evt, cityName) {
		// Declare all variables
		var i, tabcontent, tablinks;
	
		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "";
		}
	
		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace("active",
					"compras");
		}
	
		// Show the current tab, and add an "active" class to the link that opened
		// the tab
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += "compras";
	}
	
	function getDivError() {
		var divError = "<div id='modal-global' class='modal fade bd-example-modal-sm' tabindex='-1' "
				+ "role='dialog' aria-labelledby='mySmallModalLabel' aria-hidden='true'> "
				+ "<div class='modal-dialog modal-sm'> "
				+ "<div class='modal-conten'> "
				+ "<div style='width: 400px; "
				+ "height:60px; "
				+ "border-radius:5px; "
				+ "text-align: center; "
				+ "background-color: #2E8B57; "
				+ "background: rgba(255, 0, 54, 0.2);'> "
				+ "<h4 style='color: #FFF; margin-top: 100px;'><br />O formulário não pode ficar vazio!</div></h4></div> "
				+ "</div> " + "</div>";
		return divError;
	}
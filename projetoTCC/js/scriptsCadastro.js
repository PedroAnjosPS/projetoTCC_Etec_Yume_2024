$(document).ready(function(){
   
	$('#nome').click(function(){
		if($(this).val()==""){
			  $(this).val('');
		 }//fim do if
	  }//fim da funcao anonima
	  );//fim do click no objeto id=nome
   	
	
	$('#email').click(function(){
	  if($(this).val()==""){
			$(this).val('');
	   }//fim do if
	}//fim da funcao anonima
	);//fim do click no objeto id=email


	$('#senha').click(function(){
	  if($(this).val()==""){
			$(this).val('');
		}//fim do if
	}//fim da funcao anonima
	);//fim do click no objeto id=senha

	$('#confirma-senha').click(function(){
		if($(this).val()==""){
			  $(this).val('');
		  }//fim do if
	  }//fim da funcao anonima
	  );//fim do click no objeto id=confirma-senha


	  $(document).ready(function(){
		$('#cadastrar').click(function(event){
			event.preventDefault(); // Impede o envio do formulário de forma tradicional
	
			var nome = $('#nome').val();
			var email = $('#email').val();
			var senha = $('#senha').val();
			var confirmaSenha = $('#confirma-senha').val();
	
			if (nome === "") {
				$('#mensagem-error').html("Nome não digitado").fadeIn(300).delay(2000).fadeOut(400);
			} else if (email === '' || email === "name@example.com") {
				$('#mensagem-error').html('Email inválido');
				$('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
			} else if (email.indexOf('@') === -1) {
				$('#mensagem-error').html('Email deve conter "@"');
				$('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
			} else if (senha === "") {
				$('#mensagem-error').html("Senha inválida").fadeIn(300).delay(2000).fadeOut(400);
			} else if (confirmaSenha === "" || senha !== confirmaSenha) {
				$('#mensagem-error').html("Senhas não coincidem").fadeIn(300).delay(2000).fadeOut(400);
			} else {
				// Se tudo estiver correto, faça a chamada AJAX
				var usuario = {
					n: nome,
					e: email,
					s: senha
				};
	
				$.ajax({
					url: '../php/cadastraUsuario.php',
					type: 'POST',
					data: JSON.stringify(usuario), // Envia os dados como JSON
					contentType: "application/json", // Define o tipo de conteúdo
					dataType: "json",
					success: function(result){
						if(result.status == "success"){
							$('#mensagem-success').html("Cadastrado com sucesso").fadeIn(300).delay(2000).fadeOut(400);
							setTimeout(function() {
								location.replace('telaLoginUsuario.php');
							}, 3000);
						} else {
							$('#mensagem-error').html("Não foi possível o cadastro").fadeIn(300).delay(2000).fadeOut(400);
						}
					},
					error: function(errorMessage){
						$('#mensagem-error').html("Erro: " + errorMessage.responseText).fadeIn(300).delay(2000).fadeOut(400);
					}
				});
			}
		});
	});
	
	$('#verSenha1').click(function(){
        let entrada = document.querySelector('#senha');
        let olho = document.querySelector('#verSenha1');
        if(entrada.getAttribute('type') == 'password'){
            entrada.setAttribute('type', 'text');
            olho.style.backgroundImage = "url('../imgs/icons/olho_escondido.png')";
        }else{
            entrada.setAttribute('type', 'password');
            olho.style.backgroundImage = "url('../imgs/icons/olho_aberto.png')";
        }
    });//fim verSenha
	
    $('#verSenha2').click(function(){
        let entrada = document.querySelector('#confirma-senha');
        let olho = document.querySelector('#verSenha2');
        if(entrada.getAttribute('type') == 'password'){
            entrada.setAttribute('type', 'text');
            olho.style.backgroundImage = "url('../imgs/icons/olho_escondido.png')";
        }else{
            entrada.setAttribute('type', 'password');
            olho.style.backgroundImage = "url('../imgs/icons/olho_aberto.png')";
        }
    });//fim verSenha
   
	  
});//fim







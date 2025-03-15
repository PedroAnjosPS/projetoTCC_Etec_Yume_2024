

$(document).ready(function() {
        //Reativar conta do usuario --> telaLoginUsuario.js
        const popUp2 = document.querySelector('#modal');
        const txtModal = document.querySelector('#text-modal');
        const btns_modal = document.querySelector('#modal-buttons');
        const confirm_modal = document.querySelector('#confirm-modal');
        const deny_modal = document.querySelector('#deny-modal');

        //Para fechar a dialog --> geral
        deny_modal.addEventListener('click', () => {
            popUp2.close();
        });


        var user_id = null; //var do usuario

        //Para reativar a conta do usuario --> telaLoginUsuário.php
    function reativarConta(user_id){
        //Fazendo uma requisição AJAX:
        confirm_modal.addEventListener('click', () => {
        if(user_id){
            fetch('reativaUser.php?id=' + user_id).then(response => response.text()).then(data => {
                console.log('Resposta do servidor: ' + data);
                if(data.trim() === 'success'){
                    btns_modal.style.display = 'none';
                    txtModal.innerHTML = 'A conta do usuário foi reativada com sucesso!';
                    //Redireciona para a tela de index
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1000);
                }else{
                    btns_modal.style.display = 'none';    
                    txtModal.innerHTML = 'Erro ao tentar reativar a conta do usuário!';
                    popUp2.close();
                }
            }).catch(error => {
                console.error('Erro na requisição: ', error);
                popUp2.close();
            });
        }
    });
    };


		$('#botaoLogar').click(function() {
			var email = $('#email').val();
			var senha = $('#senha').val();
	
			// Verificação do caractere "@" no campo de email
			if (email === '' || email === "name@example.com") {
				$('#mensagem-error').html('Email inválido');
				$('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
			} else if (email.indexOf('@') === -1) {
				$('#mensagem-error').html('Email deve conter "@"');
				$('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
			} else if (senha === '' || senha === "senha") {
				$('#mensagem-error').html('Senha inválida');
				$('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
			} else {
            var usuario = {
                e: $('#email').val(),
                s: $('#senha').val()
            };
            var dados = JSON.stringify(usuario);

            $.ajax({
                url: 'pesquisaUsuario.php',
                type: 'POST',
                data: dados,
                contentType: "application/json",
                dataType: "json",
                success: function(result) {
                    console.log("Resposta do servidor: ", result); // Log completo para análise
                    if (result.status === "success") {
						console.log(result); //ajuda a ver o que está sendo retornado pelo arquivo pesquisaUsuario.php
						$('#mensagem-success').html('Login efetuado com sucesso');
						$('#mensagem-success').fadeIn(300).delay(2000).fadeOut(400);
						setTimeout(function() {
							location.replace("index.php");
						}, 3000);
                    } else if (result.status === "failed") {
                        $('#mensagem-error').html('Usuário não encontrado');
                        $('#mensagem-error').fadeIn(300).delay(2000).fadeOut(400);
                        $('#email').val('');
                        $('#senha').val('');
                    } else if (result.status === "desativado") {
                        $('#mensagem-error').html('A conta do usuário está desativada');
                        $('#mensagem-error').fadeIn(300).delay(5000).fadeOut(400);
                        $('#email').val('');
                        $('#senha').val('');

                        user_id = result.user_id;

                        console.log('id do usuario: ' + user_id);

                        popUp2.showModal();

                        $('#confirm-modal').click(function() {
                            /*if(user_id){
                                fetch('reativaUser.php?id=' + user_id).then(response => response.text()).then(data => {
                                    console.log('Resposta do servidor: ' + data);
                                    if(data.trim() === 'success'){
                                        btns_modal.style.display = 'none';
                                        txtModal.innerHTML = 'A conta do usuário foi reativada com sucesso!';
                                        //Redireciona para a tela de index
                                        setTimeout(() => {
                                            window.location.href = 'index.php';
                                        }, 1000);
                                    }else{
                                        btns_modal.style.display = 'none';    
                                        txtModal.innerHTML = 'Erro ao tentar reativar a conta do usuário!';
                                        popUp2.close();
                                    }
                                }).catch(error => {
                                    console.error('Erro na requisição: ', error);
                                    popUp2.close();
                                });
                            }*/

                                $.ajax({
                                    url: 'reativaUser.php',
                                    type: 'GET',
                                    data: { id: user_id },
                                    success: function(data) {
                                        if(data.status === "success"){
                                            $('#modal-buttons').hide();
                                            $('#text-modal').text('A conta do usuário foi reativada com sucesso!');
                                            $('#modal').modal('hide');
                                            //txtModal.innerHTML = 'A conta do usuário foi reativada com sucesso!';
                                            //Redireciona para a tela de index
                                            setTimeout(() => {
                                                window.location.href = 'index.php';
                                            }, 1000);
                                        }else{
                                            /*$('#btns_modal').css('display', 'none');
                                            setTimeout(() => {
                                                $('#text-modal').html('Erro ao tentar reativar a conta do usuário!');
                                            }, 1000);
                                            
                                            popUp2.close();*/
                                            $('#modal-buttons').hide();
                                            setTimeout(() => {
                                                $('#text-modal').text('Erro ao tentar reativar a conta do usuário: ' + data.message);
                                            }, 1000);
                                            $('#modal').modal('hide');
                                        }
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.error("Erro no AJAX: ", textStatus, errorThrown); // Exibe erro detalhado no console
                                        console.error("Resposta completa: ", jqXHR.responseText); // Exibe a resposta completa
                                        $('#modal').modal('hide');
                                    }
                                });
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro no AJAX: ", textStatus, errorThrown); // Exibe erro detalhado no console
                    console.error("Resposta completa: ", jqXHR.responseText); // Exibe a resposta completa
                }
            });
        }
    });

    $('#verSenha').click(function(){
        let entrada = document.querySelector('#senha');
        let olho = document.querySelector('#verSenha');
        if(entrada.getAttribute('type') == 'password'){
            entrada.setAttribute('type', 'text');
            olho.style.backgroundImage = "url('../imgs/icons/olho_escondido.png')";
        }else{
            entrada.setAttribute('type', 'password');
            olho.style.backgroundImage = "url('../imgs/icons/olho_aberto.png')";
        }
    });//fim verSenha

});





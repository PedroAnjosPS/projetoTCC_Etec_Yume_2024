const popup = document.querySelector('#dialog');
const popup1 = document.querySelector('#dialog1');
const deleteUser = document.querySelector('#deleta_conta'); //para tela editarPerfil.php
const desativaUser = document.querySelector('#desativa_conta'); //para tela editarPerfil.php
const confirmaModal = document.querySelector('#confirm-dialog'); //geral
const denyModal = document.querySelector('#deny-dialog'); //geral
const confirmaModal1 = document.querySelector('#confirm-dialog1'); //para tela EditarPerfil.php
const denyModal1 = document.querySelector('#deny-dialog1'); //para tela EditarPerfil.php
const btnsDialog = document.querySelector('#dialog-buttons'); //geral
const btnsDialog1 = document.querySelector('#dialog-buttons1');
const txtDialog = document.querySelector('#text-dialog'); //geral
const txtDialog1 = document.querySelector('#text-dialog1'); //geral


//Para excluir o anime --> editarPerfil.php
desativaUser.addEventListener('click', () => {
    popup.showModal();
});

//Para excluir o anime --> editarPerfil.php
denyModal.addEventListener('click', () => {
    popup.close();
});

//Para desativar a conta do usuario --> editarPerfil.php
confirmaModal.addEventListener('click', () => {
    //Fazendo uma requisição AJAX:
    if(user_id){
        fetch('desativaUser.php?id=' + user_id).then(response => response.text()).then(data => {
            console.log('Resposta do servidor: ' + data);
            if(data.trim() === 'success'){
                btnsDialog.style.display = 'none';
                txtDialog.innerHTML = 'Conta do usuário desativada com sucesso!';
                //Redireciona para a tela de index
                setTimeout(() => {
                    window.location.href = 'sair.php';
                }, 1000);
            }else{
                btnsDialog.style.display = 'none';    
                txtDialog.innerHTML = 'Erro ao desativar a conta do usuário!';
                popup.close();
            }
        }).catch(error => {
            console.error('Erro na requisição: ', error);
            popup.close();
        });
    }
});


//Para abrir a dialog --> editarPerfil.php
deleteUser.addEventListener('click', () => {
    popup1.showModal();
});

//Para fechar a dialog --> geral
denyModal1.addEventListener('click', () => {
    popup1.close();
});

//Para excluir o anime --> editarPerfil.php
confirmaModal1.addEventListener('click', () => {
    //Fazendo uma requisição AJAX:
    if(user_id){
        fetch('deleteUser.php?id=' + user_id).then(response => response.text()).then(data => {
            console.log('Resposta do servidor: ' + data);
            if(data.trim() === 'success'){
                btnsDialog1.style.display = 'none';
                txtDialog1.innerHTML = 'Usuário excluído com sucesso!';
                //Redireciona para a tela de index
                setTimeout(() => {
                    window.location.href = 'sair.php';
                }, 1000);
            }else{
                btnsDialog1.style.display = 'none';    
                txtDialog1.innerHTML = 'Erro ao excluir o usuário!';
                popup1.close();
            }
        }).catch(error => {
            console.error('Erro na requisição: ', error);
            popup1.close();
        });
    }
});







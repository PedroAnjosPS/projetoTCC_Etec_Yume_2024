const popup = document.querySelector('dialog');
const deleteNew = document.querySelector('#delete_new'); //para tela animeAdm.php
const confirmDialog = document.querySelector('#confirm-dialog'); //geral
const denyDialog = document.querySelector('#deny-dialog'); //geral
const divBtns = document.querySelector('.dialog-buttons'); //geral
const pDialog = document.querySelector('p#text-dialog'); //geral

//Obtendo o id do anime da URL
//const urlParams = new URLSearchParams(window.location.search);
//noticiaId = urlParams.get('id'); // Isso pega o ID do anime

//Para abrir a dialog --> animeAdm.php
deleteNew.addEventListener('click', () => {
    popup.showModal();
});

//Para fechar a dialog --> geral
denyDialog.addEventListener('click', () => {
    popup.close();
});

//Para excluir o anime --> animeAdm.php
confirmDialog.addEventListener('click', () => {
    //Fazendo uma requisição AJAX:
    if(noticiaId){
        fetch('deleteNoticia.php?id=' + noticiaId).then(response => response.text()).then(data => {
            console.log('Resposta do servidor: ' + data);
            if(data.trim() === 'success'){
                divBtns.style.display = 'none';
                pDialog.innerHTML = 'Notícia excluída com sucesso!';
                //Redireciona para a tela de index
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            }else{
                divBtns.style.display = 'none';    
                pDialog.innerHTML = 'Erro ao excluir a notícia!';
                popup.close();
            }
        }).catch(error => {
            console.error('Erro na requisição: ', error);
            popup.close();
        });
    }
});
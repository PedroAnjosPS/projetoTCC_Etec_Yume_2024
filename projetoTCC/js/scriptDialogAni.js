const popup = document.querySelector('dialog');
const deleteAnime = document.querySelector('#animeDelete'); //para tela animeAdm.php
const confirmDialog = document.querySelector('#confirm-dialog'); //geral
const denyDialog = document.querySelector('#deny-dialog'); //geral
const divBtnsDialog = document.querySelector('.dialog-buttons'); //geral
const textDialog = document.querySelector('#text-dialog'); //geral

//Obtendo o id do anime da URL
const urlParams = new URLSearchParams(window.location.search);
const animeId = urlParams.get('id'); // Isso pega o ID do anime

//Para abrir a dialog --> animeAdm.php
deleteAnime.addEventListener('click', () => {
    popup.showModal();
});

//Para fechar a dialog --> geral
denyDialog.addEventListener('click', () => {
    popup.close();
});

//Para excluir o anime --> animeAdm.php
confirmDialog.addEventListener('click', () => {
    //Fazendo uma requisição AJAX:
    if(animeId){
        fetch('deleteAnime.php?id=' + animeId).then(response => response.text()).then(data => {
            console.log('Resposta do servidor: ' + data);
            if(data.trim() === 'success'){
                divBtnsDialog.style.display = 'none';
                textDialog.innerHTML = 'Anime excluído com sucesso!';
                //Redireciona para a tela de index
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            }else{
                divBtnsDialog.style.display = 'none';
                textDialog.innerHTML = 'Erro ao excluir o anime!';
                popup.close();
            }
        }).catch(error => {
            console.error('Erro na requisição: ', error);
            popup.close();
        });
    }
});
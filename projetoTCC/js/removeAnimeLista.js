

document.querySelectorAll('.excluir').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Evita que o link padrão seja acionado
        const animeId = this.getAttribute('data-id');

        if(animeId){
            fetch('removaAniLista.php?id=' + animeId).then(response => response.text()).then(result => {
                if(result === 'success'){
                    console.log('Anime removido com sucesso!');
                    this.closest('.resultAnime').remove();

                    //verifica quantas divs de anime salvos tem na página
                    const divsPresentes = document.querySelectorAll('.resultAnime');

                    if(divsPresentes.length === 0){
                        const noAnimeMessage = document.createElement('p');
                        noAnimeMessage.textContent = 'Não há animes salvos';
                        noAnimeMessage.style.textAlign = 'center';
                        noAnimeMessage.style.margin = '30px auto';
                        noAnimeMessage.style.fontSize = '1.2em';
                        noAnimeMessage.style.fontWeight = '600';

                        //Insere a mensagem na div resultSearch
                        const divAnimesSalvos = document.querySelector('.resultSearch');
                        divAnimesSalvos.appendChild(noAnimeMessage);
                    }
                }else{
                    console.log('Erro ao tentar remover o anime!');
                }
            }).catch(error => {
                console.error('Error: ', error);
            });
        }
    });
});
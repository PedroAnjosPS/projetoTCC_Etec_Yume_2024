const salvarAnime = document.getElementById('animeSave');
const icone = salvarAnime.querySelector('i');
const abbr = document.getElementById('texto-abbr');

//Obtendo o id do anime da URL
const urlParams = new URLSearchParams(window.location.search);
const animeId = urlParams.get('id'); // Isso pega o ID do anime
console.log('animeId: ' + animeId);

//Função para verificar se o anime está salvo ou não quando a página é carregada
function verificarAnimeSalvo(){
    fetch('../php/verificaAnimeSalvo.php?id=' + animeId).then(response => response.json()).then(data => {
        //Se o anime estiver salvo, será atualizado o ícone e a descrição, se não, não
        if(data.salvo){
            icone.classList.remove('far');
            icone.classList.add('fas');
            abbr.setAttribute('title', 'Anime salvo!');
        }else{
            icone.classList.remove('fas');
            icone.classList.add('far');
            abbr.setAttribute('title', 'Salvar anime?');
        }
    }).catch(error => {
        console.error("Erro: ", error);
    });
}

//Chama a função para verificar se o anime está salvo ao carregar a página:
verificarAnimeSalvo();

salvarAnime.addEventListener('click', function() {

    let salvo = false; //Armazena a informação se o anime está salvo ou não

    /*Basicamente, esse código verifica se a classe do i é 'far' (só o contorno está pintado), se sim, ele troca para a classe 'fas' (espaço interno pintado)*/ 
    if(icone.classList.contains('far')){
        icone.classList.remove('far');
        icone.classList.add('fas');
        abbr.setAttribute('title', 'Anime salvo!');
        salvo = true; //anime salvo
    } else {
        //Faz a lógica inversa da primeira condição
        icone.classList.remove('fas');
        icone.classList.add('far');
        abbr.setAttribute('title', 'Salvar anime?');
        salvo = false; //anime removido dos salvos
    }

    //Usando AJAX para salvar a informação no banco
    fetch('../php/salvarAnime.php?id=' + animeId + '&salvo=' + salvo, {
        method: 'GET',
    }).then(response => response.json()).then(data => {
        console.log(data); //Verificando a resposta do servidor
    }).catch(error => {
        console.error("Erro: ", error);
    });
});


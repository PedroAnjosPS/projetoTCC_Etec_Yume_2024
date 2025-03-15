const salvarAnimeBtns = document.querySelectorAll('button#animeSave');

salvarAnimeBtns.forEach(button => {
    const animeId = button.value; // Pega o valor do id do botão
    const icone = button.querySelector('i'); // Ícone do botão
    const abbr = button.closest('abbr'); // O abbr mais próximo do botão

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

    button.addEventListener('click', function() {

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
});


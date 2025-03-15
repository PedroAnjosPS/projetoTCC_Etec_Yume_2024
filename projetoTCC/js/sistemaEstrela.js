// Seleciona todas as estrelas e o container das estrelas
var stars = document.querySelectorAll('.star-icon');
var container = document.querySelector('.avaliacao');
const animeScoreDiv = document.querySelector('.animeScore');
var avaliacaoUser = 0;

//Obtendo o id do anime da URL
const paramURL = new URLSearchParams(window.location.search);
const anime_id = paramURL.get('id'); // Isso pega o ID do anime



//Essa funcao preenche as estrelas com relação à nota do usuário
function preencherEstrelas(avaliacao){
    stars.forEach(function(star, index){
        if(index < avaliacao){
            star.classList.add('ativo');
        }else{
            star.classList.remove('ativo');
        }
    });
}

//Essa funcao verifica se há uma nota salva e, consquentemente, preenche as estrelas
if(typeof notaSalva !== 'undefined' && notaSalva > 0){
    preencherEstrelas(notaSalva);
    avaliacaoUser = notaSalva; //Armazena a nota salva
}

// Adiciona o comportamento de clique nas estrelas
stars.forEach(function(star, item) {
    star.addEventListener('click', function(event) {
        event.stopPropagation(); // Impede que o clique na estrela feche a avaliação
        avaliacaoUser = parseInt(star.getAttribute('data-avaliacao')); //pega o valor da estrela clicada
        preencherEstrelas(avaliacaoUser); // Atualiza as estrelas com a nota do Uuario
    });

    // Adiciona o comportamento de hover nas estrelas
    star.addEventListener('mouseover', function() {
        stars.forEach(function(innerStar, innerItem) {
            if (innerItem <= item) {
                innerStar.classList.add('hover'); // Adiciona a classe "hover" para estrelas até a atual
            }
        });
    });

    star.addEventListener('mouseout', function() {
        stars.forEach(function(innerStar) {
            innerStar.classList.remove('hover'); // Remove a classe "hover" quando o mouse sai da estrela
        });
    });
});

// Adiciona um evento global de clique para o documento
animeScoreDiv.addEventListener('click', function() {
    //Verifica se o clique não foi em uma estrela
    if(!event.target.classList.contains('star-icon')){
        avaliacaoUser = 0; //reseta a avaliacao
        preencherEstrelas(0); //desmarca todas as estrelas
    }
});

// Esta função impede que a funcao acima funcione ao clicarmos nas estrelas, ou seja, elas só vão ser desmarcadas quando o usuário clicar fora delas
container.addEventListener('click', function(event) {
    event.stopPropagation(); // Impede que o clique no container feche a avaliação
});

//Evento de clique do botão para enviar a nota
document.getElementById('nota').addEventListener('click', function(){
    //Verifica se alguma estrela foi selecionada, se não, 0 é enviado como nota
    let notaFinal = avaliacaoUser > 0 ? avaliacaoUser : 0;


    //Verificação no console
    console.log("Anime ID:", anime_id);
    console.log("Nota Final:", notaFinal);

    //Envio da nota via AJAX
    fetch('../php/salvarNota.php?id=' + anime_id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nota=' + encodeURIComponent(notaFinal) //codifica a string para que ela esteja em um formato ok na URL
    }).then(response => {
        if(!response.ok){
            throw new Error('Erro na resposta de requisição');
        }

        return response.json();

    }).then(data => {
        if(data.success){
            $('#mensagem-success').html("Nota enviada com sucesso!").fadeIn(300).delay(1000).fadeOut(400);
            preencherEstrelas(notaFinal); //Atualiza a formatação das estrelas após o envio da nota ao servidor
        }else{
            $('#mensagem-error').html("Erro ao enviar a nota!").fadeIn(300).delay(1000).fadeOut(400);
        }
    }).catch(error => {
        console.error('Erro: ', error);
        $('#mensagem-error').html("Erro ao enviar a nota!").fadeIn(300).delay(1000).fadeOut(400);
    });
});

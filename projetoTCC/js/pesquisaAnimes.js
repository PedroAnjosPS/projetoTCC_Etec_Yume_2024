const divSearch = document.querySelector('.search');
const inputSearch = divSearch.querySelector('#in-search');
const suggestionsDiv = divSearch.querySelector('.suggestions');
const btnSearch = document.querySelector('#btn-search');

// Função para buscar animes
function searchAnime(animeName){
    if(animeName){
        window.location.href = `resultSearch.php?query=${animeName}`;
    }else{
        console.log('Nenhum dado foi inserido');
    }
}

//seleciona o texto da sugestao
function select(element){
    let selectData = element.textContent;
    inputSearch.value = selectData;

    //faz com funcione a busca ao apertar o botao
    btnSearch.onclick = () => {
        searchAnime(selectData);
        inputSearch.value = "";
    }

    searchAnime(selectData);

    /*//faz com funcione a busca ao apertar o 'enter'
    inputSearch.onkeyup = (event) => {
        if(event.key === 'Enter'){
            divSearch.classList.remove('active');
            searchAnime(animeName);
            inputSearch.value = "";
        }
    }*/
    

    divSearch.classList.remove('active');
}

function showSuggestions(list){
    let listData;
    
    if(!list.length){
        animeValue = inputSearch.value;
        listData = `<li>${animeValue}</li>`;
    }else{
        listData = list.join('');
    }

    suggestionsDiv.innerHTML = listData;
};

inputSearch.onkeyup = (event) => {
    let animeName = event.target.value.trim();
    let emptyArray = [];

    if(animeName){
        emptyArray = sugestoes.filter((data) => {
            return data.toLocaleLowerCase().startsWith(animeName.toLocaleLowerCase());
        });

        //Mapeia as sugestoes para os animes do banco
        emptyArray = emptyArray.map((data) => {
            return data = `<li>${data}</li>`;
        });
        divSearch.classList.add('active'); //Ativa as sugestoes

        showSuggestions(emptyArray);

        //Adiciona o clique em cada sugestao
        let allList = suggestionsDiv.querySelectorAll('li');
        for(let c = 0; c < allList.length; c++){
            allList[c].setAttribute('onclick', 'select(this)');
        }

        //Se o usuário apertar 'esc', fecha as sugestoes
        if(event.key === 'Escape'){
            divSearch.classList.remove('active');
        }

        //aperta 'enter' para fazer a busca
        if(event.key === 'Enter'){
            divSearch.classList.remove('active');
            searchAnime(animeName);
            inputSearch.value = "";
        }

        //console.log('Sugestoes apareceram');
    }else{
        divSearch.classList.remove('active'); // esconde as sugestoes se estiver vazio
        //console.log('Sugestoes não apareceram');
    }
};

btnSearch.onclick = () => {
    let animeName = inputSearch.value.trim();
    
    if (animeName) {
        // Verifica se há sugestões que correspondam ao texto digitado no input
        let emptyArray = sugestoes.filter((data) => {
            return data.toLocaleLowerCase().startsWith(animeName.toLocaleLowerCase());
        });

        if (emptyArray.length > 0) {
            // Se houver sugestões, mostra elas antes de fazer a busca
            emptyArray = emptyArray.map((data) => `<li>${data}</li>`);
            divSearch.classList.add('active');
            showSuggestions(emptyArray);
            
            let allList = suggestionsDiv.querySelectorAll('li');
            for (let c = 0; c < allList.length; c++) {
                allList[c].setAttribute('onclick', 'select(this)');
            }
        }
            // Independente se há ou não sugestões, faz a busca do anime
            searchAnime(animeName);
        
    } else {
        divSearch.classList.remove('active'); // Oculta as sugestões se não houver texto
        console.log('Nenhum dado foi inserido');
    }
};
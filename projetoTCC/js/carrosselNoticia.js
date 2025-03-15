document.addEventListener('DOMContentLoaded', () => {
    const botaoPrev = document.querySelector('.prevNot');
    const botaoNext = document.querySelector('.nextNot');
    const carrossel = document.querySelector('.carrosselNoticias');
    const items = Array.from(document.querySelectorAll('.previewNoticia'));

    let currentIndex = 0;
    const itemLargura = items[0].offsetWidth + 20; // Largura do item + margem
    const totalItems = items.length;

    // Clonagem dos itens
    items.forEach(item => {
        /*const clone = item.cloneNode(true);
        clone.classList.add('clone');
        carrossel.appendChild(clone);*/ //codigo original

        const clone = item.cloneNode(true);
        clone.classList.add('clone');

        //Pega o link do item copiado:
        const linkOriginal = item.closest('a'); //Pega o <a> que está envolvendo o previewNoticia
        if(linkOriginal){
            const cloneLink = document.createElement('a'); //Cria um novo <a> para envolver o clone
            cloneLink.href = linkOriginal.href; //Copia o href original
            cloneLink.appendChild(clone); //Coloca o clone dentro do novo <a>

            //remove o estilo do <a> dos items clonados
            cloneLink.style.textDecoration = 'none';
            cloneLink.style.color = 'inherit';
            let href = cloneLink.href;
            //verificando se qual pagina o user sera enviado no href
            if(href.includes('animeInfo.php')){
                cloneLink.target = '_blank';
            }

            carrossel.appendChild(cloneLink);
        }else{
            carrossel.appendChild(clone); //se não tiver um <a>, põe o clone diretamente no carrossel
        }
    });

    items.slice().reverse().forEach(item => {
        /*const clone = item.cloneNode(true);
        clone.classList.add('clone');
        carrossel.insertBefore(clone, carrossel.firstChild);*/ //Código original, se der erro no novo usa esse

        const clone = item.cloneNode(true);
        clone.classList.add('clone');

        //Pega o link do item copiado:
        const linkOriginal = item.closest('a'); //Pega o <a> que está envolvendo o previewNotica
        if(linkOriginal){
            const cloneLink = document.createElement('a'); //Cria um novo <a> para envolver o clone
            cloneLink.href = linkOriginal.href; //Copia o href original
            cloneLink.appendChild(clone); //Coloca o clone dentro do novo <a>

            //remove o estilo do <a> dos items clonados
            cloneLink.style.textDecoration = 'none';
            cloneLink.style.color = 'inherit';
            let href = cloneLink.href;
            //verificando se qual pagina o user sera enviado no href
            if(href.includes('animeInfo.php')){
                cloneLink.target = '_blank';
            }

            carrossel.insertBefore(cloneLink, carrossel.firstChild);
        }else{
            carrossel.insertBefore(clone, carrossel.firstChild);
        }
    });

    const totalClones = document.querySelectorAll('.clone').length;
    const totalCarrosselItems = totalItems + totalClones;

    carrossel.style.width = `${totalCarrosselItems * itemLargura}px`;
    currentIndex = totalItems; // Começa na posição inicial dos itens originais
    carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;

    function atualizaCarrossel() {
        // Função para atualizar a posição do carrossel.
        
        carrossel.style.transition = 'transform 0.5s ease-in-out';
        // Define uma transição suave para o movimento do carrossel.
        
        carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;
        // Move o carrossel para a posição de acordo com o índice atual.
        
        if (currentIndex === totalItems + totalItems) {
            // Verifica se o índice atual alcançou o final dos clones.
            setTimeout(() => {
                carrossel.style.transition = 'none';
                // Remove a transição para o ajuste repentino.
                
                currentIndex = totalItems;
                // Define o índice para o início dos itens originais.
                
                carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;
                // Move o carrossel de volta para a posição inicial dos itens originais.
            }, 500);
        } else if (currentIndex < -totalItems) {
            // Verifica se o índice atual está abaixo do início dos itens originais.
            setTimeout(() => {
                carrossel.style.transition = 'none';
                // Remove a transição para o dar um "corte seco" a posição original do carrossel.
                
                currentIndex = totalItems;
                // Define o índice para o início dos itens originais.
                
                carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;
                // Move o carrossel de volta para a posição inicial dos itens originais.
            }, 500);
        }
    }

    function atualizaCarrosselReverso() { //para mover pra esquerda e clonar os itens dando efeito de continuidade
        carrossel.style.transition = 'transform 0.5s ease-in-out';
        carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;

        // O índice do último item clonado da esquerda é o índice 0 do contêiner
        const primeiroCloneReverso = 0; // Considera que o primeiro item clonado da esquerda está na posição 0

        if (currentIndex === primeiroCloneReverso) {
            // Chegou ao último item clonado da esquerda
            setTimeout(() => {
                carrossel.style.transition = 'none';
                currentIndex = totalItems; // Retorna à posição inicial dos itens originais
                carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;
            }, 500);
        } else if (currentIndex < -totalItems) {
            // Se o índice estiver abaixo do início dos itens originais
            setTimeout(() => {
                carrossel.style.transition = 'none';
                currentIndex = totalItems;
                carrossel.style.transform = `translateX(${-currentIndex * itemLargura}px)`;
            }, 500);
        }
    }

    botaoNext.addEventListener('click', () => {
        currentIndex++;
        atualizaCarrossel();
    });

    botaoPrev.addEventListener('click', () => {
        currentIndex--;
        atualizaCarrosselReverso();
    });

    atualizaCarrossel(); // Inicializa o carrossel na posição correta
});

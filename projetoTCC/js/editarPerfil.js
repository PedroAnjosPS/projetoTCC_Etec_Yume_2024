//
const btnUpload = document.querySelector('#edit-foto');
const uploadDiv = document.querySelector('.upload');
const uploadIcon = document.querySelector('#upload-icon');
let imgSelecAtual = null;

btnUpload.addEventListener('change', (event) => {
    const imgExistente = document.querySelector('#img-perfil');

    /*Está verificando se já há uma imagem carregada dentro da div "upload", se sim, será removido para poder carregar outra dentro
    da div*/
    if(imgExistente){
        imgExistente.remove();
        uploadDiv.appendChild(uploadIcon);
    }

    //Verifica se algum arquivo foi selecionado
    if(btnUpload.files && btnUpload.files[0]){ //Verifica se o usuário selecionou uma imagem.
        
        //Validacao da imagem selecionada 
        const file = btnUpload.files[0];

        if(!['image/jpeg', 'image/png', 'image/bmp'].includes(file.type)) { //verifica o formato da imagem
            $('#mensagem-error').html("Formato de imagem inválido").fadeIn(300).delay(2000).fadeOut(400);
            return;
        } 
        
        if(btnUpload.size > 2 * 1024 * 1024) { // Limite máximo é de 2MB
            $('#mensagem-error').html("Imagem muito grande. Máximo de 2MB").fadeIn(300).delay(2000).fadeOut(400);
            return;
        }

        const reader = new FileReader(); //Objeto que permite a leitura da imagem 

        reader.onload = function(event){ //Função é executada assim que o conteudo da imagem é lido
            //Remove a imagem padrão se existir
            uploadIcon.remove();

            //Cria uma nova imagem para o perfil
            const imagemPerfil = document.createElement('img');
            imagemPerfil.id = 'img-perfil';
            imagemPerfil.src = event.target.result; //Define o src da imagem como a URL da imagem carregada, gerada pelo FileReader.
            imagemPerfil.style.maxWidth = '100%';
            imagemPerfil.style.borderRadius = '50%';

            setTimeout(() => {
                imagemPerfil.classList.add('show');
            }, 100);

            uploadDiv.appendChild(imagemPerfil);

            //Última imagem que foi selecionada
            imgSelecAtual = event.target.result;

            
            //Chama a função que envia a imagem pro servidor após a mesma ser selecionada
            enviaImgServer(file);
        }

        //Le o conteudo da imagem como url
        reader.readAsDataURL(file);  
    }else {
        // Se nenhum arquivo foi selecionado e se o usuario fechar o seletor de arquivos, é exibido a imagem atual, se houver
        if (imgSelecAtual) {
            uploadIcon.remove(); // Remove o ícone de upload se estiver presente
            const imagemPerfil = document.createElement('img');
            imagemPerfil.id = 'img-perfil';
            imagemPerfil.src = imgSelecAtual; // Define o src da imagem como a URL armazenada
            imagemPerfil.style.maxWidth = '100%';
            imagemPerfil.style.borderRadius = '50%';

            setTimeout(() => {
                imagemPerfil.classList.add('show');
            }, 100);

            uploadDiv.appendChild(imagemPerfil);
        }
    }
    
    

});

//Envia imagem pro server
function enviaImgServer(file){
    const formData = new FormData();

    formData.append('imagem', file);

    fetch('../php/atualizaFotoPerfil.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(result => {
        console.log('A imagem foi enviada com sucesso: ', result);
        $('#mensagem-success').html(result.message).fadeIn(300).delay(2000).fadeOut(400);

        if(result.status === 'success'){
            carregarImagem();
        }
    }).catch(error => {
        console.log('Erro ao enviar a imagem: ', error);
        $('#mensagem-error').html("Erro ao enviar a imagem!!").fadeIn(300).delay(2000).fadeOut(400);
    });
}

//Carregar imagem do Banco
function carregarImagem(){
    fetch('../php/imagemBancoUsuario.php').then(response => response.json()).then(result => {
        //Imagem de perfil definida
        const uploadDiv = document.querySelector('.upload');
        const imagemPerfil = document.getElementById('img-perfil');

        //Verifica se NÃO existi o elemento imagemPerfil
        if (!imagemPerfil) {
            // Criar a imagem se não existir
            imagemPerfil = document.createElement('img');
            imagemPerfil.id = 'img-perfil';
            imagemPerfil.style.maxWidth = '100%';
            imagemPerfil.style.borderRadius = '50%';
            uploadDiv.appendChild(imagemPerfil);
        }

        

        if(result.success){
            const imgUrl = result.caminhoImagem;

            const urlComTimestamp = imgUrl + '?t=' + new Date().getTime();

            console.log('URL da imagem com timestamp:', urlComTimestamp);
            
            imagemPerfil.src = urlComTimestamp;

            console.log("Imagem de perfil atualizada com sucesso!!");
        } else {
            imagemPerfil.src = "../imgs/usuario-de-perfil.png";

            console.error(result.message);
        }
    }).catch(error => {
        const imagemPerfil = document.getElementById('img-perfil');
        if (imagemPerfil) {
            imagemPerfil.src = "../imgs/usuario-de-perfil.png";
        }
        console.error("Erro ao carregar a imagem de perfil do Banco de Dados: ", error);
    });
}

//Carrega a funcao após a página ser carregada
window.onload = carregarImagem;

//Quando a página é carregada, o nome do usuário cadastrado no banco é carregado na parte de input
window.onload = function(){
    fetch('../php/pegaNomeUserBanco.php').then(response => response.json()).then(data => {
        if(data.status === 'success'){
            document.getElementById('nome').value = data.nome;
        }else{
            console.error("Erro ao tentar carregar o nome do usuário do banco: ", data.message);
        }
    }).catch(error => console.error("Erro ao tentar buscar o nome do usuário do Banco: ", error));
}

//Altera nome do Usuário
document.addEventListener('DOMContentLoaded', () => {
    const nomeUsuario = document.getElementById('nome');

    nomeUsuario.addEventListener('blur', function(){
        const novoNome = nomeUsuario.value;

        fetch('../php/alteraNomeUsuario.php', {
            method: 'POST',
            headers:{
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
                nome: novoNome
            })
        }).then(response => response.json()).then(data => {
            if(data.status === 'success'){
                $('#mensagem-success').html("Nome atualizado com sucesso!!").fadeIn(300).delay(2000).fadeOut(400);
                console.log('Nome atualizado com sucesso!!');
            }else{
                $('#mensagem-error').html("Erro ao tentar atualizar o nome!!").fadeIn(300).delay(2000).fadeOut(400);
                console.log('Erro ao tentativa de atualizar o nome: ', data.message);
            }
        }).catch(error => console.log('Erro ao enviar a atualização do nome: ', error));
    });
});
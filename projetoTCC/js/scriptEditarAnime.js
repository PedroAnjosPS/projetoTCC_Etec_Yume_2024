//Esse início é para pegar o conteúdo da variavel blobUploadIcon declarada no PHP e converte-la de base64 para object file
// A variável 'blobUploadIcon' já foi passada pelo PHP e contém a string Base64.
var base64Data = blobUploadIcon.split(',')[1];  // Remove o prefixo "data:image/png;base64,"
var byteCarac = atob(base64Data);  // Decodifica a string Base64 para binário (ASCII)

var byteNum = new Array(byteCarac.length);
for(var c = 0; c < byteCarac.length; c++){
    byteNum[c] = byteCarac.charCodeAt(c); //Converte cada caractere para seu código em byte
}

var byteArray = new Uint8Array(byteNum); // Cria um array de bytes (binário)
var blob = new Blob([byteArray], {type: 'image/jpeg'}); //Cria um Blob com um tipo especifico de arquivo (nesse caso, jpeg)

//Criando um File a partir do Blob
var fileUploadIcon = new File([blob], 'image.jpg', {type: 'image/jpeg'});

//Criado o file 
console.log(fileUploadIcon);

//Obtendo o id do anime da URL
const urlParams = new URLSearchParams(window.location.search);
const animeId = urlParams.get('id'); // Isso pega o ID do anime

// Primeira parte do código: Mostrar um preview da imagem carregada
const capaAnime = document.querySelector('#capaAnime'); 
const uploadIcon = document.querySelector('#upload-icon');
const legend = document.querySelector('#legend');


//última imagem selecionada no seletor de arquivos
let ultimaImgSelec;
let ultimaImgArquivo;


capaAnime.addEventListener('change', event => {
    // Verificando se já foi carregado uma outra imagem, assim, caso tenha, será removida e substituída
    const previewExistente = document.querySelector('#preview-image');
    if (previewExistente) {
        previewExistente.remove();
        //uploadIcon.style.display = 'flex';
    }

    if(capaAnime.files && capaAnime.files.length > 0 && capaAnime.files[0]){ //Verifica se o usuário selecionou uma imagem.
        
        //Validacao da imagem selecionada 
        ultimaImgArquivo = capaAnime.files[0];

        const reader = new FileReader();

        reader.onload = function(event) {
        const previewImage = document.createElement('img');
        previewImage.id = 'preview-image';
        previewImage.src = event.target.result;
        previewImage.style.maxWidth = '100%'; // Garantir que a imagem não exceda o container

        // Adiciona a imagem de preview no lugar onde ficava o ícone de upload e o legend
        const uploadDiv = document.querySelector('.upload');
        uploadDiv.appendChild(previewImage);

        // Oculta o ícone de upload e o legend
        uploadIcon.style.display = 'none';
        legend.style.display = 'none';

        // Aplica a classe show para haver uma transição
        setTimeout(() => {
            previewImage.classList.add('show');
        }, 100);

        //Armazena a URL na ultima imagem carregada
        ultimaImgSelec = event.target.result;
        //console.log('Src da última imagem selecionada: ' + ultimaImgSelec);
        };

        //Verifica se algum arquivo foi selecionado antes de lê-lo
            reader.readAsDataURL(ultimaImgArquivo);
        

    
        console.log('capaAnime (type-file): ' + capaAnime.files[0]);
    }else{
        // Se nenhum arquivo foi selecionado e se o usuario fechar o seletor de arquivos, é exibido a imagem atual, se houver
        if (ultimaImgSelec) {
            const previewImage = document.createElement('img');
            previewImage.id = 'preview-image'; // Certifique-se de que o ID é o mesmo
            previewImage.src = ultimaImgSelec; // Define o src da imagem como a URL armazenada
            previewImage.style.maxWidth = '100%';
            previewImage.style.width = '120px';
            previewImage.style.height = '150px';
            previewImage.style.borderRadius = '5px';
            previewImage.style.opacity = '0.6';

            const uploadDiv = document.querySelector('.upload');
            uploadDiv.appendChild(previewImage);

            // Oculta o ícone de upload e o legend
            uploadIcon.style.display = 'none'; // Oculta o ícone de upload
            legend.style.display = 'none'; // Oculta o legend

            //setTimeout(() => {
                previewImage.classList.add('show');
            //}, 0);

            //console.log('File: ' +  file);
        }else{
            //console.log('Nenhuma imagem selecionada!!');
            ultimaImgArquivo = null; //se não houver imagem selecionada, atribui um valor nulo a variavel
        }
    }

    console.log('ultimaImgArquivo: ' + ultimaImgArquivo);
    
});


// Validação do formulário e envio via AJAX
$(document).ready(function() {
    $('#edit_anime').click(function(event) {
        event.preventDefault(); // Impede o envio do formulário de forma tradicional

        var titulo = $('#titulo').val();
        var descricao = $('#descricao').val();
        var qtdEpisodios = $('#qtdEpisodios').val();
        var data = $('#data').val();

        //verificando se existi um arquivo armazenado no input type='file', #capaAnime. Se não existir, pega o File do #upload-icon
        if(ultimaImgArquivo){
            var capaAnime = ultimaImgArquivo;
        }else{
            var capaAnime = fileUploadIcon;
        }
        //var capaAnime = $('#capaAnime')[0].files[0];

        var genero = [];
        $("input:checked").each(function() {
            genero.push($(this).val());
        });


        // Validação
        if (titulo === '') {
            $('#mensagem-error').html("Título não digitado").fadeIn(300).delay(2000).fadeOut(400);
        } else if (descricao === '') {
            $('#mensagem-error').html('Descrição inválida').fadeIn(300).delay(2000).fadeOut(400);
        } else if (descricao.length < 100) {
            $('#mensagem-error').html('Descrição insuficiente').fadeIn(300).delay(2000).fadeOut(400);
        } else if (qtdEpisodios === "") {
            $('#mensagem-error').html("Coloque o número de episódios").fadeIn(300).delay(2000).fadeOut(400);
        } else if (data === "" || data === "dd/mm/aaaa") {
            $('#mensagem-error').html("Data inválida").fadeIn(300).delay(2000).fadeOut(400);
        } else if (!capaAnime) {
            $('#mensagem-error').html("Imagem não selecionada").fadeIn(300).delay(2000).fadeOut(400);
        } else if (!['image/jpeg', 'image/png', 'image/bmp'].includes(capaAnime.type)) {
            $('#mensagem-error').html("Formato de imagem inválido").fadeIn(300).delay(2000).fadeOut(400);
        } else if (capaAnime.size > 2 * 1024 * 1024) { // Limite máximo é de 2MB
            $('#mensagem-error').html("Imagem muito grande. Máximo de 2MB").fadeIn(300).delay(2000).fadeOut(400);
        } else if(genero.length === 0){
            $('#mensagem-error').html("Gênero não selecionado").fadeIn(300).delay(2000).fadeOut(400);
        } else{

            // Se tudo estiver correto, faça a chamada AJAX
            var formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('descricao', descricao);
            formData.append('qtdEpisodios', qtdEpisodios);
            formData.append('data', data);
            formData.append('capaAnime', capaAnime);
            //mandando cada genero para o servidor (php) com foreach
            genero.forEach(function(g){
                formData.append('genero[]', g);
            });

            $.ajax({
                url: '../php/editaAnime.php?id=' + animeId,
                type: 'POST',
                data: formData, // Envia os dados como formData
                processData: false, // Impede o JQuery de processar os dados
                contentType: false, // Impede o JQuery de definir o tipo de conteúdo
                success: function(result) {
                    console.log(result);
                    if (result.trim() == "success") {
                        $('#mensagem-success').html("Editado com sucesso!!").fadeIn(300).delay(2000).fadeOut(400);
                        // Limpa o formulário e o preview após o sucesso
                        /*$('#titulo').val('');
                        $('#descricao').val('');
                        $('#qtdEpisodios').val('');
                        $('#data').val('');
                        $('#capaAnime').val('');
                        $('#preview-image').remove();
                        $('input:checked').prop('checked', false); //Limpa os checkboxes*/
                        //uploadIcon.style.display = 'flex';
                        //uploadIcon.src = '../imgs/nuvem.png';
                        //uploadIcon.style.height = '50px';
                        //uploadIcon.style.width = '50px';
                        //uploadIcon.style.opacity = '0.2';
                        //legend.style.display = 'flex';
                    } else {
                        $('#mensagem-error').html("Não foi possível editar as informações").fadeIn(300).delay(2000).fadeOut(400);
                    }
                },
                error: function(xhr, status, error) {
                    $('#mensagem-error').html("Erro: " + xhr.responseText).fadeIn(300).delay(2000).fadeOut(400);
                }
            });
        }
    });
});

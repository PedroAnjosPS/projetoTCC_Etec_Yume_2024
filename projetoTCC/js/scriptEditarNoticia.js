/*REFORMULANDO O ARQUIVO -------------------------------------------------------------------------*/

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
var file_upload_icon = new File([blob], 'image.jpg', {type: 'image/jpeg'});

//Criado o file 
console.log(file_upload_icon);

//Obtendo o id do anime da URL
const urlParametro = new URLSearchParams(window.location.search);
const noticiaId = urlParametro.get('id'); // Isso pega o ID do anime

// Primeira parte do código: Mostrar um preview da imagem carregada
const capaNoticia = document.querySelector('#capaNoticia'); 
const upload_icon = document.querySelector('#upload-icon');
const legenda = document.querySelector('#legend');


//última imagem selecionada no seletor de arquivos
let ultimaImgNotSelec;
let ultimaImgArquivo;


capaNoticia.addEventListener('change', event => {
    // Verificando se já foi carregado uma outra imagem, assim, caso tenha, será removida e substituída
    const previewNotExistente = document.querySelector('#preview-image');
    if (previewNotExistente) {
        previewNotExistente.remove();
        //uploadIcon.style.display = 'flex';
    }

    if(capaNoticia.files && capaNoticia.files.length > 0 && capaNoticia.files[0]){ //Verifica se o usuário selecionou uma imagem.
        
        //Validacao da imagem selecionada 
        ultimaImgArquivo = capaNoticia.files[0];

        const reader = new FileReader();

        reader.onload = function(event) {
        const previewNotImage = document.createElement('img');
        previewNotImage.id = 'preview-image';
        previewNotImage.src = event.target.result;
        previewNotImage.style.maxWidth = '100%'; // Garantir que a imagem não exceda o container

        // Adiciona a imagem de preview no lugar onde ficava o ícone de upload e o legend
        const upload_div = document.querySelector('.upload');
        upload_div.appendChild(previewNotImage);

        // Oculta o ícone de upload e o legend
        upload_icon.style.display = 'none';
        legenda.style.display = 'none';

        // Aplica a classe show para haver uma transição
        setTimeout(() => {
            previewNotImage.classList.add('show');
        }, 100);

        //Armazena a URL na ultima imagem carregada
        ultimaImgNotSelec = event.target.result;
        //console.log('Src da última imagem selecionada: ' + ultimaImgNotSelec);
        };

        //Verifica se algum arquivo foi selecionado antes de lê-lo
            reader.readAsDataURL(ultimaImgArquivo);

    
        console.log('capaNoticia (type-file): ' + capaNoticia.files[0]);
    }else{
        // Se nenhum arquivo foi selecionado e se o usuario fechar o seletor de arquivos, é exibido a imagem atual, se houver
        if (ultimaImgNotSelec) {
            const previewNotImage = document.createElement('img');
            previewNotImage.id = 'preview-image'; // Certifique-se de que o ID é o mesmo
            previewNotImage.src = ultimaImgNotSelec; // Define o src da imagem como a URL armazenada
            previewNotImage.style.maxWidth = '100%';
            previewNotImage.style.width = '120px';
            previewNotImage.style.height = '150px';
            previewNotImage.style.borderRadius = '5px';
            previewNotImage.style.opacity = '0.6';

            const upload_div = document.querySelector('.upload');
            upload_div.appendChild(previewNotImage);

            // Oculta o ícone de upload e o legend
            upload_icon.style.display = 'none'; // Oculta o ícone de upload
            legenda.style.display = 'none'; // Oculta o legend

            //setTimeout(() => {
                previewNotImage.classList.add('show');
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
    console.log("jQuery carregado e script ativo.");

    $('#edit_new').click(function(event) {
        event.preventDefault(); // Impede o envio do formulário de forma tradicional

        var manchete = $('#manchete').val();
        var url = $('#url').val();
        var dataPub = $('#dataPub').val();

        //verificando se existi um arquivo armazenado no input type='file', #capaAnime. Se não existir, pega o File do #upload-icon
        if(ultimaImgArquivo){
            var capaNoticia = ultimaImgArquivo;
        }else{
            var capaNoticia = file_upload_icon;
        }


        // Validação
        if (manchete === '') {
            $('#mensagem-error').html("Manchete não digitada").fadeIn(300).delay(2000).fadeOut(400);
        } else if (url === '') {
            $('#mensagem-error').html('URL inválida').fadeIn(300).delay(2000).fadeOut(400);
        } else if (dataPub === "" || dataPub === "dd/mm/aaaa") {
            $('#mensagem-error').html("Data inválida").fadeIn(300).delay(2000).fadeOut(400);
        } else if (!capaNoticia) {
            $('#mensagem-error').html("Imagem não selecionada").fadeIn(300).delay(2000).fadeOut(400);
        } else if (!['image/jpeg', 'image/png', 'image/bmp'].includes(capaNoticia.type)) {
            $('#mensagem-error').html("Formato de imagem inválido").fadeIn(300).delay(2000).fadeOut(400);
        } else if (capaNoticia.size > 2 * 1024 * 1024) { // Limite máximo é de 2MB
            $('#mensagem-error').html("Imagem muito grande. Máximo de 2MB").fadeIn(300).delay(2000).fadeOut(400);
        } else{

            // Se tudo estiver correto, faça a chamada AJAX
            var formData = new FormData();
            formData.append('manchete', manchete);
            formData.append('url', url);
            formData.append('dataPub', dataPub);
            formData.append('capaNoticia', capaNoticia);

            $.ajax({
                url: '../php/editaNoticia.php?id=' + noticiaId,
                type: 'POST',
                data: formData, // Envia os dados como formData
                processData: false, // Impede o JQuery de processar os dados
                contentType: false, // Impede o JQuery de definir o tipo de conteúdo
                success: function(result) {
                    console.log(result);
                    if (result.trim() == "success") {
                        $('#mensagem-success').html("Editado com sucesso!!").fadeIn(300).delay(2000).fadeOut(400);
                        console.log('Arquivo no upload_icon: ' + upload_icon);
                        console.log('Arquivo no capaNoticia: ' + capaNoticia);
                        // Limpa o formulário e o preview após o sucesso
                        /*$('#titulo').val('');
                        $('#descricao').val('');
                        $('#qtdEpisodios').val('');
                        $('#data').val('');
                        $('#capaAnime').val('');*/
                        //$('#preview-image').remove();
                        //$('input:checked').prop('checked', false); //Limpa os checkboxes
                        //upload_icon.src = ultimaImgNotSelec;
                        //upload_icon.style.display = 'flex';
                        //upload_icon.style.height = '50px';
                        //upload_icon.style.width = '50px';
                        //upload_icon.style.opacity = '0.2';
                        //legenda.style.display = 'flex';
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

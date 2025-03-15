// Primeira parte do código: Mostrar um preview da imagem carregada
const capaNoticia = document.querySelector('#capaNoticia'); 
const uploadIcon = document.querySelector('#upload-icon');
const legend = document.querySelector('#legend');

let ultimaImgSelec;
let ultimaImgArquivo = null;

capaNoticia.addEventListener('change', event => {
    // Verificando se já foi carregado uma outra imagem, assim, caso tenha, será removida e substituída
    const previewExistente = document.querySelector('#preview-image');
    if (previewExistente) {
        previewExistente.remove();//remove a imagem anterior
    }

    if(capaNoticia.files && capaNoticia.files.length > 0 && capaNoticia.files[0]){
        //armazena o arquivo do seletor de arquivo aqui
        ultimaImgArquivo = capaNoticia.files[0];

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

            // Armazena a URL na ultima imagem carregada
            ultimaImgSelec = event.target.result;
        };

        reader.readAsDataURL(ultimaImgArquivo);

    }else{

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

    
});

console.log('Último arquivo selecionado: ' + ultimaImgArquivo);

// Validação do formulário e envio via AJAX
$(document).ready(function() {
    $('#cad_noticia').click(function(event) {
        event.preventDefault(); // Impede o envio do formulário de forma tradicional

        var manchete = $('#manchete').val();
        var url = $('#url').val();
        var dataPub = $('#dataPub').val();
        var capaNoticia = $('#capaNoticia')[0].files[0] ? $('#capaNoticia')[0].files[0] : ultimaImgArquivo;

        // Validação
        if (checkForm()) {
            // Se tudo estiver correto, faça a chamada AJAX
            var formData = new FormData();
            formData.append('manchete', manchete);
            formData.append('url', url);
            formData.append('dataPub', dataPub);
            formData.append('capaNoticia', capaNoticia);

            $.ajax({
                url: '../php/cadastraNoticia.php',
                type: 'POST',
                data: formData, // Envia os dados como formData
                processData: false, // Impede o JQuery de processar os dados
                contentType: false, // Impede o JQuery de definir o tipo de conteúdo
                success: function(result) {
                    if (result == "success") {
                        $('#mensagem-success').html("Cadastrado com sucesso").fadeIn(300).delay(2000).fadeOut(400);
                        // Limpa o formulário e o preview após o sucesso
                        $('#manchete').val('');
                        $('#url').val('');
                        $('#dataPub').val('');
                        $('#data').val('');
                        $('#capaNoticia').val('');
                        $('#preview-image').remove();
                        uploadIcon.style.display = 'flex';
                        legend.style.display = 'flex';
                    } else {
                        $('#mensagem-error').html("Não foi possível realizar o cadastro").fadeIn(300).delay(2000).fadeOut(400);
                    }
                },
                error: function(xhr, status, error) {
                    $('#mensagem-error').html("Erro: " + xhr.responseText).fadeIn(300).delay(2000).fadeOut(400);
                }
            });
        }

        function checkForm() {
            if (manchete === '') {
                $('#mensagem-error').html("Manchete não digitada").fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (url === '') {
                $('#mensagem-error').html('Url inválida').fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (!(url.includes('http://') || url.includes('https://'))) {
                $('#mensagem-error').html('Url Inválida').fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (dataPub === "" || dataPub === "dd/mm/aaaa") {
                $('#mensagem-error').html("Data inválida").fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (!capaNoticia) {
                $('#mensagem-error').html("Imagem não selecionada").fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (!['image/jpeg', 'image/png', 'image/bmp'].includes(capaNoticia.type)) {
                $('#mensagem-error').html("Formato de imagem inválido").fadeIn(300).delay(2000).fadeOut(400);
                return false;
            } else if (capaNoticia.size > 2 * 1024 * 1024) { // Limite máximo é de 2MB
                $('#mensagem-error').html("Imagem muito grande. Máximo de 2MB").fadeIn(300).delay(2000).fadeOut(400);
                return false;
            }
            return true;
        }

    });
});



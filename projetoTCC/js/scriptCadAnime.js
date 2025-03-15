// Primeira parte do código: Mostrar um preview da imagem carregada
const capaAnime = document.querySelector('#capaAnime'); 
const uploadIcon = document.querySelector('#upload-icon');
const legend = document.querySelector('#legend');

let ultimaImgSelec;
let ultimaImgArquivo = null;


capaAnime.addEventListener('change', event => {
    // Verificando se já foi carregado uma outra imagem, assim, caso tenha, será removida e substituída
    const previewExistente = document.querySelector('#preview-image');
    if (previewExistente) {
        previewExistente.remove(); // Remove a imagem anterior
    }

    if(capaAnime.files && capaAnime.files.length > 0 && capaAnime.files[0]) { // Verifica se o usuário selecionou uma imagem.
        // Validação da imagem selecionada 
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

            // Armazena a URL na ultima imagem carregada
            ultimaImgSelec = event.target.result;
        };

        reader.readAsDataURL(ultimaImgArquivo);

    } else {
        // Se nenhum arquivo foi selecionado e se o usuario fechar o seletor de arquivos, exibe a imagem atual, se houver
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
    $('#cad_anime').click(function(event) {
        event.preventDefault(); // Impede o envio do formulário de forma tradicional

        var titulo = $('#titulo').val();
        var descricao = $('#descricao').val();
        var qtdEpisodios = $('#qtdEpisodios').val();
        var data = $('#data').val();
        var capaAnime = $('#capaAnime')[0].files[0] ? $('#capaAnime')[0].files[0] : ultimaImgArquivo;
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
                url: '../php/cadastraAnime.php',
                type: 'POST',
                data: formData, // Envia os dados como formData
                processData: false, // Impede o JQuery de processar os dados
                contentType: false, // Impede o JQuery de definir o tipo de conteúdo
                success: function(result) {
                    console.log(result);
                    if (result.trim() == "success") {
                        $('#mensagem-success').html("Cadastrado com sucesso").fadeIn(300).delay(2000).fadeOut(400);
                        // Limpa o formulário e o preview após o sucesso
                        $('#titulo').val('');
                        $('#descricao').val('');
                        $('#qtdEpisodios').val('');
                        $('#data').val('');
                        $('#capaAnime').val('');
                        $('#preview-image').remove();
                        $('input:checked').prop('checked', false); //Limpa os checkboxes
                        uploadIcon.style.display = 'flex';
                        //uploadIcon.src = "../imgs/nuvem.png";
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
    });
});

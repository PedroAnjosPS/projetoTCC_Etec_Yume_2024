<!DOCTYPE html>
<html>
<head>
  <title>TelaLogin</title>
  <link rel="stylesheet" href="../css/log.css">
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Script -->
  
  

  <style>
    a , button{
      display: inline-block;
    }
  </style>

</head>
<body>
  <div class="container">
    <h2>Faça Seu Login</h2>
    <div role="alert" id="mensagem-error" style="display: none;"> Mensagem  </div>
    <div role="alert" id="mensagem-success" style="display: none;"> Mensagem  </div>
    <form>
      <input type="email" id="email" name="email" placeholder="Email" required>
      <br>
      <input type="password" id="senha" name="senha" placeholder="Senha" required>
      <div id="verSenha" > </div>

      <div class="Entrar_Voltar">
        <button type="button" id="botaoLogar">Entrar</button>
        <a href="index.php" style="height: 40px; width: 0px;"><button type="button" id="voltar">Voltar</button></a> 
      </div>
    </form>
  </div>

  <!-- Modal para confirmar exclusão do anime -->
  <dialog class="popUp" id="modal">
                    <p id="text-modal">Deseja reativar a sua conta?</p>
                    <div class="dialog-buttons" id="modal-buttons">
                        <button id="confirm-modal" style="width: auto; height: auto;">Sim</button>
                        <button id="deny-modal" style="width: auto; height: auto;">Não</button>
                    </div>
  </dialog>


  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="../js/scriptsLogin.js"></script>
</body>
</html> 

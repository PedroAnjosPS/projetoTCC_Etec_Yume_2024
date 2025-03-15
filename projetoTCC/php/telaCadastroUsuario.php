<!DOCTYPE html>
<html>
<head>
  <title>TelaCadastro</title>
  <link rel="stylesheet" href="../css/cad.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  
</head>

<body>
  <div class="container">
    <h2>Cadastro de Usuário</h2>

      <div role="alert" id="mensagem-error" style="display: none;"> Mensagem  </div>
      <div role="alert" id="mensagem-success" style="display: none;"> Mensagem  </div>
    <form>
      <input type="text" id="nome" name="nome" placeholder="Nome"  required>

      <input type="email" id="email" name="email" placeholder="Email" required>

      <input type="password" id="senha" name="senha" placeholder="Senha" required>
      <div id="verSenha1" > </div>
      
      <input type="password" id="confirma-senha" name="confirma-senha" placeholder="Confirmar Senha" required>
      <div id="verSenha2" > </div>

      <div style="margin-left: 20px;"> 
      <button id="cadastrar" class="cadastrar_voltar" type="button">Cadastrar</button>
      <a href="index.php"><button type="button" id="voltar" class="cadastrar_voltar" type="reset">Voltar</button></a>
      </div>
    </form>
  </div>
</body>
<script src="../js/scriptsCadastro.js"></script>
</html>
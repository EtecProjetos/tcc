<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Esqueci minha senha</title>
</head>
<body>
  <h2>Recuperar Senha</h2>

  <?php
  if(isset($_SESSION['msg'])){
      echo "<p style='color:red'>".$_SESSION['msg']."</p>";
      unset($_SESSION['msg']);
  }
  ?>

  <form action="enviar_token.php" method="POST">
      <label>E-mail:</label><br>
      <input type="email" name="email" required><br><br>

      <label>Você é:</label><br>
      <select name="tipo" required>
          <option value="professor">Professor</option>
          <option value="aluno">Aluno</option>
      </select><br><br>

      <button type="submit">Enviar link</button>
  </form>
</body>
</html>

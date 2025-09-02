<?php
session_start();
include '../Back/conexao.php'; // ajuste o caminho conforme a localização real do arquivo
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Criar Conta - Professor | New Football</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#4c0070" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" />

    <style>
        body { margin: 0; background-color: #4c0070; font-family: Arial, sans-serif; color: white;
               display: flex; justify-content: center; align-items: center; height: 100vh; padding: 15px; }
        .container { background-color: #7a0ea4; padding: 30px 25px; border-radius: 15px; 
                     box-shadow: 0 4px 10px rgba(0,0,0,0.4); width: 100%; max-width: 500px; text-align: center; }
        h2 { margin-bottom: 20px; font-weight: bold; text-transform: uppercase; }
        form { text-align: left; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: none; border-radius: 8px; font-size: 1rem; }
        .form-group input:focus { outline: none; box-shadow: 0 0 5px #ffd700; }
        button { width: 100%; background-color: #ffd700; color: #4b0082; border: none; padding: 12px; font-size: 1.1rem;
                 font-weight: bold; border-radius: 10px; cursor: pointer; transition: background-color 0.3s ease; margin-top: 10px; }
        button:hover { background-color: #ffe34d; }
        .msg { margin-bottom: 15px; padding: 10px; border-radius: 6px; font-weight: bold; text-align: center; }
        .erro { background: #ff4d4d; color: white; }
        .sucesso { background: #28a745; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2>Criar Conta - Professor</h2>

    <?php
    if (isset($_SESSION['erro_cadastro'])) {
        echo "<div class='msg erro'>".$_SESSION['erro_cadastro']."</div>";
        unset($_SESSION['erro_cadastro']);
    }
    if (isset($_SESSION['sucesso_cadastro'])) {
        echo "<div class='msg sucesso'>".$_SESSION['sucesso_cadastro']."</div>";
        unset($_SESSION['sucesso_cadastro']);
    }
    ?>

    <form action="processar_cadastro_professor.php" method="POST">
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome" required />
        </div>

        <div class="form-group">
            <label>Data de nascimento</label>
            <input type="date" name="data_nascimento" required />
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" required maxlength="14" />
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required />
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" />
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required />
        </div>

        <div class="form-group">
            <label>Confirmar Senha</label>
            <input type="password" name="confirmar_senha" required />
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <p style="margin-top:15px;">
        Já tem conta? <a href="loginProfessor.php" style="color:#ffd700; font-weight:bold;">Faça login</a>
    </p>
</div>
</body>
</html>

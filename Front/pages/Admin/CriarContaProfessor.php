<?php
session_start();
include '../../../Back/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta Professor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" />
    <link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000, #4c0070);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 15px;
        }
        .login-container {
            background-color: #7a0ea4;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .login-container h2 {
            margin-top: 0;
            margin-bottom: 25px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #FFD700;
        }
        .erro, .sucesso {
            margin-bottom: 15px;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }
        .erro { background-color: #ff4d4d; color: white; }
        .sucesso { background-color: #90ee90; color: #000; }
        form { text-align: left; display: flex; flex-direction: column; gap: 15px; }
        .input-icon { position: relative; }
        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #4b0082;
            font-size: 1.2rem;
            pointer-events: none;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"] {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
        }
        input:focus { outline: none; box-shadow: 0 0 5px #ffd700; }
        button[type="submit"] {
            width: 100%;
            background-color: #ffd700;
            color: #4b0082;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        button[type="submit"]:hover { background-color: #ffe34d; }
        .link { text-align: center; margin-top: 10px; font-size: 0.95rem; }
        .link a { color: #FFD700; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
        @media (max-width: 480px) {
            .login-container { padding: 20px 15px; }
            button[type="submit"] { font-size: 1rem; padding: 12px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Criar Conta Professor</h2>

    <?php
    if(isset($_SESSION['erro_cadastro'])){
        echo '<div class="erro">'.$_SESSION['erro_cadastro'].'</div>';
        unset($_SESSION['erro_cadastro']);
    }
    if(isset($_SESSION['cadastro_ok'])){
        echo '<div class="sucesso">'.$_SESSION['cadastro_ok'].'</div>';
        unset($_SESSION['cadastro_ok']);
    }
    ?>

    <form action="processar_cadastro_professor.php" method="POST">
        <div class="input-icon">
            <i class="bi bi-person-fill"></i>
            <input type="text" name="nome" placeholder="Nome completo" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-calendar-fill"></i>
            <input type="text" name="data_nascimento" placeholder="Data de Nascimento" onfocus="(this.type='date')" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <input type="text" name="cpf" maxlength="14" placeholder="CPF" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" placeholder="E-mail">
        </div>
        <div class="input-icon">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="senha" placeholder="Senha" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-telephone-fill"></i>
            <input type="text" name="telefone" placeholder="Telefone">
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <div class="link">
        <a href="home_admin.php">← Voltar</a>
    </div>
</div>

</body>
</html>

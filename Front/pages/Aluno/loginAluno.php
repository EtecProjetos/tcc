<?php
session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['aluno_id'])) {
    header("Location: home_aluno.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Aluno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" />
    <link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; background: linear-gradient(135deg, #000000, #4c0070); 
            font-family: Arial, sans-serif; color: white;
            display: flex; justify-content: center; align-items: center; height: 100vh; padding: 15px;
        }
        .login-container {
            background-color: #7a0ea4; padding: 30px 25px; border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4); width: 100%; max-width: 400px; text-align: center;
        }
        .login-container h2 { margin-top: 0; margin-bottom: 25px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .erro { margin-bottom: 15px; font-weight: bold; color: #ff4d4d; }
        .sucesso { margin-bottom: 15px; font-weight: bold; color: #90ee90; }
        form { text-align: left; }
        .input-icon { position: relative; margin-bottom: 20px; }
        .input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #4b0082; font-size: 1.3rem; pointer-events: none; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 12px 15px 12px 40px; border: none; border-radius: 10px; font-size: 1rem;
        }
        input[type="email"]:focus, input[type="password"]:focus { outline: none; box-shadow: 0 0 5px #ffd700; }
        .forgot-link { text-align: right; margin-top: -15px; margin-bottom: 15px; }
        .forgot-link a { color: #fff7a3; font-size: 0.9rem; text-decoration: none; }
        .forgot-link a:hover { text-decoration: underline; }
        button[type="submit"] {
            width: 100%; background-color: #ffd700; color: #4b0082; border: none; padding: 15px;
            font-size: 1.1rem; font-weight: bold; border-radius: 12px; cursor: pointer;
            transition: background-color 0.3s ease; text-transform: uppercase; letter-spacing: 1.5px;
            box-shadow: 0 3px 7px rgba(0,0,0,0.3); margin-bottom: 25px;
        }
        button[type="submit"]:hover { background-color: #ffe34d; }
        .btn-group { display: flex; justify-content: space-between; gap: 15px; }
        .btn-group button {
            flex: 1; padding: 12px 0; font-weight: bold; font-size: 1rem; border-radius: 12px; cursor: pointer;
            border: none; text-transform: uppercase; letter-spacing: 1.2px; box-shadow: 0 3px 7px rgba(0,0,0,0.3);
            display: flex; align-items: center; justify-content: center; gap: 8px; transition: background-color 0.3s ease;
        }
        .btn-aluno { background-color: #ffd700; color: #4b0082; }
        .btn-aluno:hover { background-color: #ffe34d; }
        .btn-professor { background-color: #4b0082; color: #ffd700; border: 2px solid #ffd700; }
        .btn-professor:hover { background-color: #6a1aa0; color: #fff; border-color: #fff; }
        .btn-group i { font-size: 1.3rem; }
        .create-account { text-align: center; margin-top: 20px; font-size: 0.95rem; }
        .create-account a { color: #ffd700; font-weight: bold; text-decoration: none; }
        .create-account a:hover { text-decoration: underline; }
        @media (max-width: 480px) { .login-container { padding: 20px 15px; } button[type="submit"], .btn-group button { font-size: 1rem; padding: 12px 0; } }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Aluno</h2>

    <?php
    if(isset($_SESSION['erro_login'])){
        echo '<div class="erro">'.$_SESSION['erro_login'].'</div>';
        unset($_SESSION['erro_login']);
    }
    if(isset($_SESSION['logout_ok'])){
        echo '<div class="sucesso">Sessão encerrada.</div>';
        unset($_SESSION['logout_ok']);
    }
    ?>

    <form action="validar_login.php" method="POST">
        <input type="hidden" name="tipo" value="aluno">

        <div class="input-icon">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" placeholder="E-mail" required autocomplete="username">
        </div>

        <div class="input-icon">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="senha" placeholder="Senha" required autocomplete="current-password">
        </div>

        <!-- <div class="forgot-link">
            <a href="Esqueci_Senha.php">Esqueci minha senha</a>
        </div> -->

        <button type="submit">Entrar</button>
    </form>

    <div class="btn-group">
        <button class="btn-aluno" onclick="location.reload()">
            <i class="bi bi-person-fill"></i> Aluno
        </button>
        <button class="btn-professor" onclick="location.href='../Professor/loginProfessor.php'">
            <i class="bi bi-person-badge-fill"></i> Professor
        </button>
    </div>


</div>

</body>
</html>

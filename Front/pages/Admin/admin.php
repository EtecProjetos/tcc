<?php
session_start();
include '../../../Back/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login Admin - New Football</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #000000, #4c0070); /* Degradê preto → roxo */
    color: white;
}

.container {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 50px 40px;
    border-radius: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.6);
    width: 100%;
    max-width: 450px;
    text-align: center;
    backdrop-filter: blur(5px);
}

h2 {
    margin-bottom: 30px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

form {
    text-align: left;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #7a0ea4;
    color: white;
}

.form-group input:focus {
    outline: none;
    box-shadow: 0 0 8px #a020f0;
}

button {
    width: 100%;
    padding: 16px;
    font-size: 1.1rem;
    font-weight: bold;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, #7a0ea4, #a020f0);
    color: white;
    transition: all 0.3s ease;
    margin-top: 10px;
}

button:hover {
    background: linear-gradient(135deg, #a020f0, #b356f5);
    transform: scale(1.03);
}

.msg {
    margin-bottom: 18px;
    padding: 12px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
}

.erro {
    background: #ff4d4d;
    color: white;
}

.sucesso {
    background: #28a745;
    color: white;
}
</style>
</head>
<body>
<div class="container">
    <h2>Login Admin</h2>

    <?php
    if(isset($_SESSION['erro_login'])){
        echo '<div class="msg erro">'.$_SESSION['erro_login'].'</div>';
        unset($_SESSION['erro_login']);
    }
    if(isset($_SESSION['sucesso_login'])){
        echo '<div class="msg sucesso">'.$_SESSION['sucesso_login'].'</div>';
        unset($_SESSION['sucesso_login']);
    }
    ?>

    <form action="../../../Back/validar_login/validar_login_admin.php" method="POST">
        <div class="form-group">
            <label>Usuário</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>

        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>

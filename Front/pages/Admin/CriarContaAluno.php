<?php
session_start();
include '../../../Back/conexao.php';

// Buscar turmas disponíveis
$turmas = [];
$result = $conn->query("SELECT id, nome FROM turmas ORDER BY nome ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $turmas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta Aluno</title>
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
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], select {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
        }
        input:focus, select:focus { outline: none; box-shadow: 0 0 5px #ffd700; }
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
        .input-icon {
    position: relative;
}
.input-icon .placeholder {
    position: absolute;
    left: 40px;
    top: 50%;
    transform: translateY(-50%);
    color: #ccc;
    pointer-events: none;
    font-size: 0.95rem;
}
.input-icon input:focus + .placeholder,
.input-icon input:not(:placeholder-shown) + .placeholder {
    display: none;
}
.link.voltar {
    text-align: center;
}

.link.voltar a {
    background: none;
    color: #FFD700;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.link.voltar a:hover {
    color: #fff;
}

    </style>
</head>
<body>

<div class="login-container">
    <h2>Criar Conta Aluno</h2>

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

    <form action="processar_cadastro_aluno.php" method="POST">
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
            <input type="email" name="email" placeholder="E-mail" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="senha" placeholder="Senha" required>
        </div>
        <div class="input-icon">
            <i class="bi bi-telephone-fill"></i>
            <input type="text" name="telefone" placeholder="Telefone">
        </div>
        <div class="input-icon">
            <i class="bi bi-person-badge-fill"></i>
            <input type="text" name="nome_responsavel" placeholder="Nome do responsável">
        </div>
        <div class="input-icon">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <input type="text" name="cpf_responsavel" maxlength="14" placeholder="CPF do responsável">
        </div>
        <div class="input-icon">
            <i class="bi bi-building-fill"></i>
            <select name="turma_id" required>
                <option value="">Selecione uma turma</option>
                <?php foreach($turmas as $t){ ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Cadastrar</button>
<div class="link voltar" style="margin-bottom:15px;">
    <a href="home_admin.php">← Voltar</a>
</div>
    </form>


</div>

</body>
</html>

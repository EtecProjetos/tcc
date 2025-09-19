<?php
session_start();

// Impede cache da página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

include '../back/conexao.php';

$erro = '';
$sucesso = '';

if (isset($_SESSION['mensagem_sucesso'])) {
    $sucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}
if (isset($_SESSION['mensagem_erro'])) {
    $erro = $_SESSION['mensagem_erro'];
    unset($_SESSION['mensagem_erro']);
}

$professor_id = $_SESSION['professor_id'] ?? 1;

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Atualiza perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar_perfil') {
    $nome = trim($_POST['nome']);
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    if (!$nome || !$data_nascimento || !$cpf || !$email) {
        $_SESSION['mensagem_erro'] = 'Preencha os campos obrigatórios.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    $stmt = $conn->prepare("SELECT nome, data_nascimento, cpf, email, telefone FROM professores WHERE id = ?");
    $stmt->bind_param("i", $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados_atuais = $result->fetch_assoc();
    $stmt->close();

    $alterou = (
        $nome !== $dados_atuais['nome'] ||
        $data_nascimento !== $dados_atuais['data_nascimento'] ||
        $cpf !== $dados_atuais['cpf'] ||
        $email !== $dados_atuais['email'] ||
        $telefone !== $dados_atuais['telefone']
    );

    if ($alterou) {
        $stmt = $conn->prepare("UPDATE professores SET nome=?, data_nascimento=?, cpf=?, email=?, telefone=? WHERE id=?");
        $stmt->bind_param("sssssi", $nome, $data_nascimento, $cpf, $email, $telefone, $professor_id);
        if ($stmt->execute()) {
            $_SESSION['mensagem_sucesso'] = 'Perfil atualizado com sucesso!';
        } else {
            $_SESSION['mensagem_erro'] = 'Erro ao atualizar o perfil: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $_SESSION['mensagem_erro'] = 'Nenhuma alteração detectada.';
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Busca dados do professor
$stmt = $conn->prepare("SELECT * FROM professores WHERE id = ?");
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result = $stmt->get_result();
$professor = $result->fetch_assoc();
$stmt->close();

if (!$professor) die("Professor não encontrado.");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Perfil Professor</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

<style>
body {
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    font-family: 'Fredoka', sans-serif;
    margin: 0;
    padding-bottom: 120px;
}

header.logo-header {
    margin-bottom: 30px;
    text-align: center;
}
header.logo-header .logo {
    width: 180px;
    height: auto;
}

.container {
    max-width: 600px;
    margin: 30px auto 100px auto;
    background: rgba(138, 58, 185, 0.9);
    border-radius: 15px;
    padding: 20px 30px 40px 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    text-align: left;
}
.container:hover {
    box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.7);
    transition: box-shadow 0.5s ease;
}

h1 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input[type=text],
input[type=date],
input[type=email],
input[type=tel] {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: none;
    font-size: 1em;
    box-sizing: border-box;
}

input[type=text]:focus,
input[type=date]:focus,
input[type=email]:focus,
input[type=tel]:focus {
    outline: 2px solid #ffd700;
}

.btn_salvar {
    margin-top: 20px;
    background-color: #ffd700;
    border: none;
    color: #4b0082;
    font-weight: bold;
    font-size: 1.2em;
    padding: 12px;
    border-radius: 25px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}
.btn_salvar:hover {
    background-color: #ffe34d;
}

.msg-error, .msg-success {
    text-align: center;
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 12px;
    font-weight: bold;
}
.msg-error { background-color: #a80000; color: white; }
.msg-success { background-color: #2e8b57; color: white; }

@media (max-width: 650px) {
    .container { margin: 15px 15px 100px 15px; padding: 15px 20px 30px 20px; }
}
</style>
</head>
<body>

<header class="logo-header">
    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="container">
    <h1>Perfil</h1>

    <?php if ($erro): ?>
        <div class="msg-error"><?= h($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="msg-success"><?= h($sucesso) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="acao" value="atualizar_perfil" />

        <label for="nome">Nome </label>
        <input type="text" id="nome" name="nome" required maxlength="100" value="<?= h($professor['nome']) ?>" />

        <label for="data_nascimento">Data de Nascimento </label>
        <input type="date" id="data_nascimento" name="data_nascimento" required value="<?= h($professor['data_nascimento']) ?>" />

        <label for="cpf">CPF </label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00" value="<?= h($professor['cpf']) ?>" />

        <label for="email">E-mail </label>
        <input type="email" id="email" name="email" required maxlength="100" value="<?= h($professor['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= h($professor['telefone']) ?>" />

        <input type="submit" class="btn_salvar" value="Salvar Alterações" />
    </form>
</div>

<div id="nav-placeholder"></div>

<script src="js/nav_professor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const msg = document.querySelector('.msg-error') || document.querySelector('.msg-success');
    if (msg) {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 3000);
    }
});
</script>

</body>
</html>

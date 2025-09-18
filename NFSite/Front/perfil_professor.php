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

// Recupera e limpa mensagens da sessão
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

// Processa atualização do perfil
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

    // Busca dados atuais
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

if (!$professor) {
    die("Professor não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Perfil</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
<style>
body {
    margin: 0;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    font-family: 'Roboto', Arial, sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 10px;
    color: #4b0082;
}

header.logo-header {
    margin-bottom: 30px;
}
header.logo-header .logo {
    width: 180px;
    display: block;
    margin: 0 auto;
}

.container {
    background: #fff;
    border-radius: 16px;
    max-width: 500px;
    width: 100%;
    padding: 30px 35px;
    box-shadow: 0 4px 20px rgba(111, 45, 168, 0.3);
    color: #4b0082;
    box-sizing: border-box;
}

h1 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 25px;
    color: #6f2da8;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 500;
    color: #4b0082;
    margin-top: 20px; /* espaçamento entre campos */
}

input[type=text],
input[type=date],
input[type=email],
input[type=tel] {
    padding: 14px 16px;
    font-size: 16px;
    border: 2px solid #6f2da8;
    border-radius: 14px;
    color: #4b0082;
    margin-top: 8px;
    outline-offset: 2px;
}

input[type=text]:focus,
input[type=date]:focus,
input[type=email]:focus,
input[type=tel]:focus {
    border-color: #390062;
    outline: none;
}

.btn_salvar {
    margin-top: 25px;
    background-color: #ffd700;
    border: none;
    color: #4b0082;
    font-weight: 700;
    font-size: 1.2rem;
    padding: 14px 0;
    border-radius: 25px;
    cursor: pointer;
    width: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: background-color 0.3s ease;
}
.btn_salvar:hover {
    background-color: #ffe345ff;
}

.btn_voltar {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #4b0082;
    font-weight: 700;
    text-align: center;
    width: 100%;
    padding: 12px 0;
    border-radius: 25px;
    background-color: #e0d600;
    transition: background-color 0.3s ease;
}
.btn_voltar:hover {
    background-color: #d4c500;
}

.msg-error, .msg-success {
    text-align: center;
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
}
.msg-error { background-color: #a80000; color: white; }
.msg-success { background-color: #28a745; color: white; }

@media (max-width: 480px) {
    .container { padding: 20px; }
    h1 { font-size: 1.6rem; }
    input[type=text],
    input[type=date],
    input[type=email],
    input[type=tel],
    .btn_salvar,
    .btn_voltar {
        font-size: 14px;
    }
}
.back-link {
    display: block;           /* bloco para centralizar */
    margin: 25px auto 0 auto; /* 25px de margin-top e centralizado horizontalmente */
    text-decoration: none;
    color: #6f2da8;
    font-weight: 700;
    text-align: center;
    width: fit-content;       /* ajusta a largura ao conteúdo */
    padding: 12px 20px;       /* deixa mais clicável */
    border-radius: 25px;
    user-select: none;


}
.back-link:hover {
    color: #000000ff;
   
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

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required maxlength="100" value="<?= h($professor['nome']) ?>" />

        <label for="data_nascimento">Data de Nascimento</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required value="<?= h($professor['data_nascimento']) ?>" />

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00" value="<?= h($professor['cpf']) ?>" />

        <label for="email">E-mail </label>
        <input type="email" id="email" name="email" required maxlength="100" value="<?= h($professor['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= h($professor['telefone']) ?>" />

        <input type="submit" class="btn_salvar" value="Salvar Alterações" />
        <!-- Dentro do form ou abaixo dele -->
<a href="home_professor.php" class="back-link">← Voltar para Treinos</a>

    </form>

</div>

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

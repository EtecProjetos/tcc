<?php
session_start();
include '../../../Back/conexao.php';

// Impede cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Verifica login do aluno
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$erro = '';
$sucesso = '';
$aluno_id = $_SESSION['aluno_id'];

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
    $nome_responsavel = trim($_POST['nome_responsavel']);
    $cpf_responsavel = trim($_POST['cpf_responsavel']);

    if (!$nome || !$data_nascimento || !$cpf || !$email) {
        $erro = 'Preencha os campos obrigatórios.';
    } else {
        // Busca dados atuais
        $stmt = $conn->prepare("
            SELECT nome, data_nascimento, cpf, email, telefone, nome_responsavel, cpf_responsavel
            FROM alunos
            WHERE id = ?
        ");
        $stmt->bind_param("i", $aluno_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $dados = $res->fetch_assoc();
        $stmt->close();

        if (!$dados) {
            $erro = 'Aluno não encontrado.';
        } else {
            // Verifica se houve alteração
            $alterou = (
                $nome !== $dados['nome'] ||
                $data_nascimento !== $dados['data_nascimento'] ||
                $cpf !== $dados['cpf'] ||
                $email !== $dados['email'] ||
                $telefone !== $dados['telefone'] ||
                $nome_responsavel !== $dados['nome_responsavel'] ||
                $cpf_responsavel !== $dados['cpf_responsavel']
            );

            if ($alterou) {
                // Atualiza os dados
                $stmt = $conn->prepare("
                    UPDATE alunos
                    SET nome=?, data_nascimento=?, cpf=?, email=?, telefone=?, nome_responsavel=?, cpf_responsavel=?
                    WHERE id=?
                ");
                $stmt->bind_param("sssssssi", $nome, $data_nascimento, $cpf, $email, $telefone, $nome_responsavel, $cpf_responsavel, $aluno_id);

                if ($stmt->execute()) {
                    $sucesso = 'Perfil atualizado com sucesso!';
                } else {
                    $erro = 'Erro ao atualizar perfil: ' . $conn->error;
                }
                $stmt->close();
            } else {
                $erro = 'Nenhuma alteração detectada.';
            }
        }
    }
}

// Busca dados do aluno para preencher o formulário
$stmt = $conn->prepare("
    SELECT nome, data_nascimento, cpf, email, telefone, nome_responsavel, cpf_responsavel
    FROM alunos
    WHERE id = ?
");
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$res = $stmt->get_result();
$aluno = $res->fetch_assoc();
$stmt->close();

if (!$aluno) die("Aluno não encontrado.");
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Perfil</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">

<style>
body {
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: white;
    font-family: 'Fredoka', sans-serif;
    margin: 0;
    padding-bottom: 120px;
}
.container {
    max-width: 600px;
    margin: 30px auto 100px auto;
    background: rgba(138, 58, 185, 0.9);
    border-radius: 15px;
    padding: 20px 30px 40px 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
}
.container:hover {
    box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.7);
    transition: box-shadow 0.5s ease;
}
h1 { text-align: center; margin-bottom: 25px; font-weight: bold; }
label { display: block; margin-top: 15px; font-weight: bold; }
input[type=text], input[type=date], input[type=email], input[type=tel] {
    width: 100%; padding: 10px; margin-top: 6px; border-radius: 8px; border: none; font-size: 1em; box-sizing: border-box;
}
.btn_salvar {
    margin-top: 20px; background-color: #ffd700; border: none; color: #4b0082;
    font-weight: bold; font-size: 1.2em; padding: 12px; border-radius: 25px; cursor: pointer; width: 100%; transition: background-color 0.3s ease;
}
input[type=submit]:hover, button:hover { background-color: #ffe34d; } 
.msg-error, .msg-success { text-align: center; margin-bottom: 20px; padding: 12px; border-radius: 12px; font-weight: bold; } 
.msg-error { background-color: #a80000; color: white; }
.msg-success { background-color: #2e8b57; color: white; }
@media (max-width: 650px) {
    .container { margin: 15px 15px 100px 15px; padding: 15px 20px 30px 20px; }
}
header.logo-header { margin-bottom: 30px; }
header.logo-header .logo { width: 180px; display: block; margin: 0 auto; }
</style>
</head>
<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>
<body>

<div class="container">
    <h1>Perfil do Aluno</h1>
<?php if($erro): ?>
    <div class="msg-error"><?= h($erro) ?></div>
<?php elseif($sucesso): ?>
    <div class="msg-success"><?= h($sucesso) ?></div>
<?php endif; ?>


    <form method="post" action="">
        <input type="hidden" name="acao" value="atualizar_perfil" />

        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required maxlength="100" value="<?= h($aluno['nome']) ?>" />

        <label for="data_nascimento">Data de Nascimento</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required value="<?= h($aluno['data_nascimento']) ?>" />

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00" value="<?= h($aluno['cpf']) ?>" />

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required maxlength="100" value="<?= h($aluno['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= h($aluno['telefone']) ?>" />

        <label for="nome_responsavel">Nome do Responsável</label>
        <input type="text" id="nome_responsavel" name="nome_responsavel" maxlength="100" value="<?= h($aluno['nome_responsavel']) ?>" />

        <label for="cpf_responsavel">CPF do Responsável</label>
        <input type="text" id="cpf_responsavel" name="cpf_responsavel" maxlength="14" placeholder="000.000.000-00" value="<?= h($aluno['cpf_responsavel']) ?>" />

        <input type="submit" class="btn_salvar" value="Salvar Alterações" />
    </form>
</div>

<div id="nav-placeholder"></div>
<script src="../../js/nav.js"></script>
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

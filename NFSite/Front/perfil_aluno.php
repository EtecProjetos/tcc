<?php
session_start();
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

$aluno_id = $_SESSION['aluno_id'] ?? 1;

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'atualizar_perfil') {
        $nome = trim($_POST['nome']);
        $data_nascimento = $_POST['data_nascimento'];
        $cpf = trim($_POST['cpf']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        $nome_responsavel = trim($_POST['nome_responsavel']);
        $cpf_responsavel = trim($_POST['cpf_responsavel']);

        if (!$nome || !$data_nascimento || !$cpf || !$email) {
            $_SESSION['mensagem_erro'] = 'Preencha os campos obrigatórios.';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

        $stmt = $conn->prepare("SELECT nome, data_nascimento, cpf, email, telefone, nome_responsavel, cpf_responsavel FROM alunos WHERE id = ?");
        $stmt->bind_param("i", $aluno_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados_atuais = $result->fetch_assoc();
        $stmt->close();

        $alterou = (
            $nome !== $dados_atuais['nome'] ||
            $data_nascimento !== $dados_atuais['data_nascimento'] ||
            $cpf !== $dados_atuais['cpf'] ||
            $email !== $dados_atuais['email'] ||
            $telefone !== $dados_atuais['telefone'] ||
            $nome_responsavel !== $dados_atuais['nome_responsavel'] ||
            $cpf_responsavel !== $dados_atuais['cpf_responsavel']
        );

        if ($alterou) {
            $stmt = $conn->prepare("UPDATE alunos SET nome=?, data_nascimento=?, cpf=?, email=?, telefone=?, nome_responsavel=?, cpf_responsavel=? WHERE id=?");
            $stmt->bind_param("sssssssi", $nome, $data_nascimento, $cpf, $email, $telefone, $nome_responsavel, $cpf_responsavel, $aluno_id);

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
}

$sql = "SELECT * FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result = $stmt->get_result();
$aluno = $result->fetch_assoc();
$stmt->close();

if (!$aluno) {
    die("Aluno não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Perfil do Aluno</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <!-- Fonte Fredoka -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles/styleBase.css">
<style>
body {
    background-color: #4c0070;
    color: white;
    font-family: Arial, sans-serif;
    margin: 0;
    padding-bottom: 120px;
}

.container {
    max-width: 600px;
    margin: 30px auto 100px auto;
    background-color: #7a0ea4;
    border-radius: 15px;
    padding: 20px 30px 40px 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    text-align: left;
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
input[type=tel],
input[type=password] {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: none;
    font-size: 1em;
    box-sizing: border-box;
}

input[type=submit], button {
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

input[type=submit]:hover, button:hover {
    background-color: #ffe34d;
}

.msg-error, .msg-success {
    text-align: center;
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 12px;
    font-weight: bold;
}
.msg-error {
    background-color: #a80000;
    color: white;
}
.msg-success {
    background-color: #2e8b57;
    color: white;
}

@media (max-width: 650px) {
    .container {
        margin: 15px 15px 100px 15px;
        padding: 15px 20px 30px 20px;
    }
}
</style>
</head>
<body>

<div class="container">
    <h1>Perfil</h1>

    <?php if ($erro): ?>
        <div class="msg-error"><?= h($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="msg-success"><?= h($sucesso) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="acao" value="atualizar_perfil" />

        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" required maxlength="100" value="<?= h($aluno['nome']) ?>" />

        <label for="data_nascimento">Data de Nascimento *</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required value="<?= h($aluno['data_nascimento']) ?>" />

        <label for="cpf">CPF *</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14" placeholder="000.000.000-00" value="<?= h($aluno['cpf']) ?>" />

        <label for="email">E-mail *</label>
        <input type="email" id="email" name="email" required maxlength="100" value="<?= h($aluno['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= h($aluno['telefone']) ?>" />

        <label for="nome_responsavel">Nome do Responsável</label>
        <input type="text" id="nome_responsavel" name="nome_responsavel" maxlength="100" value="<?= h($aluno['nome_responsavel']) ?>" />

        <label for="cpf_responsavel">CPF do Responsável</label>
        <input type="text" id="cpf_responsavel" name="cpf_responsavel" maxlength="14" placeholder="000.000.000-00" value="<?= h($aluno['cpf_responsavel']) ?>" />

        <input type="submit" value="Salvar Alterações" />
    </form>
</div>
<div id="nav-placeholder"></div>

  <script>
    fetch("nav.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById("nav-placeholder").innerHTML = data;

        // Reativar scripts do menu lateral após carregar
        const btnToggle = document.getElementById("btn-toggle-popup");
        const btnClose = document.getElementById("btn-close-popup");
        const popupMenu = document.getElementById("popup-menu");

        if (btnToggle && btnClose && popupMenu) {
          btnToggle.addEventListener("click", () => {
            popupMenu.classList.toggle("open");
            popupMenu.setAttribute("aria-hidden", popupMenu.classList.contains("open") ? "false" : "true");
          });

          btnClose.addEventListener("click", () => {
            popupMenu.classList.remove("open");
            popupMenu.setAttribute("aria-hidden", "true");
          });
        }
      });
  </script>

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

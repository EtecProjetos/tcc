<?php
session_start();
include '../../../back/conexao.php';

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
    <title>Criar Conta - New Football</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" />
    <style>
        /* ... Seu CSS permanece igual ... */
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
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome" required>
        </div>

        <div class="form-group">
            <label>Data de nascimento</label>
            <input type="date" name="data_nascimento" required>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" maxlength="14" required>
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone">
        </div>

        <div class="form-group">
            <label>Nome do responsável</label>
            <input type="text" name="nome_responsavel">
        </div>

        <div class="form-group">
            <label>CPF do responsável</label>
            <input type="text" name="cpf_responsavel" maxlength="14">
        </div>

        <div class="form-group">
            <label>Turma</label>
            <select name="turma_id">
                <option value="">Selecione uma turma</option>
                <?php foreach($turmas as $t){ ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <div class="link">
        <a href="loginAluno.php">Já tenho conta</a>
    </div>
</div>
</body>
</html>

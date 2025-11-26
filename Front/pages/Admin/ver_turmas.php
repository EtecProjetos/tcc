<?php
include '../../../Back/conexao.php';
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

$sql = "
SELECT t.id, t.nome, t.horario, t.dias_treino
FROM turmas t
ORDER BY t.nome ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Turmas</title>
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    min-height: 100vh;
}

h1 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: bold;
    color: #FFD700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
    margin-bottom: 30px;
}

.card-tabela {
    background: linear-gradient(135deg, #7a0ea4, #a020f0);
    border-radius: 15px;
    padding: 25px;
    width: 100%;
    max-width: 950px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
    font-size: 15px;
    text-align: center;
}

th, td {
    padding: 12px 8px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

th {
    background-color: rgba(255,255,255,0.15);
    font-weight: 600;
    text-transform: uppercase;
}

tr:hover {
    background-color: rgba(255,255,255,0.1);
}

.btn-editar {
    background: linear-gradient(90deg, #6a0dad, #8e2de2);
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-editar:hover {
    background: linear-gradient(90deg, #8e2de2, #6a0dad);
    transform: scale(1.05);
}

.voltar {
    text-decoration: none;
    color: #FFD700;
    margin-top: 25px;
    font-weight: bold;
    transition: 0.3s;
}

.voltar:hover {
    color: #fff;
}

.vazio {
    text-align: center;
    padding: 20px;
    font-style: italic;
    color: #ffeb99;
}

@media (max-width: 768px) {
    table { font-size: 14px; }
    th, td { padding: 10px; }
}

@media (max-width: 480px) {
    table { font-size: 13px; }
    th, td { padding: 8px; }
    h1 { font-size: 2rem; }
}
.btn-excluir {
    background: linear-gradient(90deg, #ff0000, #ff0000);
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-excluir:hover {
    background-color: #ff0000;
    transform: scale(1.05);
}
</style>
</head>
<body>

<h1>Turmas Cadastradas</h1>

<div class="card-tabela">
    <table>
        <tr>
            <th>Nome</th>
            <th>Horário</th>
            <th>Dias de Treino</th>
            <th>Ações</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= date('H:i', strtotime($row['horario'])) ?></td>
                    <td><?= htmlspecialchars($row['dias_treino'] ?? '—') ?></td>
                    <td style="display: flex; gap: 10px; justify-content: center;">

                        <!-- Botão Editar -->
                        <a href="editar_turma.php?id=<?= $row['id'] ?>" class="btn-editar">
                            Editar
                        </a>

                        <!-- Botão Excluir -->
                        <a href="../../../Back/Excluir/excluir_turma.php?id=<?= $row['id'] ?>" 
                           class="btn-excluir"
                           onclick="return confirm('Tem certeza que deseja excluir esta turma?');">
                            Excluir
                        </a>

                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>
            <tr><td colspan="4" class="vazio">Nenhuma turma cadastrada ainda.</td></tr>
        <?php endif; ?>
    </table>
</div>

<a href="turmas_admin.php" class="voltar">← Voltar</a>

</body>
</html>

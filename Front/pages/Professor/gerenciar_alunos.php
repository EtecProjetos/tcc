<?php
include '../../../back/conexao.php';
session_start();

$professor_id = $_SESSION['professor_id'] ?? 1;

// Pega todas as turmas
$stmt = $conn->prepare("SELECT id, nome FROM turmas ORDER BY nome ASC");
$stmt->execute();
$result = $stmt->get_result();
$turmas = $result->fetch_all(MYSQLI_ASSOC);

// Turma selecionada
$turma_selecionada = $_GET['turma_id'] ?? null;
$alunos = [];

if ($turma_selecionada) {
    $stmt2 = $conn->prepare("SELECT * FROM alunos WHERE turma_id = ? ORDER BY nome ASC");
    $stmt2->bind_param("i", $turma_selecionada);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $alunos = $result2->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gerenciar Alunos</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
/* ================= Corpo ================= */
body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to bottom, #6a0dad, #000);
    margin: 0;
    padding: 20px;
    color: #fff;
    min-height: 100vh;
}
.container {
    max-width: 950px;
    background: #fff;
    color: #333;
    margin: auto;
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(57,0,98,0.5);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #390062;
}

/* ================= Form ================= */
label { font-weight: 600; }
select {
    padding: 10px 15px;
    border-radius: 10px;
    border: 2px solid #000000ff;
    width: 100%;
    max-width: 300px;
    color: #390062;
    font-weight: 500;
    transition: 0.3s;
}
select:focus {
    outline: none;
    box-shadow: 0 0 6px #000000aa;
}

/* ================= Tabela desktop ================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
}
th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #ccc;
    text-align: left;
    vertical-align: middle;
}
tr:nth-child(even) { background: #f9f9f9; }

/* ================= Botões ================= */
button.action-btn {
    background-color: #390062;
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
    margin-right: 6px;
    display: inline-flex;
    align-items: center;
}
button.action-btn:hover { 
    background: linear-gradient(90deg, #5a008a, #8e24aa);
}
button.action-btn i { margin-left: 6px; font-size: 1.1rem; }

/* ================= Modal ================= */
.modal-bg {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: #fff;
    color: #333;
    padding: 25px 30px;
    border-radius: 16px;
    max-width: 450px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 6px 20px rgba(57,0,98,0.4);
    opacity: 0;
    display: none;
    transition: all 0.3s ease;
}
.modal-bg.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
    display: flex;
    flex-direction: column;
}
.modal-close {
    position: absolute;
    top: 12px; right: 12px;
    background: none;
    border: none;
    font-size: 1.6rem;
    cursor: pointer;
    color: #390062;
}

/* ================= Alerta sucesso ================= */
#alertaSucesso {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: #fff;
    color: #390062;
    padding: 25px 40px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(57,0,98,0.4);
    font-weight: 700;
    font-size: 1.2rem;
    text-align: center;
    z-index: 1500;
    display: none;
    opacity: 0;
    transition: all 0.3s ease;
}

/* ================= Responsividade ================= */
@media (max-width: 700px) {
    /* Container menor */
    .container { padding: 20px; }
    
    /* Ocultar cabeçalho da tabela */
    table, thead, tbody, th, td, tr { display: block; }
    thead { display: none; }

    /* Linhas como cards */
    tr {
        background: linear-gradient(135deg, #f3e5ff, #d1c4e9);
        margin-bottom: 20px;
        border-radius: 16px;
        padding: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: transform 0.2s;
        border-bottom: none;
    }
    tr:hover { transform: translateY(-3px); }

    td {
        padding: 8px 12px;
        border: none;
        position: relative;
        display: flex;
        justify-content: space-between;
        font-weight: 500;
        color: #390062;
        margin-bottom: 8px;
    }
    td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #4b0082;
        width: 45%;
    }

    button.action-btn { width: 100%; justify-content: center; margin-top: 10px; }
}

.btn-voltar {
    color: #4b0082;
    text-decoration: none;
    text-align: center;
}
.btn-voltar:hover { color: #000000; }

</style>
</head>
<body>
<div class="container">
    <h2>Gerenciar Alunos</h2>
    <form method="GET" id="formTurma">
        <label for="turma_id">Selecione a turma:</label>
        <select name="turma_id" id="turma_id" required onchange="document.getElementById('formTurma').submit()">
            <option value="" disabled <?= !$turma_selecionada ? 'selected' : '' ?>>Selecione...</option>
            <?php foreach ($turmas as $turma): ?>
                <option value="<?= $turma['id'] ?>" <?= ($turma_selecionada == $turma['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($turma['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($turma_selecionada): ?>
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Idade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alunos) === 0): ?>
                    <tr><td colspan="3" style="text-align:center;">Nenhum aluno nesta turma.</td></tr>
                <?php else: ?>
                    <?php foreach ($alunos as $aluno): 
                        $data_nasc = new DateTime($aluno['data_nascimento']);
                        $idade = $data_nasc->diff(new DateTime())->y;
                    ?>
                    <tr data-aluno-id="<?= $aluno['id'] ?>">
                        <td data-label="Aluno"><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td data-label="Idade"><?= $idade ?> anos</td>
                        <td data-label="Ações">
                            <button class="action-btn btn-info" data-aluno='<?= json_encode($aluno, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                Mais Info <i class="bi bi-info-circle"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<br><br><br><br>

<!-- Modal Info -->
<div class="modal-bg" id="modalInfo">
    <button class="modal-close" onclick="fecharModal('modalInfo')">&times;</button>
    <h3>Informações do Aluno</h3>
    <div id="infoConteudo"></div>
</div>

<!-- Alerta -->
<div id="alertaSucesso"></div>
<?php include './nav_professor.php'; ?>
<script src="../../js/nav_professor.js"></script>
<script>
function abrirModal(id){ document.getElementById(id).classList.add('show'); }
function fecharModal(id){ document.getElementById(id).classList.remove('show'); }

function mostrarAlertaSucesso(msg){
    const alerta=document.getElementById('alertaSucesso');
    alerta.textContent=msg;
    alerta.style.display='block';
    setTimeout(()=>{ alerta.style.opacity='1'; },10);
    setTimeout(()=>{ alerta.style.opacity='0'; },2500);
    setTimeout(()=>{ alerta.style.display='none'; },2800);
}

document.querySelectorAll('.btn-info').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const aluno=JSON.parse(btn.getAttribute('data-aluno'));
        document.getElementById('infoConteudo').innerHTML=`
            <p><strong>Nome:</strong> ${aluno.nome}</p>
            <p><strong>Nascimento:</strong> ${aluno.data_nascimento}</p>
            <p><strong>Email:</strong> ${aluno.email}</p>
            <p><strong>Telefone:</strong> ${aluno.telefone||'Não informado'}</p>
            <p><strong>Responsável:</strong> ${aluno.nome_responsavel||'Não informado'}</p>`;
        abrirModal('modalInfo');
    });
});
</script>
</body>
</html>

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

// Todas as turmas (para mudar)
$stmt_turmas = $conn->prepare("SELECT id, nome FROM turmas ORDER BY nome ASC");
$stmt_turmas->execute();
$result_turmas = $stmt_turmas->get_result();
$turmas_para_mudar = $result_turmas->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gerenciar Alunos</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">
<style>
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
label {
    font-weight: 600;
}
select {
    padding: 10px 15px;
    border-radius: 10px;
    border: 2px solid #000000ff;
    width: 100%;
    max-width: 300px;
    color: #390062;
    font-weight: 500;
}
select:focus {
    outline: none;
    box-shadow: 0 0 6px #000000aa;
}
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
button.action-btn {
    background-color: #390062;
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
    margin-right: 6px;
    display: inline-flex;
    align-items: center;
}
button.action-btn:hover { background-color: #5a008a; }
button.action-btn i { margin-left: 6px; font-size: 1.1rem; }

/* Modal */
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
.modal-actions {
    margin-top: 25px;
    text-align: right;
}
.modal-actions button {
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}
.btn-cancel {
    background: #ccc;
    color: #390062;
    margin-right: 10px;
}
.btn-cancel:hover { background: #bbb; }
.btn-save {
    background: #fff900;
    color: #390062;
}
.btn-save:hover { background: #ffd700; }

/* Alerta sucesso */
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
@media (max-width: 600px) {
    .container { padding: 20px; }
    table, th, td { font-size: 0.9rem; }
    button.action-btn { padding: 6px 10px; font-size: 0.9rem; }
}
.btn-voltar {

    color: #4b0082;
    text-decoration: none;
    text-align: center;
}

.btn-voltar:hover {
      color: #000000;
}
</style>
</head>
<body>
<div class="container">
<a href="#" onclick="window.history.back();" class="btn-voltar">← Voltar</a>

    <h2>Gerenciar Alunos</h2>
    <form method="GET" action="" id="formTurma">
        <label for="turma_id">Selecione a turma atual:</label>
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
                    <th style="min-width:180px;">Ações</th>
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
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td><?= $idade ?> anos</td>
                        <td>
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

<!-- Modal Info -->
<div class="modal-bg" id="modalInfo">
    <button class="modal-close" onclick="fecharModal('modalInfo')">&times;</button>
    <h3>Informações do Aluno</h3>
    <div id="infoConteudo"></div>
</div>

<!-- Modal Mudar Turma -->
<div class="modal-bg" id="modalMudar">
    <button class="modal-close" onclick="fecharModal('modalMudar')">&times;</button>
    <h3>Mudar Turma</h3>
    <form id="formMudarTurma" method="POST" action="mudar_turma.php">
        <input type="hidden" name="aluno_id" id="inputAlunoId">
        <label for="nova_turma">Nova Turma:</label>
        <select name="nova_turma" id="nova_turma" required>
            <option value="" disabled selected>Selecione...</option>
            <?php foreach ($turmas_para_mudar as $t): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="fecharModal('modalMudar')">Cancelar</button>
            <button type="submit" class="btn-save">Salvar</button>
        </div>
    </form>
</div>

<!-- Alerta -->
<div id="alertaSucesso"></div>

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



document.getElementById('formMudarTurma').addEventListener('submit',e=>{
    e.preventDefault();
    const alunoId=document.getElementById('inputAlunoId').value;
    const novaTurma=document.getElementById('nova_turma').value;
    const turmaAtual=<?= json_encode($turma_selecionada) ?>;
    if(!novaTurma) return;
    if(novaTurma===turmaAtual){ fecharModal('modalMudar'); mostrarAlertaSucesso('Aluno já está nessa turma.'); return; }
    fetch('mudar_turma.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:new URLSearchParams({aluno_id:alunoId,nova_turma:novaTurma})})
    .then(res=>res.json()).then(data=>{
        if(data.success){ fecharModal('modalMudar'); mostrarAlertaSucesso('Turma alterada com sucesso!');
            const linha=document.querySelector(`tr[data-aluno-id="${alunoId}"]`); if(linha) linha.remove();
        } else { alert('Erro: '+data.message); }
    }).catch(()=>alert('Erro ao tentar alterar turma.'));
});
</script>
</body>
</html>

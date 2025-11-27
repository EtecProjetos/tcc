<?php
include '../../../Back/conexao.php';
session_start();

// Monta mapa de turmas (id => nome)
$stmt = $conn->prepare("SELECT id, nome FROM turmas ORDER BY nome ASC");
$stmt->execute();
$result = $stmt->get_result();
$turmas = $result->fetch_all(MYSQLI_ASSOC);
$turma_map = [];
foreach ($turmas as $t) $turma_map[$t['id']] = $t['nome'];

// Seleção de turma
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
<title>Gerenciar Alunos </title>

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
    max-width: 980px;
    background: #fff;
    color: #333;
    margin: auto;
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(57,0,98,0.5);
}
h2 {
    text-align: center;
    margin-bottom: 10px;
    color: #390062;
}

/* ================= Opções topo ================= */
.top-actions {
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:space-between;
    margin-bottom:12px;
    flex-wrap:wrap;
}
.left {
    display:flex;
    gap:12px;
    align-items:center;
}
label { font-weight: 600; color:#390062; }
select {
    padding: 10px 15px;
    border-radius: 10px;
    border: 2px solid #000000ff;
    width: 100%;
    max-width: 320px;
    color: #390062;
    font-weight: 500;
    transition: 0.3s;
}
select:focus {
    outline: none;
    box-shadow: 0 0 6px #000000aa;
}

#campoBusca {
    width: 100%;
    max-width: 320px;
    padding: 12px;
    border-radius: 12px;
    border: 2px solid #390062;
    font-size: 1rem;
    display: none;
}

/* ================= Tabela desktop ================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}
th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
    vertical-align: middle;
}
th { background: #f6f0fb; color:#390062; font-weight:700; }
tr:nth-child(even) { background: #faf7ff; }

/* ================= Botões ================= */
.btn {
    padding: 8px 12px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    display:inline-flex;
    gap:8px;
    align-items:center;
}
.btn-edit { background:#2b90ff; color:#fff; }
.btn-delete { background:#ff4e4e; color:#fff; }
.btn-info { background:#390062; color:#fff; }

/* ================= Modal ================= */
.modal-bg {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: #fff;
    color: #333;
    padding: 20px;
    border-radius: 12px;
    max-width: 520px;
    width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 1200;
    box-shadow: 0 6px 30px rgba(57,0,98,0.3);
    opacity: 0;
    display: none;
    transition: all 0.22s ease;
}
.modal-bg.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
    display: block;
}
.modal-close {
    position: absolute; top:12px; right:12px;
    background:none; border:none; font-size:1.4rem; cursor:pointer; color:#390062;
}
.modal-head { color:#390062; margin:0 0 12px 0; font-size:1.2rem; text-align:center; }

/* inputs dentro do modal */
.modal-body input, .modal-body select {
    width:90%;
    padding:10px 12px;
    margin:6px 0 10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

/* confirma delete */
.del-text { font-weight:700; margin-bottom:10px; }

/* ================= Responsividade ================= */
@media (max-width: 760px) {
    .top-actions { flex-direction: column; align-items:flex-start; gap:8px; }
    table, thead, tbody, th, td, tr { display: block; }
    thead { display: none; }
    tr { 
        background: linear-gradient(135deg,#f3e5ff,#d1c4e9);
        margin-bottom: 18px; padding:14px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08);
    }

    td::before { content: attr(data-label); font-weight:700; color:#4b0082; margin-right:6px; width:48%;
        display:inline-block;
    }
    .btn { width:100%; justify-content:center; }
}
@media (max-width: 760px) {

    /* Faz a área de ações virar um bloco vertical */
    td[data-label="Ações"] {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 90%;
    }

    /* Cada botão ocupa 100% da largura */
    td[data-label="Ações"] .btn {
        width: 100%;
        justify-content: center;
    }
}
button.back {
    background-color: transparent;
    color: #FFD700;
    border: none;
    font-size: 1rem;
    margin-top: 10px;
    cursor: pointer;
    text-decoration: underline;

    display: block;       /* transforma em bloco */
    margin: 0 auto;       /* centraliza horizontalmente */
}


button.back:hover {
    color: #fff;
}
</style>
</head>
<body>

<div class="container">
    <h2>Gerenciar Alunos</h2>

    <div class="top-actions">
        <div class="left">
            <form method="GET" id="formTurma" style="margin:0;">
                <label for="turma_id">Turma:</label>
                <select name="turma_id" id="turma_id" required onchange="document.getElementById('formTurma').submit()">
                    <option value="" disabled <?= !$turma_selecionada ? 'selected' : '' ?>>Selecione...</option>
                    <?php foreach ($turmas as $turma): ?>
                        <option value="<?= $turma['id'] ?>" <?= ($turma_selecionada == $turma['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($turma['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <!-- mostra nome da turma selecionada -->
            <?php if ($turma_selecionada): ?>
                <div style="margin-left:12px; align-self:center; color:#390062; font-weight:700;">
                    <?= htmlspecialchars($turma_map[$turma_selecionada] ?? '—') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="right">
            <input type="text" id="campoBusca" placeholder="Pesquisar aluno..." onkeyup="filtrarAlunos()">
        </div>
    </div>

    <?php if ($turma_selecionada): ?>
        <script>document.getElementById('campoBusca').style.display = 'block';</script>

        <table id="tabelaAlunos" aria-live="polite">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Turma</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alunos) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">Nenhum aluno nesta turma.</td></tr>
                <?php else: ?>
                    <?php foreach ($alunos as $aluno):
                        $data_nasc = new DateTime($aluno['data_nascimento']);
                        $idade = $data_nasc->diff(new DateTime())->y;
                        $turma_nome = $turma_map[$aluno['turma_id']] ?? '-';
                    ?>
                    <tr data-aluno-id="<?= $aluno['id'] ?>">
                        <td data-label="Nome" class="nomeAluno"><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td data-label="Idade"><?= $idade ?> anos</td>
                        <td data-label="Turma"><?= htmlspecialchars($turma_nome) ?></td>
                        <td data-label="Ações">
                            <button class="btn btn-info btn-view" data-aluno='<?= json_encode($aluno, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                <i class="bi bi-eye"></i> Info
                            </button>

                            <button class="btn btn-edit btn-open-edit" data-aluno='<?= json_encode($aluno, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>

                            <button class="btn btn-delete btn-open-del" data-id="<?= $aluno['id'] ?>">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </td>
                        
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
    <?php endif; ?>

</div>
<br>
    <button class="back" onclick="location.href='home_admin.php'">← Voltar</button>

<!-- Modal: Visualizar -->
<div class="modal-bg" id="modalView">
    <button class="modal-close" onclick="fecharModal('modalView')">&times;</button>
    <h3 class="modal-head">Informações do Aluno</h3>
    <div class="modal-body" id="viewBody"></div>
</div>

<!-- Modal: Editar -->
<div class="modal-bg" id="modalEdit">
    <button class="modal-close" onclick="fecharModal('modalEdit')">&times;</button>
    <h3 class="modal-head">Editar Aluno</h3>
    <div class="modal-body">
        <form id="formEdit">
            <input type="hidden" name="id" id="edit_id">
            <label>Nome</label>
            <input type="text" name="nome" id="edit_nome" required>

            <label>Data de nascimento</label>
            <input type="date" name="data_nascimento" id="edit_data_nascimento" required>

            <label>Email</label>
            <input type="email" name="email" id="edit_email">

            <label>Telefone</label>
            <input type="text" name="telefone" id="edit_telefone">

            <label>Nome do responsável</label>
            <input type="text" name="nome_responsavel" id="edit_nome_responsavel">

            <label>CPF do responsável</label>
            <input type="text" name="cpf_responsavel" id="edit_cpf_responsavel">

            <label>Turma</label>
            <br>
            <select name="turma_id" id="edit_turma_id">
                <?php foreach ($turmas as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                <?php endforeach; ?>
            </select>

            <div style="display:flex; gap:10px; margin-top:12px;">
                <button type="submit" class="btn btn-edit" style="flex:1;">Salvar</button>
                <button type="button" class="btn" style="flex:1; background:#ccc; color:#111;" onclick="fecharModal('modalEdit')">Cancelar</button>
            </div>
            <div id="editFeedback" style="margin-top:8px; font-weight:600;"></div>
        </form>
        
    </div>
</div>

<!-- Modal: Confirmar Exclusão -->
<div class="modal-bg" id="modalDel">
    <button class="modal-close" onclick="fecharModal('modalDel')">&times;</button>
    <h3 class="modal-head">Confirmar exclusão</h3>
    <div class="modal-body">
        <p class="del-text">Tem certeza que deseja excluir este aluno? Esta ação não pode ser desfeita.</p>
        <div style="display:flex; gap:10px;">
            <button id="confirmDeleteBtn" class="btn btn-delete" style="flex:1;">Excluir</button>
            <button type="button" class="btn" style="flex:1; background:#ccc; color:#111;" onclick="fecharModal('modalDel')">Cancelar</button>
        </div>
    </div>
</div>

<!-- Alerta -->
<div id="alertaSucesso" style="display:none;"></div>



<script>
// utilidades de modal
function abrirModal(id){ document.getElementById(id).classList.add('show'); }
function fecharModal(id){ document.getElementById(id).classList.remove('show'); }

// mostrar alerta curto
function mostrarAlerta(msg){
    const el = document.getElementById('alertaSucesso');
    el.textContent = msg;
    el.style.display = 'block';
    el.style.opacity = '1';
    setTimeout(()=>{ el.style.opacity = '0'; }, 2000);
    setTimeout(()=>{ el.style.display = 'none'; }, 2300);
}

// pesquisa em tempo real
function filtrarAlunos(){
    const busca = document.getElementById('campoBusca').value.toLowerCase();
    const linhas = document.querySelectorAll('#tabelaAlunos tbody tr');
    linhas.forEach(l => {
        const nomeEl = l.querySelector('.nomeAluno');
        if (!nomeEl) return;
        const nome = nomeEl.innerText.toLowerCase();
        l.style.display = nome.includes(busca) ? '' : 'none';
    });
}

// abrir modal view
document.querySelectorAll('.btn-view').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const aluno = JSON.parse(btn.getAttribute('data-aluno'));
        const html = `
            <p><strong>Nome:</strong> ${aluno.nome}</p>
            <p><strong>Data de nascimento:</strong> ${aluno.data_nascimento}</p>
            <p><strong>Email:</strong> ${aluno.email || 'Não informado'}</p>
            <p><strong>Telefone:</strong> ${aluno.telefone || 'Não informado'}</p>
            <p><strong>Responsável:</strong> ${aluno.nome_responsavel || 'Não informado'}</p>
            <p><strong>CPF Responsável:</strong> ${aluno.cpf_responsavel || '—'}</p>
        `;
        document.getElementById('viewBody').innerHTML = html;
        abrirModal('modalView');
    });
});

// abrir modal editar e preencher campos
document.querySelectorAll('.btn-open-edit').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const aluno = JSON.parse(btn.getAttribute('data-aluno'));
        document.getElementById('edit_id').value = aluno.id;
        document.getElementById('edit_nome').value = aluno.nome;
        document.getElementById('edit_data_nascimento').value = aluno.data_nascimento;
        document.getElementById('edit_email').value = aluno.email;
        document.getElementById('edit_telefone').value = aluno.telefone;
        document.getElementById('edit_nome_responsavel').value = aluno.nome_responsavel;
        document.getElementById('edit_cpf_responsavel').value = aluno.cpf_responsavel;
        document.getElementById('edit_turma_id').value = aluno.turma_id;
        document.getElementById('editFeedback').textContent = '';
        abrirModal('modalEdit');
    });
});

// envio do form de edição via AJAX (evita depender do redirect do backend)
document.getElementById('formEdit').addEventListener('submit', function(e){
    e.preventDefault();
    const url = '../../../back/alunos/salvar_edicao_aluno.php';
    const formData = new FormData(this);

    // feedback
    const feedback = document.getElementById('editFeedback');
    feedback.textContent = 'Salvando...';

    fetch(url, { method: 'POST', body: formData })
    .then(resp => resp.text())
    .then(text => {
        // backend provavelmente faz header redirect; considerar sucesso se não houver "Erro"
        if (text.toLowerCase().includes('erro') || text.toLowerCase().includes('fatal')) {
            feedback.textContent = 'Erro ao salvar. Veja o retorno do servidor.';
            console.error(text);
        } else {
            feedback.textContent = 'Salvo com sucesso!';
            mostrarAlerta('Aluno salvo com sucesso.');
            fecharModal('modalEdit');
            setTimeout(()=> location.reload(), 700);
        }
    })
    .catch(err=>{
        feedback.textContent = 'Erro na requisição';
        console.error(err);
    });
});

// abrir modal de exclusão
let alunoParaExcluirId = null;
document.querySelectorAll('.btn-open-del').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        alunoParaExcluirId = btn.getAttribute('data-id');
        abrirModal('modalDel');
    });
});

// confirmar exclusão (AJAX)
document.getElementById('confirmDeleteBtn').addEventListener('click', function(){
    if (!alunoParaExcluirId) return;
    const url = `../../../back/alunos/Excluir_aluno.php?id=${encodeURIComponent(alunoParaExcluirId)}`;

    this.textContent = 'Excluindo...';
    fetch(url, { method: 'GET' })
    .then(resp => resp.text())
    .then(text => {
        // checar retorno simples
        if (text.toLowerCase().includes('erro') || text.toLowerCase().includes('foreign key')) {
            mostrarAlerta('Erro ao excluir. Verifique vínculos no banco.');
            console.error(text);
            document.getElementById('confirmDeleteBtn').textContent = 'Excluir';
            fecharModal('modalDel');
        } else {
            mostrarAlerta('Aluno excluído.');
            fecharModal('modalDel');
            setTimeout(()=> location.reload(), 600);
        }
    })
    .catch(err=>{
        console.error(err);
        mostrarAlerta('Erro na exclusão.');
        fecharModal('modalDel');
    });
});
</script>

</body>
</html>

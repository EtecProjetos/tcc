<?php
include '../back/conexao.php';
session_start();

$professor_id = $_SESSION['professor_id'] ?? 1;

// Pega todas as turmas para o dropdown
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

// Pega todas as turmas para mudar (mesmo conjunto)
$stmt_turmas = $conn->prepare("SELECT id, nome FROM turmas ORDER BY nome ASC");
$stmt_turmas->execute();
$result_turmas = $stmt_turmas->get_result();
$turmas_para_mudar = $result_turmas->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gerenciar Alunos - Professor</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #520c6f;
            color: #fff;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            background: #fff;
            color: #333;
            margin: auto;
            padding: 25px 30px;
            border-radius: 16px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #390062;
        }
        label, select {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 12px;
            display: block;
        }
        select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            width: 100%;
            max-width: 300px;
            color: #333;
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
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        button.action-btn {
            background-color: #390062;
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-right: 6px;
            display: inline-flex;
            align-items: center;
        }
        button.action-btn:hover {
            background-color: #5a008a;
        }
        button.action-btn i {
            margin-left: 6px;
            font-size: 1.1rem;
        }
        /* Modal styles */
        .modal-bg {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: #fff;
            color: #333;
            padding: 25px 30px;
            border-radius: 12px;
            max-width: 450px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 6px 15px rgba(57,0,98,0.4);
            opacity: 0;
            display: none;
            transition: all 0.3s ease;
            position: fixed;
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
            font-size: 1.5rem;
            cursor: pointer;
            color: #390062;
            font-weight: bold;
            line-height: 1;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        select.modal-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            font-size: 1rem;
            color: #333;
        }
        .modal-actions {
            margin-top: 25px;
            text-align: right;
        }
        .modal-actions button {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-cancel {
            background: #ccc;
            color: #390062;
            margin-right: 12px;
        }
        .btn-cancel:hover {
            background: #bbb;
        }
        .btn-save {
            background: #390062;
            color: white;
        }
        .btn-save:hover {
            background: #5a008a;
        }
        /* Alerta sucesso */
        #alertaSucesso {
            position: fixed;
            top: 50%; left: 50%;
            background: #fff;
            color: #390062;
            padding: 25px 40px;
            border-radius: 20px;
            box-shadow: 0 6px 15px rgba(57,0,98,0.4);
            font-weight: 700;
            font-size: 1.2rem;
            text-align: center;
            z-index: 1500;
            display: none;
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
            transition: all 0.3s ease;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px 20px;
            }
            table, th, td {
                font-size: 0.9rem;
            }
            button.action-btn {
                padding: 6px 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Gerenciar Alunos - Professor</h2>
    
    <form method="GET" action="" id="formTurma">
        <label for="turma_id">Selecione a turma atual:</label>
        <select name="turma_id" id="turma_id" required onchange="document.getElementById('formTurma').submit()">
            <option value="" disabled <?= !$turma_selecionada ? 'selected' : '' ?>>Selecione uma turma...</option>
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
                    <th style="min-width: 180px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alunos) === 0): ?>
                    <tr><td colspan="3" style="text-align:center;">Nenhum aluno nesta turma.</td></tr>
                <?php else: ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <?php 
                            $data_nasc = new DateTime($aluno['data_nascimento']);
                            $hoje = new DateTime();
                            $idade = $data_nasc->diff($hoje)->y;
                        ?>
                        <tr data-aluno-id="<?= $aluno['id'] ?>">
                            <td><?= htmlspecialchars($aluno['nome']) ?></td>
                            <td><?= $idade ?> anos</td>
                            <td>
                                <button class="action-btn btn-mudar" data-aluno-id="<?= $aluno['id'] ?>">Mudar de Turma</button>
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

<!-- Modal Informações -->
<div class="modal-bg" id="modalInfo">
    <button class="modal-close" onclick="fecharModal('modalInfo')">&times;</button>
    <h3>Informações do Aluno</h3>
    <div id="infoConteudo"></div>
</div>

<!-- Modal Mudar Turma -->
<div class="modal-bg" id="modalMudar">
    <button class="modal-close" onclick="fecharModal('modalMudar')">&times;</button>
    <h3>Mudar Turma do Aluno</h3>
    <form id="formMudarTurma" method="POST" action="mudar_turma.php">
        <input type="hidden" name="aluno_id" id="inputAlunoId" value="">
        <label for="nova_turma">Selecione a nova turma:</label>
        <select name="nova_turma" id="nova_turma" class="modal-select" required>
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

<!-- Alerta sucesso -->
<div id="alertaSucesso"></div>

<script>
    // Abrir modal com animação
    function abrirModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('show');
    }

    // Fechar modal com animação
    function fecharModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('show');
    }

    // Mostrar alerta sucesso (modal estilizado com fade)
    function mostrarAlertaSucesso(mensagem) {
        const alerta = document.getElementById('alertaSucesso');
        alerta.textContent = mensagem;
        alerta.style.display = 'flex';
        setTimeout(() => {
            alerta.style.opacity = '1';
            alerta.style.transform = 'translate(-50%, -50%) scale(1)';
        }, 10);
        setTimeout(() => {
            alerta.style.opacity = '0';
            alerta.style.transform = 'translate(-50%, -50%) scale(0.9)';
        }, 2500);
        setTimeout(() => {
            alerta.style.display = 'none';
        }, 2800);
    }

    // Eventos dos botões informações
    document.querySelectorAll('.btn-info').forEach(button => {
        button.addEventListener('click', () => {
            const aluno = JSON.parse(button.getAttribute('data-aluno'));
            const conteudo = document.getElementById('infoConteudo');

            conteudo.innerHTML = `
                <p><strong>Nome:</strong> ${aluno.nome}</p>
                <p><strong>Data de Nascimento:</strong> ${aluno.data_nascimento}</p>
                <p><strong>Email:</strong> ${aluno.email}</p>
                <p><strong>Telefone:</strong> ${aluno.telefone || 'Não informado'}</p>
                <p><strong>Responsável:</strong> ${aluno.nome_responsavel || 'Não informado'}</p>
            `;

            abrirModal('modalInfo');
        });
    });

    // Evento do botão mudar turma abre modal
    document.querySelectorAll('.btn-mudar').forEach(button => {
        button.addEventListener('click', () => {
            const alunoId = button.getAttribute('data-aluno-id');
            document.getElementById('inputAlunoId').value = alunoId;
            document.getElementById('nova_turma').selectedIndex = 0;
            abrirModal('modalMudar');
        });
    });

    // Formulário mudar turma
    document.getElementById('formMudarTurma').addEventListener('submit', function(e) {
        e.preventDefault();
        const alunoId = document.getElementById('inputAlunoId').value;
        const novaTurmaId = document.getElementById('nova_turma').value;
        const turmaSelecionada = <?= json_encode($turma_selecionada) ?>;

        if (!novaTurmaId) return;

        if (novaTurmaId === turmaSelecionada) {
            fecharModal('modalMudar');
            mostrarAlertaSucesso('Aluno já está nessa turma.');
            return;
        }

        fetch('mudar_turma.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({aluno_id: alunoId, nova_turma: novaTurmaId})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fecharModal('modalMudar');
                mostrarAlertaSucesso('Turma alterada com sucesso!');
                // Remove linha do aluno da tabela sem recarregar
                const linha = document.querySelector(`tr[data-aluno-id="${alunoId}"]`);
                if (linha) linha.remove();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(() => {
            alert('Erro ao tentar alterar turma.');
        });
    });
</script>
</body>
</html>
  <div id="nav-placeholder"></div>

  <!-- Scripts -->
  <script src="js/nav_professor.js"></script>
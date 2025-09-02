<?php
session_start();
include '../back/conexao.php';

// Simulando aluno logado
$aluno_id = $_SESSION['aluno_id'] ?? 1; // substitua por controle de sessão real

// Pega a turma do aluno
$sql_turma = "SELECT turma_id FROM alunos WHERE id = $aluno_id";
$res_turma = $conn->query($sql_turma);
$turma_id = ($res_turma->num_rows > 0) ? $res_turma->fetch_assoc()['turma_id'] : null;

$jogos = [];
if ($turma_id) {
    $sql_jogos = "
    SELECT * FROM jogos 
    WHERE turma_id = $turma_id AND data >= CURDATE()
    ORDER BY data, horario
    ";
    $res_jogos = $conn->query($sql_jogos);
    if ($res_jogos->num_rows > 0) {
        while ($row = $res_jogos->fetch_assoc()) {
            $jogos[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Jogos do Aluno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style_jogos.css" />
    <link rel="stylesheet" href="styleBase.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #4c0070;
    color: white;
    padding-bottom: 80px;
}

.card-jogo {
    position: relative; /* Para posicionar o botão */
    background-color: #7a0ea4;
    margin: 20px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
}

.topo {
    background: linear-gradient(to right, #2b003c, #3f0058);
    padding: 10px;
    text-align: center;
}

.logo {
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 50%;
    margin-top: 5px;
}

.versus {
    display: flex;
    justify-content: space-around;
    align-items: center; /* Alinha verticalmente ao centro */
    margin-top: 10px;
    font-weight: bold;
    font-size: 1.2em;
    gap: 20px; /* Espaçamento entre os blocos */
}

.logo-time,
.logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
}

.bi-x-lg {
    font-size: 36px;
    color: white;
    /* Remove altura fixa para o flex alinhar naturalmente */
    display: flex;
    align-items: center;
    justify-content: center;
    /* Ajuste responsivo do tamanho do ícone */
    flex-shrink: 0; /* evita que encolha */
}

@media (max-width: 600px) {
    .versus {
        font-size: 1em;
        gap: 12px;
    }

    .logo-time,
    .logo {
        width: 60px;
        height: 60px;
    }

    .bi-x-lg {
        font-size: 28px;
    }
}

.info {
    background-color: #300043;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    font-weight: bold;
    font-size: 1em;
}

.info i {
    margin-right: 12px;
    font-size: 1.2em;
}

.btn-share {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: #ffd700;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4b0082;
    font-size: 1.3rem;
    transition: background-color 0.3s;
    z-index: 10;
}

.btn-share:hover {
    background-color: #ffe34d;
}
    </style>
</head>
<body>

<?php if (count($jogos) > 0): ?>
    <?php foreach ($jogos as $jogo): ?>
        <div class="card-jogo">
            <div class="topo">
                <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
            </div>

           

            <div class="versus">
                <div class="time-bloco">
                    <img src="<?= htmlspecialchars($jogo['logo_url']) ?>" alt="Logo Adversário" class="logo-time" />
                    <span class="time-nome"><?= mb_strtoupper(htmlspecialchars($jogo['adversario']), 'UTF-8') ?></span>
                </div>

                <i class="bi bi-x-lg"></i>

                <div class="time-bloco">
                    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
                    <span class="time-nome">NEW FOOTBALL</span>
                </div>
            </div>

            <div class="info"><i class="bi bi-house"></i> LOCAL: <?= mb_strtoupper(htmlspecialchars($jogo['local']), 'UTF-8') ?></div>
            <div class="info"><i class="bi bi-clock"></i> HORÁRIO: <?= date("H:i", strtotime($jogo['horario'])) ?></div>
             <div class="info"><i class="bi bi-calendar-event"></i> DATA: <?= date('d/m/Y', strtotime($jogo['data'])) ?></div>
            <div class="info"><i class="bi bi-list"></i> CATEGORIA: <?= mb_strtoupper(htmlspecialchars($jogo['categoria']), 'UTF-8') ?></div>
            <div class="info"><i class="bi bi-person-lines-fill"></i> ADVERSÁRIO: <?= mb_strtoupper(htmlspecialchars($jogo['adversario']), 'UTF-8') ?></div>
            <div class="info"><i class="bi bi-flag"></i> MANDANTE: <?= (!empty($jogo['mandante']) && $jogo['mandante'] == 1) ? 'SIM' : 'NÃO' ?></div>

            <button class="btn-share" title="Compartilhar jogo">
                <i class="bi bi-share-fill"></i>
            </button>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center; color:white;">NENHUM JOGO DISPONÍVEL PARA SUA TURMA.</p>
<?php endif; ?>

<!-- Rodapé fixo -->
<div id="nav-placeholder"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.querySelectorAll('.btn-share').forEach(button => {
    button.addEventListener('click', async (e) => {
        e.stopPropagation();
        const card = button.closest('.card-jogo');

        // Esconde o botão para não aparecer na imagem
        button.style.display = 'none';

        // Seleciona a imagem adversária dentro do card
        const adversarioImg = card.querySelector('img.logo-time');

        // Função que retorna Promise resolvida quando imagem carregar ou já estiver carregada
        function waitImageLoad(img) {
            return new Promise((resolve, reject) => {
                if (!img) {
                    resolve(); // Se não tem imagem, resolve direto
                } else if (img.complete && img.naturalHeight !== 0) {
                    resolve();
                } else {
                    img.onload = () => resolve();
                    img.onerror = () => reject('Erro ao carregar imagem adversária');
                }
            });
        }

        try {
            // Aguarda a imagem adversária carregar
            await waitImageLoad(adversarioImg);

            // Depois que a imagem estiver carregada, faz a captura
            const canvas = await html2canvas(card, {backgroundColor: null, scale: 2});
            const dataUrl = canvas.toDataURL('image/png');
            const blob = await (await fetch(dataUrl)).blob();
            const filesArray = [new File([blob], 'jogo.png', {type: 'image/png'})];

            if (navigator.canShare && navigator.canShare({ files: filesArray })) {
                await navigator.share({
                    files: filesArray,
                    title: 'Jogo New Football',
                    text: 'Confira o jogo!',
                });
            } else {
                // Fallback: baixar a imagem
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = 'jogo.png';
                document.body.appendChild(link);
                link.click();
                link.remove();
                alert('Imagem baixada. Compartilhe como quiser!');
            }
        } catch (err) {
            alert('Erro ao compartilhar a imagem: ' + err);
            console.error(err);
        } finally {
            button.style.display = 'flex'; // mostra o botão de novo
        }
    });
});
</script>

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

</body>
</html>

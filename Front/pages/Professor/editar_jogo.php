<?php
session_start();
include '../../../Back/conexao.php'; // Caminho corrigido

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login_professor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

if (!isset($_GET['id'])) {
    header("Location: jogosprofessor.php");
    exit();
}

$jogo_id = intval($_GET['id']);

// Busca o jogo do professor logado
$stmt = $conn->prepare("SELECT * FROM jogos WHERE id = ? AND professor_id = ?");
$stmt->bind_param("ii", $jogo_id, $professor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Jogo não encontrado ou você não tem permissão.";
    exit();
}

$jogo = $result->fetch_assoc();
$stmt->close();

// Busca turmas do professor logado
$turmas_stmt = $conn->prepare("SELECT id, nome FROM turmas");

$turmas_stmt->execute();
$turmas = $turmas_stmt->get_result();

$alert = null;
$message = '';

// Tratar formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $turma_id = intval($_POST['turma_id'] ?? 0);
    $local = $_POST['local'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $adversario = $_POST['adversario'] ?? '';
    $tipo = $_POST['tipo'] ?? '';  
    $logo_url = $jogo['logo_url']; // Mantém o valor atual

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../../Front/imgs/jogos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $tmpName = $_FILES['logo_file']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['logo_file']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            // Apaga o arquivo antigo, se existir e não for o padrão
            if ($jogo['logo_url'] && $jogo['logo_url'] !== 'imgs/logo.png') {
                $oldPath = '../../../Front/' . $jogo['logo_url'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $logo_url = 'imgs/jogos/' . $fileName;
        }
    }

    // Atualiza os dados
    $update = $conn->prepare("UPDATE jogos SET data=?, horario=?, turma_id=?, local=?, categoria=?, adversario=?, tipo=?, logo_url=? WHERE id=? AND professor_id=?");
    $update->bind_param("ssisssssii", $data, $horario, $turma_id, $local, $categoria, $adversario, $tipo, $logo_url, $jogo_id, $professor_id);
    $update->execute();
    $update->close();

    $alert = 'success';
    $message = 'Jogo atualizado com sucesso!';

    // Atualiza os dados exibidos
    $jogo['data'] = $data;
    $jogo['horario'] = $horario;
    $jogo['turma_id'] = $turma_id;
    $jogo['local'] = $local;
    $jogo['categoria'] = $categoria;
    $jogo['adversario'] = $adversario;
    $jogo['tipo'] = $tipo;
    $jogo['logo_url'] = $logo_url;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Editar Jogo</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">

<style>
/* Mesmos estilos do seu código anterior */
body{margin:0;background:linear-gradient(to bottom,#6a0dad 0%,#000 100%);font-family:'Roboto',Arial,sans-serif;color:#4b0082;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:20px 10px;}
.logo-header{display:flex;justify-content:center;align-items:center;padding:30px 0;}
.logo{width:130px;height:auto;}
h2{text-align:center;font-weight:700;font-size:2rem;margin-bottom:30px;color:#6f2da8;}
.container{background:#fff;border-radius:16px;max-width:500px;width:100%;padding:30px 35px;box-shadow:0 4px 20px rgba(111,45,168,0.3);color:#4b0082;}
form{display:flex;flex-direction:column;gap:20px;}
label{font-weight:500;color:#4b0082;}
input[type="date"], input[type="time"], input[type="text"], select{padding:14px 16px;font-size:16px;border:2px solid #6f2da8;border-radius:14px;color:#4b0082;transition:border-color 0.3s ease;outline-offset:2px;}
input:focus, select:focus{border-color:#390062;outline:none;}
button{padding:18px 0;font-size:1.2rem;font-weight:700;background-color:#ffd700;color:#4b0082;border:none;border-radius:20px;cursor:pointer;box-shadow:0 4px 15px rgba(0,0,0,0.3);transition:transform 0.2s,background-color 0.3s;}
button:hover{background-color:#ffe345;transform:translateY(-3px);}
.back-link{display:inline-block;margin-top:25px;text-decoration:none;color:#6f2da8;font-weight:700;text-align:center;width:100%;user-select:none;transition:color 0.3s ease;}
.back-link:hover{color:#390062;}
#alert-box{position:fixed;top:20px;left:50%;transform:translateX(-50%);padding:15px 25px;border-radius:20px;font-weight:700;font-size:1rem;color:white;z-index:1000;opacity:0;pointer-events:none;transition:opacity 0.3s ease;max-width:90%;text-align:center;box-shadow:0 4px 15px rgba(111,45,168,0.5);}
#alert-box.show{opacity:1;pointer-events:auto;}
#alert-box.success{background-color:#28a745;}
#alert-box.nochange{background-color:#ffc107;color:#333;}
@media(max-width:480px){.container{padding:20px;}h2{font-size:1.6rem;}input,select,button{font-size:14px;}}
img.logo-preview{max-width:150px;border-radius:8px;margin-bottom:10px;display:block;margin-left:auto;margin-right:auto;}
</style>
</head>
<body>

<header class="logo-header">
  <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="container">
  <h2>Editar Jogo</h2>

  <form method="POST" enctype="multipart/form-data">
    <label for="data">Data:</label>
    <input id="data" type="date" name="data" required value="<?= htmlspecialchars($jogo['data']) ?>">

    <label for="horario">Horário:</label>
    <input id="horario" type="time" name="horario" required value="<?= htmlspecialchars($jogo['horario']) ?>">

    <label for="turma_id">Turma:</label>
    <select id="turma_id" name="turma_id" required>
      <option value="">Selecione a turma</option>
      <?php while($turma = $turmas->fetch_assoc()): ?>
        <option value="<?= $turma['id'] ?>" <?= $turma['id']==$jogo['turma_id']?'selected':'' ?>>
          <?= htmlspecialchars($turma['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label for="local">Local:</label>
    <input id="local" type="text" name="local" required value="<?= htmlspecialchars($jogo['local']) ?>">

    <label for="categoria">Categoria:</label>
    <input id="categoria" type="text" name="categoria" required value="<?= htmlspecialchars($jogo['categoria']) ?>">

    <label for="adversario">Adversário:</label>
    <input id="adversario" type="text" name="adversario" required value="<?= htmlspecialchars($jogo['adversario']) ?>">

    <label for="tipo">Tipo de Jogo:</label>
    <select id="tipo" name="tipo" required>
      <option value="Amistoso" <?= $jogo['tipo']==='Amistoso'?'selected':'' ?>>Amistoso</option>
      <option value="Oficial" <?= $jogo['tipo']==='Oficial'?'selected':'' ?>>Oficial</option>
    </select>
<label>Logo Atual do Adversário:</label>
<?php if (!empty($jogo['logo_url'])): ?>
    <img src="../../<?= htmlspecialchars($jogo['logo_url']) ?>" alt="Logo do adversário" class="logo-preview">
<?php else: ?>
    <p>Sem logo cadastrado</p>
<?php endif; ?>

    <label for="logo_file">Substituir Logo do Adversário:</label>
    <input id="logo_file" type="file" name="logo_file" accept="image/*">

    <button type="submit">Salvar Alterações</button>
  </form>

  <a href="jogosprofessor.php" class="back-link">← Voltar para Jogos</a>
</div>

<div id="alert-box"></div>

<script>
const alertBox = document.getElementById('alert-box');
function showAlert(type,message){
    alertBox.textContent=message;
    alertBox.className='';
    alertBox.classList.add(type);
    alertBox.classList.add('show');
    if(type==='success'){
        setTimeout(()=>{window.location.href='jogosprofessor.php';},1500);
    } else {
        setTimeout(()=>{alertBox.classList.remove('show');},3000);
    }
}
<?php if($alert!==null): ?>
showAlert('<?= $alert ?>','<?= addslashes($message) ?>');
<?php endif; ?>
</script>

</body>
</html>

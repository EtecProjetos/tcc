<?php
session_start();
include '../../../Back/conexao.php';

// Buscar campeonatos
$camp_query = $conn->query("SELECT id, nome FROM campeonatos ORDER BY nome ASC");
$campeonatos = $camp_query->fetch_all(MYSQLI_ASSOC);

// Buscar alunos e professores
$query = "
    SELECT a.id AS uid, a.nome, 'aluno' AS tipo
    FROM alunos a
    UNION
    SELECT p.id AS uid, p.nome, 'professor' AS tipo
    FROM professores p
    ORDER BY nome ASC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Convocar Usuários</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body {font-family:'Fredoka',sans-serif; background: linear-gradient(135deg,#000,#4c0070); color:white; padding:20px;}
.card {background: rgba(255,255,255,0.1); border-radius:12px; padding:20px; margin-bottom:20px;}
.table thead {background:#FFD700; color:#4c0070; font-weight:bold;}
.btn-convocar {background:#FFD700; color:#4c0070; font-weight:bold; border-radius:10px;}
.btn-convocar:hover {background:#e6c200;}
.link {
    text-align: center;
    margin: 20px 0;
}

.link a {
    color: #FFD700;
    font-weight: bold;
    text-decoration: none;
    font-size: 1rem;
}

.link a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4" style="color:#FFD700;">Convocar Usuários</h2>

    <div class="card">
        <h5>Selecione o Campeonato</h5>
        <select id="campeonato" class="form-select mt-2">
            <option value="">Selecione...</option>
            <?php foreach($campeonatos as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="card">
        <h5>Lista de Usuários</h5>
        <table class="table table-dark table-striped" id="tabelaUsuarios">
            <thead>
                <tr><th></th><th>Nome</th><th>Tipo</th></tr>
            </thead>
            <tbody>
                <?php while($row=$result->fetch_assoc()): ?>
                <tr data-id="<?= $row['uid'] ?>" data-tipo="<?= $row['tipo'] ?>">
                    <td><input type="checkbox" class="selecionarUsuario"></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= ucfirst($row['tipo']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button class="btn btn-convocar w-100" id="btnConvocar">Salvar Convocação</button>
        <div id="alertArea" class="mt-3"></div>
    </div>
</div>
<div class="link">
    <a href="campeonato.php">← Voltar</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function(){

    // Carregar usuários já convocados
    $('#campeonato').change(function(){
        let campId = $(this).val();
        if(!campId) return;
        $.get('../../../Back/convocacoes/get_convocados.php', {campeonato: campId}, function(res){
            $('#tabelaUsuarios tbody tr').each(function(){
                let id = parseInt($(this).data('id'));
                let tipo = $(this).data('tipo');
                if(tipo==='aluno') $(this).find('input').prop('checked', res.alunos.includes(id));
                if(tipo==='professor') $(this).find('input').prop('checked', res.professores.includes(id));
            });
        }, 'json');
    });

    // Salvar convocações (add/remove)
    $('#btnConvocar').click(function(){
        let campId = $('#campeonato').val();
        if(!campId){ alert('Selecione um campeonato'); return; }

        let usuarios = [];
        $('#tabelaUsuarios tbody tr').each(function(){
            let id = $(this).data('id');
            let tipo = $(this).data('tipo');
            let selecionado = $(this).find('input').prop('checked');
            usuarios.push({id:id, tipo:tipo, selecionado:selecionado});
        });

        $.post('salvar_convocacao.php', {campeonato:campId, usuarios:JSON.stringify(usuarios)}, function(res){
            $('#alertArea').html('<div class="alert alert-success">'+res+'</div>');
        });
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

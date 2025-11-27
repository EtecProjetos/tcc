<?php
session_start();
include '../../../Back/conexao.php';

// Verifica se o admin está logado
if(!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true){
    header("Location: admin.php");
    exit;
}

// Pega o ID do campeonato
$id = intval($_GET['id'] ?? 0);

if($id){

    // ⚙️ Verifica se a tabela 'convocacoes' possui a coluna 'campeonato_id'
    $checkColumn = $conn->query("SHOW COLUMNS FROM convocacoes LIKE 'campeonato_id'");
    if($checkColumn && $checkColumn->num_rows > 0){
        // Se existir, deleta as convocações relacionadas
        $stmt = $conn->prepare("DELETE FROM convocacoes WHERE campeonato_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // 🔥 Deleta o campeonato
    $stmt = $conn->prepare("DELETE FROM campeonatos WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        $stmt->close();
        header("Location: listar_campeonato.php?msg=Campeonato excluído com sucesso");
        exit;
    } else {
        die("Erro ao excluir: " . $stmt->error);
    }

} else {
    die("ID do campeonato inválido!");
}
?>

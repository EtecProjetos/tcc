<?php
include '../../../Back/conexao.php';
session_start();

if(!isset($_POST['campeonato']) || !isset($_POST['usuarios'])){
    echo "Erro: Dados incompletos"; exit;
}

$campeonato_id = intval($_POST['campeonato']);
$usuarios = json_decode($_POST['usuarios'], true);

foreach($usuarios as $u){
    $tipo = $u['tipo'];
    $usuario_id = intval($u['id']);
    $selecionado = $u['selecionado'];

    if($tipo==='aluno') $campo='aluno_id';
    elseif($tipo==='professor') $campo='professor_id';
    else continue;

    // verifica se já existe
    $check = $conn->prepare("SELECT id FROM convocacoes WHERE campeonato_id=? AND $campo=?");
    $check->bind_param("ii", $campeonato_id, $usuario_id);
    $check->execute();
    $existe = $check->get_result()->num_rows>0;

    if($selecionado && !$existe){
        $insert = $conn->prepare("INSERT INTO convocacoes (campeonato_id,$campo) VALUES (?,?)");
        $insert->bind_param("ii",$campeonato_id,$usuario_id);
        $insert->execute();
    }
    elseif(!$selecionado && $existe){
        $delete = $conn->prepare("DELETE FROM convocacoes WHERE campeonato_id=? AND $campo=?");
        $delete->bind_param("ii",$campeonato_id,$usuario_id);
        $delete->execute();
    }
}

echo "Convocações atualizadas com sucesso!";
exit;

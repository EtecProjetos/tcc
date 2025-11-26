    <?php
header('Content-Type: application/json; charset=utf-8');
include 'conexao.php';

if(!isset($_GET['campeonato']) || empty($_GET['campeonato'])){
    echo json_encode(['alunos'=>[], 'professores'=>[]]);
    exit;
}

$camp = intval($_GET['campeonato']);
$sql = "SELECT aluno_id, professor_id FROM convocacoes WHERE campeonato_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$camp);
$stmt->execute();
$res = $stmt->get_result();

$alunos=[]; $profs=[];
while($r=$res->fetch_assoc()){
    if(!empty($r['aluno_id'])) $alunos[]=(int)$r['aluno_id'];
    if(!empty($r['professor_id'])) $profs[]=(int)$r['professor_id'];
}

echo json_encode(['alunos'=>$alunos,'professores'=>$profs]);
exit;

<?php
session_start();
include '../../../Back/conexao.php';
if(!isset($_SESSION['aluno_id'])){header("Location: loginAluno.php"); exit();}
$aluno_id = $_SESSION['aluno_id'];

$mes = date('m');
$ano = date('Y');
$meses_pt = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];

if(isset($_GET['mes'])) $mes=(int)$_GET['mes'];
if(isset($_GET['ano'])) $ano=(int)$_GET['ano'];

$stmt = $conn->prepare("SELECT data,presente FROM frequencia WHERE aluno_id=? AND MONTH(data)=? AND YEAR(data)=? ORDER BY data DESC");
$stmt->bind_param("iii",$aluno_id,$mes,$ano);
$stmt->execute();
$result = $stmt->get_result();
$frequencias=[];
while($row=$result->fetch_assoc()) $frequencias[$row['data']]=$row['presente'];
$stmt->close();

$total=count($frequencias);
$presentes=array_sum($frequencias);
$porcentagem=($total>0)?round(($presentes/$total)*100,2):0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Frequência</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body{font-family:Fredoka,sans-serif;background:linear-gradient(to bottom,#6a0dad 0%,#000);color:#fff;margin:0;padding:0;display:flex;flex-direction:column;align-items:center;min-height:100vh;}
.container{max-width:480px;width:90%;margin:30px auto 50px;background:rgba(138,58,185,0.95);border-radius:20px;padding:25px;box-shadow:0 4px 15px rgba(0,0,0,0.4);}
h2{text-align:center;margin-bottom:20px;}
.porcentagem{font-size:48px;font-weight:bold;text-align:center;margin-bottom:20px;color:#ffd700;}
.info p{margin:6px 0;font-weight:600;text-align:center;}
.frequencia-lista{background:#faf7ff;color:#390062;border-radius:12px;padding:10px;max-height:300px;overflow-y:auto;}
.frequencia-item{display:flex;justify-content:space-between;padding:10px;border-bottom:1px solid #d9c9f9;font-weight:600;align-items:center;}
.presente i{color:#28a745;}
.faltou i{color:#dc3545;}
header.logo-header{display:flex;justify-content:center;align-items:center;padding:15px 0;width:100%;}
header.logo-header img.logo{width:150px;}
form{margin-bottom:15px;text-align:center;}
select{padding:6px 10px;border-radius:8px;border:none;font-weight:600;margin-right:5px;cursor:pointer;}
</style>
</head>
<body>
<header class="logo-header"><img src="../../imgs/logo.png" alt="Logo" class="logo"/></header>

<div class="container">
<h2>Frequência de <?= $meses_pt[(int)$mes] ?> / <?= $ano ?></h2>
<form id="form-mes" onchange="this.submit()">
<select name="mes"><?php foreach($meses_pt as $num=>$nome): ?><option value="<?= $num ?>" <?= $num==$mes?'selected':'' ?>><?= $nome ?></option><?php endforeach;?></select>
<select name="ano"><?php for($y=date('Y')-2;$y<=date('Y');$y++): ?><option value="<?= $y ?>" <?= $y==$ano?'selected':'' ?>><?= $y ?></option><?php endfor;?></select>
</form>

<div class="porcentagem"><?= $porcentagem ?>%</div>
<div class="info"><p>Total de presenças: <?= $total ?></p><p>Presentes: <?= $presentes ?></p><p>Faltas: <?= $total-$presentes ?></p></div>

<div class="frequencia-lista">
<?php if($total>0): foreach($frequencias as $data=>$presente): ?>
<div class="frequencia-item <?= $presente?'presente':'faltou' ?>">
<span><?= date('d/m/Y',strtotime($data)) ?></span>
<span><i class="bi <?= $presente?'bi-check-circle-fill':'bi-x-circle-fill' ?>"></i></span>
</div>
<?php endforeach; else: ?>
<p style="text-align:center;">Nenhuma presença registrada neste mês.</p>
<?php endif; ?>
</div>
</div>

<script>document.getElementById('form-mes').addEventListener('change',function(){this.submit();});</script>

<div id="nav-placeholder"></div>

<script src="../../js/nav.js"></script>
</body>
</html>

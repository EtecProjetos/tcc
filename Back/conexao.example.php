<?php
// Back/conexao.example.php
// Copie para conexao.php e preencha as credenciais locais.

$host = 'localhost';
$user = 'seu_usuario';
$pass = 'sua_senha';
$db   = 'nome_do_banco';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Erro na conexão: ' . $conn->connect_error);
}

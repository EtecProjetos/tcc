<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION['logout_ok'] = true;
header('Location: loginAluno.php');
exit;

<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$id = $_GET['id'];
$usuario_id = $_SESSION['id'];

$sql = "DELETE FROM receitas
        WHERE id = '$id'
        AND usuario_id = '$usuario_id'";

mysqli_query($conn, $sql);

header("Location: historico.php");
exit();
?>
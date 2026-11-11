
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = (int) $_SESSION['id'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Verifica se o ID é válido
if (!$id || $id <= 0) {
    header("Location: historico.php");
    exit();
}

// Exclui somente a receita pertencente ao usuário logado
$sql = "DELETE FROM receitas
        WHERE id = ?
        AND usuario_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $id, $usuario_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: historico.php");
exit();
?>

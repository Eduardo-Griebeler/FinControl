<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = (int) $_SESSION['id'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    die("Receita não encontrada.");
}

// Busca a receita com consulta preparada
$sql = "SELECT * FROM receitas
        WHERE id = ?
        AND usuario_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Não foi possível consultar a receita.");
}

mysqli_stmt_bind_param($stmt, "ii", $id, $usuario_id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$receita = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$receita) {
    die("Receita não encontrada.");
}

// Atualiza a receita
if (isset($_POST['salvar'])) {

    $descricao = trim($_POST['descricao'] ?? '');
    $valor = $_POST['valor'] ?? '';
    $data = $_POST['data'] ?? '';

    if ($descricao === '' || $valor === '' || $data === '') {

        $erro = "Preencha todos os campos.";

    } elseif (!is_numeric($valor) || (float)$valor < 0.01) {

        $erro = "Informe um valor maior ou igual a R$ 0,01.";

    } else {

        $valor = (float) $valor;

        $sqlUpdate = "UPDATE receitas
                      SET descricao = ?,
                          valor = ?,
                          data_receita = ?
                      WHERE id = ?
                      AND usuario_id = ?";

        $stmtUpdate = null;

        try {

            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);

            if ($stmtUpdate) {

                mysqli_stmt_bind_param(
                    $stmtUpdate,
                    "sdsii",
                    $descricao,
                    $valor,
                    $data,
                    $id,
                    $usuario_id
                );

                if (mysqli_stmt_execute($stmtUpdate)) {
                    mysqli_stmt_close($stmtUpdate);
                    header("Location: historico.php");
                    exit();
                }

                $erro = "Erro ao atualizar receita.";

            } else {
                $erro = "Não foi possível preparar a atualização.";
            }

        } catch (mysqli_sql_exception $e) {

            $erro = "Não foi possível atualizar a receita. Tente novamente.";

        } finally {

            if ($stmtUpdate instanceof mysqli_stmt) {
                mysqli_stmt_close($stmtUpdate);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Receita</title>

<link rel="stylesheet" href="css/style.css">

<style>

.form-container{
    max-width:600px;
    margin:40px auto;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
}

.erro{
    background:#f8d7da;
    color:#842029;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
}

</style>

</head>
<body>

<div class="container">

    <div class="card">
        <h1>Editar Receita</h1>
    </div>

    <div class="card form-container">

        <?php if(isset($erro)){ ?>
            <div class="erro">
                <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Descrição</label>
                <input type="text"
                       name="descricao"
                       value="<?php echo htmlspecialchars($receita['descricao'], ENT_QUOTES, 'UTF-8'); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Valor</label>
                <input type="number"
       step="0.01"
       min="0.01"
       name="valor"
       value="<?php echo htmlspecialchars($receita['valor'], ENT_QUOTES, 'UTF-8'); ?>"
       required>
            </div>

            <div class="form-group">
                <label>Data</label>
                <input type="date"
                       name="data"
                       value="<?php echo htmlspecialchars($receita['data_receita'], ENT_QUOTES, 'UTF-8'); ?>"
                       required>
            </div>

            <button class="botao" type="submit" name="salvar">
                Salvar Alterações
            </button>

        </form>

    </div>

    <div class="card">
        <a class="botao" href="historico.php">Voltar</a>
    </div>

</div>

</body>
</html>
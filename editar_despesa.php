<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = $_SESSION['id'];

$id = $_GET['id'];

$sql = "SELECT * FROM despesas
        WHERE id = '$id'
        AND usuario_id = '$usuario_id'";

$resultado = mysqli_query($conn, $sql);

$despesa = mysqli_fetch_assoc($resultado);

if (!$despesa) {
    die("Despesa não encontrada.");
}

if(isset($_POST['salvar'])){

    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];

    $sqlUpdate = "UPDATE despesas
                  SET descricao = '$descricao',
                      valor = '$valor',
                      data_despesa = '$data'
                  WHERE id = '$id'
                  AND usuario_id = '$usuario_id'";

    if(mysqli_query($conn, $sqlUpdate)){
        header("Location: historico.php");
        exit();
    }

    $erro = "Erro ao atualizar despesa.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png?v=999">
<meta charset="UTF-8">
<title>Editar Despesa</title>

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
        <h1>Editar Despesa</h1>
    </div>

    <div class="card form-container">

        <?php if(isset($erro)){ ?>
            <div class="erro">
                <?php echo $erro; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Descrição</label>
                <input type="text"
                       name="descricao"
                       value="<?php echo $despesa['descricao']; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Valor</label>
                <input type="number"
                       step="0.01"
                       name="valor"
                       value="<?php echo $despesa['valor']; ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Data</label>
                <input type="date"
                       name="data"
                       value="<?php echo $despesa['data_despesa']; ?>"
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
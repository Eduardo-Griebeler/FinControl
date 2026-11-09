
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

if (isset($_POST['salvar'])) {

    $usuario_id = (int) $_SESSION['id'];
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = $_POST['valor'] ?? '';
    $data = $_POST['data'] ?? '';

    if ($descricao === '' || $valor === '' || $data === '') {

        $erro = "Preencha todos os campos.";

    } elseif (!is_numeric($valor) || (float)$valor <= 0) {

        $erro = "Informe um valor maior que zero.";

    } else {

        // Consulta preparada para evitar SQL Injection
        $sql = "INSERT INTO receitas
                (usuario_id, descricao, valor, data_receita)
                VALUES (?, ?, ?, ?)";

        $stmt = null;

        try {

            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {

                $valor = (float) $valor;

                mysqli_stmt_bind_param(
                    $stmt,
                    "isds",
                    $usuario_id,
                    $descricao,
                    $valor,
                    $data
                );

                if (mysqli_stmt_execute($stmt)) {

                    $sucesso = "Receita cadastrada com sucesso!";

                }

            } else {

                $erro = "Não foi possível preparar o cadastro da receita.";

            }

        } catch (mysqli_sql_exception $e) {

            $erro = "Não foi possível cadastrar a receita. Tente novamente.";

        } finally {

            if ($stmt instanceof mysqli_stmt) {
                mysqli_stmt_close($stmt);
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png?v=999">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nova Receita - FinControl</title>

<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script>
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}
</script>

<style>

.form-container{
    max-width:700px;
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

.sucesso{
    background:#d1e7dd;
    color:#0f5132;
    padding:12px;
    margin-bottom:15px;
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

<div class="sidebar">

    <h2>FinControl</h2>

    <a href="dashboard.php">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>

    <a href="receitas.php">
        <i class="fa-solid fa-money-bill"></i> Receitas
    </a>

    <a href="despesas.php">
        <i class="fa-solid fa-wallet"></i> Despesas
    </a>

    <a href="historico.php">
        <i class="fa-solid fa-clock-rotate-left"></i> Histórico
    </a>

    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Sair
    </a>

</div>

<div class="content">

    <div class="card">

        <h1>💰 Nova Receita</h1>

        <p>
            Olá,
            <strong><?php echo htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <br>

<button class="botao" id="btnModo" onclick="toggleDarkMode()">
    🌙 Modo Escuro
</button>

    </div>

    <div class="card form-container">

        <?php if(isset($sucesso)){ ?>
    <div class="sucesso">
        <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>

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
                       placeholder="Ex: Salário, Freelance..."
                       required>
            </div>

            <div class="form-group">
                <label>Valor (R$)</label>

                <input type="number"
       step="0.01"
       min="0.01"
       name="valor"
       required>
            </div>

            <div class="form-group">
                <label>Data</label>

                <input type="date"
                       name="data"
                       required>
            </div>

            <button class="botao"
                    type="submit"
                    name="salvar">

                Salvar Receita

            </button>

        </form>

    </div>

</div>

</body>
</html>
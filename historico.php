<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = $_SESSION['id'];

$sql = "
SELECT
    id,
    'Receita' AS tipo,
    descricao,
    valor,
    data_receita AS data_lancamento
FROM receitas
WHERE usuario_id = '$usuario_id'

UNION ALL

SELECT
    id,
    'Despesa' AS tipo,
    descricao,
    valor,
    data_despesa AS data_lancamento
FROM despesas
WHERE usuario_id = '$usuario_id'

ORDER BY data_lancamento DESC
";

$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png?v=999">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Histórico Financeiro - FinControl</title>

<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script>
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
}
</script>

<style>

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#198754;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

.receita{
    color:#198754;
    font-weight:bold;
}

.despesa{
    color:#dc3545;
    font-weight:bold;
}

.editar{
    background:#0d6efd;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
    margin-right:5px;
}

.editar:hover{
    background:#0b5ed7;
}

.excluir{
    background:#dc3545;
    color:white;
    padding:8px 12px;
    text-decoration:none;
    border-radius:5px;
}

.excluir:hover{
    background:#bb2d3b;
}

.acoes{
    white-space:nowrap;
}

</style>

</head>
<body>

<div class="sidebar">

    <h2>FinControl</h2>

    <a href="dashboard.php">
        <i class="fa-solid fa-house"></i>
        Dashboard
    </a>

    <a href="receitas.php">
        <i class="fa-solid fa-money-bill"></i>
        Receitas
    </a>

    <a href="despesas.php">
        <i class="fa-solid fa-wallet"></i>
        Despesas
    </a>

    <a href="historico.php">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Histórico
    </a>

    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Sair
    </a>

</div>

<div class="content">

    <div class="card">

        <h1>📋 Histórico Financeiro</h1>

        <p>
            Bem-vindo,
            <strong><?php echo $_SESSION['nome']; ?></strong>
        </p>

        <br>

<button class="botao" id="btnModo" onclick="toggleDarkMode()">
    🌙 Modo Escuro
</button>

    </div>

    <div class="card">

        <table>

            <tr>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>

            <?php while($linha = mysqli_fetch_assoc($resultado)) { ?>

            <tr>

                <td class="<?php echo strtolower($linha['tipo']); ?>">
                    <?php echo $linha['tipo']; ?>
                </td>

                <td>
                    <?php echo $linha['descricao']; ?>
                </td>

                <td>
                    R$ <?php echo number_format($linha['valor'], 2, ',', '.'); ?>
                </td>

                <td>
                    <?php echo date('d/m/Y', strtotime($linha['data_lancamento'])); ?>
                </td>

                <td class="acoes">

                    <?php if($linha['tipo'] == 'Receita') { ?>

                        <a class="editar"
                           href="editar_receita.php?id=<?php echo $linha['id']; ?>">
                           Editar
                        </a>

                        <a class="excluir"
                           href="excluir_receita.php?id=<?php echo $linha['id']; ?>"
                           onclick="return confirm('Deseja excluir esta receita?')">
                           Excluir
                        </a>

                    <?php } else { ?>

                        <a class="editar"
                           href="editar_despesa.php?id=<?php echo $linha['id']; ?>">
                           Editar
                        </a>

                        <a class="excluir"
                           href="excluir_despesa.php?id=<?php echo $linha['id']; ?>"
                           onclick="return confirm('Deseja excluir esta despesa?')">
                           Excluir
                        </a>

                    <?php } ?>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>
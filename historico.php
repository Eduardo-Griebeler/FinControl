
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = (int) $_SESSION['id'];

$mes = isset($_GET['mes']) ? (int) $_GET['mes'] : 0;
$ano = isset($_GET['ano']) ? (int) $_GET['ano'] : 0;

// Valida os filtros
if ($mes < 1 || $mes > 12) {
    $mes = 0;
}

if ($ano < 2000 || $ano > 2100) {
    $ano = 0;
}

$sql = "
SELECT
    id,
    'Receita' AS tipo,
    descricao,
    NULL AS categoria,
    valor,
    data_receita AS data_lancamento
FROM receitas
WHERE usuario_id = ?
AND (? = 0 OR MONTH(data_receita) = ?)
AND (? = 0 OR YEAR(data_receita) = ?)

UNION ALL

SELECT
    id,
    'Despesa' AS tipo,
    descricao,
    categoria,
    valor,
    data_despesa AS data_lancamento
FROM despesas
WHERE usuario_id = ?
AND (? = 0 OR MONTH(data_despesa) = ?)
AND (? = 0 OR YEAR(data_despesa) = ?)

ORDER BY data_lancamento DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Não foi possível consultar o histórico.");
}

mysqli_stmt_bind_param(
    $stmt,
    "iiiiiiiiii",
    $usuario_id,
    $mes,
    $mes,
    $ano,
    $ano,
    $usuario_id,
    $mes,
    $mes,
    $ano,
    $ano
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
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
            <strong><?php echo htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <br>

<button class="botao" id="btnModo" onclick="toggleDarkMode()">
    🌙 Modo Escuro
</button>

    </div>

    <div class="card">

    <h3>Filtrar histórico</h3>

    <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <select name="mes">
            <option value="0">Todos os meses</option>
            <option value="1" <?php if($mes == 1) echo 'selected'; ?>>Janeiro</option>
            <option value="2" <?php if($mes == 2) echo 'selected'; ?>>Fevereiro</option>
            <option value="3" <?php if($mes == 3) echo 'selected'; ?>>Março</option>
            <option value="4" <?php if($mes == 4) echo 'selected'; ?>>Abril</option>
            <option value="5" <?php if($mes == 5) echo 'selected'; ?>>Maio</option>
            <option value="6" <?php if($mes == 6) echo 'selected'; ?>>Junho</option>
            <option value="7" <?php if($mes == 7) echo 'selected'; ?>>Julho</option>
            <option value="8" <?php if($mes == 8) echo 'selected'; ?>>Agosto</option>
            <option value="9" <?php if($mes == 9) echo 'selected'; ?>>Setembro</option>
            <option value="10" <?php if($mes == 10) echo 'selected'; ?>>Outubro</option>
            <option value="11" <?php if($mes == 11) echo 'selected'; ?>>Novembro</option>
            <option value="12" <?php if($mes == 12) echo 'selected'; ?>>Dezembro</option>
        </select>

        <input
            type="number"
            name="ano"
            placeholder="Ano (ex: 2026)"
            min="2000"
            max="2100"
            value="<?php echo $ano > 0 ? $ano : ''; ?>"
        >

        <button type="submit" class="botao">Filtrar</button>

        <a href="historico.php" class="botao">Limpar filtros</a>

    </form>

<table>

    <tr>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Valor</th>
        <th>Data</th>
        <th>Ações</th>
    </tr>
            
                
        

<?php while($linha = mysqli_fetch_assoc($resultado)) { ?>

<tr>

    <!-- Tipo -->
    <td class="<?php echo strtolower($linha['tipo']); ?>">
        <?php echo htmlspecialchars($linha['tipo'], ENT_QUOTES, 'UTF-8'); ?>
    </td>

    <!-- Descrição -->
    <td>
        <?php echo htmlspecialchars($linha['descricao'], ENT_QUOTES, 'UTF-8'); ?>
    </td>

    <!-- Categoria -->
    <td>
        <?php
        echo $linha['tipo'] == 'Despesa'
            ? htmlspecialchars($linha['categoria'] ?? '', ENT_QUOTES, 'UTF-8')
            : '—';
        ?>
    </td>

    <!-- Valor -->
    <td>
        R$ <?php echo number_format($linha['valor'], 2, ',', '.'); ?>
    </td>

    <!-- Data -->
    <td>
        <?php echo date('d/m/Y', strtotime($linha['data_lancamento'])); ?>
    </td>

    <!-- Ações -->
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
    <script src="css/script.js"></script>
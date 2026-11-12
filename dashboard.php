
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = (int) $_SESSION['id'];

// RECEITAS: total e quantidade
$sqlReceitas = "SELECT SUM(valor) AS total, COUNT(*) AS quantidade
                FROM receitas
                WHERE usuario_id = ?";

$stmt = mysqli_prepare($conn, $sqlReceitas);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resReceitas = mysqli_stmt_get_result($stmt);
$receitas = mysqli_fetch_assoc($resReceitas);
mysqli_stmt_close($stmt);

$totalReceitas = (float) ($receitas['total'] ?? 0);
$qtdReceitas = (int) ($receitas['quantidade'] ?? 0);

// DESPESAS: total e quantidade
$sqlDespesas = "SELECT SUM(valor) AS total, COUNT(*) AS quantidade
                FROM despesas
                WHERE usuario_id = ?";

$stmt = mysqli_prepare($conn, $sqlDespesas);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resDespesas = mysqli_stmt_get_result($stmt);
$despesas = mysqli_fetch_assoc($resDespesas);
mysqli_stmt_close($stmt);

$totalDespesas = (float) ($despesas['total'] ?? 0);
$qtdDespesas = (int) ($despesas['quantidade'] ?? 0);

// SALDO
$saldo = $totalReceitas - $totalDespesas;

$classeSaldo = $saldo >= 0
    ? 'saldo-positivo'
    : 'saldo-negativo';

// GRÁFICO DE RECEITAS POR MÊS E ANO
$sqlGraficoReceitas = "
SELECT
    YEAR(data_receita) AS ano,
    MONTH(data_receita) AS mes,
    SUM(valor) AS total
FROM receitas
WHERE usuario_id = ?
GROUP BY YEAR(data_receita), MONTH(data_receita)
ORDER BY ano, mes
";

$stmt = mysqli_prepare($conn, $sqlGraficoReceitas);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resGraficoReceitas = mysqli_stmt_get_result($stmt);

$receitasMes = [];

while ($linha = mysqli_fetch_assoc($resGraficoReceitas)) {
    $periodo = $linha['ano'] . '-' . str_pad($linha['mes'], 2, '0', STR_PAD_LEFT);
    $receitasMes[$periodo] = (float) $linha['total'];
}

mysqli_stmt_close($stmt);

// GRÁFICO DE DESPESAS POR MÊS E ANO
$sqlGraficoDespesas = "
SELECT
    YEAR(data_despesa) AS ano,
    MONTH(data_despesa) AS mes,
    SUM(valor) AS total
FROM despesas
WHERE usuario_id = ?
GROUP BY YEAR(data_despesa), MONTH(data_despesa)
ORDER BY ano, mes
";

$stmt = mysqli_prepare($conn, $sqlGraficoDespesas);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resGraficoDespesas = mysqli_stmt_get_result($stmt);

$despesasMes = [];

while ($linha = mysqli_fetch_assoc($resGraficoDespesas)) {
    $periodo = $linha['ano'] . '-' . str_pad($linha['mes'], 2, '0', STR_PAD_LEFT);
    $despesasMes[$periodo] = (float) $linha['total'];
}

mysqli_stmt_close($stmt);

// ORGANIZA OS MESES E ANOS DOS DOIS GRÁFICOS
$periodos = array_unique(array_merge(
    array_keys($receitasMes),
    array_keys($despesasMes)
));

sort($periodos);

$labelsMensais = [];
$dadosReceitas = [];
$dadosDespesas = [];

foreach ($periodos as $periodo) {
    $labelsMensais[] = date('m/Y', strtotime($periodo . '-01'));
    $dadosReceitas[] = $receitasMes[$periodo] ?? 0;
    $dadosDespesas[] = $despesasMes[$periodo] ?? 0;
}

// GRÁFICO DE PIZZA - CATEGORIAS
$sqlCategorias = "
SELECT
    categoria,
    SUM(valor) AS total
FROM despesas
WHERE usuario_id = ?
GROUP BY categoria
";

$stmt = mysqli_prepare($conn, $sqlCategorias);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resCategorias = mysqli_stmt_get_result($stmt);

$categorias = [];
$valoresCategorias = [];

while ($linha = mysqli_fetch_assoc($resCategorias)) {
    $categorias[] = $linha['categoria'];
    $valoresCategorias[] = (float) $linha['total'];
}

mysqli_stmt_close($stmt);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png?v=999">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard - FinControl</title>

<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="sidebar">
    <h2>💰 FinControl</h2>


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
        <div style="font-size:60px; margin-bottom:10px;">
    👤
</div>



        <h1>📊 Dashboard Financeiro</h1>

<br>

<p>
Bem-vindo de volta,
<strong><?php echo htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></strong>
</p>

<br>

<p>
📅 <?php echo date('d/m/Y'); ?>
</p>

        <button class="botao"
                id="btnModo"
                onclick="toggleDarkMode()">

            🌙 Modo Escuro

        </button>

    </div>

    <div class="cards">

        <div class="card-financeiro receita-card">

    <h3>💰 Receitas</h3>

    <h2>
        R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?>
    </h2>

    <p>
        <?php echo $qtdReceitas; ?> lançamentos
    </p>

    </div>

        <div class="card-financeiro despesa-card">

    <h3>💸 Despesas</h3>

    <h2>
        R$ <?php echo number_format($totalDespesas, 2, ',', '.'); ?>
    </h2>

    <p>
        <?php echo $qtdDespesas; ?> lançamentos
    </p>

    </div>

<div class="card-financeiro <?php echo $classeSaldo; ?>">

    <h3>📈 Saldo</h3>

    <h2>
        R$ <?php echo number_format($saldo, 2, ',', '.'); ?>
    </h2>

</div>

</div> <!-- FECHA A DIV .cards -->

<div class="card">

    <h2>📊 Receitas e Despesas por Mês</h2>

    <br>

    <canvas id="graficoMensal"></canvas>
</div>
<div class="card">

    <h2>🥧 Despesas por Categoria</h2>

    <br>

    <canvas id="graficoPizza"></canvas>

</div>


<script>




// Dados dos gráficos recebidos do PHP
const labelsMensais = <?php
echo json_encode($labelsMensais, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>;

const dadosReceitas = <?php echo json_encode($dadosReceitas); ?>;

const dadosDespesas = <?php echo json_encode($dadosDespesas); ?>;

const categorias = <?php
echo json_encode($categorias, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>;

const valoresCategorias = <?php echo json_encode($valoresCategorias); ?>;

// GRÁFICO MENSAL
new Chart(
    document.getElementById('graficoMensal'),
    {
        type: 'bar',

        data: {
            labels: labelsMensais,

            datasets: [
                {
                    label: 'Receitas',
                    data: dadosReceitas
                },
                {
                    label: 'Despesas',
                    data: dadosDespesas
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    }
);

// GRÁFICO DE PIZZA
new Chart(
    document.getElementById('graficoPizza'),
    {
        type: 'pie',

        data: {
            labels: categorias,

            datasets: [{
                data: valoresCategorias
            }]
        },

        options: {
            responsive: true
        }
    }
);
</script>

<script src="css/script.js"></script>


</body>
</html>
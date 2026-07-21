<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/conexao.php';

$usuario_id = $_SESSION['id'];

/* RECEITAS */
$sqlReceitas = "SELECT SUM(valor) AS total
                FROM receitas
                WHERE usuario_id='$usuario_id'";

$resReceitas = mysqli_query($conn, $sqlReceitas);
$receitas = mysqli_fetch_assoc($resReceitas);

$totalReceitas = $receitas['total'] ?? 0;
$sqlQtdReceitas = "SELECT COUNT(*) AS total
                   FROM receitas
                   WHERE usuario_id='$usuario_id'";

$resQtdReceitas = mysqli_query($conn, $sqlQtdReceitas);
$qtdReceitas = mysqli_fetch_assoc($resQtdReceitas)['total'];

/* DESPESAS */
$sqlDespesas = "SELECT SUM(valor) AS total
                FROM despesas
                WHERE usuario_id='$usuario_id'";

$resDespesas = mysqli_query($conn, $sqlDespesas);
$despesas = mysqli_fetch_assoc($resDespesas);

$totalDespesas = $despesas['total'] ?? 0;
$sqlQtdDespesas = "SELECT COUNT(*) AS total
                   FROM despesas
                   WHERE usuario_id='$usuario_id'";

$resQtdDespesas = mysqli_query($conn, $sqlQtdDespesas);
$qtdDespesas = mysqli_fetch_assoc($resQtdDespesas)['total'];

/* SALDO */
$saldo = $totalReceitas - $totalDespesas;

$classeSaldo = $saldo >= 0
    ? 'saldo-positivo'
    : 'saldo-negativo';

/* GRÁFICO RECEITAS */

$sqlGraficoReceitas = "
SELECT
MONTH(data_receita) AS mes,
SUM(valor) AS total
FROM receitas
WHERE usuario_id='$usuario_id'
GROUP BY MONTH(data_receita)
";

$resGraficoReceitas = mysqli_query($conn, $sqlGraficoReceitas);

$receitasMes = array_fill(1, 12, 0);

while($linha = mysqli_fetch_assoc($resGraficoReceitas)){
    $receitasMes[$linha['mes']] = $linha['total'];
}

/* GRÁFICO DESPESAS */

$sqlGraficoDespesas = "
SELECT
MONTH(data_despesa) AS mes,
SUM(valor) AS total
FROM despesas
WHERE usuario_id='$usuario_id'
GROUP BY MONTH(data_despesa)
";

$resGraficoDespesas = mysqli_query($conn, $sqlGraficoDespesas);

$despesasMes = array_fill(1, 12, 0);

while($linha = mysqli_fetch_assoc($resGraficoDespesas)){
    $despesasMes[$linha['mes']] = $linha['total'];
}
/* GRÁFICO DE PIZZA - CATEGORIAS */

$sqlCategorias = "
SELECT
    categoria,
    SUM(valor) AS total
FROM despesas
WHERE usuario_id='$usuario_id'
GROUP BY categoria
";

$resCategorias = mysqli_query($conn, $sqlCategorias);

$categorias = [];
$valoresCategorias = [];

while($linha = mysqli_fetch_assoc($resCategorias)){
    $categorias[] = $linha['categoria'];
    $valoresCategorias[] = $linha['total'];
}

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
<strong><?php echo $_SESSION['nome']; ?></strong>
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

function toggleDarkMode() {

    document.body.classList.toggle('dark-mode');

    let botao = document.getElementById('btnModo');

    if(document.body.classList.contains('dark-mode')){

        botao.innerHTML = '☀️ Modo Claro';

    }else{

        botao.innerHTML = '🌙 Modo Escuro';

    }
}

new Chart(
document.getElementById('graficoMensal'),
{
    type:'bar',

    data:{

        labels:[
            'Jan',
            'Fev',
            'Mar',
            'Abr',
            'Mai',
            'Jun',
            'Jul',
            'Ago',
            'Set',
            'Out',
            'Nov',
            'Dez'
        ],

        datasets:[

        {
            label:'Receitas',

            data:[
                <?php echo implode(',', $receitasMes); ?>
            ]
        },

        {
            label:'Despesas',

            data:[
                <?php echo implode(',', $despesasMes); ?>
            ]
        }

        ]
    },

    options:{
        responsive:true,
        maintainAspectRatio:true
    }
}
);
new Chart(
document.getElementById('graficoPizza'),
{
    type:'pie',

    data:{
        labels:[
            <?php
            foreach($categorias as $cat){
                echo "'".$cat."',";
            }
            ?>
        ],

        datasets:[{
            data:[
                <?php echo implode(',', $valoresCategorias); ?>
            ]
        }]
    },

    options:{
        responsive:true
    }
}
);
</script>

</body>
</html>
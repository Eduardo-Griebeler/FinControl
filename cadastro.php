
<?php

include 'includes/conexao.php';

if (isset($_POST['cadastrar'])) {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senhaDigitada = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senhaDigitada)) {

        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erro = "Informe um e-mail válido.";

    } else {

        // Protege a senha utilizando hash
        $senha = password_hash($senhaDigitada, PASSWORD_DEFAULT);

        // Consulta preparada para evitar SQL Injection
        $sql = "INSERT INTO usuarios (nome, email, senha)
                VALUES (?, ?, ?)";

        $stmt = null;

        try {

            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {

                mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha);

                if (mysqli_stmt_execute($stmt)) {

                    $sucesso = "Usuário cadastrado com sucesso!";

                }

            } else {

                $erro = "Não foi possível preparar o cadastro.";

            }

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {

                $erro = "Este e-mail já está cadastrado.";

            } else {

                $erro = "Não foi possível realizar o cadastro. Tente novamente mais tarde.";

            }

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
<title>Cadastro - FinControl</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.cadastro-container{
    background:white;
    padding:40px;
    width:420px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

.logo{
    text-align:center;
    margin-bottom:25px;
}

.logo h1{
    color:#28a745;
    font-size:32px;
}

.logo p{
    color:#777;
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

.btn{
    width:100%;
    padding:12px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

.btn:hover{
    background:#218838;
}

.erro{
    background:#f8d7da;
    color:#842029;
    padding:10px;
    margin-bottom:15px;
    border-radius:5px;
}

.sucesso{
    background:#d1e7dd;
    color:#0f5132;
    padding:10px;
    margin-bottom:15px;
    border-radius:5px;
}

.login{
    text-align:center;
    margin-top:20px;
}

.login a{
    color:#28a745;
    text-decoration:none;
    font-weight:bold;
}
body.dark-mode {
    background: #121212;
    color: white;
}

.dark-mode .cadastro-container {
    background: #1e1e1e;
    color: white;
}

.dark-mode .form-group input {
    background: #333;
    color: white;
    border-color: #555;
}

.dark-mode .form-group label {
    color: white;
}

</style>

</head>
<body>

<div class="cadastro-container">
    <button type="button" id="btnModo" onclick="toggleDarkMode()">
    🌙 Modo Escuro
</button>

    <div class="logo">
        <h1>FinControl</h1>
        <p>Crie sua conta</p>
    </div>

    <?php if(isset($erro)){ ?>
    <div class="erro">
        <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>

<?php if(isset($sucesso)){ ?>
    <div class="sucesso">
        <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>

    <form method="POST">

        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="nome" required>
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>

        <button type="submit" name="cadastrar" class="btn">
            Criar Conta
        </button>

    </form>

    <div class="login">
        Já possui uma conta?
        <a href="login.php">Entrar</a>
    </div>

</div>
<script src="css/script.js"></script>

</body>
</html><
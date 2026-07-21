<?php
session_start();

include 'includes/conexao.php';

if (isset($_POST['entrar'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) == 1) {

        $usuario = mysqli_fetch_assoc($resultado);

        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: dashboard.php");
            exit();

        } else {
            $erro = "Senha incorreta!";
        }

    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="imagens/favicon.png?v=999">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - FinControl</title>

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

.login-container{
    background:white;
    padding:40px;
    width:400px;
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

.cadastro{
    text-align:center;
    margin-top:20px;
}

.cadastro a{
    color:#28a745;
    text-decoration:none;
}

</style>

</head>
<body>

<div class="login-container">

    <div class="logo">
        <h1>FinControl</h1>
        <p>Gestão Financeira Pessoal</p>
    </div>

    <?php if(isset($erro)){ ?>
        <div class="erro">
            <?php echo $erro; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>

        <button type="submit" name="entrar" class="btn">
            Entrar
        </button>

    </form>

    <div class="cadastro">
        Não possui conta?
        <a href="cadastro.php">Cadastre-se</a>
    </div>

</div>

</body>
</html>
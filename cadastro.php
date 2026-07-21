<?php

include 'includes/conexao.php';

if(isset($_POST['cadastrar'])){

    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha)
            VALUES ('$nome', '$email', '$senha')";

    if(mysqli_query($conn, $sql)){
        $sucesso = "Usuário cadastrado com sucesso!";
    } else {
        $erro = "Este e-mail já está cadastrado.";
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

</style>

</head>
<body>

<div class="cadastro-container">

    <div class="logo">
        <h1>FinControl</h1>
        <p>Crie sua conta</p>
    </div>

    <?php if(isset($erro)){ ?>
        <div class="erro">
            <?php echo $erro; ?>
        </div>
    <?php } ?>

    <?php if(isset($sucesso)){ ?>
        <div class="sucesso">
            <?php echo $sucesso; ?>
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

</body>
</html>
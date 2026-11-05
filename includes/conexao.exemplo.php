<?php

$host = "SEU_HOST";
$usuario = "SEU_USUARIO";
$senha = "SUA_SENHA";
$banco = "NOME_DO_BANCO";

$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    die("Erro na conexão com o banco de dados.");
}

?>

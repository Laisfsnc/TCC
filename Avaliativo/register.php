<?php
// Simulação de cadastro
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

if ($nome && $email && $senha) {
    echo "<p style='text-align:center;'>Cadastro realizado com sucesso! <a href='index.html'>Voltar ao Login</a></p>";
} else {
    echo "<p style='color:red;text-align:center;'>Preencha todos os campos!</p>";
}
?>

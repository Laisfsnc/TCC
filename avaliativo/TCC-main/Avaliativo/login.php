<?php
session_start();

$usuarios = [
    "maria@escola.com" => "Professor",
    "joao@aluno.com" => "Aluno",
    "admin@escola.com" => "Admin"
];

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (array_key_exists($email, $usuarios)) {
    $_SESSION['usuario'] = $usuarios[$email];
    header("Location: dashboard.php");
} else {
    echo "<p style='color:red;text-align:center;'>Usuário ou senha inválidos</p>";
    echo "<a href='index.html'>Voltar</a>";
}
?>

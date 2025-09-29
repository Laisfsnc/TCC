<?php
// Aqui, você pegaria o token da URL e validaria no banco

// Como não tem banco, vamos supor que o token é válido para qualquer valor

if (!isset($_GET['token'])) {
    die("Token inválido.");
}

$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Redefinir senha</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f2f2f2; padding: 20px;}
    .container { max-width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; }
    input[type="password"], input[type="submit"] { width: 100%; padding: 10px; margin-top: 10px; }
    input[type="submit"] { background: #28a745; color: white; border: none; cursor: pointer; }
    input[type="submit"]:hover { background: #1e7e34; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Redefinir senha</h2>
    <form action="resetar_senha.php?token=<?=htmlspecialchars($token)?>" method="POST">
      <label for="senha">Nova senha:</label><br/>
      <input type="password" name="senha" id="senha" required /><br/>
      <label for="confirma_senha">Confirme a nova senha:</label><br/>
      <input type="password" name="confirma_senha" id="confirma_senha" required /><br/>
      <input type="submit" value="Redefinir senha" />
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $senha = $_POST['senha'];
        $confirma = $_POST['confirma_senha'];

        if ($senha !== $confirma) {
            echo "<p style='color:red;'>As senhas não coincidem!</p>";
            exit;
        }

        if (strlen($senha) < 6) {
            echo "<p style='color:red;'>A senha deve ter pelo menos 6 caracteres.</p>";
            exit;
        }

        // Aqui você atualizaria a senha do usuário no banco de dados (após validar token)
        // E invalidaria o token para que não seja usado novamente

        echo "<p style='color:green;'>Senha redefinida com sucesso!</p>";
    }
    ?>
  </div>
</body>
</html>
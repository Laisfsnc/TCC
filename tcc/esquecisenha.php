<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Esqueci minha senha</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f2f2f2; padding: 20px;}
    .container { max-width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; }
    input[type="email"], input[type="submit"] { width: 100%; padding: 10px; margin-top: 10px; }
    input[type="submit"] { background: #007BFF; color: white; border: none; cursor: pointer; }
    input[type="submit"]:hover { background: #0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Recuperar senha</h2>
    <form action="esqueci_senha.php" method="POST">
      <label for="email">Digite seu e-mail:</label><br/>
      <input type="email" name="email" id="email" required /><br/>
      <input type="submit" value="Enviar link de recuperação" />
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo "<p style='color:red;'>E-mail inválido!</p>";
            exit;
        }

        // Aqui você faria a busca no banco se o email existe
        // Como não tem banco, vamos simular que existe um usuário com o email test@example.com
        if ($email === "test@example.com") {
            // Gerar token simples para recuperação
            $token = bin2hex(random_bytes(16));

            // Aqui você salvaria esse token no banco, associado ao usuário, com tempo de expiração

            // Montar link para resetar a senha
            $reset_link = "http://seusite.com/resetar_senha.php?token=$token";

            // Enviar email (exemplo usando mail())
            $assunto = "Recuperação de senha";
            $mensagem = "Olá!\n\nClique no link abaixo para redefinir sua senha:\n$reset_link\n\nSe você não solicitou, ignore este e-mail.";
            $headers = "From: no-reply@seusite.com";

            if (mail($email, $assunto, $mensagem, $headers)) {
                echo "<p style='color:green;'>Um link para redefinição foi enviado para seu e-mail.</p>";
            } else {
                echo "<p style='color:red;'>Erro ao enviar o e-mail. Tente novamente.</p>";
            }

        } else {
            echo "<p style='color:red;'>E-mail não cadastrado.</p>";
        }
    }
    ?>
  </div>
</body>
</html>
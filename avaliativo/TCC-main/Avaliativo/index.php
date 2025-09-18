<?php 
include "conexao.php";
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro de Usuário - AvaliAtivo</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-600 via-purple-400 to-purple-600 flex items-center justify-center font-sans">

  <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-8 max-w-md w-full">
    <div class="flex flex-col items-center mb-6">
      <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true" role="img" aria-label="Ícone de formatura representando AvaliAtivo">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.75 12.083 12.083 0 015.84 10.578L12 14z" />
        </svg>
      </div>
      <h1 class="text-2xl font-semibold text-green-800">AvaliAtivo</h1>
      <p class="text-center text-gray-600 mt-1 text-sm">Cadastre-se para acessar o sistema</p>
    </div>

    <form id="cadastroForm" class="space-y-5" novalidate>
      <div>
        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome completo</label>
        <input
          type="text"
          id="nome"
          name="nome"
          required
          placeholder="Digite seu nome completo"
          class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-400"
          autocomplete="name"
        />
        <p id="nomeError" class="text-red-600 mt-1 text-xs hidden">Preencha seu nome completo.</p>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          required
          placeholder="seu@email.com"
          class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-400"
          autocomplete="email"
        />
        <p id="emailError" class="text-red-600 mt-1 text-xs hidden">Informe um e-mail válido.</p>
      </div>

      <div>
        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
        <input
          type="password"
          id="senha"
          name="senha"
          required
          placeholder="********"
          class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-green-400"
          autocomplete="new-password"
          minlength="6"
        />
        <p id="senhaError" class="text-red-600 mt-1 text-xs hidden">Senha deve ter no mínimo 6 caracteres.</p>
      </div>

      <div>
        <label for="tipoUsuario" class="block text-sm font-medium text-gray-700 mb-1">Tipo de usuário</label>
        <select
      
          id="tipoUsuario"
          name="tipoUsuario"
          required
          class="w-full border border-gray-300 rounded-md p-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-400"
        >
          <option value="" disabled selected>Selecione o tipo de usuário</option>
          <option value="admin">Administrador</option>
          <option value="professor">Professor</option>
          <option value="aluno">Aluno</option>

        </select>
        <p id="tipoUsuarioError" class="text-red-600 mt-1 text-xs hidden">Selecione um tipo de usuário.</p>
      </div>

      <button
        type="submit"
        class="w-full bg-green-500 hover:bg-green-600 transition-colors duration-200 text-white font-medium py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
      >
        Cadastrar
      </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
      Já possui uma conta?
      <a href="loggin.html" class="text-green-600 hover:underline">Faça login aqui</a>
    </p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $tipoUsuario = $_POST['tipoUsuario'] ?? '';

        if ($tipoUsuario == "aluno") {
            $sql = "INSERT INTO Alunos (nome, email, senha, tipoUsuario) VALUES ('$nome', '$email', '$senha', '$tipoUsuario')";
        } else if ($tipoUsuario == "admin") {
            $sql = "INSERT INTO Admin (nome, email, senha, tipoUsuario) VALUES ('$nome', '$email', '$senha', '$tipoUsuario')";
        } else if ($tipoUsuario == "professor") {
            $sql = "INSERT INTO Professor (nome, email, senha, tipoUsuario) VALUES ('$nome', '$email', '$senha', '$tipoUsuario')";
        }
        //colocar  Aqui você pode executar a query usando mysqli_query ou PDO
    }
    ?>
  </div>

  <script>
    const form = document.getElementById('cadastroForm');

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Reseta mensagens de erro
      const errors = ['nomeError', 'emailError', 'senhaError', 'tipoUsuarioError'];
      errors.forEach(id => document.getElementById(id).classList.add('hidden'));

      // Coleta valores
      const nome = form.nome.value.trim();
      const email = form.email.value.trim();
      const senha = form.senha.value;
      const tipoUsuario = form.tipoUsuario.value;

      let valid = true;

      if (nome.length === 0) {
        document.getElementById('nomeError').classList.remove('hidden');
        valid = false;
      }

      if (email.length === 0 || !validateEmail(email)) {
        document.getElementById('emailError').classList.remove('hidden');
        valid = false;
      }

      if (senha.length < 6) {
        document.getElementById('senhaError').classList.remove('hidden');
        valid = false;
      }

      if (!tipoUsuario) {
        document.getElementById('tipoUsuarioError').classList.remove('hidden');
        valid = false;
      }

      if (!valid) return;

      // Para demonstração, mostramos um alerta de sucesso
      alert(`Usuário cadastrado:\nNome: ${nome}\nEmail: ${email}\nTipo: ${tipoUsuario.toUpperCase()}`);

      // Aqui entraria a lógica para integrar com backend via fetch/ajax etc.

      form.reset();
    });

    function validateEmail(email) {
      // Regex simples para validação de email
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email.toLowerCase());
    }
  </script>
</body>
</html>




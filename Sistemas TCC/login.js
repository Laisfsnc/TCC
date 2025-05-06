// Elementos da interface
const tabLogin = document.getElementById('tabLogin');
const tabCadastro = document.getElementById('tabCadastro');
const formLogin = document.getElementById('formLogin');
const formCadastro = document.getElementById('formCadastro');
const messageBox = document.getElementById('message');

// Alternar abas Login/Cadastro
tabLogin.addEventListener('click', () => {
  tabLogin.classList.add('active');
  tabCadastro.classList.remove('active');
  formLogin.classList.add('active');
  formCadastro.classList.remove('active');
  messageBox.textContent = '';
});
tabCadastro.addEventListener('click', () => {
  tabCadastro.classList.add('active');
  tabLogin.classList.remove('active');
  formCadastro.classList.add('active');
  formLogin.classList.remove('active');
  messageBox.textContent = '';
});

// Função para buscar professores cadastrados no localStorage
function getProfessores() {
  const dados = localStorage.getItem('professores');
  return dados ? JSON.parse(dados) : [];
}
// Salvar lista professores localStorage
function saveProfessores(lista) {
  localStorage.setItem('professores', JSON.stringify(lista));
}

// Cadastrar professor novo
formCadastro.addEventListener('submit', (e) => {
  e.preventDefault();
  const nome = document.getElementById('cadNome').value.trim();
  const email = document.getElementById('cadEmail').value.trim().toLowerCase();
  const senha = document.getElementById('cadSenha').value;

  if (!nome || !email || !senha) {
    messageBox.textContent = 'Preencha todos os campos do cadastro.';
    return;
  }

  // Verificar se email já está cadastrado
  const profess = getProfessores();
  if (profess.some(p => p.email === email)) {
    messageBox.textContent = 'Email já cadastrado.';
    return;
  }
  // Criar novo professor (senha sem hash por simplicidade)
  profess.push({ nome, email, senha, alunos: [] }); 
  saveProfessores(profess);

  messageBox.style.color = 'green';
  messageBox.textContent = 'Cadastro realizado com sucesso! Faça login.';
  formCadastro.reset();
  tabLogin.click();
});

// Login professor
formLogin.addEventListener('submit', (e) => {
  e.preventDefault();
  const email = document.getElementById('loginEmail').value.trim().toLowerCase();
  const senha = document.getElementById('loginSenha').value;

  if (!email || !senha) {
    messageBox.textContent = 'Preencha email e senha.';
    return;
  }

  const profess = getProfessores();
  const user = profess.find(p => p.email === email && p.senha === senha);
  if (!user) {
    messageBox.textContent = 'Email ou senha inválidos.';
    return;
  }

  // Salvar sessão no localStorage
  localStorage.setItem('sessaoProfessor', JSON.stringify(user));
  // Redirecionar para dashboard
  window.location.href = 'dashboard.html';
});

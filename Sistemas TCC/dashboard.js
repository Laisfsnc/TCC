// Lógica dashboard - gerenciamento professor, alunos e gráfico

// Elementos DOM
const nomeProfessorEl = document.getElementById('nomeProfessor');
const btnLogout = document.getElementById('btnLogout');
const listaAlunosEl = document.getElementById('listaAlunos');
const btnAddAluno = document.getElementById('btnAddAluno');
const formAluno = document.getElementById('formAluno');
const graficoContainer = document.getElementById('graficoContainer');

const inputAlunoId = document.getElementById('alunoId');
const inputNomeAluno = document.getElementById('nomeAluno');
const inputNota = document.getElementById('notaAluno');
const selectQualitativa = document.getElementById('qualitativaAluno');
const selectComportamento = document.getElementById('comportamentoAluno');

let radarChart;
let professorAtual = null;
let alunos = [];
let alunoSelecionadoId = null;

// Funções para localStorage professores
function getProfessores() {
  const dados = localStorage.getItem('professores');
  return dados ? JSON.parse(dados) : [];
}
function saveProfessores(lista) {
  localStorage.setItem('professores', JSON.stringify(lista));
}

// Função para carregar professor da sessão
function carregarProfessorSessao() {
  const sessao = localStorage.getItem('sessaoProfessor');
  if (!sessao) {
    window.location.href = 'index.html';
    return null;
  }
  return JSON.parse(sessao);
}

// Atualizar sessão localStorage (para salvar alterações)
function atualizarSessao(professor) {
  localStorage.setItem('sessaoProfessor', JSON.stringify(professor));
}

// Criar/Atualizar a lista de alunos na interface
function atualizarListaAlunos() {
  listaAlunosEl.innerHTML = '';
  if (alunos.length === 0) {
    listaAlunosEl.innerHTML = '<li><em>Nenhum aluno cadastrado.</em></li>';
    formAluno.style.display = 'none';
    graficoContainer.style.display = 'none';
    return;
  }
  alunos.forEach((aluno) => {
    const li = document.createElement('li');
    li.textContent = aluno.nome;
    li.dataset.id = aluno.id;
    li.classList.toggle('selected', aluno.id === alunoSelecionadoId);
    li.title = 'Clique para selecionar o aluno';
    li.addEventListener('click', () => {
      selecionarAluno(aluno.id);
    });
    listaAlunosEl.appendChild(li);
  });
}

// Seleciona um aluno e carrega dados no formulário e gráfico
function selecionarAluno(id) {
  alunoSelecionadoId = id;
  const aluno = alunos.find(a => a.id === id);
  if (!aluno) return;

  // Atualizar seleção UI
  atualizarListaAlunos();

  // Preencher formulário
  inputAlunoId.value = aluno.id;
  inputNomeAluno.value = aluno.nome;
  inputNota.value = aluno.nota;
  selectQualitativa.value = aluno.qualitativa;
  selectComportamento.value = aluno.comportamento;

  formAluno.style.display = 'block';
  graficoContainer.style.display = 'block';

  atualizarGrafico(aluno);
}

// Função para atualizar gráfico radar do aluno
function atualizarGrafico(aluno) {
  const ctx = document.getElementById('radarChart').getContext('2d');

  // Converter qualitativa e comportamento para escala 0-10 para radar
  let qualitativaEscalada = (parseFloat(aluno.qualitativa) / 2) * 10;
  let comportamentoEscalado = (parseFloat(aluno.comportamento) / 2) * 10;

  const dataGrafico = {
    labels: ['Nota', 'Avaliação Qualitativa', 'Comportamento'],
    datasets: [
      {
        label: `Avaliação de ${aluno.nome}`,
        data: [aluno.nota, qualitativaEscalada, comportamentoEscalado],
        fill: true,
        backgroundColor: 'rgba(46, 204, 113, 0.3)', // verde claro
        borderColor: 'rgba(46, 204, 113, 1)', // verde forte
        pointBackgroundColor: 'rgba(46, 204, 113, 1)',
        pointRadius: 6,
        borderWidth: 2
      }
    ]
  };

  const configGrafico = {
    type: 'radar',
    data: dataGrafico,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        r: {
          angleLines: { color: '#bdc3c7' },
          grid: { color: '#ecf0f1' },
          suggestedMin: 0,
          suggestedMax: 10,
          pointLabels: { font: { size: 14 }, color: '#34495e' },
          ticks: {
            stepSize: 2,
            color: '#7f8c8d',
            font: { size: 12 }
          }
        }
      },
      plugins: {
        legend: { display: true, labels: { font: { size: 14 }, color: '#2c3e50' } },
        tooltip: {
          enabled: true,
          backgroundColor: '#27ae60',
          titleFont: { size: 14, weight: 'bold' },
          bodyFont: { size: 13 },
          padding: 8
        }
      }
    }
  };

  if (radarChart) {
    radarChart.destroy();
  }
  radarChart = new Chart(ctx, configGrafico);
}

// Criar novo aluno com id único simples
function criarNovoAluno(nome) {
  return {
    id: 'aluno-' + Date.now(),
    nome: nome,
    nota: 0,
    qualitativa: 0,
    comportamento: 0
  };
}

// Atualiza lista alunos em professor e salva
function salvarAlunos() {
  const profess = getProfessores();
  const indexProfessor = profess.findIndex(p => p.email === professorAtual.email);
  if (indexProfessor >= 0) {
    profess[indexProfessor].alunos = alunos;
    saveProfessores(profess);
    professorAtual.alunos = alunos;
    atualizarSessao(professorAtual);
  }
}

// Event: logout
btnLogout.addEventListener('click', () => {
  localStorage.removeItem('sessaoProfessor');
  window.location.href = 'index.html';
});

// Event: adicionar novo aluno
btnAddAluno.addEventListener('click', () => {
  const nomeAluno = prompt('Digite o nome do novo aluno:');
  if (nomeAluno && nomeAluno.trim()) {
    const novoAluno = criarNovoAluno(nomeAluno.trim());
    alunos.push(novoAluno);
    salvarAlunos();
    alunoSelecionadoId = novoAluno.id;
    atualizarListaAlunos();
    selecionarAluno(novoAluno.id);
  }
});

// Event: Submissão formulário avaliação aluno
formAluno.addEventListener('submit', (e) => {
  e.preventDefault();

  const id = inputAlunoId.value;
  const nome = inputNomeAluno.value.trim();
  const nota = parseFloat(inputNota.value);
  const qualitativa = selectQualitativa.value;
  const comportamento = selectComportamento.value;

  if (!nome || isNaN(nota) || qualitativa === "" || comportamento === "") {
    alert('Preencha todos os campos corretamente');
    return;
  }
  if (nota <0 || nota > 10) {
    alert('Nota deve estar entre 0 e 10');
    return;
  }

  // Atualiza aluno
  const aluno = alunos.find(a => a.id === id);
  if (!aluno) return;

  aluno.nome = nome;
  aluno.nota = nota;
  aluno.qualitativa = qualitativa;
  aluno.comportamento = comportamento;

  salvarAlunos();
  atualizarListaAlunos();
  selecionarAluno(id);
});

// Inicialização do dashboard
function iniciarDashboard() {
  professorAtual = carregarProfessorSessao();
  if (!professorAtual) return;

  nomeProfessorEl.textContent = `Olá, Professor ${professorAtual.nome}`;

  alunos = professorAtual.alunos || [];
  atualizarListaAlunos();
  if (alunos.length > 0) {
    selecionarAluno(alunos[0].id);
  }
}
iniciarDashboard();

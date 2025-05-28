// Pega o nome da profissão da URL
const params = new URLSearchParams(window.location.search);
const profissao = params.get('profissao') || 'Profissional';

// Define o título com o nome da profissão
document.getElementById('tituloChat').textContent = `Chat com ${profissao}`;

// Referências
const form = document.getElementById('chatForm');
const chatBox = document.getElementById('chatBox');
const input = document.getElementById('mensagem');
const arquivo = document.getElementById('arquivo');

// Envia texto ou mídia
form.addEventListener('submit', (e) => {
  e.preventDefault();
  const texto = input.value.trim();
  if (texto) {
    adicionarMensagem('Você', texto);
    input.value = '';
  }
});

arquivo.addEventListener('change', () => {
  const file = arquivo.files[0];
  if (!file) return;

  const tipo = file.type;

  let media;
  if (tipo.startsWith('image/')) {
    media = `<img src="${URL.createObjectURL(file)}" class="max-w-xs rounded" />`;
  } else if (tipo.startsWith('audio/')) {
    media = `<audio controls src="${URL.createObjectURL(file)}" class="w-full"></audio>`;
  } else if (tipo.startsWith('video/')) {
    media = `<video controls src="${URL.createObjectURL(file)}" class="max-w-xs rounded"></video>`;
  } else {
    media = `<p>Arquivo não suportado</p>`;
  }

  adicionarMensagem('Você', media, true);
  arquivo.value = ''; // Reset input
});

function adicionarMensagem(remetente, conteudo, isHtml = false) {
  const msg = document.createElement('div');
  msg.className = 'bg-gray-200 rounded p-2 max-w-sm';
  msg.innerHTML = `<strong>${remetente}:</strong><br>${isHtml ? conteudo : escapeHTML(conteudo)}`;
  chatBox.appendChild(msg);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function escapeHTML(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

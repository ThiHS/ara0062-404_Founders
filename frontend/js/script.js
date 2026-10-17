// ===========================================================================
// ARA0062 — comportamento da página
// Este arquivo começa vazio de propósito: o JavaScript entra no ciclo 6.
// Ele já está ligado ao index.html pela linha
// <script src="js/script.js" defer></script>
// ===========================================================================
/* Mascara de telefone */

function mascara(campo,formatacao){
    v_obj=campo
    v_fun=formatacao
    setTimeout(execmascara, 1);
}
function execmascara(){ 
    v_obj.value=v_fun(v_obj.value)
}
function mascTelefone(v){
    v=v.replace(/\D/g,""); //Remove o que não é numero
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses
    v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hifen
    return v;
}
function id( el ){
	return document.getElementById( el );
}
document.addEventListener('DOMContentLoaded', function() {
    
    const campoTelefone = document.getElementById("telefone");
    if (campoTelefone) {
        campoTelefone.onkeyup = function() {
            if (typeof mascara === 'function' && typeof mascTelefone === 'function') {
                mascara(this, mascTelefone);
            }
        };
    }



/* Mascara de email */

// Função que confere se o e-mail tem @ e .
function validarEmail(email) {
  const padrao = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  return padrao.test(email);
}

const formUsuario = document.getElementById("form");
const inputEmail = document.getElementById("email");

if (formUsuario && inputEmail) {
  formUsuario.addEventListener("submit", function (evento) {
    const valorDigitado = inputEmail.value.trim();

    if (!validarEmail(valorDigitado)) {
      alert("Por favor, insira um endereço de e-mail válido.");
      inputEmail.focus();
      evento.preventDefault(); // Impede o envio apenas se o e-mail for inválido
      return;
    }

    // A matrícula precisa ter 12 números
    const campoMatricula = document.getElementById("matricula");
    if (campoMatricula && campoMatricula.value.length !== 12) {
      const erro = document.getElementById("erro-matricula");
      if (erro) erro.style.display = "block";
      campoMatricula.focus();
      evento.preventDefault();
      return;
    }

    // não tem servidor para receber o POST (tirar o preventDefault e o reset quando criar o backend, estão aí apenas para tirar o erro 405)
    evento.preventDefault();
    const aviso = document.getElementById("aviso");
    if (aviso) {
      aviso.textContent = "Cadastro enviado com sucesso!";
    }
    formUsuario.reset();
  });
}


/*Mascara de matricula*/

    const inputMatricula = document.getElementById("matricula");
    const mensagemErro = document.getElementById("erro-matricula");

    if (inputMatricula) {
//Limpa letras enquanto o usuário digita e deixa apenas números
      inputMatricula.addEventListener("input", function () {
//encontra tudo o que não for número e substitui por nada
          this.value = this.value.replace(/\D/g, "");
      });

//Verifica se tem 12 números
      inputMatricula.addEventListener("blur", function () {
          const totalCaracteres = this.value.length;

          //Mostra o erro caso não tenha
          if (totalCaracteres > 0 && totalCaracteres !== 12) {
              mensagemErro.style.display = "block";
          } else {
              mensagemErro.style.display = "none";
          }
      });
    }



/* Máscara de nome próprio */

    const inputNome = document.getElementById("nome");

    if (inputNome) {
      inputNome.addEventListener('input', (e) => {
        let valor = e.target.value;

//Remove números e caracteres especiais
        valor = valor.replace(/[^a-zA-Z\u00C0-\u00FF\s]/g, "");

//Primeira letra maiuscula e o resto minuscula
        valor = valor.toLowerCase().replace(/(?:^|\s)\S/g, (letra) => letra.toUpperCase());

        e.target.value = valor;
      });
    }
});



/* Disciplinas vinculadas só aparecem se marcar sim em "disciplinas vinculadas" */

function verificarResposta() {
  const select = document.getElementById("requisito");
  const divChecklist = document.getElementById("checklist-materia");

  if (!select || !divChecklist) return;

  if (select.value.trim().toLowerCase() === "sim") {
    divChecklist.style.display = "block";
  } else {
    divChecklist.style.display = "none";
  }
}

const requisitoSelect = document.getElementById("requisito");

if (requisitoSelect) {
    requisitoSelect.addEventListener("change", verificarResposta);
    verificarResposta();
}

/* Mensagem de sucesso ao enviar o cadastro de disciplina */
//(tirar o preventDefault e o reset quando criar o backend, estão aí apenas para tirar o erro 405)

const formDisciplina = document.getElementById('form-disciplina');
if (formDisciplina) {
  formDisciplina.addEventListener('submit', function (event) {
    event.preventDefault();

    const aviso = document.getElementById('aviso');
    if (aviso) {
      aviso.textContent = 'Disciplina cadastrada com sucesso!';
    }
    this.reset();
    verificarResposta();
  });
}
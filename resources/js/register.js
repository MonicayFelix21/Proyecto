// public/js/register.js

// Paso 1: verifica si el email ya existe
function verificarEmail() {
  const emailInput = document.getElementById('email');
  const email = emailInput.value.trim();
  const feedback = document.getElementById('email-feedback');
  const nextBtn = document.getElementById('next-btn-email');

  if (!email || !email.indexOf('@') === -1) {
    feedback.classList.add('d-none');
    nextBtn.disabled = true;
    return;
  }

  fetch(`/verificar-email?email=${encodeURIComponent(email)}`)
    .then(res => res.json())
    .then(data => {
      if (data.existe) {
        feedback.classList.remove('d-none');
        nextBtn.disabled = true;
      } else {
        feedback.classList.add('d-none');
        nextBtn.disabled = false;
      }
    })
    .catch(() => {
      feedback.classList.add('d-none');
      nextBtn.disabled = true;
    });
}

// Navegación entre pasos
function goToStep2() {
  const emailInput = document.getElementById('email');
  if (!emailInput.checkValidity()) return emailInput.reportValidity();

  // reset contraseña
  const pwd = document.getElementById('password');
  if (pwd) {
    pwd.value = '';
    checkPassword();
  }

  document.getElementById('step-email').classList.add('hidden');
  document.getElementById('step-password').classList.remove('hidden');
  document.getElementById('titulo-inicial').style.display = 'none';
}

function backToStep1() {
  document.getElementById('step-password').classList.add('hidden');
  document.getElementById('step-email').classList.remove('hidden');
  document.getElementById('titulo-inicial').style.display = 'block';

  // limpia email y botón
  const email = document.getElementById('email');
  email.value = '';
  verificarEmail();
}

function goToStep3() {
  document.getElementById('step-password').classList.add('hidden');
  document.getElementById('step-profile').classList.remove('hidden');
}

function backToStep2() {
  document.getElementById('step-profile').classList.add('hidden');
  document.getElementById('step-password').classList.remove('hidden');
}

// Mostrar/ocultar contraseña
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('toggle-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye-slash','bi-eye');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye','bi-eye-slash');
  }
}

// Validación en tiempo real de contraseña
function checkPassword() {
  const pw = document.getElementById('password').value;
  const btn = document.getElementById('submit-btn');
  const hasLetter   = /[a-zA-Z]/.test(pw);
  const hasSpecial  = /[\d\W]/.test(pw);
  const isLongEnough= pw.length >= 10;

  updateRequirement('req-letter', hasLetter);
  updateRequirement('req-special', hasSpecial);
  updateRequirement('req-length', isLongEnough);

  btn.disabled = !(hasLetter && hasSpecial && isLongEnough);
}

function updateRequirement(id, ok) {
  const el   = document.getElementById(id);
  const icon = document.getElementById('icon-'+id.split('-')[1]);
  el.classList.toggle('valid',   ok);
  el.classList.toggle('invalid', !ok);
  el.classList.toggle('neutral', pw.length===0);
  icon.className = ok
    ? 'bi me-2 bi-check-circle-fill text-success'
    : 'bi me-2 bi-circle text-secondary';
}

function validateStep3() {
  const required = ['nombre','dia','mes','anio'];
  for (let field of required) {
    if (!document.getElementById(field).value) {
      alert('Completa todos los campos');
      return false;
    }
  }
  if (!document.querySelector('input[name="genero"]:checked')) {
    alert('Selecciona un género');
    return false;
  }
  return true;
}

// Resetea el formulario (si vienes de otro flujo)
function resetFormularioRegistro() {
  ['email','password','nombre','dia','anio']
    .forEach(id=>document.getElementById(id).value='');
  document.getElementById('mes').selectedIndex = 0;
  document.querySelectorAll('input[name="genero"]').forEach(i=>i.checked=false);
  ['step-password','step-profile'].forEach(s=>document.getElementById(s).classList.add('hidden'));
  document.getElementById('step-email').classList.remove('hidden');
  document.getElementById('titulo-inicial').style.display='block';
  document.getElementById('next-btn-email').disabled = true;
  document.getElementById('submit-btn').disabled    = true;
}

// Al cargar la página, resetea si vino de un registro previo
window.addEventListener('DOMContentLoaded', ()=> {
  if (localStorage.getItem('registro_nuevo')==='true') {
    resetFormularioRegistro();
    localStorage.removeItem('registro_nuevo');
  }
});

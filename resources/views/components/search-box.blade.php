{{-- resources/views/components/search-box.blade.php --}}
<div class="position-relative" style="max-width:600px; width:100%;">
  <div class="buscador-wrapper d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-search text-white fs-5"></i>
      <input
        id="busqueda"
        type="text"
        class="form-control bg-transparent border-0 text-white p-0"
        placeholder="¿Qué quieres reproducir?"
        autocomplete="off"
      >
    </div>
    <div class="d-flex align-items-center">
      <div class="mx-3" style="width:1px;height:24px;background:rgba(255,255,255,0.3)"></div>
      <button class="btn p-0" style="width:30px;height:30px;">
        <i class="bi bi-inbox text-white fs-5"></i>
      </button>
    </div>
  </div>

  <div
    id="sugerencias"
    class="position-absolute bg-dark text-white rounded mt-1 px-3 py-2 w-100"
    style="z-index:1000; display:none;"
  ></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input  = document.getElementById('busqueda'),
        suger  = document.getElementById('sugerencias');
  let  timer;

  input.addEventListener('input', () => {
    clearTimeout(timer);
    const q = input.value.trim();
    if (q.length < 2) {
      suger.style.display = 'none';
      return;
    }
    timer = setTimeout(() => {
      fetch(`{{ route('spotify.search') }}?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(items => {
          if (! items.length) {
            suger.style.display = 'none';
            return;
          }
          suger.innerHTML = items.map(item => `
            <div class="sugerencia py-2 border-bottom" data-id="${item.id}">
              <strong>${item.titulo}</strong><br>
              <small>${item.artista}</small>
            </div>
          `).join('');
          suger.style.display = 'block';
        });
    }, 300);
  });

  document.addEventListener('click', e => {
    if (! input.contains(e.target) && ! suger.contains(e.target)) {
      suger.style.display = 'none';
    }
  });

  suger.addEventListener('click', e => {
    const el = e.target.closest('.sugerencia');
    if (! el) return;
    const trackId = el.dataset.id;
    // Aquí puedes redirigir o reproducir la pista
    console.log('Seleccionado track ID:', trackId);
    suger.style.display = 'none';
  });
});
</script>
@endpush

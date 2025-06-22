<div class="position-relative" style="max-width:600px; width:100%;">
  <div class="buscador-wrapper">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-search text-white fs-5"></i>
      <input id="busqueda"
             type="text"
             class="form-control bg-transparent border-0 text-white p-0"
             placeholder="¿Qué quieres reproducir?"
             autocomplete="off">
    </div>
    <div class="d-flex align-items-center">
      <div class="mx-3" style="width:1px;height:24px;background:rgba(255,255,255,0.3)"></div>
      <button class="btn p-0" style="width:30px;height:30px;">
        <i class="bi bi-inbox text-white fs-5"></i>
      </button>
    </div>
  </div>
  <div id="sugerencias"
       class="position-absolute bg-dark text-white rounded mt-1 px-3 py-2 w-100"
       style="z-index:1000; display:none;"></div>
</div>

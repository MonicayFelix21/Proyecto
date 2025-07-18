// resources/js/carrusel.js

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.grupo-carrusel').forEach(grupo => {
    const carr = grupo.querySelector('#carrusel-artistas'),
          izq  = grupo.querySelector('#scroll-izquierda'),
          der  = grupo.querySelector('#scroll-derecha');

    function actualizarBotones() {
      const max = carr.scrollWidth - carr.clientWidth,
            x   = carr.scrollLeft;
      izq.style.display = x > 10       ? 'flex' : 'none';
      der.style.display = x < max - 10 ? 'flex' : 'none';
    }

    der.addEventListener('click', () =>
      carr.scrollBy({ left: carr.clientWidth * 0.6, behavior: 'smooth' })
    );
    izq.addEventListener('click', () =>
      carr.scrollBy({ left: -carr.clientWidth * 0.6, behavior: 'smooth' })
    );
    carr.addEventListener('scroll', actualizarBotones);
    window.addEventListener('resize', actualizarBotones);
    setTimeout(actualizarBotones, 300);
  });
});

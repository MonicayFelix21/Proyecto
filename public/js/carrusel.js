document.addEventListener('DOMContentLoaded', () => {
  const carrusel = document.getElementById('carrusel-artistas');
  const btnIzquierda = document.getElementById('scroll-izquierda');
  const btnDerecha = document.getElementById('scroll-derecha');

  if (!carrusel || !btnIzquierda || !btnDerecha) return;

  btnIzquierda.addEventListener('click', () => {
    carrusel.scrollBy({
      left: -carrusel.clientWidth * 0.7,
      behavior: 'smooth'
    });
  });

  btnDerecha.addEventListener('click', () => {
    carrusel.scrollBy({
      left: carrusel.clientWidth * 0.7,
      behavior: 'smooth'
    });
  });
});

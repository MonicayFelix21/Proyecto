{{-- resources/views/components/artist-card.blade.php --}}
<div class="text-center" style="flex:0 0 auto; width:160px;">
  <img src="{{ asset($artist->imagen) }}"
       alt="{{ $artist->nombre }}"
       class="img-fluid rounded-circle mb-2 hover-zoom"
       style="width:160px;height:160px;object-fit:cover;transition:transform 0.3s;">
  <p class="mb-0 fw-semibold text-white">{{ $artist->nombre }}</p>
  <small class="text-white-50">Artista</small>
</div>

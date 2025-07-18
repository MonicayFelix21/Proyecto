@extends('layouts.app')
@section('content')
<div class="container-fluid px-0" style="background: linear-gradient(135deg, #450af5 0%, #c4efd9 100%); min-height: 100vh;">
    <div class="row justify-content-center pt-5">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center gap-4 mb-4">
                <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #450af5, #c4efd9); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-heart-fill" style="font-size: 64px; color: white;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-white-50 fw-bold mb-1" style="font-size: 13px;">Lista</div>
                    <h1 class="display-4 fw-bold text-white mb-2">Canciones que te gustan</h1>
                    <div class="text-white-50">{{ $user->name ?? $user->email }} • {{ count($tracks) }} canciones</div>
                </div>
            </div>
            <div class="card bg-dark bg-opacity-75 rounded-4 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 48px;"></th>
                                <th>Título</th>
                                <th>Artista</th>
                                <th>Álbum</th>
                                <th style="width: 80px;">Duración</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($tracks as $item)
                            @php $track = $item['track'] ?? null; @endphp
                            @if($track)
                            <tr class="song-card" data-uri="{{ $track['uri'] }}" data-titulo="{{ $track['name'] }}" data-artista="{{ $track['artists'][0]['name'] ?? '' }}" data-imagen="{{ $track['album']['images'][0]['url'] ?? '' }}">
                                <td>
                                    <img src="{{ $track['album']['images'][2]['url'] ?? $track['album']['images'][1]['url'] ?? $track['album']['images'][0]['url'] ?? asset('imagenes/default.png') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                </td>
                                <td class="text-white">{{ $track['name'] }}</td>
                                <td class="text-white-50">{{ $track['artists'][0]['name'] ?? '' }}</td>
                                <td class="text-white-50">{{ $track['album']['name'] ?? '' }}</td>
                                <td class="text-white-50">@php $ms = $track['duration_ms'] ?? 0; echo sprintf('%d:%02d', floor($ms/60000), ($ms/1000)%60); @endphp</td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="5" class="text-center text-white-50 py-5">No tienes canciones guardadas en Spotify.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Permite reproducir desde la lista de "me gusta"
document.querySelectorAll('.song-card').forEach(card => {
    card.addEventListener('click', function() {
        const uri = this.dataset.uri;
        const titulo = this.dataset.titulo;
        const artista = this.dataset.artista;
        const imagen = this.dataset.imagen;
        if (window.playSpotify && uri) {
            window.playSpotify(uri);
            window.currentTrackId = uri.split(':').pop();
            // Actualiza reproductor
            if (window.updateTrackInfo) window.updateTrackInfo(titulo, artista, imagen);
        }
    });
});
</script>
@endpush

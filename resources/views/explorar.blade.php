@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-white mb-4" style="font-size:2rem;">Explorar todo</h2>
    <div class="row g-4" id="explorar-categorias">
        {{-- Las categorías se cargarán vía JS/AJAX --}}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/spotify/categorias')
        .then(res => res.json())
        .then(data => {
            const cont = document.getElementById('explorar-categorias');
            if (!data.categories || !data.categories.items) return;
            cont.innerHTML = data.categories.items.map(cat => `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="explore-card" style="background:${getColor(cat.id)};">
                        <div class="explore-img-wrap">
                            <img src="${cat.icons[0]?.url || '/imagenes/spotify.png'}" alt="${cat.name}" class="explore-img">
                        </div>
                        <div class="explore-title">${cat.name}</div>
                    </div>
                </div>
            `).join('');
        });

    // Colores por categoría (puedes personalizar)
    function getColor(id) {
        const colors = [
            '#e13300', '#1e3264', '#e8115b', '#148a08', '#bc5900', '#8d67ab', '#b49bc8', '#f59b23', '#477d95', '#dc148c', '#777777', '#537aa1', '#ff4632', '#509bf5', '#f037a5', '#c4d62d', '#f59b23', '#ba5d07', '#777777', '#e8115b'
        ];
        let hash = 0;
        for (let i = 0; i < id.length; i++) hash += id.charCodeAt(i);
        return colors[hash % colors.length];
    }
});
</script>

<style>
.explore-card {
    border-radius: 16px;
    padding: 1.5rem 1rem 1rem 1rem;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.12s, box-shadow 0.12s;
}
.explore-card:hover {
    transform: scale(1.04) translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.explore-img-wrap {
    position: absolute;
    right: 0.5rem;
    top: 0.5rem;
    width: 80px;
    height: 80px;
    z-index: 2;
    transform: rotate(18deg);
}
.explore-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}
.explore-title {
    color: #fff;
    font-size: 1.3rem;
    font-weight: 700;
    z-index: 3;
    text-shadow: 0 2px 8px rgba(0,0,0,0.18);
}
</style>
@endsection

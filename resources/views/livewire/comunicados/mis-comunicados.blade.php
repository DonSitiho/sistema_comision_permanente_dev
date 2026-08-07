<div class="row g-5">
    <div class="col-xl-3">
        <div class="card card-flush shadow-sm p-4">
            <div class="d-flex flex-column">
                <div wire:click="filtrarPor(null)" class="d-flex align-items-center justify-content-between rounded p-3 cursor-pointer {{ $categoriaId === null ? 'bg-light-primary text-primary fw-bold' : 'bg-hover-light' }}">
                    <span>Todos</span>
                    <span class="badge badge-circle {{ $categoriaId === null ? 'badge-primary' : 'badge-light' }}">{{ $totalTodos }}</span>
                </div>
                @foreach ($categorias as $categoria)
                    <div wire:key="cat-{{ $categoria->id }}" wire:click="filtrarPor({{ $categoria->id }})"
                         class="d-flex align-items-center justify-content-between rounded p-3 cursor-pointer {{ $categoriaId === $categoria->id ? 'bg-light-primary text-primary fw-bold' : 'bg-hover-light' }}">
                        <span>{{ $categoria->nombre }}</span>
                        <span class="badge badge-circle {{ $categoriaId === $categoria->id ? 'badge-primary' : 'badge-light' }}">{{ $categoria->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div class="d-flex flex-column gap-4">
            @forelse ($comunicados as $comunicado)
                <div wire:key="cmu-{{ $comunicado->id }}" class="card card-flush shadow-sm p-6 {{ !$comunicado->leido_por_mi ? 'border-start border-4 border-primary' : '' }}">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge badge-light-{{ $comunicado->categoria->color ?? 'secondary' }} text-uppercase">{{ $comunicado->categoria->nombre }}</span>
                        <span class="text-muted fs-8">{{ $comunicado->enviado_at?->diffForHumans() }}</span>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $comunicado->titulo }}</h3>
                    <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($comunicado->cuerpo), 220) }}</p>
                    <a href="#" wire:click.prevent="abrir({{ $comunicado->id }})" class="fw-semibold">Leer comunicado completo</a>
                </div>
            @empty
                <div class="card card-flush shadow-sm p-10 text-center text-muted">No hay comunicados en esta categoria.</div>
            @endforelse
        </div>
    </div>
    @livewire('comunicados.comunicado-ver-mas')
</div>
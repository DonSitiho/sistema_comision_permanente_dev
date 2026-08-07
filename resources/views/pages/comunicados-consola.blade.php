<x-default-layout>
    @section('title', 'Consola de Comunicados')
    <div class="container-xxl">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-5">
            <h1 class="fw-bold">Consola de Comunicados</h1>
            <a href="{{ route('comunicados.nuevo') }}" class="btn btn-primary">Crear Nuevo Comunicado</a>
        </div>
        <div class="row g-5 g-xl-10">
            <div class="col-xl-7">
                <livewire:comunicados.comunicados-enviados />
            </div>
            <div class="col-xl-5">
                <livewire:comunicados.comunicado-lecturas />
            </div>
        </div>
    </div>
    @push('styles')
        @livewireStyles
    @endpush
    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>
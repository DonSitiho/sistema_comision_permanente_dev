<x-default-layout>
    @section('title', ($comunicadoId ?? null) ? 'Editar comunicado' : 'Crear comunicado')
    <div class="container-xxl">
        <div class="mb-5">
            <h1 class="fw-bold">{{ ($comunicadoId ?? null) ? 'Editar Comunicado' : 'Crear Comunicado' }}</h1>
        </div>
        <livewire:comunicados.comunicado-form :comunicado-id="$comunicadoId ?? null" />
    </div>
    @push('styles')
        @livewireStyles
    @endpush
    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>
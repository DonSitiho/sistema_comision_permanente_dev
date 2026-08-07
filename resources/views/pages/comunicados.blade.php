<x-default-layout>
    @section('title', 'Institucional')
    <div class="container-xxl">
        <div class="mb-5">
            <h1 class="fw-bold">Institucional</h1>
        </div>
        <livewire:comunicados.mis-comunicados />
    </div>
    @push('styles')
        @livewireStyles
    @endpush
    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>
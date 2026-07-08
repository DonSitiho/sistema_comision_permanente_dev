<x-default-layout>
    @push('styles')
        @livewireStyles
    @endpush

    <div id="kt_content_container" class="container-xxl mt-5">
        <div class="row g-5 g-xl-10">
            <div class="col-xl-12">
                <livewire:notificaciones.historial-notificaciones />
            </div>
        </div>
    </div>

    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>        
        
       
<x-default-layout>
    @push('styles')
        @livewireStyles
    @endpush

    <div id="kt_content_container" class="container-xxl mt-5">
        <div class="row g-5 g-xl-10">
            <div class="col-xl-6">
                <div class="card card-flush shadow-sm p-6 h-xl-100">
                    <div class="card-header pt-2 ps-0">
                        <h3 class="card-title fw-bold text-gray-800 fs-2">Nueva Convocatoria</h3>
                    </div>
                    <livewire:sesiones.convocatoria-modal />
                </div>
            </div>

            <!-- RESUMEN DE REGISTRO RECIENTE Y ACCESO A SESIÓN -->
            <div class="col-xl-6">
                <livewire:sesiones.sesion-modal />
            </div>

        </div>
    </div>

    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>
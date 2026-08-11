<x-default-layout>
    @push('styles')
        <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        @livewireStyles
    @endpush

    <div id="kt_content_container" class="container-xxl mt-10">
        <div class="row g-12 g-xl-12">
            <div class="col-xl-12">
                <div class="card card-flush shadow-sm p-6 h-xl-100">
                    <livewire:grupos-actividades.grupo-actividades />
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        @livewireScripts
    @endpush
</x-default-layout>
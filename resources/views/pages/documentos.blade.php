<x-default-layout>
    @push('styles')
        <link href="{{ asset('assets/plugins/custom/jkanban/jkanban.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        @livewireStyles
    @endpush

    <div id="kt_content_container" class="container-xxl mt-5">
        <div class="row g-5 g-xl-10">
            <div class="col-xl-12">
                {{--<livewire:documentos.documento-uploader />--}}
                {{--<livewire:documentos.documento-uploader :entidad="$sesion" />--}}
                <livewire:documentos.documento-uploader :sesion="$sesion" />
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/jkanban/jkanban.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        @livewireScripts
    @endpush
</x-default-layout>
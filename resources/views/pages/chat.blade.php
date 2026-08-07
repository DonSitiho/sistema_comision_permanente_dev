<x-default-layout>
    @section('title', 'Mensajes')
    <div class="container-xxl">
        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoChat">Nuevo chat</button>
            <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalCrearGrupo">Crear grupo</button>
        </div>
        <div class="row g-5 g-xl-10">
            <div class="col-xl-4">
                <div class="card card-flush shadow-sm p-4">
                    <livewire:comunicacion.conversacion-index />
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card card-flush shadow-sm p-4">
                    <livewire:comunicacion.chat-ventana />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNuevoChat" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <livewire:comunicacion.nuevo-chat-modal />
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCrearGrupo" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <livewire:comunicacion.crear-grupo-modal />
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('conversacion-seleccionada', () => {
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoChat'))?.hide();
                bootstrap.Modal.getInstance(document.getElementById('modalCrearGrupo'))?.hide();
            });
        });
    </script>
    @endpush
    @push('styles')
        @livewireStyles
    @endpush
    @push('scripts')
        @livewireScripts
    @endpush
</x-default-layout>
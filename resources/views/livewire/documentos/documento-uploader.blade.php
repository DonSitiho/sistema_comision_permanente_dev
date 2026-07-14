<div>
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-success d-flex align-items-center py-4">
            <h3 class="card-title fw-bold text-white fs-3 m-0">Documento</h3>
        </div>

        <div class="card-body p-6">
            <!-- Alerta informativa Azul -->
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4 mb-6">
                <i class="ki-duotone ki-information-5 fs-2tx text-primary me-3">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="d-flex flex-stack flex-grow-1 ">
                    <div class="fw-semibold">
                        <div class="fs-6 text-gray-700">
                            * El documento deberá ser cargado en Formato PDF, DOC o DOCX, no debe exceder los 10 MB de tamaño.
                        </div>
                    </div>
                </div>
            </div>

            <div class="fv-row mb-5">
                <label class="form-label fw-bold text-gray-700 fs-6 mb-2">Subir documento</label>
                <input type="file" 
                       wire:model="archivo" 
                       class="form-control form-control-solid" 
                       accept=".pdf,.doc,.docx" />
                    {{-- accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png --}}
                @error('archivo')
                    <span class="text-danger fs-7 mt-2 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-center mt-6">
                <button type="button" 
                        class="btn btn-primary px-6" 
                        wire:click="subir" 
                        wire:loading.attr="disabled">
                    <span class="indicator-label" wire:loading.remove>Guardar Documento</span>
                    <span class="indicator-progress" wire:loading>
                        Subiendo... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="card card-flush shadow-sm p-6">
        <div id="kt_docs_jkanban_fixed_height" class="kanban-fixed-height" data-kt-jkanban-height="300"></div>
    </div>
</div>
    <script>
        document.addEventListener('livewire:init', () => {
            window.cerrarModalesMetronic = () => {
                // 1. Intentar el cierre limpio por Instancia de JS
                document.querySelectorAll('.modal.show').forEach(modalEl => {
                    let modalInstancia = bootstrap.Modal.getInstance(modalEl);
                    
                    if (!modalInstancia) {
                        modalInstancia = new bootstrap.Modal(modalEl);
                    }
                    
                    if (modalInstancia) {
                        modalInstancia.hide();
                    }
                });

                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.querySelectorAll('.modal.show').forEach(el => {
                        el.classList.remove('show');
                        el.style.display = 'none';
                        el.setAttribute('aria-hidden', 'true');
                        el.removeAttribute('aria-modal');
                        el.removeAttribute('role');
                    });
                    document.body.style.overflow = 'auto';
                    document.body.style.paddingRight = '0px';
                    document.body.classList.remove('modal-open');
                }, 250); // Tiempo de espera para que termine la animación original
            };

            Livewire.on('refresh-listado-convocatorias', window.cerrarModalesMetronic);
            Livewire.on('refreshTable', window.cerrarModalesMetronic);

            Livewire.on('abrir-submodal-seguro', (event) => {
                const targetId = event.targetModal || event[0].targetModal;
                const subModalEl = document.getElementById(targetId);
                
                if (!subModalEl) return;

                const subModal = new bootstrap.Modal(subModalEl);
                subModal.show();

                subModalEl.addEventListener('show.bs.modal', function () {
                    setTimeout(() => {
                        const backdrops = document.querySelectorAll('.modal-backdrop:not(.stacked-adjusted)');
                        if (backdrops.length > 0) {
                            const latestBackdrop = backdrops[backdrops.length - 1];
                            latestBackdrop.style.zIndex = '1055';
                            latestBackdrop.classList.add('stacked-adjusted');
                            subModalEl.style.zIndex = '1056';
                        }
                    }, 0);
                });

                subModalEl.addEventListener('hidden.bs.modal', function () {
                    if (document.querySelector('.modal.show')) {
                        document.body.classList.add('modal-open');
                    }
                });
            });
        });
    </script>
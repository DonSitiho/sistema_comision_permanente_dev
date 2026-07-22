{{-- resources/views/livewire/sesiones/convocatoria-modal.blade.php --}}
<div>
    <div class="p-5">
        @if($this->convocatoria_id)
            <div class="alert alert-light-warning d-flex align-items-center p-0 mb-3">
            
            </div>
        @endif
        <div class="mb-4">
            <label class="form-label fw-bold">Tipo de Convocatoria</label>
            <select wire:model="tipo_conv" class="form-select form-select-solid">
                <option value="">Seleccione tipo de convocatoria</option>
                @foreach($tiposPermitidos as $permitidos => $nombre)
                    <option value="{{ $permitidos }}">{{ $nombre }}</option>
                @endforeach
            </select>
            @error("tipo_conv") 
                <span class="text-danger small">{{ $message }}</span> 
            @enderror
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Título</label> 
            <input wire:model="titulo" type="text" class="form-control" placeholder="Título de la convocatoria" {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}> 
            @error('titulo') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Descripción</label> 
            <textarea wire:model="descripcion" class="form-control" placeholder="Descripción..." {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}></textarea>
            @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4" wire:ignore>
            <label class="form-label fw-bold">Fecha y Hora de la Sesión</label> 
            <div class="position-relative d-flex align-items-center">
                <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4 text-primary">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </i>
                <input type="text" id="fecha_sesion_picker"
                    class="form-control ps-12 {{ $this->convocatoria_id ? 'border-warning' : 'form-control-solid fw-semibold text-gray-800' }}" 
                    placeholder="Selecciona fecha y hora " readonly />
            </div>
            @error('fecha_sesion') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Lugar</label> 
            <input wire:model="lugar" type="text" class="form-control" placeholder="Lugar de la sesión" {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}> 
            @error('lugar') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="text-end pt-4 mt-5">
            @if($this->convocatoria_id)
                <button id="sin_fecha" type="button" wire:click="posponerConvocatoria(true)" class="btn btn-info">
                    Posponer sin especificar la nueva fecha
                </button>
                <button type="button" wire:click="posponerConvocatoria(false)" class="btn btn-warning">
                    Confirmar
                </button>
            @else
                <button type="button" wire:click="submit" class="btn btn-primary">
                    Guardar Convocatoria
                </button>
            @endif
        </div>
    </div>

    <style>
        .flatpickr-day.today {
            background-color: #E8FFF3 !important;
            border-color: #50CD89 !important;
            color: #50CD89 !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
        }
        .flatpickr-day.today:hover {
            background-color: #50CD89 !important;
            color: #FFFFFF !important;
        }
        .flatpickr-time {
            border-top: 1px dashed #E4E6EF !important;
            background: #F9F9F9 !important;
            padding: 8px 0 !important;
            border-radius: 0 0 8px 8px;
        }
        .flatpickr-time input {
            color: #181C32 !important;
            font-weight: 800 !important;
            font-size: 1.2rem !important;
            background-color: #FFFFFF !important;
            border: 1px solid #E4E6EF !important;
            border-radius: 6px !important;
        }
        .flatpickr-time input:focus {
            background-color: #EEF6FF !important;
            border-color: #3E97FF !important;
        }
        .flatpickr-time .flatpickr-time-separator {
            font-weight: 800 !important;
            color: #3E97FF !important;
            font-size: 1.2rem !important;
        }
        .flatpickr-time .arrowUp, .flatpickr-time .arrowDown {
            color: #A1A5B7 !important;
        }
        .flatpickr-time .arrowUp:hover, .flatpickr-time .arrowDown:hover {
            color: #3E97FF !important;
        }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            const v_localeEs = {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
                amPM: ['A.M.', 'P.M.'],
                time24hr: true
            };

            const picker = flatpickr("#fecha_sesion_picker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today", 
                position: "above", 
                locale: v_localeEs,
                onChange: function(selectedDates, dateStr, instance) {
                    const checkDate = selectedDates[0];
                    const today = new Date();
                    
                    if (checkDate && checkDate.toDateString() === today.toDateString()) {
                        instance.set('minTime', today.getHours() + ":" + today.getMinutes());
                    } else {
                        instance.set('minTime', "00:00");
                    }
                    
                    @this.set('fecha_sesion', dateStr);
                }
            });

            Livewire.hook('morph.updated', ({ component }) => {
                if (component.name === 'sesiones.convocatoria-modal') {
                    const valorPHP = @this.get('fecha_sesion');
                    if (picker && picker.setDate) {
                        picker.setDate(valorPHP, false);
                    }
                }
            });

            Livewire.on('swal:alert', (event) => {
                const data = event[0] || event;
                Swal.fire({
                    title: data.title,
                    html: data.text,
                    icon: data.icon,
                    buttonsStyling: false,
                    confirmButtonText: "Entendido",
                    customClass: {
                        confirmButton: "btn btn-primary fw-bold"
                    }
                });
            });
        });
    </script>
</div>
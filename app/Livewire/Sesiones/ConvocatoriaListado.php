<?php
// app/Livewire/Sesiones/ConvocatoriaListado.php
//Muestra el listado existente de convocatorias y permite agregarle la sesión así como visualizar los 
// datos de está una vez que ya le fueron asignado
namespace App\Livewire\Sesiones;

use App\Models\Convocatoria;
use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth; 

class ConvocatoriaListado extends Component
{
    public string $buscar = "";
    public ?Convocatoria $convocatoriaSeleccionada = null;
    public string $alcance = "generales";

    protected $listeners = ['refreshTable' => '$refresh'];
    public function mount(): void
    {
        if (request()->routeIs('mis-convocatorias')) {
            $this->alcance = "propias";
        }
    }
    // Carga la convocatoria junto con su relación 'sesion' para las condicionales del Modal de configuración de sesión
    // Carga la convocatoria y le avisa de forma segura a JS que levante el menú
    public function prepararOpciones(int $id): void
    {
        $this->convocatoriaSeleccionada = Convocatoria::with('sesion')->find($id);
        $this->dispatch('mostrar-menu-opciones');
    }
  
    public function ejecutarConfigurar(int $id): void
    {
        $this->dispatch("convocatoria-creada", id: $id)->to('sesiones.sesion-modal'); 
        $this->dispatch('abrir-submodal-seguro', targetModal: 'kt_modal_1'); // Modal 1
    }

    public function ejecutarVerDatos(int $id): void
    {
        $this->dispatch("convocatoria-creada", id: $id)->to('sesiones.sesion-modal'); 
        $this->dispatch('abrir-submodal-seguro', targetModal: 'kt_modal_2'); // Modal 2
    }

    public function ejecutarPosponer(int $id): void
    {
        $this->dispatch('cargar-convocatoria-a-posponer', id: $id)->to('sesiones.convocatoria-modal');
        $this->dispatch('abrir-submodal-seguro', targetModal: 'kt_modal_3'); // Modal 3
    }
    
    public function ejecutarCancelarSesion(int $id): void
    {
        $this->dispatch('notificar-cancelar-sesion-directa', id: $id)->to('sesiones.sesion-modal');
        $this->dispatch('refresh-listado-convocatorias');
    }

    public function seleccionarConvocatoria(int $id): void
    {
        $this->dispatch("convocatoria-creada", id: $id)->to('sesiones.sesion-modal'); 
    }

    #[On('refreshTable')]
    #[On('refresh-listado-convocatorias')]
    public function limpiarSeleccion(): void
    {
        $this->convocatoriaSeleccionada = null;
    }

    public function render()
    {
       
        $convocatorias = Convocatoria::with('sesion')
            ->when($this->alcance === 'propias', function($query) {
                $query->where('creada_por', Auth::id());
            })
            ->when($this->alcance === 'generales', function($query) {
                $query->where('creada_por', '!=', Auth::id());
            })

            ->when($this->buscar, function($query) {
                $query->where(function($q) {
                    $q->where('folio', 'LIKE', '%' . $this->buscar . '%')
                      ->orWhere('titulo', 'LIKE', '%' . $this->buscar . '%')
                      ->orWhereRaw("DATE_FORMAT(fecha_sesion, '%d/%m/%Y') LIKE ?", ['%' . $this->buscar . '%']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view("livewire.sesiones.convocatoria-listado", [
            'convocatorias' => $convocatorias
        ]);
    }
}
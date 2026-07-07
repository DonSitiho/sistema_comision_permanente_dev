<?php
// app/Livewire/Sesiones/ConvocatoriaListado.php
//Muestra el listado existente de convocatorias y permite agregarle la sesión así como visualizar los 
// datos de está una vez que ya le fueron asignado
namespace App\Livewire\Sesiones;

use App\Models\Convocatoria;
use Livewire\Component;

class ConvocatoriaListado extends Component
{
    public string $buscar = "";

    protected $listeners = ['refreshTable' => '$refresh'];

    public function seleccionarConvocatoria(int $id): void
    {
        $this->dispatch("convocatoria-creada", id: $id)->to('sesiones.sesion-modal'); 
    }

    public function render()
    {
        $convocatorias = Convocatoria::when($this->buscar, function($query) {
                $query->where('folio', 'LIKE', '%' . $this->buscar . '%')
                      ->orWhere('titulo', 'LIKE', '%' . $this->buscar . '%')
                      ->orWhereRaw("DATE_FORMAT(fecha_sesion, '%d/%m/%Y') LIKE ?", ['%' . $this->buscar . '%']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view("livewire.sesiones.convocatoria-listado", [
            'convocatorias' => $convocatorias
        ]);
    }
}
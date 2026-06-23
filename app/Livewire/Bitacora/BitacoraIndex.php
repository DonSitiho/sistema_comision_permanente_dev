<?php
// app/Livewire/Bitacora/BitacoraIndex.php
 
namespace App\Livewire\Bitacora;
 
use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
 
class BitacoraIndex extends Component
{
    use WithPagination;
 
    // Filtros reactivos — Livewire actualiza la tabla en tiempo real
    public string $filtroAccion   = '';
    public string $filtroUsuario  = '';
    public string $filtroDesde    = '';
    public string $filtroHasta    = '';
 
    // Reiniciar paginación al cambiar cualquier filtro
    public function updatingFiltroAccion()   { $this->resetPage(); }
    public function updatingFiltroUsuario()  { $this->resetPage(); }
    public function updatingFiltroDesde()    { $this->resetPage(); }
    public function updatingFiltroHasta()    { $this->resetPage(); }
 
    public function render()
    {
        $query = AuditLog::with('user')
            ->latest('created_at');
 
        if ($this->filtroAccion) {
            $query->deAccion($this->filtroAccion);
        }
        if ($this->filtroUsuario) {
            $query->deUsuario((int) $this->filtroUsuario);
        }
        if ($this->filtroDesde && $this->filtroHasta) {
            $query->entreFechas($this->filtroDesde, $this->filtroHasta . ' 23:59:59');
        }
 
        return view('livewire.bitacora.bitacora-index', [
            'logs'    => $query->paginate(20),
            'usuarios' => User::orderBy('name')->get(['id', 'name']),
            'acciones' => AuditLog::distinct()->pluck('accion')->sort()->values(),
        ]);
    }
}

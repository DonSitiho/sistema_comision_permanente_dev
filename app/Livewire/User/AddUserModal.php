<?php
// app/Livewire/User/AddUserModal.php
 
namespace App\Livewire\User;
 
use App\Models\Dependencia;
use App\Models\User;
use App\Services\AuditService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
 
class AddUserModal extends Component
{
    use WithFileUploads;
 
    // Propiedades del formulario
    public ?int    $user_id      = null;
    public string  $name         = '';
    public string  $email        = '';
    public string  $cargo        = '';
    public ?int    $dependencia_id = null;
    public string  $role         = '';
    public         $avatar       = null;
    public ?string $saved_avatar = null;
    public bool    $edit_mode    = false;
 
    // rules() como método para poder usar $this->user_id dinámicamente
    protected function rules(): array
    {
        return [
            'name'           => 'required|string|max:200',
            // unique ignora el propio registro en edición
            'email'          => 'required|email|unique:users,email,' . ($this->user_id ?? 'NULL'),
            'cargo'          => 'nullable|string|max:150',
            'dependencia_id'  => 'nullable|exists:dependencias,id',
            'role'           => 'required|string',
            'avatar'         => 'nullable|sometimes|image|max:2048',
        ];
    }
 
    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'new_user'    => 'hydrate',
    ];
 
    public function render()
    {
        return view('livewire.user.add-user-modal', [
            'roles'        => Role::orderBy('name')->get(),
            // Solo dependencias activas para el select
            'dependencias' => Dependencia::activas()->orderBy('nombre')->get(),
        ]);
    }
 
    public function submit(): void
    {
        $this->validate();
 
        DB::transaction(function () {
            $data = [
                'name'          => $this->name,
                'email'         => $this->email,
                'cargo'         => $this->cargo ?: null,
                'dependencia_id' => $this->dependencia_id,
            ];
 
            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            }
 
            if ($this->edit_mode) {
                $user = User::findOrFail($this->user_id);
                $user->update($data);
                $user->syncRoles($this->role);
                // AuditService no se llama aquí: el UserObserver lo hace automáticamente
                $this->dispatch('success', __('Usuario actualizado correctamente.'));
 
            } else {
                // Contraseña temporal = email hasheado.
                // El usuario la cambia con el link de activación.
                $data['password'] = Hash::make($this->email);
                $user = User::create($data);
                $user->assignRole($this->role);
 
                // Enviar correo para que el usuario establezca su contraseña real
                Password::sendResetLink($user->only('email'));
 
                $this->dispatch('success', __('Usuario creado. Se envió correo de activación.'));
            }
        });
 
        $this->reset();
    }
 
    public function deleteUser(int $id): void
    {
        if ($id === Auth::id()) {
            $this->dispatch('error', 'No puedes desactivar tu propio usuario.');
            return;
        }
 
        $user = User::findOrFail($id);
 
        // Desactivar en lugar de eliminar (preserva historial y bitácora)
        $user->update(['is_active' => false]);
        $user->syncRoles([]);
 
        // Auditoría manual porque desactivar no es un "deleted" de Eloquent
        AuditService::log('deactivated', 'users', $id, [
            'name'  => $user->name,
            'email' => $user->email,
        ]);
 
        $this->dispatch('success', 'Usuario desactivado correctamente.');
    }
 
    public function updateUser(int $id): void
    {
        $this->edit_mode = true;
        $user = User::with('roles', 'dependencia')->findOrFail($id);
 
        $this->fill([
            'user_id'        => $user->id,
            'saved_avatar'   => $user->profile_photo_url,
            'name'           => $user->name,
            'email'          => $user->email,
            'cargo'          => $user->cargo ?? '',
            'dependencia_id'  => $user->dependencia_id,
            'role'           => $user->roles->first()?->name ?? '',
        ]);
    }
 
    public function hydrate(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
    }
}
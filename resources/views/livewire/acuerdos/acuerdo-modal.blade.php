<div>
    <form wire:submit="submit">
        <div class="mb-4">
            <label class="form-label">Descripción del acuerdo</label>
            <textarea wire:model="descripcion" class="form-control" rows="4"
                placeholder="Describe el acuerdo tomado en la sesión..."></textarea>
            @error('descripcion')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar acuerdo
        </button>
    </form>
</div>
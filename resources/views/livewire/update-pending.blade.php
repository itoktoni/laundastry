<td data-label="Packing" class="border rounded bg-light">

<div class="form-group input-group {{ $errors->has('qty') ? 'has-error' : '' }}">
  <input type="number" wire:model="qty" name="qty[{{ $id }}][bersih]" class="form-control" placeholder="Qty">
  <div class="input-group-append">
    <button class="btn btn-dark" wire:click="updateQty" type="button">Bayar</button>
  </div>
</div>

    @if ($message)
        <div class="mt-2">
            <div class="alert alert-{{ $status == 'success' ? 'success' : 'danger' }} alert-sm">
                <i class="ti-{{ $status == 'success' ? 'check' : 'close' }}"></i>
                {{ $message }}
            </div>
        </div>
    @endif

    @error('kotorId')
        <div class="text-danger mt-1">
            <small>{{ $message }}</small>
        </div>
    @enderror

    @error('qty')
        <div class="text-danger mt-1">
            <small>{{ $message }}</small>
        </div>
    @enderror
</td>

<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)
                    
                <x-form-input col="6" name="jenis_id" />
                <x-form-input col="6" name="jenis_nama" />
                <x-form-input col="6" name="jenis_code_customer" />
                <x-form-input col="6" name="jenis_harga" />
                <x-form-input col="6" name="jenis_fee" />
                <x-form-input col="6" name="jenis_total" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

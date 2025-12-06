<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select col="4" id="customer" name="jenis_code_customer" label="Customer" :options="$customer" />
                <x-form-input col="6" name="jenis_nama" />
                <x-form-input col="2" name="jenis_harga" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

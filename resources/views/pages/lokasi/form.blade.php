<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)


                <x-form-select id="customer" :default="request()->get('customer') ?? $model->lokasi_code_customer ?? null" col="6" id="customer" name="lokasi_code_customer" label="Customer" :options="$customer" />
                <x-form-input col="6" name="lokasi_nama" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

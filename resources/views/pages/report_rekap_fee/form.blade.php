<x-layout>
    <x-form :model="$model" method="GET" target="_blank" action="{{ moduleRoute('getPrint') }}" :upload="true">
        <x-card>
            <x-action form="print" />

            @bind($model)

                <x-form-select col="6" default="{{ request('customer') ?? null }}" name="customer_code" id="customer" label="Customer" :options="$customer" />

                <x-form-input col="3" type="date" label="Tanggal Awal" name="start_date" />
                <x-form-input col="3" type="date" label="Tanggal Akhir" name="end_date" />

            @endbind

        </x-card>
    </x-form>

    <x-scriptcustomer/>

</x-layout>

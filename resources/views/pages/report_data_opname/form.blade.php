<x-layout>
    <x-form :model="$model" method="GET" target="_blank" action="{{ moduleRoute('getPrint') }}" :upload="true">
        <x-card>
            <x-action form="print" />

            @bind($model)

                <x-form-select col="6" default="{{ request('customer') ?? null }}" name="opname" id="customer" label="Opname" :options="$opname" />
                <x-form-select col="6" id="jenis" name="jenis_id" label="Jenis" :options="$jenis" />

            @endbind

        </x-card>
    </x-form>

        <x-scriptcustomer/>

</x-layout>

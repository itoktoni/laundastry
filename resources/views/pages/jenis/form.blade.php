<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select id="customer" :default="request()->get('customer') ?? ''" col="6" id="customer" name="jenis_code_customer" label="Customer" :options="$customer" />
                <x-form-input col="6" name="jenis_nama" />
                <x-form-input col="3" name="jenis_harga" />
                <x-form-input col="3" type="number" step="any" min="0" name="jenis_berat" label="Berat Kg"/>
                <x-form-select col="3" name="jenis_type" label="Type" :options="['RENTAL' => 'Rental','CUCI' => 'Cuci']" />

                @if (auth()->user()->role == RoleType::Developer)
                <x-form-input col="3" name="jenis_fee" />
                @endif

                @endbind
            </div>

        </x-card>
    </x-form>

    <x-scriptcustomertable />

</x-layout>

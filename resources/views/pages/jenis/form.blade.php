<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select col="3" id="customer" name="jenis_code_customer" label="Customer" :options="$customer" />
                <x-form-input col="3" name="jenis_nama" />
                <x-form-input col="2" name="jenis_harga" />
                <x-form-select col="2" name="jenis_type" label="Type" :options="['RENTAL' => 'Rental','CUCI' => 'Cuci']" />

                @if (auth()->user()->role == RoleType::Developer)
                <x-form-input col="2" name="jenis_fee" />
                @endif

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

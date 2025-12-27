<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select col="6" id="customer" name="opname_code_customer" label="Customer" :options="$customer" />
                <x-form-input col="3" type="date" value="{{ date('Y-m-d') }}" name="opname_mulai" />
                <x-form-input col="3" type="date" name="opname_selesai" />

                <x-form-select col="6" name="opname_status" label="Status" :options="$status" />
                <x-form-textarea col="6" name="opname_nama" label="Keterangan"/>

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

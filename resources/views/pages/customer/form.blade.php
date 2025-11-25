<x-layout>
    <x-form :model="$model" :upload="true">
        <x-card>
            <x-action form="form"/>

            <div class="row">
                @bind($model)

                <x-form-input col="6" name="customer_code" />
                <x-form-input col="6" name="customer_nama" />
                <x-form-input col="6" name="customer_alamat" />

                <x-form-upload col="3" name="image" />
                <div class="col-md-3">
                    <img class="mt-4 img-thumbnail img-fluid"
                        src="{{ imageUrl($model->customer_logo, 'customer') }}"
                        alt="background">
                </div>

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

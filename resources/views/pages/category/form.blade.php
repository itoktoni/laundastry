<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="3" name="category_code" />
                <x-form-input col="3" name="category_nama" />
                <x-form-input type="color" col="2" name="category_warna" />
                <x-form-textarea col="4" name="category_keterangan" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

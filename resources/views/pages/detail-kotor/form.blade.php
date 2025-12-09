<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="6" name="kotor_id" />
                <x-form-input col="6" name="kotor_code_scan" />
                <x-form-input col="6" name="kotor_code_packing" />
                <x-form-input col="6" name="kotor_code_bersih" />
                <x-form-input col="6" name="kotor_code_customer" />
                <x-form-input col="6" name="kotor_code_category" />
                <x-form-input col="6" name="kotor_id_jenis" />
                <x-form-input col="6" name="kotor_scan" />
                <x-form-input col="6" name="kotor_qc" />
                <x-form-input col="6" name="kotor_bersih" />
                <x-form-input col="6" name="kotor_pending" />
                <x-form-input col="6" name="kotor_tanggal" />
                <x-form-input col="6" name="kotor_created_at" />
                <x-form-input col="6" name="kotor_created_by" />
                <x-form-input col="6" name="kotor_updated_at" />
                <x-form-input col="6" name="kotor_updated_by" />
                <x-form-input col="6" name="kotor_deleted_at" />
                <x-form-input col="6" name="kotor_deleted_by" />
                <x-form-input col="6" name="kotor_qc_at" />
                <x-form-input col="6" name="kotor_qc_by" />
                <x-form-input col="6" name="kotor_bersih_at" />
                <x-form-input col="6" name="kotor_bersih_by" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>

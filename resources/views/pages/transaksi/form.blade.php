<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

                @bind($model)

                <x-form-select col="4" id="customer" default="{{ request()->get('customer') ?? $model->customer_code ?? null }}" name="customer_code" label="Customer" :options="$customer" />
                <x-form-select col="2" name="transaksi_status" default="{{ $model->transaksi_code_category ?? 'NORMAL' }}" label="Category" :options="$category" />
                <div class=" form-group col-md-6 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                    <input type="hidden" name="type" value="{{ $type }}">
                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 4%" class="checkbox-column">No.</th>
                                <th style="width: 60%">Jenis Linen</th>
                                <th style="width: 10%" class="text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                            @php

                            $qty = 0;
                            $transaksi_id = null;

                            if(isset($detail)){
                                $kotor = $detail->firstWhere('transaksi_id_jenis', $key);
                                $qty = $kotor ? $kotor->transaksi_scan : 0;
                                $transaksi_id = $kotor ? $kotor->transaksi_id : null;
                            }

                            @endphp

                                <tr>

                                    <input type="hidden" name="qty[{{ $key }}][jenis_id]"
                                        value="{{ $key ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>
                                    <td data-label="Qty">
                                        <input class="form-control text-right" type="number" min="0" value="{{ $qty ?? null }}"
                                            name="qty[{{ $key }}][scan]" />
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>

                @endbind

        </x-card>
    </x-form>

    <x-scriptcustomertable />

</x-layout>

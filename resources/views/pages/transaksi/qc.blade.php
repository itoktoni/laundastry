<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

                @bind($model)

                <div class=" form-group col-md-3 ">
                    <label for="auto_id_filter">Customer</label>
                    <input type="hidden" name="customer_code" value="{{ $model->customer_code }}">
                    <input class="form-control" type="text" readonly value="{{ $model->customer_nama ?? '' }}">
                </div>

                <x-form-select col="2" name="transaksi_status" default="{{ $model->transaksi_code_category ?? 'NORMAL' }}"  label="Category" :options="$category" />

                <div class=" form-group col-md-6 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                    <input type="hidden" name="type" value="{{ $type }}">

                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px" class="checkbox-column">No.</th>
                                <th>Jenis Linen</th>
                                <th style="width: 100px" class="text-center">Kotor</th>
                                <th style="width: 100px" class="text-center">QC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                            @php
                            $transaksi = $detail->firstWhere('transaksi_id_jenis', $key);
                            $qty_scan = $transaksi ? $transaksi->transaksi_scan : 0;
                            $qty_qc = $transaksi ? $transaksi->transaksi_qc : 0;
                            $transaksi_id = $transaksi ? $transaksi->transaksi_id : null;

                            @endphp

                                <tr>
                                    <input type="hidden" name="qty[{{ $key }}][jenis_id]"
                                        value="{{ $key ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>
                                    <td class="text-center" data-label="Kotor">
                                        <input type="hidden" name="qty[{{ $key }}][scan]"
                                        value="{{ $qty_scan ?? null }}" />

                                        {{ $qty_scan }}
                                    </td>

                                    <td data-label="Qty">
                                        <input class="form-control text-center" type="number" min="0" value="{{ $qty_qc ?? null }}"
                                            name="qty[{{ $key }}][qc]" />
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

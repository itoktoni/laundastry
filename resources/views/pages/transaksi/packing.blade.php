<x-layout>
    <x-form :model="$model">
        <x-card :label="'Packing '. $model->transaksi_code">
            <x-action form="blank">
                <x-button :href="moduleRoute('getPrintBersih', ['code' => $model->transaksi_code])" color="success" label="Print Delivery" />
            </x-action>

                @bind($model)

                 <div class=" form-group col-md-3 ">
                    <label for="auto_id_filter">Customer</label>
                    <input type="hidden" name="customer_code" value="{{ $model->customer_code }}">
                    <input class="form-control" type="text" readonly value="{{ $model->customer_nama ?? '' }}">
                </div>

                <div class=" form-group col-md-3 ">
                    <label for="auto_id_filter">Tanggal</label>
                    <input class="form-control" type="text" readonly value="{{ $model->transaksi_tanggal }}">
                </div>
                <div class=" form-group col-md-6 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                    <input type="hidden" name="type" value="{{ $type }}">

                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%" class="checkbox-column">No.</th>
                                <th style="width: 60%">Jenis Linen</th>
                                <th style="width: 10%" class="text-center">Kotor</th>
                                <th style="width: 15%" class="text-center">Packing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                            @php
                            $transaksi = $detail->firstWhere('transaksi_id_jenis', $key);
                            $qty_scan = $transaksi ? $transaksi->transaksi_scan : 0;
                            $qty_qc = $transaksi ? $transaksi->transaksi_qc : 0;
                            $qty_bersih = $transaksi ? $transaksi->qty_bersih : 0;

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

                                         <input type="hidden" name="qty[{{ $key }}][qc]"
                                        value="{{ $qty_qc ?? null }}" />

                                        {{ $qty_qc }}
                                    </td>

                                    <livewire:update-qty :kotorId="$transaksi_id" :id="$key"/>

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

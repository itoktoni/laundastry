<x-layout>
    <x-form :model="$model">
        <x-card :label="'Packing '. $model->kotor_code">
            <x-action form="form">
                    <x-button :href="moduleRoute('getPrintBersih', ['code' => $model->kotor_code])" color="danger" label="Print Delivery" />
            </x-action>

                @bind($model)

                 <div class=" form-group col-md-3 ">
                    <label for="auto_id_filter">Customer</label>
                    <input type="hidden" name="customer_code" value="{{ $model->customer_code }}">
                    <input class="form-control" type="text" readonly value="{{ $model->customer_nama ?? '' }}">
                </div>

                <div class=" form-group col-md-3 ">
                    <label for="auto_id_filter">Tanggal</label>
                    <input class="form-control" type="text" readonly value="{{ $model->kotor_tanggal }}">
                </div>
                <div class=" form-group col-md-6 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
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
                            $kotor = $detail->firstWhere('kotor_id_jenis', $key);
                            $qty_scan = $kotor ? $kotor->kotor_scan : 0;
                            $qty_qc = $kotor ? $kotor->kotor_qc : 0;
                            $qty_bersih = $kotor ? $kotor->qty_bersih : 0;

                            $kotor_id = $kotor ? $kotor->kotor_id : null;

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

                                    <livewire:update-qty :kotorId="$kotor_id" :id="$key"/>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                @endbind

        </x-card>
    </x-form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const customerSelect = document.getElementById('customer');
            if (customerSelect) {
                customerSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const currentUrl = new URL(window.location);

                    if (selectedValue) {
                        currentUrl.searchParams.set('customer', selectedValue);
                    } else {
                        currentUrl.searchParams.delete('customer');
                    }

                    window.location.href = currentUrl.toString();
                });
            }


            const searchInput = document.querySelector('.search');
            const tableRows = document.querySelectorAll('.table tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    tableRows.forEach(function(row) {
                        const linenCell = row.querySelector('td[data-label="Linen"]');
                        if (linenCell) {
                            const linenText = linenCell.textContent.toLowerCase().trim();
                            if (searchTerm === '' || linenText.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
</x-layout>

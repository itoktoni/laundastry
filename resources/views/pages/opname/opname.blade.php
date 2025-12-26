<x-layout>
    <x-form :model="$model">
        <x-card label="Opname">
            <x-action form="blank">
            </x-action>

                @bind($model)

                <x-form-select col="4" id="customer" default="{{ request()->get('customer') ?? $model->opname_code_customer ?? null }}" name="customer_code" label="Customer" :options="$customer" />
                <x-form-input col="4" id="tanggal" name="tanggal" value="{{ formatDate($model->opname_mulai).' - '.formatDate($model->opname_selesai) }}" label="Tanggal Opname"/>

                <div class=" form-group col-md-4 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%" class="checkbox-column">No.</th>
                                <th style="width: 60%">Jenis Linen</th>
                                <th style="width: 10%" class="text-center">Register</th>
                                <th style="width: 15%" class="text-center">Opname</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                            @php
                            $transaksi = $detail->firstWhere('odetail_id_jenis', $key);
                            $qty_register = $transaksi ? $transaksi->odetail_register : 0;

                            $transaksi_id = $transaksi ? $transaksi->odetail_id : null;

                            @endphp

                                <tr>
                                    <input type="hidden" name="qty[{{ $key }}][jenis_id]"
                                        value="{{ $key ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>

                                    <td class="text-center" data-label="Kotor">
                                        <input type="hidden" name="qty[{{ $key }}][qc]"
                                        value="{{ $qty_register ?? null }}" />

                                        {{ $qty_register }}
                                    </td>

                                    <livewire:update-opname :transaksiID="$transaksi_id" :id="$key"/>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center">*</td>
                                <td>Total Summary Opname</td>
                                <td class="text-center">{{ $transaksi->sum('odetail_register') }}</td>
                                <td>
                                    <input type="text" value="{{ $transaksi->sum('odetail_ketemu') }}" class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                @endbind

        </x-card>
    </x-form>

     <script>
        document.addEventListener('DOMContentLoaded', function() {

            const customerSelect = document.getElementById('customer');
            const tanggal = document.getElementById('tanggal');

            if (customerSelect) {
                customerSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const currentUrl = new URL(window.location);

                    if (selectedValue) {
                        currentUrl.searchParams.set('customer', selectedValue);
                    } else {
                        currentUrl.searchParams.delete('customer');
                    }

                    // Add tanggal parameter if it exists
                    if (tanggal && tanggal.value) {
                        currentUrl.searchParams.set('tanggal', tanggal.value);
                    }

                    window.location.href = currentUrl.toString();
                });
            }

            if (tanggal) {
                tanggal.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const currentUrl = new URL(window.location);

                    if (selectedValue) {
                        currentUrl.searchParams.set('tanggal', selectedValue);
                    } else {
                        currentUrl.searchParams.delete('tanggal');
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

<x-layout>
    <x-form :model="$model">
        <x-card label="Pending">
            <x-action form="blank">
            </x-action>

                @bind($model)

                <x-form-select col="4" id="customer" default="{{ request()->get('customer') ?? $model->transaksi_code_customer ?? null }}" name="customer_code" label="Customer" :options="$customer" />
                <x-form-input col="3" id="tanggal" default="{{ request()->get('tanggal') ?? $model->transaksi_bersih_at->format('Y-m-d') ??  date('Y-m-d') }}" name="tanggal" label="Tanggal Pending" type="date"/>

                <div class=" form-group col-md-5 ">
                    <label for="auto_id_filter">Filter Jenis Linen</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px" class="checkbox-column">No.</th>
                                <th>Jenis Linen</th>
                                <th style="width: 100px" class="text-center">Pending</th>
                                <th style="width: 170px" class="text-center">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenis as $key => $value)
                            @php
                            $transaksi = $detail->firstWhere('transaksi_id_jenis', $key);
                            $qty_pending = $transaksi ? $transaksi->transaksi_pending : 0;
                            $qty_bayar = $transaksi ? $transaksi->transaksi_bayar : 0;

                            $transaksi_id = $transaksi ? $transaksi->transaksi_id : null;

                            @endphp

                                @if ($qty_pending > 0)

                                <tr>
                                    <input type="hidden" name="qty[{{ $key }}][jenis_id]"
                                        value="{{ $key ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Linen">{{ $value }}</td>

                                    <td class="text-center" data-label="Kotor">
                                        <input type="hidden" name="qty[{{ $key }}][qc]"
                                        value="{{ $qty_pending ?? null }}" />

                                        {{ $qty_pending }}
                                    </td>

                                    <livewire:update-pending :transaksiID="$transaksi_id" :id="$key"/>

                                </tr>

                                @endif

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

                    history.pushState(null, '', currentUrl.toString());

                    // Reload the page to update jenis options
                    window.location.reload();
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

                    history.pushState(null, '', currentUrl.toString());

                    // Reload the page to update jenis options
                    window.location.reload();

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
